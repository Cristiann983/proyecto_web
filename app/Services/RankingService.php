<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Models\Calificacion;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Calcular y actualizar el ranking de todos los proyectos de un evento
     */
    public function calcularRankingEvento($eventoId)
    {
        // Obtener todos los proyectos del evento con sus calificaciones
        $proyectos = Proyecto::where('Evento_id', $eventoId)
            ->with('calificaciones')
            ->get();

        // Calcular puntuación para cada proyecto
        foreach ($proyectos as $proyecto) {
            $puntuacion = $proyecto->calificaciones->sum('Calificacion');
            $promedio = $proyecto->calificaciones->avg('Calificacion') ?? 0;
            
            $proyecto->ranking_puntuacion = $puntuacion;
            $proyecto->save();
        }

        // Ordenar proyectos según criterios de desempate
        $proyectosOrdenados = $this->aplicarCriteriosDesempate($proyectos);

        // Asignar posiciones
        $this->asignarPosiciones($proyectosOrdenados);
    }

    /**
     * Actualizar ranking después de que un juez califica
     */
    public function actualizarRankingDespuesDeCalificacion($calificacionId)
    {
        $calificacion = Calificacion::with('proyecto')->find($calificacionId);
        
        if (!$calificacion || !$calificacion->proyecto) {
            return;
        }

        $proyecto = $calificacion->proyecto;
        
        // Actualizar timestamp de última calificación
        $proyecto->ultima_calificacion = now();
        $proyecto->save();

        // Recalcular ranking del evento completo
        $this->calcularRankingEvento($proyecto->Evento_id);
    }

    /**
     * Aplicar criterios de desempate para ordenar proyectos
     * 1. Puntuación total (DESC)
     * 2. Promedio de calificaciones (DESC)
     * 3. Timestamp de última calificación (ASC - quien terminó primero gana)
     * 4. ID del proyecto (ASC - determinístico)
     */
    private function aplicarCriteriosDesempate($proyectos)
    {
        return $proyectos->sortByDesc(function ($proyecto) {
            // Crear una clave de ordenamiento compuesta
            // Usamos números negativos para simular DESC en valores secundarios
            $puntuacion = $proyecto->ranking_puntuacion ?? 0;
            $promedio = $proyecto->calificaciones->avg('Calificacion') ?? 0;
            
            // Timestamp: convertir a timestamp Unix (los números más pequeños son primeros en el tiempo)
            $timestamp = $proyecto->ultima_calificacion ? $proyecto->ultima_calificacion->timestamp : PHP_INT_MAX;
            
            return [
                $puntuacion,                    // 1. Mayor puntuación primero
                $promedio,                      // 2. Mayor promedio primero  
                -$timestamp,                    // 3. Timestamp más antiguo primero (negativo para orden correcto)
                -$proyecto->Id                  // 4. ID menor primero (determinístico)
            ];
        })->values();
    }

    /**
     * Asignar posiciones de ranking a los proyectos ordenados
     */
    private function asignarPosiciones($proyectosOrdenados)
    {
        $posicion = 1;
        
        foreach ($proyectosOrdenados as $proyecto) {
            $proyecto->ranking_posicion = $posicion;
            $proyecto->save();
            $posicion++;
        }
    }

    /**
     * Obtener el ranking completo de un evento
     */
    public function obtenerRankingEvento($eventoId)
    {
        return Proyecto::where('Evento_id', $eventoId)
            ->whereNotNull('ranking_posicion')
            ->orderBy('ranking_posicion', 'asc')
            ->with(['equipo', 'calificaciones'])
            ->get();
    }

    /**
     * Resetear el ranking de un evento (útil para reinicios o correcciones)
     */
    public function resetearRankingEvento($eventoId)
    {
        Proyecto::where('Evento_id', $eventoId)
            ->update([
                'ranking_posicion' => null,
                'ranking_puntuacion' => null,
                'ultima_calificacion' => null
            ]);
    }
}
