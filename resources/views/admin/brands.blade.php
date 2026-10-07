@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Merek</h1>
        <p class="page-subtitle">Kelola daftar merek produk.</p>
    </div>
    <a href="{{ route('admin.brand.add') }}" class="btn-spark-primary"><i class="bi bi-plus-lg"></i>Tambah Merek</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Merek</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Merek</h2></div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Brand</th>
                    <th>Slug</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->slug }}</td>
                        <td>
                            @if ($brand->image && file_exists(public_path('storage/brands/' . $brand->image)))
                                <img src="{{ asset('storage/brands/' . $brand->image) }}"
                                    alt="{{ $brand->name }}" class="img-thumb">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No image"
                                    class="img-thumb">
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a class="btn-view" href="{{ route('admin.brand.edit', ['id' => $brand->id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.brand.delete', ['id' => $brand->id]) }}"
                                    method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data brand</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $brands->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                let form = this;

                swal({
                    title: "Yakin hapus brand ini?",
                    text: "Data tidak bisa dikembalikan setelah dihapus.",
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
