<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'evento';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Categoria',
        'Fecha_inicio',
        'Fecha_fin',
        'Ubicacion',
        'Estado',
    ];

    protected $casts = [
        'Fecha_inicio' => 'datetime',
        'Fecha_fin' => 'datetime',
    ];

    // Relación muchos a muchos con jueces
    public function jueces()
    {
        return $this->belongsToMany(Juez::class, 'evento_juez', 'Evento_id', 'Juez_id')
                    ->withTimestamps();
    }

    // Relación con proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'Evento_id', 'Id');
    }

    // Obtener equipos inscritos a través de proyectos
    public function equipos()
    {
        return $this->hasManyThrough(
            Equipo::class,
            Proyecto::class,
            'Evento_id',
            'Id',
            'Id',
            'Equipo_id'
        );
    }

    public function criterios()
    {
        return $this->hasMany(Criterio::class, 'Evento_id', 'Id');
    }

    // ✅ Determinar estado dinámicamente basado en fechas
    public function getEstadoLabelAttribute()
    {
        $ahora = now();
        $fechaInicio = $this->Fecha_inicio;
        $fechaFin = $this->Fecha_fin;
        
        // Si el evento ya finalizó
        if ($ahora > $fechaFin) {
            $estado = 'Finalizado';
            $clase = 'bg-gray-100 text-gray-800';
            $texto = 'Finalizado';
        }
        // Si el evento está en curso
        elseif ($ahora >= $fechaInicio && $ahora <= $fechaFin) {
            $estado = 'Activo';
            $clase = 'bg-green-100 text-green-800';
            $texto = 'En curso';
        }
        // Si el evento aún no comienza
        else {
            $estado = 'Próximo';
            $clase = 'bg-blue-100 text-blue-800';
            $texto = 'Próximo';
        }
        
        // Si está cancelado, sobrescribir
        if ($this->Estado === 'Cancelado') {
            $clase = 'bg-red-100 text-red-800';
            $texto = 'Cancelado';
        }

        return [
            'clase' => $clase,
            'texto' => $texto,
            'estado' => $estado
        ];
    }
}
