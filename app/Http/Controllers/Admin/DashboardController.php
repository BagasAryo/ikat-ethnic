<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $orders   = Order::all();
        $products = Product::all();

        // ── Total Revenue ──────────────────────────────────────────────
        $totalRevenue = Order::whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->orWhereHas('payment', fn($q) => $q->where('status', 'paid'))
            ->sum('total_amount');

        // ── Recent Orders ──────────────────────────────────────────────
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ── Users ──────────────────────────────────────────────────────
        $users = User::where('role', 'user')->get();

        // ── Low Stock Products (stok per ukuran ≤ 5) ──────────────────
        $lowStockProducts = ProductSize::with('product.images')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        // ── Monthly Chart Data (6 bulan terakhir) ─────────────────────
        $monthlyChartData = collect(range(5, 0))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);
            $rows  = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->selectRaw('COUNT(*) as total_orders, COALESCE(SUM(total_amount),0) as total_revenue')
                ->first();
            return [
                'label'   => $month->translatedFormat('M'),
                'orders'  => (int) $rows->total_orders,
                'revenue' => (float) $rows->total_revenue,
            ];
        });

        // ── Weekly Chart Data (7 hari terakhir) ───────────────────────
        $weeklyChartData = collect(range(6, 0))->map(function ($i) {
            $day  = Carbon::now()->subDays($i);
            $rows = Order::whereDate('created_at', $day->toDateString())
                ->selectRaw('COUNT(*) as total_orders, COALESCE(SUM(total_amount),0) as total_revenue')
                ->first();
            return [
                'label'   => $day->translatedFormat('D'),
                'orders'  => (int) $rows->total_orders,
                'revenue' => (float) $rows->total_revenue,
            ];
        });

        // ── Order Status Distribution ──────────────────────────────────
        $orderStatusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'orders', 'products', 'totalRevenue', 'recentOrders', 'users',
            'lowStockProducts', 'monthlyChartData', 'weeklyChartData', 'orderStatusCounts'
        ));
    }
}
