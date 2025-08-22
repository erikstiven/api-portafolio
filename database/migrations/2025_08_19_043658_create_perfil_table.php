<?php
// database/migrations/xxxx_xx_xx_xxxxxx_update_perfil_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perfil', function (Blueprint $t) {
            // Si algunas ya existen como NOT NULL, cámbialas a nullable:
            $t->string('iniciales_logo')->nullable()->change();
            $t->string('telefono')->nullable()->change();
            $t->string('titulo_hero')->nullable()->default(null)->change();
            $t->string('perfil_tecnico_hero')->nullable()->default(null)->change();

            // TEXT: quitar defaults y permitir NULL
            $t->text('descripcion_hero')->nullable()->change();
            $t->text('descripcion_uno_sobre_mi')->nullable()->change();
            $t->text('descripcion_dos_sobre_mi')->nullable()->change();

            // Asegura que las de archivos sean NULL por defecto
            $t->string('cv_url')->nullable()->change();
            $t->string('cv_public_id')->nullable()->change();
            $t->string('foto_hero_url')->nullable()->change();
            $t->string('foto_hero_public_id')->nullable()->change();
            $t->string('foto_sobre_mi_url')->nullable()->change();
            $t->string('foto_sobre_mi_public_id')->nullable()->change();

            // Si no existe, agrégala (según tu migration inicial la tienes):
            if (!Schema::hasColumn('perfil', 'cv_filename')) {
                $t->string('cv_filename')->nullable();
            }
        });
    }

    public function down(): void
    {
        // opcional: revertir cambios si lo necesitas
    }
};
