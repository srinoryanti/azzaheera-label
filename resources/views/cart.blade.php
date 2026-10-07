@extends('layouts.app')

@section('content')
<style>
  .cart-page { background: #faf7f0; min-height: 60vh; }
  .cart-wrap { max-width: 1200px; margin: 0 auto; padding: 28px 20px 60px; }
  .cart-breadcrumb { font-size: 13px; color: #8a8378; margin-bottom: 12px; }
  .cart-breadcrumb a { color: #8a8378; text-decoration: none; }
  .cart-breadcrumb a:hover { color: #9c7c2a; }
  .cart-breadcrumb span.current { color: #141414; font-weight: 700; }

  .cart-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 18px; }
  .cart-head h1 { font-size: 28px; font-weight: 800; color: #141414; margin: 0; letter-spacing: -0.3px; }
  .cart-head h1 small { font-size: 15px; font-weight: 600; color: #8a8378; }
  .cart-steps { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #8a8378; }
  .cart-steps .step { display: flex; align-items: center; gap: 7px; padding: 7px 13px; border-radius: 999px; background: #fff; border: 1px solid #EAEAEA; }
  .cart-steps .step.active { background: #141414; color: #fff; border-color: #141414; }
  .cart-steps .step.active b { background: #D4AF37; color: #141414; }
  .cart-steps .step b { width: 20px; height: 20px; border-radius: 50%; background: #eee; color: #141414; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; }
  .cart-steps .dash { width: 22px; height: 2px; background: #e2d9bd; border-radius: 2px; }

  .cart-grid { display: grid; grid-template-columns: 1fr 360px; gap: 22px; align-items: start; }
  @media (max-width: 992px) { .cart-grid { grid-template-columns: 1fr; } }

  .cart-toolbar { background: #fff; border: 1px solid #EAEAEA; border-radius: 14px; padding: 13px 18px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; box-shadow: 0 8px 24px rgba(16,35,27,.05); font-size: 13.5px; font-weight: 600; }
  .cart-toolbar .left { display: flex; align-items: center; gap: 10px; color: #141414; }
  .cart-toolbar .count-badge { background: #F8F1DC; color: #141414; border: 1px solid #D4AF37; font-size: 11px; font-weight: 800; padding: 2px 10px; border-radius: 999px; }
  .cart-toolbar button.link-danger { background: none; border: none; color: #b02a37; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
  .cart-toolbar button.link-danger:hover { text-decoration: underline; }

  .cart-item { background: #fff; border: 1px solid #EAEAEA; border-radius: 18px; padding: 18px; display: flex; gap: 16px; margin-bottom: 14px; box-shadow: 0 8px 24px rgba(16,35,27,.05); transition: .18s; }
  .cart-item:hover { border-color: #e7dfcb; box-shadow: 0 12px 30px rgba(0,0,0,.07); }
  .cart-item__img { width: 104px; height: 128px; border-radius: 14px; object-fit: cover; background: #f3f0e8; flex-shrink: 0; }
  .cart-item__body { flex: 1; min-width: 0; display: flex; flex-direction: column; }
  .cart-item__store { font-size: 11px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; color: #9c7c2a; display: flex; align-items: center; gap: 6px; margin-bottom: 5px; }
  .cart-item__store .verified { width: 15px; height: 15px; border-radius: 50%; background: #D4AF37; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; }
  .cart-item__name { font-size: 15.5px; font-weight: 700; color: #141414; line-height: 1.4; margin: 0 0 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .cart-item__variant { display: inline-flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: #4A4A4A; background: #faf7f0; border: 1px solid #e7dfcb; padding: 5px 11px; border-radius: 999px; width: fit-content; margin-bottom: 10px; }
  .cart-item__variant .dot { width: 14px; height: 14px; border-radius: 50%; border: 1px solid rgba(0,0,0,.15); display: inline-block; }
  .cart-item__unit { font-size: 12.5px; color: #8a8378; margin-bottom: 12px; }
  .cart-item__bottom { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
  .qty-stepper { display: inline-flex; align-items: center; border: 1.5px solid #e7dfcb; border-radius: 999px; overflow: hidden; background: #fff; }
  .qty-stepper form { margin: 0; }
  .qty-stepper button { width: 34px; height: 34px; border: none; background: #fff; font-size: 18px; font-weight: 800; color: #141414; cursor: pointer; line-height: 1; transition: .15s; }
  .qty-stepper button:hover { background: #F8F1DC; color: #9c7c2a; }
  .qty-stepper button:disabled { opacity: .35; cursor: not-allowed; }
  .qty-stepper input { width: 40px; border: none; text-align: center; font-weight: 800; font-size: 14px; color: #141414; outline: none; background: transparent; }
  .cart-item__price { text-align: right; }
  .cart-item__price .sub { font-size: 17px; font-weight: 800; color: #141414; }
  .cart-item__price .per { font-size: 12px; color: #8a8378; }
  .cart-item__remove { background: #f6f4ef; border: none; width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: .15s; color: #8a8378; }
  .cart-item__remove:hover { background: #fde2e4; color: #b02a37; }

  .summary-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 20px; padding: 24px; box-shadow: 0 8px 24px rgba(16,35,27,.06); position: sticky; top: 95px; }
  .summary-card h3 { font-size: 17px; font-weight: 800; margin: 0 0 16px; color: #141414; }
  .summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; padding: 8px 0; color: #4A4A4A; }
  .summary-row.total { border-top: 1px dashed #e7dfcb; margin-top: 10px; padding-top: 15px; }
  .summary-row.total strong { font-size: 22px; font-weight: 800; color: #141414; }
  .free-badge { background: #dcf5e3; color: #146c43; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 999px; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 999px; padding: 14px; font-size: 14.5px; font-weight: 800; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; }
  .btn-checkout-main:hover { background: #2a2a2a; color: #D4AF37; }
  .btn-wa-main { width: 100%; background: #25D366; color: #fff; border: none; border-radius: 999px; padding: 13px; font-size: 13.5px; font-weight: 800; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; }
  .btn-wa-main:hover { filter: brightness(.95); color: #fff; }
  .btn-shop-again { width: 100%; background: #fff; color: #141414; border: 1.5px solid #e7dfcb; border-radius: 999px; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
  .btn-shop-again:hover { border-color: #D4AF37; background: #F8F1DC; }

  .empty-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 22px; padding: 60px 30px; text-align: center; box-shadow: 0 8px 24px rgba(16,35,27,.05); max-width: 560px; margin: 10px auto; }

  @media (max-width: 640px) {
    .cart-head h1 { font-size: 22px; }
    .cart-steps { width: 100%; overflow-x: auto; padding-bottom: 4px; }
    .cart-item { padding: 14px; gap: 12px; }
    .cart-item__img { width: 82px; height: 104px; }
    .summary-card { position: static; }
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
    @endphp

    <div class="cart-head">
      <h1>Keranjang Belanja <small>({{ $cartQty }} barang)</small></h1>
      <div class="cart-steps">
        <div class="step active"><b>1</b> Keranjang</div>
        <div class="dash"></div>
        <div class="step"><b>2</b> Checkout</div>
        <div class="dash"></div>
        <div class="step"><b>3</b> Selesai</div>
      </div>
    </div>

    @if($cartCount > 0)
    <div class="cart-grid">
      {{-- KIRI: daftar produk --}}
      <div>
        <div class="cart-toolbar">
          <div class="left">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 7h12l1.5 13H4.5L6 7z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
            Azzahera Official Store
            <span class="count-badge">{{ $cartCount }} produk</span>
          </div>
          <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua keranjang?')">
            @csrf @method('DELETE')
            <button class="link-danger" type="submit">Hapus semua</button>
          </form>
        </div>

        @foreach($items as $item)
          @php
            $img = $item->options->image ?? optional($item->model)->image ?? null;
            $imgUrl = $img ? asset('storage/products/' . $img) : asset('assets/images/no-image.png');
            $color = $item->options->color ?? null;
            $variantImg = $item->options->image ?? null;
            $size = $item->options->size ?? null;
            $lineTotal = $item->price * $item->qty;
          @endphp
          <div class="cart-item">
            <img class="cart-item__img" src="{{ $imgUrl }}" alt="{{ $item->name }}" loading="lazy" onerror="this.src='{{ asset('assets/images/no-image.png') }}'" />
            <div class="cart-item__body">
              <div class="cart-item__store"><span class="verified"><svg width="9" height="9" viewBox="0 0 10 10" fill="none" stroke="#fff" stroke-width="2"><path d="M1.5 5.5l2.5 2.5 4.5-5.5"/></svg></span> Azzahera Label • Official</div>
              <p class="cart-item__name">{{ $item->name }}</p>
              @if($color || $size)
                <span class="cart-item__variant">
                  @if($variantImg)<img src="{{ asset('storage/products/' . $variantImg) }}" alt="{{ $color }}" loading="lazy" style="width:22px;height:26px;object-fit:cover;border-radius:6px;border:1px solid #e5e5e5;">@endif
                  @if($color && $size)
                    Variasi: {{ $color }} • Ukuran: {{ $size }}
                  @elseif($color)
                    Variasi: {{ $color }}
                  @else
                    Ukuran: {{ $size }}
                  @endif
                </span>
              @endif
              <div class="cart-item__unit">Harga satuan: <b style="color:#141414">Rp {{ number_format($item->price, 0, ',', '.') }}</b></div>
              <div class="cart-item__bottom">
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
                <div style="display:flex;align-items:center;gap:12px;">
                  <div class="cart-item__price">
                    <div class="sub">Rp {{ number_format($lineTotal, 0, ',', '.') }}</div>
                    <div class="per">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                  </div>
                  <form method="POST" action="{{ route('cart.remove', ['rowId' => $item->rowId]) }}" onsubmit="return confirm('Hapus produk ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="cart-item__remove" title="Hapus">✕</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <a href="{{ route('shop.index') }}" style="display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:13.5px;color:#141414;text-decoration:none;margin-top:4px;">← Lanjut belanja</a>
      </div>

      {{-- KANAN: ringkasan --}}
      <div>
        <div class="summary-card">
          <h3>Ringkasan Belanja</h3>
          <div class="summary-row"><span>Subtotal ({{ $cartQty }} barang)</span><b>Rp {{ number_format($subtotal, 0, ',', '.') }}</b></div>
          <div class="summary-row"><span>Pengiriman</span><span class="free-badge">GRATIS</span></div>

          <div class="summary-row total"><span>Total Bayar</span><strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></div>

          <a href="{{ route('cart.checkout') }}" class="btn-checkout-main">Checkout Sekarang →</a>
          <a href="{{ $waLink }}" target="_blank" class="btn-wa-main">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93a7.898 7.898 0 0 0-2.327-5.607zM7.994 14.52a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
            Tanya via WhatsApp
          </a>
          <a href="{{ route('shop.index') }}" class="btn-shop-again">+ Tambah Produk Lain</a>
        </div>
      </div>
    </div>
    @else
      <div class="empty-card">
        <div style="margin-bottom:14px;display:flex;justify-content:center;">
          <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#c9c2b2" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
        <h4 style="font-weight:800;color:#141414;margin-bottom:8px;">Keranjang kamu masih kosong</h4>
        <p style="color:#8a8378;font-size:14px;margin-bottom:24px;">Yuk, jelajahi koleksi gamis, kemeja & abaya premium dari Azzahera Label.</p>
        <a href="{{ route('shop.index') }}" class="btn-checkout-main" style="max-width:260px;margin:0 auto;background:#D4AF37;color:#141414;">Belanja Sekarang</a>
      </div>
    @endif
  </div>
</main>
@endsection
