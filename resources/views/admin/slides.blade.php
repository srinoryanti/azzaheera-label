@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Slide</h1>
        <p class="page-subtitle">Kelola slide banner beranda.</p>
    </div>
    <a href="{{ route('admin.slide.add') }}" class="btn-spark-primary"><i class="bi bi-plus-lg"></i>Tambah Slide</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Slide</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Slide</h2></div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Tagline</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($slides as $slide)
                    <tr>
                        <td>{{ $slide->id }}</td>
                        <td>
                            @if($slide->image)
                                <img
                                    src="{{ asset('storage/slides/'.$slide->image) }}"
                                    alt="{{ $slide->title }}"
                                    class="img-thumb">
                            @else
                                <img
                                    src="{{ asset('images/no-image.png') }}"
                                    alt="No Image"
                                    class="img-thumb">
                            @endif
                        </td>
                        <td>{{ $slide->tagline }}</td>
                        <td>{{ $slide->title }}</td>
                        <td>{{ $slide->subtitle }}</td>
                        <td>{{ $slide->link }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a class="btn-view" href="{{ route('admin.slide.edit',$slide->id) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.slide.delete',$slide->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn-delete delete"
                                        title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada data slide.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $slides->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection


@push('scripts')
<script>
$(function () {

    $('.delete').click(function (e) {

        e.preventDefault();

        let form = $(this).closest('form');

        swal({
            title: "Yakin ingin menghapus slide ini?",
            text: "Slide yang dihapus tidak dapat dikembalikan.",
            icon: "warning",
            buttons: ["Batal", "Hapus"],
            dangerMode: true,

        }).then((result) => {

            if(result){
                form.submit();
            }

        });

    });

});
</script>
@endpush
