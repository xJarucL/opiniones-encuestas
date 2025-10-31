<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipo_usuario;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tipo: Administrador
        Tipo_usuario::create([
            'nombre' => 'Administrador'
        ]);

        // Tipo: Usuario Normal
        Tipo_usuario::create([
            'nombre' => 'Usuario'
        ]);

        $this->command->info('✅ Tipos de usuario creados exitosamente');
    }
}