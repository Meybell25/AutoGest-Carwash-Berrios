<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Pago;
use App\Models\CitaServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PagoController extends Controller
{
    /**
     * Mostrar modal de pago
     */
    public function showPagoModal($citaId)
    {
        try {
            $cita = Cita::with(['usuario', 'vehiculo', 'servicios', 'pago'])->findOrFail($citaId);

            // Verificar que la cita puede recibir pago
            if (!in_array($cita->estado, [Cita::ESTADO_CONFIRMADA, Cita::ESTADO_EN_PROCESO])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden procesar pagos para citas confirmadas o en proceso'
                ], 422);
            }

            // Verificar que no tenga pago completado
            if ($cita->pago && $cita->pago->isPagado()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cita ya tiene un pago completado'
                ], 422);
            }

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
     * Registrar un pago para una cita con descuentos actualizados
     */
    public function registrarPago(Request $request, $citaId)
    {
        try {
            $request->validate([
                'metodo' => 'required|in:efectivo,transferencia,pasarela',
                'monto_recibido' => 'required|numeric|min:0',
                'total_actualizado' => 'required|numeric|min:0',
                'referencia' => 'nullable|string|max:255|required_if:metodo,transferencia,pasarela',
                'descuentos' => 'nullable|json',
                'observaciones_pago' => 'nullable|string|max:500',
                'banco_emisor' => 'nullable|string|max:100',
                'tipo_tarjeta' => 'nullable|string|max:50'
            ]);

            $cita = Cita::with(['servicios', 'pago'])->findOrFail($citaId);

            // Validar estado de la cita
            if (!in_array($cita->estado, [Cita::ESTADO_CONFIRMADA, Cita::ESTADO_EN_PROCESO])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede procesar el pago para una cita en estado ' . $cita->estado_formatted
                ], 422);
            }

            // Validar que no tenga pago completado
            if ($cita->pago && $cita->pago->isPagado()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cita ya tiene un pago completado'
                ], 422);
            }

            DB::beginTransaction();

            // Actualizar descuentos en la tabla pivot si se enviaron
            if ($request->has('descuentos')) {
                $descuentos = json_decode($request->descuentos, true);
                
                foreach ($descuentos as $servicioId => $descuento) {
                    $descuento = max(0, (float)$descuento);
                    
                    // Obtener el precio original del servicio para validar
                    $servicioOriginal = $cita->servicios()->where('servicio_id', $servicioId)->first();
                    if ($servicioOriginal) {
                        $precioBase = $servicioOriginal->pivot->precio;
                        $descuentoValidado = min($descuento, $precioBase);
                        
                        // Actualizar el descuento en la tabla pivot
                        $cita->servicios()->updateExistingPivot($servicioId, [
                            'descuento' => $descuentoValidado
                        ]);
                    }
                }
                
                // Recargar la relación para obtener los nuevos valores
                $cita->load('servicios');
            }

            // Recalcular total con los descuentos actualizados
            $montoTotal = $cita->total;
            $montoRecibido = (float) $request->monto_recibido;
            $totalActualizado = (float) $request->total_actualizado;
            $metodoPago = $request->metodo;

            // Validar que el total actualizado coincida con el calculado
            if (abs($montoTotal - $totalActualizado) > 0.01) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'El total calculado no coincide con el enviado. Por favor recargue el modal.'
                ], 422);
            }

            // Validaciones por método de pago
            if ($metodoPago === 'efectivo' && $montoRecibido < $montoTotal) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'El monto recibido en efectivo no puede ser menor al total a pagar'
                ], 422);
            }

            // Para transferencia y pasarela, el monto recibido es exactamente el total
            if (in_array($metodoPago, ['transferencia', 'pasarela'])) {
                $montoRecibido = $montoTotal;
            }

            // Validar referencia para transferencia y pasarela
            if (in_array($metodoPago, ['transferencia', 'pasarela'])) {
                if (empty($request->referencia) || strlen($request->referencia) < 6) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'La referencia es obligatoria y debe tener al menos 6 caracteres'
                    ], 422);
                }
            }

            // Preparar datos del pago
            $vuelto = max(0, $montoRecibido - $montoTotal);
            
            $pagoData = [
                'monto' => $montoTotal,
                'monto_recibido' => $montoRecibido,
                'vuelto' => $vuelto,
                'metodo' => $metodoPago,
                'referencia' => $request->referencia,
                'estado' => Pago::ESTADO_PAGADO,
                'fecha_pago' => now(),
                'observaciones' => $request->observaciones_pago,
                'detalles_adicionales' => json_encode([
                    'banco_emisor' => $request->banco_emisor,
                    'tipo_tarjeta' => $request->tipo_tarjeta,
                    'descuentos_aplicados' => $request->has('descuentos') ? json_decode($request->descuentos, true) : [],
                    'total_antes_descuentos' => $cita->servicios->sum('pivot.precio'),
                    'total_descuentos' => $cita->servicios->sum('pivot.descuento'),
                    'admin_id' => auth()->id()
                ])
            ];

            // Crear o actualizar pago
            if ($cita->pago) {
                $cita->pago->update($pagoData);
                $pago = $cita->pago->fresh();
            } else {
                $pago = $cita->pago()->create($pagoData);
            }

            // Actualizar estado de la cita
            if ($cita->estado === Cita::ESTADO_CONFIRMADA) {
                $cita->estado = Cita::ESTADO_EN_PROCESO;
            } elseif ($cita->estado === Cita::ESTADO_EN_PROCESO) {
                $cita->estado = Cita::ESTADO_FINALIZADA;
            }
            
            $cita->save();

            // Registrar en log de administrador
            Log::channel('admin_actions')->info("Pago registrado", [
                'admin_id' => auth()->id(),
                'cita_id' => $cita->id,
                'metodo' => $metodoPago,
                'monto' => $montoTotal,
                'monto_recibido' => $montoRecibido,
                'vuelto' => $vuelto,
                'referencia' => $request->referencia,
                'descuentos_aplicados' => $request->has('descuentos'),
                'nuevo_estado_cita' => $cita->estado,
                'fecha' => now(),
                'ip' => request()->ip()
            ]);

            DB::commit();

            // Limpiar caché relevante
            Cache::forget('cita_' . $citaId);
            Cache::forget('dashboard_stats');

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente. Cita actualizada a: ' . $cita->estado_formatted,
                'pago' => $pago,
                'vuelto' => $vuelto,
                'cita_actualizada' => $cita->fresh(),
                'metodo_pago' => $metodoPago,
                'total_pagado' => $montoTotal
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar pago: " . $e->getMessage(), [
                'cita_id' => $citaId,
                'admin_id' => auth()->id(),
                'request_data' => $request->except(['_token']),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información detallada de pago de una cita
     */
    public function getInfoPago($citaId)
    {
        try {
            $cita = Cita::with(['pago', 'servicios', 'usuario', 'vehiculo'])->findOrFail($citaId);

            $serviciosDetalle = $cita->servicios->map(function ($servicio) {
                return [
                    'id' => $servicio->id,
                    'nombre' => $servicio->nombre,
                    'precio_base' => $servicio->pivot->precio,
                    'descuento' => $servicio->pivot->descuento,
                    'precio_final' => $servicio->pivot->precio - $servicio->pivot->descuento,
                    'descuento_porcentaje' => $servicio->pivot->precio > 0 
                        ? round(($servicio->pivot->descuento / $servicio->pivot->precio) * 100, 2) 
                        : 0,
                    'observacion' => $servicio->pivot->observacion
                ];
            });

            return response()->json([
                'success' => true,
                'cita_id' => $cita->id,
                'cliente' => [
                    'nombre' => $cita->usuario->nombre,
                    'email' => $cita->usuario->email,
                    'telefono' => $cita->usuario->telefono
                ],
                'vehiculo' => [
                    'marca' => $cita->vehiculo->marca,
                    'modelo' => $cita->vehiculo->modelo,
                    'placa' => $cita->vehiculo->placa
                ],
                'estado_cita' => $cita->estado,
                'estado_cita_formatted' => $cita->estado_formatted,
                'fecha_hora' => $cita->fecha_hora,
                'servicios' => $serviciosDetalle,
                'subtotal' => $serviciosDetalle->sum('precio_base'),
                'total_descuentos' => $serviciosDetalle->sum('descuento'),
                'total_final' => $cita->total,
                'pago' => $cita->pago ? [
                    'id' => $cita->pago->id,
                    'monto' => $cita->pago->monto,
                    'monto_recibido' => $cita->pago->monto_recibido,
                    'vuelto' => $cita->pago->vuelto,
                    'metodo' => $cita->pago->metodo,
                    'referencia' => $cita->pago->referencia,
                    'estado' => $cita->pago->estado,
                    'fecha_pago' => $cita->pago->fecha_pago,
                    'observaciones' => $cita->pago->observaciones,
                    'detalles_adicionales' => json_decode($cita->pago->detalles_adicionales, true)
                ] : null,
                'puede_pagar' => $cita->puedeSerPagada(),
                'tiene_pago_completado' => $cita->tienePagoCompletado()
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
     * Reembolsar un pago (marcar como rechazado)
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

            // Solo admins pueden reembolsar
            if (!auth()->user() || auth()->user()->rol !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo los administradores pueden procesar reembolsos'
                ], 403);
            }

            DB::beginTransaction();

            // Actualizar estado del pago
            $cita->pago->estado = Pago::ESTADO_RECHAZADO;
            $cita->pago->save();

            // Actualizar estado de la cita
            if ($cita->estado === Cita::ESTADO_FINALIZADA) {
                $cita->estado = Cita::ESTADO_CANCELADA;
                $cita->observaciones = ($cita->observaciones ? $cita->observaciones . "\n" : '') 
                    . "Cita cancelada por reembolso procesado por " . auth()->user()->nombre . " el " . now()->format('d/m/Y H:i');
                $cita->save();
            }

            // Registrar en log
            Log::channel('admin_actions')->info("Reembolso procesado", [
                'admin_id' => auth()->id(),
                'cita_id' => $cita->id,
                'pago_id' => $cita->pago->id,
                'monto_reembolsado' => $cita->pago->monto,
                'metodo_original' => $cita->pago->metodo,
                'fecha_reembolso' => now(),
                'ip' => request()->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reembolso procesado correctamente. La cita ha sido cancelada.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al procesar reembolso: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el reembolso'
            ], 500);
        }
    }

    /**
     * Obtener historial de pagos de una cita (para auditoría)
     */
    public function historialPagos($citaId)
    {
        try {
            $cita = Cita::with(['pago'])->findOrFail($citaId);
            
            return response()->json([
                'success' => true,
                'historial' => $cita->pago ? [$cita->pago] : [],
                'cita_info' => [
                    'id' => $cita->id,
                    'estado' => $cita->estado,
                    'total' => $cita->total
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener historial de pagos'
            ], 500);
        }
    }
}