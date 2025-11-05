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
        Schema::table('preguntas', function (Blueprint $table) {
            // Cambiamos la columna a un VARCHAR de 50 (suficiente)
            // .change() modifica la columna existente
            $table->string('tipo', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preguntas', function (Blueprint $table) {
            // Volvemos a como estaba (asumiendo que era 8)
            $table->string('tipo', 8)->change();
        });
    }
};