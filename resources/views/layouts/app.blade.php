@php
    $user = session('auth_user');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Inventaris Truk' }} | PT. Chakra Jawara</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 276px;
            --brand-blue: #1d4ed8;
            --brand-ink: #172033;
            --page-bg: #f4f7fb;
            --line: #dbe3ef;
        }

        body {
            background: var(--page-bg);
            color: var(--brand-ink);
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            background: #111827;
            color: #e5e7eb;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            z-index: 1030;
            transition: left .3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-nav {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .15);
            border-radius: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, .25);
        }

        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: .75rem;
        }

        .brand-panel {
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            padding: 1.25rem;
        }

        .brand-mark {
            align-items: center;
            background: #2563eb;
            border-radius: 8px;
            display: inline-flex;
            height: 42px;
            justify-content: center;
            width: 42px;
        }

        .sidebar .nav-link {
            align-items: center;
            border-radius: 8px;
            color: #cbd5e1;
            display: flex;
            gap: .7rem;
            margin: .12rem .75rem;
            padding: .72rem .85rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(37, 99, 235, .18);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: rgba(37, 99, 235, .25);
            border-left: 3px solid #60a5fa;
            padding-left: calc(.85rem - 3px);
        }

        .sidebar-section-label {
            color: #64748b;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .sidebar .nav-link .badge {
            font-size: .68rem;
            min-width: 1.6em;
        }

        .main-area {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left .3s ease;
        }

        .topbar {
            background: rgba(255, 255, 255, .96);
            border-bottom: 1px solid var(--line);
            min-height: 72px;
        }

        .content-wrap {
            padding: 1.5rem;
        }

        .metric-card,
        .panel-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, .05);
        }

        .metric-icon {
            align-items: center;
            border-radius: 8px;
            display: inline-flex;
            font-size: 1.35rem;
            height: 46px;
            justify-content: center;
            width: 46px;
        }

        .chart-placeholder {
            background:
                linear-gradient(180deg, rgba(29, 78, 216, .08), rgba(29, 78, 216, 0)),
                repeating-linear-gradient(90deg, transparent 0 52px, rgba(148, 163, 184, .12) 52px 53px);
            border: 1px dashed #b6c4d8;
            border-radius: 8px;
            min-height: 280px;
            overflow: hidden;
            position: relative;
        }

        .chart-line {
            background: linear-gradient(90deg, #2563eb, #16a34a, #f59e0b);
            border-radius: 999px;
            height: 5px;
            left: 8%;
            position: absolute;
            right: 8%;
            top: 52%;
            transform: rotate(-5deg);
        }

        .table > :not(caption) > * > * {
            padding: .82rem .9rem;
            vertical-align: middle;
        }

        .btn {
            border-radius: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        /* ============================================
           GLOBAL: Dark Header, Card, Table Styles
           ============================================ */

        /* Dark Header */
        .page-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .page-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            right: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Modern Card */
        .modern-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .modern-card-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .modern-card-body {
            padding: 1.5rem;
        }
        .modern-card-footer {
            background: #f8fafc;
            padding: 0.75rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Modern Table inside card */
        .modern-card table thead tr {
            background: #f1f5f9;
        }
        .modern-card table thead th {
            font-weight: 600;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .modern-card table tbody td {
            padding: 0.8rem 1rem;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .modern-card table tbody tr:hover {
            background: #f8fafc;
        }

        /* Badge Status Pills */
        .badge-status {
            padding: 0.4em 0.8em;
            font-size: 0.78rem;
            border-radius: 50px;
            font-weight: 600;
        }
        .badge-status.aman {
            background: #dcfce7;
            color: #16a34a;
        }
        .badge-status.hampir-habis {
            background: #fef9c3;
            color: #ca8a04;
        }
        .badge-status.habis {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Report Icon */
        .report-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* Report header & card responsive */
        .report-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .report-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.08);
        }

        .report-card-body {
            padding: 1.5rem;
        }

        .report-card-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .report-card-footer {
            background: #f8fafc;
            padding: 0.75rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85rem;
        }

        .report-card table thead tr {
            background: #f1f5f9;
        }

        .report-card table thead th {
            font-weight: 600;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .report-card table tbody td {
            padding: 0.8rem 1rem;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .report-card table tbody tr:hover {
            background: #f8fafc;
        }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 1035;
            opacity: 0;
            transition: opacity .3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -280px;
                z-index: 1040;
            }

            .sidebar.open {
                left: 0;
            }

            .main-area {
                margin-left: 0;
            }

            .content-wrap {
                padding: 1rem .75rem;
            }

            .topbar {
                min-height: 56px;
                padding: .5rem .75rem !important;
            }

            .topbar h1.h4 {
                font-size: 1.1rem;
            }

            .topbar .text-uppercase.small {
                font-size: .7rem;
            }

            .page-header {
                padding: 1.25rem;
                border-radius: 12px;
            }

            .page-header .h3 {
                font-size: 1.15rem;
            }

            .page-header p {
                font-size: .82rem;
            }

            /* Responsive tables: hide less important columns */
            .hide-md {
                display: none !important;
            }

            .table > :not(caption) > * > * {
                padding: .6rem .5rem;
                font-size: .85rem;
            }

            /* Modern card adjustments */
            .modern-card-body {
                padding: 1rem;
            }

            .modern-card-header {
                padding: .75rem 1rem;
            }

            /* Stat cards: 2 columns on tablet */
            .metric-card .display-6 {
                font-size: 1.5rem;
            }

            /* Filter row stacking */
            .filter-row .col-md-7,
            .filter-row .col-md-5 {
                margin-bottom: .5rem;
            }

            /* Report pages responsive */
            .report-header {
                padding: 1.25rem;
                border-radius: 12px;
            }

            .report-header .h3 {
                font-size: 1.15rem;
            }

            .report-header p {
                font-size: .82rem;
            }

            .report-card-body {
                padding: 1rem;
            }

            .report-card-header {
                padding: .75rem 1rem;
            }

            /* Panel card responsive */
            .panel-card {
                padding: 1.25rem !important;
            }

            .panel-card .h5 {
                font-size: 1.1rem;
            }
        }

        /* Extra small phones (< 576px) */
        @media (max-width: 575.98px) {
            .content-wrap {
                padding: .75rem .5rem;
            }

            .page-header {
                padding: 1rem;
                border-radius: 10px;
                margin-bottom: 1rem;
            }

            .page-header .h3 {
                font-size: 1rem;
            }

            .page-header .btn {
                font-size: .8rem;
                padding: .35rem .75rem;
            }

            .metric-card {
                padding: .75rem !important;
            }

            .metric-card .display-6 {
                font-size: 1.3rem;
            }

            .metric-icon {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
            }

            .topbar .badge {
                font-size: .7rem;
                padding: .3em .6em;
            }

            /* Hide more columns on very small screens */
            .hide-sm {
                display: none !important;
            }

            .modern-card-footer {
                padding: .5rem 1rem;
                font-size: .78rem;
            }

            /* Report pages extra small */
            .report-header {
                padding: 1rem;
                border-radius: 10px;
                margin-bottom: 1rem;
            }

            .report-header .h3 {
                font-size: 1rem;
            }

            .report-card-body {
                padding: .75rem;
            }

            .report-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            /* Panel card extra small */
            .panel-card {
                padding: 1rem !important;
            }

            .panel-card .h5 {
                font-size: 1rem;
            }

            .panel-card .h2 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @if(($user['role'] ?? null) === 'pimpinan')
        @include('layouts.sidebar-pimpinan')
    @elseif(($user['role'] ?? null) === 'karyawan')
        @include('layouts.sidebar-karyawan')
    @else
        @include('layouts.sidebar-admin')
    @endif

    <main class="main-area">
        @include('layouts.navbar')
        <div class="content-wrap">
            {{-- Auto Breadcrumb --}}
            @php
                $segments = request()->segments();
                $breadcrumbMap = [
                    'admin' => 'Admin',
                    'karyawan' => 'Karyawan',
                    'pimpinan' => 'Pimpinan',
                    'dashboard' => 'Dashboard',
                    'sparepart' => 'Sparepart',
                    'kategori' => 'Kategori',
                    'supplier' => 'Supplier',
                    'user' => 'User',
                    'barang-masuk' => 'Barang Masuk',
                    'barang-keluar' => 'Barang Keluar',
                    'pengajuan' => 'Pengajuan Pembelian',
                    'stock-opname' => 'Stock Opname',
                    'monitoring-stok' => 'Monitoring Stok',
                    'laporan' => 'Laporan',
                    'permintaan' => 'Permintaan',
                    'katalog' => 'Katalog',
                    'create' => 'Tambah',
                    'edit' => 'Edit',
                ];
            @endphp
            @if(count($segments) > 1)
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none"><i class="bi bi-house"></i></a></li>
                    @foreach($segments as $i => $segment)
                        @if($i < count($segments) - 1)
                            <li class="breadcrumb-item">
                                <a href="{{ url(implode('/', array_slice($segments, 0, $i + 1))) }}" class="text-decoration-none">
                                    {{ $breadcrumbMap[$segment] ?? ucfirst(str_replace('-', ' ', $segment)) }}
                                </a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $breadcrumbMap[$segment] ?? ucfirst(str_replace('-', ' ', $segment)) }}
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            @endif
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mobile sidebar toggle
const sidebar = document.querySelector('.sidebar');
const overlay = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('sidebarToggle');

function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}

if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
}
if (overlay) {
    overlay.addEventListener('click', closeSidebar);
}

// Close sidebar on nav link click (mobile)
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
    });
});
</script>
<script>
// Hover effect untuk tabel dengan rowspan (Barang Masuk/Keluar)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('table tbody').forEach(tbody => {
        let currentGroup = [];
        let groupStart = -1;

        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.forEach((row, index) => {
            // Cek apakah baris ini punya cell dengan rowspan
            const hasRowspan = Array.from(row.cells).some(cell => cell.hasAttribute('rowspan'));

            if (hasRowspan) {
                // Simpan group sebelumnya
                if (currentGroup.length > 0) {
                    attachHover(currentGroup);
                }
                // Mulai group baru
                currentGroup = [row];
                groupStart = index;
            } else if (groupStart >= 0) {
                // Lanjutkan group yang sama
                currentGroup.push(row);
            } else {
                // Baris standalone
                attachHover([row]);
            }
        });

        // Simpan group terakhir
        if (currentGroup.length > 0) {
            attachHover(currentGroup);
        }
    });

    function attachHover(rows) {
        rows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                rows.forEach(r => r.classList.add('table-active'));
            });
            row.addEventListener('mouseleave', () => {
                rows.forEach(r => r.classList.remove('table-active'));
            });
        });
    }
});
</script>
@stack('scripts')

{{-- Toast Notification Container --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    @if(session('success'))
    <div id="toastSuccess" class="toast show align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div id="toastError" class="toast show align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div id="toastValidation" class="toast show align-items-center text-bg-warning border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-triangle me-2"></i>Ada kesalahan pada input. Silakan periksa kembali.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script>
// Auto-hide toasts after 4 seconds
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toast.show').forEach(function(toast) {
        setTimeout(function() {
            var bsToast = bootstrap.Toast.getOrCreateInstance(toast);
            bsToast.hide();
        }, 4000);
    });
});
</script>

{{-- Real-time Notifikasi Polling --}}
@if(in_array(($user['role'] ?? null), ['admin', 'pimpinan']))
<div id="notifToastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9998;"></div>
<script>
(function() {
    const role = '{{ $user["role"] ?? "" }}';
    let lastCounts = {};

    function updateBadge(el, count) {
        if (!el) return;
        if (count > 0) {
            el.textContent = count;
            el.style.display = '';
        } else {
            el.style.display = 'none';
        }
    }

    function showToast(icon, bgClass, title, message, link) {
        const container = document.getElementById('notifToastContainer');
        if (!container) return;
        const id = 'notif-' + Date.now();
        const html = `
            <div id="${id}" class="toast align-items-center text-bg-${bgClass} border-0" role="alert">
                <div class="d-flex">
                    <a href="${link}" class="toast-body text-decoration-none text-white d-flex align-items-center gap-2">
                        <i class="bi ${icon} fs-5"></i>
                        <div>
                            <strong>${title}</strong><br>
                            <small>${message}</small>
                        </div>
                    </a>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        const el = document.getElementById(id);
        const bsToast = new bootstrap.Toast(el, { delay: 8000 });
        bsToast.show();
        el.addEventListener('hidden.bs.toast', () => el.remove());
    }

    async function poll() {
        try {
            const resp = await fetch('{{ route("api.notif") }}');
            if (!resp.ok) return;
            const data = await resp.json();

            if (role === 'admin') {
                const badgePermintaan = document.getElementById('badge-permintaan');
                const badgePengajuan = document.getElementById('badge-pengajuan');

                if (lastCounts.permintaan_pending !== undefined && data.permintaan_pending > lastCounts.permintaan_pending) {
                    const diff = data.permintaan_pending - lastCounts.permintaan_pending;
                    showToast('bi-inbox', 'primary', 'Permintaan Baru', diff + ' permintaan sparepart menunggu diproses', '{{ route("admin.barang-keluar") }}');
                }
                if (lastCounts.pengajuan_pending !== undefined && data.pengajuan_pending > lastCounts.pengajuan_pending) {
                    showToast('bi-cart-plus', 'warning', 'Pengajuan Baru', 'Ada pengajuan pembelian baru', '{{ route("admin.pengajuan.index") }}');
                }

                updateBadge(badgePermintaan, data.permintaan_pending);
                updateBadge(badgePengajuan, data.pengajuan_pending);
                lastCounts = data;
            }

            if (role === 'pimpinan') {
                const badgePengajuan = document.getElementById('badge-pengajuan');
                const badgeOpname = document.getElementById('badge-opname');

                if (lastCounts.pengajuan_pending !== undefined && data.pengajuan_pending > lastCounts.pengajuan_pending) {
                    showToast('bi-cart-plus', 'warning', 'Pengajuan Menunggu', 'Ada pengajuan pembelian menunggu persetujuan', '{{ route("pimpinan.pengajuan.index") }}');
                }
                if (lastCounts.opname_pending !== undefined && data.opname_pending > lastCounts.opname_pending) {
                    showToast('bi-clipboard-check', 'info', 'Stock Opname Baru', 'Ada stock opname menunggu review', '{{ route("pimpinan.stock-opname.index") }}');
                }

                updateBadge(badgePengajuan, data.pengajuan_pending);
                updateBadge(badgeOpname, data.opname_pending);
                lastCounts = data;
            }
        } catch (e) {
            // silent fail
        }
    }

    // Initial fetch to set baseline
    poll();
    // Poll every 5 detik
    setInterval(poll, 5000);
})();
</script>
@endif
</body>
</html>
