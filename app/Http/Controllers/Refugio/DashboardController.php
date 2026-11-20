<?php

namespace App\Http\Controllers\Refugio;

use App\Http\Controllers\Controller;
use App\Services\EstadisticaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Patrón: MVC + Service Layer.
 *
 * Dashboard del Refugio (HU-28). Solo muestra métricas del refugio
 * autenticado (validación de pertenencia vía refugio_id).
 *
 * Dependency Injection: EstadisticaService se inyecta vía constructor.
 */
class DashboardController extends Controller
{
    public function __construct(private EstadisticaService $estadisticas) {}

    /**
     * Muestra el dashboard del refugio autenticado.
     *
     * @return View
     */
    public function index()
    {
        $refugio = Auth::user()->refugio;

        abort_if(! $refugio, 403, 'El usuario no tiene perfil de refugio.');

        return view('refugio.dashboard', [
            'metricas' => $this->estadisticas->paraRefugio($refugio),
        ]);
    }
}
