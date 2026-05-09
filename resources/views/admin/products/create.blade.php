@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('breadcrumb', 'Produk')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Tambah Product</h1>
      <p class="text-muted text-sm mt-0.5">Tambah Product</p>
    </div>
  </div>

  <div class="bg-surface border border-white/5 rounded-sm">
    <form action="{{ route('admin.products.store') }}" method="POST">
      @csrf
      @method('POST')
      <div class="px-6 py-6">
        <div class="form-group mb-4">
          <label for="name" class="block text-sm font-medium text-muted mb-2">Nama Produk</label>
          <input type="text"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            id="name" name="name" required>
          @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group mb-4">
          <label for="description" class="block text-sm font-medium text-muted mb-2">Deskripsi</label>
          <textarea
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            id="description" name="description" rows="3"></textarea>
          @error('description')
            <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group mb-4">
          <label for="price" class="block text-sm font-medium text-muted mb-2">Harga</label>
          <input type="number"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            id="price" name="price" required>
          @error('price')
            <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group mb-4">
          <label for="stock" class="block text-sm font-medium text-muted mb-2">Stok</label>
          <input type="number"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            id="stock" name="stock" required>
          @error('stock')
            <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group mb-4">
          <label for="kategori_id" class="block text-sm font-medium text-muted mb-2">Kategori</label>
          <select
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            id="kategori_id" name="kategori_id" required>
            @foreach ($kategoris as $kategori)
              <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
            @endforeach
          </select>
          @error('kategori_id')
            <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="flex items-center gap-2 px-6 py-4 border-t border-white/5">
        <a href="{{ route('admin.products.index') }}"
          class="bg-surface2 hover:bg-white/5 text-muted text-sm font-medium px-4 py-2.5 rounded-sm transition-colors">
          Kembali
        </a>
        <button type="submit"
          class="bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2.5 rounded-sm transition-colors cursor-pointer">
          Tambah Produk
        </button>
      </div>
    </form>
  </div>
@endsection
