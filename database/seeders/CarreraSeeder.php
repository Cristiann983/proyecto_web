<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carrera;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = [
            ['Nombre' => 'Ingeniería en Sistemas Computacionales'],
            ['Nombre' => 'Ingeniería Industrial'],
            ['Nombre' => 'Ingeniería Mecatrónica'],
            ['Nombre' => 'Ingeniería Electrónica'],
            ['Nombre' => 'Ingeniería en Gestión Empresarial'],
            ['Nombre' => 'Ingeniería Civil'],
        ];

        foreach ($carreras as $carrera) {
            Carrera::create($carrera);
        }
    }
}
