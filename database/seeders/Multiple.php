<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Multiple extends Seeder
{
    /**
     * php artisan db:seed --class=Multiple
     */
    public function run(): void
    {
        $categoriaId = DB::table('categorias')->insertGetId([
            'nombre' => 'Comida',
            'descripcion' => 'Encuestas sobre preferencias alimenticias',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $encuestaId = DB::table('encuestas')->insertGetId([
            'titulo' => 'Comida Favorita',
            'descripcion' => 'Encuesta para conocer qué comidas disfrutan más los usuarios',
            'categoria_id' => $categoriaId,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pregunta1 = DB::table('preguntas')->insertGetId([
            'encuesta_id' => $encuestaId,
            'texto' => '¿Qué tanto te gusta la pizza?',
            'tipo' => 'multiple',
            'orden' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('respuestas')->insert([
            ['pregunta_id' => $pregunta1, 'user_id' => 1, 'texto' => 'Mucho', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta1, 'user_id' => 1, 'texto' => 'Mucho', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta1, 'user_id' => 1, 'texto' => 'Mucho', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta1, 'user_id' => 1, 'texto' => 'Poco', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta1, 'user_id' => 1, 'texto' => 'No me gusta', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta1, 'user_id' => 1, 'texto' => 'Depende del tipo', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $pregunta2 = DB::table('preguntas')->insertGetId([
            'encuesta_id' => $encuestaId,
            'texto' => '¿Qué tanto disfrutas los tacos?',
            'tipo' => 'multiple',
            'orden' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('respuestas')->insert([
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Mucho', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Mucho', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Poco', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Poco', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Poco', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'No me gusta', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'No me gusta', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'No me gusta', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'No me gusta', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'No me gusta', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Depende del tipo', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['pregunta_id' => $pregunta2, 'user_id' => 1, 'texto' => 'Depende del tipo', 'valor' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
