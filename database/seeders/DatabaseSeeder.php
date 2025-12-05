<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CarreraSeeder::class,
            PerfilSeeder::class,
            PermisoSeeder::class,
            RolPermisoSeeder::class,
            AsesorSeeder::class,     
            UsuarioSeeder::class,
        ]);
    }
}