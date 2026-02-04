<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PengajuController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\Barang\BarangExportController;

Route::get('/', [DashboarController::class, 'index'])->name('dashboard');

// LOGIN & LOGOUT
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// KHUSUS SUPER ADMIN (Role 1)
Route::middleware(['auth', 'cek_status:1'])->group(function () {
    Route::resource('admin', AdminController::class)->names('Admin');
    Route::resource('pengaju', PengajuController::class)->names('Pengaju');
});

    // 1. Rute Index (Daftar Barang)
Route::get('/barang', [BarangController::class, 'index'])->name('Barang.index');

// RUTE BARANG
Route::middleware('auth')->group(function () {



    // 2. Rute Create (SEMUA ROLE 1,2,3)
    // WAJIB DI ATAS {barang} agar tidak dianggap sebagai ID
    Route::get('/barangs/create', [BarangController::class, 'create'])->name('Barang.create');
    Route::post('/barangs', [BarangController::class, 'store'])->name('Barang.store');

    // 3. Rute Khusus Admin & Super Admin (Role 1 & 2)
    Route::middleware(['cek_status:1,2'])->group(function () {
        // Export & Import (Taruh di atas rute parameter agar aman)
        Route::get('/barang/export/download', [BarangExportController::class, 'exportDownload'])->name('barangs.export.download');
        Route::get('/barang/export/server', [BarangExportController::class, 'exportToServer'])->name('barangs.export.server');
        Route::post('/barang/import', [BarangController::class, 'import'])->name('barangs.import');

        // Edit, Update, Destroy
        Route::get('/barangs/{barang}/edit', [BarangController::class, 'edit'])->name('Barang.edit');
        Route::put('/barangs/{barang}', [BarangController::class, 'update'])->name('Barang.update');
        Route::delete('/barangs/{barang}', [BarangController::class, 'destroy'])->name('Barang.destroy');
        Route::get('/barangs/{id}/cetak-pdf', [BarangController::class, 'cetakPdf'])->name('Barang.cetakPdf');
        Route::get('/barang/cetak-pdf', [BarangController::class, 'cetakData'])->name('Barang.cetakData');
    });
});
    // 4. Rute Show (Detail Barang) - TARUH PALING BAWAH
    Route::get('/barangs/{barang}', [BarangController::class, 'show'])->name('Barang.show');
