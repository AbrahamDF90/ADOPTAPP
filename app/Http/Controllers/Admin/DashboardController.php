<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EstadisticaService;
use Illuminate\View\View;

/**
 * Patrón: MVC + Service Layer.
 *
 * Dashboard del Admin (HU-28). La lógica de métricas vive en
 * EstadisticaService para respetar SRP y mantener el controlador
 * por debajo de las 50 líneas (docs/ARQUITECTURA.md sección 2).
 *
 * Dependency Injection: EstadisticaService se inyecta vía constructor.
 */
class DashboardController extends Controller
{
    public function __construct(private EstadisticaService $estadisticas) {}

    /**
     * Muestra el dashboard con métricas globales.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.dashboard', [
            'metricas' => $this->estadisticas->paraAdmin(),
            'porEstado' => $this->estadisticas->publicacionesPorEstado(),
        ]);
    }
}
