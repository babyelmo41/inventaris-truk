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
                <i class="bi bi-file-earmark-bar-graph me-1"></i>5 Laporan Tersedia
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

    {{-- Report 5: Riwayat Transaksi --}}
    <div class="col-12">
        <div class="report-card h-100">
            <div class="report-card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="report-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Riwayat Transaksi Sparepart</h6>
                        <p class="text-muted small mb-3">Histori seluruh transaksi barang masuk dan keluar.</p>
                        <a href="{{ route('reports.show', 'riwayat-transaksi') }}" class="btn btn-info btn-sm"><i class="bi bi-eye me-1"></i>Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dark Header */
    .report-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .report-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .report-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        right: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* Card Style */
    .report-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.08);
    }
    .report-card-body {
        padding: 1.5rem;
    }

    /* Icon */
    .report-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
</style>
@endsection
