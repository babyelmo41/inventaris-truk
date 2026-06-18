@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Data Kategori</h1>
            <p class="text-white-50 mb-0">Kelola kategori sparepart truk.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Tambah Kategori</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-tags me-2"></i>Daftar Kategori</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 hide-sm" style="width:50px">No</th>
                        <th>Nama Kategori</th>
                        <th class="hide-md">Deskripsi</th>
                        <th class="text-center">Jml. SP</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="ps-4 text-muted hide-sm">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="hide-md">{{ $category->description ?? '-' }}</td>
                            <td class="text-center">{{ $category->spareparts_count }}</td>
                            <td class="text-end pe-4">
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
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modern-card-footer">
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
