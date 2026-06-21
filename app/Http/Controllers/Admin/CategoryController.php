<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->name)
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z][a-zA-Z0-9\s]*$/|unique:categories,name',
            'slug' => 'required|string|unique:categories,slug',
        ], [
            'name.unique' => 'Nama kategori sudah digunakan.',
            'name.regex' => 'Nama kategori hanya boleh mengandung huruf, angka, dan spasi, serta harus diawali dengan huruf.',
            'slug.unique' => 'Slug kategori sudah digunakan.',
        ]);

        try {
            Category::create($validated);
            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori. Silakan coba lagi.');
        }
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
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->merge([
            'slug' => Str::slug($request->name)
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z][a-zA-Z0-9\s]*$/|unique:categories,name,' . $id,
            'slug' => 'required|string|unique:categories,slug,' . $id,
        ], [
            'name.unique' => 'Nama kategori sudah digunakan.',
            'name.regex' => 'Nama kategori hanya boleh mengandung huruf, angka, dan spasi, serta harus diawali dengan huruf.',
            'slug.unique' => 'Slug kategori sudah digunakan.',
        ]);

        try {
            $category->update($validated);
            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate kategori. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Gagal menghapus kategori. Silakan coba lagi.');
        }
    }
}

