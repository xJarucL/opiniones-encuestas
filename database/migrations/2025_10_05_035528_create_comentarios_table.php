<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->bigIncrements('pk_comentario');

            // Relaciones con usuarios (usa pk_usuario)
            $table->unsignedBigInteger('fk_autor');
            $table->unsignedBigInteger('fk_perfil_user');

            $table->foreign('fk_autor')->references('pk_usuario')->on('users')->cascadeOnDelete();
            $table->foreign('fk_perfil_user')->references('pk_usuario')->on('users')->cascadeOnDelete();

            // Respuestas o hilos
            $table->unsignedBigInteger('fk_coment_respuesta')->nullable();
            $table->foreign('fk_coment_respuesta')->references('pk_comentario')->on('comentarios')->cascadeOnDelete();

            $table->text('contenido');
            $table->boolean('anonimo')->default(false);
            $table->enum('estatus', ['visible','oculto','eliminado'])->default('visible');

            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('fecha_eliminacion')->nullable();

            $table->index(['fk_perfil_user','fk_coment_respuesta','fecha_creacion'], 'cmt_perfil_padre_fecha_idx');
            $table->index('estatus', 'cmt_estatus_idx');
            $table->index('fk_autor', 'cmt_autor_idx');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
