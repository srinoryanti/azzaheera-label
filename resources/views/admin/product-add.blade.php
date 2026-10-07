@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Produk</h1>
        <p class="page-subtitle">Tambah produk baru ke katalog Azzahera Label.</p>
    </div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / <a href="{{ route('admin.products') }}">Produk</a> / Tambah</div>

<form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="form-spark">
    @csrf
    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title">Informasi Produk</h2></div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama produk" value="{{ old('name') }}" required>
                <div class="form-text">Jangan lebih dari 100 karakter saat memasukkan nama produk.</div>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="Masukkan slug produk" value="{{ old('slug') }}" required>
                <div class="form-text">Slug akan dibuat otomatis berdasarkan nama produk.</div>
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand <span class="text-danger">*</span></label>
                <select name="brand_id" class="form-select" required>
                    <option value="">Pilih Brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi Singkat Produk <span class="text-danger">*</span></label>
                <textarea name="short_description" class="form-control" rows="3" placeholder="Deskripsi Singkat Produk" required>{{ old('short_description') }}</textarea>
                <div class="form-text">Jangan lebih dari 255 karakter saat memasukkan deskripsi singkat produk.</div>
                @error('short_description')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi Lengkap Produk <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi Lengkap Produk" required>{{ old('description') }}</textarea>
                @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title">Foto & Varian</h2></div>

        <div class="mb-4">
            <label class="form-label">Upload Foto Produk <span class="text-danger">*</span></label>
            <div class="upload-image flex-grow">
                <div id="imgpreview" class="item" style="display: none;">
                    <img src="" alt="Preview Foto Produk" class="effect8" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    <button type="button" id="removeMainImage" class="btn-remove-image" title="Hapus gambar">&times;</button>
                </div>
                <div id="upload-file" class="item up-load">
                    <label for="myFile" class="uploadfile">
                        <span class="icon"><i class="bi bi-cloud-upload"></i></span>
                        <span class="body-text">Tarik gambar ke sini atau <span class="tf-color">klik untuk pilih</span></span>
                        <input type="file" id="myFile" name="image" accept="image/*" required>
                    </label>
                </div>
            </div>
            @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Upload Gallery Produk</label>
            <div id="galleryPreviewWrapper" class="upload-image mb-16">
                <div id="galUpload" class="item up-load">
                    <label for="gFile" class="uploadfile">
                        <span class="icon"><i class="bi bi-cloud-upload"></i></span>
                        <span class="text-tiny">Tarik gambar ke sini atau <span class="tf-color">klik untuk pilih</span></span>
                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                    </label>
                </div>
            </div>
            @error('images')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="form-label">Varian Warna Produk <span class="form-text">(opsional — kosongkan jika produk tidak punya varian warna)</span></label>
            <style>
                .variant-head, .variant-row {
                    display: grid;
                    grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr) 32px;
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
                    .variant-row { grid-template-columns: 1fr 1fr; }
                    .variant-remove { justify-self: end; }
                }
            </style>
            <div class="variant-head">
                <span>Nama warna</span>
                <span>Foto warna</span>
                <span></span>
            </div>
            <div id="colorRows" class="mb-10">
                <div class="color-row variant-row">
                    <input type="text" name="colors[0][name]" placeholder="cth: Hitam" maxlength="50">
                    <label class="variant-file" title="Pilih foto untuk warna ini">
                        <input type="file" name="colors[0][image]" accept="image/*" hidden>
                        <span class="variant-file-btn">Pilih foto</span>
                        <span class="variant-file-name">Belum ada foto</span>
                    </label>
                    <button type="button" class="variant-remove" title="Hapus baris">&times;</button>
                </div>
            </div>
            <button type="button" id="btnAddColor" class="btn-spark-outline">+ Tambah Warna</button>
            <div class="form-text mt-2">Setiap warna bisa punya foto sendiri. Di halaman produk, klik warna akan menampilkan foto warna tersebut.</div>
            @error('colors')<div class="text-danger small">{{ $message }}</div>@enderror
            @error('colors.*.name')<div class="text-danger small">{{ $message }}</div>@enderror
            @error('colors.*.image')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title">Harga & Stok</h2></div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Harga Produk <span class="text-danger">*</span></label>
                <input type="number" name="regular_price" class="form-control" placeholder="Masukkan Harga Produk" value="{{ old('regular_price') }}" min="0" step="1" required>
                @error('regular_price')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga Promo</label>
                <input type="number" name="sale_price" class="form-control" placeholder="Kosongkan jika tidak promo" value="{{ old('sale_price') }}" min="0" step="1">
                <div class="form-text">Isi hanya jika produk sedang promo. Harga promo harus lebih kecil dari harga normal.</div>
                @error('sale_price')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" name="SKU" class="form-control" placeholder="Masukkan SKU produk" value="{{ old('SKU') }}" required>
                @error('SKU')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Masukkan quantity" value="{{ old('quantity') }}" min="0" step="1" required>
                <div class="form-text">Jika quantity 0, status stok otomatis menjadi Stok Habis.</div>
                @error('quantity')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Ukuran (opsional)</label>
                <input type="text" name="sizes" class="form-control" placeholder="cth: S,M,L,XL" value="{{ old('sizes') }}" maxlength="255">
                <div class="form-text">Pisahkan dengan koma. Kosongkan jika produk tanpa varian ukuran.</div>
                @error('sizes')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Stok</label>
                <div class="form-control" id="stock_auto_badge" style="background:#f8f9fa;font-weight:600;">Tersedia</div>
                <div class="form-text">Otomatis mengikuti Quantity — Quantity &gt; 0 = Tersedia, 0 = Stok Habis.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Unggulan</label>
                <select name="featured" class="form-select" required>
                    <option value="0" {{ old('featured', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                    <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Ya</option>
                </select>
                @error('featured')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-spark-primary"><i class="bi bi-check-lg"></i>Tambah Produk</button>
            <a href="{{ route('admin.products') }}" class="btn-spark-outline">Batal</a>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script>
    $(function () {

        /* ==========================
           PREVIEW FOTO UTAMA
        ========================== */
        $("#myFile").on("change", function () {
            const file = this.files[0];

            if (file) {
                const imageUrl = URL.createObjectURL(file);

                $("#imgpreview img").attr("src", imageUrl);
                $("#imgpreview").show();
            } else {
                $("#imgpreview img").attr("src", "");
                $("#imgpreview").hide();
            }
        });
        $("#removeMainImage").on("click", function () {
            $("#imgpreview img").attr("src", "");
            $("#imgpreview").hide();
            $("#myFile").val("");
        });
        $("#gFile").on("change", function () {

            $("#galleryPreviewWrapper .gitems").remove();

            const files = this.files;

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {

                    const fileURL = URL.createObjectURL(files[i]);

                    const imgItem = $(
                        '<div class="item gitems gallery-preview-item"></div>'
                    );

                    const img = $("<img>")
                        .attr("src", fileURL)
                        .addClass("gallery-preview-image");

                    const removeBtn = $(
                        '<button type="button" class="gallery-remove-button">×</button>'
                    );
                    removeBtn.on("click", function () {
                        imgItem.remove();
                        $("#gFile").val("");
                    });

                    imgItem
                        .append(img)
                        .append(removeBtn);

                    $("#galleryPreviewWrapper").prepend(imgItem);
                }
            }
        });
        $("input[name='name']").on("input", function () {
            const value = $(this).val();

            $("input[name='slug']").val(StringToSlug(value));
        });

        /* Stok otomatis mengikuti Quantity — tampil sebagai badge info */
        function syncStockStatus() {
            const qty = parseInt($("input[name='quantity']").val(), 10) || 0;
            $("#stock_auto_badge").text(qty > 0 ? 'Tersedia' : 'Stok Habis');
        }
        $(document).on("input change", "input[name='quantity']", syncStockStatus);
        syncStockStatus();

        /* ==========================
           VARIAN WARNA DINAMIS
        ========================== */
        let colorIndex = 1;
        function variantRowHtml(index) {
            return '<div class="color-row variant-row">' +
                '<input type="text" name="colors[' + index + '][name]" placeholder="cth: Hitam" maxlength="50">' +
                '<label class="variant-file" title="Pilih foto untuk warna ini">' +
                    '<input type="file" name="colors[' + index + '][image]" accept="image/*" hidden>' +
                    '<span class="variant-file-btn">Pilih foto</span>' +
                    '<span class="variant-file-name">Belum ada foto</span>' +
                '</label>' +
                '<button type="button" class="variant-remove" title="Hapus baris">&times;</button>' +
            '</div>';
        }
        $("#btnAddColor").on("click", function () {
            $("#colorRows").append(variantRowHtml(colorIndex));
            colorIndex++;
        });
        $(document).on("click", ".variant-remove", function () {
            $(this).closest(".color-row").remove();
        });
        $(document).on("change", ".variant-file input[type=file]", function () {
            var name = (this.files && this.files.length) ? this.files[0].name : "Belum ada foto";
            $(this).closest(".variant-file").find(".variant-file-name").text(name);
        });
    });
    function StringToSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^\w\s-]/g, "")
            .trim()
            .replace(/\s+/g, "-");
    }
</script>
@endpush
