@extends('layouts.app')

@section('content')

<style>
 
  .category-image {
    border-radius: 50%;
    object-fit: cover;
  }
  main {
    margin-top: 0 !important;
    padding-top: 0 !important;
  }
  .home-slideshow .slideshow-character__img {
    display: block !important;

    width: 100% !important;
    max-width: 100% !important;

    height: 100% !important;
    max-height: 100% !important;

    margin: 0 !important;
    padding: 0 !important;

    object-fit: contain !important;
    object-position: right bottom !important;

    transform: translateY(20px) !important;

    box-sizing: border-box !important;
}

  .home-slideshow .slideshow-text {
    position: absolute !important;

    left: 6% !important;
    top: 50% !important;

    transform: translateY(-50%) !important;

    z-index: 60 !important;

    width: auto !important;
    max-width: 48% !important;

    margin: 0 !important;
    padding: 0 1rem 0 0 !important;

    text-align: left !important;

    box-sizing: border-box !important;

    overflow-wrap: break-word !important;
    word-break: normal !important;
  }

  .home-slideshow .slideshow-text h6 {
    margin: 0 0 14px 0 !important;

    font-size: clamp(12px, 1.4vw, 14px) !important;
    line-height: 1.4 !important;
  }

  .home-slideshow .slideshow-text h2 {
    margin: 0 0 8px 0 !important;

    font-size: clamp(30px, 4.5vw, 64px) !important;
    line-height: 1.05 !important;

    box-sizing: border-box !important;

    overflow-wrap: break-word !important;
    word-break: normal !important;
  }

  .home-slideshow .slideshow-text h2:last-child {
    margin-bottom: 0 !important;
  }

  .home-slideshow .slideshow-character {
    position: absolute !important;

    right: 4% !important;
    bottom: 0 !important;
    top: auto !important;
    left: auto !important;

    z-index: 50 !important;

    width: 42% !important;
    height: 100% !important;

    margin: 0 !important;
    padding: 0 !important;

    display: flex !important;
    align-items: flex-end !important;
    justify-content: flex-end !important;

    overflow: hidden !important;

    box-sizing: border-box !important;
  }

  .home-slideshow .slideshow-character__img {
    display: block !important;

    width: 100% !important;
    max-width: 100% !important;

    height: 100% !important;
    max-height: 100% !important;

    margin: 0 !important;
    padding: 0 !important;

    object-fit: contain !important;
    object-position: right bottom !important;

    box-sizing: border-box !important;
  }


  .home-slideshow .character_markup {
    position: absolute !important;

    right: 0 !important;
    bottom: 20px !important;

    z-index: 70 !important;
  }

  .home-slideshow .slideshow-pagination {
    z-index: 80 !important;
  }

  .product-card__price s {
    font-size: 14px;
  }

  .product-card__price .fw-bold {
    color: #222;
  }

  /* Perbaikan spasi kosong pada produk */
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

  /* Supaya grid rapi walau gambar beda-beda */
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

  @media (max-width: 1200px) {
    .home-slideshow {
      height: 500px !important;
      min-height: 500px !important;
    }

    .home-slideshow .slideshow-text {
      left: 5% !important;
      max-width: 50% !important;
    }

    .home-slideshow .slideshow-character {
      right: 3% !important;
      width: 43% !important;
    }
  }

  @media (max-width: 992px) {
    .home-slideshow {
      height: 460px !important;
      min-height: 460px !important;
    }

    .home-slideshow .slideshow-text {
      left: 5% !important;
      max-width: 52% !important;
    }

    .home-slideshow .slideshow-text h2 {
      font-size: clamp(28px, 5vw, 48px) !important;
    }

    .home-slideshow .slideshow-character {
      right: 2% !important;
      width: 44% !important;
    }
  }

  @media (max-width: 768px) {

  .home-slideshow {
    height: 420px !important;
    min-height: 420px !important;
    overflow: hidden !important;
  }

  .home-slideshow .slide-inner {
    position: relative !important;
    width: 100% !important;
    height: 100% !important;
  }

  .home-slideshow .slideshow-text {
    position: absolute !important;
    left: 5% !important;
    top: 50% !important;
    transform: translateY(-50%) !important;

    width: auto !important;
    max-width: 50% !important;

    padding-right: 10px !important;
    text-align: left !important;
  }

  .home-slideshow .slideshow-text h6 {
    font-size: 11px !important;
  }

  .home-slideshow .slideshow-text h2 {
    font-size: clamp(20px, 5vw, 30px) !important;
    line-height: 1.2 !important;
  }

  .home-slideshow .slideshow-character {
    position: absolute !important;
    right: 2% !important;
    bottom: 0 !important;

    width: 45% !important;
    height: 100% !important;

    display: flex !important;
    align-items: flex-end !important;
    justify-content: flex-end !important;
  }

  .home-slideshow .slideshow-character__img {
    width: 100% !important;
    height: 100% !important;

    object-fit: contain !important;
    object-position: right bottom !important;
  }

  .home-slideshow .character_markup {
    display: none !important;
  }

  .home-slideshow .slideshow-pagination {
    display: none !important;
  }
}




 @media (max-width:480px){

  .home-slideshow{
    height:360px !important;
    min-height:360px !important;
  }

  .home-slideshow .slideshow-text{
    max-width:52% !important;
  }

  .home-slideshow .slideshow-text h2{
    font-size:22px !important;
  }

  .home-slideshow .slideshow-character{
    width:44% !important;
    height:100% !important;
  }

}

/* Garis PRODUK BARU */
.home-slideshow .text_dash::before {
    background: #D4AF37 !important;
    background-color: #D4AF37 !important;
    border-color: #D4AF37 !important;
}

/* Pagination 01 02 03 04 */
.home-slideshow .slideshow-pagination,
.home-slideshow .slideshow-pagination span,
.home-slideshow .slideshow-number-pagination,
.home-slideshow .slideshow-number-pagination span {
    color: #D4AF37 !important;
}

/* Nomor aktif */
.home-slideshow .swiper-pagination-bullet-active {
    color: #D4AF37 !important;
    font-weight: 700 !important;
}

/* Override pc__title to bold */
.pc__title.fw-bold {
    font-weight: 700 !important;
    font-size: 14px;
}

/* Garis penghubung pagination */
.home-slideshow .slideshow-pagination span::before,
.home-slideshow .slideshow-pagination span::after,
.home-slideshow .slideshow-number-pagination span::before,
.home-slideshow .slideshow-number-pagination span::after {
    background: #D4AF37 !important;
    background-color: #D4AF37 !important;
    border-color: #D4AF37 !important;
}

/* Tombol CTA banner */
.home-slideshow .slideshow-cta {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    margin-top: 22px !important;
    padding: 13px 32px !important;
    border-radius: 999px !important;
    background: #D4AF37 !important;
    color: #141414 !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    letter-spacing: .3px;
    text-decoration: none !important;
    transition: background .2s ease, color .2s ease, transform .2s ease !important;
}
.home-slideshow .slideshow-cta:hover {
    background: #141414 !important;
    color: #D4AF37 !important;
    transform: translateY(-2px);
}
@media (max-width: 768px) {
    .home-slideshow .slideshow-cta {
        margin-top: 14px !important;
        padding: 10px 22px !important;
        font-size: 12.5px !important;
    }
}
</style>
<main>
  <section
    class="swiper-container js-swiper-slider swiper-number-pagination slideshow home-slideshow"
    data-settings='{
      "autoplay": {
        "delay": 5000
      },
      "slidesPerView": 1,
      "effect": "fade",
      "loop": true
    }'
  >

    <div class="swiper-wrapper">

      @foreach ($slides as $slide)

        <div class="swiper-slide">

          <div class="slide-inner">

            <!-- GAMBAR SLIDER -->
            <div class="slideshow-character">

              <img
                @if($loop->first) loading="eager" fetchpriority="high" @else loading="lazy" @endif
                decoding="async"
                src="{{ asset('storage/slides/' . $slide->image) }}"
                width="500"
                height="733"
                alt="{{ $slide->title }}{{ $slide->subtitle ? ' — ' . $slide->subtitle : '' }}"
                class="slideshow-character__img animate animate_fade animate_btt animate_delay-9"
              />

            </div>


            <!-- TEKS SLIDER -->
            <div class="slideshow-text">

              <h6
                class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3"
              >
                {{ $slide->tagline ?: 'Produk Baru' }}
              </h6>

              <h2
                class="h1 fw-normal animate animate_fade animate_btt animate_delay-5"
              >
                {{ $slide->title }}
              </h2>

              <h2
                class="h1 fw-bold animate animate_fade animate_btt animate_delay-5"
              >
                {{ $slide->subtitle }}
              </h2>

              @if(!empty($slide->link) && $slide->link !== '-' && $slide->link !== '#')
                <a href="{{ $slide->link }}"
                  class="slideshow-cta animate animate_fade animate_btt animate_delay-10"
                >
                  Belanja Sekarang
                </a>
              @else
                <a href="{{ route('shop.index') }}"
                  class="slideshow-cta animate animate_fade animate_btt animate_delay-10"
                >
                  Lihat Koleksi
                </a>
              @endif

            </div>

          </div>

        </div>

      @endforeach

    </div>


    <!-- PAGINATION -->
    <div class="container">
      <div
        class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5"
      ></div>
    </div>

  </section>

  <div class="container mw-1620 bg-white border-radius-10">

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="hot-deals container">

      <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">
        Produk Promo
      </h2>


      <div class="row">

        <div class="col-12">

          <div class="position-relative">

            <div
              class="swiper-container js-swiper-slider"
              data-settings='{
                "autoplay": {
                  "delay": 2000
                },
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "effect": "none",
                "loop": false,
                "breakpoints": {
                  "320": {
                    "slidesPerView": 2,
                    "slidesPerGroup": 2,
                    "spaceBetween": 14
                  },
                  "768": {
                    "slidesPerView": 2,
                    "slidesPerGroup": 3,
                    "spaceBetween": 24
                  },
                  "992": {
                    "slidesPerView": 3,
                    "slidesPerGroup": 1,
                    "spaceBetween": 30,
                    "pagination": false
                  },
                  "1200": {
                    "slidesPerView": 4,
                    "slidesPerGroup": 1,
                    "spaceBetween": 20,
                    "pagination": false
                  }
                }
              }'
            >

              <div class="swiper-wrapper">

                @forelse ($sproducts as $sproduct)

                  <div class="swiper-slide product-card product-card_style3">

                    <!-- GAMBAR PRODUK -->
                    <div class="pc__img-wrapper">
                        <img
                          loading="lazy" decoding="async" width="360" height="450"
                          src="{{ asset('storage/products/' . $sproduct->image) }}"
                          alt="{{ $sproduct->name }}"
                          class="pc__img"
                        >
                    </div>


                    <!-- INFO PRODUK -->
                    <div class="pc__info position-relative">

                      <h6 class="pc__title fw-bold">
                          {{ $sproduct->name }}
                      </h6>


                      <!-- HARGA -->
                      <div class="product-card__price d-flex align-items-center">

                        <span class="money price text-secondary">

                          @if (
                            $sproduct->sale_price &&
                            $sproduct->sale_price < $sproduct->regular_price
                          )

                            <s class="text-muted me-2">
                              Rp {{ number_format($sproduct->regular_price, 0, ',', '.') }}
                            </s>

                            <span>
                              Rp {{ number_format($sproduct->sale_price, 0, ',', '.') }}
                            </span>

                          @else

                            <span>
                              Rp {{ number_format($sproduct->regular_price, 0, ',', '.') }}
                            </span>

                          @endif

                        </span>

                      </div>

                    </div>

                  </div>

                @empty
                  <div class="swiper-slide w-100 text-center py-5">
                    <p class="text-muted mb-0">Belum ada produk promo saat ini.</p>
                  </div>
                @endforelse

              </div>

            </div>

          </div>

        </div>

      </div>

    </section>


    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>


    <section class="products-grid container">

      <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">
        Daftar Produk
      </h2>


      <div class="row">

        @foreach ($fproducts as $fproduct)

          <div class="col-6 col-md-4 col-lg-3">

            <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">

              <!-- GAMBAR PRODUK -->
              <div class="pc__img-wrapper">

                <a
                  href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}"
                >
                  <img
                    loading="lazy" decoding="async" width="360" height="450"
                    src="{{ asset('storage/products/' . $fproduct->image) }}"
                    alt="{{ $fproduct->name }}"
                    class="pc__img"
                    onerror="this.style.display='none'"
                  >
                </a>

              </div>


              <!-- INFO PRODUK -->
              <div class="pc__info position-relative">

                <h6 class="pc__title fw-bold">

                  <a
                    href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}"
                  >
                    {{ $fproduct->name }}
                  </a>

                </h6>


                <!-- HARGA -->
                <div class="product-card__price d-flex align-items-center">

                  <span class="money price text-secondary">

                    @if (
                      $fproduct->sale_price &&
                      $fproduct->sale_price < $fproduct->regular_price
                    )

                      <s class="text-muted me-2">
                        Rp {{ number_format($fproduct->regular_price, 0, ',', '.') }}
                      </s>

                      <span>
                        Rp {{ number_format($fproduct->sale_price, 0, ',', '.') }}
                      </span>

                    @else

                      <span>
                        Rp {{ number_format($fproduct->regular_price, 0, ',', '.') }}
                      </span>

                    @endif

                  </span>

                </div>

              </div>

            </div>

          </div>

        @endforeach

      </div>

    </section>

  </div>

  <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

</main>

@endsection