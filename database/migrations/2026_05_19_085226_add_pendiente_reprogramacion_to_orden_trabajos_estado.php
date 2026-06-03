<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orden_trabajos MODIFY COLUMN estado ENUM('asignada','pendiente','en_curso','en_camino','en_proceso','completada','finalizada','cancelada','pendiente_valoracion','pendiente_reprogramacion') NOT NULL DEFAULT 'pendiente'");
        } else {
            DB::statement("ALTER TYPE orden_trabajos_estado_enum ADD VALUE IF NOT EXISTS 'pendiente_reprogramacion'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orden_trabajos MODIFY COLUMN estado ENUM('asignada','pendiente','en_curso','en_camino','en_proceso','completada','finalizada','cancelada','pendiente_valoracion') NOT NULL DEFAULT 'pendiente'");
        }
    }
};
