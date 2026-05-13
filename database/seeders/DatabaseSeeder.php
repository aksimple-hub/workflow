<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tecnico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Administrador
        User::factory()->create([
            'name'        => 'Admin Workflow',
            'email'       => 'admin@test.com',
            'password'    => 'password',
            'role'        => 'admin',
            'is_approved' => true,
        ]);

        // Técnicos
        $tecnicos = [
            [
                'user'   => ['name' => 'Carlos Martínez', 'email' => 'tecnico1@test.com'],
                'perfil' => [
                    'nombre'      => 'Carlos',
                    'apellidos'   => 'Martínez López',
                    'dni_nie'     => '12345678A',
                    'telefono'    => '611111111',
                    'direccion'   => 'Calle Mayor 10, Madrid',
                    'experiencia' => 'Especialista en instalaciones eléctricas con 5 años de experiencia.',
                ],
            ],
            [
                'user'   => ['name' => 'Ana García', 'email' => 'tecnico2@test.com'],
                'perfil' => [
                    'nombre'      => 'Ana',
                    'apellidos'   => 'García Fernández',
                    'dni_nie'     => '23456789B',
                    'telefono'    => '622222222',
                    'direccion'   => 'Avenida Libertad 45, Barcelona',
                    'experiencia' => 'Técnica en climatización y mantenimiento industrial.',
                ],
            ],
            [
                'user'   => ['name' => 'Pedro Sánchez', 'email' => 'tecnico3@test.com'],
                'perfil' => [
                    'nombre'      => 'Pedro',
                    'apellidos'   => 'Sánchez Ruiz',
                    'dni_nie'     => '34567890C',
                    'telefono'    => '633333333',
                    'direccion'   => 'Plaza España 3, Sevilla',
                    'experiencia' => 'Fontanería y reparaciones generales con 8 años de experiencia.',
                ],
            ],
        ];

        foreach ($tecnicos as $data) {
            $user = User::factory()->create([
                'name'        => $data['user']['name'],
                'email'       => $data['user']['email'],
                'password'    => 'password',
                'role'        => 'tecnico',
                'is_approved' => true,
            ]);

            Tecnico::create(array_merge($data['perfil'], ['id' => $user->id]));
        }

        // Clientes
        $clientes = [
            [
                'cliente' => [
                    'nombre'    => 'Tecnologías Avanzadas S.L.',
                    'dni_cif'   => 'B11111111',
                    'email'     => 'cliente1@test.com',
                    'telefono'  => '911111111',
                    'direccion' => 'Calle Innovación 1, Madrid',
                ],
                'user' => ['name' => 'Tecnologías Avanzadas', 'email' => 'cliente1@test.com'],
            ],
            [
                'cliente' => [
                    'nombre'    => 'Construcciones Norte S.A.',
                    'dni_cif'   => 'A22222222',
                    'email'     => 'cliente2@test.com',
                    'telefono'  => '922222222',
                    'direccion' => 'Polígono Industrial Norte 22, Bilbao',
                ],
                'user' => ['name' => 'Construcciones Norte', 'email' => 'cliente2@test.com'],
            ],
            [
                'cliente' => [
                    'nombre'    => 'Servicios Globales S.L.',
                    'dni_cif'   => 'B33333333',
                    'email'     => 'cliente3@test.com',
                    'telefono'  => '933333333',
                    'direccion' => 'Gran Vía 99, Valencia',
                ],
                'user' => ['name' => 'Servicios Globales', 'email' => 'cliente3@test.com'],
            ],
            [
                'cliente' => [
                    'nombre'    => 'Laura Romero Vidal',
                    'dni_cif'   => '45678901D',
                    'email'     => 'cliente4@test.com',
                    'telefono'  => '676444444',
                    'direccion' => 'Calle Alfonso I 14, Zaragoza',
                ],
                'user' => ['name' => 'Laura Romero', 'email' => 'cliente4@test.com'],
            ],
            [
                'cliente' => [
                    'nombre'    => 'Miguel Ángel Torres Pardo',
                    'dni_cif'   => '56789012E',
                    'email'     => 'cliente5@test.com',
                    'telefono'  => '687555555',
                    'direccion' => 'Paseo de la Independencia 8, Zaragoza',
                ],
                'user' => ['name' => 'Miguel Torres', 'email' => 'cliente5@test.com'],
            ],
        ];

        foreach ($clientes as $data) {
            $user = User::factory()->create([
                'name'        => $data['user']['name'],
                'email'       => $data['user']['email'],
                'password'    => 'password',
                'role'        => 'cliente',
                'is_approved' => true,
            ]);

            DB::table('clientes')->insert(array_merge($data['cliente'], [
                'id'         => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
