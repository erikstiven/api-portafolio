<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('perfil', function (Blueprint $table) {
            $table->id();
            
            // Información básica
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('iniciales_logo', 10)->nullable();
            
            // Sección Hero
            $table->string('titulo_hero')->nullable();
            $table->string('perfil_tecnico_hero')->nullable();
            $table->text('descripcion_hero')->nullable();
            
            // Archivos Hero
            $table->string('foto_hero_url')->nullable();
            $table->string('foto_hero_public_id')->nullable();
            
            // Sección Sobre Mí
            $table->text('descripcion_uno_sobre_mi')->nullable();
            $table->text('descripcion_dos_sobre_mi')->nullable();
            
            // Foto Sobre Mí
            $table->string('foto_sobre_mi_url')->nullable();
            $table->string('foto_sobre_mi_public_id')->nullable();
            
            // CV
            $table->string('cv_url')->nullable();
            $table->string('cv_public_id')->nullable();
            $table->string('cv_filename')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('perfil');
    }
};