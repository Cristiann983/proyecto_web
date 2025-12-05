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
        Schema::create('proyecto', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Equipo_id');
            $table->unsignedBigInteger('Evento_id');
            $table->unsignedBigInteger('Asesor_id')->nullable();
            $table->string('Nombre', 255);
            $table->string('Categoria', 255);
            $table->timestamps(); // ✅ Agregar timestamps
            
            // Foreign keys
            $table->foreign('Equipo_id')->references('Id')->on('equipo')->onDelete('cascade');
            $table->foreign('Evento_id')->references('Id')->on('evento')->onDelete('cascade');
            $table->foreign('Asesor_id')->references('Id')->on('asesor')->onDelete('set null');
            
            // Un equipo solo puede tener un proyecto por evento
            $table->unique(['Equipo_id', 'Evento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto');
    }
};
