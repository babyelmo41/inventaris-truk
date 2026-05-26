@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Dashboard Admin Gudang</h1>
            <p class="text-white-50 mb-0">Ringkasan data inventaris, stok gudang, dan aktivitas terbaru.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise me-2"></i>Refresh</a>
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

{{-- Chart & Activity --}}
<div class="row g-4">
    <div class="col-xl-5">
        <div class="modern-card h-100">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i>Status Stok Gudang</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">Realtime</span>
            </div>
            <div class="modern-card-body">
                <div class="d-flex align-items-center justify-content-center" style="height: 280px;">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-4 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:12px;height:12px;border-radius:3px;background:#22c55e;display:inline-block;"></span>
                        <span class="small text-secondary">Aman</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:12px;height:12px;border-radius:3px;background:#f59e0b;display:inline-block;"></span>
                        <span class="small text-secondary">Hampir Habis</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:12px;height:12px;border-radius:3px;background:#ef4444;display:inline-block;"></span>
                        <span class="small text-secondary">Habis</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-7">
        <div class="modern-card h-100">
            <div class="modern-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h6>
            </div>
            <div class="modern-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Waktu</th>
                                <th>Transaksi</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td class="fw-semibold ps-4">{{ $activity['date'] }}</td>
                                    <td class="fw-semibold">{{ $activity['time'] }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $activity['item'] }}</div>
                                        <div class="small text-secondary">{{ $activity['code'] }} - {{ $activity['type'] }}</div>
                                    </td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $activity['qty'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Aman', 'Hampir Habis', 'Habis'],
        datasets: [{
            data: [{{ $chartData['aman'] }}, {{ $chartData['hampir_habis'] }}, {{ $chartData['habis'] }}],
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.raw} sparepart`
                }
            }
        }
    }
});
</script>
@endpush
