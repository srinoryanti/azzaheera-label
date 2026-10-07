@extends('layouts.app')

@section('content')
    <style>
        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem !important;
            background: #141414 !important;
            color: #fff !important;
            border-color:#141414 !important;
            text-transform:uppercase;
        }
        .table th{ text-transform:uppercase !important; }
        .table-bordered>:not(caption)>tr>th{ border-color:#B8962E !important; }
        .badge{ text-transform:lowercase; }
        .status-waiting{background:#e8efea !important;color:#33544a !important;}
        .status-payment{background:#fff3d6 !important;color:#8a6100 !important;}
        .status-processing{background:#e0efff !important;color:#0b5ed7 !important;}
        .status-shipped{background:#d9f3fb !important;color:#087990 !important;}
        .status-delivered{background:#dcf5e3 !important;color:#146c43 !important;}
        .status-canceled{background:#fde2e4 !important;color:#b02a37 !important;}
        .status-ordered{background:#e9ecef !important;color:#495057 !important;}
        .badge{border-radius:999px !important;padding:6px 12px !important;font-size:11px !important;}
        .page-title{ text-transform:lowercase !important; }

        .table>tr>td {
            padding: 0.8rem 1rem !important;
        }

        .table-bordered>:not(caption)>tr>th,
        .table-bordered>:not(caption)>tr>td {
            border-color: #6a6e51;
        }

        .bg-success {
            background: #40c710 !important;
        }

        .bg-danger {
            background: #f44032 !important;
        }

        .bg-warning {
            background: #f5d700 !important;
            color: #000 !important;
        }

        .bg-info {
            background: #0dcaf0 !important;
        }

        .bg-primary {
            background: #0d6efd !important;
        }

        .pagination { display: flex; gap: 6px; flex-wrap: wrap; margin: 0; }
        .pagination .page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px; border: 1px solid #e7dfcb; border-radius: 10px; background: #fff; color: #9c7c2a; font-size: 13px; font-weight: 600; }
        .pagination .page-link:hover { background: #F8F1DC; border-color: #D4AF37; color: #9c7c2a; }
        .pagination .page-item.active .page-link { background: #D4AF37; border-color: #D4AF37; color: #141414; font-weight: 700; }
        .pagination .page-item.disabled .page-link { background: #f6f3ea; border-color: #e7dfcb; color: #b7b0a3; }
    </style>

    <main class="pt-90">

        <div class="mb-4 pb-4"></div>

        <section class="my-account container">

            <h2 class="page-title">Pesanan Saya</h2>

            <div class="row">

                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">

                    <div class="wg-table table-all-user">

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead>

                                    <tr>
                                        <th width="60">no</th>
                                        <th>nama</th>
                                        <th>no hp</th>
                                        <th>subtotal</th>
                                        <th>total</th>
                                        <th>status</th>
                                        <th>tanggal pesanan</th>
                                        <th>jumlah</th>
                                        <th>dikirim pada</th>
                                        <th>aksi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($orders as $order)
                                        <tr>

                                            <td class="text-center">
                                                {{ $order->id }}
                                            </td>

                                            <td>
                                                {{ $order->name }}
                                            </td>

                                            <td>
                                                {{ $order->phone }}
                                            </td>

                                            <td>
                                                Rp{{ number_format($order->subtotal, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                <strong>
                                                    Rp{{ number_format($order->total, 0, ',', '.') }}
                                                </strong>
                                            </td>

                                            <td class="text-center">

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
                                                        <span class="badge status-ordered">
                                                            {{ strtolower(str_replace('_',' ',$order->status)) }}
                                                        </span>
                                                @endswitch

                                            </td>

                                            <td>
                                                {{ $order->created_at->format('d M Y H:i') }}
                                            </td>

                                            <td class="text-center">
                                                {{ $order->order_items_count ?? $order->orderItems->count() }}
                                            </td>

                                            <td class="text-center">

                                                @if ($order->delivered_date)
                                                    {{ \Carbon\Carbon::parse($order->delivered_date)->format('d M Y') }}
                                                @else
                                                    -
                                                @endif

                                            </td>

                                            <td class="text-center">

                                                <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}"
                                                    class="btn btn-sm btn-dark">
                                                    Detail
                                                </a>

                                            </td>

                                        </tr>

                                        @empty

                                            <tr>

                                                <td colspan="10" class="text-center py-5">

                                                    <h5>Belum ada pesanan.</h5>

                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="divider"></div>

                        <div class="d-flex justify-content-center">

                            {{ $orders->links('pagination::bootstrap-5') }}

                        </div>

                    </div>

                </div>

            </section>

        </main>
    @endsection
