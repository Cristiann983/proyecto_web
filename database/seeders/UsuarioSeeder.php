<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear usuario administrador
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@devteams.com',
            'password' => 'admin123',
            'is_active' => true,
        ]);

        $roleAdmin = Role::where('Nombre', 'Administrador')->first();
        if ($roleAdmin) {
            DB::table('usuario_rol')->insert([
                'user_id' => $admin->id,
                'Id_Rol' => $roleAdmin->Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Crear usuario juez
        $juez = User::create([
            'name' => 'juez1',
            'email' => 'juez@devteams.com',
            'password' => 'juez123',
            'is_active' => true,
        ]);

        $roleJuez = Role::where('Nombre', 'Juez')->first();
        if ($roleJuez) {
            DB::table('usuario_rol')->insert([
                'user_id' => $juez->id,
                'Id_Rol' => $roleJuez->Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('juez')->insert([
                'user_id' => $juez->id,
                'Nombre' => 'Juez Ejemplo',
                'Correo' => 'juez@devteams.com',
                'telefono' => '1234567890',
                'Id_especialidad' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Crear usuario participante
        $participante = User::create([
            'name' => 'participante1',
            'email' => 'participante@devteams.com',
            'password' => 'part123',
            'is_active' => true,
        ]);

        $roleParticipante = Role::where('Nombre', 'Participante')->first();
        if ($roleParticipante) {
            DB::table('usuario_rol')->insert([
                'user_id' => $participante->id,
                'Id_Rol' => $roleParticipante->Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('participante')->insert([
                'user_id' => $participante->id,
                'No_Control' => '20210001',
                'Carrera_id' => 1,
                'Nombre' => 'Participante Ejemplo',
                'Correo' => 'participante@devteams.com',
                'telefono' => '0987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
