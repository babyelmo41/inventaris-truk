@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    @foreach($stats as $stat)
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card p-3 h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="text-secondary small fw-semibold">{{ $stat['label'] }}</div>
                        <div class="display-6 fw-bold mb-0">{{ $stat['value'] }}</div>
                    </div>
                    <span class="metric-icon text-bg-{{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h5 fw-bold mb-0">Grafik Transaksi Masuk dan Keluar</h2>
                <span class="badge text-bg-light border">Mei 2026</span>
            </div>
            <div class="chart-placeholder d-flex align-items-center justify-content-center">
                <div class="chart-line"></div>
                <div class="text-center position-relative">
                    <i class="bi bi-graph-up fs-1 text-primary"></i>
                    <div class="fw-semibold mt-2">Placeholder Grafik Transaksi</div>
                    <div class="text-secondary small">Menampilkan tren barang masuk dan keluar.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel-card p-4 h-100">
            <h2 class="h5 fw-bold mb-3">Shortcut Laporan</h2>
            <div class="d-grid gap-2">
                <a href="{{ route('reports.show', 'stok-sparepart') }}" class="btn btn-outline-primary text-start"><i class="bi bi-file-earmark-text me-2"></i>Laporan Stok Sparepart</a>
                <a href="{{ route('reports.show', 'barang-masuk') }}" class="btn btn-outline-success text-start"><i class="bi bi-file-earmark-plus me-2"></i>Laporan Barang Masuk</a>
                <a href="{{ route('reports.show', 'barang-keluar') }}" class="btn btn-outline-danger text-start"><i class="bi bi-file-earmark-minus me-2"></i>Laporan Barang Keluar</a>
                <a href="{{ route('reports.show', 'stok-minimum') }}" class="btn btn-outline-warning text-start"><i class="bi bi-file-earmark-medical me-2"></i>Laporan Stok Minimum</a>
                <a href="{{ route('reports.show', 'riwayat-transaksi') }}" class="btn btn-outline-info text-start"><i class="bi bi-clock-history me-2"></i>Laporan Riwayat Transaksi</a>
            </div>
        </div>
    </div>
</div>
@endsection
