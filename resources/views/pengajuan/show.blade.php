@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">{{ $pengajuan->ajuan_no }} - {{ $pengajuan->date->format('d M Y') }}</div>
        </div>
        <a href="{{ route(session('auth_user')['role'] === 'pimpinan' ? 'pimpinan.pengajuan.index' : 'admin.pengajuan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3"><strong>Status:</strong>
            @if($pengajuan->status === 'pending')<span class="badge bg-warning text-dark ms-2">Menunggu Persetujuan</span>
            @elseif($pengajuan->status === 'approved')<span class="badge bg-success ms-2">Disetujui</span>
            @else<span class="badge bg-danger ms-2">Ditolak</span>
            @endif
        </div>
        <div class="col-md-3"><strong>Diajukan oleh:</strong> {{ $pengajuan->user->name }}</div>
        <div class="col-md-3"><strong>Disetujui oleh:</strong> {{ $pengajuan->approver ? $pengajuan->approver->name : '-' }}</div>
        <div class="col-md-3"><strong>Total Estimasi:</strong> <span class="text-primary fw-bold">{{ $pengajuan->total_estimasi_formatted }}</span></div>
    </div>

    @if($pengajuan->notes)
    <div class="mb-4"><strong>Catatan:</strong><br>{!! nl2br(e($pengajuan->notes)) !!}</div>
    @endif

    <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Item Pengajuan</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Sparepart</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Subtotal</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->sparepart->code }} - {{ $detail->sparepart->name }}</td>
                    <td class="text-center">{{ $detail->quantity }} {{ $detail->sparepart->unit }}</td>
                    <td class="text-end">{{ $detail->price_formatted }}</td>
                    <td class="text-end fw-semibold text-primary">{{ $detail->total_formatted }}</td>
                    <td>{{ $detail->notes ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end">Total Estimasi:</th>
                    <th class="text-end text-primary">{{ $pengajuan->total_estimasi_formatted }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    @if(session('auth_user')['role'] === 'pimpinan' && $pengajuan->status === 'pending')
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle me-2"></i>Total estimasi biaya: <strong>{{ $pengajuan->total_estimasi_formatted }}</strong>. Gunakan informasi ini sebagai pertimbangan persetujuan.
    </div>

    <div class="d-flex gap-2">
        <form action="{{ route('pimpinan.pengajuan.approve', $pengajuan) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui pengajuan ini?')">
                <i class="bi bi-check-circle me-2"></i>Setujui
            </button>
        </form>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-circle me-2"></i>Tolak
        </button>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('pimpinan.pengajuan.reject', $pengajuan) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Tolak Pengajuan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reject_notes" rows="3" required placeholder="Jelaskan alasan penolakan... (misal: budget tidak mencukupi, harga kemahalan, dll)"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
