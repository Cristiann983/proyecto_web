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
        Schema::create('invitaciones', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Equipo_id');
            $table->unsignedBigInteger('Participante_id');
            $table->unsignedBigInteger('Perfil_id');
            $table->unsignedBigInteger('InvitadoPor_id'); // ID del participante que invitó
            $table->enum('Estado', ['Pendiente', 'Aceptada', 'Rechazada'])->default('Pendiente');
            $table->text('Mensaje')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('Equipo_id')->references('Id')->on('equipo')->onDelete('cascade');
            $table->foreign('Participante_id')->references('Id')->on('participante')->onDelete('cascade');
            $table->foreign('Perfil_id')->references('Id')->on('perfil')->onDelete('cascade');
            $table->foreign('InvitadoPor_id')->references('Id')->on('participante')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitaciones');
    }
};
