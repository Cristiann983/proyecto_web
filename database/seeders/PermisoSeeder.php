<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permisos = [
            // Permisos de Administrador
            ['Nombre' => 'gestionar_usuarios', 'Descripcion' => 'Gestionar usuarios del sistema'],
            ['Nombre' => 'gestionar_roles', 'Descripcion' => 'Gestionar roles y permisos'],
            ['Nombre' => 'gestionar_eventos', 'Descripcion' => 'Crear, editar y eliminar eventos'],
            ['Nombre' => 'gestionar_equipos', 'Descripcion' => 'Gestionar equipos'],
            ['Nombre' => 'gestionar_jueces', 'Descripcion' => 'Asignar jueces a eventos'],
          
            
            // Permisos de Juez
            ['Nombre' => 'calificar_proyectos', 'Descripcion' => 'Calificar proyectos en eventos'],
            ['Nombre' => 'ver_eventos_asignados', 'Descripcion' => 'Ver eventos asignados'],
            ['Nombre' => 'ver_criterios', 'Descripcion' => 'Ver criterios de evaluación'],
            
            // Permisos de Participante
            ['Nombre' => 'crear_equipo', 'Descripcion' => 'Crear equipos'],
            ['Nombre' => 'inscribir_evento', 'Descripcion' => 'Inscribirse a eventos'],
            ['Nombre' => 'gestionar_proyecto', 'Descripcion' => 'Gestionar proyecto del equipo'],
            ['Nombre' => 'invitar_miembros', 'Descripcion' => 'Invitar miembros al equipo'],
        ];

        foreach ($permisos as $permiso) {
            DB::table('permiso')->insert([
                'Nombre' => $permiso['Nombre'],
                'Descripcion' => $permiso['Descripcion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}