@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Pengajuan Pembelian</h1>
            <p class="text-white-50 mb-0">Daftar pengajuan pembelian sparepart dan status persetujuan.</p>
        </div>
        @if(session('auth_user')['role'] === 'admin')
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.pengajuan.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Buat Pengajuan</a>
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
        <h6 class="mb-0 fw-bold"><i class="bi bi-cart-plus me-2"></i>Daftar Pengajuan Pembelian</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 hide-sm" style="width:50px">No</th>
                        <th>Tanggal</th>
                        <th class="hide-md">No Pengajuan</th>
                        <th class="hide-sm">Diajukan Oleh</th>
                        <th>Status</th>
                        <th class="hide-md">Disetujui Oleh</th>
                        <th class="text-center hide-sm">Jumlah Item</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $i => $p)
                        <tr>
                            <td class="ps-4 text-muted hide-sm">{{ $i + 1 }}</td>
                            <td>{{ $p->date->format('d M Y') }}</td>
                            <td class="hide-md"><span class="fw-semibold">{{ $p->ajuan_no }}</span></td>
                            <td class="hide-sm">{{ $p->user->name }}</td>
                            <td>
                                @if($p->status === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($p->status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="hide-md">{{ $p->approver ? $p->approver->name : '-' }}</td>
                            <td class="text-center hide-sm">{{ $p->details->count() }} item</td>
                            <td class="text-center pe-4">
                                <a href="{{ route(session('auth_user')['role'] === 'pimpinan' ? 'pimpinan.pengajuan.show' : 'admin.pengajuan.show', $p) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(session('auth_user')['role'] === 'admin' && $p->status === 'pending')
                                    <form action="{{ route('admin.pengajuan.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengajuan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada pengajuan pembelian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
