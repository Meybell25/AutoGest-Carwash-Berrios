<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\Cita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Pago
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
{
    protected $model = Pago::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        $monto = $this->faker->randomFloat(2, 15, 100);

        return [
            'cita_id' => Cita::factory(),
            'monto' => $monto,
            'monto_recibido' => $monto,
            'vuelto' => 0,
            'metodo' => $this->faker->randomElement([
                Pago::METODO_EFECTIVO,
                Pago::METODO_TRANSFERENCIA,
                Pago::METODO_PASARELA,
            ]),
            'referencia' => $this->faker->optional()->numerify('REF-########'),
            'estado' => Pago::ESTADO_PAGADO,
            'pasarela_id' => null,
            'fecha_pago' => now(),
            'observaciones' => $this->faker->optional()->sentence(),
            'detalles_adicionales' => [],
        ];
    }

    /**
     * Estado: Pagado
     */
    public function pagado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Pago::ESTADO_PAGADO,
            'fecha_pago' => now(),
        ]);
    }

    /**
     * Estado: Pendiente
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Pago::ESTADO_PENDIENTE,
            'fecha_pago' => null,
        ]);
    }

    /**
     * Estado: Rechazado
     */
    public function rechazado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Pago::ESTADO_RECHAZADO,
            'fecha_pago' => null,
            'observaciones' => 'Pago rechazado por el banco',
        ]);
    }

    /**
     * Método: Efectivo
     */
    public function efectivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'metodo' => Pago::METODO_EFECTIVO,
            'referencia' => null,
            'pasarela_id' => null,
        ]);
    }

    /**
     * Método: Efectivo con cambio
     */
    public function efectivoConCambio(float $montoRecibido = null): static
    {
        return $this->state(function (array $attributes) use ($montoRecibido) {
            $monto = $attributes['monto'];
            $recibido = $montoRecibido ?? ($monto + $this->faker->randomFloat(2, 5, 50));
            $vuelto = $recibido - $monto;

            return [
                'metodo' => Pago::METODO_EFECTIVO,
                'monto_recibido' => $recibido,
                'vuelto' => $vuelto,
                'referencia' => null,
                'pasarela_id' => null,
            ];
        });
    }

    /**
     * Método: Transferencia
     */
    public function transferencia(): static
    {
        return $this->state(fn (array $attributes) => [
            'metodo' => Pago::METODO_TRANSFERENCIA,
            'monto_recibido' => $attributes['monto'],
            'vuelto' => 0,
            'referencia' => 'TRANS-' . $this->faker->numerify('########'),
            'pasarela_id' => null,
            'detalles_adicionales' => [
                'banco_emisor' => $this->faker->randomElement(['Banco Agrícola', 'Banco Cuscatlán', 'Scotiabank', 'BAC']),
                'numero_referencia' => $this->faker->numerify('########'),
            ],
        ]);
    }

    /**
     * Método: Pasarela (Tarjeta)
     */
    public function pasarela(): static
    {
        return $this->state(fn (array $attributes) => [
            'metodo' => Pago::METODO_PASARELA,
            'monto_recibido' => $attributes['monto'],
            'vuelto' => 0,
            'referencia' => 'CARD-' . $this->faker->numerify('########'),
            'pasarela_id' => 'txn_' . $this->faker->uuid(),
            'detalles_adicionales' => [
                'tipo_tarjeta' => $this->faker->randomElement(['Visa', 'Mastercard', 'American Express']),
                'ultimos_digitos' => $this->faker->numerify('####'),
                'nombre_titular' => $this->faker->name(),
            ],
        ]);
    }

    /**
     * Con monto específico
     */
    public function monto(float $monto): static
    {
        return $this->state(fn (array $attributes) => [
            'monto' => $monto,
            'monto_recibido' => $monto,
        ]);
    }

    /**
     * Para una cita específica
     */
    public function paraCita(int $citaId): static
    {
        return $this->state(fn (array $attributes) => [
            'cita_id' => $citaId,
        ]);
    }

    /**
     * Con fecha específica
     */
    public function fechaPago($fecha): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_pago' => $fecha,
        ]);
    }

    /**
     * Pago de hoy
     */
    public function hoy(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_pago' => now(),
        ]);
    }

    /**
     * Pago del mes actual
     */
    public function esteMes(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_pago' => now()->subDays($this->faker->numberBetween(0, 28)),
        ]);
    }

    /**
     * Con descuentos aplicados
     */
    public function conDescuentos(array $descuentos): static
    {
        return $this->state(function (array $attributes) use ($descuentos) {
            $totalDescuentos = array_sum(array_column($descuentos, 'monto'));
            $totalAntes = $attributes['monto'] + $totalDescuentos;

            return [
                'detalles_adicionales' => array_merge($attributes['detalles_adicionales'] ?? [], [
                    'descuentos_aplicados' => $descuentos,
                    'total_descuentos' => $totalDescuentos,
                    'total_antes_descuentos' => $totalAntes,
                ]),
            ];
        });
    }

    /**
     * Procesado por admin específico
     */
    public function procesadoPor(int $adminId): static
    {
        return $this->state(fn (array $attributes) => [
            'detalles_adicionales' => array_merge($attributes['detalles_adicionales'] ?? [], [
                'admin_id' => $adminId,
            ]),
        ]);
    }

    /**
     * Pago completo (ejemplo realista)
     */
    public function completo(): static
    {
        return $this->state(fn (array $attributes) => [
            'monto' => 50.00,
            'monto_recibido' => 100.00,
            'vuelto' => 50.00,
            'metodo' => Pago::METODO_EFECTIVO,
            'estado' => Pago::ESTADO_PAGADO,
            'fecha_pago' => now(),
            'observaciones' => 'Pago recibido sin inconvenientes',
        ]);
    }
}
