<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Encuesta;
use App\Models\Categoria;
use App\Models\Pregunta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class EncuestaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba que los atributos se convierten (cast) correctamente.
     * **Verifica que 'estado' sea booleano y las fechas sean objetos Carbon.**
     *
     * @return void
     */
    public function test_atributos_se_convierten_correctamente()
    {
        $categoria = Categoria::factory()->create([
            'nombre' => 'Categoria de Prueba',
            'descripcion' => 'Descripción de categoría de prueba.',
        ]);
        
        $encuesta = Encuesta::factory()->create([
            'titulo' => 'Encuesta de Prueba de Casts',
            'descripcion' => 'Descripción de prueba.',
            'categoria_id' => $categoria->id,
            'estado' => 1, 
            'fecha_inicio' => '2025-01-01 10:00:00', 
            'fecha_fin' => '2025-12-31 17:30:00',    
        ]);

        
        $this->assertIsBool($encuesta->estado);
        $this->assertTrue($encuesta->estado);

        $this->assertInstanceOf(Carbon::class, $encuesta->fecha_inicio);
        $this->assertInstanceOf(Carbon::class, $encuesta->fecha_fin);
    }

    /**
     * Prueba la relación "belongsTo" con Categoria.
     * **Verifica que se puede acceder al modelo Categoria desde la Encuesta.**
     *
     * @return void
     */
    public function test_encuesta_belongs_to_a_categoria_relationship()
    {
        $categoria = Categoria::factory()->create([
            'nombre' => 'Categoria de Prueba',
            'descripcion' => 'Descripción de categoría de prueba.',
        ]);
        
        $encuesta = Encuesta::factory()->create([
            'titulo' => 'Encuesta de Prueba de Relación',
            'descripcion' => 'Descripción de prueba.',
            'categoria_id' => $categoria->id,
        ]);

        $categoriaRelacionada = $encuesta->categoria;

        $this->assertInstanceOf(Categoria::class, $categoriaRelacionada);
        $this->assertEquals($categoria->id, $categoriaRelacionada->id);
    }

    /**
     * Prueba la relación "hasMany" con Preguntas.
     * **Verifica que una encuesta puede tener y devolver múltiples preguntas.**
     *
     * @return void
     */
    public function test_encuesta_has_many_preguntas_relationship()
    {
        $categoria = Categoria::factory()->create([
            'nombre' => 'Categoria de Prueba',
            'descripcion' => 'Descripción de categoría de prueba.',
        ]);
        $encuesta = Encuesta::factory()->create([
            'titulo' => 'Encuesta de Prueba HasMany',
            'descripcion' => 'Descripción de prueba.',
            'categoria_id' => $categoria->id,
        ]);

        Pregunta::factory()->count(3)->create([
            'encuesta_id' => $encuesta->id,
            'texto' => '¿Funciona esto?', 
            'tipo' => 'text',           
        ]);

        $preguntasRelacionadas = $encuesta->preguntas;

        $this->assertInstanceOf(Collection::class, $preguntasRelacionadas);
        $this->assertCount(3, $preguntasRelacionadas);
        $this->assertInstanceOf(Pregunta::class, $preguntasRelacionadas->first());
    }
}