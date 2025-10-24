<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class EncuestaSeeder extends Seeder
{
    /**
     * php artisan db:seed --class=EncuestaSeeder
     */
    public function run(): void
    {
        // Insertar Categoria
        $categoriaId = DB::table('categorias')->insertGetId([
            'nombre' => 'aula',
            'descripcion' => 'Comedia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar Encuesta
        $encuestaId = DB::table('encuestas')->insertGetId([
            'titulo' => 'El más guapo',
            'descripcion' => 'Quien sera?',
            'categoria_id' => $categoriaId,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar Pregunta
        $preguntaId = DB::table('preguntas')->insertGetId([
            'encuesta_id' => $encuestaId,
            'texto' => 'Quien es el más guapo?',
            'tipo' => 'multiple',
            'orden' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar respuestas
        DB::table('respuestas')->insert([
            [
                'pregunta_id' => $preguntaId,
                'user_id' => 1,
                'texto' => 'Luis',
                'valor' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pregunta_id' => $preguntaId,
                'user_id' => 1,
                'texto' => 'Ariel',
                'valor' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pregunta_id' => $preguntaId,
                'user_id' => 1,
                'texto' => 'Ariel',
                'valor' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pregunta_id' => $preguntaId,
                'user_id' => 1,
                'texto' => 'Edi',
                'valor' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pregunta_id' => $preguntaId,
                'user_id' => 1,
                'texto' => 'José',
                'valor' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pregunta_id' => $preguntaId,
                'user_id' => 1,
                'texto' => 'Edi',
                'valor' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
