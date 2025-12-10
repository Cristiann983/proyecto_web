<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipo';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
    ];

    public $timestamps = false;
    //Relación con participantes usando Id_equipo e Id_participante
    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'participante_equipo', 'Id_equipo', 'Id_participante')
                    ->withPivot('Id_perfil');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'Equipo_id', 'Id');
    }

    // Relación singular para obtener el primer proyecto
    public function proyecto()
    {
        return $this->hasOne(Proyecto::class, 'Equipo_id', 'Id');
    }

    // Relación con solicitudes de unión al equipo
    public function solicitudes()
    {
        return $this->hasMany(SolicitudEquipo::class, 'Equipo_id', 'Id');
    }
}
