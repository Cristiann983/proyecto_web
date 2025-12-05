<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rol')->insert([
            [
                'Nombre' => 'Administrador',
                'Descripcion' => 'Administrador del sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Juez',
                'Descripcion' => 'Juez de eventos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Participante',
                'Descripcion' => 'Participante de eventos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
