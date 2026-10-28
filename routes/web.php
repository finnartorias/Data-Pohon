<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicTreeController;
use App\Http\Controllers\TreeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Beranda: Redirect ke dashboard jika sudah login, atau portal publik
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('trees.index');
})->name('home');

// Halaman Publik (Tanpa Login / Scan QR Code)
Route::get('/pohon/public/{id_pohon}', [PublicTreeController::class, 'show'])->name('pohon.public');
Route::get('/pohon/{id_pohon}/qrcode.svg', [PublicTreeController::class, 'rawQr'])->name('pohon.qrcode.raw');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Admin Protected Routes (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD & Fitur Pohon
    Route::resource('trees', TreeController::class);

    // Fitur Tambahan QR Code
    Route::get('/trees/{tree}/print-qr', [TreeController::class, 'printQr'])->name('trees.print-qr');
    Route::get('/trees/{tree}/download-qr/{format?}', [TreeController::class, 'downloadQr'])->name('trees.download-qr');
    Route::post('/trees/{tree}/regenerate-qr', [TreeController::class, 'regenerateQr'])->name('trees.regenerate-qr');
});
