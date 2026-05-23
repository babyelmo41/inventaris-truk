@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Data Sparepart</h2>
            <div class="text-secondary">Daftar seluruh sparepart truk yang tersedia.</div>
        </div>
        <a href="{{ route('admin.spareparts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Tambah Sparepart</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Sparepart</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spareparts as $sparepart)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $sparepart->code }}</td>
                        <td>{{ $sparepart->name }}</td>
                        <td>{{ $sparepart->category->name }}</td>
                        <td>{{ $sparepart->supplier->name }}</td>
                        <td class="text-center">{{ $sparepart->stock }} {{ $sparepart->unit }}</td>
                        <td class="text-center">{{ $sparepart->min_stock }}</td>
                        <td class="text-center">
                            @if($sparepart->stock <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($sparepart->stock <= $sparepart->min_stock)
                                <span class="badge bg-warning text-dark">Hampir Habis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.spareparts.edit', $sparepart) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('admin.spareparts.destroy', $sparepart) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus sparepart ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-4">Belum ada data sparepart.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{$spareparts->links()}}
</div>
@endsection
