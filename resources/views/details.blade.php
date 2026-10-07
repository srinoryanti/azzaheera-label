@extends('layouts.app')

@section('content')
<style>
  .pd-page { background: #faf7f0; min-height: 60vh; }
  .pd-wrap { max-width: 1200px; margin: 0 auto; padding: 28px 20px 60px; }
  .pd-breadcrumb { font-size: 13px; color: #8a8378; margin-bottom: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .pd-breadcrumb a { color: #8a8378; text-decoration: none; }
  .pd-breadcrumb a:hover { color: #9c7c2a; }
  .pd-breadcrumb span.current { color: #141414; font-weight: 700; }

  .pd-grid { display: grid; grid-template-columns: 460px 1fr; gap: 22px; align-items: stretch; }
  /* min-width:0 mencegah konten (thumbnail strip) mendorong grid item melebihi viewport */
  .pd-grid > .pd-card { min-width: 0; }
  @media (max-width: 992px) { .pd-grid { grid-template-columns: 1fr; } }

  .pd-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 18px; padding: 20px; box-shadow: 0 8px 24px rgba(16,35,27,.05); }

  /* Galeri */
  .product-main-wrap { position: relative; min-width: 0; }
  .product-main-image img { width: 100%; aspect-ratio: 4/5; object-fit: cover; border-radius: 14px; background: #f3f0e8; transition: opacity .18s ease; display: block; }
  .product-main-image img.is-fading { opacity: 0; }
  .gallery-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,.95); border: 1px solid #eee; color: #171717; font-size: 20px; line-height: 1; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,.12); cursor: pointer; z-index: 2; transition: .15s; }
  .gallery-arrow:hover { background: #D4AF37; border-color: #D4AF37; color: #fff; }
  .gallery-prev { left: 12px; }
  .gallery-next { right: 12px; }
  .gallery-counter { position: absolute; right: 12px; bottom: 12px; background: rgba(0,0,0,.55); color: #fff; font-size: 12px; padding: 3px 10px; border-radius: 20px; z-index: 2; }
  .product-thumbnails { display: flex; gap: 10px; margin-top: 12px; overflow-x: auto; padding-bottom: 2px; }
  .thumb-img { width: 72px; height: 86px; object-fit: cover; border: 2px solid #e5e5e5; border-radius: 10px; cursor: pointer; transition: .2s; flex-shrink: 0; background: #f3f0e8; }
  .thumb-img:hover, .thumb-img.active { border-color: #d4af37; box-shadow: 0 4px 12px rgba(212,175,55,.25); }

  /* Info */
  .pd-title { font-size: 22px; font-weight: 800; color: #141414; line-height: 1.4; margin: 0 0 10px; }
  .pd-submeta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; font-size: 12.5px; color: #8a8378; margin-bottom: 14px; }
  .pd-submeta .sep { width: 1px; height: 14px; background: #e0dccf; }
  .pd-submeta .stock-ok { color: #146c43; font-weight: 700; }
  .pd-submeta .stock-low { color: #c2410c; font-weight: 700; }
  .pd-submeta .stock-out { color: #b91c1c; font-weight: 700; }
  .reviews-group { display: inline-flex; gap: 2px; align-items: center; }
  .review-star { width: 14px; height: 14px; fill: #D4AF37; }
  .review-star--empty { fill: #E0E0E0 !important; }

  .pd-pricebox { background: #F8F1DC; border: 1px solid #e7dfcb; border-radius: 14px; padding: 16px 18px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
  .pd-price { font-size: 30px; font-weight: 800; color: #141414; letter-spacing: -.5px; }
  .pd-price s { font-size: 15px; font-weight: 400; color: #8a8378; margin-right: 4px; }
  .pd-disc { background: #141414; color: #D4AF37; font-size: 12px; font-weight: 800; padding: 4px 11px; border-radius: 999px; }

  .pd-shortdesc { font-size: 13.5px; color: #4A4A4A; line-height: 1.7; margin: 0 0 16px; }

  .product-colors { margin: 0 0 16px; }
  .product-colors__label { font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8a8378; margin-bottom: 9px; display: block; }
  .product-colors__label strong { color: #141414; }
  .product-colors__dots { display: flex; gap: 12px; flex-wrap: wrap; }
  /* Gaya Shopee: kotak gambar + teks nama warna di bawah gambar */
  .product-color-dot { width: 76px; border-radius: 12px; border: 2px solid #e5e5e5; cursor: pointer; transition: .2s; padding: 0; overflow: hidden; background: #fff; display: flex; flex-direction: column; align-items: stretch; }
  .product-color-dot__img { width: 100%; height: 84px; object-fit: cover; display: block; background: #f3f0e8; }
  .product-color-dot__initial { width: 100%; height: 84px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: #9c7c2a; background: #f3f0e8; }
  .product-color-dot__name { display: block; font-size: 11px; font-weight: 600; color: #4A4A4A; text-align: center; padding: 5px 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; border-top: 1px solid #f0ece1; }
  .product-color-dot:hover { transform: translateY(-2px); border-color: #d4af37; }
  .product-color-dot.active { border-color: #d4af37; box-shadow: 0 0 0 2px rgba(212,175,55,.35); }
  .product-color-dot.active .product-color-dot__name { color: #9c7c2a; font-weight: 800; }

  .product-sizes { margin: 0 0 16px; }
  .product-sizes__label { font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8a8378; margin-bottom: 9px; display: block; }
  .product-sizes__label strong { color: #141414; }
  .product-sizes__list { display: flex; gap: 8px; flex-wrap: wrap; }
  .size-chip { min-width: 44px; height: 40px; padding: 0 14px; border-radius: 10px; border: 1.5px solid #e7dfcb; background: #fff; font-size: 13.5px; font-weight: 700; color: #141414; cursor: pointer; transition: .15s; }
  .size-chip:hover { border-color: #D4AF37; }
  .size-chip.active { background: #141414; border-color: #141414; color: #fff; }

  .pd-buyrow { display: flex; align-items: stretch; gap: 12px; margin: 4px 0 6px; flex-wrap: wrap; }
  /* width:auto + position:static meng-override aturan theme (.qty-control{width:3.375rem} & tombol absolute) yang bikin kontrol gepeng */
  .qty-control { display: inline-flex; align-items: center; border: 1.5px solid #e7dfcb; border-radius: 999px; overflow: hidden; background: #fff; height: 48px; width: auto; position: relative; flex-shrink: 0; }
  .qty-control__number { width: 52px; border: none; text-align: center; font-weight: 800; font-size: 15px; color: #141414; outline: none; background: transparent; height: 100%; -moz-appearance: textfield; appearance: textfield; }
  .qty-control__number::-webkit-outer-spin-button, .qty-control__number::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  .qty-control__reduce, .qty-control__increase { position: static; width: 42px; height: 100%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 19px; font-weight: 800; color: #141414; cursor: pointer; user-select: none; background: transparent; border: none; }
  .qty-control__reduce:hover, .qty-control__increase:hover { color: #9c7c2a; }
  .btn-addtocart { flex: 1; min-width: 200px; height: 48px; border: none; border-radius: 999px; background: #D4AF37; color: #141414; font-weight: 800; font-size: 13.5px; letter-spacing: .4px; cursor: pointer; transition: .18s; display: inline-flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; padding: 0 26px; box-sizing: border-box; }
  .btn-addtocart:hover { background: #9c7c2a; color: #fff; }
  .btn-addtocart:disabled { background: #E9E4D6; color: #9a948a; cursor: not-allowed; }
  .btn-wish { width: 48px; height: 48px; flex-shrink: 0; border-radius: 50%; border: 1.5px solid #e7dfcb; background: #fff; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #141414; transition: .15s; text-decoration: none; }
  .btn-wish:hover { border-color: #D4AF37; background: #F8F1DC; }
  .btn-wish.filled-heart { color: #e07b00; border-color: #D4AF37; background: #F8F1DC; }
  .btn-wish svg { width: 20px; height: 20px; }

  .pd-meta { margin-top: 16px; border-top: 1px solid #f0ece1; padding-top: 14px; display: flex; flex-direction: column; gap: 7px; font-size: 12.5px; }
  .pd-meta .meta-item { display: flex; gap: 8px; color: #8a8378; }
  .pd-meta .meta-item label { min-width: 70px; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: .4px; padding-top: 1px; }
  .pd-meta .meta-item span { color: #4A4A4A; }

  /* Tabs */
  .pd-tabs { margin-top: 22px; }
  .pd-tabs .nav-tabs { border: none; display: flex; gap: 8px; margin-bottom: 0; background: #fff; border: 1px solid #EAEAEA; border-radius: 14px; padding: 6px; width: fit-content; }
  .pd-tabs .nav-link_underscore { border: none; border-radius: 10px; padding: 9px 22px; font-size: 13px; font-weight: 700; color: #8a8378; text-transform: uppercase; letter-spacing: .4px; }
  .pd-tabs .nav-link_underscore.active { background: #141414; color: #fff; }
  .pd-tabbody { background: #fff; border: 1px solid #EAEAEA; border-radius: 18px; padding: 24px; margin-top: 14px; box-shadow: 0 8px 24px rgba(16,35,27,.05); font-size: 14px; line-height: 1.8; color: #333; }

  .product-reviews { display: flex; flex-direction: column; gap: 14px; }
  .product-review-item { display: flex; gap: 14px; padding: 16px 18px; border: 1px solid #eee; border-radius: 12px; background: #fff; }
  .product-review-avatar { flex-shrink: 0; width: 42px; height: 42px; border-radius: 50%; background: #D4AF37; color: #fff; font-weight: 700; font-size: 18px; display: flex; align-items: center; justify-content: center; }
  .product-review-body { flex: 1; min-width: 0; }
  .product-review-head { display: flex; align-items: baseline; gap: 10px; margin-bottom: 4px; }
  .product-review-head strong { font-size: 14px; color: #171717; }
  .product-review-date { font-size: 12px; color: #999; }
  .product-review-stars { margin-bottom: 6px; }
  .product-review-text { margin: 0; font-size: 14px; line-height: 1.7; color: #333; text-align: left; }

  /* Produk terkait */
  .pd-related { margin-top: 28px; }
  .pd-related__title { font-size: 17px; font-weight: 800; color: #141414; margin: 0 0 14px; }
  .pd-related__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
  .rel-card { display: block; background: #fff; border: 1px solid #EAEAEA; border-radius: 14px; overflow: hidden; text-decoration: none; color: inherit; transition: .2s; }
  .rel-card:hover { transform: translateY(-3px); box-shadow: 0 10px 26px rgba(16,35,27,.10); }
  .rel-card img { width: 100%; aspect-ratio: 4/5; object-fit: cover; background: #f3f0e8; display: block; }
  .rel-card__body { padding: 10px 12px 14px; }
  .rel-card__name { font-size: 13px; font-weight: 700; color: #141414; line-height: 1.4; margin: 0 0 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.8em; }
  .rel-card__price { font-size: 13.5px; font-weight: 800; color: #9c7c2a; margin: 0; }
  .rel-card__price s { font-size: 11.5px; font-weight: 400; color: #9c9385; margin-right: 4px; }

  /* Sticky bar mobile */
  .pd-stickybar { position: fixed; left: 0; right: 0; bottom: 0; z-index: 950; background: #fff; border-top: 1px solid #EAEAEA; box-shadow: 0 -6px 20px rgba(0,0,0,.10); padding: 10px 14px calc(10px + env(safe-area-inset-bottom)); display: none; align-items: center; gap: 12px; transform: translateY(110%); transition: transform .25s ease; }
  .pd-stickybar.show { transform: translateY(0); }
  .pd-stickybar__info { flex: 1; min-width: 0; }
  .pd-stickybar__label { font-size: 10.5px; color: #8a8378; text-transform: uppercase; letter-spacing: .4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
  .pd-stickybar__price { font-size: 16px; font-weight: 800; color: #141414; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
  .pd-stickybar__cta { height: 44px; padding: 0 20px; border: none; border-radius: 999px; background: #D4AF37; color: #141414; font-weight: 800; font-size: 13px; cursor: pointer; flex-shrink: 0; white-space: nowrap; }
  .pd-stickybar__cta:active { background: #9c7c2a; color: #fff; }
  .pd-stickybar__cta:disabled { background: #E9E4D6; color: #9a948a; }

  @media (max-width: 992px) {
    .pd-related__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }

  @media (max-width: 768px) {
    .pd-stickybar { display: flex; }
    .pd-wrap { padding-bottom: 96px; }
  }

  @media (max-width: 640px) {
    .pd-card { padding: 15px; }
    .pd-title { font-size: 18px; }
    .pd-price { font-size: 25px; }
    .pd-pricebox { padding: 13px 14px; }
    .btn-addtocart { min-width: 0; }
    /* qty full-width di baris tersendiri, tombol + wishlist di baris bawah */
    .pd-buyrow .qty-control { flex: 1 0 100%; width: 100%; }
    .pd-buyrow .qty-control__number { flex: 1; width: auto; }
    .pd-buyrow .btn-addtocart { min-width: 0; }
    .gallery-arrow { width: 34px; height: 34px; font-size: 18px; }
    .pd-tabs .nav-link_underscore { padding: 8px 14px; font-size: 12px; }
    .pd-tabbody { padding: 16px; }
    .product-review-item { padding: 13px 14px; gap: 10px; }
    .rel-card__name { font-size: 12.5px; }
  }
</style>

<main class="pd-page pt-90">
  <div class="pd-wrap">
    <div class="pd-breadcrumb">
      <a href="{{ route('home.index') }}">Beranda</a> &nbsp;/&nbsp;
      <a href="{{ route('shop.index') }}">Shop</a> &nbsp;/&nbsp;
      <span class="current">{{ $product->name }}</span>
    </div>

    @php
      $reviewCount = $product->reviews->count();
      $avgRating = $reviewCount > 0 ? (int) round($product->reviews->avg('rating')) : 0;
      $isSale = $product->sale_price && $product->sale_price < $product->regular_price;
      $activePrice = $isSale ? $product->sale_price : $product->regular_price;
      $discPct = $isSale ? (int) round(($product->regular_price - $product->sale_price) / $product->regular_price * 100) : 0;
      $galleryCount = 1 + ($product->images ? count(array_filter(array_map('trim', explode(',', $product->images)))) : 0);
      $sizes = $product->sizes ? array_values(array_filter(array_map('trim', explode(',', $product->sizes)), fn ($v) => $v !== '')) : [];
      $hasSizes = count($sizes) > 0;
      $outOfStock = $product->quantity <= 0;
    @endphp

    <div class="pd-grid">
      {{-- Galeri --}}
      <div class="pd-card">
        <div class="product-main-wrap">
          <div class="product-main-image">
            <img id="mainProductImage" src="{{ asset('storage/products/' . $product->image) }}"
                 alt="{{ $product->name }}" loading="eager" decoding="async">
          </div>
          @if ($galleryCount > 1)
            <button type="button" class="gallery-arrow gallery-prev" aria-label="Foto sebelumnya" onclick="moveGalleryImage(-1)">&#8249;</button>
            <button type="button" class="gallery-arrow gallery-next" aria-label="Foto berikutnya" onclick="moveGalleryImage(1)">&#8250;</button>
            <span class="gallery-counter"><span id="galleryCurrent">1</span> / {{ $galleryCount }}</span>
          @endif
        </div>
        <div class="product-thumbnails">
          <img src="{{ asset('storage/products/' . $product->image) }}" class="thumb-img active"
               loading="eager" decoding="async" onclick="changeProductImage(this)" alt="{{ $product->name }}">
          @if(!empty($product->images))
            @foreach(explode(',', $product->images) as $gimg)
              @php $gimg = trim($gimg); @endphp
              @if(!empty($gimg))
                <img src="{{ asset('storage/products/' . $gimg) }}" class="thumb-img"
                     loading="lazy" decoding="async" onclick="changeProductImage(this)" alt="{{ $product->name }}">
              @endif
            @endforeach
          @endif
        </div>
      </div>

      {{-- Info --}}
      <div class="pd-card">
        <h1 class="pd-title">{{ $product->name }}</h1>

        <div class="pd-submeta">
          @if($reviewCount > 0)
            <span class="reviews-group">
              @for ($i = 0; $i < 5; $i++)
                <svg class="review-star {{ $i < $avgRating ? '' : 'review-star--empty' }}" viewBox="0 0 9 9"><use href="#icon_star" /></svg>
              @endfor
            </span>
            <span>{{ $reviewCount }} ulasan</span>
            <span class="sep"></span>
          @else
            <span>Belum ada ulasan</span>
            <span class="sep"></span>
          @endif
          @if($product->quantity <= 0)
            <span class="stock-out">Stok Habis</span>
          @elseif($product->quantity <= 5)
            <span class="stock-low"><i class="fa fa-fire" aria-hidden="true"></i> Tersisa {{ $product->quantity }} pcs</span>
          @else
            <span class="stock-ok">Stok: {{ $product->quantity }}</span>
          @endif
        </div>

        <div class="pd-pricebox">
          <span class="pd-price">
            @if($isSale)<s>Rp {{ number_format($product->regular_price, 0, ',', '.') }}</s>@endif
            Rp {{ number_format($activePrice, 0, ',', '.') }}
          </span>
          @if($isSale)<span class="pd-disc">-{{ $discPct }}%</span>@endif
        </div>

        @if($product->short_description)
          <p class="pd-shortdesc">{{ $product->short_description }}</p>
        @endif

        @if($product->colors && $product->colors->count() > 0)
          <div class="product-colors">
            <span class="product-colors__label">Warna: <strong id="selectedColorName">{{ $product->colors->first()->color }}</strong></span>
            <div class="product-colors__dots">
              @foreach($product->colors as $pc)
                <button type="button" class="product-color-dot {{ $loop->first ? 'active' : '' }}"
                  title="{{ $pc->color }}" aria-label="Warna {{ $pc->color }}" data-color="{{ $pc->color }}"
                  @if($pc->image) data-image="{{ asset('storage/products/' . $pc->image) }}" @endif
                  onclick="selectProductColor(this)">
                  @if($pc->image)
                    <img class="product-color-dot__img" src="{{ asset('storage/products/' . $pc->image) }}" alt="{{ $pc->color }}" loading="lazy" decoding="async">
                  @else
                    <span class="product-color-dot__initial">{{ strtoupper(mb_substr($pc->color, 0, 1)) }}</span>
                  @endif
                  <span class="product-color-dot__name">{{ $pc->color }}</span>
                </button>
              @endforeach
            </div>
          </div>
        @endif

        @if($hasSizes)
          <div class="product-sizes">
            <span class="product-sizes__label">Ukuran: <strong id="selectedSizeName">{{ $sizes[0] }}</strong></span>
            <div class="product-sizes__list">
              @foreach($sizes as $sz)
                <button type="button" class="size-chip {{ $loop->first ? 'active' : '' }}"
                  data-size="{{ $sz }}" aria-label="Ukuran {{ $sz }}"
                  onclick="selectProductSize(this)">{{ $sz }}</button>
              @endforeach
            </div>
          </div>
        @endif

        @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
          <div class="pd-buyrow">
            <a href="{{ route('cart.index') }}" class="btn-addtocart">Lihat Keranjang</a>
            @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
              <a href="javascript:void(0)" class="btn-wish filled-heart" title="Hapus dari favorit" onclick="document.getElementById('frm-remove-item').submit();">
                <svg viewBox="0 0 20 20" fill="currentColor"><use href="#icon_heart" /></svg>
              </a>
            @else
              <a href="javascript:void(0)" class="btn-wish" title="Tambah ke favorit" onclick="document.getElementById('wishlist-form').submit();">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><use href="#icon_heart" /></svg>
              </a>
            @endif
          </div>
        @else
          <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
            @csrf
            <div class="pd-buyrow">
              <div class="qty-control position-relative">
                <div class="qty-control__reduce">-</div>
                <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                <div class="qty-control__increase">+</div>
              </div>
              <input type="hidden" name="id" value="{{ $product->id }}" />
              <input type="hidden" name="name" value="{{ $product->name }}" />
              <input type="hidden" name="price" value="{{ $activePrice }}" />
              @if($product->colors && $product->colors->count() > 0)
                <input type="hidden" name="color" id="selectedColorInput" value="{{ $product->colors->first()->color }}" />
              @endif
              @if($hasSizes)
                <input type="hidden" name="size" id="selectedSizeInput" value="{{ $sizes[0] }}" />
              @endif
              <button type="submit" class="btn-addtocart" {{ $outOfStock ? 'disabled' : '' }}>
                @if($outOfStock)
                  Stok Habis
                @else
                  <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h12l1.5 13H4.5L6 7z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
                  Masukkan Keranjang
                @endif
              </button>
              @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                <a href="javascript:void(0)" class="btn-wish filled-heart" title="Hapus dari favorit" onclick="document.getElementById('frm-remove-item').submit();">
                  <svg viewBox="0 0 20 20" fill="currentColor"><use href="#icon_heart" /></svg>
                </a>
              @else
                <a href="javascript:void(0)" class="btn-wish" title="Tambah ke favorit" onclick="document.getElementById('wishlist-form').submit();">
                  <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><use href="#icon_heart" /></svg>
                </a>
              @endif
            </div>
          </form>
        @endif

        @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
          <form method="POST" action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}" id="frm-remove-item">
            @csrf @method('DELETE')
          </form>
        @else
          <form method="POST" action="{{ route('wishlist.add') }}" id="wishlist-form">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}" />
            <input type="hidden" name="name" value="{{ $product->name }}" />
            <input type="hidden" name="price" value="{{ $activePrice }}" />
            <input type="hidden" name="quantity" value="1" />
          </form>
        @endif

        <div class="pd-meta">
          <div class="meta-item"><label>SKU</label><span>{{ $product->SKU }}</span></div>
          <div class="meta-item"><label>Kategori</label><span>{{ optional($product->category)->name ?? '-' }}</span></div>
        </div>
      </div>
    </div>

    {{-- Tabs --}}
    <div class="pd-tabs">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab" href="#tab-description" role="tab">Deskripsi</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab" href="#tab-reviews" role="tab">Ulasan ({{ $reviewCount }})</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active pd-tabbody" id="tab-description" role="tabpanel">
          {!! nl2br(e($product->description)) !!}
        </div>
        <div class="tab-pane fade pd-tabbody" id="tab-reviews" role="tabpanel">
          {{-- Form ulasan pembeli — hanya untuk yang sudah beli & terima produk ini --}}
          @auth
            @if(!empty($userReview))
              <div class="alert alert-success" style="border-radius:12px;font-size:13.5px;">
                Anda sudah menilai produk ini ({{ $userReview->rating }}/5). Mengirim lagi akan memperbarui ulasan Anda.
              </div>
            @endif
            @if(!empty($canReview))
              <form method="POST" action="{{ route('reviews.store') }}" class="review-form mb-4" style="background:#faf7f0;border:1px solid #e7dfcb;border-radius:14px;padding:18px;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <p class="fw-bold mb-2" style="font-size:14px;color:#141414;">Tulis ulasan Anda</p>
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                  <span style="font-size:13px;font-weight:600;color:#4A4A4A;">Rating Anda:</span>
                  <span class="review-stars" role="radiogroup" aria-label="Rating">
                    @for($r = 5; $r >= 1; $r--)
                      <input type="radio" name="rating" id="star{{ $r }}" value="{{ $r }}" {{ (int) old('rating', optional($userReview)->rating ?? 5) === $r ? 'checked' : '' }} required>
                      <label for="star{{ $r }}" title="{{ $r }} bintang">
                        <svg class="review-star review-star--input" viewBox="0 0 9 9"><use href="#icon_star" /></svg>
                      </label>
                    @endfor
                  </span>
                  <strong id="ratingLabel" style="font-size:13px;color:#9c7c2a;">{{ old('rating', optional($userReview)->rating ?? 5) }}/5</strong>
                </div>
                <textarea name="review" rows="4" class="form-control" style="border-radius:12px;" placeholder="Bagaimana kualitas produk ini? Tulis pengalaman Anda…" spellcheck="false" autocomplete="off" autocapitalize="sentences" required>{{ old('review', optional($userReview)->review) }}</textarea>
                @error('review')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <button type="submit" class="btn-addtocart mt-3" style="min-width:0;">Kirim Ulasan</button>
              </form>
              <style>
                .review-form .review-stars { display: inline-flex; flex-direction: row-reverse; gap: 4px; }
                .review-form .review-stars input { position: absolute; opacity: 0; pointer-events: none; }
                .review-form .review-stars label { cursor: pointer; margin: 0; }
                .review-form .review-star--input { width: 26px; height: 26px; fill: #E0E0E0; transition: fill .12s ease, transform .12s ease; }
                .review-form .review-stars label:hover .review-star--input,
                .review-form .review-stars label:hover ~ label .review-star--input { fill: #D4AF37; }
                .review-form .review-stars input:checked ~ label .review-star--input { fill: #D4AF37; }
                .review-form .review-stars input:focus-visible + label .review-star--input { outline: 2px solid #D4AF37; outline-offset: 2px; }
              </style>
              <script>
                (function() {
                  var wrap = document.querySelector('.review-form .review-stars');
                  if (!wrap) return;
                  var label = document.getElementById('ratingLabel');
                  wrap.addEventListener('change', function(e) {
                    if (label && e.target && e.target.value) label.textContent = e.target.value + '/5';
                  });
                })();
              </script>
            @else
              <div class="alert alert-light" style="border:1px solid #eee;border-radius:12px;font-size:13.5px;">
                Hanya pembeli yang sudah menerima produk ini yang bisa memberi ulasan.
              </div>
            @endif
          @else
            <p style="font-size:13.5px;"><a href="{{ route('login') }}" style="font-weight:700;color:#9c7c2a;">Masuk</a> untuk menilai produk yang sudah Anda beli.</p>
          @endauth
          <div class="product-reviews">
            @forelse ($product->reviews->sortByDesc('created_at') as $review)
              <div class="product-review-item">
                <div class="product-review-avatar">{{ strtoupper(mb_substr($review->name, 0, 1)) }}</div>
                <div class="product-review-body">
                  <div class="product-review-head">
                    <strong>{{ $review->name }}</strong>
                    @if($review->user_id)<span class="badge" style="background:#dcf5e3;color:#146c43;font-size:10px;">Pembeli terverifikasi</span>@endif
                    <span class="product-review-date">{{ $review->created_at->format('d M Y') }}</span>
                  </div>
                  <div class="reviews-group product-review-stars">
                    @for ($i = 0; $i < 5; $i++)
                      <svg class="review-star {{ $i < $review->rating ? '' : 'review-star--empty' }}" viewBox="0 0 9 9"><use href="#icon_star" /></svg>
                    @endfor
                  </div>
                  <p class="product-review-text">{{ $review->review }}</p>
                </div>
              </div>
            @empty
              <p class="text-secondary mb-0">Belum ada ulasan untuk produk ini.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- Produk terkait --}}
    @if($related && $related->count() > 0)
      <section class="pd-related">
        <h2 class="pd-related__title">Produk Terkait</h2>
        <div class="pd-related__grid">
          @foreach($related as $rel)
            @php
              $relSale = $rel->sale_price && $rel->sale_price < $rel->regular_price;
              $relPrice = $relSale ? $rel->sale_price : $rel->regular_price;
            @endphp
            <a class="rel-card" href="{{ route('shop.product.details', $rel->slug) }}">
              <img src="{{ asset('storage/products/' . $rel->image) }}" alt="{{ $rel->name }}" loading="lazy" decoding="async">
              <div class="rel-card__body">
                <p class="rel-card__name">{{ $rel->name }}</p>
                <p class="rel-card__price">
                  @if($relSale)<s>Rp {{ number_format($rel->regular_price, 0, ',', '.') }}</s>@endif
                  Rp {{ number_format($relPrice, 0, ',', '.') }}
                </p>
              </div>
            </a>
          @endforeach
        </div>
      </section>
    @endif

  </div>
</main>

{{-- Sticky bar mobile --}}
<div class="pd-stickybar" id="pdStickyBar">
  <div class="pd-stickybar__info">
    <span class="pd-stickybar__label">{{ $product->name }}</span>
    <span class="pd-stickybar__price">Rp {{ number_format($activePrice, 0, ',', '.') }}</span>
  </div>
  @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
    <button type="button" class="pd-stickybar__cta" onclick="window.location.href='{{ route('cart.index') }}'">Lihat Keranjang</button>
  @elseif($outOfStock)
    <button type="button" class="pd-stickybar__cta" disabled>Stok Habis</button>
  @else
    <button type="button" class="pd-stickybar__cta" onclick="stickyAddToCart()">Masukkan Keranjang</button>
  @endif
</div>

<script>
    var galleryThumbs = Array.prototype.slice.call(document.querySelectorAll('.thumb-img'));
    var galleryIndex = 0;

    function showGalleryImage(index) {
        if (!galleryThumbs.length) return;
        galleryIndex = (index + galleryThumbs.length) % galleryThumbs.length;

        var main = document.getElementById('mainProductImage');
        var src = galleryThumbs[galleryIndex].getAttribute('src');

        main.classList.add('is-fading');
        setTimeout(function() {
            main.src = src;
            main.classList.remove('is-fading');
        }, 160);

        galleryThumbs.forEach(function(img, i) {
            img.classList.toggle('active', i === galleryIndex);
        });

        var counter = document.getElementById('galleryCurrent');
        if (counter) counter.textContent = galleryIndex + 1;
    }

    function moveGalleryImage(step) {
        showGalleryImage(galleryIndex + step);
    }

    function changeProductImage(element) {
        showGalleryImage(galleryThumbs.indexOf(element));
    }

    /* Pilihan warna dinamis: ganti label, hidden input, dan foto utama */
    function selectProductColor(element) {
        var dots = Array.prototype.slice.call(document.querySelectorAll('.product-color-dot'));
        dots.forEach(function(dot) { dot.classList.remove('active'); });
        element.classList.add('active');

        var colorName = element.getAttribute('data-color');
        var label = document.getElementById('selectedColorName');
        if (label) label.textContent = colorName;

        var input = document.getElementById('selectedColorInput');
        if (input) input.value = colorName;

        var colorImage = element.getAttribute('data-image');
        if (colorImage) {
            var main = document.getElementById('mainProductImage');
            main.classList.add('is-fading');
            setTimeout(function() {
                main.src = colorImage;
                main.classList.remove('is-fading');
            }, 160);
            galleryThumbs.forEach(function(img) { img.classList.remove('active'); });
        }
    }

    /* Pilihan ukuran: ganti label + hidden input */
    function selectProductSize(element) {
        var chips = Array.prototype.slice.call(document.querySelectorAll('.size-chip'));
        chips.forEach(function(chip) { chip.classList.remove('active'); });
        element.classList.add('active');

        var sizeName = element.getAttribute('data-size');
        var label = document.getElementById('selectedSizeName');
        if (label) label.textContent = sizeName;

        var input = document.getElementById('selectedSizeInput');
        if (input) input.value = sizeName;
    }

    /* Sticky bar mobile: muncul setelah scroll, tombol submit form add-to-cart */
    (function() {
        var bar = document.getElementById('pdStickyBar');
        if (!bar) return;

        function toggleBar() {
            if (window.scrollY > 320) {
                bar.classList.add('show');
            } else {
                bar.classList.remove('show');
            }
        }
        window.addEventListener('scroll', toggleBar, { passive: true });
        toggleBar();
    })();

    function stickyAddToCart() {
        var form = document.forms['addtocart-form'];
        if (form) {
            form.submit();
            return;
        }
        var fallback = document.querySelector('.btn-addtocart');
        if (fallback) {
            fallback.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    /* Di mobile, tombol chat mengambang dipindah ke atas sticky bar agar tidak menutup CTA */
    (function() {
        var attempts = 0;
        function liftChat() {
            if (!window.matchMedia('(max-width: 768px)').matches) return true;
            var host = document.getElementById('chatessa-widget-host');
            if (!host || !host.shadowRoot) return false;
            var buttons = host.shadowRoot.querySelectorAll('button');
            for (var i = 0; i < buttons.length; i++) {
                if (getComputedStyle(buttons[i]).position === 'fixed') {
                    buttons[i].style.setProperty('bottom', '88px', 'important');
                }
            }
            return true;
        }
        var t = setInterval(function(){ if (++attempts >= 6 || liftChat()) clearInterval(t); }, 2000);
        liftChat();
    })();
</script>
@endsection
