@extends('layouts.admin')

@section('content')
@php
    $d = $dashboardDatas[0] ?? null;
    $totalPesanan = $d->Total ?? 0;
    $totalPenjualan = (float)($d->TotalAmount ?? 0);
    $aktif = $d->TotalOrdered ?? 0;
    $aktifNilai = (float)($d->TotalOrderedAmount ?? 0);
    $selesai = $d->TotalDelivered ?? 0;
    $selesaiNilai = (float)($d->TotalDeliveredAmount ?? 0);
    $batal = $d->TotalCanceled ?? 0;
    $batalNilai = (float)($d->TotalCanceledAmount ?? 0);
    $pct = fn($a,$b) => $b > 0 ? round($a/$b*100) : 0;
    $fmt = fn($v) => number_format((float)$v,0,',','.');
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Kelola penjualan Azzahera Label dengan mudah dan presisi.</p>
    </div>
    <button class="btn-date-picker" type="button">
        <i class="bi bi-calendar4-event"></i>
        <span>{{ now()->startOfMonth()->translatedFormat('d M Y') }} - {{ now()->translatedFormat('d M Y') }}</span>
        <i class="bi bi-chevron-down ms-1"></i>
    </button>
</div>

{{-- ===== TOP STAT ROW (Spark) ===== --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card alert-green-card h-100">
            <div class="position-relative z-index-2">
                <span class="alert-green-badge">Update</span>
                <div class="alert-green-date">{{ now()->translatedFormat('d M Y') }}</div>
                <div class="alert-green-text">Total {{ $totalPesanan }} pesanan dengan pendapatan Rp {{ $fmt($totalPenjualan) }}</div>
            </div>
            <a href="{{ route('admin.orders') }}" class="alert-green-link z-index-2">
                <span>Lihat Pesanan</span><i class="bi bi-arrow-right"></i>
            </a>
            <svg class="alert-green-bg-shape" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(50,50)">
                    <rect x="-6" y="-45" width="12" height="90" rx="6" fill="#D4AF37"/>
                    <rect x="-6" y="-45" width="12" height="90" rx="6" fill="#D4AF37" transform="rotate(60)"/>
                    <rect x="-6" y="-45" width="12" height="90" rx="6" fill="#D4AF37" transform="rotate(120)"/>
                </g>
            </svg>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat d-flex flex-column justify-content-between h-100">
            <div>
                <div class="card-header">
                    <span class="stat-label">Total Penjualan</span>
                    <div class="dropdown">
                        <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.orders') }}"><i class="bi bi-receipt me-2"></i>Lihat pesanan</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products') }}"><i class="bi bi-box-seam me-2"></i>Kelola produk</a></li>
                        </ul>
                    </div>
                </div>
                <div class="stat-value">Rp {{ $fmt($totalPenjualan) }}</div>
                <div class="trend-badge trend-up"><i class="bi bi-arrow-up-right"></i><span>{{ $selesai }} pesanan selesai</span></div>
            </div>
            <div class="sparkline-container"><div id="income-sparkline"></div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat d-flex flex-column justify-content-between h-100">
            <div>
                <div class="card-header">
                    <span class="stat-label">Pesanan Dibatalkan</span>
                    <div class="dropdown">
                        <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.orders') }}"><i class="bi bi-receipt me-2"></i>Lihat pesanan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="stat-value">Rp {{ $fmt($batalNilai) }}</div>
                <div class="trend-badge {{ $batal > 0 ? 'trend-down' : 'trend-neutral' }}">
                    <i class="bi {{ $batal > 0 ? 'bi-arrow-down-left' : 'bi-dash' }}"></i>
                    <span>{{ $batal }} pesanan dibatalkan</span>
                </div>
            </div>
            <div class="sparkline-container"><div id="return-sparkline"></div></div>
        </div>
    </div>
</div>

{{-- ===== MINI STATS ===== --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card"><div class="mini-stat">
            <div class="mini-stat-icon bg-forest-light text-lime"><i class="bi bi-bag"></i></div>
            <div><div class="mini-stat-label">Total Pesanan</div><div class="mini-stat-value">{{ $totalPesanan }}</div></div>
        </div></div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card"><div class="mini-stat">
            <div class="mini-stat-icon bg-forest-light text-lime"><i class="bi bi-clock"></i></div>
            <div><div class="mini-stat-label">Pesanan Aktif</div><div class="mini-stat-value">{{ $aktif }}</div></div>
        </div></div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card"><div class="mini-stat">
            <div class="mini-stat-icon bg-forest-light text-lime"><i class="bi bi-check-circle"></i></div>
            <div><div class="mini-stat-label">Pesanan Selesai</div><div class="mini-stat-value">{{ $selesai }}</div></div>
        </div></div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card"><div class="mini-stat">
            <div class="mini-stat-icon bg-forest-light text-lime"><i class="bi bi-x-circle"></i></div>
            <div><div class="mini-stat-label">Dibatalkan</div><div class="mini-stat-value">{{ $batal }}</div></div>
        </div></div>
    </div>
</div>

<div class="row g-4">
    {{-- ===== LEFT ===== --}}
    <div class="col-xl-9 col-lg-8">
        <div class="card mb-4">
            <div class="card-header mb-2">
                <h2 class="card-title">Pendapatan</h2>
                <div class="d-flex gap-3 align-items-center">
                    <div class="chart-legend-item"><span class="legend-dot" style="background:#141414"></span><span>Total</span></div>
                    <div class="chart-legend-item"><span class="legend-dot bg-lime-accent"></span><span>Selesai</span></div>
                    <div class="chart-legend-item"><span class="legend-dot bg-brand-orange"></span><span>Aktif</span></div>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2 mb-3 flex-wrap">
                <span class="stat-value-amount">Rp {{ $fmt($TotalAmount ?? $totalPenjualan) }}</span>
                <span class="trend-badge trend-up fs-xs">tahun ini</span>
            </div>
            <div id="revenue-chart"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-7 d-flex flex-column">
                <div class="card h-100 flex-grow-1">
                    <div class="card-header">
                        <h2 class="card-title">Pesanan Terbaru</h2>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-dark rounded-pill px-3">Lihat Semua</a>
                    </div>
                    <div class="transaction-list">
                        @forelse ($orders->take(5) as $order)
                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime"><i class="bi bi-bag-check"></i></div>
                                <div class="transaction-info">
                                    <div class="transaction-name">#{{ $order->id }} — {{ $order->name }}</div>
                                    <div class="transaction-date">{{ $order->created_at ? $order->created_at->format('d M Y • H:i') : '-' }} • {{ $order->order_items_count ?? $order->orderItems->count() }} item</div>
                                </div>
                                <div class="transaction-amount text-success">+Rp {{ $fmt(preg_replace('/[^\d.]/', '', $order->total)) }}</div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">Belum ada pesanan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-5 d-flex flex-column">
                <div class="card h-100 flex-grow-1">
                    <div class="card-header">
                        <h2 class="card-title">Ringkasan Pesanan</h2>
                        <a href="{{ route('admin.orders') }}" class="card-more-btn d-inline-flex align-items-center justify-content-center text-decoration-none"><i class="bi bi-gear"></i></a>
                    </div>
                    @php
                        $rows = [
                            ['Total Pesanan', $totalPesanan, $pct($totalPesanan,$totalPesanan), '#141414', 'w-65'],
                            ['Pesanan Aktif', $aktif, $pct($aktif,$totalPesanan), '#D4AF37', ''],
                            ['Pesanan Selesai', $selesai, $pct($selesai,$totalPesanan), '#141414', ''],
                            ['Dibatalkan', $batal, $pct($batal,$totalPesanan), '#ff6b35', ''],
                            ['Nilai Aktif (Rp)', $fmt($aktifNilai), $pct($aktifNilai,$totalPenjualan), '#D4AF37', ''],
                            ['Nilai Selesai (Rp)', $fmt($selesaiNilai), $pct($selesaiNilai,$totalPenjualan), '#141414', ''],
                        ];
                    @endphp
                    @foreach ($rows as $r)
                        <div class="progress-container">
                            <div class="progress-label-row"><span class="progress-label">{{ $r[0] }}</span><span class="progress-value">{{ $r[1] }}</span></div>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $r[2] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar {{ $r[4] }}" style="width:{{ $r[2] }}%;background:{{ $r[3] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tabel pesanan terbaru ala Spark --}}
        <div class="card mt-4">
            <div class="card-header mb-2">
                <h2 class="card-title">Detail Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-dark rounded-pill px-3">Kelola</a>
            </div>
            <div class="table-responsive">
                <table class="table-spark">
                    <thead><tr>
                        <th>No</th><th>Nama</th><th>No HP</th><th>Subtotal</th><th>Total</th>
                        <th>Status</th><th>Tanggal</th><th>Item</th><th>Aksi</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td class="cell-money">Rp {{ $fmt(preg_replace('/[^\d.]/', '', $order->subtotal)) }}</td>
                                <td class="cell-money fw-bold">Rp {{ $fmt(preg_replace('/[^\d.]/', '', $order->total)) }}</td>
                                <td>
                                    @switch($order->status)
                                        @case('waiting_verification')<span class="status-badge status-waiting">Menunggu Verifikasi</span>@break
                                        @case('pending_payment')<span class="status-badge status-payment">Menunggu Pembayaran</span>@break
                                        @case('processing')<span class="status-badge status-processing">Diproses</span>@break
                                        @case('shipped')<span class="status-badge status-shipped">Dikirim</span>@break
                                        @case('delivered')<span class="status-badge status-delivered">Selesai</span>@break
                                        @case('canceled')<span class="status-badge status-canceled">Dibatalkan</span>@break
                                        @case('ordered')<span class="status-badge status-processing">Diproses</span>@break
                                        @default<span class="status-badge status-default">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                                    @endswitch
                                </td>
                                <td class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</td>
                                <td>{{ $order->order_items_count ?? $order->orderItems->count() }} item</td>
                                <td><a class="btn-view" href="{{ route('admin.order.details',['order_id'=>$order->id]) }}" title="Detail"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT ===== --}}
    <div class="col-xl-3 col-lg-4">
        <div class="right-panel-wrapper d-flex flex-column gap-4">
            <div class="card mb-0">
                <div class="card-header mb-1"><h2 class="card-title">Komposisi Status</h2></div>
                <div id="views-chart"></div>
                <div class="chart-legends-container">
                    <div class="chart-legend-item"><span class="legend-dot bg-lime-accent"></span><span class="text-muted-green">Selesai</span></div>
                    <div class="chart-legend-item"><span class="legend-dot bg-forest-medium"></span><span class="text-muted-green">Aktif</span></div>
                    <div class="chart-legend-item"><span class="legend-dot bg-brand-orange"></span><span class="text-muted-green">Batal</span></div>
                </div>
            </div>
            <div class="promo-banner-card">
                <svg class="promo-banner-bg-shape" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g transform="translate(50,50)">
                        <rect x="-6" y="-45" width="12" height="90" rx="6" fill="#D4AF37"/>
                        <rect x="-6" y="-45" width="12" height="90" rx="6" fill="#D4AF37" transform="rotate(60)"/>
                        <rect x="-6" y="-45" width="12" height="90" rx="6" fill="#D4AF37" transform="rotate(120)"/>
                    </g>
                </svg>
                <h3 class="promo-title">Naikkan penjualan Azzahera ke level berikutnya.</h3>
                <p class="promo-desc">Kelola produk, pesanan, dan promo dengan mudah dan presisi.</p>
                <a href="{{ route('admin.product.add') }}" class="btn-promo text-decoration-none text-center d-block">Tambah Produk Sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ApexCharts === 'undefined') return;
    const rp = v => 'Rp ' + Number(v || 0).toLocaleString('id-ID');
    const totalData = [{{ $AmountM }}];
    const deliveredData = [{{ $DeliveredAmountM }}];
    const orderedData = [{{ $OrderedAmountM }}];
    const canceledData = [{{ $CanceledAmountM }}];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    const rev = document.querySelector('#revenue-chart');
    if (rev) {
        new ApexCharts(rev, {
            chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [
                { name: 'Total', data: totalData },
                { name: 'Selesai', data: deliveredData },
                { name: 'Aktif', data: orderedData },
            ],
            xaxis: { categories: months, labels: { style: { fontSize: '11px', colors: '#8a8378' } } },
            yaxis: { labels: { style: { fontSize: '11px', colors: '#8a8378' }, formatter: v => 'Rp' + (v >= 1000000 ? (v/1000000)+'jt' : Number(v).toLocaleString('id-ID')) } },
            colors: ['#141414', '#D4AF37', '#ff6b35'],
            fill: { type: 'gradient', gradient: { shadeIntensity: .2, opacityFrom: .35, opacityTo: .05 } },
            stroke: { curve: 'smooth', width: 2.5 },
            markers: { size: 0, hover: { size: 5 } },
            dataLabels: { enabled: false },
            tooltip: { y: { formatter: rp } },
            grid: { borderColor: '#e7dfcb', strokeDashArray: 4 },
            legend: { show: false }
        }).render();
    }

    const spark = (el, data, color) => {
        const n = document.querySelector(el);
        if (!n) return;
        new ApexCharts(n, {
            chart: { type: 'area', height: 70, sparkline: { enabled: true } },
            series: [{ data }],
            colors: [color],
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { opacityFrom: .4, opacityTo: .05 } },
            tooltip: { y: { formatter: rp } }
        }).render();
    };
    spark('#income-sparkline', totalData, '#9c7c2a');
    spark('#return-sparkline', canceledData, '#ff6b35');

    const donut = document.querySelector('#views-chart');
    if (donut) {
        new ApexCharts(donut, {
            chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
            series: [{{ $selesai }}, {{ $aktif }}, {{ $batal }}],
            labels: ['Selesai', 'Aktif', 'Dibatalkan'],
            colors: ['#D4AF37', '#141414', '#ff6b35'],
            dataLabels: { enabled: true, formatter: function(val, opts) { return opts.w.config.series[opts.seriesIndex]; }, style: { fontSize: '12px', fontWeight: 800, colors: ['#141414'] }, dropShadow: { enabled: false } },
            legend: { show: false },
            plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Pesanan', formatter: () => '{{ $totalPesanan }}' } } } } },
            tooltip: { y: { formatter: v => v + ' pesanan' } }
        }).render();
    }
});
</script>
@endpush
