@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('meta-description', 'Ringkasan performa toko Ikat Ethnic')

@section('content')
  {{-- ──────────────────────────────────────────────────────
     Page Header
────────────────────────────────────────────────────── --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-lg lg:text-xl font-semibold text-ink tracking-wide">Dashboard</h1>
      <p class="text-muted text-xs lg:text-sm mt-0.5">Selamat datang kembali - <span
          class="text-gold">{{ auth()->user()->name ?? 'Administrator' }}</span></p>
    </div>

  </div>

  {{-- ──────────────────────────────────────────────────────
     Stat Cards
────────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 mb-8">

    {{-- Total Order Hari Ini --}}
    <div
      class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 group hover:border-gold/20 transition-colors duration-300 min-w-0">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="shopping-bag" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight truncate">{{ $ordersToday }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Order Hari Ini</p>
    </div>

    {{-- Revenue Hari Ini --}}
    <div
      class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 group hover:border-gold/20 transition-colors duration-300 min-w-0">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="dollar-sign" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight truncate">
        Rp{{ number_format($revenueToday, 0, ',', '.') }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Revenue Hari Ini</p>
    </div>

    {{-- Total Produk --}}
    <div
      class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 group hover:border-gold/20 transition-colors duration-300 min-w-0">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="package" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight truncate">{{ $productsTotal }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Total Produk</p>
    </div>

    {{-- Total User --}}
    <div
      class="bg-surface border border-black/10 rounded-sm p-4 sm:p-5 group hover:border-gold/20 transition-colors duration-300 min-w-0">
      <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center mb-3 sm:mb-4">
        <i data-feather="users" class="w-4 h-4 text-gold"></i>
      </div>
      <p class="text-lg sm:text-2xl font-semibold text-ink tracking-tight truncate">{{ $usersTotal }}</p>
      <p class="text-muted text-[10px] sm:text-xs mt-1 uppercase tracking-wider">Total User</p>
    </div>

  </div>

  {{-- ──────────────────────────────────────────────────────
     Row 1: Stok Hampir Habis (full width)
────────────────────────────────────────────────────── --}}
  <div class="mb-6">

    {{-- ── Stok Hampir Habis ── --}}
    <div
      class="bg-surface border {{ $lowStockProducts->isNotEmpty() ? 'border-danger/20' : 'border-black/10' }} rounded-sm">
      <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-black/10 gap-3">
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="w-7 h-7 rounded-sm bg-danger/10 border border-danger/20 flex items-center justify-center shrink-0">
            <i data-feather="alert-triangle" class="w-3.5 h-3.5 text-danger"></i>
          </div>
          <div class="min-w-0">
            <h2 class="text-sm font-semibold text-ink tracking-wide">Stok Hampir Habis</h2>
            <p class="text-faint text-xs mt-0.5 truncate">{{ $lowStockProducts->count() }} varian produk perlu restock</p>
          </div>
        </div>
        <a href="{{ route('admin.products.index') }}"
          class="text-xs text-muted hover:text-ink transition-colors flex items-center gap-1 font-medium shrink-0">
          <span class="hidden sm:inline">Kelola Produk</span> <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
      </div>

      @if ($lowStockProducts->isNotEmpty())

        {{-- MOBILE (< md): card list --}}
        <div class="md:hidden">
          @foreach ($lowStockProducts as $size)
            @php $thumb = $size->product->images->first(); @endphp
            <div class="flex items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-b border-black/10' : '' }}">
              @if ($thumb)
                <img src="{{ asset('storage/' . $thumb->image_url) }}" alt="{{ $size->product->name }}"
                  class="w-9 h-9 rounded-sm object-cover border border-black/10 shrink-0">
              @else
                <div
                  class="w-9 h-9 rounded-sm bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                  <i data-feather="image" class="w-3.5 h-3.5 text-faint"></i>
                </div>
              @endif
              <div class="flex-1 min-w-0">
                <a href="{{ route('admin.products.edit', $size->product->id) }}"
                  class="text-ink text-xs font-medium hover:text-gold transition-colors block truncate">{{ $size->product->name }}</a>
                <div class="flex items-center gap-2 mt-1">
                  <span class="text-muted text-[10px] font-mono">{{ strtoupper($size->name) }}</span>
                  <span class="text-faint text-[10px]">•</span>
                  <span class="text-ink text-[10px] font-semibold">Stok: {{ $size->stock }}</span>
                </div>
              </div>
              @if ($size->stock <= 2)
                <span
                  class="inline-flex items-center gap-1 text-[9px] font-semibold uppercase tracking-wider px-2 py-1 rounded-full bg-danger/10 text-danger border border-danger/20 shrink-0">
                  <span class="w-1.5 h-1.5 rounded-full bg-danger animate-pulse"></span>Kritis
                </span>
              @else
                <span
                  class="inline-flex items-center gap-1 text-[9px] font-semibold uppercase tracking-wider px-2 py-1 rounded-full bg-warn/10 text-warn border border-warn/20 shrink-0">
                  <span class="w-1.5 h-1.5 rounded-full bg-warn"></span>Rendah
                </span>
              @endif
            </div>
          @endforeach
        </div>

        {{-- DESKTOP (>= md): table --}}
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-black/10">
                <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Produk
                </th>
                <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Ukuran
                </th>
                <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Stok</th>
                <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Status
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
              @foreach ($lowStockProducts as $size)
                <tr class="group border-b border-black/10 hover:bg-surface2/50 transition-colors duration-150">
                  <td class="px-6 py-3.5">
                    <div class="flex items-center gap-2.5">
                      @php $thumb = $size->product->images->first(); @endphp
                      @if ($thumb)
                        <img src="{{ asset('storage/' . $thumb->image_url) }}" alt="{{ $size->product->name }}"
                          class="w-8 h-8 rounded-sm object-cover border border-black/10 shrink-0">
                      @else
                        <div
                          class="w-8 h-8 rounded-sm bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                          <i data-feather="image" class="w-3.5 h-3.5 text-faint"></i>
                        </div>
                      @endif
                      <a href="{{ route('admin.products.edit', $size->product->id) }}"
                        class="text-ink text-xs font-medium hover:text-gold transition-colors">{{ $size->product->name }}</a>
                    </div>
                  </td>
                  <td class="px-6 py-3.5">
                    <span class="text-muted text-xs font-mono">{{ strtoupper($size->name) }}</span>
                  </td>
                  <td class="px-6 py-3.5">
                    <span class="text-ink text-xs font-semibold">{{ $size->stock }}</span>
                  </td>
                  <td class="px-6 py-3.5">
                    @if ($size->stock <= 2)
                      <span
                        class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-danger/10 text-danger border border-danger/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger animate-pulse"></span>Kritis
                      </span>
                    @else
                      <span
                        class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-warn/10 text-warn border border-warn/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-warn"></span>Rendah
                      </span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="px-6 py-10 text-center">
          <i data-feather="check-circle" class="w-8 h-8 text-success mx-auto mb-2"></i>
          <p class="text-faint text-xs">Semua stok dalam kondisi aman</p>
        </div>
      @endif
    </div>

  </div>

  {{-- ──────────────────────────────────────────────────────
     Row 2: Grafik Order + Status Order
────────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- ── Order Chart (2/3 width) ── --}}
    <div class="xl:col-span-2 bg-surface border border-black/10 rounded-sm p-4 sm:p-6">

      {{-- Chart Header --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
          <h2 class="text-sm font-semibold text-ink tracking-wide">Grafik Order</h2>
          <p class="text-faint text-xs mt-0.5">Jumlah order bulanan dan mingguan</p>
        </div>

        {{-- Legend & Filter --}}
        <div class="flex items-center gap-3 flex-wrap">
          <div class="flex items-center gap-1 bg-surface2 border border-black/10 rounded-sm p-0.5">
            <button class="chart-filter-btn text-[10px] font-medium px-2 py-1 rounded-sm bg-gold text-white active"
              data-period="monthly">Bulanan</button>
            <button
              class="chart-filter-btn text-[10px] font-medium px-2 py-1 rounded-sm text-faint hover:text-muted transition-colors"
              data-period="weekly">Mingguan</button>
          </div>
        </div>
      </div>

      {{-- Canvas --}}
      <div class="relative h-48 sm:h-56">
        <canvas id="orderChart"></canvas>
      </div>

    </div>

    {{-- ── Status Breakdown (1/3 width) ── --}}
    <div class="bg-surface border border-black/10 rounded-sm p-4 sm:p-6 flex flex-col">
      <div class="mb-5">
        <h2 class="text-sm font-semibold text-ink tracking-wide">Status Order</h2>
        <p class="text-faint text-xs mt-0.5">Distribusi semua order</p>
      </div>

      {{-- Donut Chart --}}
      <div class="flex items-center justify-center mb-6">
        <div class="relative w-32 h-32 sm:w-36 sm:h-36">
          <canvas id="statusDonutChart"></canvas>
          @php
            $completedCount = $orderStatusCounts->get('Completed', 0);
            $totalOrders = $orders->count();
            $completedPct = $totalOrders > 0 ? round(($completedCount / $totalOrders) * 100) : 0;
          @endphp
          <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-xl sm:text-2xl font-semibold text-ink">{{ $completedPct }}%</span>
            <span class="text-faint text-[10px] uppercase tracking-wider">Selesai</span>
          </div>
        </div>
      </div>

      {{-- Legend --}}
      <div class="space-y-3 mt-auto">
        @foreach (['Completed', 'Processing', 'Shipped', 'Pending', 'Cancelled'] as $status)
          @php
            $meta = \App\Helpers\OrderStatus::meta($status);
            $count = $orders->where('status', $status)->count();
            $pct = $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0;
          @endphp
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full {{ $meta['dot'] }} shrink-0"></span>
              <span class="text-muted text-xs">{{ $meta['label'] }}</span>
            </div>
            <span class="text-ink text-xs font-medium">{{ $count }}
              <span class="text-faint">({{ $pct }}%)</span>
            </span>
          </div>
        @endforeach
      </div>
    </div>

  </div>

  {{-- ──────────────────────────────────────────────────────
     Row 3: Recent Orders (full width)
────────────────────────────────────────────────────── --}}
  <div class="bg-surface border border-black/10 rounded-sm mb-6">
    <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-black/10 gap-3">
      <div class="min-w-0">
        <h2 class="text-sm font-semibold text-ink tracking-wide">Order Terbaru</h2>
        <p class="text-faint text-xs mt-0.5">5 transaksi terakhir</p>
      </div>
      <a href="{{ route('admin.orders.index') }}"
        class="text-xs text-muted hover:text-ink transition-colors flex items-center gap-1 font-medium shrink-0">
        <span class="hidden sm:inline">Lihat semua</span> <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
      </a>
    </div>

    {{-- MOBILE (< md): card list --}}
    <div class="md:hidden">
      @if ($recentOrders->isEmpty())
        <div class="px-6 py-10 text-center">
          <i data-feather="inbox" class="w-8 h-8 text-faint mx-auto mb-2"></i>
          <p class="text-faint text-xs">Belum ada order masuk</p>
        </div>
      @else
        @foreach ($recentOrders as $order)
          @php $meta = \App\Helpers\OrderStatus::meta($order->status); @endphp
          <div class="px-4 py-4 {{ !$loop->last ? 'border-b border-black/10' : '' }}">
            <div class="flex items-center justify-between gap-2 mb-2">
              <div class="flex items-center gap-2 min-w-0">
                <div
                  class="w-6 h-6 rounded-full bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                  <span
                    class="text-[9px] font-semibold text-muted">{{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}</span>
                </div>
                <span class="text-ink text-xs font-medium truncate">{{ $order->user->name ?? '-' }}</span>
              </div>
              <span
                class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full border shrink-0 {{ $meta['fill'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $meta['label'] }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <div class="min-w-0">
                <span
                  class="text-ink text-[11px] font-mono font-medium block truncate">{{ $order->order_number }}</span>
                <span class="text-faint text-[10px]">{{ $order->created_at->isoFormat('D MMM Y') }}</span>
              </div>
              <span
                class="text-ink text-xs font-semibold shrink-0">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
          </div>
        @endforeach
      @endif
    </div>

    {{-- DESKTOP (>= md): table --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-black/10">
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Order ID
            </th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Pelanggan
            </th>
            <th
              class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em] hidden lg:table-cell">
              Product</th>
            <th
              class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em] hidden xl:table-cell">
              Tanggal</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Total</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold uppercase tracking-[0.15em]">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @if ($recentOrders->isEmpty())
            <tr>
              <td colspan="6" class="px-6 py-10 text-center">
                <i data-feather="inbox" class="w-8 h-8 text-faint mx-auto mb-2"></i>
                <p class="text-faint text-xs">Belum ada order masuk</p>
              </td>
            </tr>
          @else
            @foreach ($recentOrders as $order)
              @php $meta = \App\Helpers\OrderStatus::meta($order->status); @endphp  
              <tr class="group hover:bg-surface2/50 transition-colors duration-150">
                <td class="px-6 py-4">
                  <span
                    class="text-ink text-xs font-mono font-medium block max-w-[100px] truncate">{{ $order->order_number }}</span>
                </td>
                <td class="px-6 py-4 text-ink">
                  <div class="flex items-center gap-2.5">
                    <div
                      class="w-7 h-7 rounded-full bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                      <span
                        class="text-[10px] font-semibold text-muted">{{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}</span>
                    </div>
                    <span class="text-ink text-xs font-medium">{{ $order->user->name ?? '-' }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 hidden lg:table-cell">
                  <span class="text-muted text-xs">{{ $order->orderItems?->first()?->product_name ?? '-' }}</span>
                </td>
                <td class="px-6 py-4 hidden xl:table-cell">
                  <span class="text-muted text-xs">{{ $order->created_at->isoFormat('D MMMM Y') }}</span>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="text-ink text-xs font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full border {{ $meta['fill'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $meta['label'] }}
                  </span>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>


@endsection

@php
  $statusColorMap = collect(\App\Helpers\OrderStatus::all())->mapWithKeys(fn($v, $k) => [$k => $v['chart']]);
@endphp

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // ─── Shared Chart Defaults ───────────────────────────────
      Chart.defaults.color = '#6b6459';
      Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
      Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
      Chart.defaults.font.size = 11;

      // ─── Data dari Database (orders only) ────────────────────
      const monthlyData = {
        labels: {!! json_encode($monthlyChartData->pluck('label')) !!},
        orders: {!! json_encode($monthlyChartData->pluck('orders')) !!},
      };
      const weeklyData = {
        labels: {!! json_encode($weeklyChartData->pluck('label')) !!},
        orders: {!! json_encode($weeklyChartData->pluck('orders')) !!},
      };

      // ─── Line Chart ──────────────────────────────────────────
      const ctxLine = document.getElementById('orderChart').getContext('2d');

      const goldGrad = ctxLine.createLinearGradient(0, 0, 0, 220);
      goldGrad.addColorStop(0, 'rgba(212,175,55,0.28)');
      goldGrad.addColorStop(1, 'rgba(212,175,55,0.00)');

      const lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
          labels: monthlyData.labels,
          datasets: [{
            label: 'Order Masuk',
            data: monthlyData.orders,
            borderColor: '#d4af37',
            backgroundColor: goldGrad,
            borderWidth: 2,
            pointBackgroundColor: '#d4af37',
            pointBorderColor: '#FAF8F4',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.45,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: '#221F1A',
              borderColor: 'rgba(212,175,55,0.3)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 4,
              titleColor: '#EDE6D8',
              bodyColor: '#A69C8A',
              titleFont: {
                weight: '600',
                size: 12
              },
              callbacks: {
                label: ctx => `  Order Masuk: ${ctx.parsed.y}`
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#6b6459',
                font: {
                  size: 10
                },
                autoSkip: true,
                maxTicksLimit: window.innerWidth < 640 ? 6 : undefined,
              },
            },
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(255,255,255,0.04)',
                drawBorder: false
              },
              ticks: {
                color: '#4a4540',
                font: {
                  size: 10
                },
                precision: 0,
                padding: 8,
              },
            }
          }
        }
      });

      // ─── Filter Buttons ──────────────────────────────────────
      document.querySelectorAll('.chart-filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('.chart-filter-btn').forEach(b => {
            b.classList.remove('bg-gold', 'text-white');
            b.classList.add('text-faint');
          });
          btn.classList.add('bg-gold', 'text-white');
          btn.classList.remove('text-faint');

          const d = btn.dataset.period === 'weekly' ? weeklyData : monthlyData;
          lineChart.data.labels = d.labels;
          lineChart.data.datasets[0].data = d.orders;
          lineChart.update('active');
        });
      });

      // ─── Donut Chart (data dari DB) ───────────────────────────
      const statusColorMap = {!! json_encode($statusColorMap) !!};
      const statusLabelMap = {
        'Completed': 'Selesai',
        'Processing': 'Diproses',
        'Shipped': 'Dikirim',
        'Pending': 'Pending',
        'Cancelled': 'Dibatalkan'
      };

      const rawStatus = {!! json_encode($orderStatusCounts) !!};
      const donutLabels = [],
        donutData = [],
        donutColors = [];
      Object.entries(rawStatus).forEach(([key, val]) => {
        donutLabels.push(statusLabelMap[key] ?? key);
        donutData.push(val);
        donutColors.push(statusColorMap[key] ?? 'rgba(148,163,184,0.6)');
      });


      const ctxDo = document.getElementById('statusDonutChart').getContext('2d');
      new Chart(ctxDo, {
        type: 'doughnut',
        data: {
        labels: donutLabels,
        datasets: [{
          data: donutData,
          backgroundColor: donutColors,
          borderColor: '#FAF8F4',
          borderWidth: 3,
          hoverOffset: 6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#1e1e1e',
            borderColor: 'rgba(212,175,55,0.3)',
            borderWidth: 1,
            padding: 10,
            cornerRadius: 4,
            titleColor: '#f0ece4',
            bodyColor: '#8a8279',
            callbacks: {
              label: ctx => `  ${ctx.label}: ${ctx.parsed} order`
            }
          }
        }
      }
    });

    // ─── Feather icons re-init ────────────────────────────────
    if (typeof feather !== 'undefined') feather.replace();
    });
  </script>
@endpush
