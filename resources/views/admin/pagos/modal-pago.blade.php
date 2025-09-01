<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="fas fa-credit-card me-2"></i>
            Registrar Pago - Cita #{{ $cita->id }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <!-- Información de la cita -->
        <div class="modal-section">
            <div class="modal-section-title">
                <i class="fas fa-info-circle"></i> Información de la Cita
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Cliente:</span>
                <span class="modal-info-value">{{ $cita->usuario->nombre }}</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Email:</span>
                <span class="modal-info-value">{{ $cita->usuario->email }}</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Vehículo:</span>
                <span class="modal-info-value">{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }} ({{ $cita->vehiculo->placa }})</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Fecha/Hora:</span>
                <span class="modal-info-value">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Estado actual:</span>
                <span class="modal-info-value">
                    <span class="appointment-status status-{{ $cita->estado }}">{{ $cita->estado_formatted }}</span>
                </span>
            </div>
        </div>

        <!-- Servicios detallados con opción de editar descuentos -->
        <div class="modal-section">
            <div class="modal-section-title">
                <i class="fas fa-tools"></i> Servicios y Descuentos
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Precio Base</th>
                            <th>Descuento</th>
                            <th>Precio Final</th>
                        </tr>
                    </thead>
                    <tbody id="servicios-tabla">
                        @php $totalGeneral = 0; @endphp
                        @foreach ($cita->servicios as $servicio)
                            @php 
                                $precioBase = $servicio->pivot->precio;
                                $descuento = $servicio->pivot->descuento;
                                $precioFinal = $precioBase - $descuento;
                                $totalGeneral += $precioFinal;
                            @endphp
                            <tr data-servicio-id="{{ $servicio->id }}">
                                <td class="fw-bold">{{ $servicio->nombre }}</td>
                                <td>${{ number_format($precioBase, 2) }}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control descuento-input" 
                                               data-precio-base="{{ $precioBase }}"
                                               data-servicio-id="{{ $servicio->id }}"
                                               value="{{ $descuento }}" 
                                               min="0" 
                                               max="{{ $precioBase }}" 
                                               step="0.01">
                                    </div>
                                    <small class="text-muted porcentaje-descuento">
                                        {{ $precioBase > 0 ? round(($descuento / $precioBase) * 100, 1) : 0 }}%
                                    </small>
                                </td>
                                <td class="fw-bold precio-final">${{ number_format($precioFinal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <td colspan="3" class="fw-bold text-end">TOTAL A PAGAR:</td>
                            <td class="fw-bold fs-5" id="total-general">${{ number_format($totalGeneral, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-center mt-3">
                <button type="button" class="btn btn-outline-warning btn-sm" id="aplicar-descuento-general">
                    <i class="fas fa-percentage me-1"></i> Aplicar Descuento General
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" id="quitar-descuentos">
                    <i class="fas fa-times me-1"></i> Quitar Todos los Descuentos
                </button>
            </div>
        </div>

        <!-- Formulario de pago -->
        <div class="modal-section">
            <div class="modal-section-title">
                <i class="fas fa-money-bill-wave"></i> Información del Pago
            </div>
            <form id="form-pago" data-cita-id="{{ $cita->id }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Método de pago:</label>
                    <select name="metodo" class="form-control" required id="metodo-pago">
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="transferencia">🏦 Transferencia Bancaria</option>
                        <option value="pasarela">💳 Pasarela de Pago (Tarjeta)</option>
                    </select>
                </div>

                <!-- Campos para EFECTIVO -->
                <div id="campos-efectivo">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Monto recibido:</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto_recibido" class="form-control" 
                                       step="0.01" min="0" id="monto-recibido" required>
                            </div>
                            <div class="form-text">Mínimo: $<span id="monto-minimo">{{ number_format($totalGeneral, 2) }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vuelto:</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="vuelto-calculado" readonly value="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campos para TRANSFERENCIA -->
                <div id="campos-transferencia" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Referencia/Número de transacción:</label>
                        <input type="text" name="referencia" class="form-control" id="referencia-input"
                               placeholder="Ej: TRF-789456123, REF-ABC123456">
                        <div class="form-text">
                            <i class="fas fa-info-circle text-primary"></i>
                            Ingrese el número de referencia de la transferencia (mínimo 6 caracteres)
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Banco emisor:</label>
                        <select name="banco_emisor" class="form-control" id="banco-emisor">
                            <option value="">Seleccione el banco</option>
                            <option value="bac">BAC</option>
                            <option value="banpro">BANPRO</option>
                            <option value="lafise">LAFISE</option>
                            <option value="avanz">AVANZ</option>
                            <option value="ficohsa">FICOHSA</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Para transferencia:</strong> El monto será exactamente el total de la cita ($<span class="total-transferencia">{{ number_format($totalGeneral, 2) }}</span>)
                    </div>
                </div>

                <!-- Campos para PASARELA -->
                <div id="campos-pasarela" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">ID de transacción:</label>
                        <input type="text" name="referencia" class="form-control" id="pasarela-referencia"
                               placeholder="Ej: TXN-987654321, CARD-123456">
                        <div class="form-text">
                            <i class="fas fa-credit-card text-success"></i>
                            ID de transacción generado por la pasarela de pagos
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de tarjeta:</label>
                        <select name="tipo_tarjeta" class="form-control" id="tipo-tarjeta">
                            <option value="">Seleccione tipo</option>
                            <option value="visa">VISA</option>
                            <option value="mastercard">MasterCard</option>
                            <option value="amex">American Express</option>
                            <option value="otra">Otra</option>
                        </select>
                    </div>
                    <div class="alert alert-success">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Pago con tarjeta:</strong> Monto procesado automáticamente ($<span class="total-pasarela">{{ number_format($totalGeneral, 2) }}</span>)
                    </div>
                </div>

                <!-- Observaciones adicionales -->
                <div class="mb-3">
                    <label class="form-label">Observaciones del pago (opcional):</label>
                    <textarea name="observaciones_pago" class="form-control" rows="2" 
                              placeholder="Ej: Cliente solicita factura, pago realizado en partes, etc."></textarea>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="button" class="btn btn-success" id="btn-registrar-pago">
            <i class="fas fa-check-circle"></i> Registrar Pago
        </button>
    </div>
</div>

<!-- Modal para descuento general -->
<div class="modal fade" id="descuentoGeneralModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aplicar Descuento General</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Porcentaje de descuento:</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="porcentaje-general" 
                               min="0" max="100" step="0.1" placeholder="10">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="aplicar-porcentaje">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        border-left: 4px solid var(--primary, #2e7d32);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .modal-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary, #2e7d32);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-info-item:last-child {
        border-bottom: none;
    }

    .modal-info-label {
        font-weight: 600;
        color: var(--text-primary, #2c3e50);
    }

    .modal-info-value {
        color: var(--text-secondary, #7f8c8d);
        text-align: right;
    }

    .descuento-input:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    .porcentaje-descuento {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .total-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--success, #28a745);
    }

    .appointment-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-en_proceso {
        background: linear-gradient(135deg, #f1e6ff, #e1bee7);
        color: #6a1b9a;
        border: 1px solid #ce93d8;
    }

    .status-confirmada {
        background: linear-gradient(135deg, #e1f5fe, #b3e5fc);
        color: #0277bd;
        border: 1px solid #81d4fa;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let totalActual = parseFloat({{ $totalGeneral }});
    let isProcessing = false;

    // Función para recalcular totales
    function recalcularTotales() {
        let nuevoTotal = 0;
        
        document.querySelectorAll('#servicios-tabla tr[data-servicio-id]').forEach(row => {
            const precioBase = parseFloat(row.querySelector('.descuento-input').dataset.precioBase);
            const descuento = parseFloat(row.querySelector('.descuento-input').value) || 0;
            const precioFinal = precioBase - descuento;
            
            // Actualizar precio final en la tabla
            row.querySelector('.precio-final').textContent = '$' + precioFinal.toFixed(2);
            
            // Actualizar porcentaje
            const porcentaje = precioBase > 0 ? ((descuento / precioBase) * 100).toFixed(1) : 0;
            row.querySelector('.porcentaje-descuento').textContent = porcentaje + '%';
            
            nuevoTotal += precioFinal;
        });
        
        // Actualizar total general
        totalActual = nuevoTotal;
        document.getElementById('total-general').textContent = '$' + nuevoTotal.toFixed(2);
        document.getElementById('monto-minimo').textContent = nuevoTotal.toFixed(2);
        document.querySelector('.total-transferencia').textContent = nuevoTotal.toFixed(2);
        document.querySelector('.total-pasarela').textContent = nuevoTotal.toFixed(2);
        
        // Recalcular vuelto si está visible
        if (document.getElementById('campos-efectivo').style.display !== 'none') {
            calcularVuelto();
        }
    }

    // Event listeners para descuentos individuales
    document.querySelectorAll('.descuento-input').forEach(input => {
        input.addEventListener('input', function() {
            const precioBase = parseFloat(this.dataset.precioBase);
            let descuento = parseFloat(this.value) || 0;
            
            // Validar que el descuento no sea mayor al precio base
            if (descuento > precioBase) {
                descuento = precioBase;
                this.value = precioBase;
            }
            
            if (descuento < 0) {
                descuento = 0;
                this.value = 0;
            }
            
            recalcularTotales();
        });
    });

    // Aplicar descuento general
    document.getElementById('aplicar-descuento-general').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('descuentoGeneralModal'));
        modal.show();
    });

    document.getElementById('aplicar-porcentaje').addEventListener('click', function() {
        const porcentaje = parseFloat(document.getElementById('porcentaje-general').value) || 0;
        
        if (porcentaje < 0 || porcentaje > 100) {
            alert('El porcentaje debe estar entre 0 y 100');
            return;
        }
        
        document.querySelectorAll('.descuento-input').forEach(input => {
            const precioBase = parseFloat(input.dataset.precioBase);
            const descuento = (precioBase * porcentaje) / 100;
            input.value = descuento.toFixed(2);
        });
        
        recalcularTotales();
        bootstrap.Modal.getInstance(document.getElementById('descuentoGeneralModal')).hide();
    });

    // Quitar todos los descuentos
    document.getElementById('quitar-descuentos').addEventListener('click', function() {
        if (confirm('¿Está seguro de quitar todos los descuentos?')) {
            document.querySelectorAll('.descuento-input').forEach(input => {
                input.value = '0';
            });
            recalcularTotales();
        }
    });

    // Cambio de método de pago
    function cambiarMetodoPago() {
        const metodo = document.getElementById('metodo-pago').value;
        
        // Ocultar todos los campos específicos
        document.getElementById('campos-efectivo').style.display = 'none';
        document.getElementById('campos-transferencia').style.display = 'none';
        document.getElementById('campos-pasarela').style.display = 'none';
        
        // Mostrar campos según el método
        if (metodo === 'efectivo') {
            document.getElementById('campos-efectivo').style.display = 'block';
            document.getElementById('monto-recibido').value = totalActual.toFixed(2);
            document.getElementById('monto-recibido').min = totalActual.toFixed(2);
            calcularVuelto();
        } else if (metodo === 'transferencia') {
            document.getElementById('campos-transferencia').style.display = 'block';
        } else if (metodo === 'pasarela') {
            document.getElementById('campos-pasarela').style.display = 'block';
        }
        
        // Limpiar errores
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    document.getElementById('metodo-pago').addEventListener('change', cambiarMetodoPago);

    // Calcular vuelto
    function calcularVuelto() {
        const recibido = parseFloat(document.getElementById('monto-recibido').value) || 0;
        const vuelto = Math.max(0, recibido - totalActual);
        document.getElementById('vuelto-calculado').value = vuelto.toFixed(2);
        
        // Validar monto mínimo
        if (recibido < totalActual) {
            document.getElementById('monto-recibido').classList.add('is-invalid');
        } else {
            document.getElementById('monto-recibido').classList.remove('is-invalid');
        }
    }

    document.getElementById('monto-recibido').addEventListener('input', calcularVuelto);

    // Registrar pago
    document.getElementById('btn-registrar-pago').addEventListener('click', function() {
        if (isProcessing) return;
        
        const form = document.getElementById('form-pago');
        const citaId = form.dataset.citaId;
        const metodo = document.getElementById('metodo-pago').value;
        
        // Validaciones específicas por método
        let isValid = true;
        
        if (metodo === 'efectivo') {
            const montoRecibido = parseFloat(document.getElementById('monto-recibido').value) || 0;
            if (montoRecibido < totalActual) {
                document.getElementById('monto-recibido').classList.add('is-invalid');
                isValid = false;
            }
        } else if (metodo === 'transferencia') {
            const referencia = document.getElementById('referencia-input').value.trim();
            if (referencia.length < 6) {
                document.getElementById('referencia-input').classList.add('is-invalid');
                isValid = false;
            }
        } else if (metodo === 'pasarela') {
            const referencia = document.getElementById('pasarela-referencia').value.trim();
            if (referencia.length < 6) {
                document.getElementById('pasarela-referencia').classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                text: 'Por favor complete correctamente todos los campos obligatorios.'
            });
            return;
        }
        
        isProcessing = true;
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        
        // Preparar datos
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('metodo', metodo);
        formData.append('total_actualizado', totalActual);
        
        if (metodo === 'efectivo') {
            formData.append('monto_recibido', document.getElementById('monto-recibido').value);
        } else {
            formData.append('monto_recibido', totalActual);
        }
        
        // Referencia según método
        if (metodo === 'transferencia') {
            formData.append('referencia', document.getElementById('referencia-input').value);
            formData.append('banco_emisor', document.getElementById('banco-emisor').value);
        } else if (metodo === 'pasarela') {
            formData.append('referencia', document.getElementById('pasarela-referencia').value);
            formData.append('tipo_tarjeta', document.getElementById('tipo-tarjeta').value);
        }
        
        formData.append('observaciones_pago', document.querySelector('textarea[name="observaciones_pago"]').value);
        
        // Agregar descuentos actualizados
        const descuentos = {};
        document.querySelectorAll('.descuento-input').forEach(input => {
            descuentos[input.dataset.servicioId] = parseFloat(input.value) || 0;
        });
        formData.append('descuentos', JSON.stringify(descuentos));
        
        // Enviar solicitud
        fetch(`/admin/pagos/${citaId}/registrar`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            isProcessing = false;
            document.getElementById('btn-registrar-pago').disabled = false;
            document.getElementById('btn-registrar-pago').innerHTML = '<i class="fas fa-check-circle"></i> Registrar Pago';
            
            if (data.success) {
                let mensaje = data.message;
                if (data.vuelto > 0) {
                    mensaje += `\nVuelto a entregar: $${data.vuelto.toFixed(2)}`;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Pago registrado exitosamente!',
                    html: mensaje.replace(/\n/g, '<br>'),
                    timer: 3000
                }).then(() => {
                    bootstrap.Modal.getInstance(document.getElementById('pagoModal')).hide();
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            isProcessing = false;
            document.getElementById('btn-registrar-pago').disabled = false;
            document.getElementById('btn-registrar-pago').innerHTML = '<i class="fas fa-check-circle"></i> Registrar Pago';
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión. Intente nuevamente.'
            });
        });
    });
    
    // Inicializar
    cambiarMetodoPago();
});
</script>