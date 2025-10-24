<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();

            // 🔹 Relación con preguntas
            $table->foreignId('pregunta_id')
                ->constrained('preguntas')
                ->onDelete('cascade');

            // 🔹 Relación con usuarios
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('pk_usuario')
                ->on('users')
                ->onDelete('cascade');

            // 🔹 Otros campos
            $table->text('texto')->nullable();
            $table->integer('valor')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
