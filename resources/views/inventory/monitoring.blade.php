@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Monitoring Stok</h2>
            <div class="text-secondary">Pantau ketersediaan sparepart dan status stok minimum.</div>
        </div>
        <a href="{{ route('stock.monitoring') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise me-2"></i>Refresh</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="search" id="searchInput" class="form-control" placeholder="Cari barang (kode atau nama)...">
            </div>
        </div>
        <div class="col-md-5">
            <select id="categoryFilter" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0" id="monitoringTable">
            <thead class="table-light">
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th class="text-center">Stok Saat Ini</th>
                    <th class="text-center">Stok Minimum</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spareparts as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->supplier->name }}</td>
                        <td class="text-center">{{ $item->stock }} {{ $item->unit }}</td>
                        <td class="text-center">{{ $item->min_stock }}</td>
                        <td class="text-center">
                            @if($item->stock <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($item->stock <= $item->min_stock)
                                <span class="badge bg-warning text-dark">Hampir Habis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">Tidak ada data sparepart.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 text-secondary small">
        <i class="bi bi-info-circle me-1"></i>
        Menampilkan <span id="visibleCount">{{ $spareparts->count() }}</span> dari {{ $spareparts->count() }} sparepart
    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const table = document.getElementById('monitoringTable');
    const visibleCount = document.getElementById('visibleCount');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        let count = 0;

        rows.forEach(row => {
            const code = row.cells[0]?.textContent.toLowerCase() || '';
            const name = row.cells[1]?.textContent.toLowerCase() || '';
            const cat = row.cells[2]?.textContent.toLowerCase() || '';

            const matchSearch = code.includes(searchText) || name.includes(searchText);
            const matchCategory = !category || cat === category;

            if (matchSearch && matchCategory) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        visibleCount.textContent = count;
    }

    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);
</script>
@endpush

<div class="d-flex justify-content-center mt-4">
    {{$spareparts->links()}}
</div>
@endsection
