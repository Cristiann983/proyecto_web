<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            [
                'Nombre' => 'Desarrollo Web',
                'Descripcion' => 'Especialista en desarrollo de aplicaciones web y tecnologías front-end/back-end',
            ],
            [
                'Nombre' => 'Desarrollo Móvil',
                'Descripcion' => 'Experto en desarrollo de aplicaciones móviles para iOS y Android',
            ],
            [
                'Nombre' => 'Inteligencia Artificial',
                'Descripcion' => 'Especialista en machine learning, deep learning y algoritmos de IA',
            ],
            [
                'Nombre' => 'Ciencia de Datos',
                'Descripcion' => 'Experto en análisis de datos, estadística y visualización',
            ],
            [
                'Nombre' => 'Ciberseguridad',
                'Descripcion' => 'Especialista en seguridad informática, ethical hacking y protección de datos',
            ],
            [
                'Nombre' => 'Cloud Computing',
                'Descripcion' => 'Experto en servicios en la nube (AWS, Azure, GCP) y arquitectura cloud',
            ],
            [
                'Nombre' => 'DevOps',
                'Descripcion' => 'Especialista en integración continua, despliegue y automatización',
            ],
            [
                'Nombre' => 'Blockchain',
                'Descripcion' => 'Experto en tecnología blockchain, contratos inteligentes y criptomonedas',
            ],
            [
                'Nombre' => 'Internet de las Cosas (IoT)',
                'Descripcion' => 'Especialista en dispositivos conectados y sistemas embebidos',
            ],
            [
                'Nombre' => 'UX/UI Design',
                'Descripcion' => 'Experto en diseño de experiencia de usuario e interfaces',
            ],
            [
                'Nombre' => 'Arquitectura de Software',
                'Descripcion' => 'Especialista en diseño de sistemas escalables y patrones arquitectónicos',
            ],
            [
                'Nombre' => 'Base de Datos',
                'Descripcion' => 'Experto en diseño, optimización y gestión de bases de datos',
            ],
        ];

        foreach ($especialidades as $especialidad) {
            DB::table('especialidad')->insert([
                'Nombre' => $especialidad['Nombre'],
                'Descripcion' => $especialidad['Descripcion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
