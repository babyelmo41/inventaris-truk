@php
    $items = [
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'admin.dashboard'],
        ['label' => 'Monitoring Stok', 'icon' => 'bi-clipboard-data', 'route' => 'stock.monitoring'],
        ['label' => 'Data User', 'icon' => 'bi-people', 'route' => 'admin.users.index'],
        ['label' => 'Data Kategori', 'icon' => 'bi-tags', 'route' => 'admin.categories.index'],
        ['label' => 'Data Supplier', 'icon' => 'bi-truck', 'route' => 'admin.suppliers.index'],
        ['label' => 'Data Sparepart', 'icon' => 'bi-boxes', 'route' => 'admin.spareparts.index'],
        ['label' => 'Barang Masuk', 'icon' => 'bi-box-arrow-in-down', 'route' => 'admin.barang-masuk'],
        ['label' => 'Barang Keluar', 'icon' => 'bi-box-arrow-up', 'route' => 'admin.barang-keluar'],
        ['label' => 'Laporan', 'icon' => 'bi-file-earmark-bar-graph', 'route' => 'admin.reports.index'],
    ];
    $stokMenipis = App\Models\Sparepart::whereColumn('stock', '<=', 'min_stock')->count();
@endphp
<aside class="sidebar">
    <div class="brand-panel d-flex align-items-center gap-3">
        <span class="brand-mark"><i class="bi bi-tools text-white fs-5"></i></span>
        <div>
            <div class="fw-bold text-white">Inventaris Truk</div>
            <div class="small text-secondary">Admin Gudang</div>
        </div>
    </div>
    <div class="py-3">
        <div class="px-4 pb-2 small text-uppercase text-secondary fw-semibold">Menu Utama</div>
        <nav class="nav flex-column">
            @foreach($items as $item)
                <a class="nav-link {{ request()->routeIs($item['route'] . '*') ? 'active' : '' }}" href="{{ route($item['route'], $item['params'] ?? []) }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                    @if($item['route'] === 'stock.monitoring' && $stokMenipis > 0)
                        <span class="badge rounded-pill bg-danger ms-auto">{{ $stokMenipis }}</span>
                    @endif
                </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}" class="mt-2 px-3">
                @csrf
                <button class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </div>
</aside>
