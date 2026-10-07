@extends('layouts.app')

@section('content')
<style>
  .login-page { background: #faf7f0; min-height: calc(100vh - 90px); padding: 130px 20px 70px; }

  .login-card { position: relative; max-width: 430px; margin: 56px auto 0; background: #fff; border: 1px solid #EAEAEA; border-radius: 18px; padding: 34px 30px 30px; box-shadow: 0 10px 30px rgba(16,35,27,.06); overflow: hidden; }
  .login-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #D4AF37, #F0D98C); }

  .login-logo { width: 80px; height: 80px; margin: 4px auto 16px; border-radius: 50%; overflow: hidden; border: 2px solid #D4AF37; box-shadow: 0 6px 18px rgba(212,175,55,.35); background: #fff; }
  .login-logo img { width: 100%; height: 100%; object-fit: cover; display: block; }

  .login-title { font-size: 22px; font-weight: 800; color: #141414; margin: 0 0 6px; text-align: center; }
  .login-sub { font-size: 13.5px; color: #8a8378; text-align: center; margin: 0 0 24px; }

  .login-alert { border-radius: 12px; font-size: 13.5px; border-width: 1px; }

  .login-card .form-floating { margin-bottom: 14px; }
  .login-card .form-control { border: 1.5px solid #e7dfcb; border-radius: 12px; background: #FFFDF8; font-size: 14px; height: 54px; }
  .login-card .form-control:focus { border-color: #D4AF37; background: #fff; box-shadow: 0 0 0 .2rem rgba(212,175,55,.18); }
  .login-card .form-control:focus + label { color: #9c7c2a; }
  .login-card .form-control.is-invalid { border-color: #dc3545; }

  .login-divider { border: none; border-top: 1px solid #efe9dc; margin: 20px 0 16px; }
  .login-section-label { font-size: 13px; font-weight: 800; color: #141414; text-transform: uppercase; letter-spacing: .5px; margin: 0 0 14px; }

  .btn-login { height: 50px; border: none; border-radius: 999px; background: linear-gradient(180deg, #DFBA45, #C9A22E); color: #fff; font-weight: 700; font-size: 15px; letter-spacing: .3px; cursor: pointer; transition: .18s; box-shadow: 0 8px 18px rgba(212,175,55,.35); }
  .btn-login:hover, .btn-login:focus { background: #9c7c2a; color: #fff; box-shadow: 0 6px 14px rgba(156,124,42,.4); }

  @media (max-width: 480px) {
    .login-page { padding: 115px 14px 50px; }
    .login-card { margin-top: 36px; padding: 28px 18px 24px; }
    .login-title { font-size: 20px; }
    .login-logo { width: 68px; height: 68px; }
  }
</style>

<main class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('assets/images/logowebsite.jpeg') }}" alt="Azzahera Label">
        </div>
        <h1 class="login-title">Edit Akun</h1>
        <p class="login-sub">Perbarui data profil dan kata sandi Anda</p>

        @if (session('status'))
            <div class="alert alert-success login-alert">{{ session('status') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger login-alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('account.password.update') }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="form-floating">
                <input type="text" name="name" id="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', auth()->user()->name) }}" required placeholder="Nama Lengkap">
                <label for="name">Nama Lengkap</label>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-floating">
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', auth()->user()->email) }}" required placeholder="Email">
                <label for="email">Email</label>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-floating">
                <input type="text" name="mobile" id="mobile"
                    class="form-control @error('mobile') is-invalid @enderror"
                    value="{{ old('mobile', auth()->user()->mobile) }}" required placeholder="Nomor HP">
                <label for="mobile">Nomor Handphone</label>
                @error('mobile')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <hr class="login-divider">

            <p class="login-section-label">Ganti Kata Sandi (opsional)</p>

            <div class="form-floating">
                <input type="password" name="current_password" id="current_password"
                    class="form-control @error('current_password') is-invalid @enderror"
                    placeholder="Kata Sandi Saat Ini">
                <label for="current_password">Kata Sandi Saat Ini</label>
                @error('current_password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-floating">
                <input type="password" name="new_password" id="new_password"
                    class="form-control @error('new_password') is-invalid @enderror"
                    placeholder="Kata Sandi Baru">
                <label for="new_password">Kata Sandi Baru</label>
                @error('new_password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-floating">
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="form-control"
                    placeholder="Konfirmasi Kata Sandi Baru">
                <label for="new_password_confirmation">Konfirmasi Kata Sandi Baru</label>
            </div>

            <button class="btn-login w-100 text-uppercase mt-2" type="submit">
                Simpan Perubahan
            </button>
        </form>
    </div>
</main>
@endsection
