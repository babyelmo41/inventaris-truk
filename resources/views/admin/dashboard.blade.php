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
    <div class="col-xl-7">
        <div class="panel-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h5 fw-bold mb-0">Grafik Pergerakan Stok</h2>
                <span class="badge text-bg-light border">Dummy Data</span>
            </div>
            <div class="chart-placeholder d-flex align-items-center justify-content-center">
                <div class="chart-line"></div>
                <div class="text-center position-relative">
                    <i class="bi bi-bar-chart-line fs-1 text-primary"></i>
                    <div class="fw-semibold mt-2">Placeholder Grafik Stok</div>
                    <div class="text-secondary small">Area ini siap diganti Chart.js saat backend tersedia.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="panel-card p-4 h-100">
            <h2 class="h5 fw-bold mb-3">Aktivitas Transaksi Terbaru</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Transaksi</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td class="fw-semibold">{{ $activity['time'] }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $activity['item'] }}</div>
                                    <div class="small text-secondary">{{ $activity['code'] }} - {{ $activity['type'] }}</div>
                                </td>
                                <td><span class="badge text-bg-primary">{{ $activity['qty'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
