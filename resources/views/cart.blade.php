@extends('layouts.app')

@section('content')
<style>
  /* Layout tabel seperti web online, polos tanpa card */
  .cart-page { background: #fff; min-height: 60vh; }
  .cart-wrap { max-width: 1200px; margin: 0 auto; padding: 18px 20px 60px; }
  .cart-breadcrumb { font-size: 13px; color: #777; margin-bottom: 18px; text-transform: uppercase; letter-spacing: .3px; }
  .cart-breadcrumb a { color: #111; text-decoration: none; font-weight: 600; }
  .cart-breadcrumb a:hover { text-decoration: underline; }
  .cart-breadcrumb span.current { color: #111; font-weight: 400; }

  .cart-title { font-size: 28px; font-weight: 800; letter-spacing: -.3px; text-transform: uppercase; color: #141414; margin: 0 0 6px; }
  .cart-subtitle { font-size: 15px; font-weight: 600; color: #8a8378; margin: 0 0 26px; }

  .cart-grid { display: grid; grid-template-columns: 1fr 340px; gap: 48px; align-items: start; }
  @media (max-width: 992px) { .cart-grid { grid-template-columns: 1fr; gap: 32px; } }

  .cart-table-head, .cart-row { display: grid; grid-template-columns: 110px minmax(0,1fr) 150px 120px 120px; gap: 16px; align-items: start; }
  .cart-table-head { font-size: 13px; font-weight: 600; color: #141414; text-transform: uppercase; letter-spacing: .3px; padding-bottom: 14px; border-bottom: 1px solid #e0e0e0; white-space: nowrap; }
  .cart-row { padding: 26px 0; border-bottom: 1px solid #eee; }

  .prod-img { width: 110px; aspect-ratio: 4/5; object-fit: cover; border-radius: 10px; background: #f5f5f5; display: block; }
  .detail-name { font-size: 15.5px; font-weight: 700; color: #141414; margin: 0 0 6px; line-height: 1.4; }
  .pick-line { font-size: 12.5px; color: #4A4A4A; margin: 0 0 4px; }
  .pick-line em { color: #b8860b; font-style: italic; }
  .pick-label { font-size: 12px; font-weight: 700; color: #777; margin: 14px 0 8px; letter-spacing: .3px; }
  .pick-form { margin: 0; }
  .color-dots { display: flex; gap: 10px; flex-wrap: wrap; }
  .color-dot { width: 30px; height: 30px; border-radius: 50%; border: 1px solid #d5d5d5; cursor: pointer; padding: 0; }
  .color-dot.active { outline: 2px solid #141414; outline-offset: 2px; }
  .size-row { display: flex; gap: 8px; flex-wrap: wrap; }
  .size-mini { min-width: 42px; height: 38px; padding: 0 12px; border: 1px solid #ddd; background: #fff; border-radius: 6px; font-size: 13px; font-weight: 700; color: #141414; cursor: pointer; }
  .size-mini.active { background: #C9A227; border-color: #C9A227; color: #141414; }

  .cell-harga { font-size: 15.5px; font-weight: 600; color: #555; padding-top: 2px; white-space: nowrap; }
  .qty-stepper { display: inline-flex; align-items: stretch; border: 1px solid #ddd; background: #fff; }
  .qty-stepper form { margin: 0; display: flex; }
  .qty-stepper button { width: 36px; height: 40px; border: none; background: #fff; font-size: 17px; color: #111; cursor: pointer; line-height: 1; }
  .qty-stepper button:hover { background: #f5f5f5; }
  .qty-stepper button:disabled { opacity: .3; cursor: not-allowed; }
  .qty-stepper input { width: 38px; border: none; border-left: 1px solid #eee; border-right: 1px solid #eee; text-align: center; font-weight: 600; font-size: 14px; color: #111; outline: none; background: transparent; }
  .cell-sub { display: flex; align-items: flex-start; gap: 8px; padding-top: 2px; }
  .cell-sub .val { font-size: 17px; font-weight: 800; color: #141414; white-space: nowrap; }
  .remove-x { background: none; border: none; font-size: 17px; color: #999; cursor: pointer; padding: 0 2px; line-height: 1.4; }
  .remove-x:hover { color: #b02a37; }

  .btn-gray { display: inline-block; background: #6c757d; color: #fff; border: 1px solid #6c757d; padding: 15px 24px; font-size: 13.5px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; cursor: pointer; text-decoration: none; }
  .btn-gray:hover { background: #5a6268; color: #fff; }

  .summary-box { border: 1px solid #141414; padding: 28px 28px 30px; }
  .summary-box h3 { font-size: 17px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; margin: 0 0 22px; color: #141414; }
  .sum-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; font-size: 13.5px; padding: 13px 0; border-bottom: 1px solid #eee; color: #4A4A4A; }
  .sum-row span:first-child { font-weight: 600; text-transform: uppercase; font-size: 13.5px; }
  .sum-row.total { border-bottom: none; padding-top: 16px; }
  .sum-row.total span:last-child { font-weight: 800; font-size: 22px; color: #141414; }
  .btn-wa { width: 100%; background: #25D366; color: #fff; border: none; padding: 15px; font-size: 13.5px; font-weight: 800; letter-spacing: .4px; cursor: pointer; margin-top: 22px; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; text-transform: uppercase; }
  .btn-wa:hover { filter: brightness(.95); color: #fff; }
  .btn-checkout { width: 100%; background: #141414; color: #fff; border: none; padding: 16px; font-size: 14.5px; font-weight: 800; letter-spacing: .4px; cursor: pointer; margin-top: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; text-transform: uppercase; }
  .btn-checkout:hover { background: #000; color: #fff; }
  .link-extra { display: block; text-align: center; font-size: 13.5px; font-weight: 700; color: #141414; margin-top: 14px; text-decoration: none; }
  .link-extra:hover { text-decoration: underline; }

  .empty-plain { text-align: center; padding: 60px 20px; max-width: 560px; margin: 10px auto; }
  .btn-black { display: inline-block; background: #1a1a1a; color: #fff; padding: 15px 40px; font-size: 14.5px; font-weight: 800; letter-spacing: .6px; text-transform: uppercase; text-decoration: none; }
  .btn-black:hover { background: #000; color: #fff; }

  @media (max-width: 820px) {
    .cart-title { font-size: 22px; }
    .cart-table-head { display: none; }
    .cart-row { grid-template-columns: 96px minmax(0,1fr); gap: 14px; }
    .prod-img { width: 96px; }
    .cell-harga, .cell-qty, .cell-sub { grid-column: 2; }
    .cell-harga { padding-top: 6px; }
  }
</style>

<main class="cart-page pt-90">
  <div class="cart-wrap">
    <div class="cart-breadcrumb">
      <a href="{{ route('home.index') }}">Beranda</a> &nbsp;/&nbsp; <span class="current">Keranjang</span>
    </div>

    @php
      $cartCount = $items->count();
      $cartQty = $items->sum('qty');
      $subtotal = 0;
      foreach ($items as $it) { $subtotal += $it->price * $it->qty; }
      $waNumber = "62882005332646";
      $waText = "Halo Admin Azzahera, saya ingin beli:\n";
      foreach ($items as $it) {
        $c = $it->options->color ?? null;
        $s = $it->options->size ?? null;
        $attr = ($c ? " (Warna: ".$c.")" : "") . ($s ? " (Ukuran: ".$s.")" : "");
        $waText .= "- ".$it->name.$attr." (Qty: ".$it->qty.")\n";
      }
      $waText .= "\nTotal: Rp " . number_format($subtotal, 0, ',', '.');
      $waLink = "https://wa.me/".$waNumber."?text=".urlencode($waText);
      $productsMap = $productsById ?? collect();
    @endphp

    <h1 class="cart-title">Keranjang Pembelian</h1>
    <p class="cart-subtitle">{{ $cartQty }} barang • {{ $cartCount }} produk</p>

    @if($cartCount > 0)
    <div class="cart-grid">
      {{-- KIRI: tabel produk --}}
      <div>
        <div class="cart-table-head">
          <div>Produk</div>
          <div>Detail &amp; Pilihan Varian</div>
          <div>Harga</div>
          <div>Quantity</div>
          <div>Subtotal</div>
        </div>

        @foreach($items as $item)
          @php
            $prod = $productsMap->get($item->id);
            $img = $item->options->image ?? optional($item->model)->image ?? null;
            $imgUrl = $img ? asset('storage/products/' . $img) : asset('assets/images/no-image.png');
            $curColor = $item->options->color ?? null;
            $curSize = $item->options->size ?? null;
            $lineTotal = $item->price * $item->qty;
            $prodColors = $prod ? $prod->colors : collect();
            $prodSizes = ($prod && $prod->sizes) ? array_values(array_filter(array_map('trim', explode(',', $prod->sizes)), fn ($v) => $v !== '')) : [];
          @endphp
          <div class="cart-row">
            <div>
              <img class="prod-img" src="{{ $imgUrl }}" alt="{{ $item->name }}" loading="lazy" onerror="this.src='{{ asset('assets/images/no-image.png') }}'" />
            </div>
            <div>
              <p class="detail-name">{{ $item->name }}</p>
              <p class="pick-line">Pilihan Anda:
                <em>{{ $curColor ?? '-' }}{{ $curSize ? ', Size ' . $curSize : '' }}</em>
              </p>

              @if($prodColors->count() > 0)
                <p class="pick-label">PILIH WARNA:</p>
                <form class="pick-form" method="POST" action="{{ route('cart.variant.update', ['rowId' => $item->rowId]) }}">
                  @csrf @method('PUT')
                  <input type="hidden" name="size" value="{{ $curSize }}">
                  <div class="color-dots">
                    @foreach($prodColors as $pc)
                      @php
                        // Kode warna kosong di DB -> pakai foto varian sebagai isi bulatan
                        $dotStyle = $pc->color_code
                          ? 'background: '.$pc->color_code.';'
                          : ($pc->image
                            ? "background-image: url('".asset('storage/products/'.$pc->image)."');background-size: cover;background-position: center;background-color: #eee;"
                            : 'background: #dddddd;');
                      @endphp
                      <button type="submit" name="color" value="{{ $pc->color }}" title="{{ $pc->color }}"
                        class="color-dot {{ $curColor === $pc->color ? 'active' : '' }}"
                        style="{{ $dotStyle }}"></button>
                    @endforeach
                  </div>
                </form>
              @elseif($curColor)
                <p class="pick-line" style="margin-top:10px;">Warna: {{ $curColor }}</p>
              @endif

              @if(count($prodSizes) > 0)
                <p class="pick-label">PILIH UKURAN:</p>
                <form class="pick-form" method="POST" action="{{ route('cart.variant.update', ['rowId' => $item->rowId]) }}">
                  @csrf @method('PUT')
                  <input type="hidden" name="color" value="{{ $curColor }}">
                  <div class="size-row">
                    @foreach($prodSizes as $sz)
                      <button type="submit" name="size" value="{{ $sz }}"
                        class="size-mini {{ $curSize === $sz ? 'active' : '' }}">{{ $sz }}</button>
                    @endforeach
                  </div>
                </form>
              @elseif($curSize)
                <p class="pick-line" style="margin-top:10px;">Ukuran: {{ $curSize }}</p>
              @endif
            </div>
            <div class="cell-harga">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
            <div class="cell-qty">
              <div class="qty-stepper">
                <form method="POST" action="{{ route('cart.quantity.decrease', ['rowId' => $item->rowId]) }}">
                  @csrf @method('PUT')
                  <button type="submit" {{ $item->qty <= 1 ? 'disabled' : '' }}>−</button>
                </form>
                <input value="{{ $item->qty }}" readonly />
                <form method="POST" action="{{ route('cart.quantity.increase', ['rowId' => $item->rowId]) }}">
                  @csrf @method('PUT')
                  <button type="submit">+</button>
                </form>
              </div>
            </div>
            <div class="cell-sub">
              <span class="val">Rp {{ number_format($lineTotal, 0, ',', '.') }}</span>
              <form method="POST" action="{{ route('cart.remove', ['rowId' => $item->rowId]) }}" onsubmit="return confirm('Hapus produk ini?')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="remove-x" title="Hapus">✕</button>
              </form>
            </div>
          </div>
        @endforeach

        <div style="margin-top:26px;">
          <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua keranjang?')" style="margin:0;">
            @csrf @method('DELETE')
            <button class="btn-gray" type="submit">Kosongkan Keranjang</button>
          </form>
        </div>
      </div>

      {{-- KANAN: total --}}
      <div>
        <div class="summary-box">
          <h3>Cart Total</h3>
          <div class="sum-row"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
          <div class="sum-row"><span>Pengiriman</span><span>Gratis</span></div>
          <div class="sum-row total"><span>Total</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>

          <a href="{{ $waLink }}" target="_blank" class="btn-wa">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93a7.898 7.898 0 0 0-2.327-5.607zM7.994 14.52a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
            Bayar via WhatsApp
          </a>
          <a href="{{ route('cart.checkout') }}" class="btn-checkout">Checkout Sekarang</a>
          <a href="{{ route('shop.index') }}" class="link-extra">+ Tambah Produk Lain</a>
        </div>
      </div>
    </div>
    @else
      <div class="empty-plain">
        <div style="margin-bottom:14px;display:flex;justify-content:center;">
          <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#c9c2b2" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
        <h4 style="font-weight:800;color:#141414;margin-bottom:8px;">Keranjang kamu masih kosong</h4>
        <p style="color:#8a8378;font-size:14px;margin-bottom:24px;">Yuk, jelajahi koleksi gamis, kemeja &amp; abaya premium dari Azzahera Label.</p>
        <a href="{{ route('shop.index') }}" class="btn-black">Belanja Sekarang</a>
      </div>
    @endif
  </div>
</main>
@endsection
