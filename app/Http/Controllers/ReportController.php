<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
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
        $reports = $this->reports($request);
        abort_unless(array_key_exists($type, $reports), 404);

        $report = $reports[$type];

        return view('reports.show', [
            'title' => $report['title'],
            'report' => $report,
            'type' => $type,
            'filterable' => $report['filterable'] ?? false,
            'filters' => [
                'date' => $request->date,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'month' => $request->month,
            ],
        ]);
    }

    private function reports(Request $request): array
    {
        return [
            'stok-sparepart' => [
                'title' => 'Laporan Stok Sparepart',
                'description' => 'Preview stok seluruh sparepart berdasarkan kategori dan supplier.',
                'filterable' => false,
                'headers' => ['Kode Barang', 'Nama Barang', 'Kategori', 'Supplier', 'Stok', 'Stok Minimum', 'Status'],
                'rows' => Sparepart::select(
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
                    ->toArray(),
            ],

            'barang-masuk' => [
                'title' => 'Laporan Barang Masuk',
                'description' => 'Preview transaksi barang masuk berdasarkan periode dan supplier.',
                'filterable' => true,
                'headers' => ['Tanggal', 'No Transaksi', 'Supplier', 'Sparepart', 'Jumlah', 'Total'],
                'rows' => $this->getBarangMasukRows($request),
            ],

            'barang-keluar' => [
                'title' => 'Laporan Barang Keluar',
                'description' => 'Preview transaksi barang keluar berdasarkan periode dan tujuan penggunaan.',
                'filterable' => true,
                'headers' => ['Tanggal', 'No Transaksi', 'Tujuan', 'Sparepart', 'Jumlah Keluar', 'Keterangan'],
                'rows' => $this->getBarangKeluarRows($request),
            ],

            'stok-minimum' => [
                'title' => 'Laporan Stok Minimum',
                'description' => 'Sparepart yang stoknya sudah mencapai atau di bawah batas minimum.',
                'filterable' => false,
                'headers' => ['Kode Barang', 'Nama Barang', 'Kategori', 'Supplier', 'Stok Tersisa', 'Stok Minimum', 'Status'],
                'rows' => Sparepart::select(
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
                    ->toArray(),
            ],

            'riwayat-transaksi' => [
                'title' => 'Riwayat Transaksi Sparepart',
                'description' => 'Histori seluruh transaksi barang masuk dan keluar.',
                'filterable' => true,
                'headers' => ['Tanggal', 'Tipe', 'No Transaksi', 'Sparepart', 'Jumlah', 'User/Admin'],
                'rows' => $this->getTransactionHistory($request),
            ],
        ];
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

        // Default: bulan ini
        return $query->whereYear($dateColumn, now()->year)
                     ->whereMonth($dateColumn, now()->month);
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

    private function getTransactionHistory(Request $request): array
    {
        $masuk = BarangMasuk::select(
                'barang_masuk.date',
                DB::raw("'Barang Masuk' as tipe"),
                'barang_masuk.invoice_no as no_transaksi',
                'spareparts.name as sparepart_name',
                'detail_barang_masuk.quantity',
                'users.name as user_name'
            )
            ->join('detail_barang_masuk', 'barang_masuk.id', '=', 'detail_barang_masuk.barang_masuk_id')
            ->join('spareparts', 'detail_barang_masuk.sparepart_id', '=', 'spareparts.id')
            ->join('users', 'barang_masuk.user_id', '=', 'users.id');

        $keluar = BarangKeluar::select(
                'barang_keluar.date',
                DB::raw("'Barang Keluar' as tipe"),
                'barang_keluar.reference_no as no_transaksi',
                'spareparts.name as sparepart_name',
                'detail_barang_keluar.quantity',
                'users.name as user_name'
            )
            ->join('detail_barang_keluar', 'barang_keluar.id', '=', 'detail_barang_keluar.barang_keluar_id')
            ->join('spareparts', 'detail_barang_keluar.sparepart_id', '=', 'spareparts.id')
            ->join('users', 'barang_keluar.user_id', '=', 'users.id');

        // Apply date filter to both subqueries
        if ($request->filled('date')) {
            $masuk->whereDate('barang_masuk.date', $request->date);
            $keluar->whereDate('barang_keluar.date', $request->date);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $masuk->whereBetween('barang_masuk.date', [$request->date_from, $request->date_to]);
            $keluar->whereBetween('barang_keluar.date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('month')) {
            $year = substr($request->month, 0, 4);
            $month = substr($request->month, 5, 2);
            $masuk->whereYear('barang_masuk.date', $year)->whereMonth('barang_masuk.date', $month);
            $keluar->whereYear('barang_keluar.date', $year)->whereMonth('barang_keluar.date', $month);
        } else {
            // Default: bulan ini
            $masuk->whereYear('barang_masuk.date', now()->year)->whereMonth('barang_masuk.date', now()->month);
            $keluar->whereYear('barang_keluar.date', now()->year)->whereMonth('barang_keluar.date', now()->month);
        }

        $transactions = $masuk->unionAll($keluar)
            ->orderBy('date', 'desc')
            ->get();

        return $transactions->map(fn ($row) => [
            \Carbon\Carbon::parse($row->date)->format('d M Y'),
            $row->tipe,
            $row->no_transaksi,
            $row->sparepart_name,
            $row->quantity,
            $row->user_name,
        ])->toArray();
    }

    // ============================================
    // PDF EXPORT
    // ============================================
    public function pdf(string $type, Request $request)
    {
        $reports = $this->reports($request);
        abort_unless(array_key_exists($type, $reports), 404);

        $report = $reports[$type];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', [
            'title' => $report['title'],
            'description' => $report['description'],
            'headers' => $report['headers'],
            'rows' => $report['rows'],
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
