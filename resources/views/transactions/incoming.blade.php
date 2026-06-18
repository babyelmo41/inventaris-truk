@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Data Barang Masuk</h1>
            <p class="text-white-50 mb-0">Daftar transaksi penerimaan sparepart ke gudang.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.barang-masuk.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Tambah Barang Masuk</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Table --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-down me-2"></i>Daftar Barang Masuk</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px">No</th>
                        <th>Tanggal</th>
                        <th class="hide-md">Waktu</th>
                        <th>No Invoice</th>
                        <th class="hide-sm">Supplier</th>
                        <th>Sparepart</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end hide-md">Harga Satuan</th>
                        <th class="text-end hide-md">Subtotal</th>
                        <th class="hide-sm">User</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        @foreach($transaction->details as $detail)
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{ $transaction->details->count() }}" class="ps-4 text-muted hide-sm">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->parent->iteration }}</td>
                                    <td rowspan="{{ $transaction->details->count() }}">{{ $transaction->date->format('d M Y') }}</td>
                                    <td rowspan="{{ $transaction->details->count() }}" class="hide-md">{{ \Carbon\Carbon::parse($transaction->time)->format('H:i') }}</td>
                                    <td rowspan="{{ $transaction->details->count() }}">
                                        <span class="fw-semibold">{{ $transaction->invoice_no }}</span>
                                    </td>
                                    <td rowspan="{{ $transaction->details->count() }}" class="hide-sm">{{ $transaction->supplier->name }}</td>
                                @endif
                                <td>{{ $detail->sparepart->name }}</td>
                                <td class="text-center">{{ $detail->quantity }} {{ $detail->sparepart->unit }}</td>
                                <td class="text-end hide-md">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold hide-md">Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                                @if($loop->first)
                                    <td rowspan="{{ $transaction->details->count() }}" class="hide-sm">{{ $transaction->user->name }}</td>
                                    <td rowspan="{{ $transaction->details->count() }}" class="text-center pe-4">
                                        <a href="{{ route('admin.barang-masuk.edit', $transaction) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.barang-masuk.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi ini? Stok akan dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada transaksi barang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modern-card-footer">
        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
