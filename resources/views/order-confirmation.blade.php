@extends('layouts.app')

@section('content')
<style>
  /* Gaya polos selaras keranjang/checkout/payment: tanpa card/box, background putih */
  .cart-page { background: #fff; min-height: 60vh; }
  .cart-wrap { max-width: 1200px; margin: 0 auto; padding: 18px 20px 60px; }
  .cart-breadcrumb { font-size: 13px; color: #777; margin-bottom: 18px; text-transform: uppercase; letter-spacing: .3px; }
  .cart-breadcrumb a { color: #111; text-decoration: none; font-weight: 600; }
  .cart-breadcrumb a:hover { text-decoration: underline; }
  .cart-breadcrumb span.current { color: #111; font-weight: 400; }

  .cart-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 18px; }
  .cart-head h1 { font-size: 28px; font-weight: 800; color: #141414; margin: 0; letter-spacing: -.3px; text-transform: uppercase; }

  .done-card { background: transparent; border: none; border-radius: 0; padding: 32px 20px; text-align: center; box-shadow: none; max-width: 640px; margin: 0 auto 18px; }
  .done-card h3 { font-size: 20px; font-weight: 800; color: #141414; margin: 18px 0 8px; }
  .done-card p { font-size: 13.5px; color: #8a8378; margin: 0; line-height: 1.7; }

  .co-card { background: transparent; border: none; border-radius: 0; padding: 0; margin-bottom: 16px; box-shadow: none; max-width: 640px; margin-left: auto; margin-right: auto; }
  .co-card h3 { font-size: 16px; font-weight: 800; color: #141414; margin: 0 0 14px; text-transform: uppercase; letter-spacing: .3px; padding-bottom: 14px; border-bottom: 1px solid #e0e0e0; }
  .info-rows { font-size: 13.5px; }
  .info-rows .ir { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid #eee; color: #4A4A4A; }
  .info-rows .ir:last-child { border-bottom: none; }
  .info-rows .ir span:first-child { color: #8a8378; font-weight: 600; font-size: 12.5px; }
  .info-rows .ir strong { color: #141414; }
  .st-badge { background: transparent; color: #8a6100; font-size: 12px; font-weight: 800; padding: 0; border-radius: 0; }
  .alert-soft { background: transparent; border: none; border-top: 1px solid #eee; border-radius: 0; padding: 12px 0; font-size: 12.5px; color: #4A4A4A; line-height: 1.6; margin-top: 14px; }
  .alert-soft ul { margin: 6px 0 0; padding-left: 18px; }
  .alert-soft li { margin-bottom: 3px; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 0; padding: 16px; font-size: 14.5px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-checkout-main:hover { background: #000; color: #fff; }
  .btn-shop-again { width: 100%; background: #fff; color: #141414; border: 1px solid #ddd; border-radius: 0; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; box-sizing: border-box; }
  .btn-shop-again:hover { border-color: #141414; background: #fff; }

  @media (max-width: 640px) {
    .cart-head h1 { font-size: 22px; }
    .done-card, .co-card { padding: 22px 0; }
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
