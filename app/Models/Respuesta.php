<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    /**
     * Asumimos que la tabla se llama 'respuestas'
     */
    protected $table = 'respuestas';

    protected $fillable = [
        'pregunta_id',
        'user_id',
        'respuesta',
    ];

    /**
     * Una Respuesta pertenece a una Pregunta
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    /**
     * Una Respuesta pertenece a un Usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
