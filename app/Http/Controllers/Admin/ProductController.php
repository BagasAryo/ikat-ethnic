<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
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
        $products = Produk::with(['kategori', 'sizes', 'images'])->orderBy("id", "asc")->paginate(5);
        return view("admin.products.index", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view("admin.products.create", compact("kategoris"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:produks,name",
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "kategori_id" => "required|exists:kategoris,id",
            "sizes" => "required|array|min:1",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);
        $validated["slug"] = Str::slug($request->name);

        $product = Produk::create([
            "name" => $validated["name"],
            "slug" => $validated["slug"],
            "description" => $validated["description"],
            "price" => $validated["price"],
            "kategori_id" => $validated["kategori_id"],
        ]);

        foreach ($request->sizes as $size) {
            $product->sizes()->create([
                'name' => $size['name'],
                'stock' => $size['stock'],
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_thumbnail' => $index === 0,
                ]);
            }
        }

        return redirect()->route("admin.products.index")->with("success", "Produk berhasil ditambahkan");
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
        $kategoris = Kategori::all();
        $product = Produk::with(['sizes', 'images'])->findOrFail($id);
        return view("admin.products.edit", compact("product", "kategoris"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Produk::findOrFail($id);
        $validated = $request->validate([
            "name" => "required|string|unique:produks,name," . $id,
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "kategori_id" => "required|exists:kategoris,id",
            "sizes" => "required|array|min:1",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
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
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_thumbnail' => ($existingImagesCount === 0 && $index === 0),
                ]);
            }
        }

        return redirect()->route("admin.products.index")->with("success", "Produk berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Produk::findOrFail($id);

        foreach ($product->images as $image) {
            if(Storage::disk('public')->exists($image->image_url)){
                Storage::disk('public')->delete($image->image_url);
            }
        }
        $product->delete();
        return redirect()->route("admin.products.index")->with("success", "Produk berhasil dihapus");
    }
}
