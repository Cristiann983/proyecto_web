<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permiso';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Nombre',
        'Descripcion',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'rol_permiso', 'Id_Permiso', 'Id_Rol')
                    ->withTimestamps();
    }
}
