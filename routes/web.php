<?php

// =========================================================================
// IMPORTACIÓN DE CONTROLADORES
// =========================================================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // HU-28
use App\Http\Controllers\Admin\PublicacionController as AdminPublicacionController; // HU-27
use App\Http\Controllers\Admin\RefugioController;
use App\Http\Controllers\Admin\UsuarioController; // HU-26
use App\Http\Controllers\Adoptante\DashboardController;
use App\Http\Controllers\Adoptante\NotificacionController;
use App\Http\Controllers\Adoptante\PerfilController;
use App\Http\Controllers\Adoptante\SolicitudController; // HU-07
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Refugio\AdopcionController;
use App\Http\Controllers\Refugio\DashboardController as RefugioDashboardController; // HU-28
use App\Http\Controllers\Refugio\PublicacionController;
use App\Http\Controllers\Refugio\SolicitudRefugioController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// RUTAS PÚBLICAS (HU-14 y HU-15)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/publicaciones/{publicacion}', [HomeController::class, 'show'])->name('publicaciones.show');

// =========================================================================
// RUTAS GENERALES (Usuarios autenticados)
// =========================================================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================================
// RUTAS DE ADOPTANTE (HU-07, HU-16, HU-18 y HU-24)
// =========================================================================
Route::middleware(['auth', 'role:adoptante'])->prefix('mi-perfil')->group(function () {
    // HU-18
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('adoptante.dashboard');
    // HU-16
    Route::post('/publicaciones/{publicacion}/solicitar', [SolicitudController::class, 'store'])->name('solicitudes.store');
    // HU-24
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('adoptante.notificaciones.index');
    Route::patch('/notificaciones/{id}/leer', [NotificacionController::class, 'markAsRead'])->name('adoptante.notificaciones.leer');
    // HU-07
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('adoptante.perfil.edit');
    Route::patch('/perfil', [PerfilController::class, 'update'])->name('adoptante.perfil.update');
    // HU-08: Dar de baja la cuenta
    Route::delete('/perfil', [PerfilController::class, 'destroy'])->name('adoptante.perfil.destroy');
    // HU-17: Cancelar Solicitud
    Route::patch('/solicitudes/{solicitud}/cancelar', [SolicitudController::class, 'cancelar'])->name('adoptante.solicitudes.cancelar');
});

// =========================================================================
// RUTAS DE ADMINISTRADOR (HU-9, HU-26, HU-27, HU-28)
// =========================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // HU-28
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // HU-09
    Route::get('/refugios/crear', [RefugioController::class, 'create'])->name('admin.refugios.create');
    Route::post('/refugios', [RefugioController::class, 'store'])->name('admin.refugios.store');
    // HU-26
    Route::get('/adoptantes', [UsuarioController::class, 'adoptantes'])->name('admin.adoptantes.index');
    Route::get('/refugios', [UsuarioController::class, 'refugios'])->name('admin.refugios.index');
    // HU-27
    Route::get('/publicaciones', [AdminPublicacionController::class, 'index'])->name('admin.publicaciones.index');
    Route::delete('/publicaciones/{publicacion}', [AdminPublicacionController::class, 'destroy'])->name('admin.publicaciones.destroy');
    Route::post('/publicaciones/{id}/restaurar', [AdminPublicacionController::class, 'restore'])->name('admin.publicaciones.restore');
});

// =========================================================================
// RUTAS DE REFUGIO (HU-11, HU-12, HU-19, HU-20, HU-21, HU-22, HU-28)
// =========================================================================
Route::middleware(['auth', 'role:refugio'])->prefix('refugio')->name('refugio.')->group(function () {
    // HU-28
    Route::get('/dashboard', [RefugioDashboardController::class, 'index'])->name('dashboard');

    // HU-11
    Route::get('/publicaciones/crear', [PublicacionController::class, 'create'])->name('publicaciones.create');
    Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('publicaciones.store');

    // HU-12 (Editar Publicación - NUEVO)
    Route::get('/publicaciones/{publicacion}/editar', [PublicacionController::class, 'edit'])->name('publicaciones.edit');
    Route::patch('/publicaciones/{publicacion}', [PublicacionController::class, 'update'])->name('publicaciones.update');

    // HU-19, HU-20, HU-21
    Route::get('solicitudes', [SolicitudRefugioController::class, 'index'])->name('solicitudes.index');
    Route::get('solicitudes/{solicitud}', [SolicitudRefugioController::class, 'show'])->name('solicitudes.show');
    Route::patch('solicitudes/{solicitud}/aceptar', [SolicitudRefugioController::class, 'aceptar'])->name('solicitudes.aceptar');
    Route::patch('solicitudes/{solicitud}/revertir', [SolicitudRefugioController::class, 'revertir'])->name('solicitudes.revertir');

    // HU-22
    Route::patch('/adopciones/{adopcion}/estado', [AdopcionController::class, 'updateEstado'])->name('adopciones.estado');

    // HU-13: Dar de baja publicación
    Route::delete('/publicaciones/{publicacion}', [PublicacionController::class, 'destroy'])->name('publicaciones.destroy');
});

// =========================================================================
// RUTAS DE AUTENTICACIÓN (Breeze)
// =========================================================================
require __DIR__.'/auth.php';
