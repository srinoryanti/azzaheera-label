@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Kategori</h1><p class="page-subtitle">Tambahkan kategori produk baru.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.categories') }}">Categories</a> / Tambah</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Tambah Kategori</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Kategori" value="{{ old('name') }}" required>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug Kategori <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="Masukkan Slug Kategori" value="{{ old('slug') }}" required>
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Upload Gambar <span class="text-danger">*</span></label>
                <div class="mb-2" id="imgpreview" style="display:none">
                    <img src="#" alt="Preview" class="img-thumb" width="120">
                </div>
                <input type="file" id="myFile" name="image" class="form-control" accept="image/*">
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Simpan</button>
            <a href="{{ route('admin.categories') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        $("#myFile").on("change", function() {
            const [file] = this.files;
            if (file) {
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        $("input[name='name']").on("input", function() {
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(text) {
        return text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
</script>
@endpush
