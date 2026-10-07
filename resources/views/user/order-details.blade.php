@extends('layouts.app')
@section('content')
<style>
/* Font & ukuran samakan dengan /akun-orders, warna tabel detail tetap gold */
.table thead th{background:#141414 !important;color:#fff !important;border-color:#141414 !important;padding:0.625rem 1rem !important;font-size:13px !important;font-weight:700;text-transform:uppercase;letter-spacing:.2px;white-space:nowrap;}
.table>tr>td, .table tbody td{padding:0.8rem 1rem !important;font-size:13px !important;vertical-align:middle;}
.wg-box{background:#fff;border:1px solid #e9ecef;border-radius:10px;padding:0 !important;overflow:hidden;margin-bottom:18px;}
.wg-box > .d-flex, .wg-box > .flex, .wg-box > h5, .wg-box .table-responsive, .wg-box .divider, .wg-box .wgp-pagination{padding-left:16px;padding-right:16px;}
.wg-box > .d-flex{padding-top:14px;padding-bottom:10px;border-bottom:1px solid #f0f0f0;margin-bottom:0 !important;}
.wg-box h5{font-size:14px;font-weight:700;color:#111;text-transform:lowercase;}
.order-info-table th,.order-info-table td{font-size:13px !important;}
.order-box{background:#fff;border:1px solid #e9ecef;border-radius:10px;overflow:hidden;margin-top:18px;}
.order-box h5{margin:0;padding:12px 16px;font-size:14px;font-weight:700;text-transform:lowercase;background:#D4AF37;color:#111;}
.order-box .my-account__address-item__detail{padding:14px 16px;}
.my-account__address-item__detail p{font-size:13px;margin:5px 0;color:#333;}
.pname .image{width:50px;height:50px;}
.pname .body-title-2{font-size:13px;}
.badge{font-size:11px;padding:4px 8px;border-radius:20px;text-transform:lowercase;}
.bg-success{background:#40c710 !important;}
.bg-danger{background:#f44032 !important;}
.bg-warning{background:#f5d700 !important;color:#000 !important;}
.bg-info{background:#0dcaf0 !important;}
.bg-primary{background:#0d6efd !important;}
/* Samakan warna status dengan admin */
.status-waiting{background:#e8efea !important;color:#33544a !important;}
.status-payment{background:#fff3d6 !important;color:#8a6100 !important;}
.status-processing{background:#e0efff !important;color:#0b5ed7 !important;}
.status-shipped{background:#d9f3fb !important;color:#087990 !important;}
.status-delivered{background:#dcf5e3 !important;color:#146c43 !important;}
.status-canceled{background:#fde2e4 !important;color:#b02a37 !important;}
.status-default{background:#e9ecef !important;color:#495057 !important;}
.badge{border-radius:999px !important;padding:6px 12px !important;font-size:11px !important;}
.wg-box.mt-5{margin-top:18px !important;}
@media(max-width:768px){.table-responsive{font-size:12px;}}
.pagination{display:flex;gap:6px;flex-wrap:wrap;margin:0}
.pagination .page-link{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border:1px solid #e7dfcb;border-radius:10px;background:#fff;color:#9c7c2a;font-size:13px;font-weight:600}
.pagination .page-link:hover{background:#F8F1DC;border-color:#D4AF37;color:#9c7c2a}
.pagination .page-item.active .page-link{background:#D4AF37;border-color:#D4AF37;color:#141414;font-weight:700}
.pagination .page-item.disabled .page-link{background:#f6f3ea;border-color:#e7dfcb;color:#b7b0a3}
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>

    <section class="my-account container">

        <h2 class="page-title">Detail Pesanan</h2>

        <div class="row">

            <div class="col-lg-2">
                @include('user.account-nav')
            </div>

            <div class="col-lg-10">

                <div class="wg-box">

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                        <h5 class="mb-0" style="text-transform:lowercase;">
                            informasi pesanan
                        </h5>

                        <a href="{{ route('user.orders') }}"
                           class="btn btn-sm btn-danger">
                            Kembali
                        </a>

                    </div>

                    @if(Session::has('status'))
                        <div class="alert alert-success">
                            {{ Session::get('status') }}
                        </div>
                    @endif

                    <div class="table-responsive">

                        <table class="table order-info-table">

                            <tbody>

                                <tr>

                                    <th>nomor pesanan</th>

                                    <td>
                                        <strong>
                                            #AZZ-{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}
                                        </strong>
                                    </td>

                                    <th>no. hp</th>

                                    <td>
                                        {{ $order->phone }}
                                    </td>

                                </tr>

                                <tr>

                                    <th>tanggal pesanan</th>

                                    <td>
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>

                                    <th>kode pos</th>

                                    <td>
                                        {{ $order->zip }}
                                    </td>

                                </tr>

                                <tr>

                                    <th>tanggal dikirim</th>

                                    <td>
                                        {{ $order->delivered_date ?? '-' }}
                                    </td>

                                    <th>tanggal dibatalkan</th>

                                    <td>
                                        {{ $order->canceled_date ?? '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <th>status pesanan</th>

                                    <td colspan="3">

                                        @switch($order->status)

                                            @case('waiting_verification')
                                                <span class="badge status-waiting">
                                                    menunggu verifikasi
                                                </span>
                                                @break

                                            @case('pending_payment')
                                                <span class="badge status-payment">
                                                    menunggu pembayaran
                                                </span>
                                                @break

                                            @case('processing')
                                                <span class="badge status-processing">
                                                    diproses
                                                </span>
                                                @break

                                            @case('shipped')
                                                <span class="badge status-shipped">
                                                    dikirim
                                                </span>
                                                @break

                                            @case('delivered')
                                                <span class="badge status-delivered">
                                                    selesai
                                                </span>
                                                @break

                                            @case('canceled')
                                                <span class="badge status-canceled">
                                                    dibatalkan
                                                </span>
                                                @break

                                            @default
                                                <span class="badge status-default">
                                                    {{ strtolower(str_replace('_',' ',$order->status)) }}
                                                </span>

                                        @endswitch

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="wg-box">

                    <div class="flex items-center justify-between gap10 flex-wrap">

                        <div class="wg-filter flex-grow">
                            <h5 style="text-transform:lowercase;">produk yang dipesan</h5>
                        </div>

                    </div>
		

    <div class="table-responsive">

        <table class="table table-striped table-bordered">

            <thead>

                <tr>
                    <th width="45%">produk</th>
                    <th width="12%" class="text-center">harga</th>
                    <th width="8%" class="text-center">jumlah</th>
                    <th width="15%" class="text-center">kategori</th>
                    <th width="10%" class="text-center">varian</th>
                    <th width="5%" class="text-center">retur</th>
                    <th width="5%" class="text-center">aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($orderItems as $item)

                <tr>

                    <td class="pname">

                        <div class="image">
                            @php
                                $detailOpt = $item->options ? json_decode($item->options, true) : [];
                                $detailImg = $detailOpt['image'] ?? null;
                            @endphp
                            <img src="{{ $detailImg ? asset('storage/products/'.$detailImg) : ($item->product ? asset('storage/products/'.$item->product->image) : asset('images/no-image.png')) }}"
                                 alt="{{ $item->product->name ?? 'Produk' }}">
                        </div>

                        <div class="name">
                            @if($item->product && $item->product->slug)
                            <a href="{{ route('shop.product.details',['product_slug'=>$item->product->slug]) }}"
                               target="_blank"
                               class="body-title-2">
                                {{ $item->product->name }}
                            </a>
                            @else
                            <span class="body-title-2">{{ $item->product->name ?? 'Produk tidak tersedia' }}</span>
                            @endif

                            <div class="mt-2">

                                <small class="text-muted">
                                    <strong>SKU :</strong>
                                    {{ $item->product->SKU ?? '-' }}
                                </small>

                                <br>

                                <small class="text-muted">
                                    <strong>Brand :</strong>
                                    {{ $item->product->brand->name ?? '-' }}
                                </small>

                            </div>

                        </div>

                    </td>

                    <td class="text-center">
                        Rp{{ number_format($item->price,0,',','.') }}
                    </td>

                    <td class="text-center">
                        {{ $item->quantity }}
                    </td>

                    <td class="text-center">
                        {{ $item->product->category->name ?? '-' }}
                    </td>

                    <td class="text-center">
                        @if(!empty($detailOpt['color']) || !empty($detailOpt['size']))
                            {{ $detailOpt['color'] ?? '-' }}{{ !empty($detailOpt['size']) ? ' • ' . $detailOpt['size'] : '' }}
                        @else
                            {{ $item->options ?: '-' }}
                        @endif
                    </td>

                    <td class="text-center">

                        @if($item->rstatus)
                            <span class="badge bg-success">Ya</span>
                        @else
                            <span class="badge bg-secondary">Tidak</span>
                        @endif

                    </td>

                    <td class="text-center">
                        @if($item->product && $item->product->slug)
                        <a href="{{ route('shop.product.details',['product_slug'=>$item->product->slug]) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-dark" title="Lihat produk">
                            <i class="fa fa-eye"></i>
                        </a>
                        @if($order->status === 'delivered')
                        <a href="{{ route('shop.product.details',['product_slug'=>$item->product->slug]) }}#tab-reviews"
                           target="_blank"
                           class="btn btn-sm btn-warning mt-1" title="Beri ulasan">
                            Nilai
                        </a>
                        @endif
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>

                </tr>

                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada produk dalam pesanan ini.</td></tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="divider"></div>

    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

        {{ $orderItems->links('pagination::bootstrap-5') }}

    </div>

</div>

<div class="order-box">
    <div class="my-account__address-item">


    <h5 style="text-transform:lowercase;">alamat pengiriman</h5>

    

        <div class="my-account__address-item__detail">

            <p><strong>Penerima :</strong> {{ $order->name }}</p>
            <p><strong>No. HP :</strong> {{ $order->phone }}</p>
            <p><strong>Alamat :</strong> {{ $order->address }}</p>
            <p><strong>Kelurahan / Kecamatan :</strong> {{ $order->locality }}</p>
            <p><strong>Kota :</strong> {{ $order->city }}</p>
            <p><strong>Provinsi :</strong> {{ $order->state }}</p>
            <p><strong>Negara :</strong> {{ $order->country }}</p>
            <p><strong>Patokan :</strong> {{ $order->landmark }}</p>
            <p><strong>Kode Pos :</strong> {{ $order->zip }}</p>

        </div>

    </div>

</div>

<div class="wg-box mt-5">

    <h5 style="text-transform:lowercase;">informasi pembayaran</h5>

    <div class="table-responsive">

        <table class="table table-transaction">
            <tbody>

                <tr>

                    <th>subtotal</th>
                    <td>Rp{{ number_format($order->subtotal,0,',','.') }}</td>

                    <th>tax</th>
                    <td>Rp{{ number_format($order->tax,0,',','.') }}</td>

                    <th>discount</th>
                    <td>Rp{{ number_format($order->discount,0,',','.') }}</td>

                </tr>

                <tr>

                    <th>total</th>
                    <td>
                        <strong>
                            Rp{{ number_format($order->total,0,',','.') }}
                        </strong>
                    </td>

                    <th>metode pembayaran</th>

                    <td>
                        @if($transaction->mode == 'transfer')
                            transfer bank
                        @else
                            {{ strtolower($transaction->mode ?? '-') }}
                        @endif
                    </td>

                    <th>status</th>

                    <td>

                        @if($transaction->status == 'approved')

                            <span class="badge bg-success">
                                approved
                            </span>

                        @elseif($transaction->status == 'declined')

                            <span class="badge bg-danger">
                                declined
                            </span>

                        @elseif($transaction->status == 'refunded')

                            <span class="badge bg-secondary">
                                refunded
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                pending
                            </span>

                        @endif

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@if(in_array($order->status,['waiting_verification','pending_payment']))

<div class="wg-box mt-4 text-end">

    <form action="{{ route('user.order.cancel') }}" method="POST">

        @csrf
        @method('PUT')

        <input type="hidden"
               name="order_id"
               value="{{ $order->id }}">

        <button type="button"
                class="btn btn-danger cancel-order">

            Batalkan Pesanan

        </button>

    </form>

</div>

@endif

</div>

</div>

</section>

</main>

@endsection


@push('scripts')

<script>

$(function () {

    $('.cancel-order').on('click', function (e) {

        e.preventDefault();

        let form = $(this).closest('form');

        swal({

            title: "Batalkan Pesanan?",
            text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
            buttons: ["Tidak", "Ya"],
            dangerMode: true,

        }).then(function (result) {

            if (result) {
                form.submit();
            }

        });

    });

});

</script>

@endpush
