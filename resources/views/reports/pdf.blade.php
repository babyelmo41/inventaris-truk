<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} | PT. Chakra Jawara</title>
    <style>
        @page { size: landscape; margin: 1.5cm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #1e293b; margin: 0; }

        /* Header */
        .header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; border-bottom: 2px solid #1e293b; padding-bottom: 12px; }
        .header-logo { width: 70px; height: 70px; object-fit: contain; flex-shrink: 0; }
        .header-info { flex: 1; }
        .header-info h1 { font-size: 16px; margin: 0 0 4px 0; text-transform: uppercase; }
        .header-info h2 { font-size: 13px; margin: 0 0 4px 0; color: #475569; text-transform: uppercase; }
        .header-info p { font-size: 10px; color: #64748b; margin: 2px 0; }

        /* Meta */
        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 10px; color: #64748b; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1e293b; color: #fff; padding: 8px 10px; font-size: 10px; text-transform: uppercase; text-align: left; border: 1px solid #334155; }
        tbody td { padding: 7px 10px; border: 1px solid #e2e8f0; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .badge { padding: 2px 8px; border-radius: 12px; font-size: 9px; font-weight: bold; }
        .badge-habis { background: #fee2e2; color: #dc2626; }
        .badge-hampir { background: #fef9c3; color: #ca8a04; }
        .badge-aman { background: #dcfce7; color: #16a34a; }

        /* Legend */
        .legend { font-size: 10px; }
        .legend-title { font-weight: bold; text-transform: uppercase; margin-bottom: 8px; font-size: 10px; }
        .legend-item { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
        .legend-badge { display: inline-block; padding: 2px 10px; border-radius: 4px; font-size: 9px; font-weight: bold; min-width: 80px; text-align: center; }

        /* Signature */
        .signature-box { text-align: right; min-width: 200px; }
        .signature-box .location-date { font-size: 10px; margin-bottom: 20px; }
        .signature-box .label { font-size: 10px; margin-bottom: 4px; }
        .signature-box .title { font-size: 10px; font-weight: bold; margin-bottom: 50px; }
        .signature-box .line { border-bottom: 1px solid #1e293b; margin-bottom: 4px; }
        .signature-box .name { font-size: 10px; }

        /* Bottom footer */
        .footer { margin-top: 16px; padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/logo-chakra-jawara.png') }}" alt="Logo" class="header-logo">
    <div class="header-info">
        <h1>PT. Chakra Jawara Kabupaten Banjar</h1>
        <h2>{{ $title }}</h2>
        <p>{{ $description }}</p>
    </div>
</div>

<div class="meta">
    <div>
        <span>Tanggal Cetak : {{ \Carbon\Carbon::now('Asia/Makassar')->format('d M Y H:i') }}</span>
        @if($filters['date'])
            <span style="margin-left: 16px;">Tanggal: {{ \Carbon\Carbon::parse($filters['date'])->format('d M Y') }}</span>
        @endif
        @if($filters['date_from'] && $filters['date_to'])
            <span style="margin-left: 16px;">Periode: {{ \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }}</span>
        @endif
        @if($filters['month'])
            <span style="margin-left: 16px;">Bulan: {{ \Carbon\Carbon::parse($filters['month'] . '-01')->format('M Y') }}</span>
        @endif
    </div>
    <div>Total Data : {{ count($rows) }}</div>
</div>

<table>
    <thead>
        <tr>
            <th style="width:40px;text-align:center">No</th>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $index => $row)
            <tr>
                <td style="text-align:center">{{ $index + 1 }}</td>
                @foreach($row as $cell)
                    <td>
                        @if($cell === 'Aman')
                            <span class="badge badge-aman">Aman</span>
                        @elseif($cell === 'Hampir Habis')
                            <span class="badge badge-hampir">Hampir Habis</span>
                        @elseif($cell === 'Habis')
                            <span class="badge badge-habis">Habis</span>
                        @elseif($cell === 'Surplus')
                            <span class="badge" style="background:#dcfce7;color:#16a34a;">Surplus</span>
                        @elseif($cell === 'Defisit')
                            <span class="badge" style="background:#fee2e2;color:#dc2626;">Defisit</span>
                        @elseif($cell === 'Disetujui')
                            <span class="badge" style="background:#dcfce7;color:#16a34a;">Disetujui</span>
                        @elseif($cell === 'Ditolak')
                            <span class="badge" style="background:#fee2e2;color:#dc2626;">Ditolak</span>
                        @elseif($cell === 'Menunggu')
                            <span class="badge" style="background:#fef9c3;color:#ca8a04;">Menunggu</span>
                        @elseif($cell === 'Selisih Opname')
                            <span class="badge" style="background:#ede9fe;color:#7c3aed;">Selisih Opname</span>
                        @else
                            {{ $cell }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($headers) + 1 }}" style="text-align:center;padding:20px">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table width="100%" style="margin-top: 24px; page-break-inside: avoid; border: none;">
    <tr>
        <td valign="top" style="border: none; vertical-align: top;">
            @if(!empty($legend))
            <div class="legend">
                <div class="legend-title">Keterangan Status :</div>
                @foreach($legend as $item)
                <div class="legend-item">
                    <span class="legend-badge" style="background:{{ $item['bg'] }};color:{{ $item['color'] }};">{{ $item['label'] }}</span>
                    <span>{{ $item['desc'] }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </td>
        <td valign="top" style="border: none; vertical-align: top; text-align: right;">
            <div class="signature-box">
                <div class="location-date">Kab. Banjar, {{ now()->format('d M Y') }}</div>
                <div class="label">Mengetahui,</div>
                <div class="title">Pimpinan</div>
                <div class="line"></div>
                <div class="name">( Nama Pimpinan )</div>
            </div>
        </td>
    </tr>
</table>

<div class="footer">
    PT. Chakra Jawara Kabupaten Banjar — {{ $title }}
</div>

</body>
</html>
