<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relación con roles usando TU estructura actual
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'usuario_rol', 'user_id', 'Id_Rol')
                    ->withTimestamps();
    }

    // Método helper para verificar roles
    public function hasRole($roleName)
    {
        return $this->roles()->where('Nombre', $roleName)->exists();
    }

    public function hasPermiso($permisoNombre)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermiso($permisoNombre)) {
                return true;
            }
        }
        return false;
    }

    // Relación con participante
    public function participante()
    {
        return $this->hasOne(Participante::class, 'user_id');
    }

    public function juez()
    {
        return $this->hasOne(Juez::class, 'user_id');
    }
}
