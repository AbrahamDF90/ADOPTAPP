<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RefugioController;
use Illuminate\Support\Facades\Route;

// Ruta principal (Página de inicio)
Route::get('/', function () {
    return view('welcome');
});

// Dashboard General (Para usuarios logueados)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas para cualquier usuario autenticado (Perfil, etc.)
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

// Rutas de autenticación (Login, Registro, etc. que trae Breeze)
require __DIR__.'/auth.php';