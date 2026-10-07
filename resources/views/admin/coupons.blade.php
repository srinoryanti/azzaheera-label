@extends('layouts.admin')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kupon</h1>
        <p class="page-subtitle">Kelola kupon diskon toko.</p>
    </div>
    <a href="{{ route('admin.coupon.add') }}" class="btn-spark-primary"><i class="bi bi-plus-lg"></i>Tambah Kupon</a>
</div>
<div class="breadcrumb-spark"><a href="{{ route('admin.index') }}">Dashboard</a> / Kupon</div>
<div class="card">
    <div class="card-header"><h2 class="card-title">Daftar Kupon</h2></div>
    <div class="table-responsive">
        <table class="table-spark">
            <thead><tr><th>No</th><th>Code</th><th>Type</th><th>Value</th><th>Cart Value</th><th>Expiry Date</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($coupons as $coupon)
            <tr>
                <td>{{ $loop->iteration + ($coupons->currentPage()-1)*$coupons->perPage() }}</td>
                <td>{{ $coupon->code }}</td>
                <td>{{ $coupon->type }}</td>
                <td>{{ $coupon->value }}</td>
                <td>Rp{{ number_format($coupon->cart_value,0,',','.') }}</td>
                <td>{{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d M Y') }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <a class="btn-view" href="{{ route('admin.coupon.edit',['id'=>$coupon->id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.coupon.delete',['id'=>$coupon->id]) }}" method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty<tr><td colspan="7" class="text-center">Belum ada coupon</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $coupons->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        let form = this;
        swal({
            title: "Yakin hapus kupon ini?",
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
