<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Calificacion;
use App\Models\Juez;
use App\Models\Proyecto;
use App\Models\Criterio;
use App\Services\RankingService;

class CalificacionController extends Controller
{
    /**
     * Guardar calificaciones de un proyecto
     */
    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|exists:proyecto,Id',
            'calificaciones' => 'required|array',
            'calificaciones.*.criterio_id' => 'required|exists:criterio,Id',
            'calificaciones.*.puntuacion' => 'required|numeric|min:0|max:10',
        ], [
            'proyecto_id.required' => 'El proyecto es obligatorio',
            'proyecto_id.exists' => 'El proyecto no existe',
            'calificaciones.required' => 'Debes proporcionar calificaciones',
            'calificaciones.*.criterio_id.required' => 'El criterio es obligatorio',
            'calificaciones.*.criterio_id.exists' => 'El criterio no existe',
            'calificaciones.*.puntuacion.required' => 'La puntuación es obligatoria',
            'calificaciones.*.puntuacion.numeric' => 'La puntuación debe ser un número',
            'calificaciones.*.puntuacion.min' => 'La puntuación mínima es 0',
            'calificaciones.*.puntuacion.max' => 'La puntuación máxima es 10',
        ]);

        $user = Auth::user();
        $juez = Juez::where('user_id', $user->id)->first();

        if (!$juez) {
            return back()->with('error', 'Solo los jueces pueden calificar proyectos.');
        }

        $proyecto = Proyecto::findOrFail($request->proyecto_id);

        // Verificar que el juez esté asignado al evento del proyecto
        $eventoId = $proyecto->Evento_id;

        if (!$eventoId) {
            return back()->with('error', 'El proyecto no está asignado a ningún evento.');
        }

        $juezAsignado = $juez->eventos()->where('evento.Id', $eventoId)->exists();

        if (!$juezAsignado) {
            return back()->with('error', 'No estás asignado como juez para este evento.');
        }

        // Guardar calificaciones y contar actualizaciones
        $guardadas = 0;
        $actualizadas = 0;

        foreach ($request->calificaciones as $calificacionData) {
            // Actualizar si ya existe o crear nueva
            $calificacion = Calificacion::updateOrCreate(
                [
                    'Juez_id' => $juez->Id,
                    'Proyecto_id' => $proyecto->Id,
                    'Criterio_id' => $calificacionData['criterio_id'],
                ],
                [
                    'Calificacion' => $calificacionData['puntuacion'],
                ]
            );

            // Verificar si fue recién creada o actualizada
            if ($calificacion->wasRecentlyCreated) {
                $guardadas++;
            } else {
                $actualizadas++;
            }
        }

        // Verificar que todas las calificaciones se guardaron correctamente
        $totalCalificaciones = Calificacion::where('Juez_id', $juez->Id)
            ->where('Proyecto_id', $proyecto->Id)
            ->count();

        $totalEsperado = count($request->calificaciones);
        $todasGuardadas = $totalCalificaciones >= $totalEsperado;

        // ✅ ACTUALIZAR RANKING DEL EVENTO AUTOMÁTICAMENTE
        try {
            $rankingService = app(RankingService::class);
            $rankingService->calcularRankingEvento($proyecto->Evento_id);
        } catch (\Exception $e) {
            // Log del error pero no interrumpir el flujo
            \Log::error('Error al calcular ranking: ' . $e->getMessage());
        }

        // Retornar JSON si es petición AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $actualizadas > 0
                    ? "✓ Calificaciones actualizadas exitosamente ({$actualizadas} criterios)"
                    : "✓ Calificaciones guardadas exitosamente ({$guardadas} criterios)",
                'guardadas' => $guardadas,
                'actualizadas' => $actualizadas,
                'total_verificado' => $totalCalificaciones,
                'todas_guardadas' => $todasGuardadas
            ]);
        }

        $mensaje = $actualizadas > 0
            ? "Calificaciones actualizadas exitosamente ({$actualizadas} criterios)"
            : "Calificaciones guardadas exitosamente ({$guardadas} criterios)";

        return back()->with('success', $mensaje);
    }

    /**
     * Obtener calificaciones de un proyecto por el juez actual
     */
    public function getCalificaciones($proyectoId)
    {
        $user = Auth::user();
        $juez = Juez::where('user_id', $user->id)->first();

        if (!$juez) {
            return response()->json(['error' => 'No eres un juez'], 403);
        }

        $calificaciones = Calificacion::where('Juez_id', $juez->Id)
            ->where('Proyecto_id', $proyectoId)
            ->with('criterio')
            ->get();

        return response()->json($calificaciones);
    }

    }
