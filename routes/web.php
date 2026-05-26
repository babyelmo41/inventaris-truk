<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('simple.auth')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('role:admin_gudang')->group(function () {
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

        // Transactions - Barang Masuk
        Route::get('/barang-masuk', [InventoryController::class, 'incoming'])->name('barang-masuk');
        Route::get('/barang-masuk/create', [InventoryController::class, 'incomingCreate'])->name('barang-masuk.create');
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

        // Reports
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::prefix('pimpinan')->name('pimpinan.')->middleware('role:pimpinan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');

        // Reports untuk pimpinan juga
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::get('/monitoring-stok', [InventoryController::class, 'stockMonitoring'])->name('stock.monitoring');
    Route::get('/laporan/{type}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/laporan/{type}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
});
