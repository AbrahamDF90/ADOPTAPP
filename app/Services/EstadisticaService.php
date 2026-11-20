<?php

namespace App\Services;

use App\Models\Adopcion;
use App\Models\Adoptante;
use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\Solicitud;
use Illuminate\Support\Collection;

/**
 * Patrón: Service Layer (ver docs/ARQUITECTURA.md sección 2).
 *
 * Centraliza las consultas agregadas para los dashboards de Admin (HU-28)
 * y Refugio (HU-28). Al mantenerlas acá, los controladores quedan por
 * debajo de las 50 líneas y se respeta SRP (SOLID).
 *
 * Patrón de acceso a datos: Active Record (Eloquent) + agregados
 * (count, whereIn, selectRaw con groupBy). No se usa DB::select()
 * de negocio; el selectRaw es un agregado necesario para agrupar.
 */
class EstadisticaService
{
    /**
     * Métricas globales para el dashboard del Admin.
     *
     * Patrón: Active Record (count sobre modelos Eloquent).
     *
     * @return array<string, int>
     */
    public function paraAdmin(): array
    {
        return [
            'total_adoptantes' => Adoptante::count(),
            'total_refugios' => Refugio::count(),
            'refugios_activos' => Refugio::where('activo', true)->count(),
            'total_publicaciones' => Publicacion::count(),
            'publicaciones_activas' => Publicacion::where('estado', 'activa')->count(),
            'publicaciones_adoptadas' => Publicacion::where('estado', 'adoptada')->count(),
            'solicitudes_pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'adopciones_activas' => Adopcion::where('estado_adopcion', 'activa')->count(),
        ];
    }

    /**
     * Métricas del refugio autenticado (solo sus datos).
     *
     * Patrón: Active Record + validación de pertenencia por refugio_id.
     *
     * @return array<string, int>
     */
    public function paraRefugio(Refugio $refugio): array
    {
        $publicacionesIds = $refugio->publicaciones()->pluck('id');

        return [
            'total_publicaciones' => $publicacionesIds->count(),
            'publicaciones_activas' => Publicacion::whereIn('id', $publicacionesIds)
                ->where('estado', 'activa')->count(),
            'publicaciones_adoptadas' => Publicacion::whereIn('id', $publicacionesIds)
                ->where('estado', 'adoptada')->count(),
            'solicitudes_pendientes' => Solicitud::whereIn('publicacion_id', $publicacionesIds)
                ->where('estado', 'pendiente')->count(),
            'solicitudes_en_entrevista' => Solicitud::whereIn('publicacion_id', $publicacionesIds)
                ->where('estado', 'en_entrevista')->count(),
            'adopciones_activas' => Adopcion::whereIn('publicacion_id', $publicacionesIds)
                ->where('estado_adopcion', 'activa')->count(),
        ];
    }

    /**
     * Distribución de publicaciones por estado (para tabla admin).
     *
     * Patrón: Active Record + agregado SQL (selectRaw + groupBy).
     * Se usa selectRaw porque es un agregado necesario, no SQL crudo
     * de negocio (permitido por ARQUITECTURA.md sección 2).
     *
     * @return Collection<string, int>
     */
    public function publicacionesPorEstado()
    {
        return Publicacion::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');
    }
}
