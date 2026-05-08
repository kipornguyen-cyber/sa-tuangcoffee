<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%' . $request->search . '%')
                  ->orWhere('table_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        $pendingCount = Order::where('status', 'pending')->count();
        $paidCount = Order::where('status', 'paid')->count();
        $cancelledCount = Order::where('status', 'cancelled')->count();

        return view('admin.orders.index', compact('orders', 'pendingCount', 'paidCount', 'cancelledCount'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pesanan ' . $order->order_code . ' berhasil diubah menjadi ' . strtoupper($validated['status']) . '!');
    }

    public function printInvoice(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.print', compact('order'));
    }

    public function report(Request $request)
    {
        $query = Order::with('items.product')->where('status', 'paid');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.orders.report', compact('orders'));
    }
}
