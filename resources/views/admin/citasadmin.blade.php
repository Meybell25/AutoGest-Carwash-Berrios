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

        .modal {
            /*  backdrop-filter: blur(5px);*/
        }

        .modal.show {
            padding-right: 0 !important;
            overflow-y: auto !important;
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
            padding: 25px 30px;
        }

        .filter-group {
            margin-bottom: 1.5rem;
        }

        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border-primary);
            border-radius: 10px;
            font-size: 14px;
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
            gap: 10px;
            width: 100%;
            flex-wrap: nowrap;
        }

        .filter-buttons-container .btn {
            flex: 1;
            min-width: 90px;
            white-space: nowrap;
            justify-content: center;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        .filter-buttons-container .btn i {
            font-size: 0.85rem;
            margin-right: 4px;
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
            max-height: calc(100vh - 3.5rem);
            overflow-y: auto;
            pointer-events: auto;
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
            overflow-y: auto;
            max-height: calc(100vh - 200px);
        }

        .modal-footer {
            background: white;
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
        }

        /* Campos de pago dinámicos - FALTANTES */
        #campos-efectivo,
        #campos-transferencia,
        #campos-pasarela {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Alertas informativas - FALTANTES */
        .alert {
            border-radius: 12px;
            border-left: 4px solid;
            font-size: 0.9rem;
        }

        .alert-info {
            border-left-color: #17a2b8;
            background-color: rgba(23, 162, 184, 0.1);
        }

        .alert-success {
            border-left-color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .alert-warning {
            border-left-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }

        /* Validación de errores - FALTANTES */
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        /* Estados de loading - FALTANTES */
        .btn .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Mejoras específicas para campos de formulario - */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            outline: none;
        }

        select.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Texto de ayuda  */
        .form-text {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        /* Estados hover para elementos interactivos  */
        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .service-item:hover {
            background-color: #e9ecef;
            transform: translateX(2px);
            transition: all 0.2s ease;
        }

        /* Indicadores de estado más claros  */
        .badge {
            padding: 0.5em 0.75em;
            font-size: 0.8em;
            border-radius: 0.375rem;
        }

        .bg-success {
            background-color: #198754 !important;
        }

        /* Mejorar contraste de texto  */
        .text-muted {
            color: #6c757d !important;
        }

        /* Separadores visuales  */
        .modal-section+.modal-section {
            border-top: 1px solid #f1f3f4;
            padding-top: 25px;
        }

        /* Botones de acción con mejor espaciado  */
        .text-center.mt-3 .btn {
            margin: 0 5px;
        }

        /* Estados focus mejorados para mejor accesibilidad  */
        .descuento-input:focus,
        .form-control:focus,
        .form-select:focus {
            z-index: 2;
            position: relative;
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
                        <div class="col-lg-3 col-md-6 filter-group">
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
                                    Finalizada
                                </option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                    Cancelada</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 filter-group">
                            <label for="filtro-fecha" class="filter-label">Filtrar por fecha:</label>
                            <input type="date" id="filtro-fecha" name="fecha" class="form-control"
                                value="{{ request('fecha') }}">
                        </div>
                        <div class="col-lg-4 col-md-8 filter-group">
                            <label for="buscar" class="filter-label">Buscar:</label>
                            <input type="text" id="buscar" name="buscar" class="form-control"
                                placeholder="Cliente, vehículo, placa..." value="{{ request('buscar') }}">
                        </div>
                        <div class="col-lg-3 col-md-4 filter-group d-flex align-items-end">
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
    <div class="modal fade" id="detallesCitaModal" tabindex="-1">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            let isProcessingPayment = false;
            let currentCitaId = null;
            let paymentModal = null;
            let eventsConfigured = false;

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
                            'No se puede finalizar la cita sin un pago registrado. Por favor, registre el pago primero.'
                        );
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
                    return {
                        success: false,
                        tiene_pago_completado: false
                    };
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
                        body: JSON.stringify({
                            estado: nuevoEstado
                        })
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

                        const button = e.target.classList.contains('btn-pagar') ? e.target : e.target
                            .closest('.btn-pagar');
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
                    eventsConfigured = false; // Reset flag

                    const response = await fetch(`/admin/pagos/${citaId}/modal`);
                    const data = await response.json();

                    hideLoading();

                    if (data.success) {
                        document.getElementById('pago-modal-content').innerHTML = data.html;

                        // Configurar eventos para el modal de pago cuando se muestre
                        const pagoModalElement = document.getElementById('pagoModal');
                        if (pagoModalElement) {
                            // Remover event listeners previos para evitar duplicados
                            pagoModalElement.removeEventListener('shown.bs.modal', setupPagoModalEvents);
                            pagoModalElement.addEventListener('shown.bs.modal', setupPagoModalEvents);
                        }

                        // Mostrar modal
                        paymentModal = new bootstrap.Modal(document.getElementById('pagoModal'), {
                            backdrop: 'static',
                            keyboard: false
                        });
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

            // 7.1 CONFIGURAR EVENTOS DEL MODAL DE PAGO (CORREGIDO)
            function setupPagoModalEvents() {
                if (eventsConfigured) {
                    console.log('Eventos ya configurados, saltando...');
                    return;
                }

                try {
                    console.log("Configurando eventos del modal de pago...");

                    // Cambiar visibilidad de campos según método de pago
                    const metodoPagoSelect = document.getElementById('metodo-pago');
                    if (metodoPagoSelect) {
                        metodoPagoSelect.addEventListener('change', toggleCamposPago);
                        toggleCamposPago(); // Ejecutar una vez al cargar
                        console.log("Evento de método de pago configurado");
                    }

                    // Calcular vuelto cuando cambia el monto recibido
                    const montoRecibidoInput = document.getElementById('monto-recibido');
                    if (montoRecibidoInput) {
                        montoRecibidoInput.addEventListener('input', calcularVuelto);
                        console.log("Evento de monto recibido configurado");
                    }

                    // Aplicar descuento general - BUTTON EXISTENTE
                    const aplicarDescuentoBtn = document.getElementById('aplicar-descuento-general');
                    if (aplicarDescuentoBtn) {
                        aplicarDescuentoBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log("Botón aplicar descuento clickeado");
                            showDescuentoModal();
                        });
                        console.log("Evento de aplicar descuento configurado");
                    }

                    // Quitar todos los descuentos - BUTTON EXISTENTE
                    const quitarDescuentosBtn = document.getElementById('quitar-descuentos');
                    if (quitarDescuentosBtn) {
                        quitarDescuentosBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            quitarTodosLosDescuentos();
                        });
                        console.log("Evento de quitar descuentos configurado");
                    }

                    // Eventos para descuentos individuales
                    document.querySelectorAll('.descuento-input').forEach(input => {
                        input.addEventListener('input', recalcularTotales);
                    });

                    // Registrar pago - USAR BUTTON CLICK EN VEZ DE FORM SUBMIT
                    const btnRegistrarPago = document.getElementById('btn-registrar-pago');
                    if (btnRegistrarPago) {
                        btnRegistrarPago.addEventListener('click', function(e) {
                            e.preventDefault();
                            registrarPago();
                        });
                        console.log("Evento de botón registrar pago configurado");
                    }

                    eventsConfigured = true;
                    console.log("Todos los eventos del modal configurados correctamente");

                } catch (error) {
                    console.error('Error al configurar eventos del modal:', error);
                }
            }

            // 7.2 MOSTRAR MODAL DE DESCUENTO
            function showDescuentoModal() {
                const descuentoModalElement = document.getElementById('descuentoGeneralModal');
                if (!descuentoModalElement) {
                    console.error('Modal de descuento no encontrado');
                    return;
                }

                const descuentoModal = new bootstrap.Modal(descuentoModalElement);
                descuentoModal.show();

                // Configurar evento de aplicar porcentaje
                const aplicarPorcentajeBtn = document.getElementById('aplicar-porcentaje');
                if (aplicarPorcentajeBtn) {
                    // Remover event listeners previos
                    aplicarPorcentajeBtn.onclick = null;
                    aplicarPorcentajeBtn.addEventListener('click', function() {
                        aplicarDescuentoGeneral();
                        descuentoModal.hide();
                    });
                }
            }

            // 7.3 APLICAR DESCUENTO GENERAL
            function aplicarDescuentoGeneral() {
                const porcentajeInput = document.getElementById('porcentaje-general');
                if (!porcentajeInput) return;

                const porcentaje = parseFloat(porcentajeInput.value) || 0;

                if (porcentaje < 0 || porcentaje > 100) {
                    showAlert('warning', 'Rango inválido', 'El descuento debe estar entre 0 y 100%');
                    return;
                }

                document.querySelectorAll('.descuento-input').forEach(input => {
                    const precioBase = parseFloat(input.dataset.precioBase) || 0;
                    const descuento = (precioBase * porcentaje) / 100;
                    input.value = descuento.toFixed(2);
                });

                recalcularTotales();
                showAlert('success', 'Descuento aplicado', `Se aplicó ${porcentaje}% de descuento`, 1500);
            }

            // 7.4 QUITAR TODOS LOS DESCUENTOS
            function quitarTodosLosDescuentos() {
                if (!confirm('¿Está seguro de quitar todos los descuentos?')) return;

                document.querySelectorAll('.descuento-input').forEach(input => {
                    input.value = '0';
                });

                recalcularTotales();
                showAlert('info', 'Descuentos removidos', 'Todos los descuentos han sido removidos', 1500);
            }

            // 7.5 RECALCULAR TOTALES
            function recalcularTotales() {
                let nuevoTotal = 0;

                document.querySelectorAll('#servicios-tabla tr[data-servicio-id]').forEach(row => {
                    const precioBase = parseFloat(row.querySelector('.descuento-input').dataset
                        .precioBase) || 0;
                    const descuento = Math.max(0, Math.min(precioBase, parseFloat(row.querySelector(
                        '.descuento-input').value) || 0));
                    const precioFinal = precioBase - descuento;

                    // Actualizar valor si fue corregido
                    row.querySelector('.descuento-input').value = descuento.toFixed(2);

                    // Actualizar precio final en la tabla
                    const precioFinalElement = row.querySelector('.precio-final');
                    if (precioFinalElement) {
                        precioFinalElement.textContent = '$' + precioFinal.toFixed(2);
                    }

                    // Actualizar porcentaje
                    const porcentaje = precioBase > 0 ? ((descuento / precioBase) * 100).toFixed(1) : 0;
                    const porcentajeElement = row.querySelector('.porcentaje-descuento');
                    if (porcentajeElement) {
                        porcentajeElement.textContent = porcentaje + '%';
                    }

                    nuevoTotal += precioFinal;
                });

                // Actualizar total general
                const totalGeneralElement = document.getElementById('total-general');
                if (totalGeneralElement) {
                    totalGeneralElement.textContent = '$' + nuevoTotal.toFixed(2);
                }

                // Actualizar otros elementos relacionados con el total
                const montoMinimoElement = document.getElementById('monto-minimo');
                if (montoMinimoElement) {
                    montoMinimoElement.textContent = '$' + nuevoTotal.toFixed(2);
                }

                const totalTransferencia = document.querySelector('.total-transferencia');
                if (totalTransferencia) {
                    totalTransferencia.textContent = '$' + nuevoTotal.toFixed(2);
                }

                const totalPasarela = document.querySelector('.total-pasarela');
                if (totalPasarela) {
                    totalPasarela.textContent = '$' + nuevoTotal.toFixed(2);
                }

                // Recalcular vuelto si es efectivo
                if (document.getElementById('metodo-pago')?.value === 'efectivo') {
                    calcularVuelto();
                }
            }

            // 7.6 CALCULAR VUELTO
            function calcularVuelto() {
                const montoRecibidoInput = document.getElementById('monto-recibido');
                const totalGeneralElement = document.getElementById('total-general');
                const vueltoCalculado = document.getElementById('vuelto-calculado');

                if (!montoRecibidoInput || !totalGeneralElement || !vueltoCalculado) {
                    return;
                }

                const montoRecibido = parseFloat(montoRecibidoInput.value) || 0;
                const total = parseFloat(totalGeneralElement.textContent.replace('$', '').replace(',', '')) || 0;

                if (montoRecibido >= total) {
                    const vuelto = montoRecibido - total;
                    vueltoCalculado.value = vuelto.toFixed(2);
                    montoRecibidoInput.classList.remove('is-invalid');
                } else {
                    vueltoCalculado.value = '0.00';
                    if (montoRecibido > 0) {
                        montoRecibidoInput.classList.add('is-invalid');
                    } else {
                        montoRecibidoInput.classList.remove('is-invalid');
                    }
                }
            }

            // 7.7 TOGGLE CAMPOS DE PAGO SEGÚN MÉTODO
            function toggleCamposPago() {
                const metodoPagoSelect = document.getElementById('metodo-pago');
                if (!metodoPagoSelect) return;

                const metodoPago = metodoPagoSelect.value;

                // Ocultar todos los campos
                const camposEfectivo = document.getElementById('campos-efectivo');
                const camposTransferencia = document.getElementById('campos-transferencia');
                const camposPasarela = document.getElementById('campos-pasarela');

                [camposEfectivo, camposTransferencia, camposPasarela].forEach(campo => {
                    if (campo) campo.style.display = 'none';
                });

                // Mostrar el campo correspondiente
                if (metodoPago === 'efectivo' && camposEfectivo) {
                    camposEfectivo.style.display = 'block';
                    // Auto-llenar con el total actual
                    const totalElement = document.getElementById('total-general');
                    const montoRecibidoInput = document.getElementById('monto-recibido');
                    if (totalElement && montoRecibidoInput) {
                        const total = parseFloat(totalElement.textContent.replace('$', '').replace(',', ''));
                        montoRecibidoInput.value = total.toFixed(2);
                        calcularVuelto();
                    }
                } else if (metodoPago === 'transferencia' && camposTransferencia) {
                    camposTransferencia.style.display = 'block';
                } else if (metodoPago === 'pasarela' && camposPasarela) {
                    camposPasarela.style.display = 'block';
                }

                // Limpiar errores
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
            }

            // 7.8 REGISTRAR PAGO (CORREGIDO)
            async function registrarPago() {
                if (isProcessingPayment) return;

                // Validar formulario
                if (!validatePaymentForm()) {
                    showAlert('warning', 'Formulario incompleto',
                        'Por favor complete todos los campos requeridos correctamente.');
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

                    // Agregar total actualizado
                    const totalElement = document.getElementById('total-general');
                    if (totalElement) {
                        const totalActualizado = parseFloat(totalElement.textContent.replace('$', '').replace(
                            ',', ''));
                        formData.append('total_actualizado', totalActualizado);
                    }

                    // Agregar descuentos actualizados
                    const descuentos = {};
                    document.querySelectorAll('.descuento-input').forEach(input => {
                        descuentos[input.dataset.servicioId] = parseFloat(input.value) || 0;
                    });
                    formData.append('descuentos', JSON.stringify(descuentos));

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
                    const modalElement = document.getElementById('detallesCitaModal');
                    if (!modalElement) {
                        throw new Error('El modal de detalles no se encuentra en el DOM');
                    }

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
                    const modal = new bootstrap.Modal(modalElement, {
                        backdrop: true,
                        keyboard: true,
                        focus: true
                    });

                    modal.show();

                    // Configurar el evento para el modal después de mostrarlo
                    modalElement.addEventListener('shown.bs.modal', function() {
                        const modalBody = this.querySelector('.modal-body');
                        if (modalBody) {
                            modalBody.style.overflowY = 'auto';
                            modalBody.style.maxHeight = 'calc(100vh - 200px)';
                        }
                    });

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
                        const descuento = servicio.pivot?.descuento || 0;
                        const precioFinal = precio - descuento;

                        serviciosHTML += `
                <div class="service-item">
                    <span class="service-name">${servicio.nombre}</span>
                    <span class="service-price">${precioFinal.toFixed(2)}</span>
                    ${descuento > 0 ? 
                        `<span class="text-success ms-2"><small>(-${descuento.toFixed(2)})</small></span>` : ''}
                </div>
            `;
                    });
                } else {
                    serviciosHTML = '<p class="text-muted text-center">No hay servicios registrados</p>';
                }

                const tipoVehiculo = data.vehiculo.tipo_formatted || data.vehiculo.tipo || 'No especificado';

                // Construir sección de información de pago si existe
                let pagoHTML = '';
                if (data.pago && data.estado === 'finalizada') {
                    // Asegurarnos de que los valores numéricos sean convertidos correctamente
                    const monto = typeof data.pago.monto === 'number' ? data.pago.monto : Number(data.pago.monto ||
                        0);
                    const montoRecibido = typeof data.pago.monto_recibido === 'number' ? data.pago.monto_recibido :
                        Number(data.pago.monto_recibido || 0);
                    const vuelto = typeof data.pago.vuelto === 'number' ? data.pago.vuelto : Number(data.pago
                        .vuelto || 0);

                    pagoHTML = `
            <div class="modal-section">
                <div class="modal-section-title">
                    <i class="fas fa-credit-card"></i> Información de Pago
                </div>
                <div class="modal-info-item">
                    <span class="modal-info-label">Método:</span>
                    <span class="modal-info-value">${data.pago.metodo_formatted || data.pago.metodo}</span>
                </div>
                ${data.pago.referencia ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Referencia:</span>
                            <span class="modal-info-value">${data.pago.referencia}</span>
                        </div>
                    ` : ''}
                <div class="modal-info-item">
                    <span class="modal-info-label">Monto Pagado:</span>
                    <span class="modal-info-value">$${monto.toFixed(2)}</span>
                </div>
                ${data.pago.monto_recibido ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Monto Recibido:</span>
                            <span class="modal-info-value">$${montoRecibido.toFixed(2)}</span>
                        </div>
                    ` : ''}
                ${data.pago.vuelto ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Vuelto:</span>
                            <span class="modal-info-value">$${vuelto.toFixed(2)}</span>
                        </div>
                    ` : ''}
                ${data.pago.fecha_pago ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Fecha de Pago:</span>
                            <span class="modal-info-value">${new Date(data.pago.fecha_pago).toLocaleString('es-ES')}</span>
                        </div>
                    ` : ''}
                ${data.pago.banco_emisor ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Banco Emisor:</span>
                            <span class="modal-info-value">${data.pago.banco_emisor}</span>
                        </div>
                    ` : ''}
                ${data.pago.tipo_tarjeta ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Tipo de Tarjeta:</span>
                            <span class="modal-info-value">${data.pago.tipo_tarjeta}</span>
                        </div>
                    ` : ''}
                ${data.pago.observaciones ? `
                        <div class="modal-info-item">
                            <span class="modal-info-label">Observaciones:</span>
                            <span class="modal-info-value">${data.pago.observaciones}</span>
                        </div>
                    ` : ''}
            </div>
        `;
                }

                // Determinar si debemos mostrar la sección de "Total a Pagar"
                // Solo se muestra si la cita NO está finalizada o si está finalizada pero NO tiene pago
                const mostrarTotalAPagar = data.estado !== 'finalizada' || (data.estado === 'finalizada' && !data
                    .pago);

                const totalSectionHTML = mostrarTotalAPagar ? `
        <div class="modal-section total-section">
            <div style="margin-bottom: 10px; font-size: 1.2rem; font-weight: 600; color: var(--text-primary);">
                <i class="fas fa-receipt me-2"></i> Total a Pagar
            </div>
            <div class="total-amount">$${typeof data.total === 'number' ? data.total.toFixed(2) : Number(data.total || 0).toFixed(2)}</div>
        </div>
    ` : '';

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
        
        ${pagoHTML}
        
        ${totalSectionHTML}
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

                // Configurar el modal de detalles
                const detallesModalElement = document.getElementById('detallesCitaModal');
                if (detallesModalElement) {
                    // Limpiar contenido al cerrar el modal
                    detallesModalElement.addEventListener('hidden.bs.modal', function() {
                        const detallesContent = document.getElementById('detalles-cita-content');
                        if (detallesContent) {
                            detallesContent.innerHTML = '';
                        }
                        document.getElementById('cita-id').textContent = '';
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
                    vueltoCalculado.value = '0.00';
                }

                // Limpiar errores
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                isProcessingPayment = false;
                currentCitaId = null;
                eventsConfigured = false;

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
                    text: message,
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
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
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
                }).then(result => result.isConfirmed);
            }

            // 15. MOSTRAR/OCULTAR LOADING
            function showLoading(message = 'Procesando...') {
                Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            function hideLoading() {
                Swal.close();
            }

            // 16. FUNCIONES AUXILIARES PARA VALIDACIÓN
            function validatePaymentForm() {
                const metodo = document.getElementById('metodo-pago')?.value;
                if (!metodo) return false;

                let isValid = true;

                // Limpiar errores previos
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                if (metodo === 'efectivo') {
                    const montoRecibidoInput = document.getElementById('monto-recibido');
                    const totalElement = document.getElementById('total-general');

                    if (montoRecibidoInput && totalElement) {
                        const montoRecibido = parseFloat(montoRecibidoInput.value) || 0;
                        const total = parseFloat(totalElement.textContent.replace('$', '').replace(',', '')) || 0;

                        if (montoRecibido < total) {
                            montoRecibidoInput.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                } else if (metodo === 'transferencia') {
                    const referenciaInput = document.getElementById('referencia-input');
                    const bancoSelect = document.getElementById('banco-emisor');

                    if (referenciaInput && referenciaInput.value.trim().length < 6) {
                        referenciaInput.classList.add('is-invalid');
                        isValid = false;
                    }

                    if (bancoSelect && !bancoSelect.value) {
                        bancoSelect.classList.add('is-invalid');
                        isValid = false;
                    }
                } else if (metodo === 'pasarela') {
                    const referenciaInput = document.getElementById('pasarela-referencia');
                    const tipoTarjetaSelect = document.getElementById('tipo-tarjeta');

                    if (referenciaInput && referenciaInput.value.trim().length < 6) {
                        referenciaInput.classList.add('is-invalid');
                        isValid = false;
                    }

                    if (tipoTarjetaSelect && !tipoTarjetaSelect.value) {
                        tipoTarjetaSelect.classList.add('is-invalid');
                        isValid = false;
                    }
                }

                return isValid;
            }

            // 17. MANEJO DE ERRORES GLOBALES
            window.addEventListener('error', function(e) {
                if (isProcessingPayment) {
                    hideLoading();
                    showAlert('error', 'Error',
                        'Ocurrió un error inesperado. Por favor, intente nuevamente.');
                    isProcessingPayment = false;
                }
            });

            // 18. EXPONIENDO FUNCIONES GLOBALES NECESARIAS
            window.PagosSystem = {
                openPagoModal: openPagoModal,
                openDetallesModal: openDetallesModal,
                verificarPagoCita: verificarPagoCita,
                validatePaymentForm: validatePaymentForm,
                showAlert: showAlert,
                showLoading: showLoading,
                hideLoading: hideLoading,
                recalcularTotales: recalcularTotales
            };
        });
    </script>
</body>

</html>
