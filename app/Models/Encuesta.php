<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Encuesta extends Model
{
    use HasFactory;

    // Nombre de la tabla si no sigue la convención plural de Laravel (encuestas)
    protected $table = 'encuestas';

    // Clave primaria, inferida del uso de 'pk_encuesta' en el controlador
    protected $primaryKey = 'pk_encuesta';

    // Indica que la clave primaria es un entero (por defecto)
    protected $keyType = 'int';

    // Los campos que se pueden asignar masivamente (utilizados en el método store)
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estatus',
        'fk_user_creador',
    ];

    /**
     * Relación con el usuario que creó la encuesta.
     */
    public function creador()
    {
        // Asumiendo que existe un modelo User.php
        return $this->belongsTo(User::class, 'fk_user_creador');
    }

    /**
     * Relación con las preguntas de la encuesta.
     */
    public function preguntas()
    {
        // Asumiendo que el modelo Pregunta existe y tiene una clave foránea 'fk_encuesta'
        return $this->hasMany(Pregunta::class, 'fk_encuesta', 'pk_encuesta');
    }

    /**
     * Método utilizado en EncuestaController@show para obtener el conteo total de respuestas.
     * Este método recorre las preguntas y sus opciones para sumar el total de respuestas.
     *
     * @return int
     */
    public function totalRespuestas(): int
    {
        $total = 0;

        // Cargar las respuestas de todas las opciones de todas las preguntas
        $this->load(['preguntas.opciones.respuestas']);

        foreach ($this->preguntas as $pregunta) {
            if (in_array($pregunta->tipo, ['opcion_multiple', 'escala', 'si_no'])) {
                // Para preguntas con opciones, sumamos las respuestas a las opciones
                foreach ($pregunta->opciones as $opcion) {
                    // Suma el conteo de respuestas de cada opción
                    $total += $opcion->respuestas->count();
                }
            } else if ($pregunta->tipo === 'texto_libre') {
                // Si tienes un modelo RespuestaTextoLibre o similar, deberías usarlo aquí.
                // Asumiendo una relación directa o un método de conteo para texto_libre.
                // Si la relación 'respuestas' existe en el modelo Pregunta y aplica a texto_libre:
                // $total += $pregunta->respuestas->count();

                // Por ahora, si solo las opciones tienen respuestas, asumimos que texto_libre se cuenta aparte
                // o no se incluye en este conteo general de 'totalRespuestas' si no tiene opciones.
                // Si el diseño de tu DB asume que las respuestas de texto libre se cuentan en una tabla 'respuestas' general
                // vinculada a 'Pregunta', ajusta aquí. Para simplificar, si no es una pregunta de opción, la excluimos del total basado en tu código.
            }
        }
        
        // **NOTA IMPORTANTE:** Tu implementación en el controlador asume que la cuenta de respuestas
        // debe hacerse a través de la relación de opción (`preguntas.opciones.respuestas`).
        // Si tienes respuestas para `texto_libre` en una tabla diferente, el cálculo debe ajustarse.
        // Aquí se devuelve el total basado en las opciones.

        return $total;
    }
}
