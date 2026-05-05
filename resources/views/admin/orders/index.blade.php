@extends('layouts.admin')

@section('title', 'Order')
@section('breadcrumb', 'Order')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-xl font-semibold text-ink tracking-wide">Order</h1>
        <p class="text-muted text-sm mt-0.5">Manajemen semua transaksi</p>
    </div>
</div>

<div class="bg-surface border border-white/5 rounded-sm p-16 text-center">
    <i data-feather="shopping-bag" class="w-10 h-10 text-faint mx-auto mb-4"></i>
    <p class="text-muted text-sm">Halaman order dalam pengembangan.</p>
</div>
@endsection
