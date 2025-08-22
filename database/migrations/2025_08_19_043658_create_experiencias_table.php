<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('experiencias', function (Blueprint $t) {
            $t->id();
            $t->string('puesto');
            $t->string('empresa');
            $t->date('fecha_inicio');
            $t->date('fecha_fin')->nullable();
            $t->boolean('actualmente')->default(false);
            $t->text('descripcion');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('experiencias'); }
};
