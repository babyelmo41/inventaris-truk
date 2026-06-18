@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Data Supplier</h1>
            <p class="text-white-50 mb-0">Kelola data supplier sparepart.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Tambah Supplier</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-truck me-2"></i>Daftar Supplier</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 hide-sm" style="width:50px">No</th>
                        <th>Nama Supplier</th>
                        <th class="hide-md">Alamat</th>
                        <th>Telepon</th>
                        <th class="hide-sm">Email</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="ps-4 text-muted hide-sm">{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $supplier->name }}</td>
                            <td class="hide-md">{{ $supplier->address ?? '-' }}</td>
                            <td>{{ $supplier->phone ?? '-' }}</td>
                            <td class="hide-sm">{{ $supplier->email ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modern-card-footer">
        <div class="d-flex justify-content-center">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
