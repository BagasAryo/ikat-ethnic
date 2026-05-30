<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

  public function showOrder(string $id)
  {
    $user = Auth::user();
    $order = Order::where('user_id', $user->id)
      ->with(['orderItems.product.images', 'orderItems.size', 'payment'])
      ->findOrFail($id);
    return view('pages.user.order-detail', compact('user', 'order'));
  }

  public function editProfile()
  {
    $user = Auth::user();
    return view('pages.user.edit-profile', compact('user'));
  }

  public function updateProfile(Request $request)
  {
    $user = Auth::user();

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'no_hp' => 'nullable|string|max:20',
      'alamat' => 'nullable|string|max:1000',
    ]);

    $user->update($validated);

    return redirect()->route('profile')->with('success', 'Profile updated successfully.');
  }

  public function updatePassword(Request $request)
  {
    $user = Auth::user();

    $validated = $request->validate([
      'current_password' => 'required|current_password',
      'password' => ['required', 'confirmed', Password::defaults()],
    ]);

    $user->update([
      'password' => Hash::make($validated['password']),
    ]);

    return redirect()->route('profile')->with('success', 'Password updated successfully.');
  }
}

