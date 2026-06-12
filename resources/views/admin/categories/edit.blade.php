@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('breadcrumb')
  <a href="{{ route('admin.categories.index') }}" class="text-muted hover:text-ink">Kategori</a>
  <i data-feather="chevron-right" class="w-3 h-3 mx-1 inline-block"></i>
  <span class="text-ink">Edit Kategori</span>
@endsection

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Edit Kategori</h1>
      <p class="text-muted text-sm mt-0.5">Edit kategori product</p>
    </div>
  </div>

  <div class="bg-surface border border-white/5 rounded-sm overflow-hidden">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="px-6 py-6">
        <div class="form-group">
          <label for="name" class="block text-sm font-medium text-muted mb-2">Nama Kategori</label>
          <input type="text" name="name" id="name" value="{{ $category->name }}"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors" required>
        </div>
      </div>
      <div class="flex items-center gap-2 px-6 py-4 border-t border-white/5">
        <a href="{{ route('admin.categories.index') }}"
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