<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Middleware para el control de acceso basado en roles.
 * 
 * Patrón de Diseño: Chain of Responsibility (Cadena de Responsabilidad).
 * Intercepta la petición HTTP y verifica si el usuario autenticado 
 * posee el rol requerido antes de dejarlo pasar al controlador.
 */
class RoleMiddleware
{
    /**
     * Maneja una petición entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario no está logueado o su rol no es el que se pide, lo rebotamos
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'No tenés permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}