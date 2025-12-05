<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolPermisoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs de roles
        $adminRol = DB::table('rol')->where('Nombre', 'Administrador')->first();
        $juezRol = DB::table('rol')->where('Nombre', 'Juez')->first();
        $participanteRol = DB::table('rol')->where('Nombre', 'Participante')->first();

        // Obtener IDs de permisos
        $permisos = DB::table('permiso')->get()->keyBy('Nombre');

        // Asignar permisos a Administrador (todos los permisos)
        if ($adminRol) {
            $permisosAdmin = [
                'gestionar_usuarios',
                'gestionar_roles',
                'gestionar_eventos',
                'gestionar_equipos',
                'gestionar_jueces',
                'ver_reportes',
                'calificar_proyectos',
                'ver_eventos_asignados',
                'ver_criterios',
                'crear_equipo',
                'inscribir_evento',
                'gestionar_proyecto',
                'invitar_miembros',
            ];

            foreach ($permisosAdmin as $permisoNombre) {
                if (isset($permisos[$permisoNombre])) {
                    DB::table('rol_permiso')->insert([
                        'Id_Rol' => $adminRol->Id,
                        'Id_Permiso' => $permisos[$permisoNombre]->Id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Asignar permisos a Juez
        if ($juezRol) {
            $permisosJuez = [
                'calificar_proyectos',
                'ver_eventos_asignados',
                'ver_criterios',
            ];

            foreach ($permisosJuez as $permisoNombre) {
                if (isset($permisos[$permisoNombre])) {
                    DB::table('rol_permiso')->insert([
                        'Id_Rol' => $juezRol->Id,
                        'Id_Permiso' => $permisos[$permisoNombre]->Id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Asignar permisos a Participante
        if ($participanteRol) {
            $permisosParticipante = [
                'crear_equipo',
                'inscribir_evento',
                'gestionar_proyecto',
                'invitar_miembros',
            ];

            foreach ($permisosParticipante as $permisoNombre) {
                if (isset($permisos[$permisoNombre])) {
                    DB::table('rol_permiso')->insert([
                        'Id_Rol' => $participanteRol->Id,
                        'Id_Permiso' => $permisos[$permisoNombre]->Id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}