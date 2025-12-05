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
        Schema::create('participante_equipo', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Id_participante'); 
            $table->unsignedBigInteger('Id_equipo');       
            $table->unsignedBigInteger('Id_perfil')->nullable(); // ✅ Id_perfil (no Perfil_id)
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('Id_participante')->references('Id')->on('participante')->onDelete('cascade');
            $table->foreign('Id_equipo')->references('Id')->on('equipo')->onDelete('cascade');
            $table->foreign('Id_perfil')->references('Id')->on('perfil')->onDelete('set null');
            
            // Evitar duplicados
            $table->unique(['Id_participante', 'Id_equipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participante_equipo');
    }
};
