<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación con encuestas
     */
    public function encuestas()
    {
        return $this->hasMany(Encuesta::class, 'categoria_id');
    }
}