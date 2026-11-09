<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RefugioController;
use App\Http\Controllers\Refugio\PublicacionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTAS PÚBLICAS (HU-14)
// ==========================================
// Patrón: MVC (Delega al HomeController)
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==========================================
// RUTAS GENERALES (Usuarios autenticados)
// ==========================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// RUTAS DE ADMINISTRADOR (HU-9)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/refugios/crear', [RefugioController::class, 'create'])->name('admin.refugios.create');
    Route::post('/refugios', [RefugioController::class, 'store'])->name('admin.refugios.store');
});

// ==========================================
// RUTAS DE REFUGIO (HU-11)
// ==========================================
Route::middleware(['auth', 'role:refugio'])->prefix('refugio')->group(function () {
    Route::get('/dashboard', function () {
        return view('refugio.dashboard');
    })->name('refugio.dashboard');

    Route::get('/publicaciones/crear', [PublicacionController::class, 'create'])->name('refugio.publicaciones.create');
    Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('refugio.publicaciones.store');
});

// ==========================================
// RUTAS DE AUTENTICACIÓN (Breeze)
// ==========================================
require __DIR__.'/auth.php';