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

Route::middleware('auth')->group(function () {
    Route::resource('barangs', BarangController::class)->names('Barang');
});
