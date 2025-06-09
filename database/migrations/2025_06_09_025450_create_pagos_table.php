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
         Schema::create('pagos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cita_id')->constrained('citas')->unique()->onDelete('cascade');
        $table->float('monto');                       // total a cobrar
        $table->float('monto_recibido')->nullable();  // solo efectivo
        $table->float('vuelto')->nullable();          // solo efectivo

        $table->enum('metodo', ['efectivo', 'transferencia', 'pasarela']);
        $table->string('referencia')->nullable();     // nº de transacción o recibo
        $table->string('estado')->default('pendiente'); // pendiente | pagado | rechazado
        $table->string('pasarela_id')->nullable();    // id devuelto por Stripe/PayPal

        $table->timestamp('fecha_pago')->useCurrent();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
