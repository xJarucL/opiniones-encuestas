<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Comentario extends Model
{
    use SoftDeletes;

    protected $table = 'comentarios';
    protected $primaryKey = 'pk_comentario';
    protected $fillable = [
        'fk_autor',
        'fk_perfil_user',
        'fk_coment_respuesta',
        'contenido',
        'anonimo',
        'estatus'
    ];

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    const DELETED_AT = 'fecha_eliminacion';

    // Relaciones
    public function autor() {
        return $this->belongsTo(User::class, 'fk_autor', 'pk_usuario');
    }

    public function perfil() {
        return $this->belongsTo(User::class, 'fk_perfil_user', 'pk_usuario');
    }

    public function padre() {
        return $this->belongsTo(self::class, 'fk_coment_respuesta', 'pk_comentario');
    }

    public function respuestas() {
        return $this->hasMany(self::class, 'fk_coment_respuesta', 'pk_comentario');
    }

    // Mostrar nombre del autor (anónimo o visible)
    public function getAutorPublicoAttribute(): string {
        $isAdmin = Auth::check() && (Auth::user()->fk_tipo_user == 1);
        if ($this->anonimo && !$isAdmin) {
            return 'Anónimo';
        }
        return $this->autor->username ?? 'Usuario';
    }
}
