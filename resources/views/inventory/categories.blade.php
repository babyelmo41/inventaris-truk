@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Data Kategori</h2>
            <div class="text-secondary">Pengelompokan sparepart berdasarkan jenis komponen.</div>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Tambah Kategori</a>
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
                    <th>Nama Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-center">Jumlah Sparepart</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td class="text-center">{{ $category->spareparts_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{$categories->links()}}
</div>
@endsection
