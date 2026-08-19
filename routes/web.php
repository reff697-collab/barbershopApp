<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\CelenganController;
use App\Http\Controllers\ClosingBulananController;
use App\Http\Controllers\ClosingHarianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StoreDayController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Toko (Store Day)
    Route::get('/toko', [StoreDayController::class, 'index'])->name('store-day.index');

    Route::middleware(['role:barber'])->group(function () {
        Route::post('/toko/aktifkan', [StoreDayController::class, 'activateBarber'])->name('store-day.activate');
        Route::post('/toko/nonaktifkan', [StoreDayController::class, 'deactivateBarber'])->name('store-day.deactivate');
    });

    Route::middleware(['role:kasir'])->group(function () {
        Route::post('/toko/buka', [StoreDayController::class, 'confirmOpen'])->name('store-day.open');
        Route::post('/toko/tutup', [StoreDayController::class, 'confirmClose'])->name('store-day.close');
        Route::post('/toko/buka-lagi', [StoreDayController::class, 'reopen'])->name('store-day.reopen');
        Route::post('/toko/finalisasi', [StoreDayController::class, 'finalizeClosing'])->name('store-day.finalize');
    });

    // Route Kas Keluar - bersama Kasir dan Admin IT
    Route::middleware(['role:kasir|admin_it'])->group(function () {
        Route::get('/kas-keluar', [KasKeluarController::class, 'index'])->name('kas-keluar.index');
        Route::post('/kas-keluar', [KasKeluarController::class, 'store'])->name('kas-keluar.store');
    });

    // Route Transaksi Bersama untuk Kasir dan Admin IT
    Route::middleware(['role:kasir|admin_it'])->group(function () {
        Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transaksi/baru', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    });

    // Route khusus role Admin IT
    Route::middleware(['role:admin_it'])->group(function () {
        Route::resource('/layanan', ServiceController::class)
            ->except(['show'])
            ->parameters(['layanan' => 'service'])
            ->names('services');

        // Resource Route untuk Produk
        Route::resource('/produk', ProductController::class)
            ->except(['show'])
            ->parameters(['produk' => 'product'])
            ->names('products');

        // Resource Route untuk User Management
        Route::resource('/users', UserManagementController::class)
            ->except(['show'])
            ->names('users');

        // Route untuk Stok
        Route::get('/stok', [StockMovementController::class, 'index'])->name('stock.index');
        Route::post('/stok/restock', [StockMovementController::class, 'restock'])->name('stock.restock');

        // Route Hapus Transaksi (Khusus Admin IT)
        Route::delete('/transaksi/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    });

    // Route khusus role Owner
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/closing', [ClosingHarianController::class, 'index'])->name('closing.index');
        Route::get('/closing/{closing}', [ClosingHarianController::class, 'show'])->name('closing.show');

        // Route Closing Bulanan
        Route::get('/closing-bulanan', [ClosingBulananController::class, 'index'])->name('closing-bulanan.index');
        Route::post('/closing-bulanan', [ClosingBulananController::class, 'store'])->name('closing-bulanan.store');

        // Route Absensi
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    });

    // Route bersama untuk Owner dan Admin IT
    Route::middleware(['role:owner|admin_it'])->group(function () {
        Route::resource('/pengeluaran', ExpenseController::class)
            ->except(['show'])
            ->parameters(['pengeluaran' => 'expense'])
            ->names('expenses');

        // Route Pinjaman
        Route::get('/pinjaman', [LoanController::class, 'index'])->name('loans.index');
        Route::post('/pinjaman', [LoanController::class, 'store'])->name('loans.store');

        // Route Celengan
        Route::get('/celengan', [CelenganController::class, 'index'])->name('celengan.index');
        Route::post('/celengan', [CelenganController::class, 'store'])->name('celengan.store');
        Route::get('/celengan/{celengan}', [CelenganController::class, 'show'])->name('celengan.show');
        Route::post('/celengan/{celengan}/transaksi', [CelenganController::class, 'addTransaksi'])->name('celengan.transaksi');
        Route::delete('/celengan/{celengan}/transaksi/{transaksi}', [CelenganController::class, 'destroyTransaksi'])->name('celengan.transaksi.destroy');
        Route::post('/celengan/recalculate', [CelenganController::class, 'recalculateAll'])->name('celengan.recalculate');
    });
});

require __DIR__.'/auth.php';