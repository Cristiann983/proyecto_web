<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyecto';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Equipo_id',
        'Evento_id',
        'Asesor_id',
        'Nombre',
        'Categoria',
        'ranking_posicion',
        'ranking_puntuacion',
        'ultima_calificacion',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'Equipo_id', 'Id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'Evento_id', 'Id');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'Asesor_id', 'Id');
    }

    //Un proyecto tiene muchas calificaciones
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'Proyecto_id', 'Id');
    }

    //Un proyecto tiene muchos avances
    public function avances()
    {
        return $this->hasMany(Avance::class, 'Proyecto_id', 'Id');
    }

    //Un proyecto tiene un repositorio
    public function repositorio()
    {
        return $this->hasOne(Repositorio::class, 'Proyecto_id', 'Id');
    }

    // Método helper para obtener calificación promedio
    public function calificacionPromedio()
    {
        return $this->calificaciones()->avg('Calificacion');
    }

    /**
     * Scope para filtrar proyectos por evento
     */
    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('Evento_id', $eventoId);
    }

    /**
     * Scope para ordenar por ranking
     */
    public function scopeOrdenadoPorRanking($query)
    {
        return $query->orderBy('ranking_posicion', 'asc');
    }

    /**
     * Verificar si el proyecto tiene ranking asignado
     */
    public function tieneRanking()
    {
        return !is_null($this->ranking_posicion);
    }

    /**
     * Obtener medalla según posición (oro, plata, bronce)
     */
    public function obtenerMedalla()
    {
        if (!$this->tieneRanking()) {
            return null;
        }

        return match($this->ranking_posicion) {
            1 => '🥇',
            2 => '🥈',
            3 => '🥉',
            default => null
        };
    }
}
