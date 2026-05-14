<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orden_trabajos MODIFY COLUMN estado ENUM('asignada','pendiente','en_curso','en_camino','en_proceso','completada','finalizada','cancelada','pendiente_valoracion') NOT NULL DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orden_trabajos MODIFY COLUMN estado ENUM('asignada','pendiente','en_curso','en_camino','en_proceso','completada','finalizada','cancelada') NOT NULL DEFAULT 'pendiente'");
    }
};
