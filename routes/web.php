<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // HU-28
use App\Http\Controllers\Admin\PublicacionController as AdminPublicacionController; // HU-27 (alias)
use App\Http\Controllers\Admin\RefugioController;
use App\Http\Controllers\Admin\UsuarioController; // HU-26
use App\Http\Controllers\Adoptante\DashboardController;
use App\Http\Controllers\Adoptante\NotificacionController;
use App\Http\Controllers\Adoptante\SolicitudController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Refugio\AdopcionController;
use App\Http\Controllers\Refugio\DashboardController as RefugioDashboardController; // HU-28
use App\Http\Controllers\Refugio\PublicacionController;
use App\Http\Controllers\Refugio\SolicitudRefugioController;
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTAS PÚBLICAS (HU-14 y HU-15)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/publicaciones/{publicacion}', [HomeController::class, 'show'])->name('publicaciones.show');

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
// RUTAS DE ADOPTANTE (HU-16, HU-18 y HU-24)
// ==========================================
// Patrón: Middleware (protege las rutas para que solo entren adoptantes)
Route::middleware(['auth', 'role:adoptante'])->prefix('mi-perfil')->group(function () {

    // HU-18: Panel de solicitudes del adoptante
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('adoptante.dashboard');

    // HU-16: Procesar la solicitud
    Route::post('/publicaciones/{publicacion}/solicitar', [SolicitudController::class, 'store'])->name('solicitudes.store');

    // HU-24: Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('adoptante.notificaciones.index');
    Route::patch('/notificaciones/{id}/leer', [NotificacionController::class, 'markAsRead'])->name('adoptante.notificaciones.leer');
});

// ==========================================
// RUTAS DE ADMINISTRADOR (HU-9  HU-26  HU-27  HU-28)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // HU-28: Dashboard con métricas globales (antes closure, ahora controlador)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // HU-09: Alta de refugios
    Route::get('/refugios/crear', [RefugioController::class, 'create'])->name('admin.refugios.create');
    Route::post('/refugios', [RefugioController::class, 'store'])->name('admin.refugios.store');

    // HU-26: Listado de usuarios (solo lectura)
    Route::get('/adoptantes', [UsuarioController::class, 'adoptantes'])->name('admin.adoptantes.index');
    Route::get('/refugios', [UsuarioController::class, 'refugios'])->name('admin.refugios.index');

    // HU-27: Ver publicaciones de refugios (por el admin) — usa el alias AdminPublicacionController
    Route::get('/publicaciones', [AdminPublicacionController::class, 'index'])->name('admin.publicaciones.index');
    Route::delete('/publicaciones/{publicacion}', [AdminPublicacionController::class, 'destroy'])->name('admin.publicaciones.destroy');
    Route::post('/publicaciones/{id}/restaurar', [AdminPublicacionController::class, 'restore'])->name('admin.publicaciones.restore');
});

// ==========================================
// RUTAS DE REFUGIO (HU-11  HU-28)
// ==========================================
Route::middleware(['auth', 'role:refugio'])->prefix('refugio')->group(function () {
    // HU-28: Dashboard con métricas del refugio (antes closure, ahora controlador)
    Route::get('/dashboard', [RefugioDashboardController::class, 'index'])->name('refugio.dashboard');

    Route::get('/publicaciones/crear', [PublicacionController::class, 'create'])->name('refugio.publicaciones.create');
    Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('refugio.publicaciones.store');
});

// ==========================================
// RUTAS DE AUTENTICACIÓN (Breeze)
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// HU-19: Rutas para el Refugio
// HU-20: Aceptar solicitud
// HU-21: Revertir adopción
// HU-22: Actualizar estado post-adopción
// ==========================================
Route::middleware(['auth', 'role:refugio'])->prefix('refugio')->name('refugio.')->group(function () {
    Route::get('solicitudes', [SolicitudRefugioController::class, 'index'])->name('solicitudes.index');
    Route::get('solicitudes/{solicitud}', [SolicitudRefugioController::class, 'show'])->name('solicitudes.show');

    // HU-20: Aceptar solicitud
    Route::patch('solicitudes/{solicitud}/aceptar', [SolicitudRefugioController::class, 'aceptar'])
        ->name('solicitudes.aceptar');

    // HU-21: Revertir adopción
    Route::patch('solicitudes/{solicitud}/revertir', [SolicitudRefugioController::class, 'revertir'])
        ->name('solicitudes.revertir');

    // HU-22: Actualizar estado post-adopción
    Route::patch('/adopciones/{adopcion}/estado', [AdopcionController::class, 'updateEstado'])->name('adopciones.estado');
});
