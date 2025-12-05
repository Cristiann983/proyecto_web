<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evento', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Nombre');
            $table->text('Descripcion')->nullable();
            $table->dateTime('Fecha_inicio');
            $table->dateTime('Fecha_fin');
            $table->string('Ubicacion')->nullable();
            $table->enum('Estado', ['Activo', 'Finalizado', 'Cancelado'])->default('Activo');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento');
    }
};
