<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->text('recomendaciones')->nullable()->after('observaciones');
            $table->string('satisfaccion')->nullable()->after('recomendaciones'); // satisfecho|neutral|insatisfecho
            $table->time('hora_inicio')->nullable()->after('satisfaccion');
            $table->time('hora_fin')->nullable()->after('hora_inicio');
        });
    }

    public function down(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->dropColumn(['recomendaciones', 'satisfaccion', 'hora_inicio', 'hora_fin']);
        });
    }
};
