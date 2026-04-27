<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary stats
        $totalRevenue   = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalOrders    = Order::count();
        $totalProducts  = Product::where('is_active', true)->count();
        $totalCustomers = User::where('is_admin', false)->count();

        // This month
        $monthRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $monthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Revenue last 7 days
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing days
        $chartDays     = collect();
        $chartRevenues = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartDays->push(now()->subDays($i)->format('M d'));
            $chartRevenues->push($revenueChart->get($date)?->revenue ?? 0);
        }

        // Low stock products
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->take(5)
            ->get();

        $outOfStockCount = Product::where('stock', 0)->count();

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get();

        // Top products by revenue
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.subtotal) as revenue'), DB::raw('SUM(order_items.quantity) as units'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'totalCustomers',
            'monthRevenue', 'monthOrders', 'ordersByStatus',
            'chartDays', 'chartRevenues',
            'lowStockProducts', 'outOfStockCount',
            'recentOrders', 'topProducts'
        ));
    }
}