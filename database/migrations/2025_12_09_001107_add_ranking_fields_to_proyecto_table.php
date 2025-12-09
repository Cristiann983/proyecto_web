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
        Schema::table('proyecto', function (Blueprint $table) {
            $table->integer('ranking_posicion')->nullable()->after('Categoria');
            $table->decimal('ranking_puntuacion', 8, 2)->nullable()->after('ranking_posicion');
            $table->timestamp('ultima_calificacion')->nullable()->after('ranking_puntuacion');
            
            // Índice para mejorar rendimiento de consultas de ranking
            $table->index(['Evento_id', 'ranking_posicion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyecto', function (Blueprint $table) {
            $table->dropIndex(['Evento_id', 'ranking_posicion']);
            $table->dropColumn(['ranking_posicion', 'ranking_puntuacion', 'ultima_calificacion']);
        });
    }
};
