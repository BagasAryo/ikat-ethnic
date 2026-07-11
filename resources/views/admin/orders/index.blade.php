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

    {{-- ── Mobile: Card List ── --}}
    <div class="sm:hidden space-y-3">
      @foreach ($orders as $order)
        @php $meta = \App\Helpers\OrderStatus::meta($order->status); @endphp
        <div class="bg-surface border border-black/10 rounded-sm p-4">
          {{-- Row 1: Order ID + Status Pembayaran --}}
          <div class="flex items-center justify-between mb-2">
            <a href="{{ route('admin.orders.show', $order->id) }}"
              class="text-muted hover:text-ink text-sm font-mono font-medium transition-colors">
              {{ $order->order_number }}
            </a>
            {{-- Payment Badge --}}
            @if ($order->payment)
              @if (strtolower($order->payment->status) === 'paid')
                <span
                  class="inline-block bg-emerald-500/10 text-emerald-600 border border-emerald-500/50 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Paid</span>
              @elseif (strtolower($order->payment->status) === 'pending')
                <span
                  class="inline-block bg-amber-500/10 text-amber-600 border border-amber-500/50 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Pending</span>
              @else
                <span
                  class="inline-block bg-rose-500/10 text-rose-600 border border-rose-500/50 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">{{ ucfirst($order->payment->status) }}</span>
              @endif
            @else
              <span
                class="inline-block bg-black/5 text-muted border border-black/10 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Unpaid</span>
            @endif
          </div>

          {{-- Row 2: Customer + Tanggal --}}
          <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-ink font-medium">{{ $order->user->name }}</p>
            <p class="text-xs text-muted">{{ $order->created_at->isoFormat('D MMM Y') }}</p>
          </div>

          {{-- Row 3: Total + Status Order --}}
          <div class="flex items-center justify-between">
            <p class="text-sm text-muted font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
            @if ($order->status == 'Cancelled')
              <span
                class="{{ $meta['fill'] }} text-[10px] font-semibold uppercase tracking-wider px-2.5 py-0.5 rounded-full border">
                {{ $meta['label'] }}
              </span>
            @else
              <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" onchange="confirmStatusChange(this, '{{ $order->status }}')"
                  class="{{ $meta['fill'] }} text-[10px] font-semibold uppercase tracking-wider px-2.5 py-0.5 rounded-full border cursor-pointer">
                  @foreach (['Pending', 'Processing', 'Shipped', 'Completed'] as $s)
                    <option value="{{ $s }}" style="color: #2b2620;"
                      {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                  @endforeach
                </select>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    {{-- ── Desktop: Table ── --}}
    <div class="hidden sm:block bg-surface border border-black/10 rounded-sm text-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-black/10 text-left">
            <th class="px-4 py-3 font-medium text-ink">Order ID</th>
            <th class="px-4 py-3 font-medium text-ink">Tanggal</th>
            <th class="px-4 py-3 font-medium text-ink">Nama Customer</th>
            <th class="px-4 py-3 font-medium text-ink">Total Harga</th>
            <th class="px-4 py-3 font-medium text-ink">Status Pembayaran</th>
            <th class="px-4 py-3 font-medium text-ink">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($orders as $order)
            @php $meta = \App\Helpers\OrderStatus::meta($order->status); @endphp
            <tr class="group border-b border-black/10 hover:bg-surface2/50 transition-colors duration-150">
              <td class="px-4 py-4 font-medium">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                  class="text-muted hover:text-ink transition-colors font-mono">
                  {{ $order->order_number }}
                </a>
              </td>
              <td class="px-4 py-4">{{ $order->created_at->isoFormat('D MMMM Y') }}</td>
              <td class="px-4 py-4">{{ $order->user->name }}</td>
              <td class="px-4 py-4">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
              <td class="px-4 py-4">
                @if ($order->payment)
                  @if (strtolower($order->payment->status) === 'paid')
                    <span
                      class="inline-block bg-emerald-500/10 text-emerald-600 border border-emerald-500/50 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Paid</span>
                  @elseif (strtolower($order->payment->status) === 'pending')
                    <span
                      class="inline-block bg-amber-500/10 text-amber-600 border border-amber-500/50 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Pending</span>
                  @else
                    <span
                      class="inline-block bg-rose-500/10 text-rose-600 border border-rose-500/50 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">{{ ucfirst($order->payment->status) }}</span>
                  @endif
                @else
                  <span
                    class="inline-block bg-black/5 text-muted border border-black/10 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full">Unpaid</span>
                @endif
              </td>
              <td class="px-4 py-4">
                @if ($order->status == 'Cancelled')
                  <span class="{{ $meta['fill'] }} text-xs font-medium px-2.5 py-0.5 rounded-full border">
                    {{ $meta['label'] }}
                  </span>
                @else
                  <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" onchange="confirmStatusChange(this, '{{ $order->status }}')"
                      class="{{ $meta['fill'] }} text-xs font-medium px-2.5 py-0.5 rounded-full border transition-colors cursor-pointer">
                      @foreach (['Pending', 'Processing', 'Shipped', 'Completed'] as $s)
                        <option value="{{ $s }}" style="color: #2b2620;"
                          {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>
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
    <div class="bg-surface border border-black/10 rounded-sm p-16 text-center">
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
        confirmButtonColor: '#8a6a08',
        cancelButtonColor: '#78716c',
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        background: '#FFFFFF',
        color: '#2b2620',
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
