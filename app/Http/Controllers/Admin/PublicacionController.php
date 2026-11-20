<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publicacion;

class PublicacionController extends Controller
{
    /**
     * Muestra el listado de publicaciones para el administrador.
     *
     * Patrones de Diseño aplicados:
     * 1. MVC (Controlador orquesta, Vista muestra).
     * 2. Active Record: Uso de Eloquent.
     * 3. SoftDeletes: Uso de withTrashed() para incluir registros con deleted_at no nulo.
     * 4. Eager Loading: with('refugio') para evitar el problema N+1.
     */
    public function index()
    {
        $publicaciones = Publicacion::with('refugio')
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.publicaciones.index', compact('publicaciones'));
    }

    /**
     * Da de baja lógica (SoftDelete) una publicación.
     */
    public function destroy(Publicacion $publicacion)
    {
        $publicacion->delete();

        return back()->with('success', 'Publicación dada de baja correctamente.');
    }

    /**
     * Restaura una publicación eliminada lógicamente.
     */
    public function restore($id)
    {
        $publicacion = Publicacion::withTrashed()->findOrFail($id);
        $publicacion->restore();

        return back()->with('success', 'Publicación restaurada correctamente.');
    }
}
