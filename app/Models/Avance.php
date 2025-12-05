<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    use HasFactory;

    protected $table = 'avance';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Proyecto_id',
        'Descripcion',
        'Porcentaje',
        'Fecha',
    ];

    protected $casts = [
        'Fecha' => 'date',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Proyecto_id', 'Id');
    }
}
