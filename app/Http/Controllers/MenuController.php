<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('table');

        if (!$tableNumber) {
            return view('customer.invalid-table', ['message' => 'Nomor meja tidak ditemukan. Silakan scan QR Code di meja Anda.']);
        }

        $table = Table::where('table_number', $tableNumber)->where('is_active', true)->first();

        if (!$table) {
            return view('customer.invalid-table', ['message' => 'Meja tidak valid atau sedang tidak aktif.']);
        }

        // Store table in session
        session(['table_number' => $tableNumber]);

        $categories = Category::where('is_active', true)
            ->whereHas('activeProducts')
            ->with(['activeProducts' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('customer.menu.index', compact('categories', 'tableNumber'));
    }
}
