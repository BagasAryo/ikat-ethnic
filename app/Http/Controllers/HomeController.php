<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $products = Product::with('images')->latest()->limit(3)->get();

        return view('pages.home', compact('products'));
    }
}
