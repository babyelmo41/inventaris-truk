@extends('layouts.app')

@section('content')
<div class="panel-card p-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">{{ $report['title'] }}</h2>
            <div class="text-secondary">{{ $report['description'] }}</div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer me-2"></i>Cetak</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>

    {{-- Tabel Report --}}
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0" id="report-table">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    @foreach($report['headers'] as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($report['rows'] as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @foreach($row as $cell)
                            <td>
                                {{-- Badge khusus untuk kolom Status --}}
                                @if($cell === 'Aman')
                                    <span class="badge bg-success">{{ $cell }}</span>
                                @elseif($cell === 'Hampir Habis')
                                    <span class="badge bg-warning text-dark">{{ $cell }}</span>
                                @elseif($cell === 'Habis')
                                    <span class="badge bg-danger">{{ $cell }}</span>
                                @else
                                    {{ $cell }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($report['headers']) + 1 }}" class="text-center text-secondary py-4">
                            Tidak ada data untuk ditampilkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="{{ count($report['headers']) + 1 }}" class="text-end fw-semibold">
                        Total: {{ count($report['rows']) }} data
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
    @media print {
        /* Sembunyikan elemen UI */
        .sidebar, .topbar, .btn, .navbar { display: none !important; }

        /* Layout full width */
        .main-area { margin-left: 0 !important; padding: 0 !important; }
        .content-wrap { padding: 0 !important; }
        .panel-card { box-shadow: none !important; border: none !important; padding: 0 !important; }

        /* Orientasi landscape untuk tabel lebar */
        @page {
            size: landscape;
            margin: 1.5cm;
        }

        /* Header cetak */
        body::before {
            content: "PT. Chakra Jawara - {{ $report['title'] }}";
            display: block;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        /* Tabel */
        table {
            font-size: 11px !important;
            width: 100% !important;
        }

        th, td {
            padding: 4px 8px !important;
            border: 1px solid #ddd !important;
        }

        thead {
            background-color: #f0f0f0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Badge tetap terlihat */
        .badge {
            border: 1px solid currentColor;
            padding: 2px 6px;
            font-size: 10px;
        }

        /* Footer info */
        tfoot {
            font-weight: bold;
        }

        /* Page break yang rapi */
        tr {
            page-break-inside: avoid;
        }

        thead {
            display: table-header-group;
        }
    }
</style>
@endsection
