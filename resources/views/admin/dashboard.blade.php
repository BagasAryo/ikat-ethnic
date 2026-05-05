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
        <p class="text-muted text-sm mt-0.5">Selamat datang kembali — <span class="text-gold">{{ auth()->user()->name ?? 'Administrator' }}</span></p>
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
            <span class="text-[10px] text-success bg-success/10 border border-success/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
                <i data-feather="trending-up" class="w-3 h-3"></i>+12%
            </span>
        </div>
        <p class="text-2xl font-semibold text-ink tracking-tight">{{ $totalOrders ?? '248' }}</p>
        <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total Order</p>
    </div>

    {{-- Revenue --}}
    <div class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center">
                <i data-feather="dollar-sign" class="w-4 h-4 text-gold"></i>
            </div>
            <span class="text-[10px] text-success bg-success/10 border border-success/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
                <i data-feather="trending-up" class="w-3 h-3"></i>+8%
            </span>
        </div>
        <p class="text-2xl font-semibold text-ink tracking-tight">Rp 84,2jt</p>
        <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total Pendapatan</p>
    </div>

    {{-- Produk --}}
    <div class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center">
                <i data-feather="package" class="w-4 h-4 text-gold"></i>
            </div>
            <span class="text-[10px] text-warn bg-warn/10 border border-warn/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
                <i data-feather="minus" class="w-3 h-3"></i>±0%
            </span>
        </div>
        <p class="text-2xl font-semibold text-ink tracking-tight">{{ $totalProducts ?? '64' }}</p>
        <p class="text-muted text-xs mt-1 uppercase tracking-wider">Total Produk</p>
    </div>

    {{-- Pending Order --}}
    <div class="bg-surface border border-white/5 rounded-sm p-5 group hover:border-gold/20 transition-colors duration-300">
        <div class="flex items-start justify-between mb-4">
            <div class="w-9 h-9 rounded-sm bg-danger/10 border border-danger/20 flex items-center justify-center">
                <i data-feather="clock" class="w-4 h-4 text-danger"></i>
            </div>
            <span class="text-[10px] text-danger bg-danger/10 border border-danger/20 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
                <i data-feather="alert-circle" class="w-3 h-3"></i>Perlu aksi
            </span>
        </div>
        <p class="text-2xl font-semibold text-ink tracking-tight">{{ $pendingOrders ?? '7' }}</p>
        <p class="text-muted text-xs mt-1 uppercase tracking-wider">Order Pending</p>
    </div>

</div>

{{-- ──────────────────────────────────────────────────────
     Main Content Grid: Chart + Summary
────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- ── Order Chart (2/3 width) ── --}}
    <div class="xl:col-span-2 bg-surface border border-white/5 rounded-sm p-6">

        {{-- Chart Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-sm font-semibold text-ink tracking-wide">Grafik Order</h2>
                <p class="text-faint text-xs mt-0.5">Volume order 7 bulan terakhir</p>
            </div>

            {{-- Legend & Filter --}}
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-gold inline-block"></span>
                    <span class="text-muted text-xs">Order Masuk</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-success inline-block"></span>
                    <span class="text-muted text-xs">Selesai</span>
                </div>
                <div class="flex items-center gap-4 bg-surface2 border border-white/5 rounded-sm p-0.5">
                    <button class="chart-filter-btn text-[10px] font-medium px-2 py-1 rounded-sm bg-gold text-bg active" data-period="monthly">Bulanan</button>
                    <button class="chart-filter-btn text-[10px] font-medium px-2 py-1 rounded-sm text-faint hover:text-muted transition-colors" data-period="weekly">Mingguan</button>
                </div>
            </div>
        </div>

        {{-- Summary Strip --}}
        <div class="grid grid-cols-3 gap-4 mb-6 py-4 border-y border-white/5">
            <div class="text-center">
                <p class="text-lg font-semibold text-ink">248</p>
                <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Total</p>
            </div>
            <div class="text-center border-x border-white/5">
                <p class="text-lg font-semibold text-gold">186</p>
                <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Selesai</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-semibold text-danger">7</p>
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
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl font-semibold text-ink">75%</span>
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
                <span class="text-ink text-xs font-medium">186 <span class="text-faint">(75%)</span></span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-gold shrink-0"></span>
                    <span class="text-muted text-xs">Diproses</span>
                </div>
                <span class="text-ink text-xs font-medium">55 <span class="text-faint">(22%)</span></span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-danger shrink-0"></span>
                    <span class="text-muted text-xs">Pending</span>
                </div>
                <span class="text-ink text-xs font-medium">7 <span class="text-faint">(3%)</span></span>
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
                    <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Order ID</th>
                    <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Pelanggan</th>
                    <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden md:table-cell">Produk</th>
                    <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden lg:table-cell">Tanggal</th>
                    <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Total</th>
                    <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @php
                $demoOrders = [
                    ['id' => '#ORD-0048', 'customer' => 'Ananda Wiratama', 'product' => 'Sumba Hinggi Kombu', 'date' => '4 Mei 2026', 'total' => 'Rp 12.500.000', 'status' => 'selesai'],
                    ['id' => '#ORD-0047', 'customer' => 'Bambang Hartono', 'product' => 'Ulos Pinunsaan Gold', 'date' => '3 Mei 2026', 'total' => 'Rp 8.200.000', 'status' => 'diproses'],
                    ['id' => '#ORD-0046', 'customer' => 'Sophia Latjuba', 'product' => 'Sikka Silk Sarong', 'date' => '3 Mei 2026', 'total' => 'Rp 15.000.000', 'status' => 'pending'],
                    ['id' => '#ORD-0045', 'customer' => 'Erick Thohir', 'product' => 'Songket Limar Gold', 'date' => '2 Mei 2026', 'total' => 'Rp 22.000.000', 'status' => 'selesai'],
                    ['id' => '#ORD-0044', 'customer' => 'Dewi Rahayu', 'product' => 'Endek Surya Amber', 'date' => '1 Mei 2026', 'total' => 'Rp 6.800.000', 'status' => 'selesai'],
                ];
                @endphp

                @foreach($demoOrders as $order)
                <tr class="group hover:bg-surface2/50 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <span class="text-gold text-xs font-mono font-medium">{{ $order['id'] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-surface2 border border-white/10 flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-semibold text-muted">{{ strtoupper(substr($order['customer'], 0, 1)) }}</span>
                            </div>
                            <span class="text-ink text-xs font-medium">{{ $order['customer'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="text-muted text-xs">{{ $order['product'] }}</span>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="text-faint text-xs">{{ $order['date'] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-ink text-xs font-semibold">{{ $order['total'] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($order['status'] === 'selesai')
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-success/10 text-success border border-success/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Selesai
                            </span>
                        @elseif($order['status'] === 'diproses')
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-gold/10 text-gold border border-gold/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>Diproses
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full bg-danger/10 text-danger border border-danger/20">
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
    Chart.defaults.font.size   = 11;

    // ─── Data ────────────────────────────────────────────────
    const monthlyData = {
        labels : ['Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
        orders : [28, 35, 42, 31, 58, 47, 62],
        done   : [20, 28, 35, 25, 50, 40, 28],
    };
    const weeklyData = {
        labels : ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        orders : [8, 12, 7, 14, 10, 18, 6],
        done   : [6,  9,  5, 11,  8, 15, 4],
    };

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
            datasets: [
                {
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
                    label: 'Selesai',
                    data: monthlyData.done,
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
                    titleFont: { weight: '600', size: 12 },
                    callbacks: {
                        label: ctx => `  ${ctx.dataset.label}: ${ctx.parsed.y} order`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#4a4540', font: { size: 10 } },
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255,255,255,0.04)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#4a4540',
                        font: { size: 10 },
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
            lineChart.data.labels          = d.labels;
            lineChart.data.datasets[0].data = d.orders;
            lineChart.data.datasets[1].data = d.done;
            lineChart.update('active');
        });
    });

    // ─── Donut Chart ─────────────────────────────────────────
    const ctxDo = document.getElementById('statusDonutChart').getContext('2d');
    new Chart(ctxDo, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Diproses', 'Pending'],
            datasets: [{
                data: [186, 55, 7],
                backgroundColor: [
                    'rgba(74, 222, 128, 0.85)',
                    'rgba(212, 175, 55, 0.85)',
                    'rgba(248, 113, 113, 0.85)',
                ],
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
                legend: { display: false },
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
