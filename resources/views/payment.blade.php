@extends('layouts.app')
@section('content')
<style>
  /* Gaya polos selaras keranjang/checkout: tanpa card/box, background putih */
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
  .co-card > h3 { font-size: 16px; font-weight: 800; color: #141414; margin: 0 0 14px; text-transform: uppercase; letter-spacing: .3px; padding-bottom: 14px; border-bottom: 1px solid #e0e0e0; }

  .pay-hero { text-align: center; padding: 6px 0 2px; }
  .pay-hero h3 { font-size: 19px; font-weight: 800; color: #141414; margin: 14px 0 6px; padding: 0; border: none; text-transform: none; letter-spacing: 0; }
  .pay-hero p { font-size: 13.5px; color: #8a8378; margin: 0; }

  .co-item { display: flex; gap: 14px; padding: 13px 0; border-bottom: 1px solid #eee; }
  .co-item:last-child { border-bottom: none; }
  .co-item .nm { font-size: 13.5px; font-weight: 700; color: #141414; margin: 0 0 5px; line-height: 1.4; }
  .co-item .vr { display: inline-block; font-size: 12px; font-weight: 400; color: #555; background: transparent; border: none; padding: 0; border-radius: 0; margin-bottom: 5px; }
  .co-item .qt { font-size: 12px; color: #8a8378; }
  .co-item .lt { margin-left: auto; font-size: 13.5px; font-weight: 800; color: #141414; white-space: nowrap; }

  .info-rows { font-size: 13.5px; }
  .info-rows .ir { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid #eee; color: #4A4A4A; }
  .info-rows .ir:last-child { border-bottom: none; }
  .info-rows .ir span:first-child { color: #8a8378; font-weight: 600; font-size: 12.5px; }
  .info-rows .ir strong { color: #141414; }
  .st-badge { background: transparent; color: #8a6100; font-size: 12px; font-weight: 800; padding: 0; border-radius: 0; }
  .link-salin { margin-left: 8px; font-size: 12px; font-weight: 700; color: #141414; text-decoration: underline; }
  .link-salin:hover { color: #000; }
  .qris-box { text-align: center; margin-top: 14px; }
  .qris-box img { width: 110px; height: 110px; border: 1px solid #ececec; border-radius: 8px; }
  .qris-box p { font-size: 11px; color: #8a8378; margin-top: 6px; }

  .summary-card { background: transparent; border: 1px solid #141414; border-radius: 0; padding: 28px; box-shadow: none; position: static; }
  .summary-card h3 { font-size: 17px; font-weight: 800; margin: 0 0 16px; color: #141414; text-transform: uppercase; letter-spacing: .4px; }
  .summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; padding: 8px 0; color: #4A4A4A; }
  .summary-row.total { border-top: 1px solid #eee; margin-top: 10px; padding-top: 15px; }
  .summary-row.total strong { font-size: 22px; font-weight: 800; color: #141414; }
  .alert-soft { background: transparent; border: none; border-top: 1px solid #eee; border-radius: 0; padding: 12px 0; font-size: 12.5px; color: #4A4A4A; line-height: 1.6; margin-top: 14px; }

  .btn-checkout-main { width: 100%; background: #141414; color: #fff; border: none; border-radius: 0; padding: 16px; font-size: 14.5px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; cursor: pointer; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-checkout-main:hover { background: #000; color: #fff; }
  .btn-wa-main { width: 100%; background: #25D366; color: #fff; border: none; border-radius: 0; padding: 13px; font-size: 13.5px; font-weight: 800; cursor: pointer; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 9px; text-decoration: none; transition: .18s; box-sizing: border-box; }
  .btn-wa-main:hover { filter: brightness(.95); color: #fff; }
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
      <span class="current">Pembayaran</span>
    </div>

    <div class="cart-head">
      <h1>Pembayaran</h1>
    </div>

    <div class="cart-grid">
      <div>
        <div class="co-card">
          <div class="pay-hero">
            <svg width="72" height="72" viewBox="0 0 90 90" fill="none">
              <circle cx="45" cy="45" r="45" fill="#D4AF37"/>
              <text x="50%" y="58%" text-anchor="middle" fill="#fff" font-size="42" font-weight="bold">!</text>
            </svg>
            <h3>Pesanan Berhasil Dibuat</h3>
            <p>Silakan lakukan pembayaran sesuai informasi di bawah ini.</p>
          </div>
        </div>

        <div class="co-card">
          <h3>Ringkasan Pesanan</h3>
          @foreach($order->orderItems as $item)
            @php $payOpt = $item->options ? json_decode($item->options, true) : []; @endphp
            <div class="co-item">
              <div style="min-width:0;">
                <p class="nm">{{ $item->product->name ?? 'Produk' }}</p>
                @if(!empty($payOpt['color']) || !empty($payOpt['size']))
                  <span class="vr">Variasi: {{ $payOpt['color'] ?? '-' }}{{ !empty($payOpt['size']) ? ' • Ukuran: ' . $payOpt['size'] : '' }}</span><br>
                @endif
                <span class="qt">Jumlah: {{ $item->quantity }} pcs</span>
              </div>
              <div class="lt">Rp{{ number_format($item->price * $item->quantity,0,',','.') }}</div>
            </div>
          @endforeach
        </div>

        <div class="co-card">
          <h3>Informasi Pembayaran</h3>
          <div class="info-rows">
            <div class="ir"><span>Bank</span><strong>{{ $bank['nama'] ?? 'BCA' }}</strong></div>
            <div class="ir"><span>No. Rekening</span><strong><span id="rekVal">{{ $bank['rekening'] ?? '1234567890' }}</span><a href="javascript:void(0)" onclick="copyRek()" class="link-salin">[Salin]</a></strong></div>
            <div class="ir"><span>Atas Nama</span><strong>{{ $bank['pemilik'] ?? 'CV Azzahera Label' }}</strong></div>
            <div class="ir"><span>Total Transfer</span><strong>Rp{{ number_format($order->total,0,',','.') }}</strong></div>
          </div>
          @if(!empty($bank['qris']))
          <div class="qris-box">
            <img src="{{ asset($bank['qris']) }}" alt="QRIS" onerror="this.style.display='none'">
            <p>Scan QRIS untuk pembayaran instan</p>
          </div>
          @endif
        </div>
      </div>

      <div>
        <div class="summary-card">
          <h3>Informasi Pesanan</h3>
          <div class="summary-row"><span>Nomor Pesanan</span><b>#AZZ-{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</b></div>
          <div class="summary-row"><span>Tanggal</span><b>{{ optional($order->created_at)->format('d M Y H:i') ?? '-' }}</b></div>
          <div class="summary-row"><span>Status</span><span class="st-badge">Menunggu Pembayaran</span></div>
          <div class="summary-row total"><span>Total Bayar</span><strong>Rp{{ number_format($order->total,0,',','.') }}</strong></div>

          <div class="alert-soft">Setelah melakukan transfer, klik tombol di bawah ini untuk mengirimkan bukti pembayaran melalui WhatsApp.</div>

          @php
            $waText = urlencode("Halo Admin Azzahera Label,\n\nSaya telah melakukan pembayaran.\nNo Order : #AZZ-".str_pad($order->id,5,'0',STR_PAD_LEFT)."\nTotal : Rp".number_format($order->total,0,',','.')."\nTanggal: ".optional($order->created_at)->format('d M Y H:i')."\n\nSaya lampirkan bukti transfer. Terima kasih.");
          @endphp
          <a href="https://wa.me/6281325742455?text={{ $waText }}" target="_blank"
             onclick="setTimeout(function(){ window.location='{{ route('cart.order.confirmation',$order->id) }}'; },600);"
             class="btn-wa-main">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93a7.898 7.898 0 0 0-2.327-5.607zM7.994 14.52a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
            Konfirmasi via WhatsApp
          </a>

          <a href="{{ route('cart.order.confirmation',$order->id) }}" class="btn-checkout-main">Lihat Pesanan</a>
          <a href="{{ route('shop.index') }}" class="btn-shop-again">Lanjut Belanja</a>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
function copyRek(){
  const v=document.getElementById('rekVal').innerText.trim();
  navigator.clipboard.writeText(v).then(()=> alert('No. Rekening tersalin: '+v));
}
</script>
@endsection
