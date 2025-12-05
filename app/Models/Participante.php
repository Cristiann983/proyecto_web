<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;

    protected $table = 'participante';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'user_id',
        'No_Control',
        'Carrera_id',
        'Nombre',
        'Correo',
        'telefono',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'Carrera_id', 'Id');
    }

    // ✅ Actualizar withPivot para usar Id_perfil
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'participante_equipo', 'Id_participante', 'Id_equipo')
                    ->withPivot('Id_perfil') // ✅ Cambiado de Perfil_id a Id_perfil
                    ->withTimestamps();
    }
}
