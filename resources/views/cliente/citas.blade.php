<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - Cliente</title>
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

        .filters-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 25px;
            margin-bottom: 30px;
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
        }

        .cita-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
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
            background: var(--primary-gradient);
            color: white;
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: center;
            min-width: 80px;
            box-shadow: var(--shadow-soft);
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

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pendiente {
            background: linear-gradient(45deg, #ffeaa7, #fdcb6e);
            color: #d63031;
        }

        .status-confirmada {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            color: white;
        }

        .status-en-proceso {
            background: linear-gradient(45deg, #fd79a8, #e84393);
            color: white;
        }

        .status-finalizada {
            background: linear-gradient(45deg, #55efc4, #00b894);
            color: white;
        }

        .status-cancelada {
            background: linear-gradient(45deg, #fab1a0, #e17055);
            color: white;
        }

        .cita-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-section {
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: var(--border-radius);
            border-left: 4px solid #667eea;
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
            justify-content: between;
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

        .vehicle-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
        }

        .vehicle-info i {
            color: #667eea;
        }

        .cita-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #dc3545;
            color: #dc3545;
        }

        .btn-outline:hover {
            background: #dc3545;
            color: white;
        }

        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
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

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.3);
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Estilos para el selector de vista */
        .view-switcher {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
            background: var(--glass-bg);
            border-radius: var(--border-radius-lg);
            padding: 10px;
            box-shadow: var(--shadow-soft);
        }

        .view-switch-btn {
            padding: 10px 20px;
            border: none;
            background: none;
            font-weight: 600;
            cursor: pointer;
            color: var(--text-secondary);
            transition: var(--transition);
            border-radius: var(--border-radius);
        }

        .view-switch-btn.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .view-switch-btn:first-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .view-switch-btn:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Estilos para citas urgentes */
        .cita-card.urgent {
            border-left: 4px solid #dc3545;
            animation: pulseBorder 2s infinite;
        }

        @keyframes pulseBorder {
            0% {
                border-left-color: #dc3545;
            }

            50% {
                border-left-color: #ff6b6b;
            }

            100% {
                border-left-color: #dc3545;
            }
        }

        .cita-card.coming-soon {
            border-left: 4px solid #fd7e14;
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
            
            .view-switcher {
                flex-direction: column;
            }
            
            .view-switch-btn {
                border-radius: var(--border-radius);
                margin: 2px 0;
            }
            
            .view-switch-btn:first-child,
            .view-switch-btn:last-child {
                border-radius: var(--border-radius);
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
            <h1><i class="fas fa-calendar-alt"></i> 
                @if(request('tipo') == 'proximas')
                    Próximas Citas
                @elseif(request('tipo') == 'pasadas')
                    Historial de Citas
                @else
                    Todas mis Citas
                @endif
            </h1>
            <p>
                @if(request('tipo') == 'proximas')
                    Citas programadas para los próximos días
                @elseif(request('tipo') == 'pasadas')
                    Registro de citas anteriores
                @else
                    Todas tus citas, pasadas y futuras
                @endif
            </p>
        </div>
        
        <!-- Selector de vista mejorado -->
        <div class="view-switcher">
            <button class="view-switch-btn {{ request('tipo') == 'proximas' ? 'active' : '' }}"
                onclick="window.location.href='{{ route('cliente.citas', ['tipo' => 'futuras']) }}'">
                <i class="fas fa-clock"></i> Próximas
            </button>
            <button class="view-switch-btn {{ request('tipo') == 'pasadas' ? 'active' : '' }}"
                onclick="window.location.href='{{ route('cliente.citas', ['tipo' => 'pasadas']) }}'">
                <i class="fas fa-history"></i> Historial
            </button>
            <button class="view-switch-btn {{ !request('tipo') ? 'active' : '' }}"
                onclick="window.location.href='{{ route('cliente.citas') }}'">
                <i class="fas fa-list"></i> Todas
            </button>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <form id="filtrosForm">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="estado">
                            <i class="fas fa-filter"></i> Estado
                        </label>
                        <select name="estado" id="estado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="finalizada">Finalizada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="fecha_desde">
                            <i class="fas fa-calendar-day"></i> Desde
                        </label>
                        <input type="date" name="fecha_desde" id="fecha_desde">
                    </div>

                    <div class="filter-group">
                        <label for="fecha_hasta">
                            <i class="fas fa-calendar-day"></i> Hasta
                        </label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta">
                    </div>

                    <div class="filter-group">
                        <label for="vehiculo_id">
                            <i class="fas fa-car"></i> Vehículo
                        </label>
                        <select name="vehiculo_id" id="vehiculo_id">
                            <option value="">Todos los vehículos</option>
                            @foreach (auth()->user()->vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}">
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

            <!-- Resumen de estadísticas -->
            <div class="stats-summary">
                <div class="stat-item">
                    <span class="stat-number" id="total-citas">{{ $citas->total() }}</span>
                    <span class="stat-label">Total Citas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="pendientes">{{ $citas->where('estado', 'pendiente')->count() }}</span>
                    <span class="stat-label">Pendientes</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"
                        id="confirmadas">{{ $citas->where('estado', 'confirmada')->count() }}</span>
                    <span class="stat-label">Confirmadas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"
                        id="finalizadas">{{ $citas->where('estado', 'finalizada')->count() }}</span>
                    <span class="stat-label">Finalizadas</span>
                </div>
            </div>
        </div>

        <!-- Lista de citas -->
        <div id="citas-container">
            @if ($citas->count() > 0)
                <div class="citas-grid">
                    @foreach ($citas as $cita)
                        @php
                            $isFuture = $cita->fecha_hora > now();
                            $daysDiff = $isFuture ? now()->diffInDays($cita->fecha_hora) : null;
                            
                            $cardClass = '';
                            if ($isFuture && $daysDiff <= 1) {
                                $cardClass = 'urgent';
                            } elseif ($isFuture && $daysDiff <= 3) {
                                $cardClass = 'coming-soon';
                            }
                        @endphp
                        
                        <div class="cita-card {{ $cardClass }}" data-cita-id="{{ $cita->id }}">
                            <div class="cita-header">
                                <div class="cita-date-time">
                                    <div class="date-badge">
                                        <span class="day">{{ $cita->fecha_hora->format('d') }}</span>
                                        <span class="month">{{ $cita->fecha_hora->format('M') }}</span>
                                    </div>
                                    <div class="time-info">
                                        <div class="time">{{ $cita->fecha_hora->format('h:i A') }}</div>
                                        <div class="duration">
                                            <i class="fas fa-clock"></i>
                                            Duración: {{ $cita->servicios->sum('duracion_min') }} min
                                        </div>
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
                                    <h4><i class="fas fa-tools"></i> Servicios</h4>
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
                                        <strong>Total:
                                            ${{ number_format($cita->servicios->sum('precio'), 2) }}</strong>
                                    </div>
                                </div>

                                @if ($cita->observaciones)
                                    <div class="detail-section">
                                        <h4><i class="fas fa-comment"></i> Observaciones</h4>
                                        <p>{{ $cita->observaciones }}</p>
                                    </div>
                                @endif
                            </div>

                            @if (in_array($cita->estado, ['pendiente', 'confirmada']))
                                <div class="cita-actions">
                                    <button class="btn btn-sm btn-warning" onclick="editCita({{ $cita->id }})">
                                        <i class="fas fa-edit"></i> Modificar
                                    </button>
                                    <button class="btn btn-sm btn-outline" onclick="cancelCita({{ $cita->id }})">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="pagination-wrapper">
                    {{ $citas->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No se encontraron citas</h3>
                    <p>No tienes citas que coincidan con los filtros seleccionados</p>
                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Agendar Nueva Cita
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Funciones para manejar las citas (editar, cancelar)
        function editCita(citaId) {
            fetch(`/cliente/citas/${citaId}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Abrir modal con los datos de la cita
                    openCitaModal();
                    
                    // Rellenar formulario con data.data
                    document.getElementById('form_cita_id').value = citaId;
                    document.getElementById('vehiculo_id').value = data.data.vehiculo_id;
                    // ... resto de campos
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        title: 'Cita cargada',
                        text: 'Puedes modificar los detalles de tu cita',
                        icon: 'success'
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message,
                    icon: 'error'
                });
            });
        }

        function cancelCita(citaId) {
            Swal.fire({
                title: '¿Cancelar cita?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, mantener'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/cliente/citas/${citaId}/cancelar`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Cancelada',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: error.message,
                            icon: 'error'
                        });
                    });
                }
            });
        }

        function limpiarFiltros() {
            document.getElementById('filtrosForm').reset();
            // Opcional: Enviar el formulario vacío para mostrar todas las citas
            document.getElementById('filtrosForm').submit();
        }

        // Envío del formulario de filtros con AJAX
        document.getElementById('filtrosForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            // Recargar la página con los nuevos parámetros
            window.location.href = '{{ route('cliente.citas') }}?' + params.toString();
        });

        // Auto-filtrado cuando cambian los selects
        const filters = ['estado', 'vehiculo_id'];
        filters.forEach(filterId => {
            document.getElementById(filterId).addEventListener('change', function() {
                document.getElementById('filtrosForm').dispatchEvent(new Event('submit'));
            });
        });
        
        // Función para abrir modal de cita (compatible con ambas vistas)
        function openCitaModal(vehiculoId = null) {
            // Implementación igual que en el dashboard
        }
    </script>
</body>

</html>