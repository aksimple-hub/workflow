<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->index('estado');
            $table->index('cliente_id');
            $table->index('usuario_id');
            $table->index('created_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('cliente_id');
        });
    }

    public function down(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['cliente_id']);
            $table->dropIndex(['usuario_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['cliente_id']);
        });
    }
};
