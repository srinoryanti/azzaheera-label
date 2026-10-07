@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Slide</h1><p class="page-subtitle">Perbarui slide banner utama toko.</p></div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.slides') }}">Slides</a> / Edit</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Form Edit Slide</h2></div>
    <form class="form-spark" method="POST" action="{{ route('admin.slide.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $slide->id }}"/>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tagline <span class="text-danger">*</span></label>
                <input type="text" name="tagline" class="form-control" placeholder="Tagline" tabindex="0" value="{{ $slide->tagline }}" aria-required="true" required="">
                @error('tagline')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="Title" tabindex="0" value="{{ $slide->title }}" aria-required="true" required="">
                @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtitle <span class="text-danger">*</span></label>
                <input type="text" name="subtitle" class="form-control" placeholder="Subtitle" tabindex="0" value="{{ $slide->subtitle }}" aria-required="true" required="">
                @error('subtitle')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Link <span class="form-text">(opsional)</span></label>
                <input type="text" name="link" class="form-control" placeholder="cth: /shop — kosongkan untuk tombol default" tabindex="0" value="{{ $slide->link == '-' ? '' : $slide->link }}">
                <div class="form-text">Tujuan tombol banner. Kosongkan untuk tombol default "Lihat Koleksi".</div>
                @error('link')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Upload images <span class="text-danger">*</span></label>
                @if ($slide->image)
                <div class="mb-2" id="imgpreview">
                    @if($slide->image && file_exists(public_path('storage/slides/' . $slide->image)))
                        <img src="{{ asset('storage/slides/' . $slide->image) }}" alt="Slide Image" class="img-thumb"/>
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="No image" class="img-thumb"/>
                    @endif
                </div>
                @endif
                <input type="file" id="myFile" name="image" class="form-control">
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Select</option>
                    <option value="1" @if($slide->status == "1") selected @endif>Active</option>
                    <option value="0" @if($slide->status == "0") selected @endif>Inactive</option>
                </select>
                <div class="form-text">Note: Ubah menjadi <strong class="text-danger">Active</strong> untuk menjadikan tampilan awal</div>
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
