@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Data User</h2>
            <div class="text-secondary">Akun pengguna sistem berdasarkan role.</div>
        </div>
        <button class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Tambah User</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] }}</td>
                        <td><span class="badge {{ $user['status'] === 'Aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $user['status'] }}</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
