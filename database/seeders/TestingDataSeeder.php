<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Cita;
use App\Models\Pago;
use App\Models\DiaNoLaborable;
use App\Models\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea datos completos de prueba para todas las pruebas manuales de Germán
     */
    public function run(): void
    {
        echo "🌱 Iniciando creación de datos de prueba...\n\n";

        // 1. CREAR USUARIOS
        echo "👤 Creando usuarios...\n";
        $admin = Usuario::create([
            'nombre' => 'Admin Principal',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
            'estado' => true,
            'telefono' => '78901234',
        ]);

        $empleado = Usuario::create([
            'nombre' => 'Carlos Empleado',
            'email' => 'empleado@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'empleado',
            'estado' => true,
            'telefono' => '78905678',
        ]);

        $empleado2 = Usuario::create([
            'nombre' => 'María Asistente',
            'email' => 'empleado2@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'empleado',
            'estado' => true,
            'telefono' => '78909999',
        ]);

        echo "   ✅ Admin: admin@test.com\n";
        echo "   ✅ Empleado 1: empleado@test.com\n";
        echo "   ✅ Empleado 2: empleado2@test.com\n\n";

        // 2. CREAR CLIENTES
        echo "🧑 Creando clientes...\n";
        $cliente1 = Usuario::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'cliente',
            'estado' => true,
            'telefono' => '79001111',
        ]);

        $cliente2 = Usuario::create([
            'nombre' => 'María García',
            'email' => 'maria.garcia@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'cliente',
            'estado' => true,
            'telefono' => '79002222',
        ]);

        $cliente3 = Usuario::create([
            'nombre' => 'Pedro Rodríguez',
            'email' => 'pedro.rodriguez@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'cliente',
            'estado' => true,
            'telefono' => '79003333',
        ]);

        echo "   ✅ 3 clientes creados\n\n";

        // 3. CREAR VEHÍCULOS
        echo "🚗 Creando vehículos...\n";
        $vehiculo1 = Vehiculo::create([
            'usuario_id' => $cliente1->id,
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'placa' => 'ABC-1234',
            'color' => 'Blanco',
            'tipo' => 'sedan',
        ]);

        $vehiculo2 = Vehiculo::create([
            'usuario_id' => $cliente1->id,
            'marca' => 'Nissan',
            'modelo' => 'Sentra',
            'placa' => 'XYZ-5678',
            'color' => 'Negro',
            'tipo' => 'sedan',
        ]);

        $vehiculo3 = Vehiculo::create([
            'usuario_id' => $cliente2->id,
            'marca' => 'Ford',
            'modelo' => 'Escape',
            'placa' => 'DEF-9012',
            'color' => 'Rojo',
            'tipo' => 'pickup',
        ]);

        $vehiculo4 = Vehiculo::create([
            'usuario_id' => $cliente3->id,
            'marca' => 'Chevrolet',
            'modelo' => 'Spark',
            'placa' => 'GHI-3456',
            'color' => 'Azul',
            'tipo' => 'sedan',
        ]);

        $vehiculo5 = Vehiculo::create([
            'usuario_id' => $cliente3->id,
            'marca' => 'Toyota',
            'modelo' => 'Hilux',
            'placa' => 'JKL-7890',
            'color' => 'Plateado',
            'tipo' => 'pickup',
        ]);

        echo "   ✅ 5 vehículos creados\n\n";

        // 4. CREAR SERVICIOS
        echo "🧼 Creando servicios...\n";
        $servicio1 = Servicio::create([
            'nombre' => 'Lavado Básico',
            'descripcion' => 'Lavado exterior e interior básico',
            'precio' => 30.00,
            'duracion_min' => 30,
            'activo' => true,
        ]);

        $servicio2 = Servicio::create([
            'nombre' => 'Lavado Premium',
            'descripcion' => 'Lavado completo con encerado',
            'precio' => 70.00,
            'duracion_min' => 60,
            'activo' => true,
        ]);

        $servicio3 = Servicio::create([
            'nombre' => 'Encerado',
            'descripcion' => 'Encerado profesional',
            'precio' => 50.00,
            'duracion_min' => 45,
            'activo' => true,
        ]);

        $servicio4 = Servicio::create([
            'nombre' => 'Pulido',
            'descripcion' => 'Pulido de carrocería',
            'precio' => 100.00,
            'duracion_min' => 90,
            'activo' => true,
        ]);

        $servicio5 = Servicio::create([
            'nombre' => 'Limpieza de Motor',
            'descripcion' => 'Limpieza profunda del motor',
            'precio' => 80.00,
            'duracion_min' => 60,
            'activo' => true,
        ]);

        echo "   ✅ 5 servicios creados\n\n";

        // 5. CREAR CITAS PARA HOY (PARA PROBAR GESTIÓN DE CITAS)
        echo "📅 Creando citas para HOY (pruebas de Gestión de Citas)...\n";
        $hoy = Carbon::today();

        // Cita 1: HOY 9:00 AM - CONFIRMADA (lista para cambiar a EN_PROCESO)
        $cita_hoy_1 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo1->id,
            'fecha_hora' => $hoy->copy()->setTime(9, 0, 0),
            'estado' => 'confirmada',
            'observaciones' => 'Cliente llegó temprano, auto Toyota Corolla blanco',
        ]);
        $cita_hoy_1->servicios()->attach([
            $servicio1->id => ['precio' => 30.00],
            $servicio3->id => ['precio' => 50.00]
        ]);

        // Cita 2: HOY 10:30 AM - CONFIRMADA (para probar cambio de estado)
        $cita_hoy_2 = Cita::create([
            'usuario_id' => $cliente2->id,
            'vehiculo_id' => $vehiculo3->id,
            'fecha_hora' => $hoy->copy()->setTime(10, 30, 0),
            'estado' => 'confirmada',
            'observaciones' => 'Cliente solicitó lavado rápido, Ford Escape rojo',
        ]);
        $cita_hoy_2->servicios()->attach([
            $servicio2->id => ['precio' => 70.00]
        ]);

        // Cita 3: HOY 2:00 PM - EN_PROCESO (lista para agregar observaciones y finalizar)
        $cita_hoy_3 = Cita::create([
            'usuario_id' => $cliente3->id,
            'vehiculo_id' => $vehiculo4->id,
            'fecha_hora' => $hoy->copy()->setTime(14, 0, 0),
            'estado' => 'en_proceso',
            'observaciones' => '[' . $hoy->format('d/m/Y H:i') . '] Estado cambiado a \'En proceso\' por Carlos Empleado',
        ]);
        $cita_hoy_3->servicios()->attach([
            $servicio1->id => ['precio' => 30.00],
            $servicio5->id => ['precio' => 80.00]
        ]);

        // Cita 4: HOY 3:00 PM - EN_PROCESO (para probar finalización con pago efectivo)
        $cita_hoy_4 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo2->id,
            'fecha_hora' => $hoy->copy()->setTime(15, 0, 0),
            'estado' => 'en_proceso',
            'observaciones' => '[' . $hoy->format('d/m/Y H:i') . '] Estado cambiado a \'En proceso\' por Carlos Empleado' . "\n" .
                               '[' . $hoy->format('d/m/Y H:i') . '] Carlos Empleado: Vehículo requiere encerado adicional',
        ]);
        $cita_hoy_4->servicios()->attach([
            $servicio2->id => ['precio' => 70.00],
            $servicio3->id => ['precio' => 50.00]
        ]);

        // Cita 5: HOY 4:30 PM - CONFIRMADA (para probar transición inválida)
        $cita_hoy_5 = Cita::create([
            'usuario_id' => $cliente2->id,
            'vehiculo_id' => $vehiculo3->id,
            'fecha_hora' => $hoy->copy()->setTime(16, 30, 0),
            'estado' => 'confirmada',
            'observaciones' => 'Cliente llegó puntual',
        ]);
        $cita_hoy_5->servicios()->attach([
            $servicio4->id => ['precio' => 100.00]
        ]);

        // Cita 6: HOY 5:00 PM - EN_PROCESO (para probar pago con tarjeta)
        $cita_hoy_6 = Cita::create([
            'usuario_id' => $cliente3->id,
            'vehiculo_id' => $vehiculo5->id,
            'fecha_hora' => $hoy->copy()->setTime(17, 0, 0),
            'estado' => 'en_proceso',
            'observaciones' => '[' . $hoy->format('d/m/Y H:i') . '] Estado cambiado a \'En proceso\' por María Asistente',
        ]);
        $cita_hoy_6->servicios()->attach([
            $servicio1->id => ['precio' => 30.00],
            $servicio3->id => ['precio' => 50.00],
            $servicio5->id => ['precio' => 80.00]
        ]);

        // Cita 7: HOY 5:30 PM - PENDIENTE (para probar finalización simple)
        $cita_hoy_7 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo1->id,
            'fecha_hora' => $hoy->copy()->setTime(17, 30, 0),
            'estado' => 'pendiente',
            'observaciones' => null,
        ]);
        $cita_hoy_7->servicios()->attach([
            $servicio1->id => ['precio' => 30.00]
        ]);

        echo "   ✅ 7 citas para hoy creadas:\n";
        echo "      - 3 CONFIRMADAS (para cambiar a en_proceso)\n";
        echo "      - 3 EN_PROCESO (para agregar observaciones y finalizar)\n";
        echo "      - 1 PENDIENTE (para finalización simple)\n\n";

        // 6. CREAR CITAS PARA MAÑANA
        echo "📅 Creando citas para MAÑANA...\n";
        $manana = Carbon::tomorrow();

        // Cita 1: MAÑANA 8:00 AM - Pendiente
        $cita_manana_1 = Cita::create([
            'usuario_id' => $cliente2->id,
            'vehiculo_id' => $vehiculo3->id,
            'fecha_hora' => $manana->copy()->setTime(8, 0, 0),
            'estado' => 'pendiente',
            'observaciones' => null,
        ]);
        $cita_manana_1->servicios()->attach([
            $servicio1->id => ['precio' => $servicio1->precio]
        ]);

        // Cita 2: MAÑANA 11:00 AM - Confirmada
        $cita_manana_2 = Cita::create([
            'usuario_id' => $cliente3->id,
            'vehiculo_id' => $vehiculo5->id,
            'fecha_hora' => $manana->copy()->setTime(11, 0, 0),
            'estado' => 'confirmada',
            'observaciones' => 'Camioneta requiere lavado completo',
        ]);
        $cita_manana_2->servicios()->attach([
            $servicio2->id => ['precio' => $servicio2->precio],
            $servicio3->id => ['precio' => $servicio3->precio],
            $servicio5->id => ['precio' => $servicio5->precio]
        ]);

        // Cita 3: MAÑANA 3:00 PM - Pendiente
        $cita_manana_3 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo1->id,
            'fecha_hora' => $manana->copy()->setTime(15, 0, 0),
            'estado' => 'pendiente',
            'observaciones' => null,
        ]);
        $cita_manana_3->servicios()->attach([
            $servicio4->id => ['precio' => $servicio4->precio]
        ]);

        echo "   ✅ 3 citas para mañana creadas (8:00, 11:00, 15:00)\n\n";

        // 7. CREAR CITAS PARA FECHA ESPECÍFICA (5 días adelante)
        echo "📅 Creando citas para fecha específica (+5 días)...\n";
        $fechaEspecifica = Carbon::now()->addDays(5);

        $cita_futura_1 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo2->id,
            'fecha_hora' => $fechaEspecifica->copy()->setTime(10, 0, 0),
            'estado' => 'confirmada',
            'observaciones' => null,
        ]);
        $cita_futura_1->servicios()->attach([
            $servicio2->id => ['precio' => $servicio2->precio]
        ]);

        $cita_futura_2 = Cita::create([
            'usuario_id' => $cliente3->id,
            'vehiculo_id' => $vehiculo4->id,
            'fecha_hora' => $fechaEspecifica->copy()->setTime(14, 0, 0),
            'estado' => 'pendiente',
            'observaciones' => null,
        ]);
        $cita_futura_2->servicios()->attach([
            $servicio1->id => ['precio' => $servicio1->precio],
            $servicio3->id => ['precio' => $servicio3->precio]
        ]);

        echo "   ✅ 2 citas futuras creadas para " . $fechaEspecifica->format('Y-m-d') . "\n\n";

        // 8. CREAR CITAS HISTÓRICAS (FINALIZADAS)
        echo "📜 Creando citas históricas (finalizadas)...\n";

        // Ayer
        $ayer = Carbon::yesterday();
        $cita_hist_1 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo1->id,
            'fecha_hora' => $ayer->copy()->setTime(9, 0, 0),
            'estado' => 'finalizada',
            'observaciones' => 'Servicio completado satisfactoriamente',
        ]);
        $cita_hist_1->servicios()->attach([
            $servicio1->id => ['precio' => $servicio1->precio]
        ]);

        $pago1 = Pago::create([
            'cita_id' => $cita_hist_1->id,
            'monto' => 30.00,
            'monto_recibido' => 30.00,
            'metodo' => 'efectivo',
            'estado' => 'pagado',
        ]);

        // Hace 3 días
        $hace3dias = Carbon::now()->subDays(3);
        $cita_hist_2 = Cita::create([
            'usuario_id' => $cliente2->id,
            'vehiculo_id' => $vehiculo3->id,
            'fecha_hora' => $hace3dias->copy()->setTime(11, 0, 0),
            'estado' => 'finalizada',
            'observaciones' => 'Cliente muy satisfecho con el servicio',
        ]);
        $cita_hist_2->servicios()->attach([
            $servicio2->id => ['precio' => $servicio2->precio],
            $servicio3->id => ['precio' => $servicio3->precio]
        ]);

        $pago2 = Pago::create([
            'cita_id' => $cita_hist_2->id,
            'monto' => 120.00,
            'monto_recibido' => 120.00,
            'metodo' => 'pasarela',
            'estado' => 'pagado',
        ]);

        // Hace 7 días
        $hace7dias = Carbon::now()->subDays(7);
        $cita_hist_3 = Cita::create([
            'usuario_id' => $cliente3->id,
            'vehiculo_id' => $vehiculo5->id,
            'fecha_hora' => $hace7dias->copy()->setTime(15, 0, 0),
            'estado' => 'finalizada',
            'observaciones' => 'Lavado de camioneta, excelente resultado',
        ]);
        $cita_hist_3->servicios()->attach([
            $servicio2->id => ['precio' => $servicio2->precio],
            $servicio4->id => ['precio' => $servicio4->precio],
            $servicio5->id => ['precio' => $servicio5->precio]
        ]);

        $pago3 = Pago::create([
            'cita_id' => $cita_hist_3->id,
            'monto' => 250.00,
            'monto_recibido' => 250.00,
            'metodo' => 'transferencia',
            'estado' => 'pagado',
        ]);

        // Hace 14 días
        $hace14dias = Carbon::now()->subDays(14);
        $cita_hist_4 = Cita::create([
            'usuario_id' => $cliente1->id,
            'vehiculo_id' => $vehiculo2->id,
            'fecha_hora' => $hace14dias->copy()->setTime(10, 0, 0),
            'estado' => 'finalizada',
            'observaciones' => 'Servicio estándar',
        ]);
        $cita_hist_4->servicios()->attach([
            $servicio1->id => ['precio' => $servicio1->precio],
            $servicio3->id => ['precio' => $servicio3->precio]
        ]);

        $pago4 = Pago::create([
            'cita_id' => $cita_hist_4->id,
            'monto' => 80.00,
            'monto_recibido' => 100.00,
            'vuelto' => 20.00,
            'metodo' => 'efectivo',
            'estado' => 'pagado',
        ]);

        // Hace 1 mes
        $hace1mes = Carbon::now()->subMonth();
        $cita_hist_5 = Cita::create([
            'usuario_id' => $cliente2->id,
            'vehiculo_id' => $vehiculo3->id,
            'fecha_hora' => $hace1mes->copy()->setTime(13, 0, 0),
            'estado' => 'finalizada',
            'observaciones' => 'Primera visita del cliente, todo bien',
        ]);
        $cita_hist_5->servicios()->attach([
            $servicio2->id => ['precio' => $servicio2->precio]
        ]);

        $pago5 = Pago::create([
            'cita_id' => $cita_hist_5->id,
            'monto' => 70.00,
            'monto_recibido' => 70.00,
            'metodo' => 'pasarela',
            'estado' => 'pagado',
        ]);

        echo "   ✅ 5 citas históricas finalizadas creadas con pagos\n\n";

        // 9. CREAR UNA CITA CANCELADA
        echo "❌ Creando cita cancelada...\n";
        $cita_cancelada = Cita::create([
            'usuario_id' => $cliente3->id,
            'vehiculo_id' => $vehiculo4->id,
            'fecha_hora' => $hace3dias->copy()->setTime(16, 0, 0),
            'estado' => 'cancelada',
            'observaciones' => 'Cliente canceló por motivos personales',
        ]);
        $cita_cancelada->servicios()->attach([
            $servicio1->id => ['precio' => $servicio1->precio]
        ]);

        echo "   ✅ 1 cita cancelada creada\n\n";

        // 10. CREAR DÍAS NO LABORABLES
        echo "🚫 Creando días no laborables...\n";

        // Día festivo futuro 1
        $futuro1 = Carbon::now()->addDays(10);
        DiaNoLaborable::create([
            'fecha' => $futuro1->format('Y-m-d'),
            'motivo' => 'Día de la Independencia',
        ]);

        // Día festivo futuro 2
        $futuro2 = Carbon::now()->addDays(20);
        DiaNoLaborable::create([
            'fecha' => $futuro2->format('Y-m-d'),
            'motivo' => 'Navidad',
        ]);

        // Mantenimiento
        $futuro3 = Carbon::now()->addDays(15);
        DiaNoLaborable::create([
            'fecha' => $futuro3->format('Y-m-d'),
            'motivo' => 'Mantenimiento general del local',
        ]);

        // Capacitación
        $futuro4 = Carbon::now()->addDays(30);
        DiaNoLaborable::create([
            'fecha' => $futuro4->format('Y-m-d'),
            'motivo' => 'Capacitación del personal',
        ]);

        // Otro motivo
        $futuro5 = Carbon::now()->addDays(45);
        DiaNoLaborable::create([
            'fecha' => $futuro5->format('Y-m-d'),
            'motivo' => 'Evento especial en el local',
        ]);

        echo "   ✅ 5 días no laborables creados\n\n";

        // RESUMEN FINAL
        echo "╔═══════════════════════════════════════════════════════════╗\n";
        echo "║              ✅ DATOS DE PRUEBA CREADOS                  ║\n";
        echo "╚═══════════════════════════════════════════════════════════╝\n\n";

        echo "👤 USUARIOS:\n";
        echo "   - 1 Administrador\n";
        echo "   - 2 Empleados\n";
        echo "   - 3 Clientes\n\n";

        echo "🚗 VEHÍCULOS: 5 vehículos\n\n";

        echo "🧼 SERVICIOS: 5 servicios activos\n\n";

        echo "📅 CITAS:\n";
        echo "   - HOY: 7 citas para pruebas de Gestión de Citas\n";
        echo "     · 3 CONFIRMADAS → listas para cambiar a EN_PROCESO\n";
        echo "     · 3 EN_PROCESO → listas para agregar observaciones/finalizar\n";
        echo "     · 1 PENDIENTE → para finalización simple\n";
        echo "   - MAÑANA: 3 citas (8:00, 11:00, 15:00)\n";
        echo "   - " . $fechaEspecifica->format('Y-m-d') . ": 2 citas\n";
        echo "   - HISTÓRICAS (finalizadas): 5 citas\n";
        echo "   - CANCELADAS: 1 cita\n";
        echo "   - TOTAL: " . Cita::count() . " citas\n\n";

        echo "💰 PAGOS: 5 pagos registrados\n\n";

        echo "🚫 DÍAS NO LABORABLES: 5 días\n\n";

        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🎯 CREDENCIALES DE PRUEBA:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "Admin:     admin@test.com / password123\n";
        echo "Empleado:  empleado@test.com / password123\n";
        echo "Cliente 1: juan.perez@test.com / password123\n";
        echo "Cliente 2: maria.garcia@test.com / password123\n";
        echo "Cliente 3: pedro.rodriguez@test.com / password123\n\n";

        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ ¡Listo para ejecutar pruebas manuales!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}
