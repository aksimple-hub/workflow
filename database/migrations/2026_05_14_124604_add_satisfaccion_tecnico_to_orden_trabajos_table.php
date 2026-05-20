<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->unsignedTinyInteger('satisfaccion_tecnico')->nullable()->after('satisfaccion');
        });
    }

    public function down(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->dropColumn('satisfaccion_tecnico');
        });
    }
};
