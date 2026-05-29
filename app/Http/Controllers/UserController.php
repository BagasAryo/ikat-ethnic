<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function profile()
  {
    $user = Auth::user();
    $orders = Order::where('user_id', $user->id)
      ->with('orderItems.product.images')
      ->orderBy('created_at', 'desc')
      ->limit(2)
      ->get();
    return view('pages.user.profile', compact('user', 'orders'));
  }

  public function myOrders()
  {
    $user = Auth::user();
    $orders = Order::where('user_id', $user->id)
      ->with(['orderItems.product.images', 'orderItems.size', 'payment'])
      ->orderBy('created_at', 'desc')
      ->get();
    return view('pages.user.orders', compact('user', 'orders'));
  }

  public function showOrder($id)
  {
    $user = Auth::user();
    $order = Order::where('user_id', $user->id)
      ->with(['orderItems.product.images', 'orderItems.size', 'payment'])
      ->findOrFail($id);
    return view('pages.user.order-detail', compact('user', 'order'));
  }
}

