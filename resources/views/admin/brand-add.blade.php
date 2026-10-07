@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Brand</h1><p class="page-subtitle">Tambahkan merek baru ke katalog Azzahera Label.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.brands') }}">Brands</a> / Tambah</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Tambah Brand</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.brand.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Brand <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Brand" value="{{ old('name') }}" required>
                <div class="form-text">Contoh: Azzahera Label</div>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="Masukkan Slug Brand" value="{{ old('slug') }}" required>
                <div class="form-text">Contoh: azzahera-label</div>
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Upload Gambar <span class="text-danger">*</span></label>
                <div class="mb-2" id="imgpreview" style="display:none">
                    <img src="" alt="" id="previewImage" class="img-thumb" style="max-height:150px;">
                </div>
                <input type="file" id="myFile" name="image" class="form-control" accept="image/*">
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Simpan</button>
            <a href="{{ route('admin.brands') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        // Preview gambar sebelum upload
        $("#myFile").on("change", function() {
            const [file] = this.files;
            if (file) {
                $("#previewImage").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        // Otomatis buat slug dari nama
        $("input[name='name']").on("input", function() {
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(Text) {
        return Text.toLowerCase()
            .replace(/[^\w ]+/g, "")
            .replace(/ +/g, "-");
    }
</script>
@endpush
