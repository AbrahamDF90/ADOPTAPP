<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RefugioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

// ESTA LÍNEA ES VITAL PARA QUE FUNCIONE EL LOGIN
require __DIR__.'/auth.php';

use App\Http\Controllers\Refugio\PublicacionController;

// Rutas para el Refugio
Route::middleware(['auth', 'role:refugio'])->prefix('refugio')->group(function () {
    // CAMBIAR ESTO:
    Route::get('/dashboard', function () {
        return view('refugio.dashboard'); // <-- Ahora llama a la vista
    })->name('refugio.dashboard');

    // HU-11: Crear publicación
    Route::get('/publicaciones/crear', [PublicacionController::class, 'create'])->name('refugio.publicaciones.create');
    Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('refugio.publicaciones.store');
});