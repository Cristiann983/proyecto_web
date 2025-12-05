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
        Schema::create('calificacion', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Proyecto_id'); // ✅ FK a proyecto
            $table->unsignedBigInteger('Juez_id');
            $table->unsignedBigInteger('Criterio_id');
            $table->integer('Calificacion');
            $table->timestamps();
            
            $table->foreign('Proyecto_id')->references('Id')->on('proyecto')->onDelete('cascade');
            $table->foreign('Juez_id')->references('Id')->on('juez')->onDelete('cascade');
            $table->foreign('Criterio_id')->references('Id')->on('criterio')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificacion');
    }
};
