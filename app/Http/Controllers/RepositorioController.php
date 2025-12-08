<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Repositorio;
use App\Models\Proyecto;
use App\Models\Participante;
use App\Models\Equipo;

class RepositorioController extends Controller
{
    /**
     * Mostrar lista de repositorios del participante
     */
    public function index()
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return view('codigos.index', ['repositorios' => collect(), 'proyectos' => collect()]);
        }

        // Obtener todos los proyectos del participante a través de sus equipos
        $proyectos = Proyecto::whereIn('Equipo_id', function($query) use ($participante) {
            $query->select('Id_equipo')
                  ->from('participante_equipo')
                  ->where('Id_participante', $participante->Id);
        })->get();

        // Obtener repositorios de esos proyectos
        $repositorios = Repositorio::whereIn('Proyecto_id', $proyectos->pluck('Id'))
            ->with('proyecto')
            ->get();

        return view('codigos.index', compact('repositorios', 'proyectos'));
    }

    /**
     * Crear nuevo repositorio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|exists:proyecto,Id',
            'url' => 'required|url|regex:/^https?:\/\/(www\.)?github\.com\//',
        ], [
            'proyecto_id.required' => 'Debes seleccionar un proyecto',
            'proyecto_id.exists' => 'El proyecto seleccionado no existe',
            'url.required' => 'La URL es obligatoria',
            'url.url' => 'Debes ingresar una URL válida',
            'url.regex' => 'La URL debe ser de GitHub',
        ]);

        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar que el participante tenga acceso al proyecto
        $proyecto = Proyecto::findOrFail($validated['proyecto_id']);
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return back()->with('error', 'No tienes acceso para agregar repositorio a este proyecto.');
        }

        // Verificar si ya existe repositorio para este proyecto
        $yaExiste = Repositorio::where('Proyecto_id', $validated['proyecto_id'])->exists();
        
        if ($yaExiste) {
            return back()->with('error', 'Este proyecto ya tiene un repositorio vinculado.');
        }

        try {
            Repositorio::create([
                'Proyecto_id' => $validated['proyecto_id'],
                'Url' => $validated['url'],
            ]);

            return back()->with('success', 'Repositorio agregado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al agregar repositorio: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar repositorio
     */
    public function destroy($id)
    {
        $repositorio = Repositorio::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar acceso
        $proyecto = $repositorio->proyecto;
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return back()->with('error', 'No tienes permisos para eliminar este repositorio.');
        }

        $repositorio->delete();

        return back()->with('success', 'Repositorio eliminado correctamente.');
    }
}
