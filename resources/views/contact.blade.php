@extends('layouts.app')

@section('content')

<style>
    .contact-page { margin: 0 !important; padding: 0 !important; background: #fff; color: #222; }
    .contact-page h1, .contact-page h2, .contact-page h3 { font-family: 'Jost', 'Inter', sans-serif; }
    .contact-page p, .contact-page span, .contact-page a, .contact-page li,
    .contact-page label, .contact-page input, .contact-page select, .contact-page textarea, .contact-page button {
        font-family: 'Inter', 'Jost', system-ui, sans-serif;
    }

    .contact-page .kp-head { padding: 130px 0 10px; text-align: center; }
    .contact-page .kp-head h1 { margin: 0 0 8px; font-size: 28px; font-weight: 700; color: #161616; }
    .contact-page .kp-head h1::after {
        content: ""; display: block; width: 52px; height: 3px;
        margin: 12px auto 0; background: #8a6d1f; border-radius: 3px;
    }
    .contact-page .kp-head p { max-width: 560px; margin: 0 auto; font-size: 14.5px; line-height: 1.75; color: #666; }

    .contact-page .kp-body { padding: 28px 0 56px; }
    .contact-page .kp-narrow { max-width: 920px; margin: 0 auto; }
    .contact-page .kp-grid { display: grid; grid-template-columns: 320px 1fr; gap: 16px; align-items: start; }

    .contact-page .kp-info { background: #faf8f1; border: 1px solid #efe6cd; border-radius: 12px; padding: 24px; }
    .contact-page .kp-info h2 { margin: 0 0 6px; font-size: 17px; font-weight: 700; color: #161616; }
    .contact-page .kp-info > p { margin: 0 0 18px; font-size: 13.5px; line-height: 1.7; color: #666; }
    .contact-page .kp-row {
        background: #fff; border: 1px solid #eee6cf; border-radius: 10px;
        padding: 12px 14px; margin-bottom: 10px;
    }
    .contact-page .kp-row:last-child { margin-bottom: 0; }
    .contact-page .kp-row small { display: block; margin-bottom: 2px; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #999; }
    .contact-page .kp-row strong { font-size: 14px; color: #161616; }
    .contact-page .kp-row a { color: #161616; font-weight: 700; text-decoration: none; }
    .contact-page .kp-row a:hover { text-decoration: underline; }
    .contact-page .kp-row span { font-size: 13.5px; line-height: 1.7; color: #444; }

    .contact-page .kp-form-card { border: 1px solid #e9e9e9; border-radius: 12px; padding: 26px; background: #fff; }
    .contact-page .kp-form-card h2 { margin: 0 0 4px; font-size: 17px; font-weight: 700; color: #161616; }
    .contact-page .kp-form-card > p { margin: 0 0 20px; font-size: 13.5px; color: #777; }
    .contact-page .kp-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .contact-page .kp-field { margin-bottom: 14px; }
    .contact-page .kp-field label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #555; }
    .contact-page .kp-field input, .contact-page .kp-field select, .contact-page .kp-field textarea {
        width: 100%; padding: 11px 14px; font-size: 14px; color: #222;
        border: 1px solid #ddd; border-radius: 10px; background: #fff; outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }
    .contact-page .kp-field textarea { min-height: 120px; resize: vertical; }
    .contact-page .kp-field input:focus, .contact-page .kp-field select:focus, .contact-page .kp-field textarea:focus {
        border-color: #8a6d1f; box-shadow: 0 0 0 3px rgba(138, 109, 31, 0.14);
    }
    .contact-page .kp-err { display: block; min-height: 16px; margin-top: 4px; font-size: 12px; color: #dc3545; }
    .contact-page .kp-submit {
        width: 100%; padding: 13px; border: none; border-radius: 999px;
        background: #161616; color: #fff; font-size: 14px; font-weight: 700; cursor: pointer;
    }
    .contact-page .kp-submit:hover { background: #000; }
    .contact-page .kp-note { margin: 12px 0 0; font-size: 12.5px; text-align: center; color: #999; }

    @media (max-width: 860px) {
        .contact-page .kp-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767px) {
        .contact-page .kp-head { padding: 104px 16px 6px; }
        .contact-page .kp-2col { grid-template-columns: 1fr; }
        .contact-page .kp-form-card { padding: 20px; }
    }
</style>

<main class="contact-page">
    <section class="kp-head">
        <div class="container">
            <h1>Kontak Kami</h1>
            <p>Isi form di bawah, pesan Anda langsung terkirim ke WhatsApp admin. Cocok untuk tanya produk, grosir, seragam, atau custom.</p>
        </div>
    </section>

    <section class="container kp-body">
        <div class="kp-narrow">
            <div class="kp-grid">
                <aside class="kp-info">
                    <h2>Info Kontak</h2>
                    <p>Lebih suka chat langsung? Hubungi kami di sini.</p>
                    <div class="kp-row">
                        <small>WhatsApp / Telepon</small>
                        <a href="https://wa.me/6281325742455" target="_blank" rel="noopener">0813-2574-2455</a>
                    </div>
                    <div class="kp-row">
                        <small>Alamat</small>
                        <span>Ngaringan Klumpit Rt 03 Rw 06, Gebog, Kudus, Jawa Tengah 59333</span>
                    </div>
                    <div class="kp-row">
                        <small>Produk</small>
                        <span>Gamis, abaya, kemeja — ecer, lusinan, partai.</span>
                    </div>
                </aside>

                <div class="kp-form-card">
                    <h2>Kirim Pesan</h2>
                    <p>Lengkapi data berikut, lalu tekan tombol kirim.</p>
                    <form id="contact-us-wa-form" novalidate>
                        <div class="kp-2col">
                            <div class="kp-field">
                                <label for="wa_name">Nama</label>
                                <input type="text" name="name" id="wa_name" placeholder="Nama Anda" autocomplete="name">
                                <span class="kp-err" id="err_name"></span>
                            </div>
                            <div class="kp-field">
                                <label for="wa_phone">No. Handphone</label>
                                <input type="text" name="phone" id="wa_phone" placeholder="08xx-xxxx-xxxx" autocomplete="tel">
                                <span class="kp-err" id="err_phone"></span>
                            </div>
                        </div>
                        <div class="kp-2col">
                            <div class="kp-field">
                                <label for="wa_email">Email</label>
                                <input type="email" name="email" id="wa_email" placeholder="nama@email.com" autocomplete="email">
                                <span class="kp-err" id="err_email"></span>
                            </div>
                            <div class="kp-field">
                                <label for="wa_topic">Keperluan</label>
                                <select name="topic" id="wa_topic">
                                    <option value="Tanya produk">Tanya produk</option>
                                    <option value="Grosir">Grosir / lusinan</option>
                                    <option value="Seragam">Seragam</option>
                                    <option value="Custom">Custom jahitan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <span class="kp-err"></span>
                            </div>
                        </div>
                        <div class="kp-field">
                            <label for="wa_comment">Pesan</label>
                            <textarea name="comment" id="wa_comment" rows="5" placeholder="Tulis pesan Anda, contoh: model, jumlah, dan untuk kapan..."></textarea>
                            <span class="kp-err" id="err_comment"></span>
                        </div>
                        <button type="submit" class="kp-submit">Kirim via WhatsApp</button>
                        <p class="kp-note">Pesan dibuka di WhatsApp — periksa kembali sebelum dikirim.</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document
        .getElementById("contact-us-wa-form")
        .addEventListener("submit", function (e) {
            e.preventDefault();

            const name = document
                .getElementById("wa_name")
                .value
                .trim();

            const phone = document
                .getElementById("wa_phone")
                .value
                .trim();

            const email = document
                .getElementById("wa_email")
                .value
                .trim();

            const topic = document
                .getElementById("wa_topic")
                .value;

            const comment = document
                .getElementById("wa_comment")
                .value
                .trim();

            const errName =
                document.getElementById("err_name");

            const errPhone =
                document.getElementById("err_phone");

            const errEmail =
                document.getElementById("err_email");

            const errComment =
                document.getElementById("err_comment");

            errName.innerText = "";
            errPhone.innerText = "";
            errEmail.innerText = "";
            errComment.innerText = "";

            let isValid = true;

            if (!name) {
                errName.innerText = "Nama wajib diisi.";
                isValid = false;
            }

            if (!phone) {
                errPhone.innerText = "No. Handphone wajib diisi.";
                isValid = false;
            }

            if (!email) {
                errEmail.innerText = "Email wajib diisi.";
                isValid = false;
            }

            if (!comment) {
                errComment.innerText = "Pesan wajib diisi.";
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            const emailPattern =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                errEmail.innerText =
                    "Format email tidak valid.";
                return;
            }

            const phonePattern =
                /^[0-9+\-\s]+$/;

            if (!phonePattern.test(phone)) {
                errPhone.innerText =
                    "Format nomor handphone tidak valid.";
                return;
            }

            const whatsappNumber =
                "6281325742455";

            const message = encodeURIComponent(
`Halo Admin Azzahera Label,

Saya ingin menghubungi Anda.

Nama: ${name}
No. HP: ${phone}
Email: ${email}
Keperluan: ${topic}
Pesan: ${comment}`
            );

            const waUrl =
                `https://wa.me/${whatsappNumber}?text=${message}`;

            window.open(waUrl, "_blank");
        });
</script>

@endsection
