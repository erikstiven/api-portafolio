<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        ALTER TABLE `perfil`
          MODIFY `descripcion_uno_sobre_mi`   TEXT NULL,
          MODIFY `descripcion_dos_sobre_mi`   TEXT NULL,
          MODIFY `descripcion_hero`           TEXT NULL,

          MODIFY `iniciales_logo`             VARCHAR(10)   NULL,
          MODIFY `telefono`                   VARCHAR(20)   NULL,
          MODIFY `titulo_hero`                VARCHAR(255)  NULL,
          MODIFY `perfil_tecnico_hero`        VARCHAR(255)  NULL,

          MODIFY `cv_url`                     VARCHAR(255)  NULL,
          MODIFY `cv_public_id`               VARCHAR(255)  NULL,
          MODIFY `cv_filename`                VARCHAR(255)  NULL,
          MODIFY `foto_hero_url`              VARCHAR(255)  NULL,
          MODIFY `foto_hero_public_id`        VARCHAR(255)  NULL,
          MODIFY `foto_sobre_mi_url`          VARCHAR(255)  NULL,
          MODIFY `foto_sobre_mi_public_id`    VARCHAR(255)  NULL
    ");
    }
    public function down(): void {}
};
