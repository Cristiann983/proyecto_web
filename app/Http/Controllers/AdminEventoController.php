<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Juez;
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

        $eventos = Evento::with('jueces')->orderBy('Fecha_inicio', 'desc')->get();
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
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'ubicacion' => 'nullable|string|max:255',
            'estado' => 'nullable|in:Activo,Finalizado,Cancelado',
            'jueces' => 'nullable|array',
            'jueces.*' => 'exists:juez,Id'
        ]);

        DB::beginTransaction();

        try {
            // Crear evento sin Id_juez
            $evento = Evento::create([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Fecha_inicio' => $request->fecha_inicio,
                'Fecha_fin' => $request->fecha_fin,
                'Ubicacion' => $request->ubicacion,
                'Estado' => $request->estado ?? 'Activo',
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

        $evento = Evento::findOrFail($id);
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
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'ubicacion' => 'nullable|string|max:255',
            'estado' => 'nullable|in:Activo,Finalizado,Cancelado',
            'jueces' => 'nullable|array',
            'jueces.*' => 'exists:juez,Id'
        ]);

        DB::beginTransaction();

        try {
            $evento = Evento::findOrFail($id);
            
            $evento->update([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Fecha_inicio' => $request->fecha_inicio,
                'Fecha_fin' => $request->fecha_fin,
                'Ubicacion' => $request->ubicacion,
                'Estado' => $request->estado ?? 'Activo',
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

            DB::commit();

            return redirect()->route('admin.eventos.index')
                ->with('success', 'Evento actualizado exitosamente');

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
