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
        Schema::table('evento', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable()->after('Fecha_inicio');
            $table->time('hora_fin')->nullable()->after('Fecha_fin');
            $table->json('tecnologias')->nullable()->after('Categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evento', function (Blueprint $table) {
            $table->dropColumn(['hora_inicio', 'hora_fin', 'tecnologias']);
        });
    }
};
