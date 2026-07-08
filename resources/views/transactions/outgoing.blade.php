@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Data Barang Keluar</h1>
            <p class="text-white-50 mb-0">Daftar transaksi pengeluaran sparepart dari gudang.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('admin.barang-keluar.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-2"></i>Tambah Barang Keluar</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($pendingCount > 0)
<div class="alert alert-warning d-flex align-items-center justify-content-between mb-4">
    <div>
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>{{ $pendingCount }} permintaan</strong> dari karyawan menunggu diproses.
    </div>
</div>
@endif

{{-- Table --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-up me-2"></i>Daftar Barang Keluar</h6>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px">No</th>
                        <th>Tanggal</th>
                        <th class="hide-md">Waktu</th>
                        <th>No Referensi</th>
                        <th class="hide-sm">Tujuan</th>
                        <th>Sparepart</th>
                        <th class="text-center">Jumlah</th>
                        <th class="hide-sm">Foto Pengajuan</th>
                        <th>Status</th>
                        <th class="hide-md">Keterangan</th>
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
                                        <span class="fw-semibold">{{ $transaction->reference_no }}</span>
                                    </td>
                                    <td rowspan="{{ $transaction->details->count() }}" class="hide-sm">{{ $transaction->purpose }}</td>
                                @endif
                                <td>{{ $detail->sparepart->name }}</td>
                                <td class="text-center">{{ $detail->quantity }} {{ $detail->sparepart->unit }}</td>
                                <td class="hide-sm">
                                    @if($detail->before_photo)
                                        <a href="{{ $detail->before_photo_url }}" target="_blank">
                                            <img src="{{ $detail->before_photo_url }}" class="img-thumbnail" style="max-height: 50px; cursor: zoom-in;" title="Klik untuk perbesar">
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                @if($loop->first)
                                    <td rowspan="{{ $transaction->details->count() }}">
                                        @if($transaction->status === 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($transaction->status === 'processed')
                                            <span class="badge bg-info">Diproses</span>
                                            <div class="small text-muted mt-1">Pemasangan: {{ $transaction->completionProgress() }}</div>
                                        @elseif($transaction->status === 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($transaction->status === 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $transaction->details->count() }}" class="hide-md">
                                        {{ $transaction->notes ?? '-' }}
                                        @if($transaction->requester)
                                            <br><small class="text-muted">Diminta: {{ $transaction->requester->name }}</small>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $transaction->details->count() }}" class="text-center pe-4">
                                        {{-- Aksi berbeda berdasarkan sumber transaksi --}}
                                        @if($transaction->requester)
                                            {{-- Dari karyawan: Proses + Tolak --}}
                                            @if($transaction->status === 'pending')
                                                <form action="{{ route('admin.barang-keluar.process', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Proses permintaan ini? Stok akan dikurangi.')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Proses">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.barang-keluar.reject', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak permintaan ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            {{-- Input admin langsung: Edit + Hapus --}}
                                            <a href="{{ route('admin.barang-keluar.edit', $transaction) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.barang-keluar.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi ini? Stok akan dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada transaksi barang keluar.
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
