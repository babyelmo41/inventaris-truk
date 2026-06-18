@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">Input hasil hitung fisik sparepart per kelompok (cycle counting).</div>
        </div>
        <a href="{{ route('admin.stock-opname.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.stock-opname.store') }}" method="POST" id="opnameForm">
        @csrf

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <label for="opname_no" class="form-label fw-semibold">No Opname <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('opname_no') is-invalid @enderror" id="opname_no" name="opname_no" value="{{ old('opname_no', $generatedOpnameNo ?? '') }}" readonly required>
                @error('opname_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="cycle_month" class="form-label fw-semibold">Bulan Siklus <span class="text-danger">*</span></label>
                <input type="month" class="form-control @error('cycle_month') is-invalid @enderror" id="cycle_month" name="cycle_month" value="{{ old('cycle_month', date('Y-m')) }}" required>
                @error('cycle_month')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="cycle_group" class="form-label fw-semibold">Kelompok <span class="text-danger">*</span></label>
                <select class="form-select @error('cycle_group') is-invalid @enderror" id="cycle_group" name="cycle_group" required>
                    <option value="">-- Pilih --</option>
                    <option value="A">Kelompok A</option>
                    <option value="B">Kelompok B</option>
                    <option value="C">Kelompok C</option>
                    <option value="D">Kelompok D</option>
                </select>
                @error('cycle_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <label for="notes" class="form-label fw-semibold">Catatan</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Hasit Hitung Fisik</h5>

        @error('items')<div class="alert alert-danger">{{ $message }}</div>@enderror

        <div class="table-responsive mb-3">
            <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 35%">Sparepart <span class="text-danger">*</span></th>
                        <th style="width: 15%">Stok Sistem</th>
                        <th style="width: 15%">Stok Fisik <span class="text-danger">*</span></th>
                        <th style="width: 10%">Selisih</th>
                        <th style="width: 15%">Catatan</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <tr class="item-row">
                        <td>
                            <select class="form-select sparepart-select" name="items[0][sparepart_id]" required>
                                <option value="">-- Pilih --</option>
                                @foreach($spareparts as $sp)
                                    <option value="{{ $sp->id }}" data-stock="{{ $sp->stock }}">{{ $sp->code }} - {{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><span class="system-stock form-control-plaintext text-muted">-</span></td>
                        <td><input type="number" class="form-control physical-stock-input" name="items[0][physical_stock]" value="0" min="0" required></td>
                        <td><span class="discrepancy-display form-control-plaintext fw-bold">-</span></td>
                        <td><input type="text" class="form-control" name="items[0][notes]" placeholder="Opsional"></td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-outline-success btn-sm mb-4" id="addItem">
            <i class="bi bi-plus-circle me-1"></i>Tambah Item
        </button>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Submit Stock Opname</button>
            <a href="{{ route('admin.stock-opname.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    const sparepartOptions = `<option value="">-- Pilih --</option>
        @foreach($spareparts as $sp)\n            <option value="{{ $sp->id }}" data-stock="{{ $sp->stock }}">{{ $sp->code }} - {{ $sp->name }}</option>
        @endforeach`;

    document.getElementById('addItem').addEventListener('click', function() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td><select class="form-select sparepart-select" name="items[${rowIndex}][sparepart_id]" required>${sparepartOptions}</select></td>
            <td><span class="system-stock form-control-plaintext text-muted">-</span></td>
            <td><input type="number" class="form-control physical-stock-input" name="items[${rowIndex}][physical_stock]" value="0" min="0" required></td>
            <td><span class="discrepancy-display form-control-plaintext fw-bold">-</span></td>
            <td><input type="text" class="form-control" name="items[${rowIndex}][notes]" placeholder="Opsional"></td>
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

    function updateRow(row) {
        const select = row.querySelector('.sparepart-select');
        const option = select.options[select.selectedIndex];
        const systemStock = parseInt(option.dataset.stock) || 0;
        const physicalStock = parseInt(row.querySelector('.physical-stock-input').value) || 0;
        const discrepancy = physicalStock - systemStock;

        row.querySelector('.system-stock').textContent = systemStock + ' pcs';
        const discDisplay = row.querySelector('.discrepancy-display');
        discDisplay.textContent = (discrepancy > 0 ? '+' : '') + discrepancy;
        discDisplay.style.color = discrepancy < 0 ? '#dc3545' : (discrepancy > 0 ? '#0d6efd' : '#198754');
    }

    document.getElementById('itemsBody').addEventListener('change', function(e) {
        const row = e.target.closest('.item-row');
        if (row) updateRow(row);
    });
    document.getElementById('itemsBody').addEventListener('input', function(e) {
        const row = e.target.closest('.item-row');
        if (row && e.target.classList.contains('physical-stock-input')) updateRow(row);
    });
    // Initialize first row
    const firstRow = document.querySelector('.item-row');
    if (firstRow) updateRow(firstRow);
});
</script>
@endsection
