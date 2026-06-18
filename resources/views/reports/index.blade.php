@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="report-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Laporan</h1>
            <p class="text-white-50 mb-0">Pilih laporan yang ingin dilihat atau dicetak.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge bg-white bg-opacity-25 text-white fw-semibold px-3 py-2">
            <i class="bi bi-file-earmark-bar-graph me-1"></i>7 Laporan Tersedia
            </span>
        </div>
    </div>
</div>

{{-- Report Cards --}}
<div class="row g-4">
    {{-- Report 1: Stok Sparepart --}}
    <div class="col-md-6">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Laporan Stok Sparepart</h6>
                        <p class="text-muted small mb-3">Stok seluruh sparepart berdasarkan kategori dan supplier.</p>
                        <a href="{{ route('reports.show', 'stok-sparepart') }}" class="btn btn-primary btn-sm"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report 2: Barang Masuk --}}
    <div class="col-md-6">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Laporan Barang Masuk</h6>
                        <p class="text-muted small mb-3">Transaksi barang masuk berdasarkan periode dan supplier.</p>
                        <a href="{{ route('reports.show', 'barang-masuk') }}" class="btn btn-success btn-sm"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report 3: Barang Keluar --}}
    <div class="col-md-6">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Laporan Barang Keluar</h6>
                        <p class="text-muted small mb-3">Transaksi barang keluar berdasarkan periode dan tujuan.</p>
                        <a href="{{ route('reports.show', 'barang-keluar') }}" class="btn btn-danger btn-sm"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report 4: Stok Minimum --}}
    <div class="col-md-6">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Laporan Stok Minimum</h6>
                        <p class="text-muted small mb-3">Sparepart yang stoknya sudah di bawah batas minimum.</p>
                        <a href="{{ route('reports.show', 'stok-minimum') }}" class="btn btn-warning btn-sm"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-teal bg-opacity-10 text-teal" style="background-color: rgba(20,184,166,0.1)!important; color: #14b8a6!important;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Transaksi per Supplier</h6>
                        <p class="text-muted small mb-3">Rekapitulasi transaksi barang masuk berdasarkan supplier.</p>
                        <a href="{{ route('reports.show', 'transaksi-per-supplier') }}" class="btn btn-sm" style="background-color:#14b8a6; color:#fff;"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-purple bg-opacity-10" style="background-color: rgba(168,85,247,0.1)!important; color: #a855f7!important;">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Rekap Bulanan Masuk vs Keluar</h6>
                        <p class="text-muted small mb-3">Perbandingan stok masuk dan keluar per bulan.</p>
                        <a href="{{ route('reports.show', 'rekap-bulanan') }}" class="btn btn-sm" style="background-color:#a855f7; color:#fff;"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report 9: Pengajuan Pembelian --}}
    <div class="col-12">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-opacity-10" style="background-color: rgba(100,116,139,0.1)!important; color: #64748b!important;">
                        <i class="bi bi-clipboard-plus"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Laporan Pengajuan Pembelian</h6>
                        <p class="text-muted small mb-3">Riwayat pengajuan pembelian sparepart dan status persetujuan Pimpinan.</p>
                        <a href="{{ route('reports.show', 'pengajuan-pembelian') }}" class="btn btn-sm" style="background-color:#64748b; color:#fff;"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>
<style>
    /* Report card hover */
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.08);
    }
</style>
@endsection
