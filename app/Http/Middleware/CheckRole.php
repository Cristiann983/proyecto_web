<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta está inactiva.');
        }

        $allowedRoles = array_map('trim', explode(',', $roles));
        
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Redirigir según el rol del usuario
        if ($user->hasRole('Administrador')) {
            return redirect()->route('admin.equipos.index')
                ->with('error', 'No tienes permisos para acceder a esa sección.');
        } elseif ($user->hasRole('Juez')) {
            return redirect()->route('admin.jueces.index')
                ->with('error', 'No tienes permisos para acceder a esa sección.');
        } elseif ($user->hasRole('Participante')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esa sección.');
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'No tienes un rol asignado.');
    }
}
