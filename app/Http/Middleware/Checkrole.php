<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request
     * revisa si el rol del usuario es el permitido
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role El rol que permitimos
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario no está logueado O
        // Si el rol del usuario NO es el que requerimos
        if (!Auth::check() || Auth::user()->rol != $role) {
            // Es redirigido a la página principal.
            return redirect('/');
        }
        
        // Si el rol es correcto, dejamos pasar la solicitud
        return $next($request);
    }
}