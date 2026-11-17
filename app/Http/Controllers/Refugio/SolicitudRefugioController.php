<?php

namespace App\Http\Controllers\Refugio;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Patrón: MVC (Model-View-Controller)
 * Patrón: Active Record (Eloquent)
 * Patrón: Eager Loading (with) para evitar consultas N+1
 *
 * HU-19: Gestión de solicitudes para el rol Refugio.
 */
class SolicitudRefugioController extends Controller
{
    /**
     * Lista las solicitudes de las publicaciones del refugio autenticado.
     */
    public function index(Request $request)
    {
        $request->validate([
            'estado' => 'nullable|in:pendiente,en_entrevista,aceptada,cerrada,cancelada',
        ]);

        $refugio = Auth::user()->refugio;

        $query = Solicitud::with(['publicacion', 'adoptante.user'])
            ->whereHas('publicacion', function ($q) use ($refugio) {
                $q->where('refugio_id', $refugio->id);
            });

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $solicitudes = $query->latest()->paginate(10);

        return view('refugio.solicitudes.index', compact('solicitudes'));
    }

    /**
     * Muestra la ficha del adoptante (solo si la solicitud es del refugio).
     */
    public function show(Solicitud $solicitud)
    {
        $refugio = Auth::user()->refugio;

        if ($solicitud->publicacion->refugio_id !== $refugio->id) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }

        $solicitud->load(['publicacion', 'adoptante.user']);

        return view('refugio.solicitudes.show', compact('solicitud'));
    }
}
