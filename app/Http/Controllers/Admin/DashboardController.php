<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
        // ── Helpers ────────────────────────────────────────────────────
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // ── Stat: Total Order Hari Ini ─────────────────────────────────
        $ordersToday     = Order::whereDate('created_at', $today)->count();
        $ordersYesterday = Order::whereDate('created_at', $yesterday)->count();
        $ordersPct       = $this->pctChange($ordersYesterday, $ordersToday);

        // ── Stat: Revenue Hari Ini ─────────────────────────────────────
        $revenueToday     = Order::whereDate('created_at', $today)
            ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->sum('total_amount');
        $revenueYesterday = Order::whereDate('created_at', $yesterday)
            ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->sum('total_amount');
        $revenuePct       = $this->pctChange($revenueYesterday, $revenueToday);

        // ── Stat: Total Product Bulan Ini ──────────────────────────────
        $productsThisMonth = Product::where('created_at', '>=', $thisMonth)->count();
        $productsLastMonth = Product::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $productsPct       = $this->pctChange($productsLastMonth, $productsThisMonth);

        // ── Stat: Total User Bulan Ini ─────────────────────────────────
        $usersThisMonth = User::where('role', 'user')->where('created_at', '>=', $thisMonth)->count();
        $usersLastMonth = User::where('role', 'user')->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $usersPct       = $this->pctChange($usersLastMonth, $usersThisMonth);

        // ── All Orders (untuk donut & summary strip) ───────────────────
        $orders = Order::all();

        // ── Total Revenue (all-time, untuk stat card revenue hari ini label) ─
        $totalRevenue = Order::whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->sum('total_amount');

        // ── Recent Orders ──────────────────────────────────────────────
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ── Users (untuk navigasi, bukan stat card) ────────────────────
        $users = User::where('role', 'user')->get();

        // ── Low Stock Products (stok per ukuran ≤ 5) ──────────────────
        $lowStockProducts = ProductSize::with('product.images')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        // ── Top 5 Best-Selling Products ────────────────────────────────
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with(['product' => fn($q) => $q->with('images')])
            ->get();

        // ── Monthly Chart Data — orders only (6 bulan terakhir) ────────
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

        // ── Weekly Chart Data — orders only (7 hari terakhir) ──────────
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
            'orders', 'totalRevenue', 'recentOrders', 'users',
            'lowStockProducts', 'topProducts',
            'monthlyChartData', 'weeklyChartData', 'orderStatusCounts',
            'ordersToday', 'ordersPct',
            'revenueToday', 'revenuePct',
            'productsThisMonth', 'productsPct',
            'usersThisMonth', 'usersPct'
        ));
    }

    // ── Helper: hitung persentase perubahan ───────────────────────────
    private function pctChange(int $old, int $new): array
    {
        if ($old == 0 && $new == 0) {
            return ['value' => 0, 'direction' => 'flat'];
        }
        if ($old == 0) {
            return ['value' => 100, 'direction' => 'up'];
        }
        $pct = round((($new - $old) / $old) * 100, 1);
        return [
            'value'     => abs($pct),
            'direction' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'),
        ];
    }
}
