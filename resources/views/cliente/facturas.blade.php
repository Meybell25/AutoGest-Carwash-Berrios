<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Facturas - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(45deg, #4facfe 0%, #1be9f4 100%);
            --success-gradient: linear-gradient(45deg, #3dd26e 0%, #35ebc9 100%);
            --warning-gradient: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #333;
            --text-secondary: #666;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --border-radius: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #bbadfd, #5b21b6, #452383);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: rgba(245, 245, 245, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            border-radius: var(--border-radius-xl);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
            border-radius: 20px 20px 0 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-section h1 {
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-icon {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Cards Base */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 25px;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            padding: 20px 25px 0;
            border-bottom: 2px solid #f1f3f4;
            margin-bottom: 20px;
            position: relative;
        }

        .card-header h2 {
            color: #4facfe;
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .card-header .icon {
            background: var(--secondary-gradient);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 0 25px 25px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(245, 245, 245, 0.9));
            padding: 25px;
            border-radius: var(--border-radius-lg);
            text-align: center;
            border-left: 4px solid #4facfe;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #4facfe;
            display: block;
            margin-bottom: 8px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Filters */
        .filters-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(250, 250, 250, 0.95));
            padding: 25px;
            border-radius: var(--border-radius-lg);
            margin-bottom: 25px;
            border: 1px solid rgba(79, 172, 254, 0.1);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4facfe;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e3e8ef;
            border-radius: 10px;
            font-size: 14px;
            transition: var(--transition);
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4facfe;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        /* Buttons */
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #4facfe;
            color: #4facfe;
        }

        .btn-outline:hover {
            background: #4facfe;
            color: white;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
        }

        /* Facturas List */
        .facturas-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .factura-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(250, 250, 250, 0.95));
            border-radius: var(--border-radius-lg);
            padding: 25px;
            border-left: 4px solid #4facfe;
            transition: var(--transition);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .factura-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-left-color: #667eea;
        }

        .factura-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .factura-info h3 {
            color: #4facfe;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .factura-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .meta-item i {
            color: #4facfe;
            width: 16px;
        }

        .factura-amount {
            text-align: right;
        }

        .amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: #4facfe;
            display: block;
            margin-bottom: 8px;
        }

        .status-badge {
            background: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .payment-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .payment-efectivo {
            background: #28a745;
            color: white;
        }

        .payment-transferencia {
            background: #17a2b8;
            color: white;
        }

        .payment-pasarela {
            background: #6f42c1;
            color: white;
        }

        .servicios-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .servicio-tag {
            background: #e9ecef;
            color: #495057;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .factura-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            color: #4facfe;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state h3 {
            margin-bottom: 15px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .empty-state p {
            margin-bottom: 25px;
            line-height: 1.6;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
        }

        .pagination li {
            margin: 0;
        }

        .pagination a,
        .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            background: white;
            color: #4facfe;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .pagination a:hover {
            background: #4facfe;
            color: white;
            transform: translateY(-2px);
        }

        .pagination .active span {
            background: var(--secondary-gradient);
            color: white;
            border-color: #4facfe;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-section h1 {
                justify-content: center;
            }

            .header-actions {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .factura-header {
                flex-direction: column;
                gap: 15px;
            }

            .factura-amount {
                text-align: left;
                width: 100%;
            }

            .factura-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .btn {
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 15px;
            }

            .header {
                padding: 20px;
            }

            .card-header, .card-body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <div class="welcome-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        Mis Facturas
                    </h1>
                    <p>Historial completo de tus facturas y recibos</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('cliente.citas') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i>
                        Nueva Cita
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card">
            <div class="card-header">
                <h2>
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    Resumen de Facturas
                </h2>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-number">{{ $estadisticas['total_facturas'] }}</span>
                        <span class="stat-label">Facturas Totales</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">${{ number_format($estadisticas['total_pagado'], 2) }}</span>
                        <span class="stat-label">Total Gastado</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">{{ $estadisticas['facturas_este_mes'] }}</span>
                        <span class="stat-label">Este Mes</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">${{ number_format($estadisticas['promedio_por_factura'], 2) }}</span>
                        <span class="stat-label">Promedio por Factura</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-card">
            <form method="GET" action="{{ route('cliente.facturas') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="fecha_desde">
                            <i class="fas fa-calendar-alt"></i> Fecha Desde
                        </label>
                        <input type="date" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="form-group">
                        <label for="fecha_hasta">
                            <i class="fas fa-calendar-alt"></i> Fecha Hasta
                        </label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="form-group">
                        <label for="vehiculo_id">
                            <i class="fas fa-car"></i> Vehículo
                        </label>
                        <select id="vehiculo_id" name="vehiculo_id">
                            <option value="">Todos los vehículos</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}" {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }} - {{ $vehiculo->placa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-filter"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('cliente.facturas') }}" class="btn btn-outline" style="width: 100%; margin-top: 10px;">
                            <i class="fas fa-times"></i> Limpiar Filtros
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de Facturas -->
        <div class="card">
            <div class="card-header">
                <h2>
                    <div class="icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    Historial de Facturas
                    <span style="margin-left: auto; font-size: 1rem; color: var(--text-secondary);">
                        {{ $facturas->total() }} facturas encontradas
                    </span>
                </h2>
            </div>
            <div class="card-body">
                @if($facturas->count() > 0)
                    <div class="facturas-grid">
                        @foreach($facturas as $cita)
                            @php
                                $total = $cita->pago ? $cita->pago->monto : $cita->servicios->sum('precio');
                                $numeroFactura = 'FACT-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT);
                                
                                // Determinar clase del badge de pago
                                $paymentClass = '';
                                if ($cita->pago) {
                                    switch($cita->pago->metodo) {
                                        case 'efectivo':
                                            $paymentClass = 'payment-efectivo';
                                            break;
                                        case 'transferencia':
                                            $paymentClass = 'payment-transferencia';
                                            break;
                                        case 'pasarela':
                                            $paymentClass = 'payment-pasarela';
                                            break;
                                    }
                                }
                            @endphp
                            
                            <div class="factura-card">
                                @if($cita->pago)
                                    <span class="payment-badge {{ $paymentClass }}">
                                        @switch($cita->pago->metodo)
                                            @case('efectivo')
                                                💵 Efectivo
                                                @break
                                            @case('transferencia')
                                                🏦 Transferencia
                                                @break
                                            @case('pasarela')
                                                💳 Tarjeta
                                                @break
                                        @endswitch
                                    </span>
                                @endif

                                <div class="factura-header">
                                    <div class="factura-info" style="flex: 1;">
                                        <h3>{{ $numeroFactura }}</h3>
                                        <div class="factura-meta">
                                            <div class="meta-item">
                                                <i class="fas fa-calendar"></i>
                                                {{ $cita->fecha_hora->format('d/m/Y') }}
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-clock"></i>
                                                {{ $cita->fecha_hora->format('h:i A') }}
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-car"></i>
                                                {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                            </div>
                                        </div>
                                        
                                        <div class="servicios-list">
                                            @foreach($cita->servicios->take(4) as $servicio)
                                                <span class="servicio-tag">{{ $servicio->nombre }}</span>
                                            @endforeach
                                            @if($cita->servicios->count() > 4)
                                                <span class="servicio-tag" style="background: #4facfe; color: white;">
                                                    +{{ $cita->servicios->count() - 4 }} más
                                                </span>
                                            @endif
                                        </div>

                                        @if($cita->pago && $cita->pago->referencia)
                                            <div style="background: #fff3cd; padding: 8px 12px; border-radius: 6px; margin-top: 10px;">
                                                <small style="color: #856404;">
                                                    <i class="fas fa-receipt"></i>
                                                    <strong>Referencia:</strong> {{ $cita->pago->referencia }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="factura-amount">
                                        <span class="amount">${{ number_format($total, 2) }}</span>
                                        <span class="status-badge">
                                            <i class="fas fa-check-circle"></i> COMPLETADA
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="factura-actions">
                                    <button onclick="verDetalleFactura({{ $cita->id }})" class="btn btn-outline btn-sm">
                                        <i class="fas fa-eye"></i> Ver Detalle
                                    </button>
                                    <button onclick="descargarFactura({{ $cita->id }})" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Descargar PDF
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="pagination-container">
                        {{ $facturas->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-file-invoice"></i>
                        <h3>No hay facturas disponibles</h3>
                        <p>
                            Aún no tienes servicios finalizados con facturas generadas.<br>
                            Tus facturas aparecerán aquí una vez que completes tus servicios.
                        </p>
                        <a href="{{ route('cliente.citas') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Agendar Servicio
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Función para ver el detalle de una factura (CORREGIDA)
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

        // Función para descargar factura en PDF
        function descargarFactura(citaId) {
            Swal.fire({
                title: 'Generando PDF...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            window.open(`/cliente/facturas/${citaId}/descargar`, '_blank');
            Swal.close();
        }

        // Función para mostrar modal con detalles de factura (CORREGIDA)
        function mostrarModalFactura(factura) {
            // Asegurar que total sea un número
            const total = typeof factura.total === 'number' ? factura.total : parseFloat(factura.total) || 0;
            
            const serviciosList = factura.servicios.map(servicio => {
                const precio = typeof servicio.precio === 'number' ? servicio.precio : parseFloat(servicio.precio) || 0;
                return `<li>${servicio.nombre} - $${precio.toFixed(2)}</li>`;
            }).join('');

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
                            Total: $${total.toFixed(2)}
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
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide'
                }
            });
        }

        // Agregar estilos para el modal ancho
        const style = document.createElement('style');
        style.textContent = `
            .swal-wide {
                max-width: 90vw !important;
            }
            
            .swal2-popup.swal-wide {
                font-family: inherit;
            }
            
            .swal2-popup.swal-wide .swal2-html-container {
                max-height: 70vh;
                overflow-y: auto;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>