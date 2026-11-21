<?php

namespace App\Http\Controllers\Refugio;

use App\Http\Controllers\Controller;
use App\Models\Publicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * @return RedirectResponse
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

    /**
     * Muestra el formulario para editar una publicación existente.
     *
     * Patrón: MVC (Retorna la vista).
     * Seguridad: Validación de pertenencia (el refugio solo edita sus propias mascotas).
     */
    public function edit(Publicacion $publicacion)
    {
        // Verificamos que la publicación pertenezca al refugio logueado
        if ($publicacion->refugio_id !== auth()->user()->refugio->id) {
            abort(403, 'No tenés permiso para editar esta publicación.');
        }

        return view('refugio.publicaciones.edit', compact('publicacion'));
    }

    /**
     * Actualiza los datos de la publicación en la base de datos.
     *
     * Patrones: Active Record y Storage Pattern (para la foto).
     * Regla de Negocio: La foto es opcional al editar. Si no se sube una nueva, se mantiene la anterior.
     */
    public function update(Request $request, Publicacion $publicacion)
    {
        // Validación de pertenencia
        if ($publicacion->refugio_id !== auth()->user()->refugio->id) {
            abort(403, 'No tenés permiso para editar esta publicación.');
        }

        // Validación de datos (la foto es 'nullable' porque no es obligatorio cambiarla)
        $request->validate([
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'especie' => ['required', 'string', 'max:50'],
            'sexo' => ['required', 'string', 'max:20'],
            'edad_aproximada' => ['required', 'string', 'max:50'],
        ]);

        // Si se subió una nueva foto, reemplazamos la anterior
        if ($request->hasFile('foto')) {
            // Borramos la foto vieja del disco
            if ($publicacion->foto && Storage::disk('public')->exists($publicacion->foto)) {
                Storage::disk('public')->delete($publicacion->foto);
            }
            // Guardamos la nueva
            $rutaFoto = $request->file('foto')->store('publicaciones', 'public');
            $publicacion->foto = $rutaFoto;
        }

        // Actualizamos los datos (Active Record)
        $publicacion->especie = $request->especie;
        $publicacion->sexo = $request->sexo;
        $publicacion->edad_aproximada = $request->edad_aproximada;
        $publicacion->save();

        return redirect()->route('refugio.dashboard')->with('success', 'Publicación actualizada correctamente.');
    }

    /**
     * Da de baja lógica (SoftDelete) una publicación del refugio.
     *
     * Patrones: Active Record y SoftDeletes.
     * Seguridad: Validación de pertenencia (el refugio solo borra sus mascotas).
     * Regla de Negocio: La mascota no se borra de la base de datos, solo se marca como eliminada.
     */
    public function destroy(Request $request, Publicacion $publicacion)
    {
        // Validación de pertenencia
        if ($publicacion->refugio_id !== auth()->user()->refugio->id) {
            abort(403, 'No tenés permiso para dar de baja esta publicación.');
        }

        // Validación del motivo (requerido para saber por qué se dio de baja)
        $request->validate([
            'motivo_baja' => ['required', 'string', 'max:255'],
        ]);

        // Actualizamos el estado y guardamos el motivo antes de borrar
        $publicacion->update([
            'estado' => 'baja',
            'motivo_baja' => $request->motivo_baja,
        ]);

        // Aplicamos el SoftDelete (borrado lógico)
        $publicacion->delete();

        return redirect()->route('refugio.dashboard')->with('success', 'Publicación dada de baja correctamente.');
    }
}
