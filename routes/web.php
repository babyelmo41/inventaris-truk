<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengajuanPembelianController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('simple.auth')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Sparepart CRUD
        Route::get('/sparepart', [InventoryController::class, 'spareparts'])->name('spareparts.index');
        Route::get('/sparepart/create', [InventoryController::class, 'sparepartCreate'])->name('spareparts.create');
        Route::post('/sparepart', [InventoryController::class, 'sparepartStore'])->name('spareparts.store');
        Route::get('/sparepart/{sparepart}/edit', [InventoryController::class, 'sparepartEdit'])->name('spareparts.edit');
        Route::put('/sparepart/{sparepart}', [InventoryController::class, 'sparepartUpdate'])->name('spareparts.update');
        Route::delete('/sparepart/{sparepart}', [InventoryController::class, 'sparepartDestroy'])->name('spareparts.destroy');

        // Category CRUD
        Route::get('/kategori', [InventoryController::class, 'categories'])->name('categories.index');
        Route::get('/kategori/create', [InventoryController::class, 'categoryCreate'])->name('categories.create');
        Route::post('/kategori', [InventoryController::class, 'categoryStore'])->name('categories.store');
        Route::get('/kategori/{category}/edit', [InventoryController::class, 'categoryEdit'])->name('categories.edit');
        Route::put('/kategori/{category}', [InventoryController::class, 'categoryUpdate'])->name('categories.update');
        Route::delete('/kategori/{category}', [InventoryController::class, 'categoryDestroy'])->name('categories.destroy');

        // Supplier CRUD
        Route::get('/supplier', [InventoryController::class, 'suppliers'])->name('suppliers.index');
        Route::get('/supplier/create', [InventoryController::class, 'supplierCreate'])->name('suppliers.create');
        Route::post('/supplier', [InventoryController::class, 'supplierStore'])->name('suppliers.store');
        Route::get('/supplier/{supplier}/edit', [InventoryController::class, 'supplierEdit'])->name('suppliers.edit');
        Route::put('/supplier/{supplier}', [InventoryController::class, 'supplierUpdate'])->name('suppliers.update');
        Route::delete('/supplier/{supplier}', [InventoryController::class, 'supplierDestroy'])->name('suppliers.destroy');

        // Users
        Route::get('/user', [InventoryController::class, 'users'])->name('users.index');
        Route::get('/user/{user}/edit', [InventoryController::class, 'userEdit'])->name('users.edit');
        Route::put('/user/{user}', [InventoryController::class, 'userUpdate'])->name('users.update');
        Route::get('/user/{user}/reset-password', [InventoryController::class, 'userPasswordForm'])->name('users.reset-password');
        Route::post('/user/{user}/reset-password', [InventoryController::class, 'userUpdatePassword'])->name('users.update-password');
        Route::post('/user/{user}/toggle-status', [InventoryController::class, 'userToggleStatus'])->name('users.toggle-status');

        // Transactions - Barang Masuk
        Route::get('/barang-masuk', [InventoryController::class, 'incoming'])->name('barang-masuk');
        Route::get('/barang-masuk/create', [InventoryController::class, 'incomingCreate'])->name('barang-masuk.create');
        Route::get('/barang-masuk/pengajuan/{pengajuan}', [InventoryController::class, 'getPengajuanDetails'])->name('barang-masuk.pengajuan-details');
        Route::post('/barang-masuk', [InventoryController::class, 'incomingStore'])->name('barang-masuk.store');
        Route::get('/barang-masuk/{transaction}/edit', [InventoryController::class, 'incomingEdit'])->name('barang-masuk.edit');
        Route::put('/barang-masuk/{transaction}', [InventoryController::class, 'incomingUpdate'])->name('barang-masuk.update');
        Route::delete('/barang-masuk/{transaction}', [InventoryController::class, 'incomingDestroy'])->name('barang-masuk.destroy');

        // Transactions - Barang Keluar
        Route::get('/barang-keluar', [InventoryController::class, 'outgoing'])->name('barang-keluar');
        Route::get('/barang-keluar/create', [InventoryController::class, 'outgoingCreate'])->name('barang-keluar.create');
        Route::post('/barang-keluar', [InventoryController::class, 'outgoingStore'])->name('barang-keluar.store');
        Route::get('/barang-keluar/{transaction}/edit', [InventoryController::class, 'outgoingEdit'])->name('barang-keluar.edit');
        Route::put('/barang-keluar/{transaction}', [InventoryController::class, 'outgoingUpdate'])->name('barang-keluar.update');
        Route::delete('/barang-keluar/{transaction}', [InventoryController::class, 'outgoingDestroy'])->name('barang-keluar.destroy');
        Route::post('/barang-keluar/{transaction}/process', [InventoryController::class, 'outgoingProcess'])->name('barang-keluar.process');
        Route::post('/barang-keluar/{transaction}/reject', [InventoryController::class, 'outgoingReject'])->name('barang-keluar.reject');

        // Reports
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

        // Pengajuan Pembelian (Admin)
        Route::get('/pengajuan', [PengajuanPembelianController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PengajuanPembelianController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanPembelianController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{pengajuan}', [PengajuanPembelianController::class, 'show'])->name('pengajuan.show');
        Route::delete('/pengajuan/{pengajuan}', [PengajuanPembelianController::class, 'destroy'])->name('pengajuan.destroy');
        Route::get('/pengajuan/sparepart/{sparepart}/last-price', [PengajuanPembelianController::class, 'getLastPrice'])->name('pengajuan.last-price');

        // Stock Opname (Admin)
        Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::get('/stock-opname/create', [StockOpnameController::class, 'create'])->name('stock-opname.create');
        Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
        Route::get('/stock-opname/{stockOpname}', [StockOpnameController::class, 'show'])->name('stock-opname.show');
        Route::delete('/stock-opname/{stockOpname}', [StockOpnameController::class, 'destroy'])->name('stock-opname.destroy');
    });

    // API endpoint untuk notifikasi polling (admin & pimpinan)
    Route::get('/api/notif', [DashboardController::class, 'getNotifCount'])->name('api.notif');

    Route::prefix('pimpinan')->name('pimpinan.')->middleware('role:pimpinan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');

        // Reports untuk pimpinan juga
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

        // Pengajuan Pembelian - Approval (Pimpinan)
        Route::get('/pengajuan', [PengajuanPembelianController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [PengajuanPembelianController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{pengajuan}/approve', [PengajuanPembelianController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{pengajuan}/reject', [PengajuanPembelianController::class, 'reject'])->name('pengajuan.reject');

        // Stock Opname - Approval (Pimpinan)
        Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::get('/stock-opname/{stockOpname}', [StockOpnameController::class, 'show'])->name('stock-opname.show');
        Route::post('/stock-opname/{stockOpname}/approve', [StockOpnameController::class, 'approve'])->name('stock-opname.approve');
        Route::post('/stock-opname/{stockOpname}/reject', [StockOpnameController::class, 'reject'])->name('stock-opname.reject');
    });

    // Karyawan Routes
    Route::prefix('karyawan')->name('karyawan.')->middleware('role:karyawan')->group(function () {
        Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('dashboard');
        Route::get('/permintaan', [KaryawanController::class, 'permintaanIndex'])->name('permintaan.index');
        Route::get('/permintaan/create', [KaryawanController::class, 'permintaanCreate'])->name('permintaan.create');
        Route::post('/permintaan', [KaryawanController::class, 'permintaanStore'])->name('permintaan.store');
        Route::get('/permintaan/{permintaan}', [KaryawanController::class, 'permintaanShow'])->name('permintaan.show');
        Route::post('/permintaan/{permintaan}/detail/{detail}/upload-after', [KaryawanController::class, 'uploadAfterPhoto'])->name('permintaan.upload-after');
        Route::get('/katalog', [KaryawanController::class, 'katalog'])->name('katalog');
    });

    Route::get('/monitoring-stok', [InventoryController::class, 'stockMonitoring'])->name('stock.monitoring');
    Route::get('/laporan/{type}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/laporan/{type}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/laporan/{type}/print', [ReportController::class, 'print'])->name('reports.print');
});
