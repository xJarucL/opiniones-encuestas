<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_usuario extends Model
{
    use HasFactory; 


    protected $table = 'tipo_user';


    protected $primaryKey = 'pk_tipo_user';


    public function users()
    {
        return $this->hasMany(User::class, 'fk_tipo_user', 'pk_tipo_user');
    }
}
