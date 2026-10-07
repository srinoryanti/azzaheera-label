@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Produk</h1>
        <p class="page-subtitle">Perbarui data produk {{ $product->name }}.</p>
    </div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.products') }}">Produk</a> / Edit</div>

<form class="form-spark" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.update') }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $product->id }}" />

    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title">Informasi Produk</h2></div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input class="form-control" type="text" placeholder="Masukkan Nama Produk" name="name" value="{{ $product->name }}" required>
                <div class="form-text">Jangan lebih dari 100 karakter saat memasukkan nama produk.</div>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Slug <span class="text-danger">*</span></label>
                <input class="form-control" type="text" placeholder="Masukkan slug/judul produk" name="slug" value="{{ $product->slug }}" required>
                <div class="form-text">Jangan lebih dari 100 karakter saat memasukkan slug produk.</div>
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select">
                    <option>Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? "selected" : "" }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand <span class="text-danger">*</span></label>
                <select name="brand_id" class="form-select">
                    <option>Pilih Brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? "selected" : "" }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi Singkat Produk <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="3" name="short_description" required>{{ $product->short_description }}</textarea>
                <div class="form-text">Jangan lebih dari 100 karakter saat memasukkan deskripsi singkat produk.</div>
                @error('short_description')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi Lengkap Produk <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="4" name="description" required>{{ $product->description }}</textarea>
                @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title">Foto & Varian</h2></div>

        <div class="mb-4">
            <label class="form-label">Upload Foto Produk <span class="text-danger">*</span></label>
            <div class="upload-image flex-grow">
                @if($product->image)
                <div class="item" id="imgpreview">
                    <img src="{{ Storage::url('products/' . $product->image) }}" class="effect8" alt="{{ $product->name }}">
                </div>
                @endif
                <div id="upload-file" class="item up-load">
                    <label class="uploadfile" for="myFile">
                        <span class="icon"><i class="bi bi-cloud-upload"></i></span>
                        <span class="body-text">Tarik gambar ke sini atau <span class="tf-color">klik untuk pilih</span></span>
                        <input type="file" id="myFile" name="image" accept="image/*">
                    </label>
                </div>
            </div>
            @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Upload Gallery Produk</label>
            <div class="form-text mb-2">Foto baru akan <strong>ditambahkan</strong> ke galeri (foto lama tidak hilang). Bisa pilih beberapa file sekaligus.</div>
            <div class="upload-image mb-16">
                @if($product->images)
                    @foreach(explode(',', $product->images) as $img)
                        <div class="item gitems" style="position: relative;">
                            <img src="{{ Storage::url('products/' . trim($img)) }}" alt="">
                            <button type="button" class="btn-remove-gallery" data-filename="{{ trim($img) }}" title="Hapus foto ini">&times;</button>
                        </div>
                    @endforeach
                @endif
                <div id="galUpload" class="item up-load">
                    <label class="uploadfile" for="gFile">
                        <span class="icon"><i class="bi bi-cloud-upload"></i></span>
                        <span class="text-tiny">Tarik gambar ke sini atau <span class="tf-color">klik untuk pilih</span></span>
                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                    </label>
                </div>
            </div>
            <style>
                .btn-remove-gallery {
                    position: absolute;
                    top: 4px;
                    right: 4px;
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    border: none;
                    background: rgba(214, 0, 28, 0.9);
                    color: #fff;
                    font-size: 16px;
                    line-height: 1;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .btn-remove-gallery:hover {
                    background: #D6001C;
                }
                #galUpload.dragover {
                    border: 2px dashed #D4AF37 !important;
                    border-radius: 12px;
                }
            </style>
            @error('images')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="form-label">Varian Warna Produk <span class="form-text">(opsional)</span></label>
            <div class="form-text mb-2">Setiap warna bisa punya foto sendiri. Klik <strong>&times;</strong> untuk menghapus warna.</div>
            <style>
                .variant-head, .variant-row {
                    display: grid;
                    grid-template-columns: 44px minmax(0, 1fr) minmax(0, 1.4fr) 32px;
                    gap: 8px;
                    align-items: center;
                }
                .variant-head {
                    font-size: 11px;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: .4px;
                    color: #888;
                    margin-bottom: 6px;
                    padding: 0 2px;
                }
                .variant-row {
                    background: #fafafa;
                    border: 1px solid #ececec;
                    border-radius: 10px;
                    padding: 8px 10px;
                    margin-bottom: 10px;
                    max-width: 100%;
                }
                .variant-row input[type="text"] {
                    width: 100%;
                    min-width: 0;
                    height: 40px;
                    padding: 0 10px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background: #fff;
                    font-size: 13px;
                }
                .variant-row input[type="text"]:focus {
                    outline: none;
                    border-color: #D4AF37;
                }
                .variant-thumb {
                    width: 44px;
                    height: 44px;
                    object-fit: cover;
                    border-radius: 8px;
                    border: 1px solid #e5e5e5;
                }
                .variant-thumb--empty {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #eee;
                    color: #aaa;
                    font-size: 18px;
                }
                .variant-file {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    min-width: 0;
                    margin: 0;
                    cursor: pointer;
                }
                .variant-file-btn {
                    flex: 0 0 auto;
                    height: 40px;
                    line-height: 40px;
                    padding: 0 10px;
                    border-radius: 8px;
                    background: #fff;
                    border: 1px dashed #bbb;
                    font-size: 12px;
                    font-weight: 600;
                    color: #555;
                    white-space: nowrap;
                }
                .variant-file:hover .variant-file-btn {
                    border-color: #D4AF37;
                    color: #000;
                }
                .variant-file-name {
                    font-size: 12px;
                    color: #888;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .variant-remove {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    border: none;
                    background: #dc3545;
                    color: #fff;
                    font-size: 16px;
                    line-height: 1;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0 0 2px;
                    justify-self: end;
                }
                .variant-remove:hover {
                    background: #b02a37;
                }
                @media (max-width: 640px) {
                    .variant-head { display: none; }
                    .variant-row { grid-template-columns: 44px 1fr 1fr; }
                    .variant-file { grid-column: 1 / -1; }
                    .variant-remove { justify-self: end; }
                }
            </style>
            <div class="variant-head">
                <span>Foto</span>
                <span>Nama warna</span>
                <span>Ganti foto</span>
                <span></span>
            </div>
            <div id="colorRows" class="mb-10">
                @foreach($product->colors as $i => $pc)
                    <div class="color-row variant-row">
                        <input type="hidden" name="colors[{{ $i }}][id]" value="{{ $pc->id }}">
                        <input type="checkbox" name="delete_colors[]" value="{{ $pc->id }}" hidden>
                        @if($pc->image)
                            <img src="{{ Storage::url('products/' . $pc->image) }}" alt="{{ $pc->color }}" class="variant-thumb">
                        @else
                            <span class="variant-thumb variant-thumb--empty">&ndash;</span>
                        @endif
                        <input type="text" name="colors[{{ $i }}][name]" value="{{ $pc->color }}" maxlength="50" placeholder="cth: Hitam">
                        <label class="variant-file" title="Ganti foto untuk warna ini">
                            <input type="file" name="colors[{{ $i }}][image]" accept="image/*" hidden>
                            <span class="variant-file-btn">Pilih foto</span>
                            <span class="variant-file-name">{{ $pc->image ? $pc->image : 'Belum ada foto' }}</span>
                        </label>
                        <button type="button" class="variant-remove" title="Hapus warna ini">&times;</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="btnAddColor" class="btn-spark-outline">+ Tambah Warna</button>
            @error('colors')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title">Harga & Stok</h2></div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Harga Produk <span class="text-danger">*</span></label>
                <input class="form-control" type="number" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" placeholder="Masukkan harga produk" min="0" required>
                @error('regular_price')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga Promo</label>
                <input class="form-control" type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" placeholder="Kosongkan jika tidak promo" min="0" step="1">
                <div class="form-text">Isi hanya jika produk sedang promo. Harga promo harus lebih kecil dari harga normal.</div>
                @error('sale_price')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">SKU <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="SKU" value="{{ old('SKU', $product->SKU) }}" required>
                @error('SKU')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input class="form-control" type="number" min="0" step="1" name="quantity" value="{{ $product->quantity }}" required>
                <div class="form-text">Jika quantity 0, status stok otomatis menjadi Stok Habis.</div>
                @error('quantity')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Ukuran (opsional)</label>
                <input class="form-control" type="text" name="sizes" value="{{ old('sizes', $product->sizes) }}" placeholder="cth: S,M,L,XL" maxlength="255">
                <div class="form-text">Pisahkan dengan koma. Kosongkan jika produk tanpa varian ukuran.</div>
                @error('sizes')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Stok</label>
                <div class="form-control" id="stock_auto_badge" style="background:#f8f9fa;font-weight:600;">{{ $product->stock_status == 'instock' ? 'Tersedia' : 'Stok Habis' }}</div>
                <div class="form-text">Otomatis mengikuti Quantity — Quantity &gt; 0 = Tersedia, 0 = Stok Habis.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Unggulan</label>
                <select name="featured" class="form-select">
                    <option value="0" {{ $product->featured == "0" ? "selected" : "" }}>No</option>
                    <option value="1" {{ $product->featured == "1" ? "selected" : "" }}>Yes</option>
                </select>
                @error('featured')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn-spark-primary" type="submit"><i class="bi bi-check-lg"></i>Update Product</button>
            <a href="{{ route('admin.products') }}" class="btn-spark-outline">Batal</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(function(){
        $("#myFile").on("change", function(){
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        $("#gFile").on("change", function(){
            const gPhotos = this.files;
            $.each(gPhotos, function(key, val){
                $("#galUpload").prepend('<div class="item gitems"><img src="' + URL.createObjectURL(val) + '"/></div>');
            });
        });

        // Drag & drop ke kotak gallery (teksnya memang menjanjikan drop)
        var galUpload = $("#galUpload");
        galUpload.on("dragover dragenter", function(e){
            e.preventDefault();
            $(this).addClass("dragover");
        });
        galUpload.on("dragleave drop", function(e){
            e.preventDefault();
            $(this).removeClass("dragover");
        });
        galUpload.on("drop", function(e){
            e.preventDefault();
            var files = e.originalEvent.dataTransfer.files;
            if (files && files.length) {
                $("#gFile")[0].files = files;
                $("#gFile").trigger("change");
            }
        });

        // Hapus satu foto gallery tanpa reload
        $(document).on("click", ".btn-remove-gallery", function(){
            var btn = $(this);
            if (!confirm("Hapus foto ini dari galeri?")) return;
            $.ajax({
                url: "{{ route('admin.product.gallery.delete', ['id' => $product->id]) }}",
                type: "DELETE",
                data: {
                    filename: btn.data("filename"),
                    _token: "{{ csrf_token() }}"
                },
                success: function(){
                    btn.closest(".gitems").remove();
                },
                error: function(){
                    alert("Gagal menghapus foto. Silakan coba lagi.");
                }
            });
        });

        $("input[name='name']").on("change", function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });

        /* Stok otomatis mengikuti Quantity — tampil sebagai badge info */
        function syncStockStatus() {
            const qty = parseInt($("input[name='quantity']").val(), 10) || 0;
            $("#stock_auto_badge").text(qty > 0 ? 'Tersedia' : 'Stok Habis');
        }
        $(document).on("input change", "input[name='quantity']", syncStockStatus);
        syncStockStatus();

        /* Varian warna dinamis (baris baru tanpa id = data baru) */
        let newColorIndex = 0;
        $("#btnAddColor").on("click", function(){
            const key = "new" + (newColorIndex++);
            $("#colorRows").append(
                '<div class="color-row variant-row">' +
                    '<span class="variant-thumb variant-thumb--empty">&ndash;</span>' +
                    '<input type="text" name="colors[' + key + '][name]" placeholder="cth: Hitam" maxlength="50">' +
                    '<label class="variant-file" title="Pilih foto untuk warna ini">' +
                        '<input type="file" name="colors[' + key + '][image]" accept="image/*" hidden>' +
                        '<span class="variant-file-btn">Pilih foto</span>' +
                        '<span class="variant-file-name">Belum ada foto</span>' +
                    '</label>' +
                    '<button type="button" class="variant-remove" title="Hapus baris">&times;</button>' +
                '</div>'
            );
        });
        $(document).on("click", ".variant-remove", function(){
            var row = $(this).closest(".color-row");
            var del = row.find("input[name='delete_colors[]']");
            if (del.length) {
                del.prop("checked", true);
                row.slideUp(150);
            } else {
                row.remove();
            }
        });
        $(document).on("change", ".variant-file input[type=file]", function(){
            var name = (this.files && this.files.length) ? this.files[0].name : "Belum ada foto";
            $(this).closest(".variant-file").find(".variant-file-name").text(name);
        });
    });

    function StringToSlug(Text){
        return Text.toLowerCase()
            .replace(/[^\w ]+/g,"")
            .replace(/ +/g,"-");
    }
</script>
@endpush
