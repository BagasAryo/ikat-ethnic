<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        // Eager load images and sizes for each product
        $products = Product::with(['images', 'sizes', 'category'])->get();

        return view('pages.products', compact('products'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['images', 'sizes', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.product-detail', compact('product'));
    }
}
