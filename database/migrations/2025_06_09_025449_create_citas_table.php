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
        Schema::create('citas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('usuarios');
        $table->foreignId('vehiculo_id')->constrained('vehiculos');
        $table->dateTime('fecha_hora');
        $table->enum('estado', ['pendiente','confirmada','en_proceso','finalizada','cancelada']);
        $table->text('observaciones')->nullable();
        $table->unique('fecha_hora'); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
