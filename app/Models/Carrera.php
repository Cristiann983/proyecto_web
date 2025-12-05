<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carrera'; 

    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
    ];

    public function participantes()
    {
        return $this->hasMany(Participante::class, 'Carrera_id', 'Id');
    }
}
