@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Riwayat Permintaan</h1>
            <p class="text-white-50 mb-0">Daftar permintaan sparepart yang pernah Anda ajukan.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('karyawan.permintaan.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Buat Permintaan Baru</a>
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
        <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Daftar Permintaan</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 hide-sm" style="width:50px">No</th>
                        <th>Tanggal</th>
                        <th class="hide-md">No Permintaan</th>
                        <th class="hide-sm">Keperluan</th>
                        <th class="hide-md">Truk</th>
                        <th class="text-center hide-sm">Jumlah Item</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $i => $p)
                        <tr>
                            <td class="ps-4 text-muted hide-sm">{{ ($permintaan->currentPage() - 1) * $permintaan->perPage() + $i + 1 }}</td>
                            <td>{{ $p->date->format('d M Y') }}</td>
                            <td class="hide-md"><span class="fw-semibold">{{ $p->reference_no }}</span></td>
                            <td class="hide-sm">{{ $p->purpose }}</td>
                            <td class="hide-md">{{ $p->truck_name ?: '-' }}</td>
                            <td class="text-center hide-sm">{{ $p->details->count() }} item</td>
                            <td>
                                @if($p->status === 'pending')<span class="badge bg-warning text-dark">Menunggu</span>
                                @else<span class="badge bg-success">Diproses</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('karyawan.permintaan.show', $p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i> Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada permintaan. <a href="{{ route('karyawan.permintaan.create') }}">Buat sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($permintaan->hasPages())
        <div class="modern-card-footer d-flex justify-content-center">
            {{ $permintaan->links() }}
        </div>
    @endif
</div>
@endsection
