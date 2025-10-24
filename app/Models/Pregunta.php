<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';

    protected $fillable = [
        'encuesta_id',
        'texto',
        'tipo',
        'orden',
    ];

    /**
     * Relación con encuesta
     */
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }

    /**
     * Relación con respuestas
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }
}