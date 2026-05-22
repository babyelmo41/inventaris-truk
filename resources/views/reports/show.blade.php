@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $report['title'] }}</h2>
            <div class="text-secondary">{{ $report['description'] }}</div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer me-2"></i>Cetak</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>

    {{-- Info Filter --}}
    <div class="mb-3">
        <small class="text-secondary">
            <i class="bi bi-funnel me-1"></i>
            Filter tersedia: {{ implode(', ', $report['filters']) }}
        </small>
    </div>

    {{-- Tabel Report --}}
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0" id="report-table">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    @foreach($report['headers'] as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($report['rows'] as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @foreach($row as $cell)
                            <td>
                                {{-- Badge khusus untuk kolom Status --}}
                                @if($cell === 'Aman')
                                    <span class="badge bg-success">{{ $cell }}</span>
                                @elseif($cell === 'Hampir Habis')
                                    <span class="badge bg-warning text-dark">{{ $cell }}</span>
                                @elseif($cell === 'Habis')
                                    <span class="badge bg-danger">{{ $cell }}</span>
                                @else
                                    {{ $cell }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($report['headers']) + 1 }}" class="text-center text-secondary py-4">
                            Tidak ada data untuk ditampilkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="{{ count($report['headers']) + 1 }}" class="text-end fw-semibold">
                        Total: {{ count($report['rows']) }} data
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Info Relasi Database --}}
    <div class="mt-4 p-3 bg-light rounded">
        <h6 class="fw-bold mb-2"><i class="bi bi-diagram-3 me-2"></i>Informasi Relasi Database</h6>
        <small class="text-secondary">
            @if($type === 'stok-sparepart')
                <code>spareparts</code> → <code>categories</code> (category_id)<br>
                <code>spareparts</code> → <code>suppliers</code> (supplier_id)
            @elseif($type === 'barang-masuk')
                <code>barang_masuk</code> → <code>suppliers</code> (supplier_id)<br>
                <code>barang_masuk</code> → <code>detail_barang_masuk</code> (id)<br>
                <code>detail_barang_masuk</code> → <code>spareparts</code> (sparepart_id)
            @elseif($type === 'barang-keluar')
                <code>barang_keluar</code> → <code>detail_barang_keluar</code> (id)<br>
                <code>detail_barang_keluar</code> → <code>spareparts</code> (sparepart_id)<br>
                <code>barang_keluar</code> → <code>users</code> (user_id)
            @elseif($type === 'stok-minimum')
                <code>spareparts</code> → <code>categories</code> (category_id)<br>
                <code>spareparts</code> → <code>suppliers</code> (supplier_id)<br>
                <i>Kondisi: WHERE stock ≤ min_stock</i>
            @elseif($type === 'riwayat-transaksi')
                <code>UNION ALL</code>:<br>
                <code>barang_masuk</code> → <code>detail_barang_masuk</code> → <code>spareparts</code> → <code>users</code><br>
                <code>barang_keluar</code> → <code>detail_barang_keluar</code> → <code>spareparts</code> → <code>users</code>
            @endif
        </small>
    </div>
</div>

<style>
    @media print {
        .sidebar, .topbar, .btn { display: none !important; }
        .main-area { margin-left: 0 !important; }
        .panel-card { box-shadow: none !important; border: none !important; }
    }
</style>
@endsection
