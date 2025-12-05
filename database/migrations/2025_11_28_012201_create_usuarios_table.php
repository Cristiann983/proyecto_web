<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('usuario', function (Blueprint $table) {
        //     $table->id('Id');
        //     $table->string('Nombre', 255);
        //     $table->string('Correo', 255)->unique();
        //     $table->string('Contraseña', 255);
        //     $table->boolean('Is_active')->default(true);
        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('usuario');
    }
};