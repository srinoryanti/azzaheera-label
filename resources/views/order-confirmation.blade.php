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
  .alert-soft ul { margin: 6px 0 0; padding-left: 18px; }
  .alert-soft li { margin-bottom: 3px; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 999px; padding: 14px; font-size: 14.5px; font-weight: 800; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-checkout-main:hover { background: #2a2a2a; color: #D4AF37; }
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
        <circle cx="45" cy="45" r="45" fill="#198754"/>
        <path d="M25 46L39 60L65 32" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <h3>Pesanan Berhasil Dibuat</h3>
      <p>Selanjutnya lakukan pembayaran sesuai instruksi, lalu kirim bukti transfer melalui WhatsApp agar pesanan segera diverifikasi oleh Admin Azzahera Label.</p>
    </div>

    <div class="co-card">
      <h3>Informasi Pesanan</h3>
      <div class="info-rows">
        <div class="ir"><span>Nomor Order</span><strong>#AZZ-{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</strong></div>
        <div class="ir"><span>Tanggal</span><strong>{{ $order->created_at->format('d M Y H:i') }}</strong></div>
        <div class="ir"><span>Total Pembayaran</span><strong>Rp{{ number_format($order->total,0,',','.') }}</strong></div>
        <div class="ir"><span>Status</span>@if($order->status === 'waiting_verification')<span class="st-badge">Menunggu Verifikasi Admin</span>@elseif($order->status === 'pending_payment')<span class="st-badge">Menunggu Pembayaran</span>@else<span class="st-badge">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>@endif</div>
      </div>

      <div class="alert-soft">
        <strong>Informasi :</strong>
        <ul>
          <li>Admin akan memverifikasi pembayaran Anda.</li>
          <li>Proses verifikasi maksimal 1 x 24 jam.</li>
          <li>Setelah pembayaran diverifikasi, pesanan akan segera diproses.</li>
        </ul>
      </div>

      <a href="{{ route('user.orders') }}" class="btn-checkout-main">Lihat Pesanan Saya</a>
      <a href="{{ route('home.index') }}" class="btn-shop-again">Kembali ke Beranda</a>
    </div>
  </div>
</main>
@endsection
