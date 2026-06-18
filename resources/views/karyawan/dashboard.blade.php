@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Dashboard Karyawan</h1>
            <p class="text-white-50 mb-0">Selamat datang, {{ session('auth_user.name') }}! Kelola permintaan sparepart Anda.</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="metric-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-clipboard-plus"></i></div>
                <div>
                    <div class="small text-secondary">Total Permintaan</div>
                    <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="small text-secondary">Menunggu Proses</div>
                    <div class="h4 fw-bold mb-0">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="small text-secondary">Sudah Diproses</div>
                    <div class="h4 fw-bold mb-0">{{ $stats['processed'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="small text-secondary">Ditolak</div>
                    <div class="h4 fw-bold mb-0">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel-card p-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Permintaan Terakhir</h5>
    @if($latest->isEmpty())
        <p class="text-muted text-center py-3">Belum ada permintaan. <a href="{{ route('karyawan.permintaan.create') }}">Buat sekarang</a></p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr><th>No Ref</th><th>Tanggal</th><th>Keperluan</th><th>Jumlah Item</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($latest as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->reference_no }}</td>
                        <td>{{ $p->date->format('d M Y') }}</td>
                        <td>{{ $p->purpose }}</td>
                        <td>{{ $p->details->count() }} item</td>
                        <td>
                            @if($p->status === 'pending')<span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($p->status === 'processed')<span class="badge bg-success">Diproses</span>
                            @else<span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
