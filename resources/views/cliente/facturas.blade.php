<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Facturas - Carwash Berríos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2rem; font-weight: bold; color: #4facfe; }
        .filters { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .facturas-list { background: white; padding: 20px; border-radius: 10px; }
        .factura-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        .pagination { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mis Facturas</h1>
            <p>Historial completo de tus facturas y recibos</p>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['total_facturas'] }}</div>
                <div>Facturas Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${{ number_format($estadisticas['total_pagado'], 2) }}</div>
                <div>Total Gastado</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['facturas_este_mes'] }}</div>
                <div>Este Mes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${{ number_format($estadisticas['promedio_por_factura'], 2) }}</div>
                <div>Promedio por Factura</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters">
            <form method="GET" action="{{ route('cliente.facturas') }}">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <label>Fecha Desde:</label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control">
                    </div>
                    <div>
                        <label>Fecha Hasta:</label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control">
                    </div>
                    <div>
                        <label>Vehículo:</label>
                        <select name="vehiculo_id" class="form-control">
                            <option value="">Todos los vehículos</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}" {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }} - {{ $vehiculo->placa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; align-items: end;">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="{{ route('cliente.facturas') }}" class="btn btn-outline" style="margin-left: 10px;">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de Facturas -->
        <div class="facturas-list">
            @if($facturas->count() > 0)
                @foreach($facturas as $cita)
                    @php
                        $total = $cita->pago ? $cita->pago->monto : $cita->servicios->sum('precio');
                        $numeroFactura = 'FACT-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT);
                    @endphp
                    
                    <div class="factura-item">
                        <div style="display: flex; justify-content: between; align-items: center;">
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 10px 0; color: #4facfe;">{{ $numeroFactura }}</h3>
                                <p style="margin: 5px 0;">
                                    <strong>Fecha:</strong> {{ $cita->fecha_hora->format('d/m/Y') }}
                                </p>
                                <p style="margin: 5px 0;">
                                    <strong>Vehículo:</strong> {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                    @if($cita->vehiculo->placa)
                                        - {{ $cita->vehiculo->placa }}
                                    @endif
                                </p>
                                <p style="margin: 5px 0;">
                                    <strong>Servicios:</strong> {{ $cita->servicios->pluck('nombre')->join(', ') }}
                                </p>
                                @if($cita->pago)
                                    <p style="margin: 5px 0;">
                                        <strong>Método de pago:</strong> {{ $cita->pago->metodo_formatted }}
                                        @if($cita->pago->referencia)
                                            - Ref: {{ $cita->pago->referencia }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: #4facfe; margin-bottom: 10px;">
                                    ${{ number_format($total, 2) }}
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <button onclick="verDetalleFactura({{ $cita->id }})" class="btn btn-outline">
                                        Ver Detalle
                                    </button>
                                    <button onclick="descargarFactura({{ $cita->id }})" class="btn btn-primary">
                                        Descargar PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Paginación -->
                <div class="pagination">
                    {{ $facturas->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 40px;">
                    <h3>No hay facturas disponibles</h3>
                    <p>No tienes servicios finalizados con facturas generadas.</p>
                    <a href="{{ route('cliente.citas') }}" class="btn btn-primary">
                        Agendar Servicio
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function verDetalleFactura(citaId) {
            Swal.fire({
                title: 'Cargando factura...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/cliente/facturas/${citaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.close();
                        mostrarModalFactura(data.factura);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'No se pudo cargar la factura: ' + error.message, 'error');
                });
        }

        function descargarFactura(citaId) {
            Swal.fire({
                title: 'Generando PDF...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            window.open(`/cliente/facturas/${citaId}/descargar`, '_blank');
            Swal.close();
        }

        function mostrarModalFactura(factura) {
            const serviciosList = factura.servicios.map(servicio =>
                `<li>${servicio.nombre} - $${servicio.precio.toFixed(2)}</li>`
            ).join('');

            const htmlContent = `
                <div style="text-align: left;">
                    <div style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        <h3 style="color: #4facfe; margin-bottom: 5px;">Factura #${factura.numero}</h3>
                        <p><strong>Fecha de emisión:</strong> ${factura.fecha_emision}</p>
                        <p><strong>Fecha de servicio:</strong> ${factura.fecha_servicio} ${factura.hora_servicio}</p>
                        <p><strong>Estado:</strong> <span style="color: #28a745;">${factura.estado}</span></p>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <h4>Información del Cliente</h4>
                        <p>${factura.cliente_nombre}<br>
                        ${factura.cliente_email}<br>
                        ${factura.cliente_telefono || 'Sin teléfono'}</p>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <h4>Vehículo</h4>
                        <p>${factura.vehiculo_marca} ${factura.vehiculo_modelo}<br>
                        ${factura.vehiculo_placa ? `Placa: ${factura.vehiculo_placa}<br>` : ''}
                        Color: ${factura.vehiculo_color || 'No especificado'}<br>
                        Tipo: ${factura.vehiculo_tipo}</p>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <h4>Servicios</h4>
                        <ul style="padding-left: 20px;">
                            ${serviciosList}
                        </ul>
                    </div>
                    
                    <div style="border-top: 2px solid #4facfe; padding-top: 10px; font-weight: bold;">
                        <p style="text-align: right; font-size: 1.2em;">
                            Total: $${factura.total.toFixed(2)}
                        </p>
                    </div>
                    
                    ${factura.metodo_pago ? `
                        <div style="margin-top: 15px; background: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p><strong>Método de pago:</strong> ${factura.metodo_pago}</p>
                            ${factura.referencia_pago ? `<p><strong>Referencia:</strong> ${factura.referencia_pago}</p>` : ''}
                            <p><strong>Estado del pago:</strong> ${factura.estado_pago}</p>
                            ${factura.fecha_pago !== 'N/A' ? `<p><strong>Fecha de pago:</strong> ${factura.fecha_pago}</p>` : ''}
                        </div>
                    ` : ''}
                </div>
            `;

            Swal.fire({
                title: 'Detalle de Factura',
                html: htmlContent,
                width: '600px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    </script>
</body>
</html>