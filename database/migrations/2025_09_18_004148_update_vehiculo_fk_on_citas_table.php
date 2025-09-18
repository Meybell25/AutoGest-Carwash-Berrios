<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_id']);
            $table->foreign('vehiculo_id')
                  ->references('id')->on('vehiculos')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_id']);
            $table->foreign('vehiculo_id')
                  ->references('id')->on('vehiculos');
        });
    }
};
