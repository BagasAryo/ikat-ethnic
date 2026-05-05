<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Nanti bisa diganti dengan data asli dari DB
        $stats = [
            'totalOrders'   => 248,
            'totalProducts' => 64,
            'pendingOrders' => 7,
        ];

        return view('admin.dashboard', $stats);
    }
}
