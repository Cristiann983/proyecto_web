<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Juez;
use App\Models\Evento;
use App\Models\Criterio;

class JuezController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Verificar si el usuario tiene rol de Juez O Administrador
        if ($user->hasRole('Juez') || $user->hasRole('Administrador')) {
            // Obtener información del juez (si existe)
            $juez = Juez::where('user_id', $user->id)->first();

            // Verificar si es administrador
            $isAdmin = $user->hasRole('Administrador');

            // Obtener eventos asignados al juez (si no es admin puro)
            $eventosAsignados = $juez ? $juez->eventos()
                ->where('Fecha_fin', '>=', now())
                ->with(['equipos.participantes.usuario', 'equipos.proyecto'])
                ->get() : collect();

            // Si se selecciona un evento específico, paginar sus equipos
            $eventoSeleccionado = null;
            $equiposPaginados = null;
            $criterios = collect(); // Criterios vacíos por defecto
            
            if ($request->has('evento_id') && !empty($request->evento_id)) {
                $eventoSeleccionado = Evento::with(['equipos.participantes.usuario', 'equipos.proyectos.calificaciones.juez', 'criterios'])->find($request->evento_id);
                
                if ($eventoSeleccionado) {
                    // Paginar equipos (6 por página)
                    $equipos = $eventoSeleccionado->equipos()->with(['participantes.usuario', 'proyectos.calificaciones.juez'])->paginate(6);
                    $equiposPaginados = $equipos;
                    
                    // Obtener criterios específicos del evento
                    $criterios = $eventoSeleccionado->criterios;
                }
            }

            return view('admin.jueces.index', compact('juez', 'isAdmin', 'eventosAsignados', 'criterios', 'eventoSeleccionado', 'equiposPaginados'));
        }

        return redirect()->route('dashboard')
            ->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
