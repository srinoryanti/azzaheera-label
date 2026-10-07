@extends('layouts.app')

@section('content')
<style>
    /* Disamakan dengan gaya "Daftar Produk" di beranda */
    .shop-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 30px;
    }
    .product-card__price s {
        font-size: 14px;
    }
    .product-card__price .fw-bold {
        color: #222;
    }
    .pc__img-wrapper {
        overflow: hidden;
        border-radius: 8px;
        background: #f5f5f5;
        position: relative;
        width: 100%;
        padding-top: 125%;
    }
    .pc__img-wrapper .pc__img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .products-grid .row {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
    }
    .products-grid .col-6 {
        display: flex;
        flex-direction: column;
    }
    .products-grid .product-card {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .products-grid .pc__img-wrapper {
        flex: none;
    }
    .products-grid .pc__info {
        flex: 1;
    }
    .pc__title.fw-bold {
        font-weight: 700 !important;
        font-size: 14px;
    }
    .pc__title a {
        color: inherit;
        text-decoration: none;
    }
    .pc__title a:hover {
        color: #9c7c2a;
    }
</style>

<main class="pt-90">
    <section class="container py-4 products-grid">

        <!-- Judul Halaman -->
        <h1 class="shop-title text-center">Koleksi Produk</h1>

        <div class="row">
            <div class="col-lg-12">
                <div class="row justify-content-center">

                    @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">

                        <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">

                            <!-- Gambar Produk -->
                            <div class="pc__img-wrapper">
                                <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                                    <img
                                        loading="lazy" decoding="async" width="360" height="450"
                                        src="{{ asset('storage/products/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="pc__img"
                                        onerror="this.style.display='none'"
                                    >
                                </a>
                            </div>

                            <!-- Info Produk -->
                            <div class="pc__info position-relative">

                                <h6 class="pc__title fw-bold">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <!-- Harga -->
                                <div class="product-card__price d-flex align-items-center">
                                    <span class="money price text-secondary">
                                        @if ($product->sale_price && $product->sale_price < $product->regular_price)
                                            <s class="text-muted me-2">Rp {{ number_format($product->regular_price, 0, ',', '.') }}</s>
                                            <span>Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                        @else
                                            <span>Rp {{ number_format($product->regular_price, 0, ',', '.') }}</span>
                                        @endif
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>
                    @empty

                    <div class="col-12">
                        <div class="text-center py-5">
                            <p style="color: #888; font-size: 1rem;">Belum ada produk tersedia.</p>
                            <a href="{{ route('home.index') }}" class="btn px-4 py-2" style="background-color: #D4AF37; color: #fff; font-weight: 500;">Kembali ke Beranda</a>
                        </div>
                    </div>

                    @endforelse

                </div>
                @if(method_exists($products, 'links') && $products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>

    </section>
</main>
@endsection
