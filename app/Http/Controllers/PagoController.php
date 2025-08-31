<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{
    /**
     * Mostrar modal de pago
     */
    public function showPagoModal($citaId)
    {
        try {
            $cita = Cita::with(['usuario', 'vehiculo', 'servicios', 'pago'])->findOrFail($citaId);
            
            return response()->json([
                'success' => true,
                'html' => view('admin.pagos.modal-pago', compact('cita'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error("Error al mostrar modal de pago: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar información de pago'
            ], 500);
        }
    }

    /**
     * Registrar un pago para una cita
     */
    public function registrarPago(Request $request, $citaId)
    {
        try {
            $request->validate([
                'metodo' => 'required|in:efectivo,transferencia,pasarela',
                'monto_recibido' => 'required|numeric|min:0',
                'referencia' => 'nullable|string|max:255'
            ]);

            $cita = Cita::with(['servicios', 'pago'])->findOrFail($citaId);
            
            // Validar que la cita esté en un estado que permita pago
            if (!in_array($cita->estado, [Cita::ESTADO_CONFIRMADA, Cita::ESTADO_EN_PROCESO])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede procesar el pago para una cita en estado ' . $cita->estado_formatted
                ], 422);
            }

            // Validar que no tenga ya un pago completado
            if ($cita->pago && $cita->pago->isPagado()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cita ya tiene un pago completado'
                ], 422);
            }

            DB::beginTransaction();

            $montoTotal = $cita->total;
            $montoRecibido = (float) $request->monto_recibido;
            
            // Validar monto mínimo
            if ($montoRecibido < $montoTotal && $request->metodo !== 'pasarela') {
                return response()->json([
                    'success' => false,
                    'message' => 'El monto recibido no puede ser menor al total a pagar'
                ], 422);
            }

            // Crear o actualizar el pago
            $pagoData = [
                'monto' => $montoTotal,
                'monto_recibido' => $montoRecibido,
                'vuelto' => max(0, $montoRecibido - $montoTotal),
                'metodo' => $request->metodo,
                'referencia' => $request->referencia,
                'estado' => Pago::ESTADO_PAGADO,
                'fecha_pago' => now()
            ];

            if ($cita->pago) {
                $cita->pago->update($pagoData);
                $pago = $cita->pago;
            } else {
                $pago = $cita->pago()->create($pagoData);
            }

            // Si el pago es completado y la cita está en proceso, marcarla como finalizada
            if ($cita->estado === Cita::ESTADO_EN_PROCESO) {
                $cita->estado = Cita::ESTADO_FINALIZADA;
                $cita->save();
            }

            DB::commit();

            // Generar factura (puedes implementar esto después)
            // $this->generarFactura($cita, $pago);

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente',
                'pago' => $pago,
                'cita_actualizada' => $cita->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar pago: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de pago de una cita
     */
    public function getInfoPago($citaId)
    {
        try {
            $cita = Cita::with(['pago', 'servicios'])->findOrFail($citaId);

            return response()->json([
                'success' => true,
                'cita_id' => $cita->id,
                'total' => $cita->total,
                'pago' => $cita->pago,
                'servicios' => $cita->servicios->map(function ($servicio) {
                    return [
                        'nombre' => $servicio->nombre,
                        'precio' => $servicio->pivot->precio,
                        'descuento' => $servicio->pivot->descuento,
                        'precio_final' => $servicio->pivot->precio - $servicio->pivot->descuento
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener información de pago: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del pago'
            ], 500);
        }
    }

    /**
     * Reembolsar un pago
     */
    public function reembolsarPago($citaId)
    {
        try {
            $cita = Cita::with('pago')->findOrFail($citaId);

            if (!$cita->pago || !$cita->pago->isPagado()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede reembolsar un pago que no está completado'
                ], 422);
            }

            DB::beginTransaction();

            $cita->pago->estado = Pago::ESTADO_RECHAZADO;
            $cita->pago->save();

            // Cambiar estado de la cita a cancelada si es necesario
            if ($cita->estado === Cita::ESTADO_FINALIZADA) {
                $cita->estado = Cita::ESTADO_CANCELADA;
                $cita->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago reembolsado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al reembolsar pago: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al reembolsar el pago'
            ], 500);
        }
    }
}