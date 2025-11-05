<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Importar Attribute

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    // Tu Primary Key está bien
    protected $primaryKey = 'pk_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombres',
        'ap_paterno',
        'ap_materno',
        'username',
        'email',
        'password',
        'fk_tipo_user',
        'img_user',
    ];

    public function tipo_usuario(){
        return $this->belongsTo(Tipo_usuario::class, 'fk_tipo_user', 'pk_tipo_user');
    }

    // ==========================================================
    // ¡NUEVO ACCESOR AÑADIDO!
    // Esto nos da acceso a una propiedad $user->full_name
    // ==========================================================
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->nombres} {$this->ap_paterno} {$this->ap_materno}",
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
