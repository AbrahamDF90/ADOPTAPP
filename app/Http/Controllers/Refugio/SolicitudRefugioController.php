<?php

namespace App\Http\Controllers\Refugio;

use App\Http\Controllers\Controller;
use App\Models\Adopcion;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Patrón: MVC (Model-View-Controller)
 * Patrón: Active Record (Eloquent)
 * Patrón: Eager Loading (with) para evitar consultas N+1
 *
 * HU-19: Gestión de solicitudes para el rol Refugio.
 * HU-20: Aceptación de solicitud (transaction script).
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

    /**
     * HU-20: Acepta una solicitud de adopción.
     *
     * Flujo atómico (Transaction Script):
     *  1. Valida que la solicitud pertenezca a una publicación del refugio autenticado.
     *  2. Valida que la solicitud esté en estado aceptable (pendiente o en_entrevista).
     *  3. Dentro de una transacción:
     *     - Marca la solicitud elegida como 'aceptada'.
     *     - Marca la publicación como 'adoptada'.
     *     - Cierra las demás solicitudes activas con motivo.
     *     - Crea el registro en la tabla `adopciones` con estado 'activa' y mascota 'adoptada'.
     *
     * Patrón de diseño: Transaction Script (DB::transaction) + MVC + Active Record.
     *
     * @param  Solicitud  $solicitud  Solicitud a aceptar (Route Model Binding).
     * @return RedirectResponse
     */
    public function aceptar(Solicitud $solicitud)
    {
        $refugio = Auth::user()->refugio;

        // Seguridad: el refugio solo puede aceptar solicitudes de SUS publicaciones.
        if (! $refugio || $solicitud->publicacion->refugio_id !== $refugio->id) {
            abort(403, 'No tienes permiso para aceptar esta solicitud.');
        }

        // Validación de estado: no se puede aceptar una solicitud ya cerrada/cancelada/aceptada.
        if (! in_array($solicitud->estado, ['pendiente', 'en_entrevista'], true)) {
            return redirect()
                ->route('refugio.solicitudes.show', $solicitud)
                ->with('error', 'La solicitud no está en un estado válido para aceptarse.');
        }

        // TODO: Refactorizar a AceptarSolicitudService cuando el controlador supere las 50 líneas (ver docs/ARQUITECTURA.md).

        DB::transaction(function () use ($solicitud) {
            $publicacion = $solicitud->publicacion;

            // 1. Aceptar la solicitud elegida.
            $solicitud->update(['estado' => 'aceptada']);

            // 2. La publicación pasa a 'adoptada'.
            $publicacion->update(['estado' => 'adoptada']);

            // 3. Cerrar las otras solicitudes activas de la misma publicación.
            $publicacion->solicitudes()
                ->where('id', '!=', $solicitud->id)
                ->whereIn('estado', ['pendiente', 'en_entrevista'])
                ->update([
                    'estado' => 'cerrada',
                    'motivo_cierre' => 'Otra solicitud fue aceptada para esta publicación.',
                ]);

            // 4. Crear el registro final de adopción (fecha_adopcion es NOT NULL en la migración).
            Adopcion::create([
                'publicacion_id' => $publicacion->id,
                'adoptante_id' => $solicitud->adoptante_id,
                'estado_adopcion' => 'activa',
                'estado_mascota' => 'adoptada',
                'fecha_adopcion' => now()->toDateString(),
            ]);
        });

        return redirect()
            ->route('refugio.solicitudes.index')
            ->with('success', 'Solicitud aceptada. La mascota fue marcada como adoptada.');
    }
}
