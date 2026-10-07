@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Slide</h1><p class="page-subtitle">Tambahkan slide baru untuk banner utama toko.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.slides') }}">Slides</a> / Tambah</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Tambah Slide</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.slide.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tagline <span class="text-danger">*</span></label>
                <input type="text" name="tagline" class="form-control" placeholder="cth: Elegance in Simplicity" tabindex="0" value="{{ old('tagline') }}" aria-required="true" required="">
                <div class="form-text">Teks kecil di atas judul (huruf kapital otomatis).</div>
                @error('tagline')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="cth: Azzahera Signature Collection" tabindex="0" value="{{ old('title') }}" aria-required="true" required="">
                <div class="form-text">Judul besar baris 1 (tampil reguler/tipis).</div>
                @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtitle <span class="text-danger">*</span></label>
                <input type="text" name="subtitle" class="form-control" placeholder="cth: Pesona Elegan untuk Setiap Kesempatan" tabindex="0" value="{{ old('subtitle') }}" aria-required="true" required="">
                <div class="form-text">Judul besar baris 2 (tampil tebal). Maksimal ±6 kata agar rapi.</div>
                @error('subtitle')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Link <span class="form-text">(opsional)</span></label>
                <input type="text" name="link" class="form-control" placeholder="cth: /shop — kosongkan untuk tombol default" tabindex="0" value="{{ old('link') }}">
                <div class="form-text">Tujuan tombol banner. Kosongkan untuk tombol default "Lihat Koleksi".</div>
                @error('link')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Upload images <span class="text-danger">*</span></label>
                <div class="mb-2" id="imgpreview" style="display:none;">
                    <img src="" alt="" class="img-thumb" />
                </div>
                <input type="file" id="myFile" name="image" class="form-control">
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Select</option>
                    <option value="1" @if(old('status') == "1") selected @endif>Active</option>
                    <option value="0" @if(old('status') == "0") selected @endif>Inactive</option>
                </select>
                @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Simpan</button>
            <a href="{{ route('admin.slides') }}" class="btn-spark-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(function(){
        $("#myFile").on("change", function(e){
            const photoInp = $("#myFile");
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr('src',URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });
    });
</script>
@endpush
