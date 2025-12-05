<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion'
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'Id_perfil', 'Id');
    }

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'participante_equipo', 'Id_perfil', 'Participante_id')
                    ->withPivot('Equipo_id')
                    ->withTimestamps();
    }
}
