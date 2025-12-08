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
            // Renombrar 'descripcion' a 'Descripcion' si existe
            if (Schema::hasColumn('criterio', 'descripcion')) {
                $table->renameColumn('descripcion', 'Descripcion');
            }
            
            // Agregar columna 'Nombre' si no existe
            if (!Schema::hasColumn('criterio', 'Nombre')) {
                $table->string('Nombre', 255)->after('Id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterio', function (Blueprint $table) {
            if (Schema::hasColumn('criterio', 'Nombre')) {
                $table->dropColumn('Nombre');
            }
            
            if (Schema::hasColumn('criterio', 'Descripcion')) {
                $table->renameColumn('Descripcion', 'descripcion');
            }
        });
    }
};
