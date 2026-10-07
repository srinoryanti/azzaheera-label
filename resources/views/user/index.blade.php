@extends('layouts.app')

@section('content')
<style>
.my-account__dashboard{background:#fff;border:1px solid #e9ecef;border-top:3px solid #D4AF37;border-radius:12px;box-shadow:0 4px 14px rgba(0,0,0,.06);padding:22px !important;}
.my-account__dashboard .list-group{border-radius:10px;overflow:hidden;border:1px solid #ececec;}
.my-account__dashboard .list-group-item{border:none;border-bottom:1px solid #ececec;padding:16px 18px;background:#fff;}
.my-account__dashboard .list-group-item:last-child{border-bottom:none;}
.my-account__dashboard .list-group-item:hover{background:#fffbf0;}
.my-account__dashboard .list-group-item:hover .fw-semibold{color:#9c7c2a;}
.page-title{font-weight:800;letter-spacing:.3px;margin-bottom:20px !important;}
</style>
<main class="pt-90">
  <section class="my-account container py-5">
    <h2 class="page-title mb-4">Akun Saya</h2>

    <div class="row">
      <!-- Sidebar Navigasi -->
      <div class="col-lg-3 mb-4">
        @include('user.account-nav')
      </div>

      <!-- Konten Utama -->
      <div class="col-lg-9">
        <div class="page-content my-account__dashboard">

          <!-- Sambutan -->
          <p class="fs-5">Halo, <strong>{{ Auth::user()->name }}</strong> 👋 Senang bertemu kembali!</p>
          <p class="mb-4">Di halaman ini, Anda dapat mengelola berbagai informasi akun Anda.</p>

          <!-- Daftar Aksi -->
          <div class="list-group mb-4">
            <a href="{{ route('account.password.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center">
              <i class="bi bi-shield-lock-fill me-3 text-primary"></i>
              <div>
                <div class="fw-semibold">Perbarui Kata Sandi & Informasi Akun</div>
                <small class="text-muted">Menjaga keamanan akun Anda tetap terjamin.</small>
              </div>
            </a>
            <a href="{{ route('home.contact') }}" class="list-group-item list-group-item-action d-flex align-items-center">
              <i class="bi bi-headset me-3 text-success"></i>
              <div>
                <div class="fw-semibold">Hubungi Tim Dukungan</div>
                <small class="text-muted">Kami siap membantu pertanyaan atau kendala Anda.</small>
              </div>
            </a>
          </div>

          <!-- Pesan Penutup -->
          <p class="text-muted">Butuh bantuan lebih lanjut? Tim kami siap membantu Anda kapan saja.</p>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
