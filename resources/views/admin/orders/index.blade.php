@extends('layouts.admin')

@section('title', 'Order')
@section('breadcrumb', 'Order')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Order</h1>
      <p class="text-muted text-sm mt-0.5">Melihat semua transaksi</p>
    </div>
  </div>

  @if ($orders->count() > 0)
    <div class="bg-surface border border-white/5 rounded-sm text-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-white/5 text-left">
            <th class="px-6 py-4 font-medium text-muted">No</th>
            <th class="px-6 py-4 font-medium text-muted">Order ID</th>
            <th class="px-6 py-4 font-medium text-muted">Tanggal</th>
            <th class="px-6 py-4 font-medium text-muted">Nama Customer</th>
            <th class="px-6 py-4 font-medium text-muted">Total Harga</th>
            <th class="px-6 py-4 font-medium text-muted">Status Pembayaran</th>
            <th class="px-6 py-4 font-medium text-muted">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($orders as $order)
            <tr class="border-b border-white/5 hover:bg-white/2">
              <td class="px-6 py-4">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
              <td class="px-6 py-4 font-medium">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                  class="text-gold/80 hover:text-gold transition-colors" title="Lihat Detail Order">
                  {{ $order->order_number }}
                </a>
              </td>
              <td class="px-6 py-4">{{ $order->created_at->format('d M Y') }}</td>
              <td class="px-6 py-4">{{ $order->user->name }}</td>
              <td class="px-6 py-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
              <td class="px-6 py-4">Paid</td>
              <td class="px-6 py-4">
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                  @csrf @method('PATCH')
                  <select name="status" onchange="confirmChange(this)">
                    @foreach (['Pending', 'Processing', 'Shipped', 'Completed'] as $s)
                      <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                      </option>
                    @endforeach
                  </select>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-4">
      {{ $orders->links() }}
    </div>
  @else
    <div class="bg-surface border border-white/5 rounded-sm p-16 text-center">
      <i data-feather="package" class="w-10 h-10 text-faint mx-auto mb-4"></i>
      <p class="text-muted text-sm">Halaman order dalam pengembangan.</p>
    </div>
  @endif
@endsection
