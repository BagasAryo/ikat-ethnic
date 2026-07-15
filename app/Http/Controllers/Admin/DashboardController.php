<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // ── Stat Cards ────────────────────────────────────────────────
        $ordersToday   = Order::whereDate('created_at', $today)->count();

        $revenueToday  = Order::whereDate('created_at', $today)
            ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->sum('total_amount');

        $productsTotal = Product::count();

        $usersTotal    = User::where('role', 'user')->count();

        // ── All Orders (untuk donut chart) ────────────────────────────
        $orders = Order::all();

        // ── Recent Orders ─────────────────────────────────────────────
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ── Low Stock Products (stok per ukuran ≤ 5) ──────────────────
        $lowStockProducts = ProductSize::with('product.images')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        // ── Monthly Chart Data — orders only (6 bulan terakhir) ───────
        $monthlyChartData = collect(range(5, 0))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);
            $count = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            return [
                'label'  => $month->translatedFormat('M'),
                'orders' => $count,
            ];
        });

        // ── Weekly Chart Data — orders only (7 hari terakhir) ─────────
        $weeklyChartData = collect(range(6, 0))->map(function ($i) {
            $day   = Carbon::now()->subDays($i);
            $count = Order::whereDate('created_at', $day->toDateString())->count();
            return [
                'label'  => $day->translatedFormat('D'),
                'orders' => $count,
            ];
        });

        // ── Order Status Distribution ──────────────────────────────────
        $orderStatusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'orders', 'recentOrders',
            'lowStockProducts',
            'monthlyChartData', 'weeklyChartData', 'orderStatusCounts',
            'ordersToday', 'revenueToday',
            'productsTotal', 'usersTotal'
        ));
    }
}
