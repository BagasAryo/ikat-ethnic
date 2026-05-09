<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Produk::orderBy("id","asc")->paginate(10);
        return view("admin.products.index", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::select("id","name");
        $produk = Produk::all();
        return view("admin.products.create", compact("kategoris","produk"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $product = Produk::find($id);
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
            "stock" => "required|integer|min:0",
            "kategori_id" => "required|exists:kategoris,id",
        ]);
        $validated["slug"] = Str::slug($request->name);

        $product->update($validated);
        return redirect()->route("admin.products.index")->with("success", "Produk berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Produk::findOrFail($id);
        $product->delete();
        return redirect()->route("admin.products.index")->with("success", "Produk berhasil dihapus");
    }
}
