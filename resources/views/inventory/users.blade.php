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
                        <th class="ps-4 hide-sm" style="width:50px">No</th>
                        <th>Nama</th>
                        <th class="hide-md">Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center" style="width:200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $user->is_active ? '' : 'table-light opacity-75' }}">
                            <td class="ps-4 text-muted hide-sm">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold">
                                {{ $user->name }}
                                @if(! $user->is_active)
                                    <span class="badge bg-secondary ms-1" style="font-size:0.65rem">Nonaktif</span>
                                @endif
                            </td>
                            <td class="hide-md">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge-status aman">Admin Gudang</span>
                                @elseif($user->role === 'pimpinan')
                                    <span class="badge-status hampir-habis">Pimpinan</span>
                                @else
                                    <span class="badge-status habis">Karyawan/Mekanik</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Ubah Password --}}
                                    <a href="{{ route('admin.users.reset-password', $user) }}" class="btn btn-sm btn-outline-warning" title="Ubah Password">
                                        <i class="bi bi-key"></i>
                                    </a>

                                    {{-- Toggle Status --}}
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user {{ $user->name }}?')">
                                        @csrf
                                        @if($user->is_active)
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Nonaktifkan">
                                                <i class="bi bi-person-dash"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Aktifkan">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
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
