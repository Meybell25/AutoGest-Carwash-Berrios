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
                <span class="modal-info-value">{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                    ({{ $cita->vehiculo->placa }})</span>
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
            @foreach ($cita->servicios as $servicio)
                <div class="service-item">
                    <span class="service-name">{{ $servicio->nombre }}</span>
                    <span class="service-price">
                        ${{ number_format($servicio->pivot->precio - $servicio->pivot->descuento, 2) }}
                        @if ($servicio->pivot->descuento > 0)
                            <small class="text-muted">(Descuento:
                                ${{ number_format($servicio->pivot->descuento, 2) }})</small>
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

                <!-- Campos para EFECTIVO -->
                <div id="campos-efectivo">
                    <div class="mb-3">
                        <label class="form-label">Monto recibido:</label>
                        <input type="number" name="monto_recibido" class="form-control" step="0.01"
                            min="{{ $cita->total }}" value="{{ $cita->total }}" required
                            id="monto-recibido">
                        <div class="form-text">Mínimo: ${{ number_format($cita->total, 2) }}</div>
                        <div class="invalid-feedback" id="monto-recibido-error">
                            El monto recibido no puede ser menor al total a pagar.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vuelto calculado:</label>
                        <input type="text" class="form-control" id="vuelto-calculado" readonly
                            value="$0.00">
                    </div>
                </div>

                <!-- Campos para TRANSFERENCIA/PASARELA -->
                <div id="campos-transferencia" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Referencia/Comprobante:</label>
                        <input type="text" name="referencia" class="form-control" id="referencia-input"
                            placeholder="Número de transacción o referencia">
                        <div class="form-text">Ej: TRF-789456, CMP-123456 (mínimo 6 caracteres)</div>
                        <div class="invalid-feedback" id="referencia-error">
                            La referencia es obligatoria y debe tener al menos 6 caracteres.
                        </div>
                    </div>
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
    let isProcessing = false; // Para prevenir múltiples envíos

    // Inicializar campos al cargar la página
    function inicializarCampos() {
        const metodo = $('#metodo-pago').val();

        if (metodo === 'efectivo') {
            $('#campos-efectivo').show();
            $('#campos-transferencia').hide();
            $('#monto-recibido').prop('required', true);
            $('#referencia-input').prop('required', false);
        } else {
            $('#campos-efectivo').hide();
            $('#campos-transferencia').show();
            $('#monto-recibido').prop('required', false);
            $('#referencia-input').prop('required', true);
        }

        // Limpiar mensajes de error
        $('.is-invalid').removeClass('is-invalid');
    }

    // Inicializar campos al cargar
    inicializarCampos();

    // Mostrar/ocultar campos según método de pago
    $('#metodo-pago').change(function() {
        inicializarCampos();

        // Si es efectivo, calcular vuelto con el valor actual
        if ($(this).val() === 'efectivo') {
            calcularVuelto();
        }
    });

    // Calcular vuelto automáticamente
    function calcularVuelto() {
        const recibido = parseFloat($('#monto-recibido').val()) || 0;
        const vuelto = Math.max(0, recibido - total);

        $('#vuelto-calculado').val('$' + vuelto.toFixed(2));

        // Validar en tiempo real
        if (recibido < total) {
            $('#monto-recibido').addClass('is-invalid');
        } else {
            $('#monto-recibido').removeClass('is-invalid');
        }
    }

    $('#monto-recibido').on('input', calcularVuelto);

    // Validar referencia en tiempo real
    $('#referencia-input').on('input', function() {
        if ($(this).val().length > 0 && $(this).val().length < 6) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Validar formulario antes de enviar
    function validarFormulario() {
        let isValid = true;
        const metodo = $('#metodo-pago').val();
        const montoRecibido = parseFloat($('#monto-recibido').val()) || 0;
        const referencia = $('#referencia-input').val();

        // Validar efectivo
        if (metodo === 'efectivo') {
            if (montoRecibido < total) {
                $('#monto-recibido').addClass('is-invalid');
                isValid = false;
            }

            if (isNaN(montoRecibido) || montoRecibido === 0) {
                $('#monto-recibido').addClass('is-invalid');
                isValid = false;
            }
        }

        // Validar transferencia/pasarela
        if ((metodo === 'transferencia' || metodo === 'pasarela')) {
            if (!referencia || referencia.length < 6) {
                $('#referencia-input').addClass('is-invalid');
                isValid = false;
            }
        }

        return isValid;
    }

    // Registrar pago
    $('#btn-registrar-pago').click(function() {
        if (isProcessing) return; // Prevenir múltiples clics

        const form = $('#form-pago');
        const citaId = form.data('cita-id');

        // Validar formulario antes de enviar
        if (!validarFormulario()) {
            // Mostrar mensaje general de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor complete correctamente todos los campos obligatorios.'
            });
            return;
        }

        isProcessing = true;
        $('#btn-registrar-pago').prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

        // Mostrar loading
        Swal.fire({
            title: 'Procesando pago...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Preparar datos para enviar
        const formData = {
            _token: $('input[name="_token"]').val(),
            metodo: $('#metodo-pago').val(),
            monto_recibido: $('#monto-recibido').val(),
            referencia: $('#referencia-input').val()
        };

        $.ajax({
            url: `/admin/pagos/${citaId}/registrar`,
            method: 'POST',
            data: formData,
            success: function(response) {
                isProcessing = false;
                $('#btn-registrar-pago').prop('disabled', false)
                    .html('<i class="fas fa-check"></i> Registrar Pago');
                Swal.close();

                if (response.success) {
                    let mensaje = response.message;

                    // Mostrar vuelto si aplica
                    if (response.vuelto > 0) {
                        mensaje += `\\nVuelto: $${response.vuelto.toFixed(2)}`;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: '¡Pago registrado!',
                        html: mensaje.replace(/\n/g, '<br>'),
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#pagoModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                isProcessing = false;
                $('#btn-registrar-pago').prop('disabled', false)
                    .html('<i class="fas fa-check"></i> Registrar Pago');
                Swal.close();

                let errorMsg = 'Error al registrar pago';

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        // Mostrar primeros errores de validación
                        const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                        errorMsg = firstError;
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        });
    });

    // Limpiar formulario cuando se cierra el modal
    $('#pagoModal').on('hidden.bs.modal', function() {
        $('#form-pago')[0].reset();
        $('#vuelto-calculado').val('$0.00');
        $('.is-invalid').removeClass('is-invalid');
        inicializarCampos();
        isProcessing = false;
        $('#btn-registrar-pago').prop('disabled', false)
            .html('<i class="fas fa-check"></i> Registrar Pago');
    });
});
</script>