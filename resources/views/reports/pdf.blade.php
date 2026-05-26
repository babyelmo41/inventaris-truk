<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} | PT. Chakra Jawara</title>
    <style>
        @page { size: landscape; margin: 1.5cm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 12px; }
        .header h1 { font-size: 16px; margin: 0 0 4px 0; }
        .header h2 { font-size: 13px; margin: 0 0 4px 0; color: #475569; }
        .header p { font-size: 10px; color: #64748b; margin: 2px 0; }
        .meta { margin-bottom: 16px; font-size: 10px; color: #64748b; }
        .meta span { margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1e293b; color: #fff; padding: 8px 10px; font-size: 10px; text-transform: uppercase; text-align: left; border: 1px solid #334155; }
        tbody td { padding: 7px 10px; border: 1px solid #e2e8f0; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .badge { padding: 2px 8px; border-radius: 12px; font-size: 9px; font-weight: bold; }
        .badge-habis { background: #fee2e2; color: #dc2626; }
        .badge-hampir { background: #fef9c3; color: #ca8a04; }
        .badge-aman { background: #dcfce7; color: #16a34a; }
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h1>PT. Chakra Jawara Kabupaten Banjar</h1>
    <h2>{{ $title }}</h2>
    <p>{{ $description }}</p>
</div>

<div class="meta">
    <span>Tanggal cetak: {{ now()->format('d M Y H:i') }}</span>
    @if($filters['date'])
        <span>Tanggal: {{ \Carbon\Carbon::parse($filters['date'])->format('d M Y') }}</span>
    @endif
    @if($filters['date_from'] && $filters['date_to'])
        <span>Periode: {{ \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }}</span>
    @endif
    @if($filters['month'])
        <span>Bulan: {{ \Carbon\Carbon::parse($filters['month'] . '-01')->format('M Y') }}</span>
    @endif
    <span>Total data: {{ count($rows) }}</span>
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

<div class="footer">
    PT. Chakra Jawara Kabupaten Banjar — {{ $title }}
</div>

</body>
</html>
