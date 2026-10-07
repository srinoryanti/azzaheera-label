@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Akun</h1><p class="page-subtitle">Kelola data akun dan kata sandi administrator.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Akun</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Edit Akun</h2></div>
    <form class="form-spark" method="POST" action="{{ route('account.admin.password.update') }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Lengkap" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan Email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Handphone <span class="text-danger">*</span></label>
                <input type="text" name="mobile" class="form-control" placeholder="Masukkan Nomor HP" value="{{ old('mobile', auth()->user()->mobile) }}" required>
                @error('mobile')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <hr class="my-4">
        <h5 class="mb-3">Ganti Kata Sandi (Opsional)</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" class="form-control" placeholder="Masukkan Kata Sandi Lama">
                @error('current_password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kata Sandi Baru</label>
                <input type="password" name="new_password" class="form-control" placeholder="Masukkan Kata Sandi Baru">
                @error('new_password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi Kata Sandi Baru">
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Simpan Perubahan</button>
            <a href="{{ route('admin.index') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
