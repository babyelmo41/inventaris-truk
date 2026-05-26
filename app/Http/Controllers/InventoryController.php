<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Category;
use App\Models\Sparepart;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'transactions' => BarangMasuk::with(['supplier', 'user', 'details.sparepart'])->latest('date')->paginate(10),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function incomingCreate(): View
    {
        return view('transactions.incoming-form', [
            'title' => 'Tambah Barang Masuk',
            'transaction' => null,
            'suppliers' => Supplier::all(),
            'spareparts' => Sparepart::all(),
        ]);
    }

    public function incomingStore(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'invoice_no' => 'required|unique:barang_masuk,invoice_no',
            'supplier_id' => 'required|exists:suppliers,id',
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
            'spareparts' => Sparepart::all(),
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
            'transactions' => BarangKeluar::with(['user', 'details.sparepart'])->latest('date')->paginate(10),
        ]);
    }

    public function outgoingCreate(): View
    {
        return view('transactions.outgoing-form', [
            'title' => 'Tambah Barang Keluar',
            'transaction' => null,
            'spareparts' => Sparepart::where('stock', '>', 0)->get(),
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
            'spareparts' => Sparepart::all(),
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
}
