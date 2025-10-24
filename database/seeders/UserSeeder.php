<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombres' => 'Admin',
            'ap_paterno' => 'Admin',
            'ap_materno' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'fk_tipo_user' => 1,
            'estatus' => true,
        ]);

        User::create([
            'nombres' => 'test',
            'ap_paterno' => 'test',
            'ap_materno' => 'test',
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => Hash::make('test'),
            'fk_tipo_user' => 2,
            'estatus' => true,
        ]);
    }
}
