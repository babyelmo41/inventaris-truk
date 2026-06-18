@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">{{ $stockOpname->opname_no }} - {{ $stockOpname->date->format('d M Y') }}</div>
        </div>
        <a href="{{ route(session('auth_user')['role'] === 'pimpinan' ? 'pimpinan.stock-opname.index' : 'admin.stock-opname.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3"><strong>Status:</strong>
            @if($stockOpname->status === 'draft')<span class="badge bg-secondary ms-2">Draft</span>
            @elseif($stockOpname->status === 'submitted')<span class="badge bg-info text-dark ms-2">Disubmit</span>
            @elseif($stockOpname->status === 'approved')<span class="badge bg-success ms-2">Disetujui</span>
            @else<span class="badge bg-danger ms-2">Ditolak</span>
            @endif
        </div>
        <div class="col-md-3"><strong>Bulan Siklus:</strong> {{ \Carbon\Carbon::parse($stockOpname->cycle_month)->format('M Y') }}</div>
        <div class="col-md-3"><strong>Kelompok:</strong> {{ $stockOpname->cycle_group }}</div>
        <div class="col-md-3"><strong>Dihitung oleh:</strong> {{ $stockOpname->user->name }}</div>
    </div>

    @if($stockOpname->notes)
    <div class="mb-4"><strong>Catatan:</strong><br>{{ $stockOpname->notes }}</div>
    @endif

    <h5 class="fw-bold mb-3"><i class="bi bi-clipboard-data me-2"></i>Hasil Hitung Fisik</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr><th class="hide-sm">No</th><th>Sparepart</th><th>Stok Sistem</th><th>Stok Fisik</th><th>Selisih</th><th class="hide-md">Catatan</th></tr>
            </thead>
            <tbody>
                @foreach($stockOpname->details as $i => $detail)
                <tr>
                    <td class="hide-sm">{{ $i + 1 }}</td>
                    <td>{{ $detail->sparepart->code }} - {{ $detail->sparepart->name }}</td>
                    <td>{{ $detail->system_stock }} pcs</td>
                    <td>{{ $detail->physical_stock }} pcs</td>
                    <td class="fw-bold {{ $detail->discrepancy < 0 ? 'text-danger' : ($detail->discrepancy > 0 ? 'text-primary' : 'text-success') }}">
                        {{ $detail->discrepancy > 0 ? '+' : '' }}{{ $detail->discrepancy }}
                    </td>
                    <td class="hide-md">{{ $detail->notes ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(session('auth_user')['role'] === 'pimpinan' && ($stockOpname->status === 'submitted' || $stockOpname->status === 'draft'))
    <div class="d-flex gap-2">
        <form action="{{ route('pimpinan.stock-opname.approve', $stockOpname) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui hasil stock opname ini?')">
                <i class="bi bi-check-circle me-2"></i>Setujui
            </button>
        </form>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-circle me-2"></i>Tolak
        </button>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('pimpinan.stock-opname.reject', $stockOpname) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Tolak Stock Opname</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reject_notes" rows="3" required placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
