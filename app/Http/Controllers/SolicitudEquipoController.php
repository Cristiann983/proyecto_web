<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudEquipo;
use App\Models\Equipo;
use App\Models\Participante;
use App\Models\Perfil;

class SolicitudEquipoController extends Controller
{
    /**
     * Crear una solicitud para unirse a un equipo
     */
    public function store(Request $request, $equipoId)
    {
        $request->validate([
            'mensaje' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $equipo = Equipo::findOrFail($equipoId);

        // Verificar si el usuario ya es miembro del equipo
        $participante = Participante::where('user_id', $user->id)->first();
        if ($participante) {
            $esMiembro = $equipo->participantes()->where('participante.Id', $participante->Id)->exists();
            if ($esMiembro) {
                return back()->with('error', 'Ya eres miembro de este equipo.');
            }
        }

        // Verificar si ya existe una solicitud pendiente
        $solicitudExistente = SolicitudEquipo::where('Equipo_id', $equipoId)
            ->where('Usuario_id', $user->id)
            ->where('Estado', 'Pendiente')
            ->first();

        if ($solicitudExistente) {
            return back()->with('error', 'Ya has enviado una solicitud a este equipo que está pendiente de revisión.');
        }

        // Crear la solicitud
        SolicitudEquipo::create([
            'Equipo_id' => $equipoId,
            'Usuario_id' => $user->id,
            'Mensaje' => $request->mensaje,
            'Estado' => 'Pendiente',
        ]);

        return back()->with('success', 'Solicitud enviada correctamente. El líder del equipo revisará tu solicitud.');
    }

    /**
     * Ver solicitudes pendientes para los equipos del usuario (solo líder)
     */
    public function misSolicitudes()
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No eres un participante registrado.');
        }

        // Obtener equipos donde el usuario es líder
        $perfilLider = Perfil::where('Nombre', 'Líder')->first();
        $equiposLiderados = $participante->equipos()
            ->wherePivot('Id_perfil', $perfilLider?->Id)
            ->get();

        $equipoIds = $equiposLiderados->pluck('Id')->toArray();

        // Obtener solicitudes pendientes para estos equipos
        $solicitudes = SolicitudEquipo::whereIn('Equipo_id', $equipoIds)
            ->where('Estado', 'Pendiente')
            ->with(['equipo', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('solicitudes.mis-solicitudes', compact('solicitudes', 'equiposLiderados'));
    }

    /**
     * Aceptar una solicitud y agregar el usuario al equipo
     */
    public function aceptar(Request $request, $solicitudId)
    {
        $request->validate([
            'perfil_id' => 'required|exists:perfil,Id',
        ]);

        $solicitud = SolicitudEquipo::findOrFail($solicitudId);

        // Verificar permisos: solo el líder del equipo o admin
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();
        
        if (!$user->hasRole('Administrador')) {
            $perfilLider = Perfil::where('Nombre', 'Líder')->first();
            $esLider = $participante && $solicitud->equipo->participantes()
                ->where('participante.Id', $participante->Id)
                ->wherePivot('Id_perfil', $perfilLider?->Id)
                ->exists();

            if (!$esLider) {
                return back()->with('error', 'No tienes permisos para aceptar esta solicitud.');
            }
        }

        // Obtener o crear participante para el usuario solicitante
        $usuarioSolicitante = $solicitud->usuario;
        $participanteNuevo = Participante::where('user_id', $usuarioSolicitante->id)->first();

        if (!$participanteNuevo) {
            // Crear participante si no existe
            $participanteNuevo = Participante::create([
                'Nombre' => $usuarioSolicitante->name,
                'user_id' => $usuarioSolicitante->id,
            ]);
        }

        // Agregar participante al equipo con el rol especificado
        $solicitud->equipo->participantes()->attach(
            $participanteNuevo->Id,
            ['Id_perfil' => $request->perfil_id]
        );

        // Marcar solicitud como aceptada
        $solicitud->update(['Estado' => 'Aceptada']);

        return back()->with('success', "¡{$usuarioSolicitante->name} ha sido agregado al equipo con éxito!");
    }

    /**
     * Rechazar una solicitud
     */
    public function rechazar($solicitudId)
    {
        $solicitud = SolicitudEquipo::findOrFail($solicitudId);

        // Verificar permisos: solo el líder del equipo o admin
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();
        
        if (!$user->hasRole('Administrador')) {
            $perfilLider = Perfil::where('Nombre', 'Líder')->first();
            $esLider = $participante && $solicitud->equipo->participantes()
                ->where('participante.Id', $participante->Id)
                ->wherePivot('Id_perfil', $perfilLider?->Id)
                ->exists();

            if (!$esLider) {
                return back()->with('error', 'No tienes permisos para rechazar esta solicitud.');
            }
        }

        // Marcar solicitud como rechazada
        $solicitud->update(['Estado' => 'Rechazada']);

        return back()->with('success', 'Solicitud rechazada correctamente.');
    }

    /**
     * Ver el estado de mis solicitudes enviadas
     */
    public function miEstado()
    {
        $user = Auth::user();

        $solicitudes = SolicitudEquipo::where('Usuario_id', $user->id)
            ->with('equipo')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('solicitudes.mi-estado', compact('solicitudes'));
    }

    /**
     * Buscar equipos disponibles
     */
    public function buscarEquipos(Request $request)
    {
        $query = Equipo::with('participantes.usuario');

        if ($request->has('buscar') && !empty($request->buscar)) {
            $query->where('Nombre', 'like', '%' . $request->buscar . '%');
        }

        $equipos = $query->paginate(9);

        return view('solicitudes.buscar-equipos', compact('equipos'));
    }
}
