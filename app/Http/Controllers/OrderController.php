<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success($orderCode)
    {
        $order = Order::with('items.product')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $tableNumber = session('table_number', $order->table_number);

        return view('customer.order.success', compact('order', 'tableNumber'));
    }
}
