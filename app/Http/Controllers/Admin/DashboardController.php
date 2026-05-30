<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $products = Product::all();
        
        // Hitung total pendapatan dari order yang lunas (status paid atau status order Processing/Shipped/Completed)
        $totalRevenue = Order::whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->orWhereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })->sum('total_amount');
        $recentOrders = Order::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('orders', 'products', 'totalRevenue', 'recentOrders'));
    }
}
