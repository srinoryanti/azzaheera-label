@extends('layouts.app')

@push('styles')
{{-- Ikon halaman Tentang Kami (font mengikuti font global situs: Inter) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')

<style>
    :root {
        --azzahera-gold: #D4AF37;
        --azzahera-gold-dark: #B8962E;
        --azzahera-gold-soft: #F8F1DC;
        --azzahera-black: #171717;
        --azzahera-text: #4A4A4A;
        --azzahera-muted: #777777;
        --azzahera-border: #EAEAEA;
        --azzahera-white: #FFFFFF;
    }

    .about-page {
        margin: 0 !important;
        padding-top: 90px !important;
        background-color: var(--azzahera-white);
    }

    .about-page h1, .about-page h2, .about-page h3, .about-page h4, .about-page h5,
    .about-page p, .about-page span, .about-page a, .about-page li, .about-page small {
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
    }

    .about-header {
        position: relative;
        padding: 40px 0 38px;
        margin: 0 0 45px;
        overflow: hidden;
        text-align: center;
        background-color: #FCFCFC;
        border-bottom: 1px solid var(--azzahera-border);
    }

    .about-header::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 0;
        width: 80px;
        height: 3px;
        background-color: var(--azzahera-gold);
        transform: translateX(-50%);
    }

    .about-title {
        margin: 0 0 10px;
        color: var(--azzahera-black);
        font-size: clamp(26px, 2.8vw, 36px);
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.3px;
    }

    .about-subtitle {
        max-width: 650px;
        margin: 0 auto;
        color: var(--azzahera-muted);
        font-size: 17px;
        line-height: 1.7;
    }

    .about-intro {
        margin-bottom: 50px;
    }

    .about-section-title {
        position: relative;
        margin-bottom: 20px;
        padding-left: 22px;
        color: var(--azzahera-black);
        font-size: 26px;
        font-weight: 700;
    }

    .about-section-title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 4px;
        width: 4px;
        height: 26px;
        border-radius: 10px;
        background-color: var(--azzahera-gold);
    }

    .about-description {
        margin-bottom: 0;
        color: var(--azzahera-text);
        font-size: 15px;
        line-height: 1.9;
        text-align: justify;
    }

    .card-vision {
        position: relative;
        height: 100%;
        padding: 30px;
        overflow: hidden;
        background-color: #FCFBF7;
        border: 1px solid #EFE7CF;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
    }

    .card-vision::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: var(--azzahera-gold);
    }

    .card-vision:hover {
        transform: translateY(-4px);
        border-color: var(--azzahera-gold);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
    }

    .vision-title {
        margin-bottom: 16px;
        color: var(--azzahera-gold-dark);
        font-size: 19px;
        font-weight: 700;
    }

    .vision-text {
        margin-bottom: 0;
        color: var(--azzahera-text);
        font-size: 14px;
        line-height: 1.8;
    }

    .mission-list {
        margin: 0;
        padding-left: 0;
        list-style: none;
    }

    .mission-list li {
        position: relative;
        margin-bottom: 14px;
        padding-left: 30px;
        color: var(--azzahera-text);
        font-size: 14px;
        line-height: 1.7;
    }

    .mission-list li:last-child {
        margin-bottom: 0;
    }

    .mission-list li::before {
        content: "\f26a";
        position: absolute;
        left: 0;
        top: 1px;
        color: var(--azzahera-gold-dark);
        font-family: "bootstrap-icons";
        font-size: 16px;
    }

    .contact-section {
        margin-top: 10px;
        padding-top: 40px;
        border-top: 1px solid var(--azzahera-border);
    }

    .contact-title {
        margin-bottom: 12px;
        color: var(--azzahera-black);
        font-size: 25px;
        font-weight: 700;
    }

    .contact-description {
        margin-bottom: 0;
        color: var(--azzahera-muted);
        font-size: 15px;
        line-height: 1.8;
    }

    .contact-badge {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 16px 18px;
        background-color: #FCFBF7;
        border: 1px solid #EFE7CF;
        border-radius: 12px;
        transition:
            transform 0.25s ease,
            border-color 0.25s ease,
            background-color 0.25s ease;
    }

    .contact-badge:last-child {
        margin-bottom: 0;
    }

    .contact-badge:hover {
        transform: translateX(4px);
        background-color: var(--azzahera-gold-soft);
        border-color: var(--azzahera-gold);
    }

    .contact-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        margin-right: 15px;
        flex-shrink: 0;
        color: var(--azzahera-gold-dark);
        font-size: 20px;
        background-color: var(--azzahera-gold-soft);
        border-radius: 50%;
    }

    .contact-label {
        display: block;
        margin-bottom: 3px;
        color: var(--azzahera-muted);
        font-size: 12px;
    }

    .contact-value {
        color: var(--azzahera-black);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .contact-value:hover {
        color: var(--azzahera-gold-dark);
    }

    .btn-azzahera {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 210px;
        padding: 12px 28px;
        color: var(--azzahera-white) !important;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        background-color: var(--azzahera-black);
        border: 1px solid var(--azzahera-black);
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.10);
        transition:
            background-color 0.25s ease,
            border-color 0.25s ease,
            color 0.25s ease,
            transform 0.25s ease;
    }

    .btn-azzahera:hover {
        color: var(--azzahera-black) !important;
        background-color: var(--azzahera-gold);
        border-color: var(--azzahera-gold);
        transform: translateY(-2px);
    }

    @media (max-width: 992px) {
        .about-header {
            padding: 38px 0 36px;
            margin-bottom: 40px;
        }

        .card-vision {
            padding: 26px;
        }
    }

    @media (max-width: 768px) {
        .about-page {
            padding-top: 80px !important;
        }

        .about-header {
            padding: 32px 16px 30px;
            margin-bottom: 35px;
        }

        .about-title {
            font-size: 30px;
        }

        .about-subtitle {
            font-size: 15px;
        }

        .about-section-title {
            margin-top: 0;
            font-size: 22px;
        }

        .about-description {
            text-align: left;
        }

        .about-second-description {
            margin-top: 20px !important;
        }

        .card-vision {
            padding: 24px;
        }

        .contact-section {
            padding-top: 30px;
        }

        .contact-info-wrapper {
            margin-top: 25px;
        }

        .btn-azzahera {
            width: 100%;
            max-width: 280px;
        }
    }

    @media (max-width: 480px) {
        .about-page {
            padding-top: 75px !important;
        }

        .about-header {
            padding: 28px 14px 26px;
        }

        .about-title {
            font-size: 27px;
        }

        .card-vision {
            padding: 22px 20px;
        }

        .contact-badge {
            padding: 14px;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            font-size: 18px;
        }
    }
</style>

<main class="about-page">

    <section class="about-header">
        <div class="container">
            <h1 class="about-title">
                Tentang Kami
            </h1>

            <p class="about-subtitle">
                Melayani dengan ketulusan dan memberikan yang terbaik
                bagi setiap pelanggan.
            </p>
        </div>
    </section>

    <section class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="row about-intro">
                    <div class="col-md-6">
                        <h3 class="about-section-title">
                            Dedikasi & Kepercayaan
                        </h3>

                        <p class="about-description">
                            Kami adalah tim yang berdedikasi untuk menyediakan
                            layanan dan produk terbaik kepada pelanggan kami.
                            Berawal dari visi untuk memudahkan masyarakat dalam
                            memenuhi kebutuhannya secara online, kami terus
                            berkembang dan berinovasi agar selalu relevan dan
                            dapat diandalkan.
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p class="about-description about-second-description mt-md-5">
                            Dengan mengutamakan kepercayaan, keamanan, dan
                            kemudahan, kami percaya bahwa pelayanan yang tulus
                            dan profesional adalah kunci utama membangun hubungan
                            jangka panjang dengan pelanggan.
                        </p>
                    </div>
                </div>

                <div class="row g-4 mb-5">

                    <div class="col-md-5">
                        <div class="card-vision">
                            <h5 class="vision-title">
                                Visi Kami
                            </h5>

                            <p class="vision-text">
                                Menciptakan produk yang berkualitas, inovatif,
                                kreatif, dan kompetitif, yang mampu menembus
                                pasar global dengan bersaing secara sehat.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card-vision">
                            <h5 class="vision-title">
                                Misi Kami
                            </h5>

                            <ul class="mission-list">
                                <li>
                                    Menciptakan lapangan pekerjaan untuk
                                    kesejahteraan bersama dan daya saing tinggi.
                                </li>

                                <li>
                                    Mengutamakan tenaga kerja lokal untuk
                                    mengentaskan kemiskinan di sekitar lingkungan
                                    perusahaan.
                                </li>

                                <li>
                                    Membangun komunitas dan kerja sama yang
                                    saling menguntungkan.
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="contact-section">
                    <div class="row align-items-center">

                        <div class="col-md-6">
                            <h4 class="contact-title">
                                Hubungi Kami
                            </h4>

                            <p class="contact-description">
                                Jika Anda memiliki pertanyaan atau ingin bekerja
                                sama, jangan ragu untuk menghubungi kami.
                            </p>
                        </div>

                        <div class="col-md-6 contact-info-wrapper">

                            <div class="contact-badge">
                                <div class="contact-icon">
                                    <i class="bi bi-whatsapp"></i>
                                </div>

                                <div>
                                    <small class="contact-label">
                                        WhatsApp / Telepon
                                    </small>

                                    <a
                                        href="https://wa.me/6281325742455"
                                        class="contact-value"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        0813-2574-2455
                                    </a>
                                </div>
                            </div>

                            <div class="contact-badge">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div>
                                    <small class="contact-label">
                                        Alamat
                                    </small>

                                    <span class="contact-value">
                                        Gebog, Kudus, Jawa Tengah 59333
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <a
                        href="{{ route('home.index') }}"
                        class="btn-azzahera"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </section>

</main>

@endsection
