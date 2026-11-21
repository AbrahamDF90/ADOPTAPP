<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RefugioController extends Controller
{
    /**
     * Muestra el formulario para dar de alta un nuevo Refugio.
     *
     * Patrón de Diseño: MVC (Model-View-Controller) - Delega la vista.
     */
    public function create()
    {
        // Retorna la vista ubicada en resources/views/admin/refugios/create.blade.php
        return view('admin.refugios.create');
    }

    /**
     * Procesa, valida y guarda los datos del nuevo Refugio.
     *
     * Patrones de Diseño:
     * 1. Active Record: Uso de los modelos User y Refugio para interactuar con la BD.
     * 2. Transaction Script: Uso de DB::transaction para garantizar atomicidad.
     */
    public function store(Request $request)
    {
        // Validación: Asegura que los datos cumplan las reglas antes de tocar la BD.
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // El email debe ser único
            'password' => ['required', 'string', 'min:8'],
            'ubicacion' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
        ]);

        // Patrón Transaction Script:
        // Si falla la creación del Refugio, se cancela (rollback) la del Usuario, y viceversa.
        DB::transaction(function () use ($request) {

            // 1. Creamos el Usuario (credenciales de login)
            $user = User::create([
                'name' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'refugio', // Asignación estricta del rol
            ]);

            // 2. Creamos el perfil del Refugio (datos adicionales)
            Refugio::create([
                'user_id' => $user->id, // Enlace foráneo
                'nombre' => $request->nombre,
                'ubicacion' => $request->ubicacion,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'activo' => true,
            ]);
        });

        // Redirección al panel con mensaje de éxito
        return redirect()->route('admin.dashboard')->with('success', 'Refugio creado con éxito.');
    }

    /**
     * Muestra el formulario para editar un refugio.
     */
    public function edit(Refugio $refugio)
    {
        return view('admin.refugios.edit', compact('refugio'));
    }

    /**
     * Actualiza los datos del refugio.
     *
     * Patrón: Active Record.
     */
    public function update(Request $request, Refugio $refugio)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'ubicacion' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
        ]);

        $refugio->update($request->only('nombre', 'ubicacion', 'direccion', 'telefono'));

        return redirect()->route('admin.refugios.index')->with('success', 'Refugio actualizado correctamente.');
    }

    /**
     * Bloquea o desbloquea un refugio.
     *
     * Patrones de Diseño:
     * 1. Transaction Script: Al bloquear, también se dan de baja todas sus publicaciones activas.
     * 2. Active Record: Uso de update() en el refugio y en sus publicaciones.
     */
    public function toggleActivo(Refugio $refugio)
    {
        DB::transaction(function () use ($refugio) {
            // Invertimos el estado actual (si está activo, lo bloqueamos; si está bloqueado, lo activamos)
            $nuevoEstado = ! $refugio->activo;
            $refugio->update(['activo' => $nuevoEstado]);

            // Si se está bloqueando (activo = false), damos de baja sus publicaciones activas
            if (! $nuevoEstado) {
                $refugio->publicaciones()->where('estado', 'activa')->update([
                    'estado' => 'baja',
                    'motivo_baja' => 'Refugio bloqueado por el administrador.',
                ]);
            }
        });

        $mensaje = $refugio->activo ? 'Refugio habilitado correctamente.' : 'Refugio bloqueado y sus publicaciones dadas de baja.';

        return back()->with('success', $mensaje);
    }
}
