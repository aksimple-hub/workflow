<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE orden_trabajos ALTER COLUMN satisfaccion TYPE SMALLINT USING (satisfaccion::SMALLINT)');
        } else {
            DB::statement('ALTER TABLE orden_trabajos MODIFY COLUMN satisfaccion TINYINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE orden_trabajos ALTER COLUMN satisfaccion TYPE VARCHAR(255) USING (satisfaccion::VARCHAR)');
        } else {
            DB::statement('ALTER TABLE orden_trabajos MODIFY COLUMN satisfaccion VARCHAR(255) NULL');
        }
    }
};
