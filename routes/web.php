<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PengajuController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\Barang\BarangExportController;

Route::get('/', [DashboarController::class, 'index'])->name('dashboard');

// LOGIN
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'cek_status:1'])->group(function () {
    Route::resource('admin', AdminController::class)->names('Admin');
    Route::resource('pengaju', PengajuController::class)->names('Pengaju');
});



// 1. Rute Publik atau Tanpa Login (Jika diperlukan)
Route::get('/barang', [BarangController::class, 'index'])->name('Barang.index');
Route::get('/barangs/{barang}', [BarangController::class, 'show'])->name('Barang.show');

// 2. Rute Terproteksi (Harus Login)
Route::middleware('auth')->group(function () {

    // SEMUA ROLE (1, 2, 3) bisa Create & Store
    Route::get('/barangs/create', [BarangController::class, 'create'])->name('Barang.create');
    Route::post('/barangs', [BarangController::class, 'store'])->name('Barang.store');

    // KHUSUS ROLE 1 & 2 (Super Admin & Admin)
    // Menggunakan prefix atau logic tambahan untuk Edit, Delete, Cetak, Export, Import
    Route::middleware(['auth', 'cek_status:1, 2'])->group(function () {

        // Edit, Update, Destroy
        Route::get('/barangs/{barang}/edit', [BarangController::class, 'edit'])->name('Barang.edit');
        Route::put('/barangs/{barang}', [BarangController::class, 'update'])->name('Barang.update');
        Route::delete('/barangs/{barang}', [BarangController::class, 'destroy'])->name('Barang.destroy');

        // Cetak PDF
        Route::get('/barangs/{id}/cetak-pdf', [BarangController::class, 'cetakPdf'])->name('Barang.cetakPdf');

        // Export & Import
        Route::get('/barang/export/download', [BarangExportController::class, 'exportDownload'])->name('barangs.export.download');
        Route::get('/barang/export/server', [BarangExportController::class, 'exportToServer'])->name('barangs.export.server');
        Route::post('/barang/import', [BarangController::class, 'import'])->name('barangs.import');
    });
});
