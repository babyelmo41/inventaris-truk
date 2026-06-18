@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Katalog Sparepart</h1>
            <p class="text-white-50 mb-0">Daftar sparepart yang tersedia di gudang (read only).</p>
        </div>
    </div>
</div>

<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Sparepart Tersedia</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 hide-sm">No</th>
                        <th class="hide-md">Kode</th>
                        <th>Nama</th>
                        <th class="hide-md">Kategori</th>
                        <th class="hide-sm">Supplier</th>
                        <th class="text-center">Stok</th>
                        <th class="hide-sm">Satuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($spareparts as $i => $sp)
                        <tr>
                            <td class="ps-4 text-muted hide-sm">{{ $i + 1 }}</td>
                            <td class="hide-md"><span class="fw-semibold">{{ $sp->code }}</span></td>
                            <td>{{ $sp->name }}</td>
                            <td class="hide-md">{{ $sp->category->name ?? '-' }}</td>
                            <td class="hide-sm">{{ $sp->supplier->name ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $sp->stock }}</td>
                            <td class="hide-sm">{{ $sp->unit }}</td>
                            <td>
                                @if($sp->stock <= 0)
                                    <span class="badge-status habis">Habis</span>
                                @elseif($sp->stock <= $sp->min_stock)
                                    <span class="badge-status hampir-habis">Stok Menipis</span>
                                @else
                                    <span class="badge-status aman">Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
