<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificacion';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Proyecto_id',
        'Juez_id',
        'Criterio_id',
        'Calificacion',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Proyecto_id', 'Id');
    }

    public function juez()
    {
        return $this->belongsTo(Juez::class, 'Juez_id', 'Id');
    }

    public function criterio()
    {
        return $this->belongsTo(Criterio::class, 'Criterio_id', 'Id');
    }
}
