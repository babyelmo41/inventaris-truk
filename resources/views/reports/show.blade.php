@extends('layouts.app')

@section('content')
{{-- Dark Header --}}
<div class="report-header mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-white mb-2">{{ $report['title'] }}</h1>
            <p class="text-white-50 mb-0">{{ $report['description'] }}</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex gap-2 justify-content-lg-end position-relative" style="z-index:1">
                @if(count($report['rows']) > 0)
                <button onclick="window.open('{{ route('reports.print', array_merge(['type' => $type], request()->query())) }}', '_blank')" class="btn btn-light"><i class="bi bi-printer me-2"></i>Cetak</button>
                <a href="{{ route('reports.pdf', array_merge(['type' => $type], request()->query())) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-2"></i>Download PDF</a>
                @endif
                <a href="{{ url()->previous() }}" class="btn btn-outline-light"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
@if($filterable)
<div class="report-card mb-4">
    <div class="report-card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
    </div>
    <div class="report-card-body">
        <form method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md" id="periodCol">
                    <label class="form-label fw-semibold text-muted small">Period</label>
                    <select id="periodSelect" class="form-select form-select-lg">
                        <option value="" {{ !request()->has('date') && !request()->has('date_from') && !request()->has('month') ? 'selected' : '' }}>-- Pilih Period --</option>
                        @php
                            $periodLabels = [
                                'today' => 'Hari Ini',
                                'yesterday' => 'Kemarin',
                                'this_week' => 'Pekan Ini',
                                'last_week' => 'Pekan Lalu',
                                'this_month' => 'Bulan Ini',
                                'last_month' => 'Bulan Lalu',
                                'custom' => 'Rentang Kustom',
                            ];
                        @endphp
                        @foreach($periodLabels as $key => $label)
                            @if(in_array($key, $allowed_periods))
                            @php
                                $isSelected = false;
                                if ($key === 'this_month' && request()->month === now()->format('Y-m')) $isSelected = true;
                                elseif ($key === 'last_month' && request()->month === now()->subMonth()->format('Y-m')) $isSelected = true;
                                elseif ($key === 'custom' && (request()->date_from || request()->date_to)) $isSelected = true;
                            @endphp
                            <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2" id="dateSingleWrap" style="display:none">
                    <label class="form-label fw-semibold text-muted small">Tanggal</label>
                    <input type="date" name="date" id="dateSingle" class="form-control form-control-lg" value="{{ $filters['date'] ?? '' }}">
                </div>
                <div class="col-md-2" id="dateFromWrap" style="display:none">
                    <label class="form-label fw-semibold text-muted small">Dari Tanggal</label>
                    <input type="date" name="date_from" id="dateFrom" class="form-control form-control-lg" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2" id="dateToWrap" style="display:none">
                    <label class="form-label fw-semibold text-muted small">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="dateTo" class="form-control form-control-lg" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-2" id="monthWrap" style="display:none">
                    <label class="form-label fw-semibold text-muted small">Bulan</label>
                    <input type="month" name="month" id="monthInput" class="form-control form-control-lg" value="{{ $filters['month'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1"><i class="bi bi-search me-1"></i>Filter</button>
                        <a href="{{ route('reports.show', $type) }}" class="btn btn-outline-secondary btn-lg">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Data Card --}}
<div class="report-card">
    <div class="report-card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Report Overview</h6>
        @if(count($report['rows']) > 0)
        <span class="badge bg-white text-dark fw-semibold">{{ count($report['rows']) }} data</span>
        @endif
    </div>
    @if(count($report['rows']) > 0)
    <div class="report-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="report-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        @foreach($report['headers'] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['rows'] as $row)
                        <tr>
                            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                            @foreach($row as $cell)
                                <td>
                                    @if($cell === 'Aman')
                                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>{{ $cell }}</span>
                                    @elseif($cell === 'Hampir Habis')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>{{ $cell }}</span>
                                    @elseif($cell === 'Habis')
                                        <span class="badge rounded-pill bg-danger-subtle text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i>{{ $cell }}</span>
                                    @else
                                        {{ $cell }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="report-card-footer text-muted">
        <i class="bi bi-file-earmark-text me-1"></i>
        Menampilkan {{ count($report['rows']) }} data | PT. Chakra Jawara Kabupaten Banjar
    </div>
    @else
    <div class="report-card-body text-center py-5">
        <div class="mb-4">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h5 class="fw-bold text-secondary mb-2">No Report Generated Yet</h5>
        <p class="text-muted mb-0">Pilih <strong>Period</strong> dan klik <strong>Filter</strong> untuk menampilkan data laporan.</p>
    </div>
    @endif
</div>

@if($filterable)
@push('scripts')
<script>
const periodSelect = document.getElementById('periodSelect');
const dateSingleWrap = document.getElementById('dateSingleWrap');
const dateFromWrap = document.getElementById('dateFromWrap');
const dateToWrap = document.getElementById('dateToWrap');
const monthWrap = document.getElementById('monthWrap');
const dateSingle = document.getElementById('dateSingle');
const dateFrom = document.getElementById('dateFrom');
const dateTo = document.getElementById('dateTo');
const monthInput = document.getElementById('monthInput');

function formatDate(date) {
    return date.toISOString().split('T')[0];
}

function getMonday(d) {
    d = new Date(d);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff));
}

periodSelect.addEventListener('change', function() {
    const val = this.value;
    const now = new Date();
    const today = formatDate(now);
    const yesterday = formatDate(new Date(now.setDate(now.getDate() - 1)));

    // Reset visibility
    dateSingleWrap.style.display = 'none';
    dateFromWrap.style.display = 'none';
    dateToWrap.style.display = 'none';
    monthWrap.style.display = 'none';
    dateSingle.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    monthInput.value = '';

    switch (val) {
        case 'today':
            dateSingleWrap.style.display = '';
            dateSingle.value = today;
            break;
        case 'yesterday':
            dateSingleWrap.style.display = '';
            dateSingle.value = formatDate(new Date(Date.now() - 86400000));
            break;
        case 'this_week':
            dateFromWrap.style.display = '';
            dateToWrap.style.display = '';
            dateFrom.value = formatDate(getMonday(new Date()));
            dateTo.value = today;
            break;
        case 'last_week':
            dateFromWrap.style.display = '';
            dateToWrap.style.display = '';
            const lastMon = getMonday(new Date(Date.now() - 7 * 86400000));
            dateFrom.value = formatDate(lastMon);
            dateTo.value = formatDate(new Date(lastMon.getTime() + 6 * 86400000));
            break;
        case 'this_month':
            monthWrap.style.display = '';
            monthInput.value = today.substring(0, 7);
            break;
        case 'last_month':
            monthWrap.style.display = '';
            const lm = new Date();
            lm.setMonth(lm.getMonth() - 1);
            monthInput.value = formatDate(lm).substring(0, 7);
            break;
        case 'custom':
            dateFromWrap.style.display = '';
            dateToWrap.style.display = '';
            break;
    }
});

// Init: show correct fields on page load
(function() {
    const hasDate = dateSingle.value !== '';
    const hasRange = dateFrom.value !== '' && dateTo.value !== '';
    const hasMonth = monthInput.value !== '';

    if (hasDate) { dateSingleWrap.style.display = ''; }
    else if (hasRange) { dateFromWrap.style.display = ''; dateToWrap.style.display = ''; }
    else if (hasMonth) { monthWrap.style.display = ''; }
})();
</script>
@endpush
@endif

<style>
    /* Report-specific hover */
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.08);
    }

    /* Badge helper classes */
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .bg-warning-subtle { background-color: #fef9c3 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; }

    /* Form Control */
    .form-control-lg, .form-select-lg {
        border-radius: 10px;
        border-color: #e2e8f0;
    }
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .form-select-lg {
        padding-right: 2.5rem;
    }

    /* Empty State */
    .report-card-body svg {
        opacity: 0.5;
    }

    /* Print Styles */
    @media print {
        .sidebar, .topbar, .btn, .navbar, #filterForm, .report-card:first-of-type { display: none !important; }
        .main-area { margin-left: 0 !important; padding: 0 !important; }
        .content-wrap { padding: 0 !important; }
        .report-header {
            background: #1e293b !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-radius: 0;
            padding: 1rem;
        }
        .report-card { box-shadow: none !important; border: 1px solid #ddd; border-radius: 8px; }
        .report-card-header { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

        @page {
            size: landscape;
            margin: 1cm;
        }

        body::before {
            content: "PT. Chakra Jawara - {{ $report['title'] }}";
            display: block;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid #333;
        }

        table {
            font-size: 10px !important;
            width: 100% !important;
        }
        th, td {
            padding: 4px 8px !important;
            border: 1px solid #ddd !important;
        }
        .badge {
            border: 1px solid currentColor;
            padding: 2px 6px;
            font-size: 9px;
        }
        tr { page-break-inside: avoid; }
        thead { display: table-header-group; }
        .report-card-body .text-center.py-5 { display: none !important; }
    }
</style>
@endsection
