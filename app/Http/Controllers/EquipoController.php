<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Equipo;
use App\Models\Participante;
use App\Models\Perfil;
use App\Models\Invitacion;

class EquipoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return view('equipos.index', ['equipos' => collect()]);
        }

        // ✅ Obtener equipos del participante con sus miembros, proyectos y calificaciones
        $equipos = Equipo::whereIn('Id', function($query) use ($participante) {
            $query->select('Id_equipo')
                  ->from('participante_equipo')
                  ->where('Id_participante', $participante->Id);
        })
        ->with([
            'participantes.usuario',
            'proyectos.calificaciones.juez',
            'proyectos.calificaciones.criterio',
            'proyectos.evento'
        ])
        ->get();

        // Cargar perfil para cada participante en cada equipo
        foreach ($equipos as $equipo) {
            foreach ($equipo->participantes as $participanteEquipo) {
                $relacionEquipo = DB::table('participante_equipo')
                    ->where('Id_equipo', $equipo->Id)
                    ->where('Id_participante', $participanteEquipo->Id)
                    ->first();
                
                if ($relacionEquipo) {
                    $perfil = Perfil::find($relacionEquipo->Id_perfil);
                    $participanteEquipo->perfil = $perfil;
                }
            }
        }

        // Obtener todos los perfiles para el formulario de invitación
        $perfiles = Perfil::all();

        return view('dashboard', compact('equipos', 'perfiles'));
    }

    public function create()
    {
        return view('equipos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:equipo,Nombre',
        ]);

        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'Debes ser un participante para crear un equipo.');
        }

        DB::beginTransaction();

        try {
            // Crear el equipo
            $equipo = Equipo::create([
                'Nombre' => $request->nombre,
            ]);

            // Obtener el perfil de "Líder"
            $perfilLider = Perfil::where('Nombre', 'Líder')->first();

            if (!$perfilLider) {
                DB::rollBack();
                return back()->with('error', 'No se encontró el perfil de Líder en el sistema.');
            }

            // Asignar al creador como líder
            DB::table('participante_equipo')->insert([
                'Id_participante' => $participante->Id,
                'Id_equipo' => $equipo->Id,
                'Id_perfil' => $perfilLider->Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('equipos.show', $equipo->Id)
                ->with('success', '¡Equipo creado exitosamente! Eres el líder del equipo.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el equipo: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        // ✅ Cargar equipo con participantes, proyectos y calificaciones
        $equipo = Equipo::with([
            'participantes.usuario',
            'participantes.carrera',
            'proyectos.calificaciones.juez.especialidad',
            'proyectos.calificaciones.criterio',
            'proyectos.evento'
        ])->findOrFail($id);

        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar si el usuario actual es miembro del equipo
        $esMiembro = false;
        $esLider = false;

        if ($participante) {
            $miembro = DB::table('participante_equipo')
                ->where('Id_equipo', $equipo->Id)
                ->where('Id_participante', $participante->Id)
                ->first();

            if ($miembro) {
                $esMiembro = true;

                // Verificar si es líder
                $perfilLider = Perfil::where('Nombre', 'Líder')->first();
                if ($perfilLider && $miembro->Id_perfil == $perfilLider->Id) {
                    $esLider = true;
                }
            }
        }

        // Obtener todos los perfiles disponibles para el formulario de invitación
        $perfiles = Perfil::all();

        // Cargar perfil para cada participante desde la tabla pivote
        foreach ($equipo->participantes as $participante) {
            $participanteEquipo = DB::table('participante_equipo')
                ->where('Id_equipo', $equipo->Id)
                ->where('Id_participante', $participante->Id)
                ->first();
            
            if ($participanteEquipo) {
                $perfil = Perfil::find($participanteEquipo->Id_perfil);
                $participante->perfil = $perfil;
            }
        }

        return view('equipos.show', compact('equipo', 'esMiembro', 'esLider', 'perfiles'));
    }

    public function edit($id)
    {
        $equipo = Equipo::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return redirect()->route('equipos.index')
                ->with('error', 'No tienes permisos para editar este equipo.');
        }

        // Verificar si es líder
        $perfilLider = Perfil::where('Nombre', 'Líder')->first();
        $esLider = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->where('Id_perfil', $perfilLider->Id)
            ->exists();

        if (!$esLider) {
            return redirect()->route('equipos.show', $equipo->Id)
                ->with('error', 'Solo el líder puede editar el equipo.');
        }

        return view('equipos.edit', compact('equipo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:equipo,Nombre,' . $id . ',Id',
        ]);

        $equipo = Equipo::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para actualizar este equipo.');
        }

        // Verificar si es líder
        $perfilLider = Perfil::where('Nombre', 'Líder')->first();
        $esLider = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->where('Id_perfil', $perfilLider->Id)
            ->exists();

        if (!$esLider) {
            return back()->with('error', 'Solo el líder puede actualizar el equipo.');
        }

        $equipo->update([
            'Nombre' => $request->nombre,
        ]);

        return redirect()->route('equipos.show', $equipo->Id)
            ->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para eliminar este equipo.');
        }

        // Verificar si es líder
        $perfilLider = Perfil::where('Nombre', 'Líder')->first();
        $esLider = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->where('Id_perfil', $perfilLider->Id)
            ->exists();

        if (!$esLider) {
            return back()->with('error', 'Solo el líder puede eliminar el equipo.');
        }

        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado exitosamente.');
    }

    /**
     * Invitar a un participante al equipo
     */
    public function invite(Request $request, $id)
    {
        // Log para depuración
        \Log::info('Invitación iniciada', [
            'equipo_id' => $id,
            'email' => $request->email,
            'perfil_id' => $request->perfil_id,
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'email' => 'required|email',
            'perfil_id' => 'required|exists:perfil,Id',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debes proporcionar un correo electrónico válido',
            'perfil_id.required' => 'Debes seleccionar un perfil',
            'perfil_id.exists' => 'El perfil seleccionado no es válido',
        ]);

        $equipo = Equipo::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para invitar miembros.');
        }

        // Verificar si es líder del equipo
        $perfilLider = Perfil::where('Nombre', 'Líder')->first();
        $esLider = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->where('Id_perfil', $perfilLider->Id)
            ->exists();

        if (!$esLider) {
            return back()->with('error', 'Solo el líder puede invitar miembros al equipo.');
        }

        // Buscar al participante a invitar por correo electrónico
        $participanteInvitado = Participante::where('Correo', $request->email)->first();

        if (!$participanteInvitado) {
            return back()->with('error', 'No se encontró un participante con ese correo electrónico.');
        }

        // Verificar si ya es miembro del equipo
        $yaMiembro = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participanteInvitado->Id)
            ->exists();

        if ($yaMiembro) {
            return back()->with('error', 'Este participante ya es miembro del equipo.');
        }

        // Verificar que el perfil seleccionado no sea "Líder"
        if ($request->perfil_id == $perfilLider->Id) {
            return back()->with('error', 'No puedes asignar el rol de Líder a otro participante. Solo puede haber un líder.');
        }

        // Verificar si ya existe una invitación pendiente
        $invitacionExistente = Invitacion::where('Equipo_id', $equipo->Id)
            ->where('Participante_id', $participanteInvitado->Id)
            ->where('Estado', 'Pendiente')
            ->exists();

        if ($invitacionExistente) {
            return back()->with('error', 'Ya existe una invitación pendiente para este participante.');
        }

        // Crear invitación
        $invitacion = Invitacion::create([
            'Equipo_id' => $equipo->Id,
            'Participante_id' => $participanteInvitado->Id,
            'Perfil_id' => $request->perfil_id,
            'InvitadoPor_id' => $participante->Id,
            'Estado' => 'Pendiente',
            'Mensaje' => $request->mensaje ?? null,
        ]);

        \Log::info('Invitación creada exitosamente', [
            'invitacion_id' => $invitacion->Id,
            'equipo' => $equipo->Nombre,
            'para' => $participanteInvitado->Correo,
            'por' => $participante->Correo
        ]);

        return back()->with('success', '¡Invitación enviada exitosamente! El participante ' . $participanteInvitado->Nombre . ' recibirá la notificación.');
    }

    /**
     * Salir de un equipo
     */
    public function leave($id)
    {
        $equipo = Equipo::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para salir del equipo.');
        }

        // Verificar si es miembro del equipo
        $miembro = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->first();

        if (!$miembro) {
            return back()->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar si es líder
        $perfilLider = Perfil::where('Nombre', 'Líder')->first();
        if ($miembro->Id_perfil == $perfilLider->Id) {
            // Contar cuántos miembros tiene el equipo
            $cantidadMiembros = DB::table('participante_equipo')
                ->where('Id_equipo', $equipo->Id)
                ->count();

            if ($cantidadMiembros > 1) {
                return back()->with('error', 'Como líder, no puedes salir del equipo si hay otros miembros. Transfiere el liderazgo o elimina el equipo.');
            }

            // Si es el único miembro, eliminar el equipo completo
            DB::table('participante_equipo')
                ->where('Id_equipo', $equipo->Id)
                ->delete();

            $equipo->delete();

            return redirect()->route('dashboard')
                ->with('success', 'Has salido del equipo y este ha sido eliminado porque eras el único miembro.');
        }

        // Remover al participante del equipo
        DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Has salido del equipo exitosamente.');
    }

    /**
     * Generar constancia de participación
     */
    public function generarConstancia($id)
    {
        $equipo = Equipo::with(['participantes', 'proyectos.evento', 'proyectos.calificaciones'])->findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return back()->with('error', 'No tienes permisos para generar constancia.');
        }

        // Verificar si es miembro del equipo
        $miembro = DB::table('participante_equipo')
            ->where('Id_equipo', $equipo->Id)
            ->where('Id_participante', $participante->Id)
            ->first();

        if (!$miembro) {
            return back()->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar que el equipo tenga proyecto en un evento
        $proyecto = $equipo->proyectos()->with(['evento', 'calificaciones'])->first();
        
        if (!$proyecto || !$proyecto->evento) {
            return back()->with('error', 'Este equipo no tiene un proyecto asociado a un evento.');
        }

        // 🔒 VALIDAR QUE EL EVENTO HAYA FINALIZADO (por fecha)
        $ahora = now();
        if ($ahora <= $proyecto->evento->Fecha_fin) {
            $fechaFin = \Carbon\Carbon::parse($proyecto->evento->Fecha_fin)->format('d/m/Y H:i');
            return back()->with('error', "⏳ La constancia solo estará disponible después de que el evento haya finalizado. El evento termina el: {$fechaFin}");
        }

        // 🔒 VALIDAR QUE TENGAN CALIFICACIONES
        $calificaciones = $proyecto->calificaciones;
        if ($calificaciones->isEmpty()) {
            return back()->with('error', '📊 Aún no puedes descargar la constancia. Debes recibir al menos una calificación de los jueces primero.');
        }

        // Obtener el rol del participante
        $perfil = Perfil::find($miembro->Id_perfil);

        // Verificar si el equipo quedó en el top 3 del ranking
        $posicionRanking = $proyecto->ranking_posicion;
        $esGanador = $posicionRanking && $posicionRanking <= 3;

        // Datos para el PDF
        $datos = [
            'participante' => $participante,
            'equipo' => $equipo,
            'proyecto' => $proyecto,
            'evento' => $proyecto->evento,
            'perfil' => $perfil,
            'fecha_emision' => now(),
            'codigo_verificacion' => strtoupper(substr(md5($equipo->Id . $participante->Id . $proyecto->evento->Id), 0, 10)),
            'posicion_ranking' => $posicionRanking,
            'es_ganador' => $esGanador
        ];

        $pdf = \PDF::loadView('equipos.pdf.constancia', $datos);
        
        return $pdf->download('Constancia_' . $proyecto->evento->Nombre . '_' . $participante->Nombre . '.pdf');
    }
}
