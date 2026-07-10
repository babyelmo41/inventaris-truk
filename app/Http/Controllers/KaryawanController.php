<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\Sparepart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'completed' => BarangKeluar::where('requested_by', $userId)->where('status', 'completed')->count(),
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
            ->latest('date')
            ->latest('created_at')
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
            'spareparts' => Sparepart::orderBy('code')->get(),
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
            'date' => 'required|date',
            'time' => 'required',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.before_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'date' => $request->date,
            'time' => $request->time,
            'purpose' => $request->purpose,
            'user_id' => $request->session()->get('auth_user.id'),
            'requested_by' => $request->session()->get('auth_user.id'),
            'truck_name' => $request->truck_name,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        foreach ($request->items as $index => $item) {
            // Compress & simpan foto pengajuan
            $beforePhotoPath = $this->compressAndStore(
                $item['before_photo'],
                'photos/before'
            );

            $permintaan->details()->create([
                'sparepart_id' => $item['sparepart_id'],
                'quantity' => $item['quantity'],
                'before_photo' => $beforePhotoPath,
                'item_status' => 'pending',
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

    /**
     * Upload foto bukti pemasangan untuk item tertentu
     */
    public function uploadAfterPhoto(Request $request, BarangKeluar $permintaan, DetailBarangKeluar $detail): RedirectResponse
    {
        // Pastikan karyawan hanya bisa upload ke permintaannya sendiri
        abort_if($permintaan->requested_by !== session('auth_user.id'), 403);

        // Pastikan item milik permintaan ini
        abort_unless($detail->barang_keluar_id === $permintaan->id, 404);

        // Pastikan status processed (sudah di-admin-proses)
        abort_unless($detail->item_status === 'processed', 403);

        $request->validate([
            'after_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Hapus foto lama kalau ada
        if ($detail->after_photo) {
            Storage::disk('public')->delete($detail->after_photo);
        }

        // Compress & simpan foto bukti pemasangan
        $afterPhotoPath = $this->compressAndStore(
            $request->file('after_photo'),
            'photos/after'
        );

        $detail->update([
            'after_photo' => $afterPhotoPath,
            'item_status' => 'completed',
        ]);

        // Cek apakah semua item sudah completed → update header status
        $freshPermintaan = BarangKeluar::with('details')->findOrFail($permintaan->id);
        if ($freshPermintaan->allItemsHaveAfterPhoto()) {
            $freshPermintaan->update(['status' => 'completed']);
        }

        return back()->with('success', 'Foto bukti pemasangan berhasil diupload untuk ' . $detail->sparepart->name . '!');
    }

    public function katalog(): View
    {
        return view('karyawan.katalog', [
            'title' => 'Katalog Sparepart',
            'spareparts' => Sparepart::with(['category', 'supplier'])->orderBy('code')->get(),
        ]);
    }

    /**
     * Compress image menggunakan GD library bawaan PHP
     * Resize max 1920px width, simpan sebagai JPEG 80%
     */
    private function compressAndStore($file, string $directory): string
    {
        $filename = Str::uuid() . '.jpg';
        $path = $directory . '/' . $filename;

        // Baca image dari uploaded file
        $sourcePath = $file->getRealPath();
        $imageInfo = getimagesize($sourcePath);
        $mimeType = $imageInfo['mime'];

        // Buat image dari tipe yang sesuai
        $image = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png' => imagecreatefrompng($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default => imagecreatefromjpeg($sourcePath),
        };

        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        // Resize jika lebih besar dari 1920px width
        $maxWidth = 1920;
        if ($origWidth > $maxWidth) {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) ($origHeight * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resized;
        }

        // Simpan sebagai JPEG 80% quality ke buffer
        ob_start();
        imagejpeg($image, null, 80);
        $encoded = ob_get_clean();
        imagedestroy($image);

        // Simpan ke storage
        Storage::disk('public')->put($path, $encoded);

        return $path;
    }
}
