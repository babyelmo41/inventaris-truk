@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Data Barang Keluar</h2>
            <div class="text-secondary">Daftar transaksi pengeluaran sparepart dari gudang.</div>
        </div>
        <a href="{{ route('admin.barang-keluar.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Barang Keluar</a>
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
                    <th>Tanggal</th>
                    <th>No Referensi</th>
                    <th>Tujuan</th>
                    <th>Sparepart</th>
                    <th class="text-center">Jumlah Keluar</th>
                    <th>Keterangan</th>
                    <th>User</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    @foreach($transaction->details as $detail)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $transaction->details->count() }}">{{ $loop->parent->iteration }}</td>
                                <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->date->format('d M Y') }}</td>
                                <td rowspan="{{ $transaction->details->count() }}">
                                    <span class="fw-semibold">{{ $transaction->reference_no }}</span>
                                </td>
                                <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->purpose }}</td>
                            @endif
                            <td>{{ $detail->sparepart->name }}</td>
                            <td class="text-center">{{ $detail->quantity }} {{ $detail->sparepart->unit }}</td>
                            @if($loop->first)
                                <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->notes ?? '-' }}</td>
                                <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->user->name }}</td>
                                <td rowspan="{{ $transaction->details->count() }}" class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.barang-keluar.edit', $transaction) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.barang-keluar.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin hapus transaksi ini? Stok akan dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-4">Belum ada transaksi barang keluar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
