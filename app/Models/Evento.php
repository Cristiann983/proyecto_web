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

    // ✅ Agregar método estadoLabel
    public function getEstadoLabelAttribute()
    {
        $estados = [
            'Activo' => [
                'clase' => 'bg-green-100 text-green-800',
                'texto' => 'Activo'
            ],
            'Finalizado' => [
                'clase' => 'bg-gray-100 text-gray-800',
                'texto' => 'Finalizado'
            ],
            'Cancelado' => [
                'clase' => 'bg-red-100 text-red-800',
                'texto' => 'Cancelado'
            ],
        ];

        return $estados[$this->Estado] ?? [
            'clase' => 'bg-gray-100 text-gray-800',
            'texto' => $this->Estado
        ];
    }
}
