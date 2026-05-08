<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $tableNumber = session('table_number');
        if (!$tableNumber) {
            return redirect('/')->with('error', 'Silakan scan QR Code di meja Anda.');
        }

        $cart = session('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('customer.cart.index', compact('cartItems', 'total', 'tableNumber'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => $product->name . ' ditambahkan ke keranjang!',
            'cartCount' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $cart = session('cart', []);

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = ['quantity' => $quantity];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang.',
            'cartCount' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    public function checkout(Request $request)
    {
        $tableNumber = session('table_number');
        if (!$tableNumber) {
            return redirect('/')->with('error', 'Sesi meja tidak ditemukan.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $tableNumber, $request) {
                $totalAmount = 0;
                $orderItems = [];

                foreach ($cart as $productId => $item) {
                    $product = Product::findOrFail($productId);
                    $subtotal = $product->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }

                $order = Order::create([
                    'order_code' => Order::generateOrderCode(),
                    'table_number' => $tableNumber,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'notes' => $request->input('notes', ''),
                ]);

                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                return $order;
            });

            // Clear cart
            session()->forget('cart');

            return redirect()->route('order.success', $order->order_code);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    public function getCount()
    {
        $cart = session('cart', []);
        return response()->json([
            'count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }
}
