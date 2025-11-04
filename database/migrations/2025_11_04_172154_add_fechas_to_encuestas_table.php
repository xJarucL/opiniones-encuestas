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
        Schema::table('encuestas', function (Blueprint $table) {
            // Añade las dos nuevas columnas
            // Las hacemos 'nullable' porque tu formulario dijo que eran opcionales
            $table->timestamp('fecha_inicio')->nullable()->after('estado');
            $table->timestamp('fecha_fin')->nullable()->after('fecha_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuestas', function (Blueprint $table) {
            // Esto es para poder revertir los cambios si es necesario
            $table->dropColumn(['fecha_inicio', 'fecha_fin']);
        });
    }
};