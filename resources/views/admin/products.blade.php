@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Produk</h1>
        <p class="page-subtitle">Kelola daftar produk toko.</p>
    </div>
    <a href="{{ route('admin.product.add') }}" class="btn-spark-primary"><i class="bi bi-plus-lg"></i>Tambah Produk</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Produk</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Produk</h2></div>
    <div class="table-responsive">
        <table class="table-spark table-wide">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Harga Jual</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Brand</th>
                    <th>Dipilih</th>
                    <th>Stok</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td>
                            <div class="cell-main">
                                @if($product->image)
                                    <img src="{{ Storage::url('products/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="img-thumb" onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="No image" loading="lazy" class="img-thumb">
                                @endif
                                <div class="cell-text">
                                    <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}" class="cell-title" title="{{ $product->name }}">{{ $product->name }}</a>
                                    <div class="cell-sub" title="{{ $product->slug }}">{{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="cell-money">Rp{{ number_format($product->regular_price, 0, ',', '.') }}</td>
                        <td class="cell-money">{{ $product->sale_price ? 'Rp'.number_format($product->sale_price, 0, ',', '.') : '-' }}</td>
                        <td>{{ $product->SKU }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ $product->featured ? 'Yes' : 'No' }}</td>
                        <td>
                            @if($product->stock_status == 'instock')
                                <span class="status-badge status-delivered">{{ $product->stock_status }}</span>
                            @else
                                <span class="status-badge status-canceled">{{ $product->stock_status }}</span>
                            @endif
                        </td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn-view" href="{{ route('admin.product.edit', ['id' => $product->id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.product.delete', ['id' => $product->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        $('.delete').on('click', function(e){
            e.preventDefault();
            let form = $(this).closest('form');

            swal({
                title: "Yakin ingin menghapus produk ini?",
                text: "Produk yang dihapus tidak dapat dikembalikan.",
                icon: "warning",
                buttons: ["Batal", "Hapus"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
