<?php

namespace App\Http\Controllers\Refugio;

use App\Http\Controllers\Controller;
use App\Models\Adopcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Patrón: MVC (Model-View-Controller)
 * Patrón: Active Record (Eloquent)
 *
 * HU-22: Refugio marca estado de mascota post-adopción.
 */
class AdopcionController extends Controller
{
    /**
     * Actualiza el estado de la mascota en una adopción.
     *
     * @return RedirectResponse
     */
    public function updateEstado(Request $request, Adopcion $adopcion)
    {
        $refugio = Auth::user()->refugio;

        // Seguridad: Validar que la adopción pertenezca a este refugio
        if ($adopcion->publicacion->refugio_id !== $refugio->id) {
            abort(403, 'No tienes permiso para modificar esta adopción.');
        }

        // Validación estricta de los estados permitidos
        $request->validate([
            'estado_mascota' => ['required', Rule::in(['adoptada', 'fallecida', 'robada', 'perdida'])],
        ]);

        // TODO: Integrar este flujo con el evento de la HU-20 cuando esté finalizada.
        // Active Record: Actualizamos el estado
        $adopcion->update([
            'estado_mascota' => $request->input('estado_mascota'),
        ]);

        return redirect()->back()->with('success', 'Estado de la mascota actualizado correctamente.');
    }
}
