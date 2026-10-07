@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Pesanan</h1>
        <p class="page-subtitle">Kelola semua pesanan pelanggan Azzahera Label.</p>
    </div>
    <span class="trend-badge trend-neutral"><i class="bi bi-receipt"></i>&nbsp;{{ $orders->total() }} Pesanan</span>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Pesanan</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Semua Pesanan</h2>
    </div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pembeli</th>
                    <th>No HP</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal Pesanan</th>
                    <th>Jumlah Item</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="fw-bold">{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td class="cell-money">Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</td>
                        <td class="cell-money fw-bold">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                        <td>
                            @switch($order->status)
                                @case('waiting_verification')<span class="status-badge status-waiting">Menunggu Verifikasi</span>@break
                                @case('pending_payment')<span class="status-badge status-payment">Menunggu Pembayaran</span>@break
                                @case('processing')<span class="status-badge status-processing">Diproses</span>@break
                                @case('shipped')<span class="status-badge status-shipped">Dikirim</span>@break
                                @case('delivered')<span class="status-badge status-delivered">Selesai</span>@break
                                @case('canceled')<span class="status-badge status-canceled">Dibatalkan</span>@break
                                @case('ordered')<span class="status-badge status-processing">Diproses</span>@break
                                @default<span class="status-badge status-default">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            @endswitch
                        </td>
                        <td class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $order->order_items_count ?? $order->orderItems->count() }} Item</td>
                        <td class="text-muted">{{ $order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('d M Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}" class="btn-view" title="Lihat Detail Pesanan"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
