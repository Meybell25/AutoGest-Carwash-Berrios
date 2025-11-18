<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\DiaNoLaborable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Tests para el módulo Días No Laborables (Admin)
 *
 * Casos de prueba cubiertos:
 * - CP-DNL-01: Crear día no laborable
 * - CP-DNL-02: Intentar crear día con fecha duplicada
 * - CP-DNL-03: Intentar crear día con fecha pasada
 * - CP-DNL-04: Actualizar motivo de día no laborable
 * - CP-DNL-05: Eliminar día no laborable
 * - CP-DNL-06: Listar días con paginación
 * - CP-DNL-07: Consultar próximos días no laborables
 * - CP-DNL-08: Consultar días del mes
 * - CP-DNL-09: Calcular días laborables en rango
 * - CP-DNL-10: Verificar motivos disponibles
 * - CP-DNL-11: Validar restricción de acceso solo admin
 * - CP-DNL-12: Validar formato de fecha
 *
 * @author Germán
 * @date 2025-10-24
 */
class DiasNoLaborablesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $empleado;
    protected $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Usuario::factory()->create([
            'nombre' => 'Admin Test',
            'email' => 'admin@test.com',
            'rol' => 'admin',
            'estado' => 'activo',
        ]);

        $this->empleado = Usuario::factory()->create([
            'nombre' => 'Empleado Test',
            'email' => 'empleado@test.com',
            'rol' => 'empleado',
            'estado' => 'activo',
        ]);

        $this->cliente = Usuario::factory()->create([
            'nombre' => 'Cliente Test',
            'email' => 'cliente@test.com',
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);
    }

    /**
     * CP-DNL-01: Crear día no laborable
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_01_crear_dia_no_laborable()
    {
        // Arrange
        $fechaFutura = now()->addDays(10)->format('Y-m-d');

        // Act
        $response = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => $fechaFutura,
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        // Assert
        $response->assertStatus(201);

        $this->assertDatabaseHas('dias_no_laborables', [
            'fecha' => $fechaFutura,
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Verificar auditoría en bitácora
        $this->assertDatabaseHas('bitacora', [
            'usuario_id' => $this->admin->id,
            'accion' => 'crear_dia_no_laborable',
            'tabla_afectada' => 'dias_no_laborables',
        ]);
    }

    /**
     * CP-DNL-02: Intentar crear día con fecha duplicada
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     * @group validacion
     */
    public function test_cp_dnl_02_rechazar_fecha_duplicada()
    {
        // Arrange: Crear día no laborable existente
        $fecha = now()->addDays(5)->format('Y-m-d');
        DiaNoLaborable::create([
            'fecha' => $fecha,
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Act: Intentar crear otro con la misma fecha
        $response = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => $fecha,
                'motivo' => DiaNoLaborable::MOTIVO_MANTENIMIENTO,
            ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('fecha');

        // Verificar que solo existe 1 registro
        $this->assertEquals(1, DiaNoLaborable::where('fecha', $fecha)->count());
    }

    /**
     * CP-DNL-03: Intentar crear día con fecha pasada
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     * @group validacion
     */
    public function test_cp_dnl_03_rechazar_fecha_pasada()
    {
        // Arrange
        $fechaPasada = now()->subDays(5)->format('Y-m-d');

        // Act
        $response = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => $fechaPasada,
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('fecha');

        // Verificar que no se creó
        $this->assertDatabaseMissing('dias_no_laborables', [
            'fecha' => $fechaPasada,
        ]);
    }

    /**
     * CP-DNL-04: Actualizar motivo de día no laborable
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_04_actualizar_motivo()
    {
        // Arrange
        $dia = DiaNoLaborable::create([
            'fecha' => now()->addDays(10)->format('Y-m-d'),
            'motivo' => DiaNoLaborable::MOTIVO_MANTENIMIENTO,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->putJson("/admin/dias-no-laborables/{$dia->id}", [
                'fecha' => $dia->fecha,
                'motivo' => DiaNoLaborable::MOTIVO_VACACIONES,
            ]);

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('dias_no_laborables', [
            'id' => $dia->id,
            'motivo' => DiaNoLaborable::MOTIVO_VACACIONES,
        ]);

        // Verificar auditoría
        $this->assertDatabaseHas('bitacora', [
            'usuario_id' => $this->admin->id,
            'accion' => 'actualizar_dia_no_laborable',
            'registro_id' => $dia->id,
        ]);
    }

    /**
     * CP-DNL-05: Eliminar día no laborable
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_05_eliminar_dia_no_laborable()
    {
        // Arrange
        $dia = DiaNoLaborable::create([
            'fecha' => now()->addDays(15)->format('Y-m-d'),
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->deleteJson("/admin/dias-no-laborables/{$dia->id}");

        // Assert
        $response->assertStatus(200);

        // Verificar que fue eliminado
        $this->assertDatabaseMissing('dias_no_laborables', [
            'id' => $dia->id,
        ]);

        // Verificar auditoría
        $this->assertDatabaseHas('bitacora', [
            'usuario_id' => $this->admin->id,
            'accion' => 'eliminar_dia_no_laborable',
            'registro_id' => $dia->id,
        ]);
    }

    /**
     * CP-DNL-06: Listar días con paginación
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_06_listar_con_paginacion()
    {
        // Arrange: Crear 25 días no laborables
        for ($i = 1; $i <= 25; $i++) {
            DiaNoLaborable::create([
                'fecha' => now()->addDays($i)->format('Y-m-d'),
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);
        }

        // Act: Obtener primera página
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables?page=1');

        // Assert
        $response->assertStatus(200);

        $data = $response->json();

        // Verificar paginación de 10 elementos
        $this->assertCount(10, $data['data']);
        $this->assertEquals(25, $data['total']);
        $this->assertEquals(1, $data['current_page']);

        // Act: Obtener segunda página
        $response2 = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables?page=2');

        $data2 = $response2->json();
        $this->assertCount(10, $data2['data']);

        // Act: Obtener tercera página
        $response3 = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables?page=3');

        $data3 = $response3->json();
        $this->assertCount(5, $data3['data']);
    }

    /**
     * CP-DNL-07: Consultar próximos días no laborables
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_07_proximos_dias_no_laborables()
    {
        // Arrange: Crear días no laborables futuros
        for ($i = 1; $i <= 8; $i++) {
            DiaNoLaborable::create([
                'fecha' => now()->addDays($i)->format('Y-m-d'),
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);
        }

        // Crear día pasado (no debe aparecer)
        DiaNoLaborable::create([
            'fecha' => now()->subDays(1)->format('Y-m-d'),
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables/proximos');

        // Assert
        $response->assertStatus(200);

        $dias = $response->json();

        // Debe retornar máximo 5 días
        $this->assertCount(5, $dias);

        // Todos deben ser fechas futuras
        foreach ($dias as $dia) {
            $this->assertGreaterThanOrEqual(now()->format('Y-m-d'), $dia['fecha']);
        }

        // Verificar que están ordenados por fecha ascendente
        $fechas = array_column($dias, 'fecha');
        $fechasOrdenadas = $fechas;
        sort($fechasOrdenadas);
        $this->assertEquals($fechasOrdenadas, $fechas);
    }

    /**
     * CP-DNL-08: Consultar días del mes
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_08_dias_del_mes()
    {
        // Arrange: Crear días en noviembre 2025
        DiaNoLaborable::create([
            'fecha' => '2025-11-01',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        DiaNoLaborable::create([
            'fecha' => '2025-11-15',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        DiaNoLaborable::create([
            'fecha' => '2025-11-30',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Crear día en otro mes (no debe aparecer)
        DiaNoLaborable::create([
            'fecha' => '2025-10-20',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables/del-mes?mes=11&anio=2025');

        // Assert
        $response->assertStatus(200);

        $dias = $response->json();

        $this->assertCount(3, $dias);

        // Verificar que todos son de noviembre 2025
        foreach ($dias as $dia) {
            $this->assertStringStartsWith('2025-11-', $dia['fecha']);
        }
    }

    /**
     * CP-DNL-09: Calcular días laborables en rango
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_09_calcular_dias_laborables()
    {
        // Arrange: Crear día no laborable en el rango
        DiaNoLaborable::create([
            'fecha' => now()->addDays(5)->format('Y-m-d'),
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        $inicio = now()->format('Y-m-d');
        $fin = now()->addDays(10)->format('Y-m-d');

        // Act
        $response = $this->actingAs($this->admin)
            ->getJson("/admin/dias-no-laborables/laborables?inicio={$inicio}&fin={$fin}");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'dias_laborables',
            'cantidad',
        ]);

        $diasLaborables = $response->json('dias_laborables');

        // No debe incluir el día no laborable
        $this->assertNotContains(now()->addDays(5)->format('Y-m-d'), $diasLaborables);

        // No debe incluir domingos
        foreach ($diasLaborables as $fecha) {
            $diaSemana = date('w', strtotime($fecha));
            $this->assertNotEquals(0, $diaSemana, "El día {$fecha} es domingo y no debería estar incluido");
        }
    }

    /**
     * CP-DNL-10: Verificar motivos disponibles
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     */
    public function test_cp_dnl_10_motivos_disponibles()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables/motivos');

        // Assert
        $response->assertStatus(200);

        $motivos = $response->json();

        // Verificar que contiene todos los motivos definidos
        $motivosEsperados = [
            DiaNoLaborable::MOTIVO_FERIADO,
            DiaNoLaborable::MOTIVO_MANTENIMIENTO,
            DiaNoLaborable::MOTIVO_VACACIONES,
            DiaNoLaborable::MOTIVO_EMERGENCIA,
            DiaNoLaborable::MOTIVO_EVENTO_ESPECIAL,
            DiaNoLaborable::MOTIVO_OTRO,
        ];

        foreach ($motivosEsperados as $motivo) {
            $this->assertContains($motivo, $motivos);
        }

        $this->assertCount(6, $motivos);
    }

    /**
     * CP-DNL-11: Validar restricción de acceso solo admin
     *
     * @test
     * @group dias-no-laborables
     * @group auth
     */
    public function test_cp_dnl_11_solo_admin_puede_acceder()
    {
        $fechaFutura = now()->addDays(10)->format('Y-m-d');

        // Test 1: Empleado no puede acceder
        $response1 = $this->actingAs($this->empleado)
            ->getJson('/admin/dias-no-laborables');

        $this->assertTrue(
            $response1->status() === 403 || $response1->status() === 302,
            "Empleado no debería poder acceder (código: {$response1->status()})"
        );

        // Test 2: Cliente no puede acceder
        $response2 = $this->actingAs($this->cliente)
            ->getJson('/admin/dias-no-laborables');

        $this->assertTrue(
            $response2->status() === 403 || $response2->status() === 302,
            "Cliente no debería poder acceder (código: {$response2->status()})"
        );

        // Test 3: Empleado no puede crear
        $response3 = $this->actingAs($this->empleado)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => $fechaFutura,
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        $this->assertTrue(
            $response3->status() === 403 || $response3->status() === 302,
            "Empleado no debería poder crear (código: {$response3->status()})"
        );

        // Test 4: Admin SÍ puede acceder
        $response4 = $this->actingAs($this->admin)
            ->getJson('/admin/dias-no-laborables');

        $response4->assertStatus(200);
    }

    /**
     * CP-DNL-12: Validar formato de fecha
     *
     * @test
     * @group dias-no-laborables
     * @group admin
     * @group validacion
     */
    public function test_cp_dnl_12_validar_formato_fecha()
    {
        // Test 1: Fecha inválida (mes 13)
        $response1 = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => '2025-13-40',
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        $response1->assertStatus(422);
        $response1->assertJsonValidationErrors('fecha');

        // Test 2: Formato incorrecto
        $response2 = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => '40/13/2025',
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors('fecha');

        // Test 3: Texto en lugar de fecha
        $response3 = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => 'mañana',
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        $response3->assertStatus(422);
        $response3->assertJsonValidationErrors('fecha');

        // Test 4: Fecha válida debe funcionar
        $fechaValida = now()->addDays(10)->format('Y-m-d');
        $response4 = $this->actingAs($this->admin)
            ->postJson('/admin/dias-no-laborables', [
                'fecha' => $fechaValida,
                'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
            ]);

        $response4->assertStatus(201);
    }

    /**
     * Test adicional: Verificar método estático esNoLaborable()
     *
     * @test
     * @group dias-no-laborables
     */
    public function test_metodo_es_no_laborable()
    {
        // Arrange
        $fechaNoLaborable = now()->addDays(7)->format('Y-m-d');
        $fechaLaborable = now()->addDays(8)->format('Y-m-d');

        DiaNoLaborable::create([
            'fecha' => $fechaNoLaborable,
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Act & Assert
        $this->assertTrue(DiaNoLaborable::esNoLaborable($fechaNoLaborable));
        $this->assertFalse(DiaNoLaborable::esNoLaborable($fechaLaborable));
    }

    /**
     * Test adicional: Sin autenticar no puede acceder
     *
     * @test
     * @group dias-no-laborables
     * @group auth
     */
    public function test_sin_autenticar_no_puede_acceder()
    {
        // Act
        $response = $this->getJson('/admin/dias-no-laborables');

        // Assert
        $response->assertStatus(401);
    }

    /**
     * Test adicional: Verificar accesores del modelo
     *
     * @test
     * @group dias-no-laborables
     * @group model
     */
    public function test_accesores_del_modelo()
    {
        // Arrange
        $dia = DiaNoLaborable::create([
            'fecha' => now()->addDays(10)->format('Y-m-d'),
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);

        // Act & Assert
        $this->assertNotNull($dia->fecha_formateada); // d/m/Y
        $this->assertNotNull($dia->fecha_completa);
        $this->assertIsInt($dia->dias_restantes);
        $this->assertIsBool($dia->es_futuro);
        $this->assertIsBool($dia->es_pasado);
        $this->assertIsBool($dia->es_hoy);
    }
}
