<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Invitacion;
use App\Models\Participante;

class InvitacionController extends Controller
{
    /**
     * Mostrar todas las invitaciones del usuario
     */
    public function index()
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return view('invitaciones.index', ['invitaciones' => collect()]);
        }

        // Obtener invitaciones del participante con todas las relaciones
        $invitaciones = Invitacion::where('Participante_id', $participante->Id)
            ->with(['equipo', 'perfil', 'invitadoPor', 'invitadoPor.usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invitaciones.index', compact('invitaciones', 'participante'));
    }

    /**
     * Aceptar una invitación
     */
    public function aceptar($id)
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para aceptar invitaciones.');
        }

        $invitacion = Invitacion::findOrFail($id);

        // Verificar que la invitación es para este participante
        if ($invitacion->Participante_id != $participante->Id) {
            return back()->with('error', 'Esta invitación no es para ti.');
        }

        // Verificar que está pendiente
        if ($invitacion->Estado != 'Pendiente') {
            return back()->with('error', 'Esta invitación ya fue procesada.');
        }

        DB::beginTransaction();

        try {
            // Agregar al participante al equipo
            DB::table('participante_equipo')->insert([
                'Id_participante' => $participante->Id,
                'Id_equipo' => $invitacion->Equipo_id,
                'Id_perfil' => $invitacion->Perfil_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Marcar invitación como aceptada
            $invitacion->update(['Estado' => 'Aceptada']);

            DB::commit();

            return redirect()->route('equipos.show', $invitacion->Equipo_id)
                ->with('success', '¡Te has unido al equipo exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aceptar la invitación: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar una invitación
     */
    public function rechazar($id)
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para rechazar invitaciones.');
        }

        $invitacion = Invitacion::findOrFail($id);

        // Verificar que la invitación es para este participante
        if ($invitacion->Participante_id != $participante->Id) {
            return back()->with('error', 'Esta invitación no es para ti.');
        }

        // Verificar que está pendiente
        if ($invitacion->Estado != 'Pendiente') {
            return back()->with('error', 'Esta invitación ya fue procesada.');
        }

        // Marcar como rechazada
        $invitacion->update(['Estado' => 'Rechazada']);

        return back()->with('success', 'Invitación rechazada.');
    }

    /**
     * Cancelar una invitación (solo el que invitó o el líder)
     */
    public function cancelar($id)
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para cancelar invitaciones.');
        }

        $invitacion = Invitacion::findOrFail($id);

        // Verificar que el usuario es quien invitó
        if ($invitacion->InvitadoPor_id != $participante->Id) {
            return back()->with('error', 'Solo quien envió la invitación puede cancelarla.');
        }

        // Verificar que está pendiente
        if ($invitacion->Estado != 'Pendiente') {
            return back()->with('error', 'Solo se pueden cancelar invitaciones pendientes.');
        }

        $invitacion->delete();

        return back()->with('success', 'Invitación cancelada exitosamente.');
    }
}
