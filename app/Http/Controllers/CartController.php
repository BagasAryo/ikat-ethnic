<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = collect();

        if (Auth::check()) {
            $cart = Auth::user()->cart;
            $cartItems = $cart ? $cart->cartItems()->with('product.images')->get() : collect();
        }

        return view('pages.cart', compact('cartItems'));
    }

    public function destroy(string $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();
        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }
}
