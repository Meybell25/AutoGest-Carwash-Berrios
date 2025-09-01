<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Agregar campos adicionales si no existen
            if (!Schema::hasColumn('pagos', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('fecha_pago');
            }
            
            if (!Schema::hasColumn('pagos', 'detalles_adicionales')) {
                $table->json('detalles_adicionales')->nullable()->after('observaciones');
            }
            
            // Modificar el campo referencia para hacerlo más largo si es necesario
            $table->string('referencia', 300)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn(['observaciones', 'detalles_adicionales']);
            $table->string('referencia', 255)->nullable()->change();
        });
    }
};