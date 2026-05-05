@extends('layouts.admin')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-xl font-semibold text-ink tracking-wide">Kategori</h1>
        <p class="text-muted text-sm mt-0.5">Kelola kategori produk</p>
    </div>
    <button class="flex items-center gap-2 bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2 rounded-sm transition-colors">
        <i data-feather="plus" class="w-4 h-4"></i> Tambah Kategori
    </button>
</div>

<div class="bg-surface border border-white/5 rounded-sm p-16 text-center">
    <i data-feather="tag" class="w-10 h-10 text-faint mx-auto mb-4"></i>
    <p class="text-muted text-sm">Halaman kategori dalam pengembangan.</p>
</div>
@endsection
