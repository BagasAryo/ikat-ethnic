<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'sizes', 'images'])->orderBy("id", "asc")->paginate(5);
        return view("admin.products.index", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view("admin.products.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:products,name",
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "category_id" => "required|exists:categories,id",
            "sizes" => "required|array|min:1",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,gif,webp|max:2048",
        ]);
        $validated["slug"] = Str::slug($request->name);

        $product = Product::create([
            "name" => $validated["name"],
            "slug" => $validated["slug"],
            "description" => $validated["description"],
            "price" => $validated["price"],
            "category_id" => $validated["category_id"],
        ]);

        foreach ($request->sizes as $size) {
            $product->sizes()->create([
                'name' => $size['name'],
                'stock' => $size['stock'],
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $fileName = Str::slug($request->name) . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('products', $fileName, 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_thumbnail' => $index === 0,
                ]);
            }
        }

        return redirect()->route("admin.products.index")->with("success", "Product berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $product = Product::with(['sizes', 'images'])->findOrFail($id);
        return view("admin.products.edit", compact("product", "categories") );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            "name" => "required|string|unique:products,name," . $id,
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "category_id" => "required|exists:categories,id",
            "sizes" => "required|array|min:1",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,webp,gif|max:2048",
        ]);
        $validated["slug"] = Str::slug($request->name);

        $product->update([
            "name" => $validated["name"],
            "slug" => $validated["slug"],
            "description" => $validated["description"],
            "price" => $validated["price"],
            "kategori_id" => $validated["kategori_id"],
        ]);

        $product->sizes()->delete();
        foreach ($request->sizes as $size) {
            $product->sizes()->create([
                'name' => $size['name'],
                'stock' => $size['stock'],
            ]);
        }

        if ($request->hasFile('images')) {
            $existingImagesCount = $product->images()->count();
            foreach ($request->file('images') as $index => $file) {
                $fileName = Str::slug($request->name) . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('products', $fileName, 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_thumbnail' => ($existingImagesCount === 0 && $index === 0),
                ]);
            }
        }

        return redirect()->route("admin.products.index")->with("success", "Product berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        foreach ($product->images as $image) {
            if(Storage::disk('public')->exists($image->image_url)){
                Storage::disk('public')->delete($image->image_url);
            }
        }
        $product->delete();
        return redirect()->route("admin.products.index")->with("success", "Product berhasil dihapus");
    }
}
