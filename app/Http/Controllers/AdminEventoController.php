<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Juez;
use App\Models\Criterio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminEventoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $eventos = Evento::with(['jueces', 'proyectos'])->orderBy('Fecha_inicio', 'desc')->get();
        
        // Calcular cantidad de equipos para cada evento
        foreach ($eventos as $evento) {
            $evento->cantidadEquipos = $evento->proyectos->count();
        }
        
        $jueces = Juez::all();
        
        return view('admin.eventos.index', compact('eventos', 'jueces'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $jueces = Juez::all();
        
        return view('admin.eventos.create', compact('jueces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'dificultad' => 'nullable|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i',
            'ubicacion' => 'nullable|string|max:255',
            'estado' => 'nullable|in:Activo,Finalizado,Cancelado',
            'tecnologias' => 'nullable|array',
            'tecnologias.*' => 'string',
            'jueces' => 'nullable|array',
            'jueces.*' => 'exists:juez,Id',
            'criterios' => 'nullable|array',
            'criterios.*.nombre' => 'required_with:criterios|string|max:255',
            'criterios.*.descripcion' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Crear evento
            $evento = Evento::create([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Categoria' => $request->dificultad,
                'Fecha_inicio' => $request->fecha_inicio . ($request->hora_inicio ? ' ' . $request->hora_inicio : ' 00:00:00'),
                'Fecha_fin' => $request->fecha_fin . ($request->hora_fin ? ' ' . $request->hora_fin : ' 23:59:59'),
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'Ubicacion' => $request->ubicacion,
                'Estado' => $request->estado ?? 'Activo',
                'tecnologias' => $request->tecnologias ?? [],
            ]);

            // Asignar jueces mediante tabla pivote
            if ($request->has('jueces') && is_array($request->jueces)) {
                foreach ($request->jueces as $juezId) {
                    DB::table('evento_juez')->insert([
                        'Evento_id' => $evento->Id,
                        'Juez_id' => $juezId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Crear criterios para el evento (evitar duplicados)
            if ($request->has('criterios') && is_array($request->criterios)) {
                foreach ($request->criterios as $criterioData) {
                    if (!empty($criterioData['nombre'])) {
                        // Usar firstOrCreate para evitar duplicados
                        Criterio::firstOrCreate(
                            [
                                'Nombre' => $criterioData['nombre'],
                                'Evento_id' => $evento->Id,
                            ],
                            [
                                'Descripcion' => $criterioData['descripcion'] ?? '',
                            ]
                        );
                    }
                }
            }

            DB::commit();

            Log::info('Evento creado', ['evento_id' => $evento->Id]);

            return redirect()->route('admin.eventos.index')
                ->with('success', 'Evento creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear evento: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear el evento: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $evento = Evento::with(['jueces', 'proyectos.equipo', 'proyectos.asesor'])->findOrFail($id);
        
        return view('admin.eventos.show', compact('evento'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $evento = Evento::with('criterios')->findOrFail($id);
        $jueces = Juez::all();
        
        $juecesAsignados = DB::table('evento_juez')
            ->where('Evento_id', $evento->Id)
            ->pluck('Juez_id')
            ->toArray();
        
        return view('admin.eventos.edit', compact('evento', 'jueces', 'juecesAsignados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'dificultad' => 'nullable|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i',
            'ubicacion' => 'nullable|string|max:255',
            'estado' => 'nullable|in:Activo,Finalizado,Cancelado',
            'tecnologias' => 'nullable|array',
            'tecnologias.*' => 'string',
            'jueces' => 'nullable|array',
            'jueces.*' => 'exists:juez,Id',
            'criterios' => 'nullable|array',
            'criterios.*.id' => 'nullable|integer',
            'criterios.*.nombre' => 'required_with:criterios|string|max:255',
            'criterios.*.descripcion' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $evento = Evento::findOrFail($id);
            
            $evento->update([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Categoria' => $request->dificultad,
                'Fecha_inicio' => $request->fecha_inicio . ($request->hora_inicio ? ' ' . $request->hora_inicio : ' 00:00:00'),
                'Fecha_fin' => $request->fecha_fin . ($request->hora_fin ? ' ' . $request->hora_fin : ' 23:59:59'),
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'Ubicacion' => $request->ubicacion,
                'Estado' => $request->estado ?? 'Activo',
                'tecnologias' => $request->tecnologias ?? [],
            ]);

            DB::table('evento_juez')->where('Evento_id', $evento->Id)->delete();

            if ($request->has('jueces') && is_array($request->jueces)) {
                foreach ($request->jueces as $juezId) {
                    DB::table('evento_juez')->insert([
                        'Evento_id' => $evento->Id,
                        'Juez_id' => $juezId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Manejar criterios de evaluación
            $criteriosEnviados = [];
            if ($request->has('criterios') && is_array($request->criterios)) {
                foreach ($request->criterios as $criterioData) {
                    if (!empty($criterioData['nombre'])) {
                        if (!empty($criterioData['id'])) {
                            // Actualizar criterio existente
                            $criterio = Criterio::find($criterioData['id']);
                            if ($criterio && $criterio->Evento_id == $evento->Id) {
                                $criterio->update([
                                    'Nombre' => $criterioData['nombre'],
                                    'Descripcion' => $criterioData['descripcion'] ?? '',
                                ]);
                                $criteriosEnviados[] = $criterio->Id;
                            }
                        } else {
                            // Crear nuevo criterio
                            $criterio = Criterio::create([
                                'Nombre' => $criterioData['nombre'],
                                'Descripcion' => $criterioData['descripcion'] ?? '',
                                'Evento_id' => $evento->Id,
                            ]);
                            $criteriosEnviados[] = $criterio->Id;
                        }
                    }
                }
            }

            // Eliminar criterios que no fueron enviados (solo si no tienen calificaciones)
            $criteriosAEliminar = Criterio::where('Evento_id', $evento->Id)
                ->whereNotIn('Id', $criteriosEnviados)
                ->get();
            
            foreach ($criteriosAEliminar as $criterio) {
                // Verificar si el criterio tiene calificaciones
                $tieneCalificaciones = $criterio->calificaciones()->exists();
                if (!$tieneCalificaciones) {
                    $criterio->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.eventos.index')
                ->with('success', 'Evento "' . $evento->Nombre . '" actualizado exitosamente con todos los cambios guardados');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el evento: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        try {
            $evento = Evento::findOrFail($id);
            $evento->delete();

            return redirect()->route('admin.eventos.index')
                ->with('success', 'Evento eliminado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar evento: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el evento');
        }
    }

    public function equiposInscritos($id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $evento = Evento::with(['jueces', 'proyectos.equipo.participantes', 'proyectos.asesor'])
            ->findOrFail($id);
        
        return view('admin.eventos.equipos', compact('evento'));
    }
}
