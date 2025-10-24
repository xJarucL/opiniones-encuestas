<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_user')->insert([
            'nombre' => 'Administrador'
        ]);
        DB::table('tipo_user')->insert([
            'nombre' => 'Común'
        ]);
    }
}
