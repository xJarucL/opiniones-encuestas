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
        Schema::table('respuestas', function (Blueprint $table) {
            // AÑADE ESTA LÍNEA
            // Asumiendo que guardas la respuesta como texto.
            // La ponemos después de 'user_id' (si existe).
            $table->string('respuesta')->after('user_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            // AÑADE ESTA LÍNEA
            $table->dropColumn('respuesta');
        });
    }
};
