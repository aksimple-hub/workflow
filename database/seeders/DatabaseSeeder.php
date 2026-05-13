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
        // Crear el Administrador (ID 1)
        \App\Models\User::factory()->create([
            'name' => 'Admin Workflow',
            'email' => 'admin@test.com',
            'password' => 'password',
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Crear un Técnico de prueba (ID 2)
        \App\Models\User::factory()->create([
            'name' => 'Juan Técnico',
            'email' => 'tecnico@test.com',
            'password' => 'password',
            'role' => 'tecnico',
            'is_approved' => true,
        ]);

        // Crear un Cliente de prueba (ID 3)
        $userCliente = \App\Models\User::factory()->create([
            'name' => 'Empresa Cliente',
            'email' => 'cliente@test.com',
            'password' => 'password',
            'role' => 'cliente',
            'is_approved' => true,
        ]);

        // Insertar el cliente correspondiente en la tabla clientes (forzando ID 3 para que coincida con el usuario)
        \Illuminate\Support\Facades\DB::table('clientes')->insert([
            'id' => $userCliente->id,
            'nombre' => 'Empresa Cliente S.A.',
            'dni_cif' => 'B12345678',
            'email' => 'cliente@test.com',
            'telefono' => '600123456',
            'direccion' => 'Calle Falsa 123, Madrid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
