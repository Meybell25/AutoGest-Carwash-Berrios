<?php

namespace Tests\Feature\Empleado;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Pago;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Tests para el módulo Gestión de Citas (Empleado)
 *
 * Casos de prueba cubiertos:
 * - CP-GC-01: Cambiar estado de confirmada a en_proceso
 * - CP-GC-02: Cambiar estado de en_proceso a finalizada
 * - CP-GC-03: Intentar transición inválida (debe fallar)
 * - CP-GC-04: Agregar observaciones en estado en_proceso
 * - CP-GC-05: Intentar agregar observaciones en estado incorrecto
 * - CP-GC-06: Finalizar cita con pago en efectivo
 * - CP-GC-07: Finalizar cita con pago por tarjeta
 * - CP-GC-08: Finalizar cita con pago por transferencia
 * - CP-GC-09: Finalizar cita simple (sin pago)
 * - CP-GC-10: Validar cálculo correcto de cambio
 * - CP-GC-11: Validar auditoría en bitácora
 *
 * @author Germán
 * @date 2025-10-24
 */
class GestionCitasTest extends TestCase
{
    use RefreshDatabase;

    protected $empleado;
    protected $cliente;
    protected $vehiculo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empleado = Usuario::factory()->create([
            'nombre' => 'Empleado Test',
            'email' => 'empleado@test.com',
            'rol' => 'empleado',
            'estado' => true,
        ]);

        $this->cliente = Usuario::factory()->create([
            'nombre' => 'Cliente Test',
            'email' => 'cliente@test.com',
            'rol' => 'cliente',
            'estado' => true,
        ]);

        $this->vehiculo = Vehiculo::factory()->create([
            'usuario_id' => $this->cliente->id,
        ]);
    }

    /**
     * CP-GC-01: Cambiar estado de confirmada a en_proceso
     *
     * @test
     * @group gestion-citas
     * @group empleado
     */
    public function test_cp_gc_01_cambiar_estado_confirmada_a_en_proceso()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_CONFIRMADA,
            'fecha_hora' => now()->addHour(),
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/cambiar-estado", [
                'estado' => Cita::ESTADO_EN_PROCESO,
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verificar en base de datos
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
        ]);

        // Verificar que se añadió observación automática
        $cita->refresh();
        // El controlador guarda el formato "En proceso" no "en_proceso"
        $this->assertStringContainsString('En proceso', $cita->observaciones);
        $this->assertStringContainsString($this->empleado->nombre, $cita->observaciones);
    }

    /**
     * CP-GC-02: Cambiar estado de en_proceso a finalizada
     *
     * @test
     * @group gestion-citas
     * @group empleado
     */
    public function test_cp_gc_02_cambiar_estado_en_proceso_a_finalizada()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
            'fecha_hora' => now()->addHour(),
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/cambiar-estado", [
                'estado' => Cita::ESTADO_FINALIZADA,
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado' => Cita::ESTADO_FINALIZADA,
        ]);
    }

    /**
     * CP-GC-03: Intentar transición inválida (debe fallar)
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group validacion
     */
    public function test_cp_gc_03_transicion_invalida_debe_fallar()
    {
        // Arrange: Cita en estado confirmada
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_CONFIRMADA,
            'fecha_hora' => now()->addHour(),
        ]);

        // Act: Intentar cambiar directamente a finalizada (saltando en_proceso)
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/cambiar-estado", [
                'estado' => Cita::ESTADO_FINALIZADA,
            ]);

        // Assert: Debe rechazarse
        $this->assertTrue(
            $response->status() === 422 || $response->status() === 400,
            "Se esperaba código 422 o 400, se recibió {$response->status()}"
        );

        // Verificar que el estado NO cambió
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado' => Cita::ESTADO_CONFIRMADA, // Debe seguir igual
        ]);
    }

    /**
     * CP-GC-04: Agregar observaciones en estado en_proceso
     *
     * @test
     * @group gestion-citas
     * @group empleado
     */
    public function test_cp_gc_04_agregar_observaciones_en_proceso()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
            'fecha_hora' => now()->addHour(),
            'observaciones' => 'Observación inicial',
        ]);

        $nuevaObservacion = 'Cliente solicitó lavado adicional de tapicería';

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/agregar-observaciones", [
                'observaciones' => $nuevaObservacion,
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verificar que la observación se añadió
        $cita->refresh();
        $this->assertStringContainsString($nuevaObservacion, $cita->observaciones);
        $this->assertStringContainsString('Observación inicial', $cita->observaciones);
    }

    /**
     * CP-GC-05: Intentar agregar observaciones en estado incorrecto
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group validacion
     */
    public function test_cp_gc_05_no_agregar_observaciones_en_estado_incorrecto()
    {
        // Arrange: Cita en estado confirmada (no en_proceso)
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_CONFIRMADA,
            'fecha_hora' => now()->addHour(),
            'observaciones' => 'Original',
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/agregar-observaciones", [
                'observaciones' => 'Esta no debería guardarse',
            ]);

        // Assert: Debe rechazarse
        $this->assertTrue(
            $response->status() === 422 || $response->status() === 403,
            "Se esperaba código 422 o 403, se recibió {$response->status()}"
        );

        // Verificar que las observaciones NO cambiaron
        $cita->refresh();
        $this->assertEquals('Original', $cita->observaciones);
    }

    /**
     * CP-GC-06: Finalizar cita con pago en efectivo
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group pagos
     */
    public function test_cp_gc_06_finalizar_cita_con_pago_efectivo()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
            'fecha_hora' => now(),
        ]);

        // Agregar servicios (total: $50.00)
        $servicio1 = Servicio::factory()->create(['precio' => 30.00]);
        $servicio2 = Servicio::factory()->create(['precio' => 20.00]);
        $cita->servicios()->attach($servicio1->id, ['precio' => 30.00]);
        $cita->servicios()->attach($servicio2->id, ['precio' => 20.00]);

        // Act: Finalizar con efectivo, cliente paga con $100
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/finalizar-completa", [
                'metodo_pago' => 'efectivo',
                'monto_recibido' => 100.00,
                'observaciones_finalizacion' => 'Servicio completado',
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'pago' => [
                'total' => 50.00,
                'metodo_pago' => 'efectivo',
                'cambio' => 50.00, // 100 - 50
            ]
        ]);

        // Verificar cita finalizada
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado' => Cita::ESTADO_FINALIZADA,
        ]);

        // Verificar creación de pago (usando columnas reales de la tabla)
        $this->assertDatabaseHas('pagos', [
            'cita_id' => $cita->id,
            'metodo' => 'efectivo', // La columna es 'metodo' no 'metodo_pago'
            'monto' => 50.00,
            'estado' => 'pagado', // El estado es 'pagado' no 'completado'
        ]);
    }

    /**
     * CP-GC-07: Finalizar cita con pago por tarjeta
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group pagos
     */
    public function test_cp_gc_07_finalizar_cita_con_pago_tarjeta()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
            'fecha_hora' => now(),
        ]);

        $servicio = Servicio::factory()->create(['precio' => 75.50]);
        $cita->servicios()->attach($servicio->id, ['precio' => 75.50]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/finalizar-completa", [
                'metodo_pago' => 'tarjeta',
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'pago' => [
                'total' => 75.50,
                'metodo_pago' => 'tarjeta',
                'cambio' => 0
            ]
        ]);

        $this->assertDatabaseHas('pagos', [
            'cita_id' => $cita->id,
            'metodo' => 'tarjeta', // La columna es 'metodo' no 'metodo_pago'
            'monto' => 75.50,
        ]);
    }

    /**
     * CP-GC-08: Finalizar cita con pago por transferencia
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group pagos
     */
    public function test_cp_gc_08_finalizar_cita_con_pago_transferencia()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
            'fecha_hora' => now(),
        ]);

        $servicio = Servicio::factory()->create(['precio' => 100.00]);
        $cita->servicios()->attach($servicio->id, ['precio' => 100.00]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/finalizar-completa", [
                'metodo_pago' => 'transferencia',
            ]);

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('pagos', [
            'cita_id' => $cita->id,
            'metodo' => 'transferencia', // La columna es 'metodo' no 'metodo_pago'
            'monto' => 100.00,
        ]);
    }

    /**
     * CP-GC-09: Finalizar cita simple (sin pago)
     *
     * @test
     * @group gestion-citas
     * @group empleado
     */
    public function test_cp_gc_09_finalizar_cita_simple()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_PENDIENTE,
            'fecha_hora' => now(),
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson('/empleado/citas/finalizar-simple', [
                'cita_id' => $cita->id,
            ]);

        // Assert
        $response->assertStatus(200);

        // Verificar que la cita está finalizada
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado' => Cita::ESTADO_FINALIZADA,
        ]);

        // Verificar que NO se creó registro de pago
        $this->assertDatabaseMissing('pagos', [
            'cita_id' => $cita->id,
        ]);
    }

    /**
     * CP-GC-10: Validar cálculo correcto de cambio
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group pagos
     * @group calculos
     */
    public function test_cp_gc_10_calculo_correcto_de_cambio()
    {
        // Arrange: Crear 3 citas con diferentes totales
        $casos = [
            ['total' => 25.00, 'recibido' => 30.00, 'cambio_esperado' => 5.00],
            ['total' => 47.75, 'recibido' => 50.00, 'cambio_esperado' => 2.25],
            ['total' => 100.00, 'recibido' => 100.00, 'cambio_esperado' => 0.00],
        ];

        foreach ($casos as $index => $caso) {
            // Crear cita con fecha_hora única para evitar constraint violation
            $cita = Cita::factory()->create([
                'usuario_id' => $this->cliente->id,
                'vehiculo_id' => $this->vehiculo->id,
                'estado' => Cita::ESTADO_EN_PROCESO,
                'fecha_hora' => now()->addMinutes($index * 15), // Cada cita 15 minutos después
            ]);

            // Agregar servicio con el precio total
            $servicio = Servicio::factory()->create(['precio' => $caso['total']]);
            $cita->servicios()->attach($servicio->id, ['precio' => $caso['total']]);

            // Act
            $response = $this->actingAs($this->empleado)
                ->postJson("/empleado/citas/{$cita->id}/finalizar-completa", [
                    'metodo_pago' => 'efectivo',
                    'monto_recibido' => $caso['recibido'],
                ]);

            // Assert
            $response->assertStatus(200);
            $cambioRecibido = $response->json('pago.cambio');

            $this->assertEquals(
                $caso['cambio_esperado'],
                $cambioRecibido,
                "Error en cálculo: Total {$caso['total']}, Recibido {$caso['recibido']}, " .
                "Esperado {$caso['cambio_esperado']}, Recibido {$cambioRecibido}"
            );
        }
    }

    /**
     * CP-GC-11: Validar auditoría en bitácora
     *
     * @test
     * @group gestion-citas
     * @group empleado
     * @group auditoria
     */
    public function test_cp_gc_11_auditoria_en_bitacora()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_CONFIRMADA,
            'fecha_hora' => now(),
        ]);

        // Act: Cambiar estado
        $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/cambiar-estado", [
                'estado' => Cita::ESTADO_EN_PROCESO,
            ]);

        // Assert: Verificar registro en bitácora
        $this->assertDatabaseHas('bitacora', [
            'usuario_id' => $this->empleado->id,
            'accion' => 'cambiar_estado_cita',
            'tabla_afectada' => 'citas',
            'registro_id' => $cita->id,
        ]);

        // Verificar que el detalle contiene información relevante
        $bitacora = \DB::table('bitacora')
            ->where('registro_id', $cita->id)
            ->where('accion', 'cambiar_estado_cita')
            ->first();

        $this->assertNotNull($bitacora);
        $this->assertStringContainsString('confirmada', strtolower($bitacora->detalles));
        $this->assertStringContainsString('en_proceso', strtolower($bitacora->detalles));
    }

    /**
     * Test adicional: Validar máximo de caracteres en observaciones
     *
     * @test
     * @group gestion-citas
     * @group validacion
     */
    public function test_validar_maximo_caracteres_observaciones()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_EN_PROCESO,
            'fecha_hora' => now(),
        ]);

        $observacionLarga = str_repeat('A', 1001); // 1001 caracteres (excede el límite de 1000)

        // Act
        $response = $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/agregar-observaciones", [
                'observaciones' => $observacionLarga,
            ]);

        // Assert: Debe rechazarse
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('observaciones');
    }

    /**
     * Test adicional: Verificar que el timestamp se añade a observaciones
     *
     * @test
     * @group gestion-citas
     */
    public function test_timestamp_se_anade_a_observaciones()
    {
        // Arrange
        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id,
            'estado' => Cita::ESTADO_CONFIRMADA,
            'fecha_hora' => now(),
        ]);

        // Act
        $this->actingAs($this->empleado)
            ->postJson("/empleado/citas/{$cita->id}/cambiar-estado", [
                'estado' => Cita::ESTADO_EN_PROCESO,
            ]);

        // Assert
        $cita->refresh();

        // Verificar que contiene fecha actual en formato [DD/MM/YYYY HH:MM]
        $fechaHoy = now()->format('d/m/Y');
        $this->assertStringContainsString($fechaHoy, $cita->observaciones);

        // Verificar que contiene el nombre del empleado
        $this->assertStringContainsString($this->empleado->nombre, $cita->observaciones);
    }
}
