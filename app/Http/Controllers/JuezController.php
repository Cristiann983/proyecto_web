<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Juez;
use App\Models\Evento;
use App\Models\Criterio;

class JuezController extends Controller
{
    public function index()
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

            // Obtener criterios de evaluación
            $criterios = Criterio::all();

            return view('admin.jueces.index', compact('juez', 'isAdmin', 'eventosAsignados', 'criterios'));
        }

        return redirect()->route('dashboard')
            ->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
