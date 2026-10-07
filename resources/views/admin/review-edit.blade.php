@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Ulasan</h1><p class="page-subtitle">Perbarui ulasan produk pelanggan.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.reviews') }}">Ulasan</a> / Edit</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Edit Ulasan</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.review.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $review->id }}">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Produk <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id', $review->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Pemberi Ulasan <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Ratna Ayu" value="{{ old('name', $review->name) }}" required>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="Contoh: ratna@email.com" value="{{ old('email', $review->email) }}" required>
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Rating (1 - 5) <span class="text-danger">*</span></label>
                <select name="rating" class="form-select" required>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}"
                            {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>
                            {{ $i }} - {{ str_repeat('★', $i) }}
                        </option>
                    @endfor
                </select>
                @error('rating')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Isi Ulasan <span class="text-danger">*</span></label>
                <textarea name="review" rows="4" class="form-control" placeholder="Tulis ulasan produk di sini..." required>{{ old('review', $review->review) }}</textarea>
                @error('review')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Perbarui</button>
            <a href="{{ route('admin.reviews') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
