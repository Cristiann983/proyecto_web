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
        Schema::create('solicitud_equipos', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Equipo_id');
            $table->unsignedBigInteger('Usuario_id');
            $table->enum('Estado', ['Pendiente', 'Aceptada', 'Rechazada'])->default('Pendiente');
            $table->text('Mensaje')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreign('Equipo_id')->references('Id')->on('equipo')->onDelete('cascade');
            $table->foreign('Usuario_id')->references('id')->on('users')->onDelete('cascade');

            // Índices
            $table->unique(['Equipo_id', 'Usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_equipos');
    }
};
