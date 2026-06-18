@php
    $menu = [
        [
            'section' => 'Menu Utama',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'karyawan.dashboard'],
            ],
        ],
        [
            'section' => 'Permintaan',
            'items' => [
                ['label' => 'Buat Permintaan', 'icon' => 'bi-cart-plus', 'route' => 'karyawan.permintaan.create'],
                ['label' => 'Riwayat Permintaan', 'icon' => 'bi-clock-history', 'route' => 'karyawan.permintaan.index'],
            ],
        ],
        [
            'section' => 'Informasi',
            'items' => [
                ['label' => 'Katalog Sparepart', 'icon' => 'bi-box-seam', 'route' => 'karyawan.katalog'],
            ],
        ],
    ];
@endphp
<aside class="sidebar">
    <div class="brand-panel d-flex align-items-center gap-3">
        <img src="{{ asset('images/logo-chakra-jawara.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;border-radius:8px;border:2px solid #fff;box-shadow:0 0 0 2px rgba(255,255,255,.15);">
        <div>
            <div class="fw-bold text-white">Inventaris Truk</div>
            <div class="small text-secondary">Karyawan/Mekanik</div>
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
