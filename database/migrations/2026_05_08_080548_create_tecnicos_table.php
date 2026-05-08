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
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id(); // foreign to users
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('dni_nie')->unique();
            $table->string('telefono');
            $table->string('direccion');
            $table->string('foto_perfil')->nullable();
            $table->text('experiencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};
