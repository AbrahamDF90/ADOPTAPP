<?php

namespace App\Http\Controllers\Adoptante;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Patrón: MVC (Model-View-Controller)
 * Patrón: Active Record (Eloquent)
 * HU-24: Gestión de notificaciones in-app para el adoptante.
 */
class NotificacionController extends Controller
{
    /**
     * Muestra el listado de notificaciones del adoptante.
     *
     * @return View
     */
    public function index()
    {
        // Active Record: Obtenemos solo las notificaciones del usuario logueado
        $notificaciones = Auth::user()->notifications()->paginate(10);

        return view('adoptante.notificaciones.index', compact('notificaciones'));
    }

    /**
     * Marca una notificación como leída.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function markAsRead($id)
    {
        // Seguridad: findOrFail asegura que el usuario solo pueda leer SUS notificaciones
        $notificacion = Auth::user()->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }
}
