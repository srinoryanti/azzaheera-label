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

  /* Override CSS lama: label global uppercase (typography.css) + validasi hijau Bootstrap jadi emas */
  .login-card label, .login-card .form-check-label { text-transform: none; letter-spacing: normal; }
  .login-card .form-control.is-valid,
  .was-validated .login-card .form-control:valid {
    border-color: #9c7c2a;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%239c7c2a' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem);
  }
  .login-card .form-control.is-valid:focus,
  .was-validated .login-card .form-control:valid:focus { border-color: #D4AF37; background-color: #fff; box-shadow: 0 0 0 .2rem rgba(212,175,55,.18); }
  .login-card .form-control:-webkit-autofill { -webkit-box-shadow: 0 0 0 60px #FFFDF8 inset; -webkit-text-fill-color: #141414; caret-color: #141414; }

  .login-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin: 4px 0 18px; flex-wrap: wrap; }
  .login-card .form-check-input { border-color: #d8cfb8; cursor: pointer; }
  .login-card .form-check-input:checked { background-color: #D4AF37; border-color: #D4AF37; }
  .login-card .form-check-label { font-size: 13px; color: #4A4A4A; cursor: pointer; }
  .login-forgot { font-size: 13px; color: #9c7c2a; font-weight: 700; text-decoration: none; }
  .login-forgot:hover { text-decoration: underline; }

  .btn-login { height: 50px; border: none; border-radius: 999px; background: linear-gradient(180deg, #DFBA45, #C9A22E); color: #fff; font-weight: 700; font-size: 15px; letter-spacing: .3px; cursor: pointer; transition: .18s; box-shadow: 0 8px 18px rgba(212,175,55,.35); }
  .btn-login:hover, .btn-login:focus { background: #9c7c2a; color: #fff; box-shadow: 0 6px 14px rgba(156,124,42,.4); }

  .login-option { text-align: center; font-size: 14.5px; color: #4A4A4A; margin-top: 18px; }
  .login-option a { color: #9c7c2a; font-weight: 800; text-decoration: none; }
  .login-option a:hover { text-decoration: underline; }

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
        <h1 class="login-title">Login</h1>
        <p class="login-sub">Masuk untuk melanjutkan belanja di Azzahera Label</p>

        @if(session('success'))
            <div class="alert alert-success login-alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger login-alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
            @csrf

            {{-- Email --}}
            <div class="form-floating">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', session('email')) }}"
                    autocomplete="username"
                    placeholder="Email"
                    required
                    autofocus>

                <label for="email">Email</label>

                @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-floating">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    autocomplete="current-password"
                    placeholder="Kata Sandi"
                    required>

                <label for="password">Kata Sandi</label>

                @error('password')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            {{-- Ingat saya + lupa sandi --}}
            <div class="login-row">
                <div class="form-check m-0">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="login-forgot">Lupa Kata Sandi?</a>
                @endif
            </div>

            <button type="submit" class="btn-login w-100 text-uppercase">
                Login
            </button>

            <div class="login-option">
                <span>Belum Punya Akun?</span>
                <a href="{{ route('register') }}">Klik Register</a>
            </div>
        </form>
    </div>
</main>
@endsection
