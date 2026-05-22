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
            'spareparts' => Sparepart::with(['category', 'supplier'])->get(),
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
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required',
        ]);

        Sparepart::create($request->all());

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil ditambahkan!');
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
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required',
        ]);

        $sparepart->update($request->all());

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
            'categories' => Category::withCount('spareparts')->get(),
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
            'suppliers' => Supplier::withCount('spareparts')->get(),
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
            'users' => User::all(),
        ]);
    }

    // ============================================
    // STOCK MONITORING
    // ============================================
    public function stockMonitoring(): View
    {
        return view('inventory.monitoring', [
            'title' => 'Monitoring Stok',
            'spareparts' => Sparepart::with(['category', 'supplier'])->get(),
            'categories' => Category::pluck('name')->toArray(),
        ]);
    }

    // ============================================
    // TRANSACTIONS
    // ============================================
    public function incoming(): View
    {
        return view('transactions.incoming', [
            'title' => 'Barang Masuk',
            'transactions' => BarangMasuk::with(['supplier', 'user', 'details.sparepart'])->latest('date')->get(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function outgoing(): View
    {
        return view('transactions.outgoing', [
            'title' => 'Barang Keluar',
            'transactions' => BarangKeluar::with(['user', 'details.sparepart'])->latest('date')->get(),
        ]);
    }
}
