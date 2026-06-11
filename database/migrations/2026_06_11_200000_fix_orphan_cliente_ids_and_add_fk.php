<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Poner cliente_id a null en usuarios que apuntan a clientes inexistentes
        DB::statement('
            UPDATE users
            SET cliente_id = NULL
            WHERE cliente_id IS NOT NULL
            AND cliente_id NOT IN (SELECT id FROM clientes)
        ');

        // Añadir FK con ON DELETE SET NULL para evitar referencias huérfanas en el futuro
        Schema::table('users', function ($table) {
            $table->foreign('cliente_id')
                  ->references('id')->on('clientes')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropForeign(['cliente_id']);
        });
    }
};
