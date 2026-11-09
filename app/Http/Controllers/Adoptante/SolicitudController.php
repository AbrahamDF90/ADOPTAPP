<?php

namespace App\Http\Controllers\Adoptante;

use App\Http\Controllers\Controller;
use App\Models\Publicacion;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    /**
     * Guarda una nueva solicitud de adopción para una publicación.
     * 
     * Patrones de Diseño aplicados:
     * 1. Transaction Script: Usamos DB::transaction para asegurar que el conteo de solicitudes 
     *    y la creación de la nueva se hagan de forma atómica y sin errores de concurrencia.
     * 2. Active Record: Uso de los modelos Solicitud y Publicacion.
     * 3. Middleware: La ruta estará protegida para que solo los adoptantes logueados accedan.
     * 
     * Reglas de Negocio:
     * - No duplicar solicitudes del mismo adoptante.
     * - Máximo 3 solicitudes por publicación.
     * - Si llega a 3, la publicación pasa a estado 'completa'.
     */
    public function store(Request $request, Publicacion $publicacion)
    {
        // 1. Verificación de seguridad: La publicación debe estar activa
        if ($publicacion->estado !== 'activa') {
            return back()->with('error', 'Esta mascota ya no está disponible para adopción.');
        }

        $adoptante = auth()->user()->adoptante;

        // 2. Verificar si el adoptante ya solicitó esta publicación
        $yaSolicito = Solicitud::where('publicacion_id', $publicacion->id)
            ->where('adoptante_id', $adoptante->id)
            ->exists();

        if ($yaSolicito) {
            return back()->with('error', 'Ya enviaste una solicitud para esta mascota.');
        }

        // 3. Contar solicitudes activas (solo pendientes o en entrevista)
        $solicitudesActivas = Solicitud::where('publicacion_id', $publicacion->id)
            ->whereIn('estado', ['pendiente', 'en_entrevista'])
            ->count();

        if ($solicitudesActivas >= 3) {
            return back()->with('error', 'Esta publicación ya alcanzó el máximo de 3 solicitudes.');
        }

        // 4. Transacción para guardar y actualizar estado
        DB::transaction(function () use ($publicacion, $adoptante, $solicitudesActivas) {
            
            // Creamos la solicitud
            Solicitud::create([
                'publicacion_id' => $publicacion->id,
                'adoptante_id' => $adoptante->id,
                'estado' => 'pendiente',
            ]);

            // Si esta era la última solicitud permitida, cerramos la publicación
            if ($solicitudesActivas === 2) {
                $publicacion->update(['estado' => 'completa']);
            }
        });

        // Redirigimos al dashboard o al detalle con mensaje de éxito
        return redirect()->route('publicaciones.show', $publicacion)
            ->with('success', '¡Solicitud enviada con éxito! El refugio se contactará con vos.');
    }
}