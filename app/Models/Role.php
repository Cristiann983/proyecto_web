<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
        'Descripcion',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'usuario_rol', 'Id_Rol', 'user_id')
                    ->withTimestamps();
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'Id_Rol', 'Id_Permiso')
                    ->withTimestamps();
    }

    // Método helper para verificar si el rol tiene un permiso
    public function hasPermiso($permisoNombre)
    {
        return $this->permisos()->where('Nombre', $permisoNombre)->exists();
    }
}
