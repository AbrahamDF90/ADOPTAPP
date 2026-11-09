<?php

namespace App\Http\Controllers\Refugio;

use App\Http\Controllers\Controller;
use App\Models\Publicacion;
use Illuminate\Http\Request;

class PublicacionController extends Controller
{
    /**
     * Muestra el formulario para subir una nueva mascota.
     * Patrón: MVC (Retorna la vista al usuario).
     */
    public function create()
    {
        return view('refugio.publicaciones.create');
    }

    /**
     * Guarda la publicación en la base de datos.
     * Patrón: Active Record (Uso de Eloquent).
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validación de datos (Escalabilidad y Seguridad)
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Máx 2MB
            'especie' => ['required', 'string', 'max:50'],
            'sexo' => ['required', 'string', 'max:20'],
            'edad_aproximada' => ['required', 'string', 'max:50'],
        ]);

        // 2. Guardamos la foto en el disco (storage/app/public/publicaciones)
        $rutaFoto = $request->file('foto')->store('publicaciones', 'public');

        // 3. Creamos el registro en la base de datos
        Publicacion::create([
            // Obtenemos el ID del refugio del usuario logueado
            'refugio_id' => auth()->user()->refugio->id,
            'foto' => $rutaFoto,
            'especie' => $request->especie,
            'sexo' => $request->sexo,
            'edad_aproximada' => $request->edad_aproximada,
            'estado' => 'activa', // Estado por defecto
        ]);

        // 4. Redirigimos al panel del refugio (lo crearemos después)
        return redirect()->route('refugio.dashboard')->with('success', 'Mascota publicada con éxito.');
    }
}