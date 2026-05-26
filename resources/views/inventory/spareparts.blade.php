@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Data Sparepart</h1>
            <p class="text-white-50 mb-0">Daftar seluruh sparepart truk yang tersedia.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.spareparts.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Tambah Sparepart</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Table --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-boxes me-2"></i>Daftar Sparepart</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px">No</th>
                        <th>Kode</th>
                        <th>Nama Sparepart</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spareparts as $sparepart)
                        <tr>
                            <td class="ps-4 text-muted">{{ ($spareparts->currentPage() - 1) * $spareparts->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $sparepart->code }}</td>
                            <td>{{ $sparepart->name }}</td>
                            <td>{{ $sparepart->category->name }}</td>
                            <td>{{ $sparepart->supplier->name }}</td>
                            <td class="text-center">{{ $sparepart->stock }} {{ $sparepart->unit }}</td>
                            <td class="text-center">{{ $sparepart->min_stock }}</td>
                            <td class="text-center">
                                @if($sparepart->stock <= 0)
                                    <span class="badge-status habis"><i class="bi bi-x-circle me-1"></i>Habis</span>
                                @elseif($sparepart->stock <= $sparepart->min_stock)
                                    <span class="badge-status hampir-habis"><i class="bi bi-exclamation-triangle me-1"></i>Hampir Habis</span>
                                @else
                                    <span class="badge-status aman"><i class="bi bi-check-circle me-1"></i>Aman</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.spareparts.edit', $sparepart) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.spareparts.destroy', $sparepart) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus sparepart ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data sparepart.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modern-card-footer">
        <div class="d-flex justify-content-center">
            {{ $spareparts->links() }}
        </div>
    </div>
</div>
@endsection
