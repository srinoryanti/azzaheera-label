@extends('layouts.app')

@section('content')
<style>
  /* Gaya polos selaras halaman keranjang: tanpa card/box, background putih */
  .cart-page { background: #fff; min-height: 60vh; }
  .cart-wrap { max-width: 1200px; margin: 0 auto; padding: 18px 20px 60px; }
  .cart-breadcrumb { font-size: 13px; color: #777; margin-bottom: 18px; text-transform: uppercase; letter-spacing: .3px; }
  .cart-breadcrumb a { color: #111; text-decoration: none; font-weight: 600; }
  .cart-breadcrumb a:hover { text-decoration: underline; }
  .cart-breadcrumb span.current { color: #111; font-weight: 400; }

  .cart-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 18px; }
  .cart-head h1 { font-size: 28px; font-weight: 800; color: #141414; margin: 0; letter-spacing: -.3px; text-transform: uppercase; }
  .cart-head h1 small { font-size: 15px; font-weight: 600; color: #8a8378; }

  .cart-grid { display: grid; grid-template-columns: 1fr 340px; gap: 48px; align-items: start; }
  @media (max-width: 992px) { .cart-grid { grid-template-columns: 1fr; gap: 32px; } }

  .co-card { background: transparent; border: none; border-radius: 0; padding: 0; margin-bottom: 32px; box-shadow: none; }
  .co-card h3 { font-size: 16px; font-weight: 800; color: #141414; margin: 0 0 14px; text-transform: uppercase; letter-spacing: .3px; padding-bottom: 14px; border-bottom: 1px solid #e0e0e0; }

  .addr-box { border: none; background: transparent; border-radius: 0; padding: 0; font-size: 13.5px; color: #4A4A4A; line-height: 1.7; }
  .addr-box strong { color: #141414; }
  .addr-box .nm { font-size: 15px; font-weight: 800; color: #141414; margin-bottom: 4px; }

  .co-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .co-form-grid .full { grid-column: 1 / -1; }
  @media (max-width: 640px) { .co-form-grid { grid-template-columns: 1fr; } }
  .co-field label { display: block; font-size: 12.5px; font-weight: 700; color: #141414; margin-bottom: 6px; }
  .co-field label .req { color: #c00; font-weight: 800; }
  .req-note { font-size: 12.5px; color: #777; margin: 0 0 14px; }
  .req-note .req { color: #c00; font-weight: 800; }
  .co-field input, .co-field textarea { width: 100%; border: 1px solid #ddd; border-radius: 0; padding: 11px 13px; font-size: 13.5px; outline: none; background: #fff; color: #141414; box-sizing: border-box; }
  .co-field input:focus, .co-field textarea:focus { border-color: #141414; box-shadow: none; }
  .co-field textarea { resize: vertical; }

  .co-item { display: flex; gap: 14px; padding: 13px 0; border-bottom: 1px solid #eee; }
  .co-item:last-child { border-bottom: none; }
  .co-item img { width: 64px; height: 78px; border-radius: 8px; object-fit: cover; background: #f5f5f5; flex-shrink: 0; }
  .co-item .nm { font-size: 13.5px; font-weight: 700; color: #141414; margin: 0 0 5px; line-height: 1.4; }
  .co-item .vr { display: inline-block; font-size: 12px; font-weight: 400; color: #555; background: transparent; border: none; padding: 0; border-radius: 0; margin-bottom: 5px; }
  .co-item .qt { font-size: 12px; color: #8a8378; }
  .co-item .lt { margin-left: auto; font-size: 13.5px; font-weight: 800; color: #141414; white-space: nowrap; }

  .summary-card { background: transparent; border: 1px solid #141414; border-radius: 0; padding: 28px; box-shadow: none; position: static; }
  .summary-card h3 { font-size: 17px; font-weight: 800; margin: 0 0 6px; color: #141414; text-transform: uppercase; letter-spacing: .4px; }
  .sum-head { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; font-weight: 700; text-transform: uppercase; color: #141414; padding: 14px 0; border-bottom: 1px solid #eee; }
  .sum-item { display: flex; justify-content: space-between; gap: 14px; padding: 14px 0; border-bottom: 1px solid #eee; }
  .sum-item .inm { font-size: 13.5px; color: #555; line-height: 1.5; }
  .sum-item .inm .qq { display: block; font-size: 12.5px; color: #8a8378; margin-top: 2px; }
  .sum-item .ilt { font-size: 13.5px; color: #777; white-space: nowrap; }
  .summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; padding: 13px 0; border-bottom: 1px solid #eee; color: #141414; }
  .summary-row span:first-child { font-weight: 700; text-transform: uppercase; }
  .summary-row.total { border-bottom: none; padding-top: 16px; }
  .summary-row.total span:last-child { font-weight: 800; font-size: 16px; }
  .free-badge { background: transparent; color: #146c43; font-size: 12px; font-weight: 700; padding: 0; border-radius: 0; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 0; padding: 16px; font-size: 14.5px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-checkout-main:hover { background: #000; color: #fff; }
  .btn-shop-again { width: 100%; background: #fff; color: #141414; border: 1px solid #ddd; border-radius: 0; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; box-sizing: border-box; }
  .btn-shop-again:hover { border-color: #141414; background: #fff; }

  @media (max-width: 640px) {
    .cart-head h1 { font-size: 22px; }
    .co-card { padding: 0; }
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
    </div>

    <form method="POST" action="{{ route('cart.place.an.order') }}">
      @csrf
      <div class="cart-grid">
        <div>
          <div class="co-card">
            <h3>Alamat Pengiriman</h3>
            @if($address)
              <div class="addr-box">
                <div class="nm">{{ $address->name }} <span style="font-weight:600;color:#8a8378;font-size:12.5px;">| {{ $address->phone }}</span></div>
                <div>{{ $address->address }}</div>
                <div>{{ $address->locality }}, {{ $address->city }}, {{ $address->state }}, {{ $address->zip }}</div>
                @if($address->landmark)<div>Patokan: {{ $address->landmark }}</div>@endif
              </div>
            @else
              <p class="req-note">Tanda <span class="req">*</span> wajib diisi.</p>
              <div class="co-form-grid">
                <div class="co-field">
                  <label>Nama Penerima <span class="req">*</span></label>
                  <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="co-field">
                  <label>No. HP <span class="req">*</span></label>
                  <input type="text" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="co-field">
                  <label>Provinsi <span class="req">*</span></label>
                  <input type="text" name="state" value="{{ old('state') }}" required>
                </div>
                <div class="co-field">
                  <label>Kota / Kabupaten <span class="req">*</span></label>
                  <input type="text" name="city" value="{{ old('city') }}" required>
                </div>
                <div class="co-field">
                  <label>Kecamatan <span class="req">*</span></label>
                  <input type="text" name="locality" value="{{ old('locality') }}" required>
                </div>
                <div class="co-field">
                  <label>Kode Pos <span class="req">*</span></label>
                  <input type="text" name="zip" value="{{ old('zip') }}" required>
                </div>
                <div class="co-field full">
                  <label>Alamat Lengkap <span class="req">*</span></label>
                  <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
                </div>
                <div class="co-field full">
                  <label>Patokan <span style="font-weight:400;color:#8a8378;">(boleh kosong)</span></label>
                  <input type="text" name="landmark" value="{{ old('landmark') }}">
                </div>
              </div>
            @endif
          </div>

          <a href="{{ route('cart.index') }}" style="display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:13.5px;color:#141414;text-decoration:none;margin-top:4px;">← Kembali ke Keranjang</a>
        </div>

        <div>
          <div class="summary-card">
            <h3>Pesanan Kamu</h3>
            <div class="sum-head"><span>Produk</span><span>Subtotal</span></div>
            @foreach ($cartItems as $item)
              <div class="sum-item">
                <div class="inm">
                  {{ $item->name }}
                  @if(($item->options->color ?? null) || ($item->options->size ?? null))
                    <span class="qq">{{ $item->options->color ?? '' }}{{ $item->options->size ? ', Size ' . $item->options->size : '' }}</span>
                  @endif
                  <span class="qq">Jumlah : {{ $item->qty }} pcs</span>
                </div>
                <div class="ilt">Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}</div>
              </div>
            @endforeach
            <div class="summary-row"><span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
            <div class="summary-row"><span>Pengiriman</span><span>Gratis</span></div>
            <div class="summary-row total"><span>Total</span><span>Rp{{ number_format($total, 0, ',', '.') }}</span></div>
            <button type="submit" class="btn-checkout-main">Buat Pesanan</button>
            <a href="{{ route('cart.index') }}" class="btn-shop-again">Kembali ke Keranjang</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</main>
@endsection
