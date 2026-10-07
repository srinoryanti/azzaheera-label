@extends('layouts.app')

@section('content')

<style>
    .wishlist-page{
        padding-top:165px !important;
        padding-bottom:45px;
    }

    .wishlist-title{
        margin-bottom:28px;
        color:#171717;
        font-size:28px;
        font-weight:700;
        text-align:center;
    }

    .wishlist-title::after{
        content:"";
        display:block;
        width:50px;
        height:3px;
        margin:10px auto 0;
        background:#D4AF37;
    }

    .wishlist-table-wrapper{
        width:100%;
        overflow-x:auto;
        border:1px solid #e5e5e5;
        background:#fff;
    }

    .wishlist-table{
        width:100%;
        border-collapse:collapse;
    }

    .wishlist-table thead{
        background:#171717;
    }

    .wishlist-table th{
        padding:14px 16px;
        color:#fff;
        font-size:14px;
        font-weight:600;
    }

    .wishlist-table td{
        padding:15px 16px;
        border-bottom:1px solid #eee;
        vertical-align:middle;
    }

    .wishlist-image{
        width:70px;
        height:80px;
        object-fit:cover;
    }

    .wishlist-actions{
        display:flex;
        gap:10px;
        align-items:center;
    }

    .btn-cart{
        padding:8px 14px;
        background:#D4AF37;
        border:1px solid #D4AF37;
        color:#171717;
        font-weight:600;
        cursor:pointer;
    }

    .btn-cart:hover{
        background:#9c7c2a;
        border-color:#9c7c2a;
    }

    .btn-delete{
        width:36px;
        height:36px;
        border:1px solid #ddd;
        background:#fff;
        display:flex;
        justify-content:center;
        align-items:center;
        cursor:pointer;
    }

    .btn-delete svg{
        width:12px;
        height:12px;
    }

    .btn-delete svg path{
        fill:#dc3545;
    }

    .btn-delete:hover{
        background:#dc3545;
    }

    .btn-delete:hover svg path{
        fill:#fff;
    }

    .wishlist-footer{
        display:flex;
        justify-content:flex-end;
        margin-top:20px;
    }

    .btn-clear{
        padding:10px 18px;
        background:#fff;
        border:1px solid #ccc;
        font-weight:600;
        cursor:pointer;
    }

    .btn-clear:hover{
        background:#171717;
        color:#fff;
    }

    .wishlist-empty{
        text-align:center;
        padding:45px;
        border:1px solid #eee;
        background:#fff;
    }

    .wishlist-empty p{
        margin-bottom:20px;
    }

    .btn-shop{
        display:inline-block;
        padding:10px 18px;
        background:#D4AF37;
        border:1px solid #D4AF37;
        color:#171717;
        text-decoration:none;
        font-weight:600;
    }

    .btn-shop:hover{
        background:#9c7c2a;
        border-color:#9c7c2a;
        color:#171717;
        text-decoration:none;
    }

    @media(max-width:768px){

        .wishlist-page{
            padding-top:135px !important;
        }

        .wishlist-table{
            min-width:750px;
        }

        .wishlist-footer{
            justify-content:center;
        }
    }
</style>

<main class="wishlist-page">
    <section class="container">

        <h2 class="wishlist-title">
            Produk Favorit
        </h2>

        @if(Cart::instance('wishlist')->content()->count() > 0)

        <div class="wishlist-table-wrapper">

            <table class="wishlist-table">

                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($items as $item)

                    <tr>

                        <td>
                            <img
                                src="{{ asset('storage/products/'.(optional($item->model)->image ?? 'no-image.png')) }}"
                                class="wishlist-image"
                                alt="{{ $item->name }}"
                                loading="lazy"
                                onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                        </td>

                        <td>{{ $item->name }}</td>

                        <td>
                            Rp {{ number_format($item->price,0,',','.') }}
                        </td>

                        <td>{{ $item->qty }}</td>

                        <td>

                            <div class="wishlist-actions">

                                <form action="{{ route('wishlist.move.to.cart',$item->rowId) }}" method="POST">
                                    @csrf

                                    <button type="submit" class="btn-cart">
                                        Masukkan Keranjang
                                    </button>
                                </form>

                                <form action="{{ route('wishlist.item.remove',$item->rowId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-delete">

                                        <svg viewBox="0 0 10 10">
                                            <path d="M0.259 8.855L9.114 0L10 0.886L1.145 9.741L0.259 8.855Z"/>
                                            <path d="M0.886 0.089L9.741 8.944L8.855 9.83L0 0.974L0.886 0.089Z"/>
                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="wishlist-footer">

            <form action="{{ route('wishlist.items.clear') }}" method="POST" onsubmit="return confirm('Hapus semua favorit?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-clear">Hapus Semua Favorit</button>
            </form>

        </div>

        @else

        <div class="wishlist-empty">

            <p>Belum ada produk di daftar favorit.</p>

            <a href="{{ route('home.index') }}" class="btn-shop">
                Belanja Sekarang
            </a>

        </div>

        @endif

    </section>
</main>

@endsection