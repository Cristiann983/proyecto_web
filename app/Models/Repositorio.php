<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repositorio extends Model
{
    use HasFactory;

    protected $table = 'repositorio';
    protected $primaryKey = 'Id';
    
    protected $fillable = [
        'Proyecto_id',
        'Url',
    ];

    // Relación con Proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Proyecto_id', 'Id');
    }

    // Obtener el nombre del repositorio desde la URL de GitHub
    public function getNombreRepositorio()
    {
        // Extrae el nombre del repositorio de la URL
        // Ej: https://github.com/usuario/repo -> repo
        if (preg_match('/github\.com\/[^\/]+\/([^\/]+)(\.git)?$/', $this->Url, $matches)) {
            return $matches[1];
        }
        return 'Repositorio';
    }

    // Validar que sea una URL válida de GitHub
    public static function validarUrlGitHub($url)
    {
        return preg_match('/^https?:\/\/(www\.)?github\.com\/[^\s\/]+\/[^\s\/]+(\.git)?$/', $url);
    }
}
