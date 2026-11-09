<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Muestra la galería pública de mascotas en adopción.
     * 
     * Patrones de Diseño aplicados:
     * 1. MVC (Model-View-Controller): Delega la presentación a la vista 'welcome'.
     * 2. Active Record: Utiliza el modelo Publicacion para consultar la BD.
     * 3. Eager Loading (Carga Ansiosa): Uso de 'with("refugio")' para evitar el problema de consultas N+1.
     */
    public function index()
    {
        $publicaciones = Publicacion::with('refugio')
            ->where('estado', 'activa')
            ->orderBy('created_at', 'asc')
            ->paginate(12);

        return view('welcome', compact('publicaciones'));
    }
}