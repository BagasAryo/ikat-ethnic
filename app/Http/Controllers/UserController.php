<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function profile()
  {
    $user = Auth::user();
    $orders = Order::where('user_id', $user->id)->with('orderItems.product.images')->orderBy('created_at', 'asc')->get();
    return view('pages.profile', compact('user', 'orders'));
  }
}
