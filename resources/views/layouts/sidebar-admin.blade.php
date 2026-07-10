@php
    $stokMenipis = App\Models\Sparepart::whereColumn('stock', '<=', 'min_stock')->count();
    $permintaanPending = App\Models\BarangKeluar::where('status', 'pending')->count();
    $pengajuanPending = App\Models\PengajuanPembelian::where('status', 'pending')->count();

    $menu = [
        [
            'section' => 'Menu Utama',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'admin.dashboard'],
            ],
        ],
        [
            'section' => 'Master Data',
            'items' => [
                ['label' => 'Data User', 'icon' => 'bi-people', 'route' => 'admin.users.index'],
                ['label' => 'Data Kategori', 'icon' => 'bi-tags', 'route' => 'admin.categories.index'],
                ['label' => 'Data Supplier', 'icon' => 'bi-truck', 'route' => 'admin.suppliers.index'],
                ['label' => 'Data Sparepart', 'icon' => 'bi-boxes', 'route' => 'admin.spareparts.index'],
            ],
        ],
        [
            'section' => 'Transaksi',
            'items' => [
                ['label' => 'Barang Masuk', 'icon' => 'bi-box-arrow-in-down', 'route' => 'admin.barang-masuk'],
                ['label' => 'Barang Keluar', 'icon' => 'bi-box-arrow-up', 'route' => 'admin.barang-keluar', 'badge' => $permintaanPending ?: null, 'badge_color' => 'warning', 'badge_id' => 'badge-permintaan'],
            ],
        ],
        [
            'section' => 'Persetujuan',
            'items' => [
                ['label' => 'Pengajuan Pembelian', 'icon' => 'bi-cart-plus', 'route' => 'admin.pengajuan.index', 'badge' => $pengajuanPending ?: null, 'badge_color' => 'warning', 'badge_id' => 'badge-pengajuan'],
                ['label' => 'Stock Opname', 'icon' => 'bi-clipboard-check', 'route' => 'admin.stock-opname.index'],
            ],
        ],
        [
            'section' => 'Informasi',
            'items' => [
                ['label' => 'Monitoring Stok', 'icon' => 'bi-clipboard-data', 'route' => 'stock.monitoring', 'badge' => $stokMenipis ?: null, 'badge_color' => 'danger'],
                ['label' => 'Laporan', 'icon' => 'bi-file-earmark-bar-graph', 'route' => 'admin.reports.index'],
            ],
        ],
    ];
@endphp
<aside class="sidebar">
    <div class="brand-panel d-flex align-items-center gap-3">
        <img src="{{ asset('images/logo-chakra-jawara.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;border-radius:8px;border:2px solid #fff;box-shadow:0 0 0 2px rgba(255,255,255,.15);">
        <div>
            <div class="fw-bold text-white">Inventaris Truk</div>
            <div class="small text-secondary">Admin Gudang</div>
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
                            <span class="badge rounded-pill bg-{{ $item['badge_color'] ?? 'danger' }} ms-auto" id="{{ $item['badge_id'] ?? '' }}">{{ $item['badge'] }}</span>
                        @elseif(!empty($item['badge_id']))
                            <span class="badge rounded-pill bg-{{ $item['badge_color'] ?? 'danger' }} ms-auto" id="{{ $item['badge_id'] }}" style="display:none;">0</span>
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
