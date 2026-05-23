@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Data Barang Masuk</h2>
            <div class="text-secondary">Daftar transaksi penerimaan sparepart ke gudang.</div>
        </div>
        <a href="{{ route('admin.barang-masuk.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Barang Masuk</a>
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
                    <th>No Invoice</th>
                    <th>Supplier</th>
                    <th>Sparepart</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Subtotal</th>
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
                                    <span class="fw-semibold">{{ $transaction->invoice_no }}</span>
                                </td>
                                <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->supplier->name }}</td>
                            @endif
                            <td>{{ $detail->sparepart->name }}</td>
                            <td class="text-center">{{ $detail->quantity }} {{ $detail->sparepart->unit }}</td>
                            <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                            @if($loop->first)
                                <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->user->name }}</td>
                                <td rowspan="{{ $transaction->details->count() }}" class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.barang-masuk.edit', $transaction) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.barang-masuk.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin hapus transaksi ini? Stok akan dikembalikan.')">
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
                        <td colspan="10" class="text-center text-secondary py-4">Belum ada transaksi barang masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
