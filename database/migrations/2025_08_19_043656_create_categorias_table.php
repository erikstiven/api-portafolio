<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Si existiera un FK anterior con otro nombre, lo soltamos
            try { $table->dropForeign(['categoria_id']); } catch (\Throwable $e) {}

            // Asegurar tipo correcto (unsigned big int)
            $table->unsignedBigInteger('categoria_id')->change();

            // Volvemos a crear la FK bien
            $table->foreign('categoria_id')
                ->references('id')->on('categorias')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            try { $table->dropForeign(['categoria_id']); } catch (\Throwable $e) {}
        });
    }
};
