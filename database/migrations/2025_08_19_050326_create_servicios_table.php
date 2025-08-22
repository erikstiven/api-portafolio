<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('servicios', function (Blueprint $t) {
            $t->id();
            $t->string('nombre');
            $t->text('descripcion');
            $t->decimal('precio', 10, 2)->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('servicios'); }
};
