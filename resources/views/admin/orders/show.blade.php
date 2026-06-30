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
      'Pending'    => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
      'Processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
      'Shipped'    => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
      'Completed'  => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
      'Cancelled'  => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
    ];
  @endphp
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Detail Order</h1>
      <p class="text-muted text-sm mt-0.5">Menampilkan detail order #{{ $order->order_number }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="text-xs bg-white/5 hover:bg-white/10 text-ink border border-white/10 px-3 py-1.5 rounded-sm transition-all flex items-center gap-1.5">
      <i data-feather="arrow-left" class="w-3.5 h-3.5"></i> Kembali
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left: Order Summary & Info -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-surface border border-white/5 rounded-sm p-6 space-y-6">
        <h2 class="text-md font-semibold text-ink border-b border-white/5 pb-3">Informasi Order</h2>
        
        <div>
          <h3 class="text-xs font-semibold text-muted tracking-wider uppercase">Order ID</h3>
          <p class="text-ink font-medium mt-1">{{ $order->order_number }}</p>
        </div>

        <div>
          <h3 class="text-xs font-semibold text-muted tracking-wider uppercase">Tanggal Pesanan Dibuat</h3>
          <p class="text-ink mt-1">{{ $order->created_at->format('H:i:s - d M Y') }}</p>
        </div>

        <div>
          <h3 class="text-xs font-semibold text-muted tracking-wider uppercase">Nama Customer</h3>
          <p class="text-ink mt-1">{{ $order->user->name }}</p>
          <p class="text-muted text-xs">{{ $order->user->email }}</p>
        </div>

        <div>
          <h3 class="text-xs font-semibold text-muted tracking-wider uppercase">Status Pembayaran</h3>
          <p class="text-ink mt-1 flex items-center gap-1.5 text-xs font-medium">
            @if($order->payment)
              @if(strtolower($order->payment->status) === 'paid')
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Paid ({{ strtoupper($order->payment->payment_method) }})
              @elseif(strtolower($order->payment->status) === 'pending')
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Pending ({{ strtoupper($order->payment->payment_method) }})
              @else
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                {{ ucfirst($order->payment->status) }} ({{ strtoupper($order->payment->payment_method) }})
              @endif
            @else
              <span class="w-2 h-2 rounded-full bg-zinc-500"></span>
              Unpaid
            @endif
          </p>
        </div>

        <div>
          <h3 class="text-xs font-semibold text-muted tracking-wider uppercase mb-2">Status Transaksi</h3>
          <span class="{{ $statusColors[$order->status] ?? 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20' }} text-xs font-medium px-2.5 py-1 rounded-full border">
            {{ $order->status }}
          </span>
        </div>

        <div class="pt-4 border-t border-white/5">
          <h3 class="text-xs font-semibold text-muted tracking-wider uppercase">Informasi Pengiriman</h3>
          <p class="text-ink font-medium mt-1">{{ $order->shipping_name }} ({{ $order->shipping_phone }})</p>
          <p class="text-muted text-xs mt-1">{{ $order->shipping_district_name ? $order->shipping_district_name . ', ' : '' }}{{ $order->shipping_city_name }}, {{ $order->shipping_province }}</p>
          <p class="text-muted text-xs mt-1">{{ $order->shipping_address }}</p>
          <div class="mt-2 text-xs text-muted">
            <span class="font-medium text-ink">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</span>
            @if($order->shipping_etd) (Est. {{ $order->shipping_etd }}) @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Items Purchased -->
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-surface border border-white/5 rounded-sm p-6">
        <h2 class="text-md font-semibold text-ink border-b border-white/5 pb-3 mb-4">Product yang Dipesan</h2>
        
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="border-b border-white/5 text-muted">
                <th class="py-3 font-medium">No</th>
                <th class="py-3 font-medium">Product</th>
                <th class="py-3 font-medium text-center">Ukuran</th>
                <th class="py-3 font-medium text-center">Jumlah</th>
                <th class="py-3 font-medium text-right">Harga Satuan</th>
                <th class="py-3 font-medium text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
              @foreach ($order->orderItems as $item)
                <tr>
                  <td class="py-4 text-muted">{{ $loop->iteration }}</td>
                  <td class="py-4">
                    <div class="flex items-center gap-3">
                      @if($item->product && $item->product->images->where('is_thumbnail', true)->first())
                        <img src="{{ asset('storage/' . $item->product->images->where('is_thumbnail', true)->first()->image_url) }}" 
                             alt="{{ $item->product_name }}" 
                             class="w-10 h-10 object-cover rounded border border-white/10"
                             onerror="this.src='https://images.pexels.com/photos/37838554/pexels-photo-37838554.jpeg'">
                      @else
                        <div class="w-10 h-10 bg-white/5 rounded border border-white/10 flex items-center justify-center">
                          <i data-feather="image" class="w-4 h-4 text-muted"></i>
                        </div>
                      @endif
                      <div>
                        <span class="font-medium text-ink block">{{ $item->product_name }}</span>
                        <span class="text-xs text-muted">ID: {{ $item->product_id }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 text-center">
                    <span class="inline-block bg-white/5 px-2 py-0.5 rounded text-xs text-ink font-medium border border-white/5">
                      {{ $item->product_size }}
                    </span>
                  </td>
                  <td class="py-4 text-center text-ink font-medium">{{ $item->quantity }}</td>
                  <td class="py-4 text-right text-ink">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                  <td class="py-4 text-right text-gold font-medium">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-6 border-t border-white/5 pt-6 flex flex-col items-end">
          <div class="w-full sm:w-80 space-y-3">
            <div class="flex justify-between text-sm text-muted">
              <span>Subtotal Product</span>
              <span class="text-ink">Rp{{ number_format($order->orderItems->sum('subtotal'), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-muted">
              <span>Biaya Pengiriman</span>
              <span class="text-ink">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-base font-semibold border-t border-white/5 pt-3">
              <span class="text-ink">Total Bayar</span>
              <span class="text-gold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
