@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">Ajukan permintaan sparepart. Sertakan foto bukti kondisi truk sebelum perbaikan (before).</div>
        </div>
        <a href="{{ route('karyawan.permintaan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('karyawan.permintaan.store') }}" method="POST" id="permintaanForm" enctype="multipart/form-data">
        @csrf

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <label for="reference_no" class="form-label fw-semibold">No Permintaan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('reference_no') is-invalid @enderror" id="reference_no" name="reference_no" value="{{ old('reference_no', $generatedPermintaanNo ?? '') }}" readonly required>
                @error('reference_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="purpose" class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" value="{{ old('purpose') }}" placeholder="Contoh: Servis Truk DT-014" required>
                @error('purpose')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="truck_name" class="form-label fw-semibold">Tujuan Truk</label>
                <input type="text" class="form-control @error('truck_name') is-invalid @enderror" id="truck_name" name="truck_name" value="{{ old('truck_name') }}" placeholder="Contoh: Truk DT-014">
                @error('truck_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <label for="notes" class="form-label fw-semibold">Catatan</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Item yang Diminta</h5>
        <div class="alert alert-info mb-3">
            <i class="bi bi-camera me-2"></i><strong>Setiap item wajib dilengkapi foto before</strong> — foto bukti kondisi truk/sparepart sebelum perbaikan. Bisa ambil dari kamera langsung atau pilih dari galeri.
        </div>

        @error('items')<div class="alert alert-danger">{{ $message }}</div>@enderror

        <div class="table-responsive mb-3">
            <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%">Sparepart <span class="text-danger">*</span></th>
                        <th style="width: 10%">Stok</th>
                        <th style="width: 10%">Jumlah <span class="text-danger">*</span></th>
                        <th style="width: 8%">Satuan</th>
                        <th style="width: 32%">Foto Before <span class="text-danger">*</span></th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <tr class="item-row">
                        <td>
                            <select class="form-select sparepart-select" name="items[0][sparepart_id]" required>
                                <option value="">-- Pilih Sparepart --</option>
                                @foreach($spareparts as $sp)
                                    <option value="{{ $sp->id }}" data-stock="{{ $sp->stock }}" data-unit="{{ $sp->unit }}" {{ $sp->stock <= 0 ? 'disabled' : '' }}>{{ $sp->code }} - {{ $sp->name }} ({{ $sp->stock <= 0 ? 'HABIS' : $sp->stock . ' ' . $sp->unit }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td><span class="stock-display form-control-plaintext text-muted">-</span></td>
                        <td><input type="number" class="form-control quantity-input" name="items[0][quantity]" value="1" min="1" required></td>
                        <td><span class="unit-display form-control-plaintext">-</span></td>
                        <td>
                            <input type="file" class="form-control form-control-sm before-photo-input" name="items[0][before_photo]" accept="image/*" capture="environment" required>
                            <div class="preview-container mt-1"></div>
                        </td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-outline-success btn-sm mb-4" id="addItem">
            <i class="bi bi-plus-circle me-1"></i>Tambah Item
        </button>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Kirim Permintaan</button>
            <a href="{{ route('karyawan.permintaan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    const sparepartOptions = `<option value="">-- Pilih Sparepart --</option>
        @foreach($spareparts as $sp)\n            <option value="{{ $sp->id }}" data-stock="{{ $sp->stock }}" data-unit="{{ $sp->unit }}" {{ $sp->stock <= 0 ? 'disabled' : '' }}>{{ $sp->code }} - {{ $sp->name }} ({{ $sp->stock <= 0 ? 'HABIS' : $sp->stock . ' ' . $sp->unit }})</option>\n        @endforeach`;

    document.getElementById('addItem').addEventListener('click', function() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td><select class="form-select sparepart-select" name="items[${rowIndex}][sparepart_id]" required>${sparepartOptions}</select></td>
            <td><span class="stock-display form-control-plaintext text-muted">-</span></td>
            <td><input type="number" class="form-control quantity-input" name="items[${rowIndex}][quantity]" value="1" min="1" required></td>
            <td><span class="unit-display form-control-plaintext">-</span></td>
            <td>
                <input type="file" class="form-control form-control-sm before-photo-input" name="items[${rowIndex}][before_photo]" accept="image/*" capture="environment" required>
                <div class="preview-container mt-1"></div>
            </td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
        `;
        tbody.appendChild(newRow);
        rowIndex++;
    });

    document.getElementById('itemsBody').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) row.remove();
            else alert('Minimal harus ada 1 item!');
        }
    });

    document.getElementById('itemsBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('sparepart-select')) {
            const selected = e.target.options[e.target.selectedIndex];
            const stock = selected.dataset.stock || '-';
            const unit = selected.dataset.unit || '-';
            const row = e.target.closest('.item-row');
            row.querySelector('.stock-display').textContent = stock + ' pcs';
            row.querySelector('.unit-display').textContent = unit;
        }

        // Preview foto sebelum submit
        if (e.target.classList.contains('before-photo-input')) {
            const file = e.target.files[0];
            const container = e.target.closest('td').querySelector('.preview-container');
            container.innerHTML = '';

            if (file) {
                // Validasi ukuran (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2MB!');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(ev) {
                    container.innerHTML = `<img src="${ev.target.result}" class="img-thumbnail" style="max-height: 80px;">`;
                };
                reader.readAsDataURL(file);
            }
        }
    });
});
</script>
@endsection
