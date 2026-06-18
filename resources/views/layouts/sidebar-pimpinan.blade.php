@php
    $stokMenipis = App\Models\Sparepart::whereColumn('stock', '<=', 'min_stock')->count();
    $pengajuanPending = App\Models\PengajuanPembelian::where('status', 'pending')->count();
    $opnamePending = App\Models\StockOpname::whereIn('status', ['draft', 'submitted'])->count();

    $menu = [
        [
            'section' => 'Menu Utama',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'pimpinan.dashboard'],
            ],
        ],
        [
            'section' => 'Persetujuan',
            'items' => [
                ['label' => 'Pengajuan Pembelian', 'icon' => 'bi-cart-plus', 'route' => 'pimpinan.pengajuan.index', 'badge' => $pengajuanPending ?: null, 'badge_color' => 'warning'],
                ['label' => 'Stock Opname', 'icon' => 'bi-clipboard-check', 'route' => 'pimpinan.stock-opname.index', 'badge' => $opnamePending ?: null, 'badge_color' => 'warning'],
            ],
        ],
        [
            'section' => 'Informasi',
            'items' => [
                ['label' => 'Monitoring Stok', 'icon' => 'bi-clipboard-data', 'route' => 'stock.monitoring', 'badge' => $stokMenipis ?: null, 'badge_color' => 'danger'],
                ['label' => 'Laporan', 'icon' => 'bi-file-earmark-bar-graph', 'route' => 'pimpinan.reports.index'],
            ],
        ],
    ];
@endphp
<aside class="sidebar">
    <div class="brand-panel d-flex align-items-center gap-3">
        <img src="{{ asset('images/logo-chakra-jawara.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;border-radius:8px;border:2px solid #fff;box-shadow:0 0 0 2px rgba(255,255,255,.15);">
        <div>
            <div class="fw-bold text-white">Inventaris Truk</div>
            <div class="small text-secondary">Pimpinan</div>
        </div>
    </div>
    <div class="sidebar-nav py-3">
        @foreach($menu as $group)
            <div class="sidebar-section-label px-4 pt-3 pb-1">{{ $group['section'] }}</div>
            <nav class="nav flex-column">
                @foreach($group['items'] as $item)
                    <a class="nav-link {{ request()->routeIs($item['route'] . '*') ? 'active' : '' }}" href="{{ route($item['route'], $item['params'] ?? []) }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="badge rounded-pill bg-{{ $item['badge_color'] ?? 'danger' }} ms-auto">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        @endforeach
    </div>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
