<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{
    protected $table = 'criterio';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Evento_id',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'Evento_id', 'Id');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'Criterio_id', 'Id');
    }
}
