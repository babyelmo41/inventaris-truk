<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Sparepart;
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

    public function show(string $type): View
    {
        $reports = $this->reports();
        abort_unless(array_key_exists($type, $reports), 404);

        return view('reports.show', [
            'title' => $reports[$type]['title'],
            'report' => $reports[$type],
            'type' => $type,
        ]);
    }

    private function reports(): array
    {
        return [
            'stok-sparepart' => [
                'title' => 'Laporan Stok Sparepart',
                'description' => 'Preview stok seluruh sparepart berdasarkan kategori dan supplier.',
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
                        END as status")
                    )
                    ->join('categories', 'spareparts.category_id', '=', 'categories.id')
                    ->join('suppliers', 'spareparts.supplier_id', '=', 'suppliers.id')
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
                'headers' => ['Tanggal', 'No Transaksi', 'Supplier', 'Sparepart', 'Jumlah', 'Total'],
                'rows' => BarangMasuk::select(
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
                    ->toArray(),
            ],

            'barang-keluar' => [
                'title' => 'Laporan Barang Keluar',
                'description' => 'Preview transaksi barang keluar berdasarkan periode dan tujuan penggunaan.',
                'headers' => ['Tanggal', 'No Transaksi', 'Tujuan', 'Sparepart', 'Jumlah Keluar', 'Keterangan'],
                'rows' => BarangKeluar::select(
                        'barang_keluar.date',
                        'barang_keluar.reference_no',
                        'barang_keluar.purpose',
                        'spareparts.name as sparepart_name',
                        'detail_barang_keluar.quantity',
                        'barang_keluar.notes'
                    )
                    ->join('detail_barang_keluar', 'barang_keluar.id', '=', 'detail_barang_keluar.barang_keluar_id')
                    ->join('spareparts', 'detail_barang_keluar.sparepart_id', '=', 'spareparts.id')
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
                    ->toArray(),
            ],

            'stok-minimum' => [
                'title' => 'Laporan Stok Minimum',
                'description' => 'Sparepart yang stoknya sudah mencapai atau di bawah batas minimum.',
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
                        END as status")
                    )
                    ->join('categories', 'spareparts.category_id', '=', 'categories.id')
                    ->join('suppliers', 'spareparts.supplier_id', '=', 'suppliers.id')
                    ->whereColumn('spareparts.stock', '<=', 'spareparts.min_stock')
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
                'headers' => ['Tanggal', 'Tipe', 'No Transaksi', 'Sparepart', 'Jumlah', 'User/Admin'],
                'rows' => $this->getTransactionHistory(),
            ],
        ];
    }

    private function getTransactionHistory(): array
    {
        // Query barang masuk dengan JOIN
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

        // Query barang keluar dengan JOIN, lalu UNION dengan barang masuk
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

        // UNION kedua query, urutkan berdasarkan tanggal terbaru
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
}
