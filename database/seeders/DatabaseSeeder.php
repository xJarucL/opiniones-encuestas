<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoUsuarioSeeder::class,
            UserSeeder::class,
            EncuestaSeeder::class,
            Multiple::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 ¡Base de datos poblada exitosamente!');
        $this->command->info('');
        $this->command->info('📋 Credenciales de acceso:');
        $this->command->info('');
        $this->command->info('👤 ADMINISTRADOR:');
        $this->command->info('   Email: admin@example.com');
        $this->command->info('   Password: admin');
        $this->command->info('');
        $this->command->info('👥 USUARIO DE PRUEBA:');
        $this->command->info('   Email: test@example.com');
        $this->command->info('   Password: test');
        $this->command->info('');
    }
}