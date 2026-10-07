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

  .co-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 18px; padding: 22px; margin-bottom: 16px; box-shadow: 0 8px 24px rgba(16,35,27,.05); }
  .co-card h3 { font-size: 16px; font-weight: 800; color: #141414; margin: 0 0 14px; display: flex; align-items: center; gap: 9px; }
  .co-card h3 .n { width: 24px; height: 24px; border-radius: 50%; background: #141414; color: #fff; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; }

  .addr-box { border: 1.5px solid #e7dfcb; background: #fffdf5; border-radius: 14px; padding: 16px 18px; font-size: 13.5px; color: #4A4A4A; line-height: 1.7; }
  .addr-box strong { color: #141414; }
  .addr-box .nm { font-size: 15px; font-weight: 800; color: #141414; margin-bottom: 4px; }

  .co-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .co-form-grid .full { grid-column: 1 / -1; }
  @media (max-width: 640px) { .co-form-grid { grid-template-columns: 1fr; } }
  .co-field label { display: block; font-size: 12.5px; font-weight: 700; color: #141414; margin-bottom: 6px; }
  .co-field input, .co-field textarea { width: 100%; border: 1.5px solid #e7dfcb; border-radius: 12px; padding: 11px 13px; font-size: 13.5px; outline: none; background: #fff; color: #141414; box-sizing: border-box; }
  .co-field input:focus, .co-field textarea:focus { border-color: #D4AF37; box-shadow: 0 0 0 3px rgba(212,175,55,.18); }
  .co-field textarea { resize: vertical; }

  .co-item { display: flex; gap: 14px; padding: 13px 0; border-bottom: 1px solid #f0ece1; }
  .co-item:last-child { border-bottom: none; }
  .co-item img { width: 64px; height: 78px; border-radius: 12px; object-fit: cover; background: #f3f0e8; flex-shrink: 0; }
  .co-item .nm { font-size: 13.5px; font-weight: 700; color: #141414; margin: 0 0 5px; line-height: 1.4; }
  .co-item .vr { display: inline-block; font-size: 11.5px; font-weight: 600; color: #4A4A4A; background: #faf7f0; border: 1px solid #e7dfcb; padding: 3px 10px; border-radius: 999px; margin-bottom: 5px; }
  .co-item .qt { font-size: 12px; color: #8a8378; }
  .co-item .lt { margin-left: auto; font-size: 13.5px; font-weight: 800; color: #141414; white-space: nowrap; }

  .summary-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 20px; padding: 24px; box-shadow: 0 8px 24px rgba(16,35,27,.06); position: sticky; top: 95px; }
  .summary-card h3 { font-size: 17px; font-weight: 800; margin: 0 0 16px; color: #141414; }
  .summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; padding: 8px 0; color: #4A4A4A; }
  .summary-row.total { border-top: 1px dashed #e7dfcb; margin-top: 10px; padding-top: 15px; }
  .summary-row.total strong { font-size: 22px; font-weight: 800; color: #141414; }
  .free-badge { background: #dcf5e3; color: #146c43; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 999px; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 999px; padding: 14px; font-size: 14.5px; font-weight: 800; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; }
  .btn-checkout-main:hover { background: #2a2a2a; color: #D4AF37; }
  .btn-shop-again { width: 100%; background: #fff; color: #141414; border: 1.5px solid #e7dfcb; border-radius: 999px; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; box-sizing: border-box; }
  .btn-shop-again:hover { border-color: #D4AF37; background: #F8F1DC; }

  @media (max-width: 640px) {
    .cart-head h1 { font-size: 22px; }
    .cart-steps { width: 100%; overflow-x: auto; padding-bottom: 4px; }
    .co-card { padding: 17px; }
    .summary-card { position: static; }
  }
</style>

<main class="cart-page pt-90">
  <div class="cart-wrap">
    <div class="cart-breadcrumb">
      <a href="{{ route('home.index') }}">Beranda</a> &nbsp;/&nbsp;
      <a href="{{ route('cart.index') }}">Keranjang</a> &nbsp;/&nbsp;
      <span class="current">Checkout</span>
    </div>

    @php
      $cartItems = Cart::instance('cart')->content();
      $cartQty = $cartItems->sum('qty');
      $subtotal = 0;
      foreach ($cartItems as $it) { $subtotal += $it->price * $it->qty; }
      $total = $subtotal;
    @endphp

    <div class="cart-head">
      <h1>Checkout <small>({{ $cartQty }} barang)</small></h1>
      <div class="cart-steps">
        <div class="step"><b>1</b> Keranjang</div>
        <div class="dash"></div>
        <div class="step active"><b>2</b> Checkout</div>
        <div class="dash"></div>
        <div class="step"><b>3</b> Selesai</div>
      </div>
    </div>

    <form method="POST" action="{{ route('cart.place.an.order') }}">
      @csrf
      <div class="cart-grid">
        <div>
          <div class="co-card">
            <h3><span class="n">1</span> Alamat Pengiriman</h3>
            @if($address)
              <div class="addr-box">
                <div class="nm">{{ $address->name }} <span style="font-weight:600;color:#8a8378;font-size:12.5px;">| {{ $address->phone }}</span></div>
                <div>{{ $address->address }}</div>
                <div>{{ $address->locality }}, {{ $address->city }}, {{ $address->state }}, {{ $address->zip }}</div>
                @if($address->landmark)<div>Patokan: {{ $address->landmark }}</div>@endif
              </div>
            @else
              <div class="co-form-grid">
                <div class="co-field">
                  <label>Nama Penerima *</label>
                  <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="co-field">
                  <label>No. HP *</label>
                  <input type="text" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="co-field">
                  <label>Provinsi *</label>
                  <input type="text" name="state" value="{{ old('state') }}" required>
                </div>
                <div class="co-field">
                  <label>Kota / Kabupaten *</label>
                  <input type="text" name="city" value="{{ old('city') }}" required>
                </div>
                <div class="co-field">
                  <label>Kecamatan *</label>
                  <input type="text" name="locality" value="{{ old('locality') }}" required>
                </div>
                <div class="co-field">
                  <label>Kode Pos *</label>
                  <input type="text" name="zip" value="{{ old('zip') }}" required>
                </div>
                <div class="co-field full">
                  <label>Alamat Lengkap *</label>
                  <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
                </div>
                <div class="co-field full">
                  <label>Patokan</label>
                  <input type="text" name="landmark" value="{{ old('landmark') }}">
                </div>
              </div>
            @endif
          </div>

          <div class="co-card">
            <h3><span class="n">2</span> Produk Dipesan</h3>
            @foreach ($cartItems as $item)
              @php
                $img = $item->options->image ?? optional($item->model)->image ?? null;
                $imgUrl = $img ? asset('storage/products/' . $img) : asset('assets/images/no-image.png');
              @endphp
              <div class="co-item">
                <img src="{{ $imgUrl }}" alt="{{ $item->name }}" loading="lazy" onerror="this.src='{{ asset('assets/images/no-image.png') }}'" />
                <div style="min-width:0;">
                  <p class="nm">{{ $item->name }}</p>
                  @if(($item->options->color ?? null) || ($item->options->size ?? null))
                    <span class="vr">Variasi: {{ $item->options->color ?? '-' }}{{ $item->options->size ? ' • Ukuran: ' . $item->options->size : '' }}</span><br>
                  @endif
                  <span class="qt">Jumlah: {{ $item->qty }} pcs</span>
                </div>
                <div class="lt">Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}</div>
              </div>
            @endforeach
          </div>

          <a href="{{ route('cart.index') }}" style="display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:13.5px;color:#141414;text-decoration:none;margin-top:4px;">← Kembali ke Keranjang</a>
        </div>

        <div>
          <div class="summary-card">
            <h3>Ringkasan Belanja</h3>
            <div class="summary-row"><span>Subtotal ({{ $cartQty }} barang)</span><b>Rp{{ number_format($subtotal, 0, ',', '.') }}</b></div>
            <div class="summary-row"><span>Pengiriman</span><span class="free-badge">GRATIS</span></div>
            <div class="summary-row total"><span>Total Bayar</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>
            <button type="submit" class="btn-checkout-main">Buat Pesanan</button>
            <a href="{{ route('cart.index') }}" class="btn-shop-again">Kembali ke Keranjang</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</main>
@endsection
