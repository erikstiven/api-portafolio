<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proyectos', function (Blueprint $t) {
            $t->id();
            $t->string('titulo')->index();
            $t->text('descripcion');
            $t->string('tecnologias')->index();
            $t->string('imagen_url')->nullable();
            $t->string('imagen_public_id')->nullable();
            $t->string('demo_url')->nullable();
            $t->string('github_url')->nullable();
            $t->boolean('destacado')->default(false)->index();
            $t->string('nivel')->nullable();
            $t->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete()->index();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('proyectos'); }
};
