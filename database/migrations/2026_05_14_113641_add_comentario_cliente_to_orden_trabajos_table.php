<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->text('comentario_cliente')->nullable()->after('recomendaciones');
        });
    }

    public function down(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->dropColumn('comentario_cliente');
        });
    }
};
