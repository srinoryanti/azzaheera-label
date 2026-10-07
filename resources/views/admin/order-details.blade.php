@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Pesanan #AZZ-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        <p class="page-subtitle">Rincian pesanan, pembayaran, dan pembaruan status.</p>
    </div>
    <a class="btn-spark-outline" href="{{ route('admin.orders') }}"><i class="bi bi-arrow-left"></i>Kembali</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.orders') }}">Pesanan</a> / Detail</div>

{{-- ERROR VALIDASI --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">Informasi Pesanan</h2>
    </div>
    <div class="table-responsive">
        <table class="table-spark">
            <tbody>
                <tr>
                    <th>Nomor Pesanan</th>
                    <td><strong>#AZZ-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <th>Nama Pembeli</th>
                    <td>{{ $order->name }}</td>
                    <th>Nomor HP</th>
                    <td>{{ $order->phone }}</td>
                </tr>
                <tr>
                    <th>Kode Pos</th>
                    <td>{{ $order->zip }}</td>
                    <th>Tanggal Pesanan</th>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <th>Status Pesanan</th>
                    <td>
                        @switch($order->status)
                            @case('waiting_verification')<span class="status-badge status-waiting">Menunggu Verifikasi</span>@break
                            @case('processing')<span class="status-badge status-processing">Diproses</span>@break
                            @case('shipped')<span class="status-badge status-shipped">Dikirim</span>@break
                            @case('delivered')<span class="status-badge status-delivered">Selesai</span>@break
                            @case('canceled')<span class="status-badge status-canceled">Dibatalkan</span>@break
                            @case('pending_payment')<span class="status-badge status-payment">Menunggu Pembayaran</span>@break
                            @case('ordered')<span class="status-badge status-processing">Diproses</span>@break
                            @default<span class="status-badge status-default">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Pengiriman</th>
                    <td>
                        @if ($order->delivered_date)
                            {{ \Carbon\Carbon::parse($order->delivered_date)->format('d M Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <th>Tanggal Pembatalan</th>
                    <td>
                        @if ($order->canceled_date)
                            {{ \Carbon\Carbon::parse($order->canceled_date)->format('d M Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <th>Status Saat Ini</th>
                    <td>
                        @switch($order->status)
                            @case('waiting_verification')<span class="status-badge status-waiting">Menunggu Verifikasi</span>@break
                            @case('processing')<span class="status-badge status-processing">Diproses</span>@break
                            @case('shipped')<span class="status-badge status-shipped">Dikirim</span>@break
                            @case('delivered')<span class="status-badge status-delivered">Selesai</span>@break
                            @case('canceled')<span class="status-badge status-canceled">Dibatalkan</span>@break
                            @case('pending_payment')<span class="status-badge status-payment">Menunggu Pembayaran</span>@break
                            @case('ordered')<span class="status-badge status-processing">Diproses</span>@break
                            @default<span class="status-badge status-default">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        @endswitch
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">Barang yang Dipesan</h2>
    </div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Varian</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Brands</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orderItems as $item)
                    @php $adminOpt = $item->options ? json_decode($item->options, true) : []; @endphp
                    <tr>
                        <td class="text-center">
                            @if (!empty($adminOpt['image']))
                                <img src="{{ asset('storage/products/' . $adminOpt['image']) }}" alt="{{ $item->product->name ?? 'Produk' }}" class="img-thumb-lg">
                            @elseif ($item->product && $item->product->image)
                                <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumb-lg">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->product)
                                {{ $item->product->name }}
                            @else
                                <span class="text-danger">Produk tidak ditemukan</span>
                            @endif
                        </td>
                        <td class="text-center">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">
                            @if(!empty($adminOpt['color']) || !empty($adminOpt['size']))
                                {{ $adminOpt['color'] ?? '-' }}{{ !empty($adminOpt['size']) ? ' • ' . $adminOpt['size'] : '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ $item->product?->SKU ?? '-' }}</td>
                        <td class="text-center">{{ $item->product?->category?->name ?? '-' }}</td>
                        <td class="text-center">{{ $item->product?->brand?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada produk dalam pesanan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($orderItems->hasPages())
        <div class="mt-3">
            {{ $orderItems->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">Alamat Pengiriman</h2>
    </div>
    <div class="table-responsive">
        <table class="table-spark">
            <tbody>
                <tr><th width="250">Nama Penerima</th><td>{{ $order->name }}</td></tr>
                <tr><th>Nomor HP</th><td>{{ $order->phone }}</td></tr>
                <tr><th>Alamat</th><td>{{ $order->address }}</td></tr>
                <tr><th>Kelurahan / Kecamatan</th><td>{{ $order->locality }}</td></tr>
                <tr><th>Kota</th><td>{{ $order->city }}</td></tr>
                <tr><th>Provinsi</th><td>{{ $order->state }}</td></tr>
                <tr><th>Negara</th><td>{{ $order->country }}</td></tr>
                <tr><th>Patokan</th><td>{{ $order->landmark ?: '-' }}</td></tr>
                <tr><th>Kode Pos</th><td>{{ $order->zip }}</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">Informasi Pembayaran</h2>
    </div>
    <div class="table-responsive">
        <table class="table-spark">
            <tbody>
                <tr><th width="250">Subtotal</th><td>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                <tr><th>Pajak</th><td>Rp{{ number_format($order->tax ?? 0, 0, ',', '.') }}</td></tr>
                <tr><th>Diskon</th><td>Rp{{ number_format($order->discount ?? 0, 0, ',', '.') }}</td></tr>
                <tr><th>Total Pembayaran</th><td><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></td></tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>
                        @if ($transaction)
                            @if ($transaction->mode === 'transfer')
                                Transfer Bank
                            @else
                                {{ ucfirst($transaction->mode) }}
                            @endif
                        @else
                            <span class="text-muted">Belum ada transaksi</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>
                        @if ($transaction)
                            @switch($transaction->status)
                                @case('approved')<span class="badge bg-success">Pembayaran Dikonfirmasi</span>@break
                                @case('pending')<span class="badge bg-warning text-dark">Pending</span>@break
                                @case('declined')<span class="badge bg-danger">Pembayaran Ditolak</span>@break
                                @case('refunded')<span class="badge bg-secondary">Dikembalikan</span>@break
                                @default<span class="badge bg-dark">{{ ucfirst($transaction->status) }}</span>
                            @endswitch
                        @else
                            <span class="badge bg-secondary">Belum Ada Transaksi</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">Perbarui Status Pesanan</h2>
    </div>
    <form action="{{ route('admin.order.status.update') }}" method="POST" class="form-spark">
        @csrf
        @method('PUT')
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="row align-items-end g-3">
            <div class="col-md-5">
                <label for="order_status" class="form-label">Pilih Status Pesanan</label>
                <select name="order_status" id="order_status" class="form-select" required>
                    <option value="waiting_verification" {{ $order->status === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="processing" {{ in_array($order->status, ['processing', 'ordered']) ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                    <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Update Status</button>
            </div>
        </div>
    </form>
</div>
@endsection
