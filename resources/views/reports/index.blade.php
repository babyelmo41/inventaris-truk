@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="mb-4">
        <h2 class="h5 fw-bold mb-1">Laporan</h2>
        <div class="text-secondary">Pilih laporan yang ingin dilihat. Semua laporan menggunakan relasi antar tabel database.</div>
    </div>

    <div class="row g-3">
        {{-- Report 1: Stok Sparepart --}}
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="metric-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Laporan Stok Sparepart</h6>
                            <p class="text-secondary small mb-2">Stok seluruh sparepart berdasarkan kategori dan supplier.</p>
                            <small class="text-muted">
                                <i class="bi bi-diagram-3 me-1"></i>
                                JOIN: spareparts → categories, suppliers
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('reports.show', 'stok-sparepart') }}" class="btn btn-sm btn-primary">Lihat Laporan</a>
                </div>
            </div>
        </div>

        {{-- Report 2: Barang Masuk --}}
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="metric-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Laporan Barang Masuk</h6>
                            <p class="text-secondary small mb-2">Transaksi barang masuk berdasarkan periode dan supplier.</p>
                            <small class="text-muted">
                                <i class="bi bi-diagram-3 me-1"></i>
                                JOIN: barang_masuk → detail → spareparts → suppliers
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('reports.show', 'barang-masuk') }}" class="btn btn-sm btn-success">Lihat Laporan</a>
                </div>
            </div>
        </div>

        {{-- Report 3: Barang Keluar --}}
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="metric-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Laporan Barang Keluar</h6>
                            <p class="text-secondary small mb-2">Transaksi barang keluar berdasarkan periode dan tujuan.</p>
                            <small class="text-muted">
                                <i class="bi bi-diagram-3 me-1"></i>
                                JOIN: barang_keluar → detail → spareparts, users
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('reports.show', 'barang-keluar') }}" class="btn btn-sm btn-danger">Lihat Laporan</a>
                </div>
            </div>
        </div>

        {{-- Report 4: Stok Minimum --}}
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="metric-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Laporan Stok Minimum</h6>
                            <p class="text-secondary small mb-2">Sparepart yang stoknya sudah di bawah batas minimum.</p>
                            <small class="text-muted">
                                <i class="bi bi-diagram-3 me-1"></i>
                                JOIN: spareparts → categories, suppliers + WHERE stock ≤ min_stock
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('reports.show', 'stok-minimum') }}" class="btn btn-sm btn-warning">Lihat Laporan</a>
                </div>
            </div>
        </div>

        {{-- Report 5: Riwayat Transaksi --}}
        <div class="col-md-12">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="metric-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Riwayat Transaksi Sparepart</h6>
                            <p class="text-secondary small mb-2">Histori seluruh transaksi barang masuk dan keluar.</p>
                            <small class="text-muted">
                                <i class="bi bi-diagram-3 me-1"></i>
                                UNION ALL: barang_masuk + barang_keluar → detail → spareparts → users
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('reports.show', 'riwayat-transaksi') }}" class="btn btn-sm btn-info">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
