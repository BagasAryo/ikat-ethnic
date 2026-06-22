<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // ── Date range defaults: bulan ini ───────────────────────────
        $dateFrom = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();

        $dateTo = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfDay();

        // Swap jika terbalik
        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        // ── Summary Stats ────────────────────────────────────────────
        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        $totalRevenue = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->sum('total_amount');

        $totalNewUsers = User::where('role', 'user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $totalItemsSold = OrderItem::whereHas('order', fn($q) =>
            $q->whereBetween('created_at', [$dateFrom, $dateTo])
              ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
        )->sum('quantity');

        // ── Status Breakdown ─────────────────────────────────────────
        $statusBreakdown = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as total, SUM(total_amount) as revenue')
            ->groupBy('status')
            ->get();

        // ── Orders in Range ──────────────────────────────────────────
        $orders = Order::with(['user', 'orderItems'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        // ── Daily Revenue Chart ──────────────────────────────────────
        $diffDays = $dateFrom->diffInDays($dateTo);

        if ($diffDays <= 31) {
            // Harian
            $chartData = collect();
            $cursor = $dateFrom->copy()->startOfDay();
            while ($cursor->lte($dateTo)) {
                $dayRevenue = $orders
                    ->whereBetween('created_at', [
                        $cursor->copy()->startOfDay(),
                        $cursor->copy()->endOfDay(),
                    ])
                    ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
                    ->sum('total_amount');

                $dayOrders = $orders->filter(fn($o) =>
                    $o->created_at->isSameDay($cursor)
                )->count();

                $chartData->push([
                    'label'   => $cursor->format('d M'),
                    'revenue' => $dayRevenue,
                    'orders'  => $dayOrders,
                ]);
                $cursor->addDay();
            }
        } else {
            // Bulanan
            $chartData = collect();
            $cursor = $dateFrom->copy()->startOfMonth();
            while ($cursor->lte($dateTo)) {
                $monthRevenue = Order::whereYear('created_at', $cursor->year)
                    ->whereMonth('created_at', $cursor->month)
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
                    ->sum('total_amount');

                $monthOrders = Order::whereYear('created_at', $cursor->year)
                    ->whereMonth('created_at', $cursor->month)
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count();

                $chartData->push([
                    'label'   => $cursor->translatedFormat('M Y'),
                    'revenue' => $monthRevenue,
                    'orders'  => $monthOrders,
                ]);
                $cursor->addMonth();
            }
        }

        // ── Top Products in Range ────────────────────────────────────
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', fn($q) =>
                $q->whereBetween('created_at', [$dateFrom, $dateTo])
                  ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with(['product' => fn($q) => $q->with('images')])
            ->get();

        return view('admin.reports.index', compact(
            'dateFrom', 'dateTo',
            'totalOrders', 'totalRevenue', 'totalNewUsers', 'totalItemsSold',
            'statusBreakdown', 'orders', 'chartData', 'topProducts'
        ));
    }

    public function exportExcel(Request $request)
    {
        $dateFrom = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();

        $dateTo = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfDay();

        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $orders = Order::with(['user', 'orderItems', 'payment'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOrders    = $orders->count();
        $totalRevenue   = $orders->whereIn('status', ['Processing', 'Shipped', 'Completed'])->sum('total_amount');
        $totalNewUsers  = User::where('role', 'user')->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $totalItemsSold = OrderItem::whereHas('order', fn($q) =>
            $q->whereBetween('created_at', [$dateFrom, $dateTo])
              ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
        )->sum('quantity');

        $statusBreakdown = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as total, SUM(total_amount) as revenue')
            ->groupBy('status')
            ->get();

        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', fn($q) =>
                $q->whereBetween('created_at', [$dateFrom, $dateTo])
                  ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with('product')
            ->get();

        $fileName = 'Laporan_Penjualan_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new ReportExport(
                $dateFrom, $dateTo,
                $orders, $totalOrders, $totalRevenue,
                $totalItemsSold, $totalNewUsers,
                $statusBreakdown, $topProducts
            ),
            $fileName
        );
    }
}
