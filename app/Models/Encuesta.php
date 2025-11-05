<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_id',
        'estado',
        'fecha_inicio', // <-- AÑADIDO
        'fecha_fin',    // <-- AÑADIDO
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_inicio' => 'datetime', // <-- Buena práctica
        'fecha_fin' => 'datetime',    // <-- Buena práctica
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }
}