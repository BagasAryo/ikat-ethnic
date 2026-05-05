<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;

class CategoryController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.categories.index', compact('kategoris'));
    }
}
