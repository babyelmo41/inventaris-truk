@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Stock Opname</h1>
            <p class="text-white-50 mb-0">Verifikasi stok fisik sparepart (cycle counting bertahap).</p>
        </div>
        @if(session('auth_user')['role'] === 'admin')
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.stock-opname.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Buat Stock Opname</a>
        </div>
        @endif
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
        <h6 class="mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i>Daftar Stock Opname</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px">No</th>
                        <th>Tanggal</th>
                        <th>No Opname</th>
                        <th>Bulan/Siklus</th>
                        <th>Kelompok</th>
                        <th>Dihitung Oleh</th>
                        <th>Status</th>
                        <th class="text-center">Item</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockOpnames as $i => $op)
                        <tr>
                            <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                            <td>{{ $op->date->format('d M Y') }}</td>
                            <td><span class="fw-semibold">{{ $op->opname_no }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($op->cycle_month)->format('M Y') }}</td>
                            <td><span class="badge bg-secondary">Kelompok {{ $op->cycle_group }}</span></td>
                            <td>{{ $op->user->name }}</td>
                            <td>
                                @if($op->status === 'draft')<span class="badge bg-secondary">Draft</span>
                                @elseif($op->status === 'submitted')<span class="badge bg-info text-dark">Disubmit</span>
                                @elseif($op->status === 'approved')<span class="badge bg-success">Disetujui</span>
                                @else<span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $op->details->count() }} item</td>
                            <td class="text-center pe-4">
                                <a href="{{ route(session('auth_user')['role'] === 'pimpinan' ? 'pimpinan.stock-opname.show' : 'admin.stock-opname.show', $op) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                @if(session('auth_user')['role'] === 'admin' && ($op->status === 'draft' || $op->status === 'rejected'))
                                    <form action="{{ route('admin.stock-opname.destroy', $op) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus stock opname ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data stock opname.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
