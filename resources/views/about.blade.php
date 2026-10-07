@extends('layouts.app')

@section('content')

<style>
    .about-page { margin: 0 !important; padding: 0 !important; background: #fff; color: #222; }
    .about-page h1, .about-page h2, .about-page h3, .about-page h4 { font-family: 'Jost', 'Inter', sans-serif; }
    .about-page p, .about-page span, .about-page a, .about-page li { font-family: 'Inter', 'Jost', system-ui, sans-serif; }

    .about-page .about-head {
        padding: 130px 0 34px; text-align: center;
        border-bottom: 1px solid #eee;
    }
    .about-page .about-head h1 { margin: 0 0 8px; font-size: 28px; font-weight: 700; color: #161616; }
    .about-page .about-head h1::after {
        content: ""; display: block; width: 52px; height: 3px;
        margin: 12px auto 0; background: #8a6d1f; border-radius: 3px;
    }
    .about-page .about-head p { max-width: 560px; margin: 0 auto; font-size: 15px; line-height: 1.75; color: #666; }

    .about-page .about-body { padding: 40px 0 56px; }
    .about-page .narrow { max-width: 860px; margin: 0 auto; }

    .about-page .sec-title {
        margin: 0 0 12px; padding-left: 14px; position: relative;
        font-size: 20px; font-weight: 700; color: #161616;
    }
    .about-page .sec-title::before {
        content: ""; position: absolute; left: 0; top: 3px;
        width: 4px; height: 22px; border-radius: 4px; background: #8a6d1f;
    }
    .about-page .about-text { font-size: 14.5px; line-height: 1.9; color: #4a4a4a; margin: 0 0 12px; }

    .about-page .vm { display: grid; grid-template-columns: 1fr 1.4fr; gap: 14px; margin-top: 28px; }
    .about-page .vm-box {
        background: #faf8f1; border: 1px solid #efe6cd;
        border-radius: 12px; padding: 24px;
    }
    .about-page .vm-box h3 { margin: 0 0 10px; font-size: 16px; font-weight: 700; color: #161616; }
    .about-page .vm-box p, .about-page .vm-box li { font-size: 14px; line-height: 1.8; color: #4a4a4a; }
    .about-page .vm-box p { margin: 0; }
    .about-page .vm-box ol { margin: 0; padding-left: 20px; }
    .about-page .vm-box ol li { margin-bottom: 6px; }
    .about-page .vm-box ol li:last-child { margin-bottom: 0; }

    .about-page .contact { margin-top: 32px; padding-top: 28px; border-top: 1px solid #eee; }
    .about-page .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: center; }
    .about-page .contact h4 { margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #161616; }
    .about-page .contact p { margin: 0; font-size: 14px; line-height: 1.75; color: #666; }
    .about-page .contact-item {
        background: #faf8f1; border: 1px solid #efe6cd; border-radius: 10px;
        padding: 13px 16px; margin-bottom: 10px; font-size: 14px; color: #333;
    }
    .about-page .contact-item:last-child { margin-bottom: 0; }
    .about-page .contact-item small { display: block; font-size: 12px; color: #999; margin-bottom: 2px; }
    .about-page .contact-item a { color: #161616; font-weight: 700; text-decoration: none; }

    .about-page .back { text-align: center; margin-top: 30px; }
    .about-page .btn-dark {
        display: inline-block; padding: 12px 30px; border-radius: 999px;
        background: #161616; border: 1px solid #161616; color: #fff !important;
        font-size: 14px; font-weight: 700; text-decoration: none !important;
    }
    .about-page .btn-line {
        display: inline-block; padding: 12px 30px; border-radius: 999px;
        background: #fff; border: 1px solid #d6d6d6; color: #161616 !important;
        font-size: 14px; font-weight: 700; text-decoration: none !important;
    }

    .about-page .photo { margin-top: 26px; }
    .about-page .photo img {
        width: 100%; height: 340px; object-fit: cover; display: block;
        border-radius: 12px; border: 1px solid #eee;
    }
    .about-page .photo figcaption { margin-top: 8px; font-size: 12.5px; color: #999; }

    .about-page .svc { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 16px; }
    .about-page .svc-card { border: 1px solid #eee; border-radius: 12px; padding: 20px; background: #fff; }
    .about-page .svc-card.mid { background: #faf8f1; border-color: #efe6cd; }
    .about-page .svc-letter {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 8px; margin-bottom: 10px;
        background: #161616; color: #fff; font-size: 13px; font-weight: 800;
    }
    .about-page .svc-card.mid .svc-letter { background: #8a6d1f; }
    .about-page .svc-card h3 { margin: 0 0 6px; font-size: 15px; font-weight: 700; color: #161616; }
    .about-page .svc-card p { margin: 0; font-size: 13.5px; line-height: 1.7; color: #555; }

    .about-page .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 16px 0 0; padding: 0; list-style: none; }
    .about-page .steps li { border: 1px solid #eee; border-radius: 12px; padding: 18px; background: #fff; font-size: 13.5px; line-height: 1.7; color: #444; }
    .about-page .steps li:nth-child(even) { background: #fafafa; }
    .about-page .steps .n { display: block; margin-bottom: 8px; font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #8a6d1f; }
    .about-page .steps b { color: #161616; }

    @media (max-width: 767px) {
        .about-page .about-head { padding: 104px 16px 26px; }
        .about-page .vm, .about-page .contact-grid { grid-template-columns: 1fr; }
        .about-page .svc { grid-template-columns: 1fr; }
        .about-page .steps { grid-template-columns: 1fr 1fr; }
        .about-page .photo img { height: 230px; }
    }
</style>

<main class="about-page">
    <section class="about-head">
        <div class="container">
            <h1>Tentang Kami</h1>
            <p>Melayani dengan ketulusan dan memberikan yang terbaik bagi setiap pelanggan.</p>
        </div>
    </section>

    <section class="container about-body">
        <div class="narrow">
            <div class="row g-4">
                <div class="col-md-6">
                    <h2 class="sec-title">Dedikasi dan Kepercayaan</h2>
                    <p class="about-text">
                        Kami adalah tim yang berdedikasi untuk menyediakan
                        layanan dan produk terbaik kepada pelanggan kami.
                        Berawal dari visi untuk memudahkan masyarakat dalam
                        memenuhi kebutuhannya secara online, kami terus
                        berkembang dan berinovasi agar selalu relevan dan
                        dapat diandalkan.
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="about-text" style="margin-top:38px;">
                        Dengan mengutamakan kepercayaan, keamanan, dan
                        kemudahan, kami percaya bahwa pelayanan yang tulus
                        dan profesional adalah kunci utama membangun hubungan
                        jangka panjang dengan pelanggan.
                    </p>
                </div>
            </div>

            <figure class="photo" style="margin:26px 0 0;">
                <img src="{{ asset('assets/images/about/about-1.jpg') }}" alt="Azzahera Label" loading="lazy" onerror="this.src='{{ asset('assets/images/logowebsite.jpeg') }}'">
                <figcaption>Konveksi Gebog, Kudus — gamis, abaya, kemeja. Ecer, lusinan, partai.</figcaption>
            </figure>

            <h2 class="sec-title" style="margin-top:30px;">Layanan Konveksi</h2>
            <div class="svc">
                <div class="svc-card">
                    <div class="svc-letter">A</div>
                    <h3>Grosir</h3>
                    <p>Lusinan dan partai, mix model dan ukuran. Cocok untuk reseller.</p>
                </div>
                <div class="svc-card mid">
                    <div class="svc-letter">B</div>
                    <h3>Seragam</h3>
                    <p>Satu model banyak ukuran. Sekolah, kantor, majelis, komunitas.</p>
                </div>
                <div class="svc-card">
                    <div class="svc-letter">C</div>
                    <h3>Custom</h3>
                    <p>Request model, bahan, dan ukuran. Kirim contoh gambar.</p>
                </div>
            </div>

            <div class="vm">
                <div class="vm-box">
                    <h3>Visi Kami</h3>
                    <p>Menciptakan produk yang berkualitas, inovatif, kreatif, dan kompetitif, yang mampu menembus pasar global dengan bersaing secara sehat.</p>
                </div>
                <div class="vm-box">
                    <h3>Misi Kami</h3>
                    <ol>
                        <li>Menciptakan lapangan pekerjaan untuk kesejahteraan bersama dan daya saing tinggi.</li>
                        <li>Mengutamakan tenaga kerja lokal untuk mengentaskan kemiskinan di sekitar lingkungan perusahaan.</li>
                        <li>Membangun komunitas dan kerja sama yang saling menguntungkan.</li>
                    </ol>
                </div>
            </div>

            <h2 class="sec-title" style="margin-top:30px;">Cara Order Grosir / Seragam</h2>
            <ol class="steps">
                <li><span class="n">01</span><b>Chat.</b> Ceritakan model, jumlah, dan untuk kapan.</li>
                <li><span class="n">02</span><b>Sepakat.</b> Bahan, ukuran, harga, estimasi waktu.</li>
                <li><span class="n">03</span><b>Produksi.</b> Dijahit dan dikabari perkembangannya.</li>
                <li><span class="n">04</span><b>Kirim.</b> Dicek satu per satu, lalu dikirim.</li>
            </ol>

            <div class="contact">
                <div class="contact-grid">
                    <div>
                        <h4>Hubungi Kami</h4>
                        <p>Jika Anda memiliki pertanyaan atau ingin bekerja sama, jangan ragu untuk menghubungi kami.</p>
                    </div>
                    <div>
                        <div class="contact-item">
                            <small>WhatsApp / Telepon</small>
                            <a href="https://wa.me/6281325742455" target="_blank" rel="noopener">0813-2574-2455</a>
                        </div>
                        <div class="contact-item">
                            <small>Alamat</small>
                            <span>Ngaringan Klumpit Rt 03 Rw 06, Gebog, Kudus, Jawa Tengah 59333</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="back">
                <a href="https://wa.me/6281325742455?text=Halo%20Azzahera%20Label%2C%20saya%20mau%20tanya%20jahit%20grosir%2Fseragam%2Fcustom." target="_blank" rel="noopener" class="btn-dark">Minta Penawaran</a>
                <a href="{{ route('home.index') }}" class="btn-line" style="margin-left:10px;">Kembali ke Beranda</a>
            </div>
        </div>
    </section>
</main>

@endsection
