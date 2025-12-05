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
        Schema::create('avance', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Proyecto_id'); // ✅ FK a proyecto
            $table->text('Descripcion');
            $table->integer('Porcentaje')->default(0);
            $table->date('Fecha');
            $table->timestamps();
            
            $table->foreign('Proyecto_id')->references('Id')->on('proyecto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avance');
    }
};
