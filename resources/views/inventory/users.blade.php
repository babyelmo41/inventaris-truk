@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Data User</h1>
            <p class="text-white-50 mb-0">Daftar pengguna sistem inventaris.</p>
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
        <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Daftar Pengguna</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 text-muted">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>
                                @if($user['role'] === 'admin_gudang')
                                    <span class="badge-status aman">Admin Gudang</span>
                                @else
                                    <span class="badge-status hampir-habis">Pimpinan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modern-card-footer">
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
