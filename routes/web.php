<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\JenisSampahController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanPusatController;
use App\Http\Controllers\KategoriSampahController; // WAJIB DIIMPOR

// Redirect root ke login
Route::redirect('/', '/login');

// Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/lupa-password', [ForgotPasswordController::class, 'index'])->name('password.request');
    Route::post('/lupa-password/send', [ForgotPasswordController::class, 'send'])->name('password.send');
    Route::post('/lupa-password/update', [ForgotPasswordController::class, 'update'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // CRUD Nasabah
    Route::resource('nasabah', NasabahController::class);

    // BARU: CRUD Kategori Sampah
    Route::resource('kategori', KategoriSampahController::class);

    // CRUD Jenis Sampah
    Route::resource('jenissampah', JenisSampahController::class);

    // SETORAN
    Route::get('/setoran', [SetoranController::class, 'index'])->name('setoran.index');
    Route::get('/setoran/create', [SetoranController::class, 'create'])->name('setoran.create');
    Route::post('/setoran', [SetoranController::class, 'store'])->name('setoran.store');

    Route::get('/setoran/{group_id}/edit', [SetoranController::class, 'edit'])->name('setoran.edit');
    Route::put('/setoran/{group_id}', [SetoranController::class, 'update'])->name('setoran.update');
    Route::delete('/setoran/{group_id}', [SetoranController::class, 'destroy'])->name('setoran.destroy');

    // FITUR PENJUALAN KE PUSAT
    Route::get('/penjualan', [PenjualanPusatController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanPusatController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanPusatController::class, 'store'])->name('penjualan.store');
    Route::delete('/penjualan/{id}', [PenjualanPusatController::class, 'destroy'])->name('penjualan.destroy');

    Route::get('/setoran/export/pdf', [SetoranController::class, 'exportRekapJenisPdf'])
        ->name('setoran.export.pdf');
});