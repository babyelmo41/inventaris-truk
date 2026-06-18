<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\PengajuanPembelian;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index', [
            'title' => 'Laporan',
        ]);
    }

    public function show(string $type, Request $request): View
    {
        $report = $this->getReport($type, $request);
        abort_unless($report, 404);

        return view('reports.show', [
            'title' => $report['title'],
            'report' => $report,
            'type' => $type,
            'filterable' => $report['filterable'] ?? false,
            'allowed_periods' => $report['allowed_periods'] ?? ['today', 'yesterday', 'this_week', 'last_week', 'this_month', 'last_month', 'custom'],
            'filters' => [
                'date' => $request->date,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'month' => $request->month,
            ],
        ]);
    }

    private function getReport(string $type, Request $request): ?array
    {
        $meta = $this->reportMeta();
        abort_unless(array_key_exists($type, $meta), 404);

        $report = $meta[$type];
        $report['rows'] = match ($type) {
            'stok-sparepart' => $this->getStokSparepartRows(),
            'barang-masuk' => $this->getBarangMasukRows($request),
            'barang-keluar' => $this->getBarangKeluarRows($request),
            'stok-minimum' => $this->getStokMinimumRows(),
            'transaksi-per-supplier' => $this->getSupplierTransactions($request),
            'rekap-bulanan' => $this->getMonthlySummary($request),
            'pengajuan-pembelian' => $this->getPengajuanRows($request),
        };

        return $report;
    }

    private function reportMeta(): array
    {
        return [
            'stok-sparepart' => [
                'title' => 'Laporan Stok Sparepart',
                'description' => 'Preview stok seluruh sparepart berdasarkan kategori dan supplier.',
                'filterable' => false,
                'headers' => ['Kode Barang', 'Nama Barang', 'Kategori', 'Supplier', 'Stok', 'Stok Minimum', 'Status'],
                'legend' => [
                    ['label' => 'Aman', 'color' => '#22c55e', 'bg' => '#dcfce7', 'desc' => 'Stok mencukupi'],
                    ['label' => 'Hampir Habis', 'color' => '#d97706', 'bg' => '#fef3c7', 'desc' => 'Stok hampir mencapai batas minimum'],
                    ['label' => 'Habis', 'color' => '#dc2626', 'bg' => '#fee2e2', 'desc' => 'Stok di bawah batas minimum'],
                ],
            ],
            'barang-masuk' => [
                'title' => 'Laporan Barang Masuk',
                'description' => 'Preview transaksi barang masuk berdasarkan periode dan supplier.',
                'filterable' => true,
                'allowed_periods' => ['this_week', 'last_week', 'this_month', 'last_month', 'custom'],
                'headers' => ['Tanggal', 'No Transaksi', 'Supplier', 'Sparepart', 'Jumlah', 'Total'],
            ],
            'barang-keluar' => [
                'title' => 'Laporan Barang Keluar',
                'description' => 'Preview transaksi barang keluar berdasarkan periode dan tujuan penggunaan.',
                'filterable' => true,
                'allowed_periods' => ['this_week', 'last_week', 'this_month', 'last_month', 'custom'],
                'headers' => ['Tanggal', 'No Transaksi', 'Tujuan', 'Sparepart', 'Jumlah Keluar', 'Keterangan'],
            ],
            'stok-minimum' => [
                'title' => 'Laporan Stok Minimum',
                'description' => 'Sparepart yang stoknya sudah mencapai atau di bawah batas minimum.',
                'filterable' => false,
                'headers' => ['Kode Barang', 'Nama Barang', 'Kategori', 'Supplier', 'Stok Tersisa', 'Stok Minimum', 'Status'],
                'legend' => [
                    ['label' => 'Aman', 'color' => '#22c55e', 'bg' => '#dcfce7', 'desc' => 'Stok mencukupi'],
                    ['label' => 'Hampir Habis', 'color' => '#d97706', 'bg' => '#fef3c7', 'desc' => 'Stok hampir mencapai batas minimum'],
                    ['label' => 'Habis', 'color' => '#dc2626', 'bg' => '#fee2e2', 'desc' => 'Stok di bawah batas minimum'],
                ],
            ],
            'transaksi-per-supplier' => [
                'title' => 'Transaksi per Supplier',
                'description' => 'Rekapitulasi transaksi barang masuk berdasarkan supplier (frekuensi, total barang, total nilai).',
                'filterable' => true,
                'allowed_periods' => ['this_month', 'last_month', 'custom'],
                'headers' => ['Nama Supplier', 'Total Transaksi', 'Total Sparepart', 'Nilai Keseluruhan', 'Sparepart Terbanyak'],
            ],
            'rekap-bulanan' => [
                'title' => 'Rekap Bulanan Masuk vs Keluar',
                'description' => 'Perbandingan stok masuk dan keluar per bulan.',
                'filterable' => true,
                'allowed_periods' => ['this_month', 'last_month', 'custom'],
                'headers' => ['Bulan', 'Total Masuk', 'Total Keluar', 'Selisih', 'Netto'],
                'legend' => [
                    ['label' => 'Surplus', 'color' => '#16a34a', 'bg' => '#dcfce7', 'desc' => 'Stok masuk lebih banyak dari keluar'],
                    ['label' => 'Defisit', 'color' => '#dc2626', 'bg' => '#fee2e2', 'desc' => 'Stok keluar lebih banyak dari masuk'],
                ],
            ],
            'pengajuan-pembelian' => [
                'title' => 'Laporan Pengajuan Pembelian',
                'description' => 'Riwayat pengajuan pembelian sparepart dan status persetujuan oleh Pimpinan.',
                'filterable' => true,
                'allowed_periods' => ['this_week', 'last_week', 'this_month', 'last_month', 'custom'],
                'headers' => ['Tanggal', 'No Pengajuan', 'Diajukan Oleh', 'Status', 'Disetujui Oleh', 'Keterangan'],
                'legend' => [
                    ['label' => 'Disetujui', 'color' => '#16a34a', 'bg' => '#dcfce7', 'desc' => 'Disetujui oleh Pimpinan'],
                    ['label' => 'Ditolak', 'color' => '#dc2626', 'bg' => '#fee2e2', 'desc' => 'Ditolak oleh Pimpinan'],
                    ['label' => 'Menunggu', 'color' => '#ca8a04', 'bg' => '#fef9c3', 'desc' => 'Masih menunggu persetujuan'],
                ],
            ],
        ];
    }

    // ============================================
    // REPORT ROWS: STOK SPAREPART
    // ============================================
    private function getStokSparepartRows(): array
    {
        return Sparepart::select(
                'spareparts.code',
                'spareparts.name',
                'categories.name as category_name',
                'suppliers.name as supplier_name',
                'spareparts.stock',
                'spareparts.min_stock',
                DB::raw("CASE 
                    WHEN spareparts.stock <= 0 THEN 'Habis'
                    WHEN spareparts.stock <= spareparts.min_stock THEN 'Hampir Habis'
                    ELSE 'Aman'
                END as status"),
                DB::raw("CASE 
                    WHEN spareparts.stock <= 0 THEN 1
                    WHEN spareparts.stock <= spareparts.min_stock THEN 2
                    ELSE 3
                END as status_order")
            )
            ->join('categories', 'spareparts.category_id', '=', 'categories.id')
            ->join('suppliers', 'spareparts.supplier_id', '=', 'suppliers.id')
            ->orderBy('status_order')
            ->orderBy('spareparts.stock')
            ->get()
            ->map(fn ($row) => [
                $row->code,
                $row->name,
                $row->category_name,
                $row->supplier_name,
                $row->stock,
                $row->min_stock,
                $row->status,
            ])
            ->toArray();
    }

    // ============================================
    // REPORT ROWS: STOK MINIMUM
    // ============================================
    private function getStokMinimumRows(): array
    {
        return Sparepart::select(
                'spareparts.code',
                'spareparts.name',
                'categories.name as category_name',
                'suppliers.name as supplier_name',
                'spareparts.stock',
                'spareparts.min_stock',
                DB::raw("CASE 
                    WHEN spareparts.stock <= 0 THEN 'Habis'
                    WHEN spareparts.stock <= spareparts.min_stock THEN 'Hampir Habis'
                END as status"),
                DB::raw("CASE 
                    WHEN spareparts.stock <= 0 THEN 1
                    ELSE 2
                END as status_order")
            )
            ->join('categories', 'spareparts.category_id', '=', 'categories.id')
            ->join('suppliers', 'spareparts.supplier_id', '=', 'suppliers.id')
            ->whereColumn('spareparts.stock', '<=', 'spareparts.min_stock')
            ->orderBy('status_order')
            ->orderBy('spareparts.stock')
            ->get()
            ->map(fn ($row) => [
                $row->code,
                $row->name,
                $row->category_name,
                $row->supplier_name,
                $row->stock,
                $row->min_stock,
                $row->status,
            ])
            ->toArray();
    }

    // ============================================
    // FILTER LOGIC
    // ============================================

    private function applyDateFilter($query, Request $request, string $dateColumn = 'date')
    {
        // Filter: tanggal tertentu
        if ($request->filled('date')) {
            return $query->whereDate($dateColumn, $request->date);
        }

        // Filter: rentang tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            return $query->whereBetween($dateColumn, [$request->date_from, $request->date_to]);
        }

        // Filter: bulan tertentu (format: YYYY-MM)
        if ($request->filled('month')) {
            $year = substr($request->month, 0, 4);
            $month = substr($request->month, 5, 2);
            return $query->whereYear($dateColumn, $year)
                         ->whereMonth($dateColumn, $month);
        }

        // Tidak ada filter → tampilkan data kosong (user harus filter dulu)
        return $query->whereRaw('1 = 0');
    }

    private function getBarangMasukRows(Request $request): array
    {
        return BarangMasuk::select(
                'barang_masuk.date',
                'barang_masuk.invoice_no',
                'suppliers.name as supplier_name',
                'spareparts.name as sparepart_name',
                'detail_barang_masuk.quantity',
                DB::raw('detail_barang_masuk.quantity * detail_barang_masuk.price as total')
            )
            ->join('suppliers', 'barang_masuk.supplier_id', '=', 'suppliers.id')
            ->join('detail_barang_masuk', 'barang_masuk.id', '=', 'detail_barang_masuk.barang_masuk_id')
            ->join('spareparts', 'detail_barang_masuk.sparepart_id', '=', 'spareparts.id')
            ->tap(fn ($q) => $this->applyDateFilter($q, $request, 'barang_masuk.date'))
            ->orderBy('barang_masuk.date', 'desc')
            ->get()
            ->map(fn ($row) => [
                \Carbon\Carbon::parse($row->date)->format('d M Y'),
                $row->invoice_no,
                $row->supplier_name,
                $row->sparepart_name,
                $row->quantity,
                'Rp ' . number_format($row->total, 0, ',', '.'),
            ])
            ->toArray();
    }

    private function getBarangKeluarRows(Request $request): array
    {
        return BarangKeluar::select(
                'barang_keluar.date',
                'barang_keluar.reference_no',
                'barang_keluar.purpose',
                'spareparts.name as sparepart_name',
                'detail_barang_keluar.quantity',
                'barang_keluar.notes'
            )
            ->join('detail_barang_keluar', 'barang_keluar.id', '=', 'detail_barang_keluar.barang_keluar_id')
            ->join('spareparts', 'detail_barang_keluar.sparepart_id', '=', 'spareparts.id')
            ->tap(fn ($q) => $this->applyDateFilter($q, $request, 'barang_keluar.date'))
            ->orderBy('barang_keluar.date', 'desc')
            ->get()
            ->map(fn ($row) => [
                \Carbon\Carbon::parse($row->date)->format('d M Y'),
                $row->reference_no,
                $row->purpose,
                $row->sparepart_name,
                $row->quantity,
                $row->notes ?? '-',
            ])
            ->toArray();
    }


    // REPORT #6: TRANSAKSI PER SUPPLIER
    // ============================================
    private function getSupplierTransactions(Request $request): array
    {
        $query = DB::table('suppliers')
            ->select(
                'suppliers.name as supplier_name',
                DB::raw('COUNT(DISTINCT barang_masuk.id) as total_transaksi'),
                DB::raw('SUM(detail_barang_masuk.quantity) as total_sparepart'),
                DB::raw('SUM(detail_barang_masuk.quantity * detail_barang_masuk.price) as total_nilai'),
                DB::raw("(
                    SELECT spareparts.name 
                    FROM detail_barang_masuk 
                    JOIN spareparts ON spareparts.id = detail_barang_masuk.sparepart_id 
                    WHERE detail_barang_masuk.barang_masuk_id IN (
                        SELECT id FROM barang_masuk WHERE supplier_id = suppliers.id
                    )
                    GROUP BY spareparts.id 
                    ORDER BY SUM(detail_barang_masuk.quantity) DESC 
                    LIMIT 1
                ) as sparepart_terbanyak")
            )
            ->join('barang_masuk', 'barang_masuk.supplier_id', '=', 'suppliers.id')
            ->join('detail_barang_masuk', 'detail_barang_masuk.barang_masuk_id', '=', 'barang_masuk.id')
            ->groupBy('suppliers.id', 'suppliers.name');

        // Apply filter
        if ($request->filled('date')) {
            $query->whereDate('barang_masuk.date', $request->date);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('barang_masuk.date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('month')) {
            $year = substr($request->month, 0, 4);
            $month = substr($request->month, 5, 2);
            $query->whereYear('barang_masuk.date', $year)->whereMonth('barang_masuk.date', $month);
        } else {
            // No filter → return all data
        }

        return $query->orderBy('total_nilai', 'desc')
            ->get()
            ->map(fn ($row) => [
                $row->supplier_name,
                $row->total_transaksi . ' transaksi',
                $row->total_sparepart . ' pcs',
                'Rp ' . number_format($row->total_nilai, 0, ',', '.'),
                $row->sparepart_terbanyak ?? '-',
            ])
            ->toArray();
    }

    // ============================================
    // REPORT #9: REKAP BULANAN MASUK VS KELUAR
    // ============================================
    private function getMonthlySummary(Request $request): array
    {
        // Ambil data masuk per bulan
        $masuk = DB::table('barang_masuk')
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as bulan"),
                DB::raw("DATE_FORMAT(date, '%M %Y') as bulan_nama"),
                DB::raw('SUM(quantity) as total_masuk')
            )
            ->join('detail_barang_masuk', 'detail_barang_masuk.barang_masuk_id', '=', 'barang_masuk.id')
            ->groupBy('bulan', 'bulan_nama');

        // Ambil data keluar per bulan
        $keluar = DB::table('barang_keluar')
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as bulan"),
                DB::raw('SUM(quantity) as total_keluar')
            )
            ->join('detail_barang_keluar', 'detail_barang_keluar.barang_keluar_id', '=', 'barang_keluar.id')
            ->groupBy('bulan');

        // Apply filter to both
        if ($request->filled('date')) {
            $month = substr($request->date, 0, 7); // YYYY-MM
            $masuk->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month]);
            $keluar->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month]);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $masuk->whereBetween('date', [$request->date_from, $request->date_to]);
            $keluar->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('month')) {
            $masuk->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$request->month]);
            $keluar->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$request->month]);
        } else {
            // No filter → show all data
        }

        // Union kedua query
        $allData = DB::table($masuk)
            ->select('bulan', 'bulan_nama', 'total_masuk', DB::raw('NULL as total_keluar'))
            ->unionAll(
                DB::table($keluar)->select('bulan', DB::raw("'' as bulan_nama"), DB::raw('NULL as total_masuk'), 'total_keluar')
            )
            ->orderBy('bulan', 'desc')
            ->get();

        // Aggregate: gabungkan per bulan
        $grouped = [];
        foreach ($allData as $row) {
            $key = $row->bulan;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'bulan_nama' => $row->bulan_nama ?: $row->bulan,
                    'total_masuk' => 0,
                    'total_keluar' => 0,
                ];
            }
            if ($row->total_masuk !== null) $grouped[$key]['total_masuk'] += $row->total_masuk;
            if ($row->total_keluar !== null) $grouped[$key]['total_keluar'] += $row->total_keluar;
        }

        return collect($grouped)
            ->sortByDesc(fn ($v, $k) => $k)
            ->map(fn ($row) => [
                ucfirst($row['bulan_nama']),
                $row['total_masuk'] . ' pcs',
                $row['total_keluar'] . ' pcs',
                ($row['total_masuk'] - $row['total_keluar']) > 0 ? '+' . ($row['total_masuk'] - $row['total_keluar']) : ($row['total_masuk'] - $row['total_keluar']),
                $row['total_masuk'] - $row['total_keluar'] >= 0 ? 'Surplus' : 'Defisit',
            ])
            ->values()
            ->toArray();
    }

    // ============================================
    // REPORT #9: PENGAJUAN PEMBELIAN
    // ============================================
    private function getPengajuanRows(Request $request): array
    {
        $query = PengajuanPembelian::select(
                'pengajuan_pembelian.date',
                'pengajuan_pembelian.ajuan_no',
                'users.name as user_name',
                'pengajuan_pembelian.status',
                DB::raw("COALESCE(approvers.name, '-') as approver_name"),
                'pengajuan_pembelian.notes'
            )
            ->join('users', 'pengajuan_pembelian.user_id', '=', 'users.id')
            ->leftJoin('users as approvers', 'pengajuan_pembelian.approved_by', '=', 'approvers.id');

        // Apply filter
        if ($request->filled('date')) {
            $query->whereDate('pengajuan_pembelian.date', $request->date);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('pengajuan_pembelian.date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('month')) {
            $year = substr($request->month, 0, 4);
            $month = substr($request->month, 5, 2);
            $query->whereYear('pengajuan_pembelian.date', $year)->whereMonth('pengajuan_pembelian.date', $month);
        } else {
            return [];
        }

        return $query->orderBy('pengajuan_pembelian.date', 'desc')
            ->get()
            ->map(fn ($row) => [
                \Carbon\Carbon::parse($row->date)->format('d M Y'),
                $row->ajuan_no,
                $row->user_name,
                match ($row->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => $row->status,
                },
                $row->approver_name,
                $row->notes ?: '-',
            ])
            ->toArray();
    }

    // ============================================
    // PRINT VIEW (HTML for browser print)
    // ============================================
    public function print(string $type, Request $request)
    {
        $report = $this->getReport($type, $request);
        abort_unless($report, 404);

        return view('reports.print', [
            'title' => $report['title'],
            'description' => $report['description'],
            'headers' => $report['headers'],
            'rows' => $report['rows'],
            'legend' => $report['legend'] ?? null,
            'filters' => [
                'date' => $request->date,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'month' => $request->month,
            ],
        ]);
    }

    // ============================================
    // PDF EXPORT
    // ============================================
    public function pdf(string $type, Request $request)
    {
        $report = $this->getReport($type, $request);
        abort_unless($report, 404);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', [
            'title' => $report['title'],
            'description' => $report['description'],
            'headers' => $report['headers'],
            'rows' => $report['rows'],
            'legend' => $report['legend'] ?? null,
            'filters' => [
                'date' => $request->date,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'month' => $request->month,
            ],
        ])->setPaper('a4', 'landscape');

        $filename = str_replace(' ', '-', strtolower($report['title'])) . '.pdf';

        return $pdf->download($filename);
    }
}
