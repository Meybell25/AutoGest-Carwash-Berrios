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
        Schema::create('horarios', function (Blueprint $table) {
        $table->id();
        $table->tinyInteger('dia_semana');           // 0 = domingo, 1 = lunes…
        $table->time('hora_inicio');
        $table->time('hora_fin');
        $table->boolean('activo')->default(1);       // permitir desactivar un bloque
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
