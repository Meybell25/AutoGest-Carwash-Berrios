<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Citas - Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
            --border-radius: 0.75rem;
            --border-radius-lg: 1rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #bbadfd, #5b21b6, #452383);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Partículas flotantes de fondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(250, 112, 154, 0.05) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: var(--text-secondary);
            margin-top: 10px;
            font-size: 1.1rem;
        }

        /* Navegación entre vistas */
        .view-nav {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
            background: var(--glass-bg);
            border-radius: var(--border-radius-lg);
            padding: 10px;
            box-shadow: var(--shadow-soft);
            gap: 10px;
        }

        .nav-btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .nav-btn:not(.active) {
            background: transparent;
            color: var(--text-secondary);
        }

        .nav-btn:not(.active):hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--text-primary);
        }

        .filters-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 25px;
            margin-bottom: 30px;
        }

        /* Contadores específicos para historial */
        .counters-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: var(--border-radius);
        }

        .counter-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            min-width: 120px;
            max-width: 200px;
        }

        .counter-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .counter-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .counter-total .counter-value {
            color: #667eea;
        }

        .counter-finalizada .counter-value {
            color: #2e7d32;
        }

        .counter-cancelada .counter-value {
            color: #ad1457;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group select,
        .filter-group input {
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: var(--text-primary);
            border: 2px solid #e1e5e9;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .citas-grid {
            display: grid;
            gap: 20px;
        }

        .cita-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 25px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .cita-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 172, 254, 0.1));
            opacity: 0.1;
            transition: all 0.3s ease;
        }

        .cita-card:hover::before {
            width: 100%;
        }

        .cita-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        /* COLORES DE BORDE PARA HISTORIAL */
        .cita-card.finalizada,
        .cita-card.finalizado {
            border-left: 5px solid #2e7d32 !important;
        }

        .cita-card.cancelada {
            border-left: 5px solid #dc3545 !important;
        }

        .cita-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .cita-date-time {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .date-badge {
            color: white;
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: center;
            min-width: 80px;
            box-shadow: var(--shadow-soft);
        }

        /* COLORES DE BADGE PARA HISTORIAL */
        .date-badge.finalizada,
        .date-badge.finalizado {
            background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
        }

        .date-badge.cancelada {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
        }

        .date-badge .day {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .date-badge .month {
            display: block;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
            opacity: 0.9;
        }

        .time-info {
            flex: 1;
        }

        .time-info .time {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .time-info .duration {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .vehicle-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
        }

        .vehicle-info i {
            color: #667eea;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* ESTILOS DE BADGE PARA HISTORIAL */
        .status-finalizada,
        .status-finalizado {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9) !important;
            color: #2e7d32 !important;
            border: 1px solid #a5d6a7 !important;
        }

        .status-finalizada:hover,
        .status-finalizado:hover {
            background: linear-gradient(135deg, #c8e6c9, #a5d6a7) !important;
        }

        .status-cancelada {
            background: linear-gradient(135deg, #fde7f3, #f8bbd9) !important;
            color: #ad1457 !important;
            border: 1px solid #f48fb1 !important;
        }

        .status-cancelada:hover {
            background: linear-gradient(135deg, #f8bbd9, #f48fb1) !important;
        }

        .cita-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        /* COLORES DE BORDE PARA SECCIONES EN HISTORIAL */
        .cita-card.finalizada .detail-section,
        .cita-card.finalizado .detail-section {
            border-left: 4px solid #2e7d32 !important;
        }

        .cita-card.cancelada .detail-section {
            border-left: 4px solid #dc3545 !important;
        }

        .detail-section {
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: var(--border-radius);
        }

        .detail-section h4 {
            margin: 0 0 15px 0;
            color: var(--text-primary);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .service-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .service-item:last-child {
            border-bottom: none;
        }

        .service-name {
            font-weight: 600;
            flex: 1;
        }

        .service-price {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
        }

        .empty-state i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: var(--border-radius);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        /* Indicador de tiempo transcurrido */
        .time-ago {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-style: italic;
        }

        /* Agrega esto al final de tu sección de estilos */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .cita-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .cita-details {
                grid-template-columns: 1fr;
            }

            .back-button {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
                display: inline-block;
            }

            .view-nav {
                flex-direction: column;
                gap: 5px;
            }

            .counters-container {
                flex-direction: column;
                gap: 10px;
            }

            .counter-item {
                flex-direction: row;
                justify-content: space-between;
                min-width: auto;
                padding: 10px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .counter-item:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>

<body>
    <a href="{{ route('cliente.dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Volver al Dashboard
    </a>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Historial de Citas</h1>
            <p>Registro completo de tus citas anteriores</p>
        </div>


        <!-- Filtros -->
        <div class="filters-section">
            <!-- Contadores específicos para historial -->
            <div class="counters-container">
                <div class="counter-item counter-total">
                    <span class="counter-value" id="total-counter">{{ $citas->total() }}</span>
                    <span class="counter-label">Total Historial</span>
                </div>
                <div class="counter-item counter-finalizada">
                    <span class="counter-value"
                        id="finalizada-counter">{{ $citas->where('estado', 'finalizada')->count() }}</span>
                    <span class="counter-label">Finalizadas</span>
                </div>
                <div class="counter-item counter-cancelada">
                    <span class="counter-value"
                        id="cancelada-counter">{{ $citas->where('estado', 'cancelada')->count() }}</span>
                    <span class="counter-label">Canceladas</span>
                </div>
            </div>

            <form id="filtrosForm">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="estado">
                            <i class="fas fa-filter"></i> Estado
                        </label>
                        <select name="estado" id="estado">
                            <option value="">Todos los estados</option>
                            <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>
                                Finalizada</option>
                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                Cancelada</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="fecha_desde">
                            <i class="fas fa-calendar-day"></i> Desde
                        </label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>

                    <div class="filter-group">
                        <label for="fecha_hasta">
                            <i class="fas fa-calendar-day"></i> Hasta
                        </label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>

                    <div class="filter-group">
                        <label for="vehiculo_id">
                            <i class="fas fa-car"></i> Vehículo
                        </label>
                        <select name="vehiculo_id" id="vehiculo_id">
                            <option value="">Todos los vehículos</option>
                            @foreach (auth()->user()->vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}"
                                    {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                    @if ($vehiculo->placa)
                                        - {{ $vehiculo->placa }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>

                    <div class="filter-group">
                        <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                            <i class="fas fa-times"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de citas del historial -->
        <div id="citas-container">
            @if ($citas->count() > 0)
                <div class="citas-grid">
                    @foreach ($citas as $cita)
                        @php
                            $diasTranscurridos = now()->diffInDays($cita->fecha_hora);
                            $diasTranscurridos = (int) $diasTranscurridos;

                            $tiempoTranscurrido =
                                $diasTranscurridos > 0
                                    ? 'Hace ' . $diasTranscurridos . ' día' . ($diasTranscurridos != 1 ? 's' : '')
                                    : 'Hoy';
                        @endphp

                        <div class="cita-card {{ $cita->estado }}" data-cita-id="{{ $cita->id }}">
                            <div class="cita-header">
                                <div class="cita-date-time">
                                    <div class="date-badge {{ $cita->estado }}">
                                        <span class="day">{{ $cita->fecha_hora->format('d') }}</span>
                                        <span class="month">{{ $cita->fecha_hora->format('M') }}</span>
                                    </div>
                                    <div class="time-info">
                                        <div class="time">{{ $cita->fecha_hora->format('h:i A') }}</div>
                                        <div class="duration">
                                            <i class="fas fa-clock"></i>
                                            Duración: {{ $cita->servicios->sum('duracion_min') }} min
                                        </div>
                                        <div class="time-ago">{{ $tiempoTranscurrido }}</div>
                                        <div class="vehicle-info">
                                            <i class="fas fa-car"></i>
                                            {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                            @if ($cita->vehiculo->placa)
                                                - {{ $cita->vehiculo->placa }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="status-badge status-{{ str_replace('_', '-', $cita->estado) }}">
                                    {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                </span>
                            </div>

                            <div class="cita-details">
                                <div class="detail-section">
                                    <h4><i class="fas fa-tools"></i> Servicios Realizados</h4>
                                    <ul class="service-list">
                                        @foreach ($cita->servicios as $servicio)
                                            <li class="service-item">
                                                <span class="service-name">{{ $servicio->nombre }}</span>
                                                <span
                                                    class="service-price">${{ number_format($servicio->precio, 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div style="border-top: 2px solid #667eea; margin-top: 10px; padding-top: 10px;">
                                        <strong>Total Pagado:
                                            ${{ number_format($cita->servicios->sum('precio'), 2) }}</strong>
                                    </div>
                                </div>

                                @if ($cita->observaciones)
                                    <div class="detail-section">
                                        <h4><i class="fas fa-comment"></i> Observaciones</h4>
                                        <p>{{ $cita->observaciones }}</p>
                                    </div>
                                @endif

                                <!-- Información adicional según el estado -->
                                @if ($cita->estado == 'finalizada')
                                    <div class="detail-section">
                                        <h4><i class="fas fa-check-circle"></i> Servicio Completado</h4>
                                        <p><strong>Fecha de finalización:</strong>
                                            {{ $cita->fecha_hora->format('d/m/Y h:i A') }}</p>
                                        @if ($cita->updated_at != $cita->created_at)
                                            <p><strong>Última actualización:</strong>
                                                {{ $cita->updated_at->format('d/m/Y h:i A') }}</p>
                                        @endif
                                    </div>
                                @elseif ($cita->estado == 'cancelada')
                                    <div class="detail-section">
                                        <h4><i class="fas fa-times-circle"></i> Cita Cancelada</h4>
                                        <p><strong>Fecha original:</strong>
                                            {{ $cita->fecha_hora->format('d/m/Y h:i A') }}</p>
                                        @if ($cita->updated_at != $cita->created_at)
                                            <p><strong>Cancelada el:</strong>
                                                {{ $cita->updated_at->format('d/m/Y h:i A') }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- No hay acciones disponibles en el historial -->
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="pagination-wrapper">
                    {{ $citas->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h3>Sin historial de citas</h3>
                    <p>No tienes citas finalizadas o canceladas que coincidan con los filtros seleccionados</p>
                    <a href="{{ route('cliente.citas.proximas') }}" class="btn btn-primary">
                        <i class="fas fa-clock"></i> Ver Próximas Citas
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para limpiar filtros
        function limpiarFiltros() {
            // Resetear el formulario
            document.getElementById('filtrosForm').reset();

            // Redirigir a la URL base sin parámetros
            window.location.href = '{{ route('cliente.citas.historial') }}';
        }


        // Envío del formulario de filtros
        document.getElementById('filtrosForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Mostrar overlay de carga
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(loadingOverlay);

            // Obtener valores actuales
            const estado = document.getElementById('estado').value;
            const fecha_desde = document.getElementById('fecha_desde').value;
            const fecha_hasta = document.getElementById('fecha_hasta').value;
            const vehiculo_id = document.getElementById('vehiculo_id').value;

            // Construir URL con parámetros
            let url = '{{ route('cliente.citas.historial') }}?';

            if (estado) url += `estado=${estado}&`;
            if (fecha_desde) url += `fecha_desde=${fecha_desde}&`;
            if (fecha_hasta) url += `fecha_hasta=${fecha_hasta}&`;
            if (vehiculo_id) url += `vehiculo_id=${vehiculo_id}&`;

            // Eliminar el último & si existe
            url = url.replace(/&$/, '');

            // Recargar la página con los nuevos parámetros
            window.location.href = url;
        });

        // Auto-filtrado cuando cambian los selects - VERSIÓN SIN DUPLICADOS
        const filters = ['estado', 'vehiculo_id', 'fecha_desde', 'fecha_hasta'];
        filters.forEach(filterId => {
            const element = document.getElementById(filterId);
            if (element) {
                element.addEventListener('change', function() {
                    document.getElementById('filtrosForm').dispatchEvent(new Event('submit'));
                });
            }
        });

        // Función para mostrar detalles adicionales
        function mostrarDetallesCita(citaId) {
            console.log('Mostrando detalles de la cita:', citaId);
            // Aquí podrías implementar un modal con más detalles si es necesario
        }

        // Animación de entrada para las cards
        document.addEventListener('DOMContentLoaded', function() {
            const citaCards = document.querySelectorAll('.cita-card');
            citaCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>
