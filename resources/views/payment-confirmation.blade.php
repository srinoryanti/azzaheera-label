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
  .cart-steps { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #8a8378; }
  .cart-steps .step { display: flex; align-items: center; gap: 7px; padding: 7px 13px; border-radius: 999px; background: #fff; border: 1px solid #EAEAEA; }
  .cart-steps .step.active { background: #141414; color: #fff; border-color: #141414; }
  .cart-steps .step.active b { background: #D4AF37; color: #141414; }
  .cart-steps .step b { width: 20px; height: 20px; border-radius: 50%; background: #eee; color: #141414; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; }
  .cart-steps .dash { width: 22px; height: 2px; background: #e2d9bd; border-radius: 2px; }

  .done-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 22px; padding: 48px 30px; text-align: center; box-shadow: 0 8px 24px rgba(16,35,27,.05); max-width: 640px; margin: 0 auto 18px; }
  .done-card h3 { font-size: 20px; font-weight: 800; color: #141414; margin: 18px 0 8px; }
  .done-card p { font-size: 13.5px; color: #8a8378; margin: 0; line-height: 1.7; }

  .co-card { background: #fff; border: 1px solid #EAEAEA; border-radius: 18px; padding: 22px; margin-bottom: 16px; box-shadow: 0 8px 24px rgba(16,35,27,.05); max-width: 640px; margin-left: auto; margin-right: auto; }
  .co-card h3 { font-size: 16px; font-weight: 800; color: #141414; margin: 0 0 14px; }
  .info-rows { font-size: 13.5px; }
  .info-rows .ir { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid #f0ece1; color: #4A4A4A; }
  .info-rows .ir:last-child { border-bottom: none; }
  .info-rows .ir span:first-child { color: #8a8378; font-weight: 600; font-size: 12.5px; }
  .info-rows .ir strong { color: #141414; }
  .st-badge { background: #fff3d6; color: #8a6100; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 999px; }
  .alert-soft { background: #fffdf5; border: 1px solid #e7dfcb; border-radius: 12px; padding: 12px 14px; font-size: 12.5px; color: #4A4A4A; line-height: 1.6; margin-top: 14px; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 999px; padding: 14px; font-size: 14.5px; font-weight: 800; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-checkout-main:hover { background: #2a2a2a; color: #D4AF37; }
  .btn-wa-main { width: 100%; background: #25D366; color: #fff; border: none; border-radius: 999px; padding: 13px; font-size: 13.5px; font-weight: 800; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-wa-main:hover { filter: brightness(.95); color: #fff; }
  .btn-shop-again { width: 100%; background: #fff; color: #141414; border: 1.5px solid #e7dfcb; border-radius: 999px; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; box-sizing: border-box; }
  .btn-shop-again:hover { border-color: #D4AF37; background: #F8F1DC; }

  @media (max-width: 640px) {
    .cart-head h1 { font-size: 22px; }
    .cart-steps { width: 100%; overflow-x: auto; padding-bottom: 4px; }
    .done-card, .co-card { padding: 22px 17px; }
  }
</style>

<main class="cart-page pt-90">
  <div class="cart-wrap">
    <div class="cart-breadcrumb">
      <a href="{{ route('home.index') }}">Beranda</a> &nbsp;/&nbsp;
      <span class="current">Konfirmasi Pesanan</span>
    </div>

    <div class="cart-head">
      <h1>Konfirmasi Pesanan</h1>
      <div class="cart-steps">
        <div class="step"><b>1</b> Keranjang</div>
        <div class="dash"></div>
        <div class="step"><b>2</b> Checkout</div>
        <div class="dash"></div>
        <div class="step active"><b>3</b> Selesai</div>
      </div>
    </div>

    <div class="done-card">
      <svg width="72" height="72" viewBox="0 0 90 90" fill="none">
        <circle cx="45" cy="45" r="45" fill="#D4AF37" />
        <path d="M28 46l12 12 22-26" stroke="#fff" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <h3>Pesanan Berhasil Dibuat</h3>
      <p>Terima kasih telah berbelanja di Azzahera Label. Silakan lakukan pembayaran dan konfirmasi melalui WhatsApp.</p>
    </div>

    <div class="co-card">
      <div class="info-rows">
        <div class="ir"><span>Nomor Pesanan</span><strong>#AZZ-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></div>
        <div class="ir"><span>Tanggal</span><strong>{{ $order->created_at->format('d M Y') }}</strong></div>
        <div class="ir"><span>Total Pembayaran</span><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></div>
        <div class="ir"><span>Status</span><span class="st-badge">Menunggu Pembayaran</span></div>
      </div>
    </div>

    <div class="co-card">
      <h3>Informasi Pembayaran</h3>
      <div class="info-rows">
        <div class="ir"><span>Bank</span><strong>BCA</strong></div>
        <div class="ir"><span>No. Rekening</span><strong>1234567890</strong></div>
        <div class="ir"><span>Atas Nama</span><strong>CV Azzahera Label</strong></div>
        <div class="ir"><span>Total Transfer</span><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></div>
      </div>

      <div class="alert-soft">Setelah transfer, kirim bukti pembayaran melalui WhatsApp agar pesanan segera diproses.</div>

      @php
        $waText = urlencode(
          "Halo Admin Azzahera Label,\n\n" .
          "Saya ingin konfirmasi pembayaran pesanan:\n" .
          "No Order: #AZZ-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . "\n" .
          "Total Transfer: Rp" . number_format($order->total, 0, ',', '.') . "\n\n" .
          "Saya akan mengirimkan bukti transfer. Terima kasih."
        );
      @endphp

      <a href="https://wa.me/62882005332646?text={{ $waText }}" target="_blank" class="btn-wa-main">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93a7.898 7.898 0 0 0-2.327-5.607zM7.994 14.52a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
        Konfirmasi via WhatsApp
      </a>

      <a href="{{ route('user.orders') }}" class="btn-checkout-main">Lihat Pesanan Saya</a>
      <a href="{{ route('home.index') }}" class="btn-shop-again">Kembali ke Beranda</a>
    </div>
  </div>
</main>
@endsection
