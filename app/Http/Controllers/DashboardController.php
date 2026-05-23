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
                ['label' => 'Total Jenis Sparepart', 'value' => Sparepart::count(), 'icon' => 'bi-box-seam', 'tone' => 'primary'],
                ['label' => 'Stok Hampir Habis', 'value' => Sparepart::whereColumn('stock', '<=', 'min_stock')->count(), 'icon' => 'bi-exclamation-triangle', 'tone' => 'warning'],
                ['label' => 'Barang Masuk Hari Ini', 'value' => BarangMasuk::whereDate('date', $today)->count(), 'icon' => 'bi-arrow-down-circle', 'tone' => 'success'],
                ['label' => 'Barang Keluar Hari Ini', 'value' => BarangKeluar::whereDate('date', $today)->count(), 'icon' => 'bi-arrow-up-circle', 'tone' => 'danger'],
            ],
            'activities' => BarangMasuk::with(['supplier', 'user'])
                ->latest('date')
                ->latest('time')
                ->limit(3)
                ->get()
                ->map(function ($masuk) {
                    return [
                        'date' => $masuk->date->format('d M Y'),
                        'time' => \Carbon\Carbon::parse($masuk->time)->format('H:i'),
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
                        ->latest('time')
                        ->limit(3)
                        ->get()
                        ->map(function ($keluar) {
                            return [
                                'date' => $keluar->date->format('d M Y'),
                                'time' => \Carbon\Carbon::parse($keluar->time)->format('H:i'),
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

        return view('pimpinan.dashboard', [
            'title' => 'Dashboard Pimpinan',
            'stats' => [
                ['label' => 'Total Jenis Sparepart', 'value' => Sparepart::count(), 'icon' => 'bi-box-seam', 'tone' => 'primary'],
                ['label' => 'Total Stok Gudang', 'value' => number_format(Sparepart::sum('stock')), 'icon' => 'bi-stack', 'tone' => 'info'],
                ['label' => 'Barang Masuk Bulan Ini', 'value' => BarangMasuk::where('date', '>=', $thisMonth)->count(), 'icon' => 'bi-graph-up-arrow', 'tone' => 'success'],
                ['label' => 'Barang Keluar Bulan Ini', 'value' => BarangKeluar::where('date', '>=', $thisMonth)->count(), 'icon' => 'bi-activity', 'tone' => 'warning'],
            ],
        ]);
    }
}
