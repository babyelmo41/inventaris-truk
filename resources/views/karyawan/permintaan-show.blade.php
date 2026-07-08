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
        <div class="col-md-3">
            <strong>Status:</strong>
            @if($permintaan->status === 'pending')
                <span class="badge bg-warning text-dark ms-2">Menunggu Proses Admin</span>
            @elseif($permintaan->status === 'processed')
                <span class="badge bg-info ms-2">Sedang Diproses</span>
            @elseif($permintaan->status === 'completed')
                <span class="badge bg-success ms-2">Selesai</span>
            @endif
        </div>
        <div class="col-md-3"><strong>Keperluan:</strong> {{ $permintaan->purpose }}</div>
        <div class="col-md-3"><strong>Truk:</strong> {{ $permintaan->truck_name ?: '-' }}</div>
        <div class="col-md-3">
            <strong>Progress Foto After:</strong>
            <span class="badge bg-secondary ms-1">{{ $permintaan->completionProgress() }}</span>
        </div>
    </div>

    @if($permintaan->notes)
    <div class="mb-4"><strong>Catatan:</strong><br>{{ $permintaan->notes }}</div>
    @endif

    <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Detail Item</h5>

    @if($permintaan->status === 'pending')
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle me-2"></i>Permintaan Anda sedang menunggu proses oleh Admin. Foto before sudah tercatat.
    </div>
    @elseif($permintaan->status === 'processed')
    <div class="alert alert-warning mb-3">
        <i class="bi bi-camera me-2"></i><strong>Upload foto after</strong> — setelah memasang sparepart, upload foto bukti pemasangan untuk setiap item di bawah ini.
    </div>
    @elseif($permintaan->status === 'completed')
    <div class="alert alert-success mb-3">
        <i class="bi bi-check-circle me-2"></i>Semua foto after sudah terupload. Permintaan ini selesai.
    </div>
    @endif

    @foreach($permintaan->details as $i => $detail)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-start">
                <div class="col-md-3">
                    <h6 class="fw-bold mb-1">{{ $detail->sparepart->code }}</h6>
                    <div class="text-muted small">{{ $detail->sparepart->name }}</div>
                    <div class="mt-1">Jumlah: <strong>{{ $detail->quantity }} {{ $detail->sparepart->unit }}</strong></div>
                    <div class="mt-1">
                        <span class="badge bg-{{ $detail->status_badge }}">{{ $detail->status_label }}</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold mb-1"><i class="bi bi-camera me-1"></i>Foto Before</div>
                    @if($detail->before_photo)
                        <a href="{{ $detail->before_photo_url }}" target="_blank">
                            <img src="{{ $detail->before_photo_url }}" class="img-thumbnail" style="max-height: 150px;">
                        </a>
                    @else
                        <span class="text-muted">Tidak ada foto</span>
                    @endif
                </div>

                <div class="col-md-5">
                    <div class="fw-semibold mb-1"><i class="bi bi-camera-fill me-1"></i>Foto After</div>
                    @if($detail->after_photo)
                        <a href="{{ $detail->after_photo_url }}" target="_blank">
                            <img src="{{ $detail->after_photo_url }}" class="img-thumbnail" style="max-height: 150px;">
                        </a>
                        <div class="text-success small mt-1"><i class="bi bi-check-circle"></i> Sudah diupload</div>
                    @elseif($permintaan->status === 'processed')
                        <form action="{{ route('karyawan.permintaan.upload-after', [$permintaan, $detail]) }}" method="POST" enctype="multipart/form-data" class="upload-after-form">
                            @csrf
                            <input type="file" class="form-control form-control-sm after-photo-input" name="after_photo" accept="image/*" capture="environment" required>
                            <div class="preview-container mt-1"></div>
                            <button type="submit" class="btn btn-sm btn-success mt-2">
                                <i class="bi bi-upload me-1"></i>Upload Foto After
                            </button>
                        </form>
                    @else
                        <span class="text-muted">Menunggu proses Admin</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview foto after sebelum upload
    document.querySelectorAll('.after-photo-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = e.target.closest('.upload-after-form').querySelector('.preview-container');
            container.innerHTML = '';

            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2MB!');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(ev) {
                    container.innerHTML = `<img src="${ev.target.result}" class="img-thumbnail" style="max-height: 100px;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection
