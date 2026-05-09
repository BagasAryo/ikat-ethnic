@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('breadcrumb', 'Produk')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Edit Produk</h1>
      <p class="text-muted text-sm mt-0.5">Kelola seluruh produk tenun</p>
    </div>
  </div>
  <div class="bg-surface border border-white/5 rounded-sm overflow-hidden">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="px-6 py-6">
        <div class="form-group">
          <label for="name" class="block text-sm font-medium text-muted mb-2">Nama Produk</label>
          <input type="text" name="name" id="name" value="{{ $product->name }}"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors">
        </div>
        <div class="form-group">
          <label for="description" class="block text-sm font-medium text-muted mb-2">Deskripsi</label>
          <textarea name="description" id="description"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors">{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
          <label for="price" class="block text-sm font-medium text-muted mb-2">Harga</label>
          <input type="number" name="price" id="price" value="{{ $product->price }}"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors" required>
        </div>
        <div class="form-group">
          <label for="stock" class="block text-sm font-medium text-muted mb-2">Stok</label>
          <input type="number" name="stock" id="stock" value="{{ $product->stock }}"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors" required>
        </div>
        <div class="form-group">
          <label for="kategori_id" class="block text-sm font-medium text-muted mb-2">Kategori</label>
          <select name="kategori_id" id="kategori_id"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors" required>
            @foreach ($kategoris as $kategori)
              <option value="{{ $kategori->id }}" {{ $kategori->id == $product->kategori_id ? 'selected' : '' }}>
                {{ $kategori->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="flex items-center gap-2 px-6 py-4 border-t border-white/5">
        <a href="{{ route('admin.products.index') }}"
          class="bg-surface2 hover:bg-white/5 text-muted text-sm font-medium px-4 py-2.5 rounded-sm transition-colors">
          Kembali
        </a>
        <button type="submit"
          class="bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2.5 rounded-sm transition-colors cursor-pointer">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
@endsection
