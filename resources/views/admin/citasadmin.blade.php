<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administración de Citas - AutoGest Carwash</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos del dashboard admin */
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
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-primary: rgba(39, 174, 96, 0.2);
            --border-light: rgba(255, 255, 255, 0.2);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --blur: blur(20px);
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3498db, #27ae60, #f39c12);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
            position: relative;
        }

        /* Header estilo bitácora */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: var(--blur);
            padding: 30px 35px;
            border-radius: 24px;
            margin-bottom: 35px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #2e7d32 0%, #00695c 100%);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Botones estilo bitácora */
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
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
            background: linear-gradient(135deg, #00695c 0%, #2e7d32 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 105, 92, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00695c 0%, #004d40 100%);
            box-shadow: 0 8px 25px rgba(0, 105, 92, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #263238 0%, #37474f 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(38, 50, 56, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #263238 0%, #1c262b 100%);
            box-shadow: 0 8px 25px rgba(38, 50, 56, 0.4);
        }

        /* Botón detalles  */
        .btn-details {
            background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(56, 142, 60, 0.3);
        }

        .btn-details:hover {
            color: white;
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            box-shadow: 0 8px 25px rgba(56, 142, 60, 0.4);
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: var(--blur);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(135deg, #00695c 0%, #2e7d32 100%);
            opacity: 0;
            transition: var(--transition);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            padding: 25px 30px 15px;
            border-bottom: 2px solid var(--border-primary);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 0 30px 30px;
        }

        /* Estilos para las estadísticas */
        .stats-card {
            margin-bottom: 25px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stats-item {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border-primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stats-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #00695c, #2e7d32);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 5px;
            display: block;
        }

        .stats-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-breakdown {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 16px;
            border: 1px solid #dee2e6;
        }

        .stats-breakdown-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .breakdown-item:last-child {
            border-bottom: none;
        }

        .breakdown-status {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .breakdown-count {
            font-weight: 700;
            color: var(--primary);
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .status-dot-pendiente {
            background: #ef6c00;
        }

        .status-dot-confirmada {
            background: #0277bd;
        }

        .status-dot-en_proceso {
            background: #6a1b9a;
        }

        .status-dot-finalizada {
            background: #2e7d32;
        }

        .status-dot-cancelada {
            background: #ad1457;
        }

        /* MEJORAS EN LOS FILTROS */
        .filters-card .card-body {
            padding: 30px 30px 25px;
            /* Más padding top */
        }

        .filter-group {
            margin-bottom: 1.8rem;
            /* Más espaciado entre filtros */
        }

        .filter-label {
            display: block;
            margin-bottom: 10px;
            /* Más separación del input */
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid var(--border-primary);
            border-radius: 12px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.98);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
            outline: none;
        }

        /* Mejoras en botones de filtros */
        .filter-buttons-container {
            display: flex;
            gap: 12px;
            width: 100%;
            align-items: end;
        }

        .filter-buttons-container .btn {
            flex: 1;
            min-width: 100px;
            /* Ancho mínimo para evitar recortes */
            justify-content: center;
            padding: 12px 16px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .admin-table th {
            background: var(--light);
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .admin-table td {
            padding: 18px 15px;
            border-bottom: 1px solid var(--border-primary);
            background: rgba(255, 255, 255, 0.98);
        }

        .admin-table tr:hover td {
            background: rgba(39, 174, 96, 0.03);
        }

        /* Estilos para estados según especificaciones */
        .appointment-status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .status-pendiente {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2) !important;
            color: #ef6c00 !important;
            border: 1px solid #ffcc80 !important;
        }

        .status-confirmada {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc) !important;
            color: #0277bd !important;
            border: 1px solid #81d4fa !important;
        }

        .status-en_proceso {
            background: linear-gradient(135deg, #f1e6ff, #e1bee7);
            color: #6a1b9a;
            border: 1px solid #ce93d8;
        }

        .status-finalizada {
            background: linear-gradient(135deg, #e0f2e0, #c8e6c9);
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .status-cancelada {
            background: linear-gradient(135deg, #fde7f3, #f8bbd9);
            color: #ad1457;
            border: 1px solid #f48fb1;
        }

        .estado-select {
            padding: 10px 12px;
            border-radius: 8px;
            border: 2px solid var(--border-primary);
            background: white;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
        }

        .estado-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .page-link {
            padding: 10px 15px;
            border: 2px solid var(--border-primary);
            border-radius: 10px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .page-link:hover,
        .page-link.active {
            background: var(--primary);
            color: white;
        }

        .filter-badge {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 8px;
        }

        .service-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            margin: 2px;
            display: inline-block;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Modal detaller */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #00695c 0%, #2e7d32 100%);
            color: white;
            padding: 25px 30px;
            border-bottom: none;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-title::before {
            content: '\f073';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-close {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            opacity: 1;
            font-size: 1.2rem;
            padding: 8px;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: white;
            transition: var(--transition);
        }

        .btn-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .btn-close::before {
            content: '\f00d';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 14px;
        }

        .modal-body {
            padding: 35px 30px;
            background: #fafafa;
        }

        .modal-footer {
            background: white;
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
        }


        /* Estilos para las secciones del modal */
        .modal-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .modal-section:last-child {
            margin-bottom: 0;
        }

        .modal-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
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
            color: var(--text-primary);
        }

        .modal-info-value {
            color: var(--text-secondary);
            text-align: right;
        }

        .services-grid {
            display: grid;
            gap: 12px;
        }

        .service-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 3px solid var(--info);
        }

        .service-name {
            font-weight: 600;
            color: var(--text-primary);
            flex-grow: 1;
        }

        .service-price {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .total-section {
            background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
            border: 2px solid var(--primary);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-container {
                padding: 20px 15px;
            }

            .header {
                padding: 20px;
            }

            .card-header,
            .card-body {
                padding: 20px 25px;
            }

            .filters-card .card-body {
                padding: 25px 25px 20px;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
                padding: 20px;
            }

            .search-filter-container {
                flex-direction: column;
            }

            .filter-buttons-container {
                flex-direction: column;
                gap: 10px;
            }

            .filter-buttons-container .btn {
                width: 100%;
            }

            .admin-table {
                display: block;
                overflow-x: auto;
            }

            .admin-table th,
            .admin-table td {
                padding: 12px 8px;
                font-size: 0.85rem;
            }

            .modal-body {
                padding: 25px 20px;
            }

            .modal-section {
                padding: 20px;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .dashboard-container {
                padding: 15px 10px;
            }

            .header {
                padding: 20px;
                border-radius: 18px;
            }

            .card {
                border-radius: 18px;
            }

            .card-header,
            .card-body {
                padding: 15px 20px;
            }

            .filters-card .card-body {
                padding: 20px 20px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div class="bitacora-title">
                <div class="icon-container"
                    style="width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #00695c 0%, #2e7d32 100%); margin-bottom: 0;">
                    <i class="fas fa-calendar" style="color: white !important; font-size: 1.3rem;"></i>
                </div>
                <div>
                    <h1
                        style="margin: 0; font-size: 1.8rem; background: linear-gradient(135deg, #00695c 0%, #2e7d32 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Administración de Citas</h1>
                    <p style="margin: 0; color: var(--text-secondary);">Gestiona y actualiza el estado de todas las
                        citas del sistema</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Estadísticas de Citas -->
        <div class="card stats-card">
            <div class="card-header">
                <h2
                    style="font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); margin-bottom: 0;">
                    <i class="fas fa-chart-bar"></i>
                    Resumen de Citas
                </h2>
            </div>
            <div class="card-body">
                <!-- Información de filtros activos -->
                @if (!empty($estadisticas['filtros_activos']))
                    <div class="filter-info">
                        <div class="filter-info-title">
                            <i class="fas fa-filter"></i>
                            Filtros Aplicados
                        </div>
                        @if (isset($estadisticas['filtros_activos']['usuario_nombre']))
                            <div class="filter-detail">
                                <strong>Cliente:</strong> {{ $estadisticas['filtros_activos']['usuario_nombre'] }}
                            </div>
                        @elseif(isset($estadisticas['filtros_activos']['busqueda']))
                            <div class="filter-detail">
                                <strong>Búsqueda:</strong> "{{ $estadisticas['filtros_activos']['busqueda'] }}"
                            </div>
                        @endif
                        @if (isset($estadisticas['filtros_activos']['estado']))
                            <div class="filter-detail">
                                <strong>Estado:</strong> {{ ucfirst($estadisticas['filtros_activos']['estado']) }}
                            </div>
                        @endif
                        @if (isset($estadisticas['filtros_activos']['fecha']))
                            <div class="filter-detail">
                                <strong>Fecha:</strong> {{ $estadisticas['filtros_activos']['fecha'] }}
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Estadística principal -->
                <div class="stats-grid">
                    <div class="stats-item">
                        <span class="stats-number">{{ $estadisticas['total'] }}</span>
                        <span class="stats-label">
                            @if (!empty($estadisticas['filtros_activos']))
                                Citas Filtradas
                            @else
                                Total de Citas
                            @endif
                        </span>
                    </div>

                    <div class="stats-item">
                        <span class="stats-number">{{ $estadisticas['por_estado']['pendiente'] }}</span>
                        <span class="stats-label">Pendientes</span>
                    </div>

                    <div class="stats-item">
                        <span class="stats-number">{{ $estadisticas['por_estado']['confirmada'] }}</span>
                        <span class="stats-label">Confirmadas</span>
                    </div>

                    <div class="stats-item">
                        <span class="stats-number">{{ $estadisticas['por_estado']['en_proceso'] }}</span>
                        <span class="stats-label">En Proceso</span>
                    </div>

                    <div class="stats-item">
                        <span class="stats-number">{{ $estadisticas['por_estado']['finalizada'] }}</span>
                        <span class="stats-label">Finalizadas</span>
                    </div>

                    <div class="stats-item">
                        <span class="stats-number">{{ $estadisticas['por_estado']['cancelada'] }}</span>
                        <span class="stats-label">Canceladas</span>
                    </div>
                </div>

                <!-- Desglose detallado -->
                @if ($estadisticas['total'] > 0)
                    <div class="stats-breakdown">
                        <div class="stats-breakdown-title">
                            <i class="fas fa-list-ul"></i>
                            Desglose por Estado
                            @if (isset($estadisticas['filtros_activos']['usuario_nombre']))
                                - {{ $estadisticas['filtros_activos']['usuario_nombre'] }}
                            @endif
                        </div>

                        @foreach (['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'en_proceso' => 'En Proceso', 'finalizada' => 'Finalizada', 'cancelada' => 'Cancelada'] as $estado => $label)
                            @if ($estadisticas['por_estado'][$estado] > 0)
                                <div class="breakdown-item">
                                    <div class="breakdown-status">
                                        <div class="status-dot status-dot-{{ $estado }}"></div>
                                        {{ $label }}
                                    </div>
                                    <div class="breakdown-count">
                                        {{ $estadisticas['por_estado'][$estado] }}
                                        <small style="color: var(--text-secondary); font-weight: normal;">
                                            ({{ round(($estadisticas['por_estado'][$estado] / $estadisticas['total']) * 100, 1) }}%)
                                        </small>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Filtros Mejorados -->
        <div class="card filters-card">
            <div class="card-body">
                <form id="filtros-form" method="GET" action="{{ route('admin.citasadmin.index') }}">
                    <div class="row">
                        <div class="col-md-3 filter-group">
                            <label for="filtro-estado" class="filter-label">Filtrar por estado:</label>
                            <select id="filtro-estado" name="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente</option>
                                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>
                                    Confirmada</option>
                                <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En
                                    Proceso</option>
                                <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>
                                    Finalizada</option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                    Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3 filter-group">
                            <label for="filtro-fecha" class="filter-label">Filtrar por fecha:</label>
                            <input type="date" id="filtro-fecha" name="fecha" class="form-control"
                                value="{{ request('fecha') }}">
                        </div>
                        <div class="col-md-4 filter-group">
                            <label for="buscar" class="filter-label">Buscar:</label>
                            <input type="text" id="buscar" name="buscar" class="form-control"
                                placeholder="Cliente, vehículo, placa..." value="{{ request('buscar') }}">
                        </div>
                        <div class="col-md-2 filter-group d-flex align-items-end">
                            <div class="filter-buttons-container">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="{{ route('admin.citasadmin.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-broom"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de citas  -->
        <div class="card">
            <div class="card-header">
                <h2
                    style="font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); margin-bottom: 10px;">
                    <i class="fas fa-list"></i>
                    Lista de Citas
                    <small style="font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">
                        ({{ $citas->total() }} {{ $citas->total() == 1 ? 'registro' : 'registros' }})
                    </small>
                </h2>
                @if (request()->anyFilled(['estado', 'fecha', 'buscar']))
                    <p style="font-size: 0.9rem; color: var(--text-secondary; margin-bottom: 0;">
                        Filtros aplicados:
                        @if (request('estado'))
                            <span class="filter-badge">Estado: {{ ucfirst(request('estado')) }}</span>
                        @endif
                        @if (request('fecha'))
                            <span class="filter-badge">Fecha: {{ request('fecha') }}</span>
                        @endif
                        @if (request('buscar'))
                            <span class="filter-badge">Búsqueda: "{{ request('buscar') }}"</span>
                        @endif
                    </p>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                            @forelse($citas as $cita)
                                <tr>
                                    <td>#{{ $cita->id }}</td>
                                    <td>
                                        <strong>{{ $cita->usuario->nombre }}</strong>
                                    </td>
                                    <td>
                                        {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                    </td>
                                    <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @foreach ($cita->servicios as $servicio)
                                            <span class="service-badge">{{ $servicio->nombre }}</span>
                                        @endforeach
                                    </td>
                                    <td>${{ number_format($cita->total, 2) }}</td>
                                    <td>
                                        <span
                                            class="appointment-status status-{{ $cita->estado }}">{{ $cita->estado_formatted }}</span>
                                    </td>
                                    <td>
                                        <select class="estado-select" data-cita-id="{{ $cita->id }}">
                                            <option value="pendiente"
                                                {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada"
                                                {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada
                                            </option>
                                            <option value="en_proceso"
                                                {{ $cita->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso
                                            </option>
                                            <option value="finalizada"
                                                {{ $cita->estado == 'finalizada' ? 'selected' : '' }}
                                                {{ !$cita->tienePagoCompletado() ? 'disabled' : '' }}>Finalizada
                                            </option>
                                            <option value="cancelada"
                                                {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>

                                        <!-- Botón SOLO para citas EN PROCESO sin pago -->
                                        @if ($cita->estado == 'en_proceso' && !$cita->tienePagoCompletado())
                                            <button class="btn btn-success mt-2 w-100 btn-pagar"
                                                data-cita-id="{{ $cita->id }}">
                                                <i class="fas fa-credit-card me-1"></i> Registrar Pago
                                            </button>
                                        @endif

                                        <!-- Badge para citas pagadas -->
                                        @if ($cita->tienePagoCompletado())
                                            <div class="text-center mt-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Pagado
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $cita->pago->metodo }}</small>
                                            </div>
                                        @endif

                                        <button class="btn btn-details mt-2 w-100 view-details"
                                            data-cita-id="{{ $cita->id }}">
                                            <i class="fas fa-eye me-1"></i> Detalles
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        <i class="fas fa-calendar-times"></i>
                                        <h3>No hay citas registradas</h3>
                                        <p>No hay actividades registradas en el sistema</p>
                                        @if (request()->anyFilled(['estado', 'fecha', 'buscar']))
                                            <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                                            <a href="{{ route('admin.citasadmin.index') }}"
                                                class="btn btn-primary mt-2">
                                                <i class="fas fa-broom me-1"></i> Limpiar filtros
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if ($citas->hasPages())
                    <div class="pagination">
                        @if ($citas->onFirstPage())
                            <span class="page-link disabled">&laquo;</span>
                        @else
                            <a href="{{ $citas->previousPageUrl() }}" class="page-link">&laquo;</a>
                        @endif

                        @foreach (range(1, $citas->lastPage()) as $page)
                            <a href="{{ $citas->url($page) }}"
                                class="page-link {{ $citas->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        @if ($citas->hasMorePages())
                            <a href="{{ $citas->nextPageUrl() }}" class="page-link">&raquo;</a>
                        @else
                            <span class="page-link disabled">&raquo;</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Mejorado para detalles de cita -->
    <div class="modal fade" id="detallesCitaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Cita #<span id="cita-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detalles-cita-content">
                    <!-- Los detalles se cargarán aquí via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pago -->
    <div class="modal fade" id="pagoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="pago-modal-content">
                <!-- El contenido se cargará via AJAX -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Sistema de Pagos JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Variables globales
            let isProcessingPayment = false;
            let currentCitaId = null;
            let paymentModal = null;
            
            // Inicialización
            initializePagosSystem();
            
            function initializePagosSystem() {
                // Inicializar selects de estado con validación de pago
                initializeEstadoSelects();
                
                // Configurar botones de pago
                setupPagoButtons();
                
                // Configurar botones de detalles
                setupDetallesButtons();
                
                // Configurar modales
                setupModals();
                
                console.log('Sistema de Pagos inicializado correctamente');
            }
            
            // 1. INICIALIZACIÓN DE SELECTS DE ESTADO
            function initializeEstadoSelects() {
                document.querySelectorAll('.estado-select').forEach(select => {
                    select._currentValue = select.value;
                    select._previousValue = select.value;
                    
                    select.addEventListener('change', function() {
                        handleEstadoChange(this);
                    });
                });
            }
            
            // 2. MANEJO DE CAMBIO DE ESTADO
            async function handleEstadoChange(selectElement) {
                const citaId = selectElement.getAttribute('data-cita-id');
                const nuevoEstado = selectElement.value;
                const estadoActual = selectElement._currentValue;
                
                // Validar cambio a finalizada
                if (nuevoEstado === 'finalizada') {
                    const verificacion = await verificarPagoCita(citaId);
                    
                    if (!verificacion.success || !verificacion.tiene_pago_completado) {
                        showAlert('warning', 'Pago requerido', 
                            'No se puede finalizar la cita sin un pago registrado. Por favor, registre el pago primero.');
                        selectElement.value = estadoActual;
                        return;
                    }
                }
                
                // Confirmación para cancelación
                if (nuevoEstado === 'cancelada') {
                    const confirmacion = await showConfirmation(
                        '¿Confirmar cancelación?',
                        'Esta acción cancelará la cita. ¿Estás seguro?',
                        'warning'
                    );
                    
                    if (!confirmacion) {
                        selectElement.value = selectElement._previousValue;
                        return;
                    }
                }
                
                // Actualizar estado
                await actualizarEstadoCita(citaId, nuevoEstado, selectElement);
            }
            
            // 3. VERIFICAR PAGO DE CITA
            async function verificarPagoCita(citaId) {
                try {
                    const response = await fetch(`/admin/pagos/${citaId}/verificar-pago`);
                    const data = await response.json();
                    
                    return {
                        success: response.ok,
                        tiene_pago_completado: data.tiene_pago_completado || false,
                        pago: data.pago || null
                    };
                } catch (error) {
                    console.error('Error al verificar pago:', error);
                    return { success: false, tiene_pago_completado: false };
                }
            }
            
            // 4. ACTUALIZAR ESTADO DE CITA
            async function actualizarEstadoCita(citaId, nuevoEstado, selectElement) {
                try {
                    showLoading('Actualizando estado...');
                    
                    const response = await fetch(`/admin/citasadmin/${citaId}/actualizar-estado`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ estado: nuevoEstado })
                    });
                    
                    const data = await response.json();
                    hideLoading();
                    
                    if (data.success) {
                        // Actualizar badge de estado
                        updateEstadoBadge(citaId, nuevoEstado, data.nuevo_estado);
                        
                        // Actualizar valores de control
                        if (selectElement) {
                            selectElement._previousValue = nuevoEstado;
                            selectElement._currentValue = nuevoEstado;
                        }
                        
                        showAlert('success', '¡Éxito!', data.message, 2000);
                        
                        // Recargar después de 2 segundos para actualizar botones y estadísticas
                        setTimeout(() => window.location.reload(), 2000);
                        
                    } else {
                        throw new Error(data.message);
                    }
                    
                } catch (error) {
                    hideLoading();
                    console.error('Error al actualizar estado:', error);
                    
                    showAlert('error', 'Error', error.message || 'Ocurrió un error al actualizar el estado');
                    
                    // Revertir cambio en el select
                    if (selectElement) {
                        selectElement.value = selectElement._previousValue;
                    }
                }
            }
            
            // 5. ACTUALIZAR BADGE DE ESTADO
            function updateEstadoBadge(citaId, estadoCodigo, estadoTexto) {
                const row = document.querySelector(`.estado-select[data-cita-id="${citaId}"]`)?.closest('tr');
                if (!row) return;
                
                const badge = row.querySelector('.appointment-status');
                if (badge) {
                    badge.className = `appointment-status status-${estadoCodigo}`;
                    badge.textContent = estadoTexto;
                }
            }
            
            // 6. CONFIGURAR BOTONES DE PAGO
            function setupPagoButtons() {
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('btn-pagar') || e.target.closest('.btn-pagar')) {
                        e.preventDefault();
                        
                        const button = e.target.classList.contains('btn-pagar') ? e.target : e.target.closest('.btn-pagar');
                        const citaId = button.getAttribute('data-cita-id');
                        
                        if (!citaId) {
                            showAlert('error', 'Error', 'ID de cita no encontrado');
                            return;
                        }
                        
                        openPagoModal(citaId);
                    }
                });
            }
            
            // 7. ABRIR MODAL DE PAGO
            async function openPagoModal(citaId) {
                try {
                    showLoading('Cargando formulario de pago...');
                    currentCitaId = citaId;
                    
                    const response = await fetch(`/admin/pagos/${citaId}/modal`);
                    const data = await response.json();
                    
                    hideLoading();
                    
                    if (data.success) {
                        document.getElementById('pago-modal-content').innerHTML = data.html;
                        
                        // Configurar eventos para el modal de pago
                        setupPagoModalEvents();
                        
                        // Mostrar modal
                        paymentModal = new bootstrap.Modal(document.getElementById('pagoModal'));
                        paymentModal.show();
                        
                    } else {
                        throw new Error(data.message);
                    }
                    
                } catch (error) {
                    hideLoading();
                    console.error('Error al cargar modal de pago:', error);
                    showAlert('error', 'Error', 'No se pudo cargar el formulario de pago: ' + error.message);
                }
            }
            
            // 7.1 CONFIGURAR EVENTOS DEL MODAL DE PAGO
            function setupPagoModalEvents() {
                // Esperar un breve momento para asegurar que el DOM esté listo
                setTimeout(() => {
                    try {
                        // Calcular vuelto cuando cambia el monto recibido
                        const montoRecibidoInput = document.getElementById('monto-recibido');
                        if (montoRecibidoInput) {
                            montoRecibidoInput.addEventListener('input', calcularVuelto);
                        } else {
                            console.warn("Elemento 'monto-recibido' no encontrado.");
                        }
                        
                        // Cambiar visibilidad de campos según método de pago
                        const metodoPagoSelect = document.getElementById('metodo-pago');
                        if (metodoPagoSelect) {
                            metodoPagoSelect.addEventListener('change', toggleCamposPago);
                            // Ejecutar una vez al cargar para establecer el estado inicial
                            toggleCamposPago();
                        } else {
                            console.warn("Elemento 'metodo-pago' no encontrado.");
                        }
                        
                        // Aplicar descuento
                        const aplicarDescuentoBtn = document.getElementById('aplicar-descuento-general');
                        if (aplicarDescuentoBtn) {
                            aplicarDescuentoBtn.addEventListener('click', aplicarDescuento);
                        } else {
                            console.warn("Elemento 'aplicar-descuento-general' no encontrado.");
                        }
                        
                        // Quitar descuento
                        const quitarDescuentoBtn = document.getElementById('quitar-descuento-general');
                        if (quitarDescuentoBtn) {
                            quitarDescuentoBtn.addEventListener('click', quitarDescuento);
                        } else {
                            console.warn("Elemento 'quitar-descuento-general' no encontrado.");
                        }
                        
                        // Registrar pago
                        const formPago = document.getElementById('form-pago');
                        if (formPago) {
                            formPago.addEventListener('submit', registrarPago);
                        } else {
                            console.warn("Elemento 'form-pago' no encontrado.");
                        }
                        
                    } catch (error) {
                        console.error('Error al configurar eventos del modal:', error);
                    }
                }, 100);
            }
            
            // 7.2 CALCULAR VUELTO
            function calcularVuelto() {
                try {
                    const montoRecibido = parseFloat(document.getElementById('monto-recibido')?.value) || 0;
                    const totalElement = document.getElementById('total-general');
                    const total = totalElement ? parseFloat(totalElement.textContent.replace('$', '')) : 0;
                    const vueltoCalculado = document.getElementById('vuelto-calculado');
                    
                    if (vueltoCalculado && montoRecibido >= total) {
                        const vuelto = montoRecibido - total;
                        vueltoCalculado.value = `$${vuelto.toFixed(2)}`;
                    } else if (vueltoCalculado) {
                        vueltoCalculado.value = '$0.00';
                    }
                } catch (error) {
                    console.error('Error al calcular vuelto:', error);
                }
            }
            
            // 7.3 TOGGLE CAMPOS DE PAGO SEGÚN MÉTODO
            function toggleCamposPago() {
                try {
                    const metodoPagoSelect = document.getElementById('metodo-pago');
                    if (!metodoPagoSelect) {
                        console.warn("Elemento 'metodo-pago' no encontrado en toggleCamposPago");
                        return;
                    }
                    
                    const metodoPago = metodoPagoSelect.value;
                    
                    // Ocultar todos los campos primero de manera segura
                    const campoEfectivo = document.getElementById('campo-efectivo');
                    const campoTransferencia = document.getElementById('campo-transferencia');
                    const campoPasarela = document.getElementById('campo-pasarela');
                    
                    [campoEfectivo, campoTransferencia, campoPasarela].forEach(campo => {
                        if (campo) campo.classList.add('d-none');
                    });
                    
                    // Mostrar el campo correspondiente de manera segura
                    if (metodoPago === 'efectivo' && campoEfectivo) {
                        campoEfectivo.classList.remove('d-none');
                        calcularVuelto(); // Calcular vuelto inicial
                    } else if (metodoPago === 'transferencia' && campoTransferencia) {
                        campoTransferencia.classList.remove('d-none');
                    } else if (metodoPago === 'pasarela' && campoPasarela) {
                        campoPasarela.classList.remove('d-none');
                    }
                } catch (error) {
                    console.error('Error en toggleCamposPago:', error);
                }
            }
            
            // 7.4 APLICAR DESCUENTO
            function aplicarDescuento() {
                try {
                    const porcentajeDescuento = parseFloat(document.getElementById('porcentaje-descuento')?.value) || 0;
                    const totalOriginalElement = document.getElementById('total-original');
                    const totalOriginal = totalOriginalElement ? parseFloat(totalOriginalElement.textContent.replace('$', '')) : 0;
                    
                    if (porcentajeDescuento > 0 && porcentajeDescuento <= 100) {
                        const descuento = totalOriginal * (porcentajeDescuento / 100);
                        const totalConDescuento = totalOriginal - descuento;
                        
                        const descuentoAplicadoElement = document.getElementById('descuento-aplicado');
                        const totalGeneralElement = document.getElementById('total-general');
                        
                        if (descuentoAplicadoElement && totalGeneralElement) {
                            descuentoAplicadoElement.textContent = `-$${descuento.toFixed(2)}`;
                            totalGeneralElement.textContent = `$${totalConDescuento.toFixed(2)}`;
                            
                            // Mostrar elementos de descuento aplicado
                            const descuentoInfo = document.getElementById('descuento-info');
                            const sinDescuento = document.getElementById('sin-descuento');
                            const conDescuento = document.getElementById('con-descuento');
                            
                            if (descuentoInfo) descuentoInfo.classList.remove('d-none');
                            if (sinDescuento) sinDescuento.classList.add('d-none');
                            if (conDescuento) conDescuento.classList.remove('d-none');
                            
                            // Recalcular vuelto si es pago en efectivo
                            if (document.getElementById('metodo-pago')?.value === 'efectivo') {
                                calcularVuelto();
                            }
                        }
                    } else {
                        showAlert('warning', 'Descuento inválido', 'Por favor ingrese un porcentaje válido entre 1 y 100.');
                    }
                } catch (error) {
                    console.error('Error al aplicar descuento:', error);
                    showAlert('error', 'Error', 'Ocurrió un error al aplicar el descuento');
                }
            }
            
            // 7.5 QUITAR DESCUENTO
            function quitarDescuento() {
                try {
                    const totalOriginalElement = document.getElementById('total-original');
                    const totalOriginal = totalOriginalElement ? parseFloat(totalOriginalElement.textContent.replace('$', '')) : 0;
                    
                    const descuentoAplicadoElement = document.getElementById('descuento-aplicado');
                    const totalGeneralElement = document.getElementById('total-general');
                    const porcentajeDescuentoElement = document.getElementById('porcentaje-descuento');
                    
                    if (descuentoAplicadoElement && totalGeneralElement && porcentajeDescuentoElement) {
                        descuentoAplicadoElement.textContent = '$0.00';
                        totalGeneralElement.textContent = `$${totalOriginal.toFixed(2)}`;
                        porcentajeDescuentoElement.value = '';
                        
                        // Ocultar elementos de descuento aplicado
                        const sinDescuento = document.getElementById('sin-descuento');
                        const conDescuento = document.getElementById('con-descuento');
                        
                        if (sinDescuento) sinDescuento.classList.remove('d-none');
                        if (conDescuento) conDescuento.classList.add('d-none');
                        
                        // Recalcular vuelto si es pago en efectivo
                        if (document.getElementById('metodo-pago')?.value === 'efectivo') {
                            calcularVuelto();
                        }
                    }
                } catch (error) {
                    console.error('Error al quitar descuento:', error);
                    showAlert('error', 'Error', 'Ocurrió un error al quitar el descuento');
                }
            }
            
            // 7.6 REGISTRAR PAGO
            async function registrarPago(e) {
                e.preventDefault();
                
                if (isProcessingPayment) return;
                
                // Validar formulario
                if (!validatePaymentForm()) {
                    showAlert('warning', 'Formulario incompleto', 'Por favor complete todos los campos requeridos correctamente.');
                    return;
                }
                
                isProcessingPayment = true;
                const btnRegistrar = document.getElementById('btn-registrar-pago');
                const originalText = btnRegistrar?.innerHTML;
                
                try {
                    if (btnRegistrar) {
                        btnRegistrar.disabled = true;
                        btnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                    }
                    
                    showLoading('Registrando pago...');
                    
                    const formData = new FormData(document.getElementById('form-pago'));
                    
                    const response = await fetch(`/admin/pagos/${currentCitaId}/registrar`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    hideLoading();
                    
                    if (data.success) {
                        showAlert('success', '¡Pago registrado!', data.message, 2000);
                        
                        // Cerrar modal después de 2 segundos
                        setTimeout(() => {
                            if (paymentModal) {
                                paymentModal.hide();
                            }
                            // Recargar página para actualizar estado
                            window.location.reload();
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Error al registrar el pago');
                    }
                    
                } catch (error) {
                    hideLoading();
                    console.error('Error al registrar pago:', error);
                    showAlert('error', 'Error', error.message || 'Ocurrió un error al registrar el pago');
                } finally {
                    isProcessingPayment = false;
                    if (btnRegistrar) {
                        btnRegistrar.disabled = false;
                        btnRegistrar.innerHTML = originalText;
                    }
                }
            }
            
            // 8. CONFIGURAR BOTONES DE DETALLES
            function setupDetallesButtons() {
                document.querySelectorAll('.view-details').forEach(button => {
                    button.addEventListener('click', function() {
                        const citaId = this.getAttribute('data-cita-id');
                        openDetallesModal(citaId);
                    });
                });
            }
            
            // 9. ABRIR MODAL DE DETALLES
            async function openDetallesModal(citaId) {
                try {
                    showLoading('Cargando detalles...');
                    
                    const response = await fetch(`/admin/citasadmin/${citaId}/detalles`);
                    const data = await response.json();
                    
                    hideLoading();
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    // Actualizar contenido del modal
                    updateDetallesModal(data);
                    
                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('detallesCitaModal'));
                    modal.show();
                    
                } catch (error) {
                    hideLoading();
                    console.error('Error al cargar detalles:', error);
                    showAlert('error', 'Error', 'No se pudieron cargar los detalles: ' + error.message);
                }
            }
            
            // 10. ACTUALIZAR CONTENIDO DEL MODAL DE DETALLES
            function updateDetallesModal(data) {
                const citaIdElement = document.getElementById('cita-id');
                if (citaIdElement) {
                    citaIdElement.textContent = data.id;
                }
                
                let serviciosHTML = '';
                if (data.servicios && data.servicios.length > 0) {
                    data.servicios.forEach(servicio => {
                        const precio = servicio.pivot?.precio || servicio.precio || 0;
                        serviciosHTML += `
                            <div class="service-item">
                                <span class="service-name">${servicio.nombre}</span>
                                <span class="service-price">$${precio.toFixed(2)}</span>
                            </div>
                        `;
                    });
                } else {
                    serviciosHTML = '<p class="text-muted text-center">No hay servicios registrados</p>';
                }
                
                const tipoVehiculo = data.vehiculo.tipo_formatted || data.vehiculo.tipo || 'No especificado';
                
                const contenidoHTML = `
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-user"></i> Información del Cliente
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Nombre:</span>
                            <span class="modal-info-value">${data.usuario.nombre}</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Email:</span>
                            <span class="modal-info-value">${data.usuario.email}</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Teléfono:</span>
                            <span class="modal-info-value">${data.usuario.telefono || 'No proporcionado'}</span>
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Marca/Modelo:</span>
                            <span class="modal-info-value">${data.vehiculo.marca} ${data.vehiculo.modelo}</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Placa:</span>
                            <span class="modal-info-value">${data.vehiculo.placa}</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Tipo:</span>
                            <span class="modal-info-value">${tipoVehiculo}</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Color:</span>
                            <span class="modal-info-value">${data.vehiculo.color || 'No especificado'}</span>
                        </div>
                        ${data.vehiculo.descripcion ? `
                            <div class="modal-info-item">
                                <span class="modal-info-label">Descripción:</span>
                                <span class="modal-info-value">${data.vehiculo.descripcion}</span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-calendar-alt"></i> Detalles de la Cita
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Fecha/Hora:</span>
                            <span class="modal-info-value">${new Date(data.fecha_hora).toLocaleString('es-ES')}</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-label">Estado:</span>
                            <span class="modal-info-value">
                                <span class="appointment-status status-${data.estado}">${data.estado_formatted}</span>
                            </span>
                        </div>
                        ${data.observaciones ? `
                            <div class="modal-info-item">
                                <span class="modal-info-label">Observaciones:</span>
                                <span class="modal-info-value">${data.observaciones}</span>
                            </div>
                        ` : ''}
                        <div class="modal-info-item">
                            <span class="modal-info-label">Fecha de creación:</span>
                            <span class="modal-info-value">${new Date(data.created_at).toLocaleString('es-ES')}</span>
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-tools"></i> Servicios Seleccionados
                        </div>
                        <div class="services-grid">
                            ${serviciosHTML}
                        </div>
                    </div>
                    
                    <div class="modal-section total-section">
                        <div style="margin-bottom: 10px; font-size: 1.2rem; font-weight: 600; color: var(--text-primary);">
                            <i class="fas fa-receipt me-2"></i> Total a Pagar
                        </div>
                        <div class="total-amount">$${data.total.toFixed(2)}</div>
                    </div>
                `;
                
                const detallesContent = document.getElementById('detalles-cita-content');
                if (detallesContent) {
                    detallesContent.innerHTML = contenidoHTML;
                }
            }
            
            // 11. CONFIGURAR MODALES
            function setupModals() {
                // Limpiar formulario al cerrar modal de pago
                const pagoModalElement = document.getElementById('pagoModal');
                if (pagoModalElement) {
                    pagoModalElement.addEventListener('hidden.bs.modal', function() {
                        resetPagoForm();
                    });
                }
            }
            
            // 12. RESETEAR FORMULARIO DE PAGO
            function resetPagoForm() {
                const form = document.getElementById('form-pago');
                if (form) {
                    form.reset();
                }
                
                const vueltoCalculado = document.getElementById('vuelto-calculado');
                if (vueltoCalculado) {
                    vueltoCalculado.value = '$0.00';
                }
                
                // Limpiar errores
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                
                isProcessingPayment = false;
                currentCitaId = null;
                
                const btnRegistrar = document.getElementById('btn-registrar-pago');
                if (btnRegistrar) {
                    btnRegistrar.disabled = false;
                    btnRegistrar.innerHTML = '<i class="fas fa-check-circle"></i> Registrar Pago';
                }
            }
            
            // 13. FUNCIONES DE UI - ALERTS
            function showAlert(type, title, message, timer = null) {
                const config = {
                    icon: type,
                    title: title,
                    text: message
                };
                
                if (timer) {
                    config.timer = timer;
                    config.showConfirmButton = false;
                }
                
                return Swal.fire(config);
            }
            
            // 14. MOSTRAR CONFIRMACIÓN
            function showConfirmation(title, message, type = 'warning') {
                return Swal.fire({
                    title: title,
                    text: message,
                    icon: type,
                    showCancelButton: true,
                    confirmButtonColor: type === 'warning' ? '#d33' : '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then(result => result.isConfirmed);
            }
            
            // 15. MOSTRAR/OCULTAR LOADING
            function showLoading(message = 'Procesando...') {
                Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            function hideLoading() {
                Swal.close();
            }
            
            // 16. MANEJO DE ERRORES GLOBALES
            window.addEventListener('error', function(e) {
                if (isProcessingPayment) {
                    hideLoading();
                    showAlert('error', 'Error', 'Ocurrió un error inesperado. Por favor, intente nuevamente.');
                    isProcessingPayment = false;
                }
            });
            
            // 17. FUNCIONES AUXILIARES PARA VALIDACIÓN
            function validatePaymentForm() {
                const metodo = document.getElementById('metodo-pago')?.value;
                if (!metodo) return false;
                
                let isValid = true;
                
                if (metodo === 'efectivo') {
                    const montoRecibido = parseFloat(document.getElementById('monto-recibido')?.value) || 0;
                    const totalElement = document.getElementById('total-general');
                    const total = totalElement ? parseFloat(totalElement.textContent.replace('$', '')) : 0;
                    
                    if (montoRecibido < total) {
                        document.getElementById('monto-recibido')?.classList.add('is-invalid');
                        isValid = false;
                    }
                } else if (['transferencia', 'pasarela'].includes(metodo)) {
                    const referenciaInput = metodo === 'transferencia' 
                        ? document.getElementById('referencia-input')
                        : document.getElementById('pasarela-referencia');
                    
                    if (!referenciaInput || referenciaInput.value.trim().length < 6) {
                        referenciaInput?.classList.add('is-invalid');
                        isValid = false;
                    }
                }
                
                return isValid;
            }
            
            // 18. EXPONIENDO FUNCIONES GLOBALES NECESARIAS
            window.PagosSystem = {
                openPagoModal: openPagoModal,
                openDetallesModal: openDetallesModal,
                verificarPagoCita: verificarPagoCita,
                validatePaymentForm: validatePaymentForm,
                showAlert: showAlert,
                showLoading: showLoading,
                hideLoading: hideLoading
            };
        });
    </script>
</body>

</html>