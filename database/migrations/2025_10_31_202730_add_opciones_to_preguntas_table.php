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
            // AÑADE ESTA LÍNEA
            // Usamos ->text() para guardar el JSON de opciones.
            // La ponemos después de 'orden'.
            $table->text('opciones')->nullable()->after('orden'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preguntas', function (Blueprint $table) {
            // AÑADE ESTA LÍNEA
            $table->dropColumn('opciones');
        });
    }
};
