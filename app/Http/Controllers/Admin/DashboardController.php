<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'paid')
            ->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalCategories = Category::where('is_active', true)->count();
        $totalTables = Table::where('is_active', true)->count();

        $recentOrders = Order::with('items.product')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue last 7 days
        $weeklyRevenue = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact(
            'todayOrders', 'todayRevenue', 'pendingOrders',
            'totalProducts', 'totalCategories', 'totalTables',
            'recentOrders', 'weeklyRevenue'
        ));
    }
}
