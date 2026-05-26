@php
    $user = session('auth_user');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Inventaris Truk' }} | PT. Chakra Jawara</title>
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
            min-height: 100vh;
            position: fixed;
            width: var(--sidebar-width);
            z-index: 1030;
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

        .main-area {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
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
                position: fixed;
                left: -280px;
                top: 0;
                width: 276px;
                min-height: 100vh;
                transition: left .3s ease;
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

            /* Responsive tables */
            .table-responsive-custom {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive-custom .btn-sm {
                font-size: .75rem;
                padding: .25rem .5rem;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @if(($user['role'] ?? null) === 'pimpinan')
        @include('layouts.sidebar-pimpinan')
    @else
        @include('layouts.sidebar-admin')
    @endif

    <main class="main-area">
        @include('layouts.navbar')
        <div class="content-wrap">
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
</body>
</html>
