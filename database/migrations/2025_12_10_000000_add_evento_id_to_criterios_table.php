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
        Schema::table('criterio', function (Blueprint $table) {
            // Agregar columna Evento_id si no existe
            if (!Schema::hasColumn('criterio', 'Evento_id')) {
                $table->unsignedBigInteger('Evento_id')->nullable()->after('Id');
                $table->foreign('Evento_id')->references('Id')->on('evento')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterio', function (Blueprint $table) {
            if (Schema::hasColumn('criterio', 'Evento_id')) {
                $table->dropForeign(['Evento_id']);
                $table->dropColumn('Evento_id');
            }
        });
    }
};
