<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente - AutoGest Carwash</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .welcome-section h1 {
            color: #4facfe;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .welcome-section p {
            color: #666;
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 15px;
        }

        .stat-card.vehiculos .icon {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card.citas .icon {
            background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%);
        }

        .stat-card.pendientes .icon {
            background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        }

        .stat-card.confirmadas .icon {
            background: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Main Content Grid */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .content-card h2 {
            color: #4facfe;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Vehicles Section */
        .vehicle-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .vehicle-item:hover {
            border-color: #4facfe;
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .vehicle-icon {
            font-size: 2rem;
            color: #4facfe;
            margin-right: 15px;
        }

        .vehicle-info h4 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .vehicle-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .vehicle-actions {
            margin-left: auto;
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 8px 15px;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        /* Appointments Section */
        .appointment-item {
            padding: 15px;
            border-left: 4px solid #4facfe;
            background: #f8f9fa;
            border-radius: 0 10px 10px 0;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .appointment-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .appointment-date {
            color: #4facfe;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .appointment-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmada {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-finalizada {
            background: #d4edda;
            color: #155724;
        }

        .appointment-vehicle {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .appointment-services {
            color: #666;
            font-size: 0.9rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: #4facfe;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: #333;
        }

        /* Quick Actions */
        .quick-actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .quick-actions h2 {
            color: #4facfe;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-card {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
            color: white;
        }

        .action-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .action-card h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .action-card p {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .welcome-section h1 {
                font-size: 1.5rem;
            }

            .main-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .content-card {
                padding: 20px;
            }

            .vehicle-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .vehicle-actions {
                margin-left: 0;
                width: 100%;
                justify-content: flex-start;
            }

            .appointment-header {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div class="welcome-section">
                <h1>¡Bienvenido, {{ $user->nombre ?? 'Cliente' }}!</h1>
                <p>Gestiona tus vehículos y citas de lavado</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('cliente.citas') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i>
                    Nueva Cita
                </a>
                <a href="{{ route('perfil') }}" class="btn btn-outline">
                    <i class="fas fa-user"></i>
                    Mi Perfil
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card vehiculos">
                <div class="icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>{{ $stats['total_vehiculos'] ?? 0 }}</h3>
                <p>Vehículos Registrados</p>
            </div>
            <div class="stat-card citas">
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>{{ $stats['total_citas'] ?? 0 }}</h3>
                <p>Total de Citas</p>
            </div>
            <div class="stat-card pendientes">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>{{ $stats['citas_pendientes'] ?? 0 }}</h3>
                <p>Citas Pendientes</p>
            </div>
            <div class="stat-card confirmadas">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>{{ $stats['citas_confirmadas'] ?? 0 }}</h3>
                <p>Citas Confirmadas</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Mis Vehículos -->
            <div class="content-card">
                <h2>
                    <i class="fas fa-car"></i>
                    Mis Vehículos
                </h2>
                
                @if(isset($mis_vehiculos) && count($mis_vehiculos) > 0)
                    @foreach($mis_vehiculos as $vehiculo)
                    <div class="vehicle-item">
                        <div class="vehicle-icon">
                            @switch($vehiculo->tipo)
                                @case('sedan')
                                    <i class="fas fa-car"></i>
                                    @break
                                @case('pickup')
                                    <i class="fas fa-truck-pickup"></i>
                                    @break
                                @case('camion')
                                    <i class="fas fa-truck"></i>
                                    @break
                                @case('moto')
                                    <i class="fas fa-motorcycle"></i>
                                    @break
                                @default
                                    <i class="fas fa-car"></i>
                            @endswitch
                        </div>
                        <div class="vehicle-info">
                            <h4>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h4>
                            <p>Placa: {{ $vehiculo->placa }} • Color: {{ $vehiculo->color }}</p>
                        </div>
                        <div class="vehicle-actions">
                            <a href="#" class="btn btn-small btn-primary">
                                <i class="fas fa-calendar-plus"></i>
                                Agendar
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-car"></i>
                        <h3>No tienes vehículos registrados</h3>
                        <p>Agrega tu primer vehículo para comenzar a agendar citas</p>
                        <a href="{{ route('cliente.vehiculos') }}" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fas fa-plus"></i>
                            Agregar Vehículo
                        </a>
                    </div>
                @endif
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('cliente.vehiculos') }}" class="btn btn-outline">
                        <i class="fas fa-list"></i>
                        Ver Todos los Vehículos
                    </a>
                </div>
            </div>

            <!-- Citas Recientes -->
            <div class="content-card">
                <h2>
                    <i class="fas fa-calendar-alt"></i>
                    Citas Recientes
                </h2>
                
                @if(isset($mis_citas) && count($mis_citas) > 0)
                    @foreach($mis_citas as $cita)
                    <div class="appointment-item">
                        <div class="appointment-header">
                            <div class="appointment-date">
                                {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}
                            </div>
                            <span class="appointment-status status-{{ $cita->estado }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </div>
                        <div class="appointment-vehicle">
                            {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }} - {{ $cita->vehiculo->placa }}
                        </div>
                        <div class="appointment-services">
                            @if($cita->servicios && count($cita->servicios) > 0)
                                Servicios: {{ $cita->servicios->pluck('nombre')->join(', ') }}
                            @else
                                Sin servicios especificados
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>No tienes citas programadas</h3>
                        <p>Agenda tu primera cita de lavado</p>
                        <a href="{{ route('cliente.citas') }}" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fas fa-calendar-plus"></i>
                            Agendar Cita
                        </a>
                    </div>
                @endif
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('cliente.citas') }}" class="btn btn-outline">
                        <i class="fas fa-history"></i>
                        Ver Historial Completo
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>
                <i class="fas fa-bolt"></i>
                Acciones Rápidas
            </h2>
            <div class="actions-grid">
                <a href="{{ route('cliente.citas') }}" class="action-card">
                    <i class="fas fa-calendar-plus"></i>
                    <h4>Agendar Cita</h4>
                    <p>Programa un nuevo servicio</p>
                </a>
                <a href="{{ route('cliente.vehiculos') }}" class="action-card">
                    <i class="fas fa-car"></i>
                    <h4>Mis Vehículos</h4>
                    <p>Gestiona tus vehículos</p>
                </a>
                <a href="#" class="action-card">
                    <i class="fas fa-history"></i>
                    <h4>Historial</h4>
                    <p>Revisa servicios anteriores</p>
                </a>
                <a href="#" class="action-card">
                    <i class="fas fa-credit-card"></i>
                    <h4>Pagos</h4>
                    <p>Gestiona tus pagos</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>