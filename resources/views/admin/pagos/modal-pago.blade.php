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
                <span class="modal-info-label">Vehículo:</span>
                <span class="modal-info-value">{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }} ({{ $cita->vehiculo->placa }})</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Total a pagar:</span>
                <span class="modal-info-value total-amount">${{ number_format($cita->total, 2) }}</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Estado actual:</span>
                <span class="modal-info-value">
                    <span class="appointment-status status-{{ $cita->estado }}">{{ $cita->estado_formatted }}</span>
                </span>
            </div>
        </div>

        <!-- Servicios -->
        <div class="modal-section">
            <div class="modal-section-title">
                <i class="fas fa-list"></i> Desglose de Servicios
            </div>
            @foreach($cita->servicios as $servicio)
            <div class="service-item">
                <span class="service-name">{{ $servicio->nombre }}</span>
                <span class="service-price">
                    ${{ number_format($servicio->pivot->precio - $servicio->pivot->descuento, 2) }}
                    @if($servicio->pivot->descuento > 0)
                    <small class="text-muted">(Descuento: ${{ number_format($servicio->pivot->descuento, 2) }})</small>
                    @endif
                </span>
            </div>
            @endforeach
        </div>

        <!-- Formulario de pago -->
        <div class="modal-section">
            <div class="modal-section-title">
                <i class="fas fa-money-bill-wave"></i> Información del Pago
            </div>
            <form id="form-pago" data-cita-id="{{ $cita->id }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Método de pago:</label>
                    <select name="metodo" class="form-control" required id="metodo-pago">
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="pasarela">Pasarela de pago</option>
                    </select>
                </div>

                <div class="mb-3" id="campo-monto-recibido">
                    <label class="form-label">Monto recibido:</label>
                    <input type="number" name="monto_recibido" class="form-control" 
                           step="0.01" min="{{ $cita->total }}" value="{{ $cita->total }}"
                           required id="monto-recibido">
                    <div class="form-text">Mínimo: ${{ number_format($cita->total, 2) }}</div>
                </div>

                <div class="mb-3" id="campo-referencia" style="display: none;">
                    <label class="form-label">Referencia/Comprobante:</label>
                    <input type="text" name="referencia" class="form-control" 
                           placeholder="Número de transacción o referencia">
                </div>

                <div class="mb-3" id="campo-vuelto">
                    <label class="form-label">Vuelto calculado:</label>
                    <input type="text" class="form-control" id="vuelto-calculado" 
                           readonly value="$0.00">
                </div>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="button" class="btn btn-success" id="btn-registrar-pago">
            <i class="fas fa-check"></i> Registrar Pago
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const total = {{ $cita->total }};
    
    // Mostrar/ocultar campos según método de pago
    $('#metodo-pago').change(function() {
        const metodo = $(this).val();
        
        if (metodo === 'efectivo') {
            $('#campo-monto-recibido').show();
            $('#campo-vuelto').show();
            $('#campo-referencia').hide();
            $('input[name="referencia"]').prop('required', false);
        } else {
            $('#campo-monto-recibido').hide();
            $('#campo-vuelto').hide();
            $('#campo-referencia').show();
            $('input[name="referencia"]').prop('required', true);
            
            // Para transferencia/pasarela, establecer monto recibido = total
            $('#monto-recibido').val(total);
        }
    });

    // Calcular vuelto automáticamente
    $('#monto-recibido').on('input', function() {
        const recibido = parseFloat($(this).val()) || 0;
        const vuelto = Math.max(0, recibido - total);
        
        $('#vuelto-calculado').val('$' + vuelto.toFixed(2));
    });

    // Registrar pago
    $('#btn-registrar-pago').click(function() {
        const form = $('#form-pago');
        const citaId = form.data('cita-id');
        
        // Validación adicional
        const metodo = $('#metodo-pago').val();
        const montoRecibido = parseFloat($('#monto-recibido').val()) || 0;
        
        if (metodo === 'efectivo' && montoRecibido < total) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El monto recibido no puede ser menor al total'
            });
            return;
        }
        
        $.ajax({
            url: `/admin/pagos/${citaId}/registrar`,
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Pago registrado!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#pagoModal').modal('hide');
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Error al registrar pago';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        });
    });
});
</script>