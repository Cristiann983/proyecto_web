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

    // ✅ Un proyecto tiene muchas calificaciones
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'Proyecto_id', 'Id');
    }

    // ✅ Un proyecto tiene muchos avances
    public function avances()
    {
        return $this->hasMany(Avance::class, 'Proyecto_id', 'Id');
    }

    // ✅ Un proyecto tiene un repositorio
    public function repositorio()
    {
        return $this->hasOne(Repositorio::class, 'Proyecto_id', 'Id');
    }

    // Método helper para obtener calificación promedio
    public function calificacionPromedio()
    {
        return $this->calificaciones()->avg('Calificacion');
    }
}
