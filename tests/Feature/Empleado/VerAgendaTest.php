<?php

namespace Tests\Feature\Empleado;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Tests para el módulo Ver Agenda (Empleado)
 *
 * Casos de prueba cubiertos:
 * - CP-AG-01: Filtrar citas de hoy
 * - CP-AG-02: Filtrar citas de mañana
 * - CP-AG-03: Filtrar citas por fecha específica
 * - CP-AG-04: Ver detalles de una cita
 * - CP-AG-05: Validar que solo se muestran citas pendientes
 * - CP-AG-06: Verificar ordenamiento cronológico
 * - CP-AG-07: Respuesta AJAX para actualizaciones
 *
 * @author Germán
 * @date 2025-10-24
 */
class VerAgendaTest extends TestCase
{
    use RefreshDatabase;

    protected $empleado;
    protected $cliente;

    /**
     * Configuración inicial para cada test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario empleado
        $this->empleado = Usuario::factory()->create([
            'nombre' => 'Empleado Test',
            'email' => 'empleado@test.com',
            'password' => bcrypt('password123'),
            'rol' => 'empleado',
            'estado' => 'activo',
        ]);

        // Crear usuario cliente
        $this->cliente = Usuario::factory()->create([
            'nombre' => 'Cliente Test',
            'email' => 'cliente@test.com',
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);
    }

    /**
     * CP-AG-01: Filtrar citas de hoy
     *
     * @test
     * @group agenda
     * @group empleado
     */
    public function test_cp_ag_01_filtrar_citas_de_hoy()
    {
        // Arrange: Crear vehículo y citas
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);

        // Citas de hoy (deben aparecer)
        $citaHoy1 = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(9, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $citaHoy2 = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(14, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Citas de otro día (no deben aparecer)
        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->addDays(1),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Act: Hacer request como empleado
        $response = $this->actingAs($this->empleado)
            ->get('/empleado/citas?filtro=hoy');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('citas');

        $citas = $response->viewData('citas');

        // Verificar que solo hay 2 citas
        $this->assertCount(2, $citas);

        // Verificar que son las citas de hoy
        $this->assertTrue($citas->contains('id', $citaHoy1->id));
        $this->assertTrue($citas->contains('id', $citaHoy2->id));
    }

    /**
     * CP-AG-02: Filtrar citas de mañana
     *
     * @test
     * @group agenda
     * @group empleado
     */
    public function test_cp_ag_02_filtrar_citas_de_manana()
    {
        // Arrange
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);

        // Cita de mañana
        $citaManana = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->addDay()->setTime(10, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Cita de hoy (no debe aparecer)
        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(10, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->get('/empleado/citas?filtro=manana');

        // Assert
        $response->assertStatus(200);
        $citas = $response->viewData('citas');

        $this->assertCount(1, $citas);
        $this->assertEquals($citaManana->id, $citas->first()->id);
    }

    /**
     * CP-AG-03: Filtrar citas por fecha específica
     *
     * @test
     * @group agenda
     * @group empleado
     */
    public function test_cp_ag_03_filtrar_citas_por_fecha_especifica()
    {
        // Arrange
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);
        $fechaEspecifica = now()->addDays(5)->format('Y-m-d');

        $citaFechaEspecifica = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => $fechaEspecifica . ' 10:00:00',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Otras citas que no deben aparecer
        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now(),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->get("/empleado/citas?filtro=fecha&fecha={$fechaEspecifica}");

        // Assert
        $response->assertStatus(200);
        $citas = $response->viewData('citas');

        $this->assertCount(1, $citas);
        $this->assertEquals($citaFechaEspecifica->id, $citas->first()->id);
    }

    /**
     * CP-AG-04: Ver detalles de una cita
     *
     * @test
     * @group agenda
     * @group empleado
     */
    public function test_cp_ag_04_ver_detalles_de_cita()
    {
        // Arrange
        $vehiculo = Vehiculo::factory()->create([
            'usuario_id' => $this->cliente->id,
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'placa' => 'ABC-123',
        ]);

        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->addHour(),
            'estado' => Cita::ESTADO_PENDIENTE,
            'observaciones' => 'Cliente requiere lavado completo',
        ]);

        // Agregar servicios a la cita
        $servicio1 = Servicio::factory()->create(['nombre' => 'Lavado Básico', 'precio' => 15.00]);
        $servicio2 = Servicio::factory()->create(['nombre' => 'Encerado', 'precio' => 10.00]);

        $cita->servicios()->attach($servicio1->id, ['precio' => 15.00]);
        $cita->servicios()->attach($servicio2->id, ['precio' => 10.00]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->get("/empleado/citas/{$cita->id}/detalles");

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'cita' => [
                'usuario' => [
                    'nombre' => $this->cliente->nombre,
                    'email' => $this->cliente->email,
                ],
                'vehiculo' => [
                    'marca' => 'Toyota',
                    'modelo' => 'Corolla',
                    'placa' => 'ABC-123',
                ],
            ]
        ]);

        // Verificar que incluye servicios
        $json = $response->json();
        $this->assertArrayHasKey('servicios', $json['cita']);
        $this->assertCount(2, $json['cita']['servicios']);

        // Verificar total
        $this->assertEquals(25.00, $json['cita']['total']);
    }

    /**
     * CP-AG-05: Validar que solo se muestran citas pendientes
     *
     * @test
     * @group agenda
     * @group empleado
     */
    public function test_cp_ag_05_solo_muestra_citas_pendientes()
    {
        // Arrange
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);

        // Crear citas con diferentes estados
        $citaPendiente = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(9, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(10, 0),
            'estado' => Cita::ESTADO_CONFIRMADA,
        ]);

        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(11, 0),
            'estado' => Cita::ESTADO_EN_PROCESO,
        ]);

        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(12, 0),
            'estado' => Cita::ESTADO_FINALIZADA,
        ]);

        Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(13, 0),
            'estado' => Cita::ESTADO_CANCELADA,
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->get('/empleado/citas?filtro=hoy');

        // Assert
        $response->assertStatus(200);
        $citas = $response->viewData('citas');

        // Solo debe haber 1 cita (la pendiente)
        $this->assertCount(1, $citas);

        // Verificar que es la cita pendiente
        $this->assertEquals($citaPendiente->id, $citas->first()->id);
        $this->assertEquals(Cita::ESTADO_PENDIENTE, $citas->first()->estado);
    }

    /**
     * CP-AG-06: Verificar ordenamiento cronológico
     *
     * @test
     * @group agenda
     * @group empleado
     */
    public function test_cp_ag_06_ordenamiento_cronologico_ascendente()
    {
        // Arrange
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);

        // Crear citas en desorden
        $cita14 = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(14, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $cita9 = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(9, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $cita16 = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(16, 30),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $cita11 = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(11, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Act
        $response = $this->actingAs($this->empleado)
            ->get('/empleado/citas?filtro=hoy');

        // Assert
        $response->assertStatus(200);
        $citas = $response->viewData('citas');

        $this->assertCount(4, $citas);

        // Verificar orden: 9:00, 11:00, 14:00, 16:30
        // Los paginadores tienen la data en el array 'data'
        $citasArray = $citas->toArray()['data'];
        $this->assertEquals($cita9->id, $citasArray[0]['id']);
        $this->assertEquals($cita11->id, $citasArray[1]['id']);
        $this->assertEquals($cita14->id, $citasArray[2]['id']);
        $this->assertEquals($cita16->id, $citasArray[3]['id']);
    }

    /**
     * CP-AG-07: Respuesta AJAX para actualizaciones
     *
     * @test
     * @group agenda
     * @group empleado
     * @group ajax
     */
    public function test_cp_ag_07_respuesta_ajax()
    {
        // Arrange
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);

        $cita = Cita::factory()->create([
            'usuario_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'fecha_hora' => now()->setTime(10, 0),
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        // Act: Hacer request AJAX
        $response = $this->actingAs($this->empleado)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/empleado/citas?filtro=hoy');

        // Assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');

        // Verificar estructura JSON paginada
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'fecha_hora',
                    'estado',
                    'usuario',
                    'vehiculo',
                    'servicios',
                ]
            ],
            'per_page',
            'total',
        ]);
    }

    /**
     * Test: Empleado sin autenticar no puede acceder
     *
     * @test
     * @group agenda
     * @group auth
     */
    public function test_empleado_sin_autenticar_no_puede_acceder()
    {
        // Act
        $response = $this->get('/empleado/citas');

        // Assert
        $response->assertRedirect('/login');
    }

    /**
     * Test: Cliente no puede acceder a agenda de empleado
     *
     * @test
     * @group agenda
     * @group auth
     */
    public function test_cliente_no_puede_acceder_a_agenda_empleado()
    {
        // Act
        $response = $this->actingAs($this->cliente)
            ->get('/empleado/citas');

        // Assert: Debe ser redirigido o recibir 403
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 302
        );
    }

    /**
     * Test: Tiempo de respuesta aceptable
     *
     * @test
     * @group agenda
     * @group performance
     */
    public function test_tiempo_de_respuesta_aceptable()
    {
        // Arrange: Crear múltiples citas
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $this->cliente->id]);

        // Crear 20 citas con diferentes horarios para evitar conflicto de unique constraint
        for ($i = 0; $i < 20; $i++) {
            Cita::factory()->create([
                'usuario_id' => $this->cliente->id,
                'vehiculo_id' => $vehiculo->id,
                'fecha_hora' => now()->setTime(8 + $i, $i * 2, $i),
                'estado' => Cita::ESTADO_PENDIENTE,
            ]);
        }

        // Act: Medir tiempo
        $startTime = microtime(true);

        $response = $this->actingAs($this->empleado)
            ->get('/empleado/citas?filtro=hoy');

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // en milisegundos

        // Assert
        $response->assertStatus(200);

        // El tiempo debe ser menor a 3000ms (3 segundos)
        $this->assertLessThan(3000, $executionTime,
            "El tiempo de respuesta ({$executionTime}ms) excede el límite de 3000ms");
    }
}
