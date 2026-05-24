<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $user = Auth::user();
        $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);
        $quantity = $validated['quantity'] ?? 1;

        $cartItem = $cart->cartItems()
            ->where('product_id', $validated['product_id'])
            ->where('product_size_id', $validated['product_size_id'])
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->cartItems()->create([
                'product_id' => $validated['product_id'],
                'product_size_id' => $validated['product_size_id'],
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Product added to cart.');
    }

    public function destroy(string $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();
        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }
}
