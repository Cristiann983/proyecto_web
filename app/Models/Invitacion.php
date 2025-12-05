<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    protected $table = 'invitaciones';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Equipo_id',
        'Participante_id',
        'Perfil_id',
        'InvitadoPor_id',
        'Estado',
        'Mensaje',
    ];

    // Relación con el equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'Equipo_id', 'Id');
    }

    // Relación con el participante invitado
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'Participante_id', 'Id');
    }

    // Relación con el perfil asignado
    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'Perfil_id', 'Id');
    }

    // Relación con quien invitó
    public function invitadoPor()
    {
        return $this->belongsTo(Participante::class, 'InvitadoPor_id', 'Id');
    }

    // Scope para invitaciones pendientes
    public function scopePendientes($query)
    {
        return $query->where('Estado', 'Pendiente');
    }

    // Scope para invitaciones aceptadas
    public function scopeAceptadas($query)
    {
        return $query->where('Estado', 'Aceptada');
    }

    // Scope para invitaciones rechazadas
    public function scopeRechazadas($query)
    {
        return $query->where('Estado', 'Rechazada');
    }
}
