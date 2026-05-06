@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Tambah Kategori</h1>
      <p class="text-muted text-sm mt-0.5">Tambah kategori produk</p>
    </div>
  </div>

  <div class="bg-surface border border-white/5 rounded-sm overflow-hidden">
    <form action="{{ route('admin.categories.store') }}" method="POST">
      @csrf
      @method('POST')
      <div class="px-6 py-6">
        <div>
          <label for="name" class="block text-sm font-medium text-muted mb-2">Nama Kategori</label>
          <input type="text" name="name" id="name"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            required>
          @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="flex items-center gap-2 px-6 py-4 border-t border-white/5">
        <a href="{{ route('admin.categories.index') }}"
          class="bg-surface2 hover:bg-white/5 text-muted text-sm font-medium px-4 py-2.5 rounded-sm transition-colors">
          Kembali
        </a>
        <button type="submit"
          class="bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2.5 rounded-sm transition-colors cursor-pointer">
          Tambah Kategori
        </button>
      </div>
    </form>
  </div>
@endsection