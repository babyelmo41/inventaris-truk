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

        @media (max-width: 991.98px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }

            .main-area {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
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
@stack('scripts')
</body>
</html>
