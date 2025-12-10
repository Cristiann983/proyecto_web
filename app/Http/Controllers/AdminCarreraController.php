<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class AdminCarreraController extends Controller
{
    /**
     * Mostrar lista de carreras
     */
    public function index()
    {
        $carreras = Carrera::withCount('participantes')->orderBy('Nombre')->get();
        return view('admin.carreras.index', compact('carreras'));
    }

    /**
     * Crear nueva carrera
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:carrera,Nombre',
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio',
            'nombre.unique' => 'Ya existe una carrera con ese nombre',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
        ]);

        try {
            Carrera::create([
                'Nombre' => $validated['nombre'],
            ]);

            return back()->with('success', 'Carrera creada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la carrera: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar carrera
     */
    public function update(Request $request, $id)
    {
        $carrera = Carrera::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:carrera,Nombre,' . $id . ',Id',
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio',
            'nombre.unique' => 'Ya existe una carrera con ese nombre',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
        ]);

        try {
            $carrera->update([
                'Nombre' => $validated['nombre'],
            ]);

            return back()->with('success', 'Carrera actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la carrera: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar carrera
     */
    public function destroy($id)
    {
        $carrera = Carrera::findOrFail($id);

        // Verificar si tiene participantes asociados
        if ($carrera->participantes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la carrera porque tiene participantes asociados.');
        }

        try {
            $carrera->delete();
            return back()->with('success', 'Carrera eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la carrera: ' . $e->getMessage());
        }
    }
}
