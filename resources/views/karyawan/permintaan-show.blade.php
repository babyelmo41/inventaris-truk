@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">{{ $permintaan->reference_no }} - {{ $permintaan->date->format('d M Y') }}</div>
        </div>
        <a href="{{ route('karyawan.permintaan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <strong>Status:</strong>
            @if($permintaan->status === 'pending')<span class="badge bg-warning text-dark ms-2">Menunggu Proses Admin</span>
            @else<span class="badge bg-success ms-2">Sudah Diproses</span>
            @endif
        </div>
        <div class="col-md-4"><strong>Keperluan:</strong> {{ $permintaan->purpose }}</div>
        <div class="col-md-4"><strong>Truk:</strong> {{ $permintaan->truck_name ?: '-' }}</div>
    </div>

    @if($permintaan->notes)
    <div class="mb-4"><strong>Catatan:</strong><br>{{ $permintaan->notes }}</div>
    @endif

    <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Detail Item</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr><th>No</th><th>Sparepart</th><th>Jumlah Diminta</th><th>Stok Saat Ini</th></tr>
            </thead>
            <tbody>
                @foreach($permintaan->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->sparepart->code }} - {{ $detail->sparepart->name }}</td>
                    <td>{{ $detail->quantity }} {{ $detail->sparepart->unit }}</td>
                    <td>{{ $detail->sparepart->stock }} {{ $detail->sparepart->unit }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($permintaan->status === 'pending')
    <div class="alert alert-info mb-0">
        <i class="bi bi-info-circle me-2"></i>Permintaan Anda sedang menunggu proses oleh Admin. Silakan hubungi Admin gudang untuk informasi lebih lanjut.
    </div>
    @endif
</div>
@endsection
