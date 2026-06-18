<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Sparepart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockOpnameController extends Controller
{
    public function index(): View
    {
        $stockOpnames = StockOpname::with(['user', 'approver'])
            ->latest('date')
            ->get();

        return view('stock-opname.index', [
            'title' => 'Stock Opname',
            'stockOpnames' => $stockOpnames,
        ]);
    }

    public function create(): View
    {
        return view('stock-opname.create', [
            'title' => 'Buat Stock Opname',
            'spareparts' => Sparepart::with(['category', 'supplier'])->get(),
            'generatedOpnameNo' => CodeGenerator::opnameNo(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'opname_no' => 'required|unique:stock_opnames,opname_no',
            'date' => 'required|date',
            'cycle_month' => 'required|date_format:Y-m',
            'cycle_group' => 'required|in:A,B,C,D',
            'notes' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:spareparts,id',
            'items.*.physical_stock' => 'required|integer|min:0',
            'items.*.notes' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $opname = StockOpname::create([
                'opname_no' => $request->opname_no,
                'date' => $request->date,
                'cycle_month' => $request->cycle_month,
                'cycle_group' => $request->cycle_group,
                'user_id' => $request->session()->get('auth_user')['id'],
                'notes' => $request->notes,
                'status' => 'submitted',
            ]);

            foreach ($request->items as $item) {
                $sparepart = Sparepart::find($item['sparepart_id']);
                $systemStock = $sparepart->stock;
                $physicalStock = (int) $item['physical_stock'];
                $discrepancy = $physicalStock - $systemStock;

                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'sparepart_id' => $item['sparepart_id'],
                    'system_stock' => $systemStock,
                    'physical_stock' => $physicalStock,
                    'discrepancy' => $discrepancy,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.stock-opname.index')->with('success', 'Stock opname berhasil disubmit!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(StockOpname $stockOpname): View
    {
        return view('stock-opname.show', [
            'title' => 'Detail Stock Opname',
            'stockOpname' => $stockOpname->load(['user', 'approver', 'details.sparepart']),
        ]);
    }

    public function approve(StockOpname $stockOpname): RedirectResponse
    {
        $stockOpname->update([
            'status' => 'approved',
            'approved_by' => request()->session()->get('auth_user')['id'],
        ]);

        return redirect()->route('pimpinan.stock-opname.index')->with('success', 'Stock opname disetujui!');
    }

    public function reject(Request $request, StockOpname $stockOpname): RedirectResponse
    {
        $request->validate([
            'reject_notes' => 'required|string',
        ]);

        $stockOpname->update([
            'status' => 'rejected',
            'notes' => ($stockOpname->notes ? $stockOpname->notes . "\n\n" : '') . 'Ditolak: ' . $request->reject_notes,
            'approved_by' => request()->session()->get('auth_user')['id'],
        ]);

        return redirect()->route('pimpinan.stock-opname.index')->with('success', 'Stock opname ditolak.');
    }

    public function destroy(StockOpname $stockOpname): RedirectResponse
    {
        $stockOpname->details()->delete();
        $stockOpname->delete();
        return redirect()->route('admin.stock-opname.index')->with('success', 'Stock opname berhasil dihapus!');
    }
}
