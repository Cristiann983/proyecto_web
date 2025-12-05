<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;

class AsesorSeeder extends Seeder
{
    public function run(): void
    {
        Asesor::create([
            'Nombre' => 'Dr. Juan Pérez',
            'Correo' => 'juan.perez@example.com',
            'Telefono' => '1234567890',
            'Especialidad' => 'Desarrollo de Software',
        ]);

        Asesor::create([
            'Nombre' => 'Dra. María López',
            'Correo' => 'maria.lopez@example.com',
            'Telefono' => '0987654321',
            'Especialidad' => 'Inteligencia Artificial',
        ]);

        Asesor::create([
            'Nombre' => 'Ing. Carlos Ramírez',
            'Correo' => 'carlos.ramirez@example.com',
            'Telefono' => '5551234567',
            'Especialidad' => 'Robótica',
        ]);
    }
}