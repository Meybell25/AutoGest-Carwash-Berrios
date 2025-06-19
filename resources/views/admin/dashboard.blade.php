<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);
            --secondary-gradient: linear-gradient(45deg, #4ca1af 0%, #2c3e50 100%);
            --success-gradient: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%);
            --warning-gradient: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
            --danger-gradient: linear-gradient(45deg, #ff758c 0%, #ff7eb3 100%);
            --info-gradient: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #333;
            --text-secondary: #666;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
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

        .welcome-section p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .welcome-stats {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .welcome-stat {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            padding: 10px 15px;
            border-radius: 10px;
            text-align: center;
            min-width: 80px;
        }

        .welcome-stat .number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #4facfe;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Botones */
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

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        /* Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .main-section, .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Cards */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            padding: 20px 25px 0;
            border-bottom: 2px solid #f1f3f4;
            margin-bottom: 20px;
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

        /* Stats Cards */
        .admin-stat-card {
            background: var(--glass-bg);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-soft);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        
        .admin-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        
        .stat-card-primary { border-left-color: #4ca1af; }
        .stat-card-success { border-left-color: #43e97b; }
        .stat-card-warning { border-left-color: #fa709a; }
        .stat-card-danger { border-left-color: #ff758c; }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: white;
        }
        
        .icon-primary { background: var(--primary-gradient); }
        .icon-success { background: var(--success-gradient); }
        .icon-warning { background: var(--warning-gradient); }
        .icon-danger { background: var(--danger-gradient); }

        /* Tablas */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .admin-table th {
            background: #f1f3f4;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .admin-table tr:hover {
            background: #f8f9fa;
        }
        
        .table-actions {
            display: flex;
            gap: 5px;
        }
        
        .table-btn {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .table-btn:hover {
            transform: scale(1.1);
        }
        
        .btn-view { background: #4facfe; color: white; }
        .btn-edit { background: #ffc107; color: white; }
        .btn-delete { background: #dc3545; color: white; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-primary { background: #4ca1af; color: white; }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-info { background: #17a2b8; color: white; }

        /* Tabs */
        .tab-container {
            margin-top: 20px;
        }
        
        .tab-buttons {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            position: relative;
        }
        
        .tab-button.active {
            color: #4ca1af;
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: #4ca1af;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }

        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        /* Service items */
        .service-history-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #f1f3f4;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .service-history-item:hover {
            border-color: #4facfe;
            background: #f8f9fa;
        }

        .service-icon {
            background: var(--success-gradient);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .service-details {
            flex: 1;
        }

        .service-details h4 {
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        /* Notificaciones */
        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .notification-item.unread {
            background: linear-gradient(45deg, #4facfe10, #00f2fe10);
            border-left: 4px solid #4facfe;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
            color: white;
        }

        .notification-icon.info { background: var(--info-gradient); }
        .notification-icon.success { background: var(--success-gradient); }
        .notification-icon.warning { background: var(--warning-gradient); }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: var(--glass-bg);
            margin: 5% auto;
            padding: 25px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #4facfe;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        /* Footer */
        .footer {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            margin-top: 30px;
            box-shadow: var(--shadow-soft);
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .footer-content {
            padding: 40px 30px;
            text-align: center;
        }

        .footer-brand h3 {
            font-size: 28px;
            font-weight: 700;
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 8px 0;
        }

        .footer-slogan {
            font-size: 14px;
            color: var(--text-secondary);
            font-style: italic;
            margin-bottom: 25px;
        }

        .footer-info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
            font-size: 15px;
        }

        .info-item i {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary-gradient);
            color: white;
            border-radius: 50%;
            font-size: 10px;
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin: 20px 0;
        }

        .footer-copyright {
            color: var(--text-secondary);
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 25px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 10px;
            }

            .header {
                padding: 15px 20px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-section h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }

            .welcome-stats {
                justify-content: center;
                flex-wrap: wrap;
            }

            .header-actions {
                justify-content: center;
            }

            .btn {
                min-width: 140px;
                justify-content: center;
            }

            .footer-info {
                flex-direction: column;
                gap: 15px;
            }

            .footer-content {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            .welcome-stats {
                flex-direction: column;
            }

            .welcome-stat {
                width: 100%;
            }

            .form-group input, textarea, select {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <!-- Contenido del dashboard admin (igual que antes) -->
    <div class="dashboard-container">
        <!-- Header con bienvenida personalizada -->
        <div class="header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <div class="welcome-icon">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        Panel de Administración
                    </h1>
                    <p>Gestiona todos los aspectos de tu negocio de lavado</p>
                    <div class="welcome-stats">
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['usuarios_totales'] ?? 0 }}</span>
                            <span class="label">Usuarios</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['citas_hoy'] ?? 0 }}</span>
                            <span class="label">Citas Hoy</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">${{ number_format($stats['ingresos_hoy'] ?? 0, 2) }}</span>
                            <span class="label">Ingresos Hoy</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.citas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Nueva Cita
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="btn btn-success">
                        <i class="fas fa-chart-bar"></i>
                        Reportes
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
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="dashboard-grid">
            <div class="main-section">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="admin-stat-card stat-card-primary">
                        <div class="stat-icon icon-primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value">{{ $stats['citas_hoy'] ?? 0 }}</div>
                        <div class="stat-label">Citas Hoy</div>
                    </div>
                    <div class="admin-stat-card stat-card-success">
                        <div class="stat-icon icon-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">${{ number_format($stats['ingresos_hoy'] ?? 0, 2) }}</div>
                        <div class="stat-label">Ingresos Hoy</div>
                    </div>
                    <div class="admin-stat-card stat-card-warning">
                        <div class="stat-icon icon-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value">{{ $stats['nuevos_clientes_mes'] ?? 0 }}</div>
                        <div class="stat-label">Nuevos Clientes (Mes)</div>
                    </div>
                    <div class="admin-stat-card stat-card-danger">
                        <div class="stat-icon icon-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-value">{{ $stats['citas_canceladas_mes'] ?? 0 }}</div>
                        <div class="stat-label">Cancelaciones (Mes)</div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            Rendimiento Mensual
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="tab-container">
                            <div class="tab-buttons">
                                <button class="tab-button active" onclick="openTab(event, 'ingresosTab')">Ingresos</button>
                                <button class="tab-button" onclick="openTab(event, 'citasTab')">Citas</button>
                                <button class="tab-button" onclick="openTab(event, 'serviciosTab')">Servicios</button>
                            </div>
                            
                            <div id="ingresosTab" class="tab-content active">
                                <div class="chart-container">
                                    <canvas id="ingresosChart"></canvas>
                                </div>
                            </div>
                            
                            <div id="citasTab" class="tab-content">
                                <div class="chart-container">
                                    <canvas id="citasChart"></canvas>
                                </div>
                            </div>
                            
                            <div id="serviciosTab" class="tab-content">
                                <div class="chart-container">
                                    <canvas id="serviciosChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimas Citas -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            Últimas Citas
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="search-filter-container">
                            <div class="search-box">
                                <input type="text" placeholder="Buscar citas..." class="form-control">
                            </div>
                            <div class="filter-select">
                                <select class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="finalizada">Finalizada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                        
                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Vehículo</th>
                                        <th>Fecha/Hora</th>
                                        <th>Servicios</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ultimas_citas as $cita)
                                    <tr>
                                        <td>#{{ $cita->id }}</td>
                                        <td>{{ $cita->cliente->nombre }}</td>
                                        <td>{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @foreach($cita->servicios as $servicio)
                                                <span class="badge badge-primary">{{ $servicio->nombre }}</span>
                                            @endforeach
                                        </td>
                                        <td>${{ number_format($cita->servicios->sum('precio'), 2) }}</td>
                                        <td>
                                            @if($cita->estado == 'pendiente')
                                                <span class="badge badge-warning">Pendiente</span>
                                            @elseif($cita->estado == 'en_proceso')
                                                <span class="badge badge-info">En Proceso</span>
                                            @elseif($cita->estado == 'finalizada')
                                                <span class="badge badge-success">Finalizada</span>
                                            @else
                                                <span class="badge badge-danger">Cancelada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <button class="table-btn btn-view" title="Ver" onclick="verDetalleCita({{ $cita->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="table-btn btn-edit" title="Editar" onclick="editarCita({{ $cita->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Cancelar" onclick="cancelarCita({{ $cita->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination">
                            <a href="#" class="page-link">&laquo;</a>
                            <a href="#" class="page-link active">1</a>
                            <a href="#" class="page-link">2</a>
                            <a href="#" class="page-link">3</a>
                            <a href="#" class="page-link">&raquo;</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Sidebar -->
            <div class="sidebar-section">
                <!-- Resumen de Usuarios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            Resumen de Usuarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: #4ca1af;">{{ $stats['usuarios_totales'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Usuarios Totales</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: #28a745;">{{ $stats['nuevos_clientes_mes'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Nuevos (Mes)</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: var(--text-primary);">Distribución por Rol</h3>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="usuariosChart"></canvas>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.usuarios') }}" class="btn btn-outline" style="width: 100%;">
                            <i class="fas fa-list"></i> Ver Todos los Usuarios
                        </a>
                    </div>
                </div>

                <!-- Servicios Populares -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-star"></i>
                            </div>
                            Servicios Populares
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach($servicios_populares as $servicio)
                        <div class="service-history-item" style="margin-bottom: 10px;">
                            <div class="service-icon" style="background: var(--secondary-gradient);">
                                <i class="fas fa-spray-can"></i>
                            </div>
                            <div class="service-details">
                                <h4>{{ $servicio->nombre }}</h4>
                                <p>${{ number_format($servicio->precio, 2) }} - {{ $servicio->duracion }} min</p>
                                <p><i class="fas fa-chart-line"></i> {{ $servicio->veces_contratado }} veces este mes</p>
                            </div>
                            <button class="btn btn-sm btn-outline" onclick="editarServicio({{ $servicio->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        @endforeach
                        
                        <button class="btn btn-primary" style="width: 100%; margin-top: 10px;" onclick="nuevoServicio()">
                            <i class="fas fa-plus"></i> Agregar Servicio
                        </button>
                    </div>
                </div>

                <!-- Notificaciones del Sistema -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            Alertas del Sistema
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        @foreach($alertas as $alerta)
                        <div class="notification-item {{ $alerta->leida ? 'read' : 'unread' }}">
                            <div class="notification-icon {{ $alerta->tipo }}">
                                <i class="fas fa-{{ $alerta->icono }}"></i>
                            </div>
                            <div class="notification-content">
                                <h4>{{ $alerta->titulo }}</h4>
                                <p>{{ $alerta->mensaje }}</p>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(count($alertas) == 0)
                        <div class="empty-state" style="padding: 20px;">
                            <i class="fas fa-check-circle"></i>
                            <h3>No hay alertas</h3>
                            <p>No hay notificaciones importantes en este momento</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalle de cita -->
    <div id="detalleCitaModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <span class="close-modal" onclick="closeModal('detalleCitaModal')">&times;</span>
            <div id="detalleCitaContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>

    <!-- Modal para editar cita -->
    <div id="editarCitaModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <span class="close-modal" onclick="closeModal('editarCitaModal')">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;">
                <i class="fas fa-edit"></i> Editar Cita
            </h2>
            <form id="editarCitaForm">
                <!-- Formulario se llenará dinámicamente -->
            </form>
        </div>
    </div>

    <!-- Modal para nuevo/editar servicio -->
    <div id="servicioModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <span class="close-modal" onclick="closeModal('servicioModal')">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;" id="servicioModalTitle">
                <i class="fas fa-plus"></i> Nuevo Servicio
            </h2>
            <form id="servicioForm">
                @csrf
                <input type="hidden" id="servicio_id" name="id">
                
                <div class="form-group">
                    <label for="servicio_nombre">Nombre del Servicio:</label>
                    <input type="text" id="servicio_nombre" name="nombre" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="servicio_descripcion">Descripción:</label>
                    <textarea id="servicio_descripcion" name="descripcion" rows="3" class="form-control"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="servicio_precio">Precio ($):</label>
                        <input type="number" step="0.01" id="servicio_precio" name="precio" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="servicio_duracion">Duración (min):</label>
                        <input type="number" id="servicio_duracion" name="duracion" required class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="servicio_activo">Estado:</label>
                    <select id="servicio_activo" name="activo" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Guardar Servicio
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <h3><i class="fas fa-car-wash"></i> AutoGest Carwash Berrios</h3>
                <p class="footer-slogan">✨ "Sistema de Administración Integral" ✨</p>
            </div>

            <div class="footer-info">
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <span>75855197</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <a href="https://maps.app.goo.gl/PhHLaky3ZPrhtdb88" target="_blank" class="location-link">
                        Ver ubicación en mapa
                    </a>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Lun - Sáb: 7:00 AM - 6:00 PM | Dom: Cerrado</span>
                </div>
            </div>

            <div class="footer-divider"></div>

            <p class="footer-copyright">
                &copy; 2025 AutoGest Carwash Berrios. Todos los derechos reservados.
                <br>Versión del sistema: 2.5.1
            </p>
        </div>
    </footer>

    <script>
        // Configuración global de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Inicializar gráficos
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de ingresos mensuales
            const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');
            const ingresosChart = new Chart(ingresosCtx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ingresos 2023',
                        data: [1200, 1900, 1500, 2000, 2200, 2500, 2800, 2600, 2300, 2000, 1800, 2100],
                        backgroundColor: 'rgba(76, 161, 175, 0.2)',
                        borderColor: 'rgba(76, 161, 175, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de citas mensuales
            const citasCtx = document.getElementById('citasChart').getContext('2d');
            const citasChart = new Chart(citasCtx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Citas Completadas',
                        data: [45, 60, 55, 70, 75, 80, 85, 80, 70, 65, 60, 65],
                        backgroundColor: 'rgba(67, 233, 123, 0.7)',
                        borderColor: 'rgba(67, 233, 123, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Citas Canceladas',
                        data: [5, 8, 6, 10, 7, 5, 4, 8, 10, 7, 9, 6],
                        backgroundColor: 'rgba(255, 117, 140, 0.7)',
                        borderColor: 'rgba(255, 117, 140, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Gráfico de servicios populares
            const serviciosCtx = document.getElementById('serviciosChart').getContext('2d');
            const serviciosChart = new Chart(serviciosCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Lavado Completo', 'Lavado Premium', 'Detallado VIP', 'Aspirado', 'Encerado'],
                    datasets: [{
                        data: [35, 25, 15, 15, 10],
                        backgroundColor: [
                            'rgba(76, 161, 175, 0.7)',
                            'rgba(67, 233, 123, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(23, 162, 184, 0.7)',
                            'rgba(108, 117, 125, 0.7)'
                        ],
                        borderColor: [
                            'rgba(76, 161, 175, 1)',
                            'rgba(67, 233, 123, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(108, 117, 125, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

            // Gráfico de distribución de usuarios
            const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
            const usuariosChart = new Chart(usuariosCtx, {
                type: 'pie',
                data: {
                    labels: ['Clientes', 'Empleados', 'Administradores'],
                    datasets: [{
                        data: [85, 10, 5],
                        backgroundColor: [
                            'rgba(76, 161, 175, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(220, 53, 69, 0.7)'
                        ],
                        borderColor: [
                            'rgba(76, 161, 175, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });

        // Funciones para pestañas
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            const tabButtons = document.getElementsByClassName('tab-button');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        // Funciones para modales
        function verDetalleCita(citaId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            const detalleContent = `
                <h2 style="color: #4facfe; margin-bottom: 20px;">
                    <i class="fas fa-calendar-check"></i> Detalle de Cita #${citaId}
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                            <i class="fas fa-user"></i> Información del Cliente
                        </h3>
                        <p><strong>Nombre:</strong> Juan Pérez</p>
                        <p><strong>Teléfono:</strong> 5555-1234</p>
                        <p><strong>Email:</strong> juan@example.com</p>
                        <p><strong>Cliente desde:</strong> Ene 2023</p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </h3>
                        <p><strong>Marca/Modelo:</strong> Toyota Corolla</p>
                        <p><strong>Año:</strong> 2020</p>
                        <p><strong>Color:</strong> Rojo</p>
                        <p><strong>Placa:</strong> P123456</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                        <i class="fas fa-concierge-bell"></i> Servicios Contratados
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f1f3f4;">
                                <th style="padding: 10px; text-align: left;">Servicio</th>
                                <th style="padding: 10px; text-align: right;">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">Lavado Completo</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">$25.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">Aspirado Interior</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">$15.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; font-weight: bold;">Total</td>
                                <td style="padding: 10px; font-weight: bold; text-align: right;">$40.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                        <i class="fas fa-info-circle"></i> Información Adicional
                    </h3>
                    <p><strong>Fecha/Hora:</strong> 15 Jun 2023 - 10:00 AM</p>
                    <p><strong>Estado:</strong> <span class="badge badge-success">Finalizada</span></p>
                    <p><strong>Empleado asignado:</strong> Carlos López</p>
                    <p><strong>Observaciones del cliente:</strong> Por favor prestar atención a las manchas en los asientos traseros.</p>
                    <p><strong>Observaciones del empleado:</strong> Se detectó pequeño rayón en la puerta derecha, cliente fue notificado.</p>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button class="btn btn-outline" onclick="imprimirRecibo(${citaId})">
                        <i class="fas fa-print"></i> Imprimir Recibo
                    </button>
                    <button class="btn btn-primary" onclick="editarCita(${citaId})">
                        <i class="fas fa-edit"></i> Editar Cita
                    </button>
                </div>
            `;
            
            document.getElementById('detalleCitaContent').innerHTML = detalleContent;
            document.getElementById('detalleCitaModal').style.display = 'block';
        }

        function editarCita(citaId) {
            // Simulación de formulario - en una aplicación real harías una petición AJAX
            const formContent = `
                <div class="form-group">
                    <label for="edit_cliente">Cliente:</label>
                    <select id="edit_cliente" class="form-control" required>
                        <option value="1" selected>Juan Pérez</option>
                        <option value="2">María González</option>
                        <option value="3">Carlos López</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_vehiculo">Vehículo:</label>
                    <select id="edit_vehiculo" class="form-control" required>
                        <option value="1" selected>Toyota Corolla (P123456)</option>
                        <option value="2">Honda Civic (P654321)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_fecha">Fecha:</label>
                    <input type="date" id="edit_fecha" class="form-control" value="2023-06-15" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_hora">Hora:</label>
                    <input type="time" id="edit_hora" class="form-control" value="10:00" required>
                </div>
                
                <div class="form-group">
                    <label>Servicios:</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <input type="checkbox" id="serv1" checked>
                            <label for="serv1">Lavado Completo ($25.00)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="serv2" checked>
                            <label for="serv2">Aspirado Interior ($15.00)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="serv3">
                            <label for="serv3">Encerado ($20.00)</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_empleado">Empleado Asignado:</label>
                    <select id="edit_empleado" class="form-control" required>
                        <option value="1" selected>Carlos López</option>
                        <option value="2">Ana Martínez</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_estado">Estado:</label>
                    <select id="edit_estado" class="form-control" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="finalizada" selected>Finalizada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_observaciones">Observaciones:</label>
                    <textarea id="edit_observaciones" rows="3" class="form-control">Por favor prestar atención a las manchas en los asientos traseros.</textarea>
                </div>
                
                <button type="button" class="btn btn-success" style="width: 100%;" onclick="guardarCambiosCita(${citaId})">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            `;
            
            document.getElementById('editarCitaForm').innerHTML = formContent;
            document.getElementById('detalleCitaModal').style.display = 'none';
            document.getElementById('editarCitaModal').style.display = 'block';
        }

        function cancelarCita(citaId) {
            Swal.fire({
                title: '¿Cancelar esta cita?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, volver'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí iría la petición AJAX para cancelar la cita
                    Toast.fire({
                        icon: 'success',
                        title: 'Cita cancelada correctamente'
                    });
                    
                    // Simulación de recarga de datos
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            });
        }

        function guardarCambiosCita(citaId) {
            // Aquí iría la petición AJAX para guardar los cambios
            Toast.fire({
                icon: 'success',
                title: 'Cambios guardados correctamente'
            });
            
            closeModal('editarCitaModal');
            
            // Simulación de recarga de datos
            setTimeout(() => {
                verDetalleCita(citaId);
            }, 500);
        }

        function imprimirRecibo(citaId) {
            // Aquí iría la lógica para imprimir el recibo
            window.open(`/admin/citas/${citaId}/recibo`, '_blank');
        }

        function nuevoServicio() {
            document.getElementById('servicioModalTitle').innerHTML = '<i class="fas fa-plus"></i> Nuevo Servicio';
            document.getElementById('servicio_id').value = '';
            document.getElementById('servicio_nombre').value = '';
            document.getElementById('servicio_descripcion').value = '';
            document.getElementById('servicio_precio').value = '';
            document.getElementById('servicio_duracion').value = '';
            document.getElementById('servicio_activo').value = '1';
            document.getElementById('servicioModal').style.display = 'block';
        }

        function editarServicio(servicioId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            document.getElementById('servicioModalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Servicio';
            document.getElementById('servicio_id').value = servicioId;
            document.getElementById('servicio_nombre').value = 'Lavado Completo';
            document.getElementById('servicio_descripcion').value = 'Lavado exterior e interior completo con aspirado y limpieza de tapicería';
            document.getElementById('servicio_precio').value = '25.00';
            document.getElementById('servicio_duracion').value = '30';
            document.getElementById('servicio_activo').value = '1';
            document.getElementById('servicioModal').style.display = 'block';
        }

        // Manejar envío del formulario de servicio
        document.getElementById('servicioForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Aquí iría la petición AJAX para guardar el servicio
            const isNew = document.getElementById('servicio_id').value === '';
            
            Toast.fire({
                icon: 'success',
                title: `Servicio ${isNew ? 'creado' : 'actualizado'} correctamente`
            });
            
            closeModal('servicioModal');
            
            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });

        // Función para cerrar modales
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Cerrar modales al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal('detalleCitaModal');
                closeModal('editarCitaModal');
                closeModal('servicioModal');
            }
        });
    </script>
</body>
</html>