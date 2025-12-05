<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juez extends Model
{
    use HasFactory;

    protected $table = 'juez';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'user_id', // ✅ Agregar
        'Id_especialidad',
        'Nombre',
        'Correo',
        'telefono',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'Id_especialidad', 'Id');
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_juez', 'Juez_id', 'Evento_id')
                    ->withTimestamps();
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'Juez_id', 'Id');
    }
}
