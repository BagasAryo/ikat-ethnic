@extends('layouts.admin')

@section('title', 'Detail Order')
@section('breadcrumb')
  <a href="{{ route('admin.orders.index') }}" class="hover:text-ink transition-colors">Order</a>
  <i data-feather="chevron-right" class="w-3 h-3 mx-1 inline-block"></i>
  <span class="text-muted">Detail Order</span>
@endsection

@section('content')
  {{-- Status Badge Pemetaan Warna --}}
  @php
    $statusColors = [
      'Pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
      'Processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
      'Shipped' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
      'Completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
    ];
  @endphp
  <div class="mb-6">
    <h1 class="text-xl font-semibold text-ink tracking-wide">Detail Order</h1>
    <p class="text-muted text-sm mt-0.5">Menampilkan detail order</p>
  </div>
  <div class="bg-surface border border-white/5 rounded-sm p-6">
    @foreach ($order->orderItems as $item)
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Order ID</h3>
        <p class="text-muted text-sm mt-0.5">{{ $order->order_number }}</p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Tanggal Pesanan Dibuat</h3>
        <p class="text-muted text-sm mt-0.5">{{ $order->created_at->format('H:i:s - d M Y') }}</p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Nama Customer</h3>
        <p class="text-muted text-sm mt-0.5">{{ $order->user->name }}</p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Nama Produk</h3>
        <p class="text-muted text-sm mt-0.5">
          {{ $item->product->name }}
        </p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Jumlah</h3>
        <p class="text-muted text-sm mt-0.5">
          {{ $item->quantity }}
        </p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Total Harga</h3>
        <p class="text-muted text-sm mt-0.5">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Status Pembayaran</h3>
        <p class="text-muted text-sm mt-0.5">Paid sementara</p>
      </div>
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-ink tracking-wide">Status</h3>
        <span class="{{ $statusColors[$order->status] }} text-xs font-medium px-2.5 py-0.5 rounded-full border">
          {{ $order->status }}
        </span>
      </div>
    @endforeach
  </div>
@endsection
