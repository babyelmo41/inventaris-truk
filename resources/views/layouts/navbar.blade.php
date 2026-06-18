@php
    $user = session('auth_user');
@endphp
<nav class="topbar d-flex align-items-center justify-content-between px-4">
    <div class="d-flex align-items-center gap-2">
        <button class="btn d-lg-none p-1 me-1" id="sidebarToggle" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>
        <img src="{{ asset('images/logo-chakra-jawara.png') }}" alt="Logo" style="width:36px;height:36px;object-fit:contain;" class="d-none d-sm-block">
        <div>
            <div class="text-uppercase text-secondary fw-semibold small">PT. Chakra Jawara Kabupaten Banjar</div>
            <h1 class="h4 mb-0 fw-bold">{{ $title ?? 'Dashboard' }}</h1>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="badge text-bg-light border px-3 py-2">{{ $user['role_label'] ?? 'User' }}</span>
        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold">{{ $user['name'] ?? 'Pengguna' }}</div>
            <div class="small text-secondary">{{ $user['email'] ?? '' }}</div>
        </div>
    </div>
</nav>
