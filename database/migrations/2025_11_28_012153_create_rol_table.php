<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SOLO crear la tabla rol, sin otras tablas
        Schema::create('rol', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Nombre')->unique();
            $table->string('Descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};