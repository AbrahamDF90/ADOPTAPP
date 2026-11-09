<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Adoptante;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }
        /**
     * Maneja la creación de un nuevo Adoptante en el sistema.
     * 
     * Patrones aplicados: 
     * - Dependency Injection: Se inyecta el objeto Request.
     * - Active Record: Uso de Eloquent para guardar los datos.
     * - Transacción DB: Garantiza la integridad de los datos. Si la creación 
     *   del perfil falla, se hace un rollback y no se crea el usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validamos todos los datos (incluyendo los nuevos)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni' => ['required', 'string', 'max:8', 'unique:adoptantes,dni'],
            'telefono' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:255'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Máximo 2MB
        ]);

        // 2. Usamos una transacción para guardar en las 2 tablas al mismo tiempo
        DB::transaction(function () use ($request) {
            
            // Guardamos la foto en el disco
            $rutaFoto = $request->file('foto')->store('perfiles', 'public');

            // Creamos el Usuario (tabla users)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'adoptante', // Le asignamos el rol automáticamente
            ]);

            // Creamos el perfil (tabla adoptantes)
            Adoptante::create([
                'user_id' => $user->id,
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'alias' => $request->name, // Usamos el nombre como alias por defecto
                'foto' => $rutaFoto,
            ]);

            event(new Registered($user));
            Auth::login($user);
        });

        // Redirigimos al dashboard
        return redirect(route('dashboard', absolute: false));
    }
}