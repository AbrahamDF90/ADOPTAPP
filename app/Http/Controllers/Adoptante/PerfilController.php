<?php

namespace App\Http\Controllers\Adoptante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    /**
     * Muestra el formulario de edición del perfil del adoptante.
     *
     * Patrón de Diseño: MVC (Retorna la vista de edición).
     * Seguridad: Middleware 'role:adoptante' asegura que solo entre el dueño.
     */
    public function edit()
    {
        $adoptante = auth()->user()->adoptante;

        return view('adoptante.perfil.edit', compact('adoptante'));
    }

    /**
     * Actualiza los datos del adoptante.
     *
     * Patrón de Diseño: Active Record (Uso de $request->user() y save()).
     * Regla de Negocio: El DNI no se puede editar (es la clave para el historial).
     */
    public function update(Request $request)
    {
        $adoptante = auth()->user()->adoptante;

        // Validación: El DNI NO se valida porque no se permite modificar.
        $request->validate([
            'alias' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Lógica para actualizar la foto (si se subió una nueva)
        if ($request->hasFile('foto')) {
            // Borramos la foto anterior si existe
            if ($adoptante->foto && Storage::disk('public')->exists($adoptante->foto)) {
                Storage::disk('public')->delete($adoptante->foto);
            }
            // Guardamos la nueva foto (Storage Pattern de Laravel)
            $rutaFoto = $request->file('foto')->store('perfiles', 'public');
            $adoptante->foto = $rutaFoto;
        }

        // Actualizamos los datos (Active Record)
        $adoptante->alias = $request->alias;
        $adoptante->direccion = $request->direccion;
        $adoptante->telefono = $request->telefono;
        $adoptante->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
