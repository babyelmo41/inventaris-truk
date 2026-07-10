@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">Input penerimaan sparepart dari supplier.</div>
        </div>
        <a href="{{ route('admin.barang-masuk') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
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

    <form action="{{ $transaction ? route('admin.barang-masuk.update', $transaction) : route('admin.barang-masuk.store') }}" method="POST" id="transactionForm">
        @csrf
        @if($transaction)
            @method('PUT')
        @endif

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <label for="date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $transaction ? $transaction->date->format('Y-m-d') : date('Y-m-d')) }}" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="time" class="form-label fw-semibold">Waktu <span class="text-danger">*</span></label>
                <input type="time" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time', $transaction ? $transaction->time : date('H:i')) }}" required>
                @error('time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="invoice_no" class="form-label fw-semibold">No Invoice <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('invoice_no') is-invalid @enderror" id="invoice_no" name="invoice_no" value="{{ old('invoice_no', $transaction->invoice_no ?? $generatedInvoiceNo ?? '') }}" {{ $transaction ? '' : 'readonly' }} required>
                @error('invoice_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="supplier_id" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $transaction->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Rujukan Pengajuan (opsional) --}}
        @if(!$transaction && isset($approvedPengajuan) && $approvedPengajuan->count() > 0)
        <div class="row mb-4">
            <div class="col-md-12">
                <label for="pengajuan_select" class="form-label fw-semibold">
                    <i class="bi bi-link-45deg me-1"></i>Rujukan Pengajuan Pembelian <span class="text-muted">(opsional)</span>
                </label>
                <select class="form-select" id="pengajuan_select">
                    <option value="">-- Tanpa Rujukan (input manual) --</option>
                    @foreach($approvedPengajuan as $pgj)
                        <option value="{{ $pgj->id }}" data-ajuan-no="{{ $pgj->ajuan_no }}">
                            {{ $pgj->ajuan_no }} — {{ $pgj->date->format('d M Y') }} ({{ $pgj->details->count() }} item, Estimasi: {{ $pgj->total_estimasi_formatted }})
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="pengajuan_id" id="pengajuan_id" value="">
                <div class="form-text">Pilih pengajuan yang sudah disetujui untuk mengisi item otomatis.</div>
            </div>
        </div>
        @endif

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
                        <th style="width: 40%">Sparepart <span class="text-danger">*</span></th>
                        <th style="width: 15%">Jumlah <span class="text-danger">*</span></th>
                        <th style="width: 15%">Satuan</th>
                        <th style="width: 20%">Harga Satuan <span class="text-danger">*</span></th>
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
                                            <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}" {{ $detail->sparepart_id == $sparepart->id ? 'selected' : '' }}>{{ $sparepart->code }} - {{ $sparepart->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity-input" name="items[{{ $loop->index }}][quantity]" value="{{ $detail->quantity }}" min="1" required>
                                </td>
                                <td>
                                    <span class="unit-display form-control-plaintext">{{ $detail->sparepart->unit }}</span>
                                </td>
                                <td>
                                    <input type="number" class="form-control price-input" name="items[{{ $loop->index }}][price]" value="{{ number_format($detail->price, 0, '', '') }}" min="0" required>
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
                                        <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}">{{ $sparepart->code }} - {{ $sparepart->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" value="1" min="1" required>
                            </td>
                            <td>
                                <span class="unit-display form-control-plaintext">-</span>
                            </td>
                            <td>
                                <input type="number" class="form-control price-input" name="items[0][price]" min="0" required>
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
            <a href="{{ route('admin.barang-masuk') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set date & time dari device user (hanya untuk form baru)
    @if(!$transaction)
    const now = new Date();
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    if (dateInput && !dateInput.value) {
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;
    }
    if (timeInput) {
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        timeInput.value = `${hours}:${minutes}`;
    }
    @endif

    // Auto-fill dari pengajuan
    const pengajuanSelect = document.getElementById('pengajuan_select');
    const pengajuanIdInput = document.getElementById('pengajuan_id');
    if (pengajuanSelect) {
        pengajuanSelect.addEventListener('change', async function() {
            const pgjId = this.value;
            pengajuanIdInput.value = pgjId;

            if (!pgjId) {
                // Reset ke manual - kosongkan items
                return;
            }

            try {
                const resp = await fetch(`{{ url('/admin/barang-masuk/pengajuan') }}/${pgjId}`);
                const data = await resp.json();

                // Kosongkan tbody
                const tbody = document.getElementById('itemsBody');
                tbody.innerHTML = '';

                // Isi item dari pengajuan
                data.items.forEach(function(item, idx) {
                    const newRow = document.createElement('tr');
                    newRow.className = 'item-row';
                    newRow.innerHTML = `
                        <td><select class="form-select sparepart-select" name="items[${idx}][sparepart_id]" required>${sparepartOptions}</select></td>
                        <td><span class="unit-display form-control-plaintext">-</span></td>
                        <td><input type="number" class="form-control quantity-input" name="items[${idx}][quantity]" value="${item.quantity}" min="1" required></td>
                        <td><input type="number" class="form-control price-input" name="items[${idx}][price]" value="${item.price}" min="0" required></td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                    `;
                    tbody.appendChild(newRow);

                    // Set sparepart selection
                    const select = newRow.querySelector('.sparepart-select');
                    select.value = item.sparepart_id;
                    // Trigger change to update unit display
                    select.dispatchEvent(new Event('change'));
                });

                rowIndex = data.items.length;
            } catch (e) {
                console.error('Gagal memuat detail pengajuan:', e);
            }
        });
    }

    let rowIndex = {{ $transaction ? $transaction->details->count() : 1 }};
    const sparepartOptions = `<option value="">-- Pilih --</option>
        @foreach($spareparts as $sparepart)
            <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}">{{ $sparepart->code }} - {{ $sparepart->name }}</option>
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
            <td>
                <input type="number" class="form-control price-input" name="items[${rowIndex}][price]" min="0" required>
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

    // Update unit when sparepart selected
    document.getElementById('itemsBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('sparepart-select')) {
            const selected = e.target.options[e.target.selectedIndex];
            const unit = selected.dataset.unit || '-';
            const row = e.target.closest('.item-row');
            row.querySelector('.unit-display').textContent = unit;
        }
    });
});
</script>
@endsection
