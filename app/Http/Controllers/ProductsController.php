<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'sizes', 'category']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Category filter
        if ($request->filled('categories')) {
            $categoryIds = $request->input('categories');
            if (!is_array($categoryIds)) {
                $categoryIds = [$categoryIds];
            }
            $query->whereIn('category_id', $categoryIds);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate products (9 per page for a balanced 3-column layout)
        $products = $query->paginate(9)->withQueryString();

        // Get categories with the number of products they contain
        $categories = Category::withCount('products')->get();

        return view('pages.products', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['images', 'sizes', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.product-detail', compact('product'));
    }
}
