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
        Schema::create('orden_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->enum('estado', ['asignada', 'pendiente', 'en_curso', 'en_camino', 'en_proceso', 'completada', 'finalizada', 'cancelada'])->default('pendiente');
            $table->dateTime('fecha_entrega_prevista')->nullable();
            $table->dateTime('fecha_asignacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajos');
    }
};
