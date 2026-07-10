<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Category;
use App\Models\PengajuanPembelian;
use App\Models\Sparepart;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InventoryController extends Controller
{
    // ============================================
    // SPAREPARTS
    // ============================================
    public function spareparts(): View
    {
        return view('inventory.spareparts', [
            'title' => 'Data Sparepart',
            'spareparts' => Sparepart::with(['category', 'supplier'])->paginate(10),
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function sparepartCreate(): View
    {
        return view('inventory.sparepart-form', [
            'title' => 'Tambah Sparepart',
            'sparepart' => null,
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'generatedCode' => CodeGenerator::sparepartCode(),
        ]);
    }

    public function sparepartStore(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|unique:spareparts',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required',
        ]);

        $data = $request->all();
        $data['stock'] = 0; // Stok awal selalu 0
        Sparepart::create($data);

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil ditambahkan! Stok awal: 0');
    }

    public function sparepartEdit(Sparepart $sparepart): View
    {
        return view('inventory.sparepart-form', [
            'title' => 'Edit Sparepart',
            'sparepart' => $sparepart,
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function sparepartUpdate(Request $request, Sparepart $sparepart): RedirectResponse
    {
        $request->validate([
            'code' => 'required|unique:spareparts,code,' . $sparepart->id,
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required',
        ]);

        // Jangan update stok dari form ini
        $data = $request->except(['stock']);
        $sparepart->update($data);

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil diupdate!');
    }

    public function sparepartDestroy(Sparepart $sparepart): RedirectResponse
    {
        $sparepart->delete();

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil dihapus!');
    }

    // ============================================
    // CATEGORIES
    // ============================================
    public function categories(): View
    {
        return view('inventory.categories', [
            'title' => 'Data Kategori',
            'categories' => Category::withCount('spareparts')->paginate(10),
        ]);
    }

    public function categoryCreate(): View
    {
        return view('inventory.category-form', [
            'title' => 'Tambah Kategori',
            'category' => null,
        ]);
    }

    public function categoryStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'nullable',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function categoryEdit(Category $category): View
    {
        return view('inventory.category-form', [
            'title' => 'Edit Kategori',
            'category' => $category,
        ]);
    }

    public function categoryUpdate(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function categoryDestroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }

    // ============================================
    // SUPPLIERS
    // ============================================
    public function suppliers(): View
    {
        return view('inventory.suppliers', [
            'title' => 'Data Supplier',
            'suppliers' => Supplier::withCount('spareparts')->paginate(10),
        ]);
    }

    public function supplierCreate(): View
    {
        return view('inventory.supplier-form', [
            'title' => 'Tambah Supplier',
            'supplier' => null,
        ]);
    }

    public function supplierStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function supplierEdit(Supplier $supplier): View
    {
        return view('inventory.supplier-form', [
            'title' => 'Edit Supplier',
            'supplier' => $supplier,
        ]);
    }

    public function supplierUpdate(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
        ]);

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diupdate!');
    }

    public function supplierDestroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }

    // ============================================
    // USERS
    // ============================================
    public function users(): View
    {
        return view('inventory.users', [
            'title' => 'Data User',
            'users' => User::paginate(10),
        ]);
    }

    public function userEdit(User $user): View
    {
        return view('inventory.user-form', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    public function userUpdate(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,pimpinan,karyawan',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Data user {$user->name} berhasil diupdate!");
    }

    public function userPasswordForm(User $user): View
    {
        return view('inventory.user-password', [
            'title' => 'Ubah Password',
            'user' => $user,
        ]);
    }

    public function userUpdatePassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', "Password {$user->name} berhasil diubah!");
    }

    public function userToggleStatus(User $user): RedirectResponse
    {
        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users.index')->with('success', "User {$user->name} berhasil {$status}!");
    }

    // ============================================
    // STOCK MONITORING
    // ============================================
    public function stockMonitoring(): View
    {
        $spareparts = Sparepart::with(['category', 'supplier'])
            ->selectRaw("spareparts.*,
                CASE
                    WHEN spareparts.stock <= 0 THEN 1
                    WHEN spareparts.stock <= spareparts.min_stock THEN 2
                    ELSE 3
                END as status_order")
            ->orderBy('status_order')
            ->orderBy('stock')
            ->get();

        return view('inventory.monitoring', [
            'title' => 'Monitoring Stok',
            'spareparts' => $spareparts,
            'categories' => Category::pluck('name')->toArray(),
        ]);
    }

    // ============================================
    // BARANG MASUK (CRUD)
    // ============================================
    public function incoming(): View
    {
        return view('transactions.incoming', [
            'title' => 'Barang Masuk',
            'transactions' => BarangMasuk::with(['supplier', 'user', 'details.sparepart'])->latest('date')->latest('created_at')->paginate(10),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function incomingCreate(): View
    {
        return view('transactions.incoming-form', [
            'title' => 'Tambah Barang Masuk',
            'transaction' => null,
            'suppliers' => Supplier::all(),
            'spareparts' => Sparepart::orderBy('code')->get(),
            'generatedInvoiceNo' => CodeGenerator::invoiceNo(),
            'approvedPengajuan' => \App\Models\PengajuanPembelian::where('status', 'approved')
                ->whereDoesntHave('barangMasuk') // belum punya barang masuk
                ->with('details.sparepart')
                ->latest('date')
                ->get(),
        ]);
    }

    public function incomingStore(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'invoice_no' => 'required|unique:barang_masuk,invoice_no',
            'supplier_id' => 'required|exists:suppliers,id',
            'pengajuan_id' => 'required|exists:pengajuan_pembelian,id',
            'notes' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:0',
        ]);

        $transaction = BarangMasuk::create([
            'invoice_no' => $request->invoice_no,
            'date' => $request->date,
            'time' => $request->time,
            'supplier_id' => $request->supplier_id,
            'user_id' => $request->session()->get('auth_user.id'),
            'status' => 'approved',
            'approved_by' => $request->session()->get('auth_user.id'),
            'pengajuan_id' => $request->pengajuan_id,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $transaction->details()->create([
                'sparepart_id' => $item['sparepart_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Update stok otomatis
            Sparepart::where('id', $item['sparepart_id'])->increment('stock', $item['quantity']);
        }

        return redirect()->route('admin.barang-masuk')->with('success', 'Barang masuk berhasil ditambahkan!');
    }

    public function incomingEdit(BarangMasuk $transaction): View
    {
        return view('transactions.incoming-form', [
            'title' => 'Edit Barang Masuk',
            'transaction' => $transaction->load('details.sparepart'),
            'suppliers' => Supplier::all(),
            'spareparts' => Sparepart::orderBy('code')->get(),
        ]);
    }

    public function incomingUpdate(Request $request, BarangMasuk $transaction): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'invoice_no' => 'required|unique:barang_masuk,invoice_no,' . $transaction->id,
            'supplier_id' => 'required|exists:suppliers,id',
            'notes' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:0',
        ]);

        // Kembalikan stok lama
        foreach ($transaction->details as $detail) {
            Sparepart::where('id', $detail->sparepart_id)->decrement('stock', $detail->quantity);
        }

        // Hapus detail lama
        $transaction->details()->delete();

        // Update header
        $transaction->update([
            'invoice_no' => $request->invoice_no,
            'date' => $request->date,
            'time' => $request->time,
            'supplier_id' => $request->supplier_id,
            'notes' => $request->notes,
        ]);

        // Simpan detail baru + update stok
        foreach ($request->items as $item) {
            $transaction->details()->create([
                'sparepart_id' => $item['sparepart_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            Sparepart::where('id', $item['sparepart_id'])->increment('stock', $item['quantity']);
        }

        return redirect()->route('admin.barang-masuk')->with('success', 'Barang masuk berhasil diupdate!');
    }

    /**
     * API: Ambil detail pengajuan untuk auto-fill barang masuk
     */
    public function getPengajuanDetails(PengajuanPembelian $pengajuan): \Illuminate\Http\JsonResponse
    {
        $items = $pengajuan->details->map(function ($detail) {
            return [
                'sparepart_id' => $detail->sparepart_id,
                'sparepart_name' => $detail->sparepart->code . ' - ' . $detail->sparepart->name,
                'quantity' => $detail->quantity,
                'price' => (int) $detail->price,
            ];
        });

        return response()->json([
            'ajuan_no' => $pengajuan->ajuan_no,
            'supplier_id' => null, // pengajuan tidak punya supplier
            'items' => $items,
        ]);
    }

    public function incomingDestroy(BarangMasuk $transaction): RedirectResponse
    {
        // Kembalikan stok
        foreach ($transaction->details as $detail) {
            Sparepart::where('id', $detail->sparepart_id)->decrement('stock', $detail->quantity);
        }

        $transaction->details()->delete();
        $transaction->delete();

        return redirect()->route('admin.barang-masuk')->with('success', 'Barang masuk berhasil dihapus!');
    }

    // ============================================
    // BARANG KELUAR (CRUD)
    // ============================================
    public function outgoing(): View
    {
        return view('transactions.outgoing', [
            'title' => 'Barang Keluar',
            'transactions' => BarangKeluar::with(['user', 'requester', 'details.sparepart'])->latest('date')->latest('created_at')->paginate(10),
            'pendingCount' => BarangKeluar::where('status', 'pending')->count(),
        ]);
    }

    public function outgoingCreate(): View
    {
        return view('transactions.outgoing-form', [
            'title' => 'Tambah Barang Keluar',
            'transaction' => null,
            'spareparts' => Sparepart::where('stock', '>', 0)->get(),
            'generatedReferenceNo' => CodeGenerator::referenceNo(),
        ]);
    }

    public function outgoingStore(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'reference_no' => 'required|unique:barang_keluar,reference_no',
            'purpose' => 'required',
            'notes' => 'nullable',
            'requested_by' => 'nullable|exists:users,id',
            'truck_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Validasi stok cukup
        foreach ($request->items as $item) {
            $sparepart = Sparepart::find($item['sparepart_id']);
            if ($sparepart->stock < $item['quantity']) {
                return back()->withErrors(['items' => "Stok {$sparepart->name} tidak cukup! Stok tersedia: {$sparepart->stock}"])->withInput();
            }
        }

        $transaction = BarangKeluar::create([
            'reference_no' => $request->reference_no,
            'date' => $request->date,
            'time' => $request->time,
            'purpose' => $request->purpose,
            'user_id' => $request->session()->get('auth_user.id'),
            'notes' => $request->notes,
            'requested_by' => $request->requested_by ?: null,
            'truck_name' => $request->truck_name ?: null,
        ]);

        foreach ($request->items as $item) {
            $transaction->details()->create([
                'sparepart_id' => $item['sparepart_id'],
                'quantity' => $item['quantity'],
            ]);

            // Update stok otomatis
            Sparepart::where('id', $item['sparepart_id'])->decrement('stock', $item['quantity']);
        }

        return redirect()->route('admin.barang-keluar')->with('success', 'Barang keluar berhasil ditambahkan!');
    }

    public function outgoingEdit(BarangKeluar $transaction): View
    {
        return view('transactions.outgoing-form', [
            'title' => 'Edit Barang Keluar',
            'transaction' => $transaction->load('details.sparepart'),
            'spareparts' => Sparepart::orderBy('code')->get(),
        ]);
    }

    public function outgoingUpdate(Request $request, BarangKeluar $transaction): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'reference_no' => 'required|unique:barang_keluar,reference_no,' . $transaction->id,
            'purpose' => 'required',
            'notes' => 'nullable',
            'requested_by' => 'nullable|exists:users,id',
            'truck_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Kembalikan stok lama
        foreach ($transaction->details as $detail) {
            Sparepart::where('id', $detail->sparepart_id)->increment('stock', $detail->quantity);
        }

        // Validasi stok cukup untuk data baru
        foreach ($request->items as $item) {
            $sparepart = Sparepart::find($item['sparepart_id']);
            if ($sparepart->stock < $item['quantity']) {
                // Rollback stok lama
                foreach ($transaction->details as $detail) {
                    Sparepart::where('id', $detail->sparepart_id)->decrement('stock', $detail->quantity);
                }
                return back()->withErrors(['items' => "Stok {$sparepart->name} tidak cukup! Stok tersedia: {$sparepart->stock}"])->withInput();
            }
        }

        // Hapus detail lama
        $transaction->details()->delete();

        // Update header
        $transaction->update([
            'reference_no' => $request->reference_no,
            'date' => $request->date,
            'time' => $request->time,
            'purpose' => $request->purpose,
            'notes' => $request->notes,
            'requested_by' => $request->requested_by ?: null,
            'truck_name' => $request->truck_name ?: null,
        ]);

        // Simpan detail baru + update stok
        foreach ($request->items as $item) {
            $transaction->details()->create([
                'sparepart_id' => $item['sparepart_id'],
                'quantity' => $item['quantity'],
            ]);

            Sparepart::where('id', $item['sparepart_id'])->decrement('stock', $item['quantity']);
        }

        return redirect()->route('admin.barang-keluar')->with('success', 'Barang keluar berhasil diupdate!');
    }

    public function outgoingDestroy(BarangKeluar $transaction): RedirectResponse
    {
        // Kembalikan stok
        foreach ($transaction->details as $detail) {
            Sparepart::where('id', $detail->sparepart_id)->increment('stock', $detail->quantity);
        }

        $transaction->details()->delete();
        $transaction->delete();

        return redirect()->route('admin.barang-keluar')->with('success', 'Barang keluar berhasil dihapus!');
    }

    // Proses permintaan dari karyawan (ubah status pending → processed)
    public function outgoingProcess(BarangKeluar $transaction): RedirectResponse
    {
        // Hanya bisa proses permintaan dari karyawan
        abort_unless($transaction->requested_by, 400, 'Hanya permintaan dari karyawan yang bisa diproses.');

        // Kurangi stok sparepart untuk setiap item dalam permintaan
        foreach ($transaction->details as $detail) {
            Sparepart::where('id', $detail->sparepart_id)->decrement('stock', $detail->quantity);

            // Update item_status ke processed
            $detail->update(['item_status' => 'processed']);
        }

        $transaction->update([
            'status' => 'processed',
        ]);

        return redirect()->route('admin.barang-keluar')->with('success', "Permintaan {$transaction->reference_no} berhasil diproses! Karyawan bisa upload foto bukti pemasangan.");
    }

    // Tolak permintaan dari karyawan (ubah status pending → rejected)
    public function outgoingReject(BarangKeluar $transaction): RedirectResponse
    {
        // Hanya bisa tolak permintaan dari karyawan
        abort_unless($transaction->requested_by, 400, 'Hanya permintaan dari karyawan yang bisa ditolak.');

        // Hanya bisa tolak yang masih pending
        abort_unless($transaction->status === 'pending', 400, 'Hanya permintaan yang masih menunggu yang bisa ditolak.');

        $transaction->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('admin.barang-keluar')->with('success', "Permintaan {$transaction->reference_no} ditolak.");
    }
}
