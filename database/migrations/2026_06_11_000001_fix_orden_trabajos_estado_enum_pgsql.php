<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE orden_trabajos DROP CONSTRAINT IF EXISTS orden_trabajos_estado_check');
            DB::statement("ALTER TABLE orden_trabajos ADD CONSTRAINT orden_trabajos_estado_check CHECK (estado IN ('asignada','pendiente','en_curso','en_camino','en_proceso','completada','finalizada','cancelada','pendiente_valoracion','pendiente_reprogramacion'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE orden_trabajos DROP CONSTRAINT IF EXISTS orden_trabajos_estado_check');
            DB::statement("ALTER TABLE orden_trabajos ADD CONSTRAINT orden_trabajos_estado_check CHECK (estado IN ('asignada','pendiente','en_curso','en_camino','en_proceso','completada','finalizada','cancelada'))");
        }
    }
};