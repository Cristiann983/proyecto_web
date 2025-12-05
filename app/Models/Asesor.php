<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
        'Correo',
        'Telefono',
        'Especialidad', 
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'Asesor_id', 'Id');
    }
}
