<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PengajuController;
use App\Http\Controllers\Barang\BarangController;

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

// 1. Rute Index (Publik) - OK di atas
Route::get('/barangs', [BarangController::class, 'index'])->name('Barang.index');

// 2. Route Terproteksi (Harus Login)
Route::middleware('auth')->group(function () {

    // TARUH EXPORT & IMPORT DI ATAS {barang}
    Route::get('/barangs/export', [BarangController::class, 'export'])->name('barangs.export');
    Route::post('/barangs/import', [BarangController::class, 'import'])->name('barangs.import');

    // Resource (Isinya ada barangs/create) juga harus di atas rute {barang}
    Route::resource('barangs', BarangController::class)
        ->except(['index', 'show'])
        ->names([
            'create'  => 'Barang.create',
            'store'   => 'Barang.store',
            'edit'    => 'Barang.edit',
            'update'  => 'Barang.update',
            'destroy' => 'Barang.destroy',
        ]);

    // Fitur Cetak PDF
    Route::get('/barangs/{id}/cetak-pdf', [BarangController::class, 'cetakPdf'])->name('Barang.cetakPdf');
});

// 3. Rute Show (Detail) - HARUS PALING BAWAH
// Karena ini menggunakan parameter wildcard {barang}
Route::get('/barangs/{barang}', [BarangController::class, 'show'])->name('Barang.show');
