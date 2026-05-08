<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\RoundBlockSizeMode;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderByRaw('CAST(table_number AS UNSIGNED) ASC')
            ->paginate(20);
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $table = Table::create($validated);

        // Generate QR Code
        $this->generateQrCode($table);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja ' . $table->table_number . ' berhasil ditambahkan!');
    }

    public function destroy(Table $table)
    {
        if ($table->qr_code_path) {
            Storage::disk('public')->delete($table->qr_code_path);
        }

        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus!');
    }

    public function regenerateQr(Table $table)
    {
        $this->generateQrCode($table);

        return back()->with('success', 'QR Code meja ' . $table->table_number . ' berhasil di-generate ulang!');
    }

    public function toggleActive(Table $table)
    {
        $table->update(['is_active' => !$table->is_active]);

        return back()->with('success', 'Status meja berhasil diubah!');
    }

    public function downloadQr(Table $table)
    {
        if (!$table->qr_code_path || !Storage::disk('public')->exists($table->qr_code_path)) {
            return back()->with('error', 'QR Code belum tersedia.');
        }

        return Storage::disk('public')->download(
            $table->qr_code_path,
            'qrcode-meja-' . $table->table_number . '.png'
        );
    }

    public function bulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|integer|min:1|max:50',
            'to' => 'required|integer|min:1|max:50|gte:from',
        ]);

        $created = 0;
        $generated = 0;
        for ($i = $validated['from']; $i <= $validated['to']; $i++) {
            $existing = Table::where('table_number', (string) $i)->first();
            if (!$existing) {
                $table = Table::create([
                    'table_number' => (string) $i,
                    'is_active' => true,
                ]);
                $this->generateQrCode($table);
                $created++;
                $generated++;
            } elseif (!$existing->qr_code_path || !Storage::disk('public')->exists($existing->qr_code_path)) {
                $this->generateQrCode($existing);
                $generated++;
            }
        }

        $msg = '';
        if ($created > 0) $msg .= $created . ' meja baru ditambahkan. ';
        if ($generated > 0) $msg .= $generated . ' QR Code berhasil di-generate!';
        if ($msg === '') $msg = 'Semua meja sudah memiliki QR Code.';

        return redirect()->route('admin.tables.index')
            ->with('success', $msg);
    }

    private function generateQrCode(Table $table): void
    {
        $url = url('/order?table=' . $table->table_number);

        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(26, 15, 10),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Store to public disk
        $directory = 'qrcodes';
        Storage::disk('public')->makeDirectory($directory);
        $filename = $directory . '/table-' . $table->table_number . '.png';
        Storage::disk('public')->put($filename, $result->getString());

        $table->update(['qr_code_path' => $filename]);
    }
}
