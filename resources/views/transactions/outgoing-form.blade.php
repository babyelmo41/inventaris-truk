@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">Input pengeluaran sparepart dari gudang.</div>
        </div>
        <a href="{{ route('admin.barang-keluar') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $transaction ? route('admin.barang-keluar.update', $transaction) : route('admin.barang-keluar.store') }}" method="POST" id="transactionForm">
        @csrf
        @if($transaction)
            @method('PUT')
        @endif

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <label for="date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $transaction ? $transaction->date->format('Y-m-d') : date('Y-m-d')) }}" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="reference_no" class="form-label fw-semibold">No Referensi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('reference_no') is-invalid @enderror" id="reference_no" name="reference_no" value="{{ old('reference_no', $transaction->reference_no ?? '') }}" placeholder="Contoh: REF-2024-001" required>
                @error('reference_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="purpose" class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" value="{{ old('purpose', $transaction->purpose ?? '') }}" placeholder="Contoh: Servis Truk Hino 500" required>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <label for="notes" class="form-label fw-semibold">Catatan</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('notes', $transaction->notes ?? '') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Detail Items --}}
        <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Detail Item</h5>

        @error('items')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="table-responsive mb-3">
            <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50%">Sparepart <span class="text-danger">*</span></th>
                        <th style="width: 20%">Jumlah <span class="text-danger">*</span></th>
                        <th style="width: 20%">Satuan</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    @if($transaction && $transaction->details->count() > 0)
                        @foreach($transaction->details as $detail)
                            <tr class="item-row">
                                <td>
                                    <select class="form-select sparepart-select" name="items[{{ $loop->index }}][sparepart_id]" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($spareparts as $sparepart)
                                            <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}" data-stock="{{ $sparepart->stock }}" {{ $detail->sparepart_id == $sparepart->id ? 'selected' : '' }}>{{ $sparepart->code }} - {{ $sparepart->name }} (Stok: {{ $sparepart->stock }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity-input" name="items[{{ $loop->index }}][quantity]" value="{{ $detail->quantity }}" min="1" required>
                                </td>
                                <td>
                                    <span class="unit-display form-control-plaintext">{{ $detail->sparepart->unit }}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="item-row">
                            <td>
                                <select class="form-select sparepart-select" name="items[0][sparepart_id]" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($spareparts as $sparepart)
                                        <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}" data-stock="{{ $sparepart->stock }}">{{ $sparepart->code }} - {{ $sparepart->name }} (Stok: {{ $sparepart->stock }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" value="1" min="1" required>
                            </td>
                            <td>
                                <span class="unit-display form-control-plaintext">-</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-outline-success btn-sm mb-4" id="addItem">
            <i class="bi bi-plus-circle me-1"></i>Tambah Item
        </button>

        {{-- Submit --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>{{ $transaction ? 'Update' : 'Simpan' }}</button>
            <a href="{{ route('admin.barang-keluar') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = {{ $transaction ? $transaction->details->count() : 1 }};
    const sparepartOptions = `<option value="">-- Pilih --</option>
        @foreach($spareparts as $sparepart)
            <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}" data-stock="{{ $sparepart->stock }}">{{ $sparepart->code }} - {{ $sparepart->name }} (Stok: {{ $sparepart->stock }})</option>
        @endforeach`;

    // Add item row
    document.getElementById('addItem').addEventListener('click', function() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select class="form-select sparepart-select" name="items[${rowIndex}][sparepart_id]" required>
                    ${sparepartOptions}
                </select>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="items[${rowIndex}][quantity]" value="1" min="1" required>
            </td>
            <td>
                <span class="unit-display form-control-plaintext">-</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(newRow);
        rowIndex++;
    });

    // Remove item row
    document.getElementById('itemsBody').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada 1 item!');
            }
        }
    });

    // Update unit when sparepart selected & validate stock
    document.getElementById('itemsBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('sparepart-select')) {
            const selected = e.target.options[e.target.selectedIndex];
            const unit = selected.dataset.unit || '-';
            const stock = parseInt(selected.dataset.stock) || 0;
            const row = e.target.closest('.item-row');
            row.querySelector('.unit-display').textContent = unit;

            // Update max quantity based on stock
            const qtyInput = row.querySelector('.quantity-input');
            qtyInput.max = stock;
            if (parseInt(qtyInput.value) > stock) {
                qtyInput.value = stock;
            }
        }
    });
});
</script>
@endsection
