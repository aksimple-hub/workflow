<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el Administrador
        \App\Models\User::factory()->create([
            'name' => 'Admin Workflow',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Crear un Técnico de prueba
        \App\Models\User::factory()->create([
            'name' => 'Juan Técnico',
            'email' => 'tecnico@test.com',
            'password' => bcrypt('password'),
            'role' => 'tecnico',
        ]);
    }
}
