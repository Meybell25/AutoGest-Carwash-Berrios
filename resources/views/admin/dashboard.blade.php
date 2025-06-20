<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - AutoGest Carwash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #2e7d32;
        --secondary: #00695c;
        --accent: #ff8f00;
        --success: #388e3c;
        --warning: #d84315;
        --danger: #c62828;
        --info: #0277bd;
        --dark: #263238;
        --light: #eceff1;

        --primary-gradient: linear-gradient(135deg, #2e7d32 0%, #00695c 100%);
        --accent-gradient: linear-gradient(45deg, #ff8f00 0%, #ef6c00 100%);
        --secondary-gradient: linear-gradient(135deg, #00695c 0%, #0277bd 100%);
        --success-gradient: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
        --warning-gradient: linear-gradient(135deg, #d84315 0%, #bf360c 100%);
        --danger-gradient: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        --info-gradient: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        --dark-gradient: linear-gradient(135deg, #263238 0%, #37474f 100%);

        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
        --text-light: #ecf0f1;

        --bg-light: rgba(255, 255, 255, 0.95);
        --bg-dark: rgba(44, 62, 80, 0.95);
        --bg-surface: rgba(255, 255, 255, 0.98);

        --border-light: rgba(255, 255, 255, 0.2);
        --border-primary: rgba(39, 174, 96, 0.2);

        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);

        --transition: all 0.3s ease;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #3498db, #27ae60, #f39c12);
        background-attachment: fixed;
        min-height: 100vh;
        color: var(--text-primary);
        line-height: 1.7;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            radial-gradient(circle at 20% 80%, rgba(39, 174, 96, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(52, 152, 219, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(243, 156, 18, 0.05) 0%, transparent 50%);
        z-index: -1;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 25px;
    }

    /* Header */
    .header {
        background: var(--bg-light);
        padding: 30px 35px;
        border-radius: 24px;
        margin-bottom: 35px;
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
        height: 5px;
        background: var(--primary-gradient);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 25px;
    }

    .welcome-section h1 {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .welcome-icon {
        background: var(--primary-gradient);
        width: 55px;
        height: 55px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        box-shadow: var(--shadow-md);
    }

    .welcome-section p {
        color: var(--text-secondary);
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .welcome-stats {
        display: flex;
        gap: 25px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .welcome-stat {
        background: white;
        padding: 15px 20px;
        border-radius: 15px;
        text-align: center;
        min-width: 90px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .welcome-stat:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .welcome-stat .number {
        font-size: 1.4rem;
        font-weight: 800;
        background: var(--secondary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .welcome-stat .label {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--secondary-gradient);
        color: white;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    .btn-outline:hover {
        background: var(--primary);
        color: white;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-info {
        background: var(--info-gradient);
        color: white;
    }

    /* Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 35px;
        margin-bottom: 35px;
    }

    .main-section, .sidebar-section {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    /* Cards */
    .card {
        background: var(--bg-light);
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    .card-header {
        padding: 25px 30px 0;
        border-bottom: 2px solid var(--border-primary);
        margin-bottom: 25px;
    }

    .card-header h2 {
        background: var(--secondary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .card-header .icon {
        background: var(--secondary-gradient);
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }

    .card-body {
        padding: 0 30px 30px;
    }

    /* Stats Cards */
    .admin-stat-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: var(--shadow-sm);
        border-left: 5px solid;
        transition: var(--transition);
    }

    .stat-card-primary { border-left-color: var(--primary); }
    .stat-card-success { border-left-color: var(--success); }
    .stat-card-warning { border-left-color: var(--warning); }
    .stat-card-danger { border-left-color: var(--danger); }

    .stat-value {
        font-size: 2.8rem;
        font-weight: 800;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 20px;
        color: white;
    }

    .icon-primary { background: var(--primary-gradient); }
    .icon-success { background: var(--success-gradient); }
    .icon-warning { background: var(--warning-gradient); }
    .icon-danger { background: var(--info-gradient); }

    /* Tablas */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 15px;
        overflow: hidden;
    }

    .admin-table th {
        background: var(--light);
        padding: 18px 15px;
        text-align: left;
    }

    .admin-table td {
        padding: 18px 15px;
        border-bottom: 1px solid var(--border-primary);
        background: var(--bg-surface);
    }

    .table-actions {
        display: flex;
        gap: 8px;
    }

    .table-btn {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-view { background: var(--info-gradient); color: white; }
    .btn-edit { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; }
    .btn-delete { background: var(--danger-gradient); color: white; }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        color: white;
    }

    .badge-primary { background: var(--primary-gradient); }
    .badge-success { background: var(--success-gradient); }
    .badge-warning { background: var(--warning-gradient); }
    .badge-danger { background: var(--danger-gradient); }
    .badge-info { background: var(--info-gradient); }

    /* Footer */
    .footer {
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        margin-top: 40px;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .footer-content {
        padding: 50px 35px;
        text-align: center;
    }

    .footer-brand h3 {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 28px;
        font-weight: 700;
    }

    .footer-info {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .info-item i {
        background: var(--primary-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .social-icons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 20px 0;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        background: var(--primary);
    }

    /* Estilos para íconos */
    .card-header .icon i,
    .welcome-icon i,
    .btn i,
    .social-icon i,
    .info-item i {
        color: white !important;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .welcome-stats {
            justify-content: center;
        }
        
        .header-actions {
            justify-content: center;
        }
        
        .footer-info {
            flex-direction: column;
        }
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }
</style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header con bienvenida personalizada -->
        <div class="header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <div class="welcome-icon ">
                            <i class="fas fa-user-cog"></i>
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
                    <a href="{{ route('configuracion.index') }}" class="btn btn-info">
                        <i class="fas fa-cog"></i>
                        Configuración
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
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
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

                <!-- Gestión de Horarios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-business-time"></i>
                            </div>
                            Gestión de Horarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <h3 style="color: var(--text-primary);">Configuración de Horarios de Trabajo</h3>
                            <button class="btn btn-primary" onclick="mostrarModalHorario()">
                                <i class="fas fa-plus"></i> Agregar Horario
                            </button>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Día">Lunes</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(1)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(1)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Martes</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(2)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(2)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Miércoles</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(3)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(3)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Jueves</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(4)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(4)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Viernes</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(5)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(5)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Sábado</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(6)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(6)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Domingo</td>
                                        <td data-label="Hora Inicio">-</td>
                                        <td data-label="Hora Fin">-</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-danger">Inactivo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(0)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-success" title="Activar"
                                                    onclick="activarHorario(0)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            Rendimiento Mensual
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="tab-container">
                            <div class="tab-buttons">
                                <button class="tab-button active"
                                    onclick="openTab(event, 'ingresosTab')">Ingresos</button>
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
                                <i class="fas fa-calendar-day"></i>
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
                                    @foreach ($ultimas_citas as $cita)
                                        <tr>
                                            <td data-label="ID">#{{ $cita->id }}</td>
                                            <td data-label="Cliente">{{ $cita->usuario->nombre }}</td>
                                            <td data-label="Vehículo">{{ $cita->vehiculo->marca }}
                                                {{ $cita->vehiculo->modelo }}</td>
                                            <td data-label="Fecha/Hora">{{ $cita->fecha_hora->format('d/m/Y H:i') }}
                                            </td>
                                            <td data-label="Servicios">
                                                @foreach ($cita->servicios as $index => $servicio)
                                                    <span
                                                        class="badge service-badge-{{ ($index % 5) + 1 }}">{{ $servicio->nombre }}</span>
                                                @endforeach
                                            </td>
                                            <td data-label="Total">
                                                ${{ number_format($cita->servicios->sum('pivot.precio'), 2) }}</td>
                                            <td data-label="Estado">
                                                <span
                                                    class="badge badge-{{ $cita->estado == 'pendiente'
                                                        ? 'pendiente'
                                                        : ($cita->estado == 'confirmado'
                                                            ? 'confirmado'
                                                            : ($cita->estado == 'en_proceso'
                                                                ? 'en_proceso'
                                                                : ($cita->estado == 'finalizada'
                                                                    ? 'finalizada'
                                                                    : 'cancelada'))) }}">
                                                    {{ $cita->estado_formatted }}
                                                </span>
                                            </td>
                                            <td data-label="Acciones">
                                                <div class="table-actions">
                                                    <button class="table-btn btn-view" title="Ver"
                                                        onclick="verDetalleCita({{ $cita->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="table-btn btn-edit" title="Editar"
                                                        onclick="editarCita({{ $cita->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="table-btn btn-delete" title="Cancelar"
                                                        onclick="cancelarCita({{ $cita->id }})">
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
                <!-- Card de Perfil -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            Mi Perfil
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="profile-card">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-name">{{ Auth::user()->nombre }}</div>
                            <div class="profile-role">Administrador</div>

                            <div class="profile-info">
                                <div class="profile-info-item">
                                    <i class="fas fa-envelope" style="color: white;"></i>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-phone" style="color: white;"></i>
                                    <span>{{ Auth::user()->telefono ?? 'No especificado' }}</span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-calendar" style="color: white;"></i>
                                    <span>Miembro desde {{ Auth::user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>

                            <button class="btn btn-outline" style="width: 100%; margin-top: 20px;"
                                onclick="editarPerfil()">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Usuarios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            Resumen de Usuarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--primary);">
                                    {{ $stats['usuarios_totales'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Usuarios Totales</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--success);">
                                    {{ $stats['nuevos_clientes_mes'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Nuevos (Mes)</div>
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: var(--text-primary);">
                                Distribución por Rol</h3>
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
                                <i class="fas fa-award"></i>
                            </div>
                            Servicios Populares
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach ($servicios_populares as $servicio)
                            <div class="service-history-item" style="margin-bottom: 10px;">
                                <div class="service-icon" style="background: var(--secondary-gradient);">
                                    <i class="fas fa-spray-can"></i>
                                </div>
                                <div class="service-details">
                                    <h4>{{ $servicio->nombre }}</h4>
                                    <p>${{ number_format($servicio->precio, 2) }} - {{ $servicio->duracion }} min</p>
                                    <p><i class="fas fa-chart-line"></i> {{ $servicio->veces_contratado }} veces este
                                        mes</p>
                                </div>
                                <button class="btn btn-sm btn-outline" onclick="editarServicio({{ $servicio->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        @endforeach

                        <button class="btn btn-primary" style="width: 100%; margin-top: 10px;"
                            onclick="nuevoServicio()">
                            <i class="fas fa-plus"></i> Agregar Servicio
                        </button>
                    </div>
                </div>

                <!-- Notificaciones del Sistema -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            Alertas del Sistema
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($alertas as $alerta)
                            <div class="notification-item {{ $alerta->leida ? 'read' : 'unread' }}">
                                <div class="notification-icon {{ $alerta->tipo }}">
                                    <i class="fas fa-{{ $alerta->icono }}"></i>
                                </div>
                                <div class="notification-content">
                                    <h4>{{ $alerta->titulo }}</h4>
                                    <p>{{ $alerta->mensaje }}</p>
                                    <small>{{ $alerta->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach

                        @if (count($alertas) == 0)
                            <div class="empty-state" style="padding: 20px;">
                                <i class="fas fa-check-circle"></i>
                                <h3>No hay alertas</h3>
                                <p>No hay notificaciones importantes en este momento</p>
                            </div>
                        @endif
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
                    <h2 style="color: var(--primary); margin-bottom: 20px;">
                        <i class="fas fa-edit"></i> Editar Cita
                    </h2>
                    <form id="editarCitaForm">
                        <!-- Formulario se llenará dinámicamente -->
                    </form>
                </div>
            </div>

            <!-- Modal para nuevo/editar servicio -->
            <div id="servicioModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('servicioModal')">&times;</span>
                    <h2 id="servicioModalTitle">
                        <i class="fas fa-plus"></i> Nuevo Servicio
                    </h2>
                    <form id="servicioForm">
                        <input type="hidden" id="servicio_id" name="id">

                        <div class="form-group">
                            <label for="servicio_nombre">Nombre del Servicio:</label>
                            <input type="text" id="servicio_nombre" name="nombre" required class="form-control"
                                placeholder="Ej: Lavado Premium">
                        </div>

                        <div class="form-group">
                            <label for="servicio_descripcion">Descripción:</label>
                            <textarea id="servicio_descripcion" name="descripcion" rows="3" class="form-control"
                                placeholder="Describe los detalles del servicio..."></textarea>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="servicio_precio">Precio ($):</label>
                                <input type="number" step="0.01" id="servicio_precio" name="precio" required
                                    class="form-control" placeholder="0.00">
                            </div>

                            <div class="form-group">
                                <label for="servicio_duracion">Duración (min):</label>
                                <input type="number" id="servicio_duracion" name="duracion" required
                                    class="form-control" placeholder="30">
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

            <!-- Modal para gestión de horarios -->
            <div id="horarioModal" class="modal">
                <div class="modal-content" style="max-width: 500px;">
                    <span class="close-modal" onclick="closeModal('horarioModal')">&times;</span>
                    <h2 id="horarioModalTitle">
                        <i class="fas fa-clock"></i> Agregar Horario
                    </h2>
                    <form id="horarioForm">
                        <input type="hidden" id="horario_id" name="id">

                        <div class="form-group">
                            <label for="horario_dia">Día de la semana:</label>
                            <select id="horario_dia" class="form-control" required>
                                <option value="">Seleccione un día</option>
                                <option value="0">Domingo</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                            </select>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="horario_inicio">Hora de inicio:</label>
                                <input type="time" id="horario_inicio" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="horario_fin">Hora de fin:</label>
                                <input type="time" id="horario_fin" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="horario_activo">Estado:</label>
                            <select id="horario_activo" class="form-control">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Guardar Horario
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal para editar perfil -->
            <div id="perfilModal" class="modal">
                <div class="modal-content" style="max-width: 500px;">
                    <span class="close-modal" onclick="closeModal('perfilModal')">&times;</span>
                    <h2 style="color: var(--primary); margin-bottom: 20px;">
                        <i class="fas fa-user-edit"></i> Editar Perfil
                    </h2>
                    <form id="perfilForm">
                        <div class="form-group">
                            <label for="perfil_nombre">Nombre:</label>
                            <input type="text" id="perfil_nombre" name="nombre" required class="form-control"
                                value="{{ Auth::user()->nombre }}">
                        </div>

                        <div class="form-group">
                            <label for="perfil_email">Email:</label>
                            <input type="email" id="perfil_email" name="email" required class="form-control"
                                value="{{ Auth::user()->email }}">
                        </div>

                        <div class="form-group">
                            <label for="perfil_telefono">Teléfono:</label>
                            <input type="tel" id="perfil_telefono" name="telefono" class="form-control"
                                value="{{ Auth::user()->telefono }}">
                        </div>

                        <div class="form-group">
                            <label for="perfil_password">Nueva Contraseña (opcional):</label>
                            <input type="password" id="perfil_password" name="password" class="form-control"
                                placeholder="Dejar en blanco para no cambiar">
                        </div>

                        <div class="form-group">
                            <label for="perfil_password_confirmation">Confirmar Contraseña:</label>
                            <input type="password" id="perfil_password_confirmation" name="password_confirmation"
                                class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>

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

            <div class="social-icons">
                <a href="#" class="social-icon facebook" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://wa.me/50375855197" class="social-icon whatsapp" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="social-icon instagram" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <div class="footer-divider"></div>

            <p class="footer-copyright">
                &copy; 2025 AutoGest Carwash Berrios. Todos los derechos reservados.
                <br>Versión del sistema: 2.10.1
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
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                        'Dic'
                    ],
                    datasets: [{
                        label: 'Ingresos 2023',
                        data: [1200, 1900, 1500, 2000, 2200, 2500, 2800, 2600, 2300, 2000, 1800,
                            2100
                        ],
                        backgroundColor: 'rgba(39, 174, 96, 0.2)',
                        borderColor: 'rgba(39, 174, 96, 1)',
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
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                        'Dic'
                    ],
                    datasets: [{
                        label: 'Citas Completadas',
                        data: [45, 60, 55, 70, 75, 80, 85, 80, 70, 65, 60, 65],
                        backgroundColor: 'rgba(211, 84, 0, 0.7)',
                        borderColor: 'rgba(211, 84, 0, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Citas Canceladas',
                        data: [5, 8, 6, 10, 7, 5, 4, 8, 10, 7, 9, 6],
                        backgroundColor: 'rgba(231, 76, 60, 0.7)',
                        borderColor: 'rgba(231, 76, 60, 1)',
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
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(243, 156, 18, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(231, 76, 60, 0.7)'
                        ],
                        borderColor: [
                            'rgba(39, 174, 96, 1)',
                            'rgba(52, 152, 219, 1)',
                            'rgba(243, 156, 18, 1)',
                            'rgba(155, 89, 182, 1)',
                            'rgba(231, 76, 60, 1)'
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
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgba(39, 174, 96, 1)',
                            'rgba(52, 152, 219, 1)',
                            'rgba(155, 89, 182, 1)'
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
                <h2 style="color: var(--primary); margin-bottom: 20px;">
                    <i class="fas fa-calendar-check"></i> Detalle de Cita #${citaId}
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                            <i class="fas fa-user"></i> Información del Cliente
                        </h3>
                        <p><strong>Nombre:</strong> Juan Pérez</p>
                        <p><strong>Teléfono:</strong> 5555-1234</p>
                        <p><strong>Email:</strong> juan@example.com</p>
                        <p><strong>Cliente desde:</strong> Ene 2023</p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </h3>
                        <p><strong>Marca/Modelo:</strong> Toyota Corolla</p>
                        <p><strong>Año:</strong> 2020</p>
                        <p><strong>Color:</strong> Rojo</p>
                        <p><strong>Placa:</strong> P123456</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                        <i class="fas fa-concierge-bell"></i> Servicios Contratados
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--light);">
                                <th style="padding: 10px; text-align: left;">Servicio</th>
                                <th style="padding: 10px; text-align: right;">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary);">Lavado Completo</td>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary); text-align: right;">$25.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary);">Aspirado Interior</td>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary); text-align: right;">$15.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; font-weight: bold;">Total</td>
                                <td style="padding: 10px; font-weight: bold; text-align: right;">$40.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
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
            document.getElementById('detalleCitaModal').style.display = 'flex';
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
            document.getElementById('editarCitaModal').style.display = 'flex';
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
            document.getElementById('servicioModal').style.display = 'flex';
        }

        function editarServicio(servicioId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            document.getElementById('servicioModalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Servicio';
            document.getElementById('servicio_id').value = servicioId;
            document.getElementById('servicio_nombre').value = 'Lavado Completo';
            document.getElementById('servicio_descripcion').value =
                'Lavado exterior e interior completo con aspirado y limpieza de tapicería';
            document.getElementById('servicio_precio').value = '25.00';
            document.getElementById('servicio_duracion').value = '30';
            document.getElementById('servicio_activo').value = '1';
            document.getElementById('servicioModal').style.display = 'flex';
        }

        function mostrarModalHorario() {
            document.getElementById('horarioModalTitle').innerHTML = '<i class="fas fa-plus"></i> Agregar Horario';
            document.getElementById('horario_id').value = '';
            document.getElementById('horario_dia').value = '';
            document.getElementById('horario_inicio').value = '';
            document.getElementById('horario_fin').value = '';
            document.getElementById('horario_activo').value = '1';
            document.getElementById('horarioModal').style.display = 'flex';
        }

        function editarHorario(diaSemana) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

            document.getElementById('horarioModalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Horario';
            document.getElementById('horario_id').value = diaSemana;
            document.getElementById('horario_dia').value = diaSemana;
            document.getElementById('horario_inicio').value = '07:00';
            document.getElementById('horario_fin').value = '18:00';
            document.getElementById('horario_activo').value = '1';
            document.getElementById('horarioModal').style.display = 'flex';
        }

        function activarHorario(diaSemana) {
            // Aquí iría la petición AJAX para activar el horario
            Toast.fire({
                icon: 'success',
                title: 'Horario activado correctamente'
            });

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }

        function desactivarHorario(diaSemana) {
            // Aquí iría la petición AJAX para desactivar el horario
            Toast.fire({
                icon: 'success',
                title: 'Horario desactivado correctamente'
            });

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }

        function editarPerfil() {
            document.getElementById('perfil_nombre').value = '{{ Auth::user()->nombre }}';
            document.getElementById('perfil_email').value = '{{ Auth::user()->email }}';
            document.getElementById('perfil_telefono').value = '{{ Auth::user()->telefono ?? '' }}';
            document.getElementById('perfil_password').value = '';
            document.getElementById('perfil_password_confirmation').value = '';
            document.getElementById('perfilModal').style.display = 'flex';
        }

        // Manejar envío del formulario de horario
        document.getElementById('horarioForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Aquí iría la petición AJAX para guardar el horario
            const isNew = document.getElementById('horario_id').value === '';

            Toast.fire({
                icon: 'success',
                title: `Horario ${isNew ? 'creado' : 'actualizado'} correctamente`
            });

            closeModal('horarioModal');

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });

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

        // Manejar envío del formulario de perfil
        document.getElementById('perfilForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Aquí iría la petición AJAX para guardar el perfil
            Toast.fire({
                icon: 'success',
                title: 'Perfil actualizado correctamente'
            });

            closeModal('perfilModal');

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
                closeModal('horarioModal');
                closeModal('perfilModal');
            }
        });
    </script>
</body>

</html>
