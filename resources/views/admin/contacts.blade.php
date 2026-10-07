@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pesan Kontak</h1>
        <p class="page-subtitle">Daftar pesan dari pelanggan.</p>
    </div>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Pesan Kontak</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Pesan</h2></div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->comment }}</td>
                        <td>{{ $contact->created_at }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('admin.contact.delete', ['id' => $contact->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $contacts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {

                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this message?",
                    type: "warning",
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#dc3545'
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                })
            });
        });
    </script>
@endpush
