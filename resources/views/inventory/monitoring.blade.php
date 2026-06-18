@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">Monitoring Stok</h1>
            <p class="text-white-50 mb-0">Pantau ketersediaan sparepart dan status stok minimum.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('stock.monitoring') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise me-2"></i>Refresh</a>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="modern-card mb-4">
    <div class="modern-card-body">
        <div class="row g-3">
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
    </div>
</div>

{{-- Table --}}
<div class="modern-card">
    <div class="modern-card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="bi bi-clipboard-data me-2"></i>Data Stok</h6>
        <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold" id="visibleCount">{{ $spareparts->count() }} item</span>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="monitoringTable">
                <thead>
                    <tr>
                        <th class="ps-4 hide-md">Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="hide-md">Kategori</th>
                        <th class="hide-sm">Supplier</th>
                        <th class="text-center">Stok Saat Ini</th>
                        <th class="text-center hide-md">Stok Minimum</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spareparts as $item)
                        <tr>
                            <td class="ps-4 fw-semibold hide-md">{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="hide-md">{{ $item->category->name }}</td>
                            <td class="hide-sm">{{ $item->supplier->name }}</td>
                            <td class="text-center">{{ $item->stock }} {{ $item->unit }}</td>
                            <td class="text-center hide-md">{{ $item->min_stock }}</td>
                            <td class="text-center">
                                @if($item->stock <= 0)
                                    <span class="badge-status habis"><i class="bi bi-x-circle me-1"></i>Habis</span>
                                @elseif($item->stock <= $item->min_stock)
                                    <span class="badge-status hampir-habis"><i class="bi bi-exclamation-triangle me-1"></i>Hampir Habis</span>
                                @else
                                    <span class="badge-status aman"><i class="bi bi-check-circle me-1"></i>Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data sparepart.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modern-card-footer">
        <i class="bi bi-info-circle me-1"></i>
        Menampilkan <span id="visibleCountFooter">{{ $spareparts->count() }}</span> dari {{ $spareparts->count() }} sparepart
    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const table = document.getElementById('monitoringTable');
    const visibleCount = document.getElementById('visibleCount');
    const visibleCountFooter = document.getElementById('visibleCountFooter');

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

        visibleCount.textContent = count + ' item';
        visibleCountFooter.textContent = count;
    }

    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);
</script>
@endpush
@endsection
