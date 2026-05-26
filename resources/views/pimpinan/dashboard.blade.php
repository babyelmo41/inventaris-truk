@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Dashboard Pimpinan</h1>
            <p class="text-white-50 mb-0">Ringkasan data inventaris, tren transaksi, dan akses laporan.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('pimpinan.dashboard') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise me-2"></i>Refresh</a>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
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

{{-- Chart & Shortcuts --}}
<div class="row g-4">
    <div class="col-xl-8">
        <div class="modern-card h-100">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2"></i>Tren Transaksi Bulanan</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">6 Bulan Terakhir</span>
            </div>
            <div class="modern-card-body">
                <div style="height: 300px;">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-link-45deg me-2"></i>Shortcut Laporan</h6>
            </div>
            <div class="modern-card-body">
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
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('trenChart'), {
    type: 'bar',
    data: {
        labels: @json($chartData['labels']),
        datasets: [
            {
                label: 'Barang Masuk',
                data: @json($chartData['masuk']),
                backgroundColor: '#22c55e',
                borderRadius: 6,
                barPercentage: 0.6,
            },
            {
                label: 'Barang Keluar',
                data: @json($chartData['keluar']),
                backgroundColor: '#ef4444',
                borderRadius: 6,
                barPercentage: 0.6,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: { usePointStyle: true, pointStyle: 'rectRounded', padding: 16 }
            },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: ${ctx.raw} transaksi`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>
@endpush
