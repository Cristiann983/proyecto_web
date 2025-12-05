<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_juez', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Evento_id');
            $table->unsignedBigInteger('Juez_id');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('Evento_id')->references('Id')->on('evento')->onDelete('cascade');
            $table->foreign('Juez_id')->references('Id')->on('juez')->onDelete('cascade');
            
            // Evitar duplicados
            $table->unique(['Evento_id', 'Juez_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_juez');
    }
};