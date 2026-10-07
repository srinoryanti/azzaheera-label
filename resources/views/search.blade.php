@extends('layouts.app')

@section('content')
<style>
  .srch-page { background: #faf7f0; min-height: calc(100vh - 90px); padding: 0 20px 70px; }
  .srch-wrap { padding-top: 40px; max-width: 1370px; margin: 0 auto; }

  .srch-head h2 { font-size: 26px; font-weight: 800; color: #141414; margin: 0 0 6px; }
  .srch-head p { font-size: 14.5px; color: #8a8378; margin: 0; }
  .srch-head p strong { color: #9c7c2a; }
  .srch-head { margin-bottom: 26px; }

  .srch-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
  @media (min-width: 768px) { .srch-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
  @media (min-width: 992px) { .srch-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }

  .srch-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 14px; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; }
  .srch-card:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(16,35,27,.10); }

  .srch-card__imglink { display: block; overflow: hidden; }
  .srch-card__img { width: 100%; aspect-ratio: 4 / 5; object-fit: cover; background: #f3f0e8; display: block; transition: transform .35s; }
  .srch-card:hover .srch-card__img { transform: scale(1.05); }

  .srch-card__body { padding: 12px 14px 16px; display: flex; flex-direction: column; gap: 6px; }
  .srch-card__name { font-size: 13.5px; font-weight: 700; color: #141414; line-height: 1.4; margin: 0; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.8em; }
  .srch-card__name:hover { color: #9c7c2a; }

  .srch-card__price { margin: 0; font-size: 14px; font-weight: 800; color: #9c7c2a; }
  .srch-card__price s { color: #b7b0a3; font-weight: 400; font-size: 12px; margin-right: 6px; }

  .srch-empty { text-align: center; padding: 60px 20px; background: #fff; border: 1px dashed #e7dfcb; border-radius: 16px; }
  .srch-empty img { width: 110px; margin-bottom: 14px; opacity: .85; }
  .srch-empty h4 { font-size: 19px; font-weight: 800; color: #141414; margin: 0 0 8px; }
  .srch-empty p { color: #8a8378; font-size: 14.5px; margin: 0; }

  .btn-srch { display: inline-block; margin-top: 18px; height: 46px; line-height: 46px; padding: 0 32px; border-radius: 999px; background: linear-gradient(180deg, #DFBA45, #C9A22E); color: #fff; font-weight: 700; font-size: 14px; text-decoration: none; border: none; transition: .18s; box-shadow: 0 8px 18px rgba(212,175,55,.3); }
  .btn-srch:hover { background: #9c7c2a; color: #fff; }

  .srch-pager { display: flex; justify-content: center; margin-top: 34px; }
  .srch-pager .page-link { color: #9c7c2a; border: 1px solid #e7dfcb; border-radius: 10px; margin: 0 4px; padding: 7px 14px; font-size: 14px; background: #fff; }
  .srch-pager .page-item.active .page-link { background: #D4AF37; border-color: #D4AF37; color: #141414; font-weight: 700; }
  .srch-pager .page-item.disabled .page-link { color: #b7b0a3; }

  @media (max-width: 576px) {
    .srch-page { padding: 0 14px 50px; }
    .srch-wrap { padding-top: 28px; }
    .srch-head h2 { font-size: 21px; }
  }
</style>

<main class="srch-page">
    <div class="srch-wrap">

        <div class="srch-head">
            <h2>Hasil Pencarian</h2>

            @if($query)
                <p>Menampilkan hasil untuk: <strong>"{{ $query }}"</strong></p>
            @endif
        </div>

        @if($products->count())

            <div class="srch-grid">

                @foreach($products as $product)

                    <div class="srch-card">
                        <a class="srch-card__imglink"
                            href="{{ route('shop.product.details',['product_slug'=>$product->slug]) }}">
                            <img loading="lazy"
                                src="{{ asset('storage/products/'.$product->image) }}"
                                alt="{{ $product->name }}"
                                class="srch-card__img">
                        </a>
                        <div class="srch-card__body">
                            <a class="srch-card__name"
                                href="{{ route('shop.product.details',['product_slug'=>$product->slug]) }}">
                                {{ $product->name }}
                            </a>

                            @if($product->sale_price)
                                <p class="srch-card__price">
                                    <s>Rp {{ number_format($product->regular_price,0,',','.') }}</s>
                                    Rp {{ number_format($product->sale_price,0,',','.') }}
                                </p>
                            @else
                                <p class="srch-card__price">
                                    Rp {{ number_format($product->regular_price,0,',','.') }}
                                </p>
                            @endif
                        </div>
                    </div>

                @endforeach

            </div>

            <div class="srch-pager">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        @else

            <div class="srch-empty">
                <img src="{{ asset('images/no-results.png') }}" alt="">
                <h4>Produk tidak ditemukan</h4>
                <p>Maaf, produk yang Anda cari tidak tersedia.</p>
                <a href="{{ route('shop.index') }}" class="btn-srch">Kembali ke Shop</a>
            </div>

        @endif
    </div>
</main>
@endsection
