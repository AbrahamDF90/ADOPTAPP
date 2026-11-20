<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adoptante;
use App\Models\Refugio;
use Illuminate\View\View;

/**
 * Patrón: MVC (Model-View-Controller).
 *
 * Controlador de solo lectura para el panel Admin (HU-26).
 * Lista adoptantes y refugios con Eager Loading para evitar N+1.
 *
 * No modifica ni depende de Admin\RefugioController (HU-09): solo lectura.
 *
 * Deuda Técnica: si se agregan filtros complejos (búsqueda, orden,
 * exportaciones) o el controlador supera las 50 líneas, extraer a
 * Admin\UsuarioService (Service Layer, ver docs/ARQUITECTURA.md sección 2).
 */
class UsuarioController extends Controller
{
    /**
     * Lista los adoptantes registrados con su usuario asociado.
     *
     * Patrón: Active Record + Eager Loading (with) para evitar N+1.
     *
     * @return View
     */
    public function adoptantes()
    {
        $adoptantes = Adoptante::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.usuarios.adoptantes', compact('adoptantes'));
    }

    /**
     * Lista los refugios registrados con su usuario y estado activo.
     *
     * Patrón: Active Record + Eager Loading.
     *
     * @return View
     */
    public function refugios()
    {
        $refugios = Refugio::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.usuarios.refugios', compact('refugios'));
    }
}
