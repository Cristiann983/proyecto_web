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
        Schema::create('participante', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('user_id');
            $table->string('No_Control')->unique();
            $table->unsignedBigInteger('Carrera_id');
            $table->string('Nombre');
            $table->string('Correo')->unique();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('Carrera_id')->references('Id')->on('carrera')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participante');
    }
};
