<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('redes_sociales', function (Blueprint $t) {
            $t->id();
            $t->string('nombre');
            $t->string('url');
            $t->string('icono');
            $t->boolean('activo')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('redes_sociales'); }
};
