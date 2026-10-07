@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Kupon</h1><p class="page-subtitle">Buat kupon diskon baru untuk pelanggan.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.coupons') }}">Coupons</a> / Tambah</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Tambah Kupon</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.coupon.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="COUPON2025" required>
                @error('code')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select" required><option value="fixed" {{ old('type')=='fixed'?'selected':'' }}>Fixed</option><option value="percent" {{ old('type')=='percent'?'selected':'' }}>Percent</option></select>
                @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Value <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="value" class="form-control" value="{{ old('value') }}" required>
                @error('value')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Cart Value <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="cart_value" class="form-control" value="{{ old('cart_value') }}" required>
                @error('cart_value')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                @error('expiry_date')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Simpan</button>
            <a href="{{ route('admin.coupons') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
