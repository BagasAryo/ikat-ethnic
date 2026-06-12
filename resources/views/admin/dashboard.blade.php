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
      <h1 class="text-xl font-semibold text-ink tracking-wide">Dashboard</h1>
      <p class="text-muted text-sm mt-0.5">Selamat datang kembali - <span
          class="text-gold">{{ auth()->user()->name ?? 'Administrator' }}</span></p>
    </div>
    <div class="flex items-center gap-2 text-xs text-faint bg-surface border border-white/5 rounded-sm px-3 py-2">
      <i data-feather="calendar" class="w-3.5 h-3.5 text-gold"></i>
      <span>{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
  </div>

  {{-- ──────────────────────────────────────────────────────
     Stat Cards
────────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    {{-- Total Order --}}
    <div class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center">
          <i data-feather="shopping-bag" class="w-4 h-4 text-gold"></i>
        </div>
        <span
          class="text-[10px] text-success bg-success/10 border border-success/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
          <i data-feather="trending-up" class="w-3 h-3"></i>+12%
        </span>
      </div>
      <p class="text-2xl font-semibold text-ink tracking-tight">{{ $orders->count() }}</p>
      <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total Order</p>
    </div>

    {{-- Revenue --}}
    <div
      class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center">
          <i data-feather="dollar-sign" class="w-4 h-4 text-gold"></i>
        </div>
        <span
          class="text-[10px] text-success bg-success/10 border border-success/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
          <i data-feather="trending-up" class="w-3 h-3"></i>+8%
        </span>
      </div>
      <p class="text-2xl font-semibold text-ink tracking-tight">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
      <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total Pendapatan</p>
    </div>

    {{-- Product --}}
    <div
      class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center">
          <i data-feather="package" class="w-4 h-4 text-gold"></i>
        </div>
        <span
          class="text-[10px] text-warn bg-warn/10 border border-warn/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
          <i data-feather="minus" class="w-3 h-3"></i>±0%
        </span>
      </div>
      <p class="text-2xl font-semibold text-ink tracking-tight">{{ $products->count() }}</p>
      <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total Product</p>
    </div>

    {{-- User --}}
    <div
      class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center">
          <i data-feather="users" class="w-4 h-4 text-gold"></i>
        </div>
        <span
          class="text-[10px] text-danger bg-danger/10 border border-danger/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
          <i data-feather="alert-circle" class="w-3 h-3"></i>
        </span>
      </div>
      <p class="text-2xl font-semibold text-ink tracking-tight">{{ $users->count() }}</p>
      <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total User</p>
    </div>

  </div>

  {{-- ──────────────────────────────────────────────────────
     Low Stock Alert
────────────────────────────────────────────────────── --}}
  @if ($lowStockProducts->isNotEmpty())
    <div class="bg-surface border border-danger/20 rounded-sm mb-6">
      <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
        <div class="flex items-center gap-2.5">
          <div class="w-7 h-7 rounded-sm bg-danger/10 border border-danger/20 flex items-center justify-center">
            <i data-feather="alert-triangle" class="w-3.5 h-3.5 text-danger"></i>
          </div>
          <div>
            <h2 class="text-sm font-semibold text-ink tracking-wide">Stok Hampir Habis</h2>
            <p class="text-faint text-xs mt-0.5">{{ $lowStockProducts->count() }} varian produk perlu restock</p>
          </div>
        </div>
        <a href="{{ route('admin.products.index') }}"
          class="text-xs text-gold hover:text-gold-lt transition-colors flex items-center gap-1 font-medium">
          Kelola Produk <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-white/5">
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Produk</th>
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Ukuran</th>
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Stok</th>
              <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5">
            @foreach ($lowStockProducts as $size)
              <tr class="group hover:bg-surface2/50 transition-colors duration-150">
                <td class="px-6 py-3.5">
                  <div class="flex items-center gap-2.5">
                    @php $thumb = $size->product->images->first(); @endphp
                    @if ($thumb)
                      <img src="{{ asset('storage/' . $thumb->image_url) }}" alt="{{ $size->product->name }}"
                        class="w-8 h-8 rounded-sm object-cover border border-white/10 shrink-0">
                    @else
                      <div
                        class="w-8 h-8 rounded-sm bg-surface2 border border-white/10 flex items-center justify-center shrink-0">
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
                      class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-danger/10 text-danger border border-danger/20">
                      <span class="w-1.5 h-1.5 rounded-full bg-danger animate-pulse"></span>Kritis
                    </span>
                  @else
                    <span
                      class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-warn/10 text-warn border border-warn/20">
                      <span class="w-1.5 h-1.5 rounded-full bg-warn"></span>Rendah
                    </span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- ──────────────────────────────────────────────────────
     Main Content Grid: Chart + Summary
────────────────────────────────────────────────────── --}}

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- ── Order Chart (2/3 width) ── --}}
    <div class="xl:col-span-2 bg-surface border border-white/5 rounded-sm p-6">

      {{-- Chart Header --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
          <h2 class="text-sm font-semibold text-ink tracking-wide">Grafik Order & Revenue</h2>
          <p class="text-faint text-xs mt-0.5">Order & pendapatan dari database</p>
        </div>

        {{-- Legend & Filter --}}
        <div class="flex items-center gap-3 flex-wrap">
          <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-gold inline-block"></span>
            <span class="text-muted text-xs">Order Masuk</span>
          </div>
          <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-success inline-block"></span>
            <span class="text-muted text-xs">Revenue</span>
          </div>
          <div class="flex items-center gap-4 bg-surface2 border border-white/5 rounded-sm p-0.5">
            <button class="chart-filter-btn text-[10px] font-medium px-2 py-1 rounded-sm bg-gold text-bg active"
              data-period="monthly">Bulanan</button>
            <button
              class="chart-filter-btn text-[10px] font-medium px-2 py-1 rounded-sm text-faint hover:text-muted transition-colors"
              data-period="weekly">Mingguan</button>
          </div>
        </div>
      </div>

      {{-- Summary Strip --}}
      <div class="grid grid-cols-3 gap-4 mb-6 py-4 border-y border-white/5">
        <div class="text-center">
          <p class="text-lg font-semibold text-ink">{{ $orders->count() }}</p>
          <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Total</p>
        </div>
        <div class="text-center border-x border-white/5">
          <p class="text-lg font-semibold text-gold">
            {{ $orders->whereIn('status', ['Processing', 'Shipped', 'Completed'])->count() }}</p>
          <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Selesai</p>
        </div>
        <div class="text-center">
          <p class="text-lg font-semibold text-danger">{{ $orders->where('status', 'Pending')->count() }}</p>
          <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Pending</p>
        </div>
      </div>

      {{-- Canvas --}}
      <div class="relative h-56">
        <canvas id="orderChart"></canvas>
      </div>

    </div>

    {{-- ── Status Breakdown (1/3 width) ── --}}
    <div class="bg-surface border border-white/5 rounded-sm p-6 flex flex-col">
      <div class="mb-5">
        <h2 class="text-sm font-semibold text-ink tracking-wide">Status Order</h2>
        <p class="text-faint text-xs mt-0.5">Distribusi bulan ini</p>
      </div>

      {{-- Donut Chart --}}
      <div class="flex items-center justify-center mb-6">
        <div class="relative w-36 h-36">
          <canvas id="statusDonutChart"></canvas>
          @php
            $completedCount = $orderStatusCounts->get('Completed', 0);
            $totalOrders = $orders->count();
            $completedPct = $totalOrders > 0 ? round(($completedCount / $totalOrders) * 100) : 0;
          @endphp
          <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-2xl font-semibold text-ink">{{ $completedPct }}%</span>
            <span class="text-faint text-[10px] uppercase tracking-wider">Selesai</span>
          </div>
        </div>
      </div>

      {{-- Legend --}}
      <div class="space-y-3 mt-auto">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-success shrink-0"></span>
            <span class="text-muted text-xs">Selesai</span>
          </div>
          <span class="text-ink text-xs font-medium">{{ $orders->where('status', 'Completed')->count() }}
            <span
              class="text-faint">({{ $orders->count() > 0 ? round(($orders->where('status', 'Completed')->count() / $orders->count()) * 100, 0) : 0 }}%)</span></span>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-gold shrink-0"></span>
            <span class="text-muted text-xs">Diproses</span>
          </div>
          <span class="text-ink text-xs font-medium">{{ $orders->where('status', 'Processing')->count() }} <span
              class="text-faint">({{ $orders->count() > 0 ? round(($orders->where('status', 'Processing')->count() / $orders->count()) * 100, 0) : 0 }}%)</span></span>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-danger shrink-0"></span>
            <span class="text-muted text-xs">Pending</span>
          </div>
          <span class="text-ink text-xs font-medium">{{ $orders->where('status', 'Pending')->count() }} <span
              class="text-faint">({{ $orders->count() > 0 ? round(($orders->where('status', 'Pending')->count() / $orders->count()) * 100, 0) : 0 }}%)</span></span>
        </div>
      </div>
    </div>

  </div>

  {{-- ──────────────────────────────────────────────────────
     Recent Orders Table
────────────────────────────────────────────────────── --}}
  <div class="bg-surface border border-white/5 rounded-sm">
    <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
      <div>
        <h2 class="text-sm font-semibold text-ink tracking-wide">Order Terbaru</h2>
        <p class="text-faint text-xs mt-0.5">5 transaksi terakhir</p>
      </div>
      <a href="{{ route('admin.orders.index') }}"
        class="text-xs text-gold hover:text-gold-lt transition-colors flex items-center gap-1 font-medium">
        Lihat semua <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-white/5">
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Order ID
            </th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Pelanggan
            </th>
            <th
              class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden md:table-cell">
              Product</th>
            <th
              class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden lg:table-cell">
              Tanggal</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Total</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($recentOrders as $order)
            <tr class="group hover:bg-surface2/50 transition-colors duration-150">
              <td class="px-6 py-4">
                <span
                  class="text-gold text-xs font-mono font-medium block max-w-[100px] truncate">{{ $order->order_number }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2.5">
                  <div
                    class="w-7 h-7 rounded-full bg-surface2 border border-white/10 flex items-center justify-center shrink-0">
                    <span
                      class="text-[10px] font-semibold text-muted">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                  </div>
                  <span class="text-ink text-xs font-medium">{{ $order->user->name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 hidden md:table-cell">
                <span class="text-muted text-xs">{{ $order->orderItems->first()->product->name }}</span>
              </td>
              <td class="px-6 py-4 hidden lg:table-cell">
                <span class="text-faint text-xs">{{ $order->created_at->format('d M Y') }}</span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="text-ink text-xs font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
              </td>
              <td class="px-6 py-4">
                @if ($order->status === 'selesai')
                  <span
                    class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-success/10 text-success border border-success/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Selesai
                  </span>
                @elseif($order->status === 'diproses')
                  <span
                    class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-gold/10 text-gold border border-gold/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>Diproses
                  </span>
                @else
                  <span
                    class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-danger/10 text-danger border border-danger/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>Pending
                  </span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // ─── Shared Chart Defaults ───────────────────────────────
      Chart.defaults.color = '#8a8279';
      Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
      Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
      Chart.defaults.font.size = 11;

      // ─── Data dari Database ───────────────────────────────────
      const monthlyData = {
        labels: {!! json_encode($monthlyChartData->pluck('label')) !!},
        orders: {!! json_encode($monthlyChartData->pluck('orders')) !!},
        revenue: {!! json_encode($monthlyChartData->pluck('revenue')) !!},
      };
      const weeklyData = {
        labels: {!! json_encode($weeklyChartData->pluck('label')) !!},
        orders: {!! json_encode($weeklyChartData->pluck('orders')) !!},
        revenue: {!! json_encode($weeklyChartData->pluck('revenue')) !!},
      };

      // ─── Helper: format Rupiah ────────────────────────────────
      const formatRp = v => 'Rp' + Number(v).toLocaleString('id-ID');

      // ─── Line Chart ──────────────────────────────────────────
      const ctxLine = document.getElementById('orderChart').getContext('2d');

      const goldGrad = ctxLine.createLinearGradient(0, 0, 0, 220);
      goldGrad.addColorStop(0, 'rgba(212,175,55,0.25)');
      goldGrad.addColorStop(1, 'rgba(212,175,55,0.00)');

      const greenGrad = ctxLine.createLinearGradient(0, 0, 0, 220);
      greenGrad.addColorStop(0, 'rgba(74,222,128,0.18)');
      greenGrad.addColorStop(1, 'rgba(74,222,128,0.00)');

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
              pointBorderColor: '#151515',
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6,
              fill: true,
              tension: 0.45,
            },
            {
              label: 'Revenue',
              data: monthlyData.revenue,
              borderColor: '#4ade80',
              backgroundColor: greenGrad,
              borderWidth: 2,
              borderDash: [4, 3],
              pointBackgroundColor: '#4ade80',
              pointBorderColor: '#151515',
              pointBorderWidth: 2,
              pointRadius: 3,
              pointHoverRadius: 5,
              fill: true,
              tension: 0.45,
            }
          ]
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
              backgroundColor: '#1e1e1e',
              borderColor: 'rgba(212,175,55,0.3)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 4,
              titleColor: '#f0ece4',
              bodyColor: '#8a8279',
              titleFont: {
                weight: '600',
                size: 12
              },
              callbacks: {
                label: ctx => {
                  if (ctx.dataset.label === 'Revenue') {
                    return `  Revenue: ${formatRp(ctx.parsed.y)}`;
                  }
                  return `  ${ctx.dataset.label}: ${ctx.parsed.y} order`;
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#4a4540',
                font: {
                  size: 10
                }
              },
            },
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(255,255,255,0.04)',
                drawBorder: false,
              },
              ticks: {
                color: '#4a4540',
                font: {
                  size: 10
                },
                stepSize: 10,
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
            b.classList.remove('bg-gold', 'text-bg');
            b.classList.add('text-faint');
          });
          btn.classList.add('bg-gold', 'text-bg');
          btn.classList.remove('text-faint');

          const d = btn.dataset.period === 'weekly' ? weeklyData : monthlyData;
          lineChart.data.labels = d.labels;
          lineChart.data.datasets[0].data = d.orders;
          lineChart.data.datasets[1].data = d.revenue;
          lineChart.update('active');
        });
      });

      // ─── Donut Chart (data dari DB) ───────────────────────────
      const statusMap = {
        'Completed': {
          label: 'Selesai',
          color: 'rgba(74, 222, 128, 0.85)'
        },
        'Processing': {
          label: 'Diproses',
          color: 'rgba(212, 175, 55, 0.85)'
        },
        'Shipped': {
          label: 'Dikirim',
          color: 'rgba(96, 165, 250, 0.85)'
        },
        'Pending': {
          label: 'Pending',
          color: 'rgba(248, 113, 113, 0.85)'
        },
        'Cancelled': {
          label: 'Dibatalkan',
          color: 'rgba(148, 163, 184, 0.5)'
        },
      };
      const rawStatus = {!! json_encode($orderStatusCounts) !!};
      const donutLabels = [];
      const donutData = [];
      const donutColors = [];
      Object.entries(rawStatus).forEach(([key, val]) => {
        const mapped = statusMap[key] ?? {
          label: key,
          color: 'rgba(148,163,184,0.6)'
        };
        donutLabels.push(mapped.label);
        donutData.push(val);
        donutColors.push(mapped.color);
      });

      const ctxDo = document.getElementById('statusDonutChart').getContext('2d');
      new Chart(ctxDo, {
        type: 'doughnut',
        data: {
          labels: donutLabels,
          datasets: [{
            data: donutData,
            backgroundColor: donutColors,
            borderColor: '#151515',
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

      // ─── Feather icons re-init (in case layout already called it) ──
      if (typeof feather !== 'undefined') feather.replace();
    });
  </script>
@endpush
