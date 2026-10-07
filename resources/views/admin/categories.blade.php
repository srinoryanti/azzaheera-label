@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kategori</h1>
        <p class="page-subtitle">Kelola kategori produk.</p>
    </div>
    <a href="{{ route('admin.category.add') }}" class="btn-spark-primary"><i class="bi bi-plus-lg"></i>Tambah Kategori</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Kategori</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Kategori</h2></div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <img src="{{ asset('storage/categories/' . $category->image) }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';"
                                alt="{{ $category->name }}" class="img-thumb">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a class="btn-view" href="{{ route('admin.category.edit', ['id' => $category->id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form
                                    action="{{ route('admin.category.delete', ['id' => $category->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada kategori ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                swal({
                    title: "Apakah Anda yakin?",
                    text: "Kategori akan dihapus secara permanen!",
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
