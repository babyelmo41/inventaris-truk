<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\Sparepart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    public function dashboard(Request $request): View
    {
        $userId = $request->session()->get('auth_user.id');

        $stats = [
            'total' => BarangKeluar::where('requested_by', $userId)->count(),
            'pending' => BarangKeluar::where('requested_by', $userId)->where('status', 'pending')->count(),
            'processed' => BarangKeluar::where('requested_by', $userId)->where('status', 'processed')->count(),
            'rejected' => BarangKeluar::where('requested_by', $userId)->where('status', 'rejected')->count(),
        ];

        $latest = BarangKeluar::where('requested_by', $userId)
            ->with('details.sparepart')
            ->latest()
            ->take(5)
            ->get();

        return view('karyawan.dashboard', [
            'title' => 'Dashboard Karyawan',
            'stats' => $stats,
            'latest' => $latest,
        ]);
    }

    public function permintaanIndex(Request $request): View
    {
        $userId = $request->session()->get('auth_user.id');

        $permintaan = BarangKeluar::where('requested_by', $userId)
            ->with('details.sparepart')
            ->latest()
            ->paginate(10);

        return view('karyawan.permintaan-index', [
            'title' => 'Permintaan Sparepart',
            'permintaan' => $permintaan,
        ]);
    }

    public function permintaanCreate(): View
    {
        return view('karyawan.permintaan-create', [
            'title' => 'Buat Permintaan Sparepart',
            'spareparts' => Sparepart::orderBy('name')->get(),
            'generatedPermintaanNo' => CodeGenerator::permintaanNo(),
        ]);
    }

    public function permintaanStore(Request $request): RedirectResponse
    {
        $request->validate([
            'reference_no' => 'required|unique:barang_keluar,reference_no',
            'purpose' => 'required|string|max:255',
            'truck_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Validasi stok cukup
        foreach ($request->items as $item) {
            $sparepart = Sparepart::find($item['sparepart_id']);
            if ($sparepart->stock < $item['quantity']) {
                return back()->withErrors([
                    'items' => "Stok {$sparepart->name} tidak cukup! Tersedia: {$sparepart->stock}"
                ])->withInput();
            }
        }

        $permintaan = BarangKeluar::create([
            'reference_no' => $request->reference_no,
            'date' => now()->toDateString(),
            'time' => now()->format('H:i'),
            'purpose' => $request->purpose,
            'user_id' => $request->session()->get('auth_user.id'),
            'requested_by' => $request->session()->get('auth_user.id'),
            'truck_name' => $request->truck_name,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            $permintaan->details()->create([
                'sparepart_id' => $item['sparepart_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('karyawan.permintaan.index')->with('success', 'Permintaan sparepart berhasil dikirim! Menunggu proses Admin.');
    }

    public function permintaanShow(BarangKeluar $permintaan): View
    {
        // Pastikan karyawan hanya bisa lihat permintaannya sendiri
        abort_if($permintaan->requested_by !== session('auth_user.id'), 403);

        return view('karyawan.permintaan-show', [
            'title' => 'Detail Permintaan',
            'permintaan' => $permintaan->load('details.sparepart', 'user'),
        ]);
    }

    public function katalog(): View
    {
        return view('karyawan.katalog', [
            'title' => 'Katalog Sparepart',
            'spareparts' => Sparepart::with(['category', 'supplier'])->orderBy('name')->get(),
        ]);
    }
}
