<?php

namespace App\Http\Controllers\Adoptante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra el panel del adoptante con su historial de solicitudes.
     * 
     * Patrones de Diseño aplicados:
     * 1. MVC (Model-View-Controller): El controlador orquesta la petición y retorna la vista.
     * 2. Active Record: Uso de las relaciones Eloquent (solicitudes()) para obtener los datos.
     * 3. Eager Loading (Carga Ansiosa): Uso de ->with('publicacion') para evitar el problema de consultas N+1 al iterar las solicitudes en la vista.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtenemos el perfil del adoptante logueado
        $adoptante = auth()->user()->adoptante;

        // Si por alguna razón el usuario no tiene perfil de adoptante, lo devolvemos
        if (!$adoptante) {
            return redirect()->route('dashboard')->with('error', 'No tenés un perfil de adoptante.');
        }

        // Traemos sus solicitudes ordenadas por fecha, incluyendo los datos de la publicación
        $solicitudes = $adoptante->solicitudes()
            ->with('publicacion')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('adoptante.dashboard', compact('solicitudes'));
    }
}