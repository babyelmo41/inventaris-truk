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

        {{-- Dasar Pengadaan (wajib) — hanya form baru --}}
        @if(!$transaction)
        <div class="row mb-4">
            <div class="col-md-12">
                @if(isset($approvedPengajuan) && $approvedPengajuan->count() > 0)
                    <label for="pengajuan_select" class="form-label fw-semibold">
                        <i class="bi bi-link-45deg me-1"></i>Dasar Pengadaan (No. Pengajuan Disetujui) <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('pengajuan_id') is-invalid @enderror" id="pengajuan_select" name="pengajuan_id" required>
                        <option value="">-- Pilih Pengajuan Disetujui --</option>
                        @foreach($approvedPengajuan as $pgj)
                            <option value="{{ $pgj->id }}" {{ old('pengajuan_id') == $pgj->id ? 'selected' : '' }}>
                                {{ $pgj->ajuan_no }} — {{ $pgj->date->format('d M Y') }} ({{ $pgj->details->count() }} item, Estimasi: {{ $pgj->total_estimasi_formatted }})
                            </option>
                        @endforeach
                    </select>
                    @error('pengajuan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Wajib pilih pengajuan yang sudah disetujui pimpinan.</div>
                @else
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Belum ada pengajuan yang disetujui.</strong><br>
                            Silakan <a href="{{ route('admin.pengajuan.create') }}">buat pengajuan pembelian</a> terlebih dahulu, lalu minta persetujuan pimpinan.
                        </div>
                    </div>
                @endif
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

        {{-- Placeholder sebelum pengajuan dipilih (form baru saja) --}}
        @if(!$transaction)
        <div id="itemsPlaceholder" class="text-center text-muted py-5 border rounded mb-3 {{ (isset($approvedPengajuan) && $approvedPengajuan->count() > 0) ? '' : 'd-none' }}">
            <i class="bi bi-inbox display-4 d-block mb-2"></i>
            Pilih <strong>Dasar Pengadaan</strong> terlebih dahulu untuk mengisi item.
        </div>
        @endif

        <div class="table-responsive mb-3" id="itemsTableWrapper" style="{{ !$transaction ? 'display:none' : '' }}">
            <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40%">Sparepart <span class="text-danger">*</span></th>
                        <th style="width: 15%">Jumlah <span class="text-danger">*</span></th>
                        <th style="width: 15%">Satuan</th>
                        <th style="width: 20%">Harga Satuan <span class="text-danger">*</span></th>
                        <th style="width: 10%" class="action-col">Aksi</th>
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
                                <td class="text-center action-col">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-outline-success btn-sm mb-4" id="addItem">
            <i class="bi bi-plus-circle me-1"></i>Tambah Item
        </button>

        {{-- Submit --}}
        <div class="d-flex gap-2">
            @if(!$transaction && (!isset($approvedPengajuan) || $approvedPengajuan->count() == 0))
                <button type="submit" class="btn btn-primary" disabled><i class="bi bi-check-lg me-2"></i>Simpan</button>
            @else
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>{{ $transaction ? 'Update' : 'Simpan' }}</button>
            @endif
            <a href="{{ route('admin.barang-masuk') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set date & time dari device (form baru)
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

    const sparepartOptions = `<option value="">-- Pilih --</option>
        @foreach($spareparts as $sparepart)
            <option value="{{ $sparepart->id }}" data-unit="{{ $sparepart->unit }}">{{ $sparepart->code }} - {{ $sparepart->name }}</option>
        @endforeach`;

    const pengajuanSelect = document.getElementById('pengajuan_select');
    const itemsPlaceholder = document.getElementById('itemsPlaceholder');
    const itemsTableWrapper = document.getElementById('itemsTableWrapper');
    const addItemBtn = document.getElementById('addItem');

    // Lock semua field item (untuk pengajuan)
    function lockItemFields() {
        document.querySelectorAll('.sparepart-select').forEach(function(sel) {
            const val = sel.value;
            sel.setAttribute('disabled', true);
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = sel.name;
            hidden.value = val;
            sel.closest('td').appendChild(hidden);
        });
        document.querySelectorAll('.quantity-input, .price-input').forEach(function(inp) {
            inp.setAttribute('readonly', true);
        });
        document.querySelectorAll('.remove-row').forEach(function(btn) {
            btn.style.display = 'none';
        });
        document.querySelectorAll('.action-col').forEach(function(el) {
            el.style.display = 'none';
        });
        if (addItemBtn) addItemBtn.style.display = 'none';
    }

    // Auto-fill dari pengajuan
    if (pengajuanSelect) {
        pengajuanSelect.addEventListener('change', async function() {
            const pgjId = this.value;

            if (!pgjId) {
                if (itemsPlaceholder) itemsPlaceholder.style.display = '';
                if (itemsTableWrapper) itemsTableWrapper.style.display = 'none';
                return;
            }

            try {
                const resp = await fetch(`{{ url('/admin/barang-masuk/pengajuan') }}/${pgjId}`);
                const data = await resp.json();

                const tbody = document.getElementById('itemsBody');
                tbody.innerHTML = '';

                data.items.forEach(function(item, idx) {
                    const newRow = document.createElement('tr');
                    newRow.className = 'item-row';
                    newRow.innerHTML = `
                        <td>
                            <select class="form-select sparepart-select" name="items[${idx}][sparepart_id]" required>${sparepartOptions}</select>
                        </td>
                        <td><input type="number" class="form-control quantity-input" name="items[${idx}][quantity]" value="${item.quantity}" min="1" required></td>
                        <td><span class="unit-display form-control-plaintext">-</span></td>
                        <td><input type="number" class="form-control price-input" name="items[${idx}][price]" value="${item.price}" min="0" required></td>
                        <td class="text-center action-col"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                    `;
                    tbody.appendChild(newRow);

                    const select = newRow.querySelector('.sparepart-select');
                    select.value = item.sparepart_id;
                    select.dispatchEvent(new Event('change'));
                });

                rowIndex = data.items.length;

                // Tampilkan tabel, sembunyikan placeholder
                if (itemsPlaceholder) itemsPlaceholder.style.display = 'none';
                if (itemsTableWrapper) itemsTableWrapper.style.display = '';

                // Kunci field
                lockItemFields();

            } catch (e) {
                console.error('Gagal memuat detail pengajuan:', e);
            }
        });

        // Auto-trigger jika ada old value (validation error redirect)
        if (pengajuanSelect.value) {
            pengajuanSelect.dispatchEvent(new Event('change'));
        }
    }

    let rowIndex = {{ $transaction ? $transaction->details->count() : 0 }};

    // Add item row (hanya untuk edit mode)
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
            <td class="text-center action-col">
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
