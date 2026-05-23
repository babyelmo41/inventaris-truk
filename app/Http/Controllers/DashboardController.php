<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Sparepart;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        $today = now()->toDateString();

        return view('admin.dashboard', [
            'title' => 'Dashboard Admin Gudang',
            'stats' => [
                ['label' => 'Total Sparepart', 'value' => Sparepart::count(), 'icon' => 'bi-box-seam', 'tone' => 'primary'],
                ['label' => 'Stok Hampir Habis', 'value' => Sparepart::whereColumn('stock', '<=', 'min_stock')->count(), 'icon' => 'bi-exclamation-triangle', 'tone' => 'warning'],
                ['label' => 'Barang Masuk Hari Ini', 'value' => BarangMasuk::whereDate('date', $today)->count(), 'icon' => 'bi-arrow-down-circle', 'tone' => 'success'],
                ['label' => 'Barang Keluar Hari Ini', 'value' => BarangKeluar::whereDate('date', $today)->count(), 'icon' => 'bi-arrow-up-circle', 'tone' => 'danger'],
            ],
            'activities' => BarangMasuk::with(['supplier', 'user'])
                ->latest('date')
                ->limit(3)
                ->get()
                ->map(function ($masuk) {
                    return [
                        'time' => $masuk->date->format('H:i'),
                        'code' => $masuk->invoice_no,
                        'item' => $masuk->details->first()?->sparepart?->name ?? '-',
                        'type' => 'Barang Masuk',
                        'qty' => $masuk->details->sum('quantity'),
                        'user' => $masuk->user->name,
                    ];
                })
                ->merge(
                    BarangKeluar::with(['user'])
                        ->latest('date')
                        ->limit(3)
                        ->get()
                        ->map(function ($keluar) {
                            return [
                                'time' => $keluar->date->format('H:i'),
                                'code' => $keluar->reference_no,
                                'item' => $keluar->details->first()?->sparepart?->name ?? '-',
                                'type' => 'Barang Keluar',
                                'qty' => $keluar->details->sum('quantity'),
                                'user' => $keluar->user->name,
                            ];
                        })
                )
                ->sortByDesc('time')
                ->values()
                ->toArray(),
        ]);
    }

    public function pimpinan(): View
    {
        $thisMonth = now()->startOfMonth()->toDateString();
        $today = now()->toDateString();

        // Hitung status stok minimum secara dinamis
        $stokMinimumCount = Sparepart::whereColumn('stock', '<=', 'min_stock')->count();
        $stokHabisCount = Sparepart::where('stock', '<=', 0)->count();

        // Ambil tanggal transaksi terakhir untuk setiap jenis laporan
        $lastBarangMasuk = BarangMasuk::latest('date')->first();
        $lastBarangKeluar = BarangKeluar::latest('date')->first();
        $lastTransaksi = BarangMasuk::latest('date')->first();
        if ($lastBarangKeluar && (!$lastTransaksi || $lastBarangKeluar->date > $lastTransaksi->date)) {
            $lastTransaksi = $lastBarangKeluar;
        }

        return view('pimpinan.dashboard', [
            'title' => 'Dashboard Pimpinan',
            'stats' => [
                ['label' => 'Total Stok', 'value' => number_format(Sparepart::sum('stock')), 'icon' => 'bi-stack', 'tone' => 'primary'],
                ['label' => 'Stok Minimum', 'value' => $stokMinimumCount, 'icon' => 'bi-clipboard2-pulse', 'tone' => 'warning'],
                ['label' => 'Transaksi Masuk Bulan Ini', 'value' => BarangMasuk::where('date', '>=', $thisMonth)->count(), 'icon' => 'bi-graph-up-arrow', 'tone' => 'success'],
                ['label' => 'Transaksi Keluar Bulan Ini', 'value' => BarangKeluar::where('date', '>=', $thisMonth)->count(), 'icon' => 'bi-activity', 'tone' => 'info'],
            ],
            'reports' => [
                [
                    'date' => $lastBarangMasuk ? $lastBarangMasuk->date->format('d F Y') : '-',
                    'name' => 'Laporan Barang Masuk',
                    'type' => 'barang-masuk',
                    'status' => $lastBarangMasuk ? 'Siap Ditinjau' : 'Belum Ada Data',
                ],
                [
                    'date' => $lastBarangKeluar ? $lastBarangKeluar->date->format('d F Y') : '-',
                    'name' => 'Laporan Barang Keluar',
                    'type' => 'barang-keluar',
                    'status' => $lastBarangKeluar ? 'Siap Ditinjau' : 'Belum Ada Data',
                ],
                [
                    'date' => now()->format('d F Y'),
                    'name' => 'Laporan Stok Sparepart',
                    'type' => 'stok-sparepart',
                    'status' => 'Siap Ditinjau',
                ],
                [
                    'date' => now()->format('d F Y'),
                    'name' => 'Laporan Stok Minimum',
                    'type' => 'stok-minimum',
                    'status' => $stokMinimumCount > 0 ? 'Perlu Perhatian' : 'Siap Ditinjau',
                ],
                [
                    'date' => $lastTransaksi ? $lastTransaksi->date->format('d F Y') : '-',
                    'name' => 'Riwayat Transaksi Sparepart',
                    'type' => 'riwayat-transaksi',
                    'status' => $lastTransaksi ? 'Siap Ditinjau' : 'Belum Ada Data',
                ],
            ],
        ]);
    }
}
