<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perfil')->insert([
            [
                'Nombre' => 'Líder',
                'Descripcion' => 'Líder del equipo',
            ],
            [
                'Nombre' => 'Diseñador',
                'Descripcion' => 'Diseñador UI/UX',
            ],
            [
                'Nombre' => 'Programador Backend',
                'Descripcion' => 'Desarrollador Backend',
            ],
            [
                'Nombre' => 'Programador Frontend',
                'Descripcion' => 'Desarrollador Frontend',
            ],
        ]);
    }
}
