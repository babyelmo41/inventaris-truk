@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
            <div class="text-secondary">{{ $sparepart ? 'Edit data sparepart.' : 'Tambah sparepart baru.' }}</div>
        </div>
        <a href="{{ route('admin.spareparts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
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

    <form action="{{ $sparepart ? route('admin.spareparts.update', $sparepart) : route('admin.spareparts.store') }}" method="POST">
        @csrf
        @if($sparepart)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="code" class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $sparepart->code ?? $generatedCode ?? '') }}" {{ $sparepart ? '' : 'readonly' }} required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label fw-semibold">Nama Sparepart <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $sparepart->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $sparepart->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="supplier_id" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $sparepart->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="min_stock" class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('min_stock') is-invalid @enderror" id="min_stock" name="min_stock" value="{{ old('min_stock', $sparepart->min_stock ?? 0) }}" min="0" required>
                @error('min_stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="unit" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                    <option value="pcs" {{ old('unit', $sparepart->unit ?? 'pcs') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    <option value="set" {{ old('unit', $sparepart->unit ?? '') == 'set' ? 'selected' : '' }}>Set</option>
                    <option value="liter" {{ old('unit', $sparepart->unit ?? '') == 'liter' ? 'selected' : '' }}>Liter</option>
                    <option value="roll" {{ old('unit', $sparepart->unit ?? '') == 'roll' ? 'selected' : '' }}>Roll</option>
                </select>
                @error('unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if(!$sparepart)
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Stok awal akan dimulai dari <strong>0</strong>. Stok akan bertambah otomatis saat Anda input Barang Masuk.
            </div>
        @endif

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>{{ $sparepart ? 'Update' : 'Simpan' }}</button>
            <a href="{{ route('admin.spareparts.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
