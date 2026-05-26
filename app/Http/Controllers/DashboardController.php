<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Sparepart;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        $today = now()->toDateString();

        // Data untuk donut chart
        $stokAman = Sparepart::whereColumn('stock', '>', 'min_stock')->count();
        $stokHampirHabis = Sparepart::whereColumn('stock', '<=', 'min_stock')->whereColumn('stock', '>', DB::raw(0))->count();
        $stokHabis = Sparepart::where('stock', '<=', 0)->count();

        return view('admin.dashboard', [
            'title' => 'Dashboard Admin Gudang',
            'stats' => [
                ['label' => 'Total Jenis Sparepart', 'value' => Sparepart::count(), 'icon' => 'bi-box-seam', 'tone' => 'primary'],
                ['label' => 'Stok Hampir Habis', 'value' => Sparepart::whereColumn('stock', '<=', 'min_stock')->count(), 'icon' => 'bi-exclamation-triangle', 'tone' => 'warning'],
                ['label' => 'Barang Masuk Hari Ini', 'value' => BarangMasuk::whereDate('date', $today)->count(), 'icon' => 'bi-arrow-down-circle', 'tone' => 'success'],
                ['label' => 'Barang Keluar Hari Ini', 'value' => BarangKeluar::whereDate('date', $today)->count(), 'icon' => 'bi-arrow-up-circle', 'tone' => 'danger'],
            ],
            'chartData' => [
                'aman' => $stokAman,
                'hampir_habis' => $stokHampirHabis,
                'habis' => $stokHabis,
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
                        'datetime' => $masuk->date->format('Y-m-d') . ' ' . $masuk->time,
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
                                'datetime' => $keluar->date->format('Y-m-d') . ' ' . $keluar->time,
                                'code' => $keluar->reference_no,
                                'item' => $keluar->details->first()?->sparepart?->name ?? '-',
                                'type' => 'Barang Keluar',
                                'qty' => $keluar->details->sum('quantity'),
                                'user' => $keluar->user->name,
                            ];
                        })
                )
                ->sortByDesc('datetime')
                ->values()
                ->toArray(),
        ]);
    }

    public function pimpinan(): View
    {
        $thisMonth = now()->startOfMonth()->toDateString();

        // Data untuk bar chart: transaksi per bulan (6 bulan terakhir)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'label' => $date->format('M Y'),
                'start' => $date->copy()->startOfMonth()->toDateString(),
                'end' => $date->copy()->endOfMonth()->toDateString(),
            ]);
        }

        $chartLabels = $months->pluck('label')->toArray();
        $chartMasuk = $months->map(fn ($m) => BarangMasuk::whereBetween('date', [$m['start'], $m['end']])->count())->toArray();
        $chartKeluar = $months->map(fn ($m) => BarangKeluar::whereBetween('date', [$m['start'], $m['end']])->count())->toArray();

        return view('pimpinan.dashboard', [
            'title' => 'Dashboard Pimpinan',
            'stats' => [
                ['label' => 'Total Jenis Sparepart', 'value' => Sparepart::count(), 'icon' => 'bi-box-seam', 'tone' => 'primary'],
                ['label' => 'Total Stok Gudang', 'value' => number_format(Sparepart::sum('stock')), 'icon' => 'bi-stack', 'tone' => 'info'],
                ['label' => 'Barang Masuk Bulan Ini', 'value' => BarangMasuk::where('date', '>=', $thisMonth)->count(), 'icon' => 'bi-graph-up-arrow', 'tone' => 'success'],
                ['label' => 'Barang Keluar Bulan Ini', 'value' => BarangKeluar::where('date', '>=', $thisMonth)->count(), 'icon' => 'bi-activity', 'tone' => 'warning'],
            ],
            'chartData' => [
                'labels' => $chartLabels,
                'masuk' => $chartMasuk,
                'keluar' => $chartKeluar,
            ],
        ]);
    }
}
