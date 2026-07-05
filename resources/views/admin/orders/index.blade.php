@extends('layouts.admin')

@section('title', 'Order')
@section('breadcrumb', 'Order')

@section('content')
  @php
    $statusColors = [
        'Pending'    => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
        'Processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
        'Shipped'    => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
        'Completed'  => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
        'Cancelled'  => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
    ];
  @endphp

  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Order</h1>
      <p class="text-muted text-sm mt-0.5">Melihat semua transaksi</p>
    </div>
  </div>

  @if ($orders->count() > 0)

    {{-- ── Mobile: Card List ── --}}
    <div class="sm:hidden space-y-3">
      @foreach ($orders as $order)
        <div class="bg-surface border border-white/5 rounded-sm p-4">
          {{-- Row 1: Order ID + Status Pembayaran --}}
          <div class="flex items-center justify-between mb-2">
            <a href="{{ route('admin.orders.show', $order->id) }}"
              class="text-gold/80 hover:text-gold text-sm font-mono font-medium transition-colors">
              {{ $order->order_number }}
            </a>
            {{-- Payment Badge --}}
            @if ($order->payment)
              @if (strtolower($order->payment->status) === 'paid')
                <span class="inline-block bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Paid</span>
              @elseif (strtolower($order->payment->status) === 'pending')
                <span class="inline-block bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Pending</span>
              @else
                <span class="inline-block bg-rose-500/10 text-rose-500 border border-rose-500/20 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">{{ ucfirst($order->payment->status) }}</span>
              @endif
            @else
              <span class="inline-block bg-white/5 text-muted border border-white/10 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Unpaid</span>
            @endif
          </div>

          {{-- Row 2: Customer + Tanggal --}}
          <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-ink font-medium">{{ $order->user->name }}</p>
            <p class="text-xs text-faint">{{ $order->created_at->isoFormat('D MMM Y') }}</p>
          </div>

          {{-- Row 3: Total + Status Order --}}
          <div class="flex items-center justify-between">
            <p class="text-sm text-gold font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
            @if ($order->status == 'Cancelled')
              <span class="{{ $statusColors[$order->status] }} text-[10px] font-semibold uppercase tracking-wider px-2.5 py-0.5 rounded-full border">
                Cancelled
              </span>
            @else
              <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" onchange="confirmStatusChange(this, '{{ $order->status }}')"
                  class="{{ $statusColors[$order->status] ?? 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20' }} text-[10px] font-semibold uppercase tracking-wider px-2.5 py-0.5 rounded-full border cursor-pointer">
                  @foreach (['Pending', 'Processing', 'Shipped', 'Completed'] as $s)
                    <option value="{{ $s }}" style="color: #151515;" {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                  @endforeach
                </select>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    {{-- ── Desktop: Table ── --}}
    <div class="hidden sm:block bg-surface border border-white/5 rounded-sm text-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-white/5 text-left">
            <th class="px-4 py-3 font-medium text-muted">No</th>
            <th class="px-4 py-3 font-medium text-muted">Order ID</th>
            <th class="px-4 py-3 font-medium text-muted">Tanggal</th>
            <th class="px-4 py-3 font-medium text-muted">Nama Customer</th>
            <th class="px-4 py-3 font-medium text-muted">Total Harga</th>
            <th class="px-4 py-3 font-medium text-muted">Status Pembayaran</th>
            <th class="px-4 py-3 font-medium text-muted">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($orders as $order)
            <tr class="hover:bg-white/2">
              <td class="px-4 py-4">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
              <td class="px-4 py-4 font-medium">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                  class="text-gold/80 hover:text-gold transition-colors font-mono">
                  {{ $order->order_number }}
                </a>
              </td>
              <td class="px-4 py-4">{{ $order->created_at->isoFormat('D MMMM Y') }}</td>
              <td class="px-4 py-4">{{ $order->user->name }}</td>
              <td class="px-4 py-4">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
              <td class="px-4 py-4">
                @if ($order->payment)
                  @if (strtolower($order->payment->status) === 'paid')
                    <span class="inline-block bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Paid</span>
                  @elseif (strtolower($order->payment->status) === 'pending')
                    <span class="inline-block bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Pending</span>
                  @else
                    <span class="inline-block bg-rose-500/10 text-rose-500 border border-rose-500/20 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">{{ ucfirst($order->payment->status) }}</span>
                  @endif
                @else
                  <span class="inline-block bg-white/5 text-muted border border-white/10 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Unpaid</span>
                @endif
              </td>
              <td class="px-4 py-4">
                @if ($order->status == 'Cancelled')
                  <span class="{{ $statusColors[$order->status] }} text-xs font-medium px-2.5 py-0.5 rounded-full border">
                    Cancelled
                  </span>
                @else
                  <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" onchange="confirmStatusChange(this, '{{ $order->status }}')"
                      class="{{ $statusColors[$order->status] ?? 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20' }} text-xs font-medium px-2.5 py-0.5 rounded-full border transition-colors cursor-pointer">
                      @foreach (['Pending', 'Processing', 'Shipped', 'Completed'] as $s)
                        <option value="{{ $s }}" style="color: #151515;" {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                      @endforeach
                    </select>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $orders->onEachSide(1)->links() }}
    </div>

  @else
    <div class="bg-surface border border-white/5 rounded-sm p-16 text-center">
      <i data-feather="package" class="w-10 h-10 text-faint mx-auto mb-4"></i>
      <p class="text-muted text-sm">Belum ada order yang masuk.</p>
    </div>
  @endif
@endsection

@push('scripts')
  <script>
    function confirmStatusChange(select, originalValue) {
      const newValue = select.value;
      if (newValue === originalValue) return;

      Swal.fire({
        title: 'Ubah Status Order?',
        text: `Apakah Anda yakin ingin mengubah status menjadi "${newValue}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d4af37',
        cancelButtonColor: '#4a4540',
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        background: '#151515',
        color: '#f0ece4',
      }).then((result) => {
        if (result.isConfirmed) {
          select.closest('form').submit();
        } else {
          select.value = originalValue;
        }
      });
    }
  </script>
@endpush