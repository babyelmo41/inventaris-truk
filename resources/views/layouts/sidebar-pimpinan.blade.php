@php
    $items = [
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'pimpinan.dashboard'],
        ['label' => 'Monitoring Stok', 'icon' => 'bi-clipboard-data', 'route' => 'stock.monitoring'],
        ['label' => 'Laporan', 'icon' => 'bi-file-earmark-bar-graph', 'route' => 'pimpinan.reports.index'],
    ];
@endphp
<aside class="sidebar">
    <div class="brand-panel d-flex align-items-center gap-3">
        <span class="brand-mark"><i class="bi bi-clipboard2-data text-white fs-5"></i></span>
        <div>
            <div class="fw-bold text-white">Inventaris Truk</div>
            <div class="small text-secondary">Pimpinan</div>
        </div>
    </div>
    <div class="py-3">
        <div class="px-4 pb-2 small text-uppercase text-secondary fw-semibold">Menu Utama</div>
        <nav class="nav flex-column">
            @foreach($items as $item)
                <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route'], $item['params'] ?? []) }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
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
