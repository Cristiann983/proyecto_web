<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudEquipo extends Model
{
    protected $table = 'solicitud_equipos';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Equipo_id',
        'Usuario_id',
        'Estado',
        'Mensaje',
    ];

    // Relación con el equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'Equipo_id', 'Id');
    }

    // Relación con el usuario que solicita
    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario_id', 'id');
    }

    // Scope para solicitudes pendientes
    public function scopePendientes($query)
    {
        return $query->where('Estado', 'Pendiente');
    }

    // Scope para solicitudes aceptadas
    public function scopeAceptadas($query)
    {
        return $query->where('Estado', 'Aceptada');
    }

    // Scope para solicitudes rechazadas
    public function scopeRechazadas($query)
    {
        return $query->where('Estado', 'Rechazada');
    }
}
