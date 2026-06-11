<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Limpiar usuarios con cliente_id que no existe en clientes (registros huérfanos)
        DB::statement('
            UPDATE users
            SET cliente_id = NULL
            WHERE cliente_id IS NOT NULL
            AND cliente_id NOT IN (SELECT id FROM clientes)
        ');
    }

    public function down(): void
    {
        // No hay nada que revertir
    }
};
