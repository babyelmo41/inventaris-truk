<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\PengajuanPembelian;
use App\Models\DetailPengajuanPembelian;
use App\Models\DetailBarangMasuk;
use App\Models\Sparepart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengajuanPembelianController extends Controller
{
    public function index(): View
    {
        $user = request()->session()->get('auth_user');
        $isPimpinan = $user && ($user['role'] ?? null) === 'pimpinan';

        if ($isPimpinan) {
            $pengajuan = PengajuanPembelian::with(['user', 'details.sparepart'])->latest('date')->get();
        } else {
            $pengajuan = PengajuanPembelian::with(['user', 'details.sparepart'])
                ->where('user_id', $user['id'])
                ->latest('date')
                ->get();
        }

        return view('pengajuan.index', [
            'title' => 'Pengajuan Pembelian',
            'pengajuan' => $pengajuan,
        ]);
    }

    public function create(): View
    {
        return view('pengajuan.create', [
            'title' => 'Buat Pengajuan Pembelian',
            'spareparts' => Sparepart::all(),
            'generatedAjuanNo' => CodeGenerator::ajuanNo(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'ajuan_no' => 'required|unique:pengajuan_pembelian,ajuan_no',
            'notes' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanPembelian::create([
                'ajuan_no' => $request->ajuan_no,
                'date' => $request->date,
                'user_id' => $request->session()->get('auth_user')['id'],
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                DetailPengajuanPembelian::create([
                    'pengajuan_pembelian_id' => $pengajuan->id,
                    'sparepart_id' => $item['sparepart_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.pengajuan.index')->with('success', 'Pengajuan pembelian berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat pengajuan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(PengajuanPembelian $pengajuan): View
    {
        return view('pengajuan.show', [
            'title' => 'Detail Pengajuan Pembelian',
            'pengajuan' => $pengajuan->load(['user', 'details.sparepart', 'approver']),
        ]);
    }

    public function approve(PengajuanPembelian $pengajuan): RedirectResponse
    {
        $pengajuan->update([
            'status' => 'approved',
            'approved_by' => request()->session()->get('auth_user')['id'],
        ]);

        return redirect()->route('pimpinan.pengajuan.index')->with('success', 'Pengajuan berhasil disetujui!');
    }

    public function reject(Request $request, PengajuanPembelian $pengajuan): RedirectResponse
    {
        $request->validate([
            'reject_notes' => 'required|string',
        ]);

        $pengajuan->update([
            'status' => 'rejected',
            'notes' => ($pengajuan->notes ? $pengajuan->notes . "\n\n" : '') . 'Ditolak: ' . $request->reject_notes,
            'approved_by' => request()->session()->get('auth_user')['id'],
        ]);

        return redirect()->route('pimpinan.pengajuan.index')->with('success', 'Pengajuan ditolak.');
    }

    public function destroy(PengajuanPembelian $pengajuan): RedirectResponse
    {
        $pengajuan->delete();
        return redirect()->route('admin.pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
    }

    /**
     * API: Ambil harga terakhir sparepart dari barang masuk
     */
    public function getLastPrice(Sparepart $sparepart): JsonResponse
    {
        $lastPrice = DetailBarangMasuk::where('sparepart_id', $sparepart->id)
            ->join('barang_masuk', 'detail_barang_masuk.barang_masuk_id', '=', 'barang_masuk.id')
            ->orderBy('barang_masuk.date', 'desc')
            ->orderBy('barang_masuk.time', 'desc')
            ->value('detail_barang_masuk.price');

        return response()->json([
            'sparepart_id' => $sparepart->id,
            'last_price' => $lastPrice ? (int) $lastPrice : null,
            'formatted' => $lastPrice ? 'Rp ' . number_format($lastPrice, 0, ',', '.') : null,
        ]);
    }
}
