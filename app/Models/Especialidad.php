<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidad';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
        'Descripcion',
    ];

    public function jueces()
    {
        return $this->hasMany(Juez::class, 'Id_especialidad', 'Id');
    }
}
