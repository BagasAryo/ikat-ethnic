@extends('layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')
@section('meta-description', 'Laporan penjualan dan transaksi Ikat Ethnic')

@section('content')

  @php
    $statusMap = [
      'Completed'  => ['label' => 'Selesai',    'color' => 'bg-success',   'text' => 'text-success',   'border' => 'border-success/20',   'bg' => 'bg-success/10'],
      'Processing' => ['label' => 'Diproses',   'color' => 'bg-gold',      'text' => 'text-gold',      'border' => 'border-gold/20',      'bg' => 'bg-gold/10'],
      'Shipped'    => ['label' => 'Dikirim',    'color' => 'bg-blue-400',  'text' => 'text-blue-400',  'border' => 'border-blue-500/20',  'bg' => 'bg-blue-500/10'],
      'Pending'    => ['label' => 'Pending',    'color' => 'bg-danger',    'text' => 'text-danger',    'border' => 'border-danger/20',    'bg' => 'bg-danger/10'],
      'Cancelled'  => ['label' => 'Dibatalkan', 'color' => 'bg-faint',     'text' => 'text-faint',     'border' => 'border-black/10',     'bg' => 'bg-surface2'],
    ];

    $orderStatusMeta = function ($status) {
      $st = strtolower($status);
      if (in_array($st, ['completed', 'selesai'])) {
        return ['label' => 'Selesai', 'class' => 'bg-success/10 text-success border-success/20', 'dot' => 'bg-success'];
      } elseif (in_array($st, ['processing', 'diproses'])) {
        return ['label' => 'Diproses', 'class' => 'bg-gold/10 text-gold border-gold/20', 'dot' => 'bg-gold'];
      } elseif ($st === 'shipped') {
        return ['label' => 'Dikirim', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'dot' => 'bg-blue-400'];
      } elseif (in_array($st, ['cancelled', 'dibatalkan'])) {
        return ['label' => 'Dibatalkan', 'class' => 'bg-rose-500/10 text-rose-400 border-rose-500/20', 'dot' => 'bg-rose-400'];
      }
      return ['label' => 'Pending', 'class' => 'bg-danger/10 text-danger border-danger/20', 'dot' => 'bg-danger'];
    };
  @endphp

  {{-- ── Page Header ──────────────────────────────────────────────── --}}
  <div class="flex items-start justify-between gap-4 mb-8">
    <div class="main-w-0">
      <h1 class="text-lg lg:text-xl font-semibold text-ink tracking-wide">Laporan</h1>
      <p class="text-muted text-xs lg:text-sm mt-0.5">
        Periode: <span class="text-gold">{{ $dateFrom->isoFormat('D MMMM Y') }}</span>
        — <span class="text-gold">{{ $dateTo->isoFormat('D MMMM Y') }}</span>
      </p>
    </div>
    <a href="{{ route('admin.reports.export', ['date_from' => request('date_from', $dateFrom->format('Y-m-d')), 'date_to' => request('date_to', $dateTo->format('Y-m-d'))]) }}"
      class="flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-ink text-xs font-semibold px-4 py-2.5 sm:py-2 rounded-sm transition-colors duration-150 shrink-0 cursor-pointer">
      <i data-feather="download-cloud" class="w-4 h-4"></i>
      <span class="sm:hidden">Export</span>
      <span class="hidden sm:inline">Export Excel</span>
    </a>
  </div>

  {{-- ── Filter Form ──────────────────────────────────────────────── --}}
  <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}"
      class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-4">

      {{-- Date From & To: side by side even on mobile to save vertical space --}}
      <div class="flex items-end gap-3">
        <div class="flex flex-col gap-1.5 flex-1 sm:flex-none">
          <label class="text-faint text-[10px] uppercase tracking-[0.15em] font-semibold">Dari Tanggal</label>
          <input type="date" id="date_from" name="date_from"
            value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}"
            class="bg-surface2 border border-black/10 rounded-sm text-ink text-xs px-3 py-2 outline-none w-full sm:w-auto
                   focus:border-gold/40 focus:ring-1 focus:ring-gold/20 transition-all cursor-pointer">
        </div>

        <div class="flex flex-col gap-1.5 flex-1 sm:flex-none">
          <label class="text-faint text-[10px] uppercase tracking-[0.15em] font-semibold">Hingga Tanggal</label>
          <input type="date" id="date_to" name="date_to"
            value="{{ request('date_to', $dateTo->format('Y-m-d')) }}"
            class="bg-surface2 border border-black/10 rounded-sm text-ink text-xs px-3 py-2 outline-none w-full sm:w-auto
                   focus:border-gold/40 focus:ring-1 focus:ring-gold/20 transition-all cursor-pointer">
        </div>
      </div>

      {{-- Quick Filters: horizontal scroll on mobile instead of wrap --}}
      <div class="flex flex-col gap-1.5 min-w-0">
        <label class="text-faint text-[10px] uppercase tracking-[0.15em] font-semibold">Cepat</label>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 sm:flex-wrap -mx-1 px-1 sm:mx-0 sm:px-0">
          @foreach ([
            ['label' => 'Hari Ini',    'from' => now()->format('Y-m-d'),              'to' => now()->format('Y-m-d')],
            ['label' => '7 Hari',      'from' => now()->subDays(6)->format('Y-m-d'),  'to' => now()->format('Y-m-d')],
            ['label' => 'Bulan Ini',   'from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->format('Y-m-d')],
            ['label' => 'Bulan Lalu',  'from' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'to' => now()->subMonth()->endOfMonth()->format('Y-m-d')],
            ['label' => '3 Bulan',     'from' => now()->subMonths(2)->startOfMonth()->format('Y-m-d'), 'to' => now()->format('Y-m-d')],
          ] as $preset)
            <a href="{{ route('admin.reports.index', ['date_from' => $preset['from'], 'date_to' => $preset['to']]) }}"
              class="text-[10px] px-2.5 py-1.5 rounded-sm border transition-all duration-150 whitespace-nowrap shrink-0
                     {{ request('date_from') === $preset['from'] && request('date_to') === $preset['to']
                          ? 'bg-gold text-white border-gold font-semibold'
                          : 'bg-surface2 text-muted border-black/10 hover:border-gold/30 hover:text-gold' }}">
              {{ $preset['label'] }}
            </a>
          @endforeach
        </div>
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="flex items-center justify-center gap-2 bg-gold text-white text-xs font-semibold px-4 py-2.5 sm:py-2 rounded-sm
               hover:bg-gold/90 transition-colors duration-150 shrink-0 w-full sm:w-auto">
        <i data-feather="filter" class="w-3.5 h-3.5"></i>
        Filter
      </button>
    </form>
  </div>

  {{-- ── Summary Cards ─────────────────────────────────────────────── --}}
  <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 mb-6">

    <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 hover:border-gold/20 transition-colors duration-300">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="shopping-bag" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight">{{ $totalOrders }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Total Order</p>
    </div>

    <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 hover:border-gold/20 transition-colors duration-300">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="dollar-sign" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight truncate">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Total Revenue</p>
    </div>

    <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 hover:border-gold/20 transition-colors duration-300">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="box" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight">{{ $totalItemsSold }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Item Terjual</p>
    </div>

    <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 hover:border-gold/20 transition-colors duration-300">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="user-plus" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight">{{ $totalNewUsers }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">User Baru</p>
    </div>

  </div>

  {{-- ── Chart + Status Breakdown ──────────────────────────────────── --}}
  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Revenue/Order Chart --}}
    <div class="xl:col-span-2 bg-surface border border-black/10 rounded-sm p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
          <h2 class="text-sm font-semibold text-ink tracking-wide">Tren Penjualan</h2>
          <p class="text-faint text-xs mt-0.5">Revenue & jumlah order per periode</p>
        </div>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-gold inline-block"></span>
            <span class="text-faint text-xs">Revenue</span>
          </div>
          <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-400 inline-block"></span>
            <span class="text-faint text-xs">Order</span>
          </div>
        </div>
      </div>
      <div class="relative h-56 sm:h-64">
        <canvas id="reportChart"></canvas>
      </div>
    </div>

    {{-- Status Breakdown --}}
    <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-6">
      <h2 class="text-sm font-semibold text-ink tracking-wide mb-5">Status Order</h2>

      <div class="space-y-3">
        @forelse ($statusBreakdown as $row)
          @php $map = $statusMap[$row->status] ?? ['label' => $row->status, 'color' => 'bg-faint', 'text' => 'text-faint', 'border' => 'border-black/10', 'bg' => 'bg-surface2']; @endphp
          <div class="p-3 rounded-sm {{ $map['bg'] }} border {{ $map['border'] }}">
            <div class="flex items-center justify-between mb-1.5">
              <span class="text-xs font-medium {{ $map['text'] }}">{{ $map['label'] }}</span>
              <span class="text-[10px] text-faint">{{ $row->total }} order</span>
            </div>
            <p class="text-ink text-xs font-semibold">Rp{{ number_format($row->revenue, 0, ',', '.') }}</p>
            @php $pct = $totalOrders > 0 ? round(($row->total / $totalOrders) * 100) : 0; @endphp
            <div class="mt-2 h-1 bg-black/20 rounded-full overflow-hidden">
              <div class="h-full {{ $map['color'] }} rounded-full" style="width: {{ $pct }}%"></div>
            </div>
          </div>
        @empty
          <div class="py-8 text-center">
            <p class="text-faint text-xs">Tidak ada data</p>
          </div>
        @endforelse
      </div>
    </div>

  </div>

  {{-- ── Top Products ──────────────────────────────────────────────── --}}
  @if ($topProducts->isNotEmpty())
    <div class="bg-surface border border-black/10 rounded-sm mb-6">
      <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-black/10">
        <div>
          <h2 class="text-sm font-semibold text-ink tracking-wide">Produk Terlaris</h2>
          <p class="text-faint text-xs mt-0.5">Top 5 produk di periode ini</p>
        </div>
        <div class="w-7 h-7 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center shrink-0">
          <i data-feather="award" class="w-3.5 h-3.5 text-gold"></i>
        </div>
      </div>

      {{-- MOBILE (< md): card list --}}
      <div class="md:hidden">
        @foreach ($topProducts as $idx => $item)
          @php $product = $item->product; $thumb = $product?->images?->first(); @endphp
          <div class="flex items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-b border-black/10' : '' }}">
            <span class="text-[11px] font-bold w-5 shrink-0 {{ $idx === 0 ? 'text-gold' : 'text-faint' }}">#{{ $idx + 1 }}</span>
            @if ($thumb)
              <img src="{{ asset('storage/' . $thumb->image_url) }}" alt="{{ $product->name }}"
                class="w-9 h-9 rounded-sm object-cover border border-black/10 shrink-0">
            @else
              <div class="w-9 h-9 rounded-sm bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                <i data-feather="image" class="w-3.5 h-3.5 text-faint"></i>
              </div>
            @endif
            <div class="flex-1 min-w-0">
              <p class="text-ink text-xs font-medium truncate">{{ $product->name ?? '(Produk dihapus)' }}</p>
              <p class="text-faint text-[10px] mt-0.5">{{ $item->total_sold }} item terjual</p>
            </div>
            <span class="text-gold text-xs font-semibold shrink-0">Rp{{ number_format($item->total_revenue, 0, ',', '.') }}</span>
          </div>
        @endforeach
      </div>

      {{-- DESKTOP (>= md): table --}}
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-black/10">
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">#</th>
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Produk</th>
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Terjual</th>
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Revenue</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5">
            @foreach ($topProducts as $idx => $item)
              @php $product = $item->product; $thumb = $product?->images?->first(); @endphp
              <tr class="hover:bg-surface2/40 transition-colors">
                <td class="px-6 py-3.5">
                  <span class="text-[11px] font-bold {{ $idx === 0 ? 'text-gold' : 'text-faint' }}">#{{ $idx + 1 }}</span>
                </td>
                <td class="px-6 py-3.5">
                  <div class="flex items-center gap-2.5">
                    @if ($thumb)
                      <img src="{{ asset('storage/' . $thumb->image_url) }}" alt="{{ $product->name }}"
                        class="w-8 h-8 rounded-sm object-cover border border-black/10 shrink-0">
                    @else
                      <div class="w-8 h-8 rounded-sm bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                        <i data-feather="image" class="w-3.5 h-3.5 text-faint"></i>
                      </div>
                    @endif
                    <span class="text-ink text-xs font-medium">{{ $product->name ?? '(Produk dihapus)' }}</span>
                  </div>
                </td>
                <td class="px-6 py-3.5">
                  <span class="text-ink text-xs font-semibold">{{ $item->total_sold }}</span>
                  <span class="text-faint text-[10px] ml-1">item</span>
                </td>
                <td class="px-6 py-3.5">
                  <span class="text-gold text-xs font-semibold">Rp{{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- ── Orders Table ──────────────────────────────────────────────── --}}
  <div class="bg-surface border border-black/10 rounded-sm">
    <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-black/10">
      <div>
        <h2 class="text-sm font-semibold text-ink tracking-wide">Daftar Transaksi</h2>
        <p class="text-faint text-xs mt-0.5">{{ $orders->count() }} transaksi dalam periode ini</p>
      </div>
    </div>

    {{-- MOBILE (< md): card list --}}
    <div class="md:hidden">
      @forelse ($orders as $order)
        @php $meta = $orderStatusMeta($order->status); @endphp
        <div class="px-4 py-4 {{ !$loop->last ? 'border-b border-black/10' : '' }}">
          <div class="flex items-center justify-between gap-2 mb-2">
            <div class="flex items-center gap-2 min-w-0">
              <div class="w-6 h-6 rounded-full bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                <span class="text-[9px] font-semibold text-muted">{{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}</span>
              </div>
              <span class="text-ink text-xs font-medium truncate">{{ $order->user->name ?? '-' }}</span>
            </div>
            <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full border shrink-0 {{ $meta['class'] }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $meta['label'] }}
            </span>
          </div>
          <div class="flex items-center justify-between">
            <div>
              <span class="text-gold text-[11px] font-mono font-medium block">{{ $order->order_number }}</span>
              <span class="text-faint text-[10px]">{{ $order->created_at->isoFormat('D MMM Y, HH:mm') }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-ink text-xs font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
              <a href="{{ route('admin.orders.show', $order->id) }}"
                class="inline-flex items-center gap-1 text-[10px] font-medium px-2 py-1.5 rounded-sm
                       bg-surface2 border border-black/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150 shrink-0">
                <i data-feather="eye" class="w-3 h-3"></i>
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="px-6 py-16 text-center">
          <i data-feather="inbox" class="w-10 h-10 text-faint mx-auto mb-3"></i>
          <p class="text-faint text-sm">Tidak ada transaksi dalam periode ini</p>
        </div>
      @endforelse
    </div>

    {{-- DESKTOP (>= md): table --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-black/10">
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Order ID</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Pelanggan</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Tanggal</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Total</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Status</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($orders as $order)
            @php $meta = $orderStatusMeta($order->status); @endphp
            <tr class="group hover:bg-surface2/50 transition-colors duration-150">
              <td class="px-6 py-4">
                <span class="text-gold text-xs font-mono font-medium">{{ $order->order_number }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-7 h-7 rounded-full bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                    <span class="text-[10px] font-semibold text-muted">{{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}</span>
                  </div>
                  <span class="text-ink text-xs font-medium">{{ $order->user->name ?? '-' }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-faint text-xs">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-ink text-xs font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full border {{ $meta['class'] }}">
                  <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $meta['label'] }}
                </span>
              </td>
              <td class="px-6 py-4">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                  class="inline-flex items-center gap-1.5 text-[10px] font-medium px-2.5 py-1.5 rounded-sm
                         bg-surface2 border border-black/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150">
                  <i data-feather="eye" class="w-3 h-3"></i> Lihat
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-16 text-center">
                <i data-feather="inbox" class="w-10 h-10 text-faint mx-auto mb-3"></i>
                <p class="text-faint text-sm">Tidak ada transaksi dalam periode ini</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Chart.defaults.color = '#8a8279';
      Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
      Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
      Chart.defaults.font.size = 11;

      const chartData = {!! json_encode($chartData) !!};
      const labels   = chartData.map(d => d.label);
      const revenues = chartData.map(d => d.revenue);
      const orders   = chartData.map(d => d.orders);
      const isMobile = window.innerWidth < 640;

      const ctx = document.getElementById('reportChart').getContext('2d');

      const goldGrad = ctx.createLinearGradient(0, 0, 0, 256);
      goldGrad.addColorStop(0, 'rgba(212,175,55,0.25)');
      goldGrad.addColorStop(1, 'rgba(212,175,55,0.00)');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [
            {
              label: 'Revenue',
              data: revenues,
              backgroundColor: goldGrad,
              borderColor: '#d4af37',
              borderWidth: 1.5,
              borderRadius: 3,
              yAxisID: 'yRevenue',
              order: 2,
            },
            {
              label: 'Order',
              data: orders,
              type: 'line',
              borderColor: 'rgba(96,165,250,0.9)',
              backgroundColor: 'transparent',
              borderWidth: 2,
              pointBackgroundColor: 'rgba(96,165,250,0.9)',
              pointBorderColor: '#151515',
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6,
              tension: 0.4,
              yAxisID: 'yOrders',
              order: 1,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e1e1e',
              borderColor: 'rgba(212,175,55,0.3)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 4,
              titleColor: '#f0ece4',
              bodyColor: '#8a8279',
              callbacks: {
                label: ctx => {
                  if (ctx.datasetIndex === 0) return `  Revenue: Rp${Number(ctx.parsed.y).toLocaleString('id-ID')}`;
                  return `  Order: ${ctx.parsed.y}`;
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: {
                color: '#4a4540',
                font: { size: isMobile ? 9 : 10 },
                maxRotation: isMobile ? 60 : 45,
                autoSkip: true,
                maxTicksLimit: isMobile ? 6 : undefined,
              },
            },
            yRevenue: {
              position: 'left',
              beginAtZero: true,
              grid: { color: 'rgba(255,255,255,0.04)' },
              ticks: {
                color: '#4a4540',
                font: { size: 10 },
                padding: 8,
                callback: v => 'Rp' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v >= 1000 ? (v/1000).toFixed(0)+'rb' : v),
              },
            },
            yOrders: {
              position: 'right',
              beginAtZero: true,
              grid: { drawOnChartArea: false },
              ticks: { color: 'rgba(96,165,250,0.7)', font: { size: 10 }, padding: 8, precision: 0 },
            },
          }
        }
      });

      if (typeof feather !== 'undefined') feather.replace();
    });
  </script>
@endpush