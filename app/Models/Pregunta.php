<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';

    /**
     * ¡LA CORRECCIÓN ESTÁ AQUÍ!
     * 'opciones' debe estar en $fillable para que se guarde.
     */
    protected $fillable = [
        'encuesta_id',
        'texto',
        'tipo',
        'orden',
        'opciones', // <-- Esta línea es la importante
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id', 'id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id', 'id');
    }
}