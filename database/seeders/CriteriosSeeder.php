<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criterios = [
            ['descripcion' => 'Innovación y Creatividad'],
            ['descripcion' => 'Funcionalidad'],
            ['descripcion' => 'Calidad del Código'],
            ['descripcion' => 'Presentación'],
            ['descripcion' => 'Impacto y Utilidad'],
        ];

        foreach ($criterios as $criterio) {
            \App\Models\Criterio::firstOrCreate($criterio);
        }
    }
}
