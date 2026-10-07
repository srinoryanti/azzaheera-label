@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Brand</h1><p class="page-subtitle">Perbarui data merek Azzahera Label.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.brands') }}">Brands</a> / Edit</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Edit Brand</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.brand.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $brand->id }}">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Brand <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Brand" value="{{ old('name', $brand->name) }}" required>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="Masukkan Slug Brand" value="{{ old('slug', $brand->slug) }}" required>
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Gambar Brand</label>
                <div class="mb-2" id="imgpreview" style="{{ $brand->image ? '' : 'display:none;' }}">
                    @if ($brand->image)
                        <img src="{{ asset('storage/brands/' . $brand->image) }}" alt="{{ $brand->name }}" id="previewImage" class="img-thumb">
                    @else
                        <img src="" id="previewImage" alt="" class="img-thumb">
                    @endif
                </div>
                <input type="file" id="myFile" name="image" class="form-control" accept="image/*">
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Update Brand</button>
            <a href="{{ route('admin.brands') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        $("#myFile").on("change", function(e) {
            const [file] = this.files;
            if (file) {
                $("#previewImage").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        $("input[name='name']").on("change", function() {
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
