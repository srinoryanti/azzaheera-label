@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ulasan</h1>
        <p class="page-subtitle">Kelola ulasan produk pelanggan.</p>
    </div>
    <a href="{{ route('admin.review.add') }}" class="btn-spark-primary"><i class="bi bi-plus-lg"></i>Tambah Ulasan</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Ulasan</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Ulasan</h2></div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Nama</th>
                    <th>Rating</th>
                    <th>Ulasan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->product->name ?? '-' }}</td>
                        <td>{{ $review->name }}</td>
                        <td>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($review->review, 80) }}</td>
                        <td>{{ $review->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a class="btn-view" href="{{ route('admin.review.edit', ['id' => $review->id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.review.delete', ['id' => $review->id]) }}"
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
                        <td colspan="7" class="text-center">Belum ada data ulasan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                    title: "Yakin hapus ulasan ini?",
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
