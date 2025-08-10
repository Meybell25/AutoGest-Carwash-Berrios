<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles del Día No Laborable - AutoGest</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #3498db;
            --success-green: #27ae60;
            --warning-orange: #f39c12;
            --danger-red: #e74c3c;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --bg-light: #ecf0f1;
            --shadow-light: rgba(52, 152, 219, 0.1);
            --shadow-medium: rgba(52, 152, 219, 0.2);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green), var(--warning-orange));
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.7;
            overflow-x: hidden;
        }

        .main-container {
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: none;
            overflow: hidden;
            transition: var(--transition);
            animation: slideInUp 0.6s ease-out;
            margin-bottom: 2rem;
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow:
                0 35px 70px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.3);
        }

        .header-modern {
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green));
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .header-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-edit {
            background: rgba(243, 156, 18, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-edit:hover {
            background: rgba(243, 156, 18, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: rgba(231, 76, 60, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-delete:hover {
            background: rgba(231, 76, 60, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-3px);
        }

        .body-content {
            padding: 2rem;
        }

        .info-section {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.05), rgba(39, 174, 96, 0.05));
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--primary-blue);
        }

        .info-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(52, 152, 219, 0.1), transparent);
            border-radius: 50%;
        }

        .date-display {
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green));
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .date-display::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 20%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        .date-number {
            font-size: 3rem;
            font-weight: 800;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .date-text {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .status-alert {
            border-radius: 12px;
            padding: 1.5rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .status-alert.alert-warning {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(255, 193, 7, 0.1));
            border-left: 4px solid var(--warning-orange);
        }

        .status-alert.alert-success {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(46, 204, 113, 0.1));
            border-left: 4px solid var(--success-green);
        }

        .status-alert.alert-secondary {
            background: linear-gradient(135deg, rgba(127, 140, 141, 0.1), rgba(149, 165, 166, 0.1));
            border-left: 4px solid var(--text-secondary);
        }

        .sidebar-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border-radius: var(--border-radius);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 2rem;
            animation: slideInRight 0.6s ease-out;
        }

        .sidebar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .sidebar-header {
            background: linear-gradient(135deg, var(--text-primary), #34495e);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .sidebar-body {
            padding: 1.5rem;
        }

        .action-btn {
            width: 100%;
            margin-bottom: 0.75rem;
            justify-content: flex-start;
        }

        .action-btn.btn-warning {
            background: linear-gradient(135deg, var(--warning-orange), #e67e22);
            color: white;
        }

        .action-btn.btn-info {
            background: linear-gradient(135deg, var(--primary-blue), #2980b9);
            color: white;
        }

        .action-btn.btn-success {
            background: linear-gradient(135deg, var(--success-green), #229954);
            color: white;
        }

        .action-btn.btn-outline-secondary {
            background: rgba(127, 140, 141, 0.1);
            border: 2px solid rgba(127, 140, 141, 0.2);
        }

        .action-btn.btn-outline-danger {
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid rgba(231, 76, 60, 0.2);
            color: var(--danger-red);
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .stat-mini {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .stat-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .table-modern {
            background: transparent;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-modern th {
            background: linear-gradient(135deg, var(--text-primary), #34495e);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.75rem;
        }

        .table-modern td {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: rgba(52, 152, 219, 0.05);
        }

        .upcoming-item {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            transition: var(--transition);
            border-left: 3px solid var(--primary-blue);
        }

        .upcoming-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .badge-modern {
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .badge-primary { background: linear-gradient(135deg, var(--primary-blue), #2980b9); }
        .badge-success { background: linear-gradient(135deg, var(--success-green), #229954); }
        .badge-warning { background: linear-gradient(135deg, var(--warning-orange), #e67e22); }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            background: var(--warning-orange);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            background: var(--primary-blue);
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            background: var(--success-green);
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .modal-content-modern {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
        }

        .modal-header-modern {
            background: linear-gradient(135deg, var(--danger-red), #c0392b);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(5deg); }
            66% { transform: translateY(10px) rotate(-5deg); }
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .header-modern {
                padding: 1.5rem;
            }

            .body-content {
                padding: 1.5rem;
            }

            .header-title {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .header-content .d-flex {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .date-number {
                font-size: 2rem;
            }

            .sidebar-card {
                margin-top: 2rem;
            }
        }

        /* Animaciones de entrada escalonadas */
        .content-card {
            animation-delay: 0.1s;
        }

        .sidebar-card:nth-child(1) { animation-delay: 0.3s; }
        .sidebar-card:nth-child(2) { animation-delay: 0.5s; }
        .sidebar-card:nth-child(3) { animation-delay: 0.7s; }

        @media print {
            .btn-modern, .header-content .d-flex > div:last-child,
            .sidebar-card, .floating-shapes {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .content-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
</head>
<body>
    <!-- Formas flotantes decorativas -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="main-container">
        <div class="container-fluid">
            <div class="row">
                <!-- Contenido principal -->
                <div class="col-lg-8">
                    <div class="content-card">
                        <!-- Header moderno -->
                        <div class="header-modern">
                            <div class="header-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h1 class="header-title">
                                        <div class="header-icon">
                                            <i class="fas fa-calendar-times"></i>
                                        </div>
                                        Día No Laborable
                                    </h1>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.dias-no-laborables.edit', $dia->id) }}" class="btn-modern btn-edit">
                                            <i class="fas fa-edit"></i>
                                            Editar
                                        </a>
                                        <button type="button" class="btn-modern btn-delete" onclick="confirmarEliminacion()">
                                            <i class="fas fa-trash"></i>
                                            Eliminar
                                        </button>
                                        <a href="{{ route('admin.dias-no-laborables.index') }}" class="btn-modern btn-back">
                                            <i class="fas fa-arrow-left"></i>
                                            Volver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido principal -->
                        <div class="body-content">
                            <!-- Información principal del día -->
                            <div class="info-section">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="date-display">
                                            <div class="date-number">{{ $dia->fecha->format('d') }}</div>
                                            <div class="date-text">{{ $dia->fecha->translatedFormat('M Y') }}</div>
                                            <div class="date-text">{{ $dia->fecha->translatedFormat('l') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 mt-3 mt-md-0">
                                        <h3 class="mb-3" style="color: var(--text-primary);">
                                            <i class="fas fa-tag text-info me-2"></i>
                                            @php
                                                $motivosDisponibles = \App\Models\DiaNoLaborable::getMotivosDisponibles();
                                            @endphp
                                            {{ $motivosDisponibles[$dia->motivo] ?? $dia->motivo }}
                                        </h3>
                                        <p class="text-muted mb-3">{{ $dia->fecha->translatedFormat('l, d \de F \de Y') }}</p>

                                        <!-- Información detallada en formato compacto -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block">Semana del año</small>
                                                <strong>Semana {{ $dia->fecha->weekOfYear }}</strong>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block">Trimestre</small>
                                                <strong>{{ $dia->fecha->quarter }}° trimestre</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado del día -->
                            <div class="status-alert alert
                                {{ $dia->es_hoy ? 'alert-warning' : ($dia->es_pasado ? 'alert-secondary' : 'alert-success') }}">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($dia->es_hoy)
                                            <i class="fas fa-exclamation-triangle fa-2x" style="color: var(--warning-orange);"></i>
                                        @elseif($dia->es_pasado)
                                            <i class="fas fa-check-circle fa-2x" style="color: var(--text-secondary);"></i>
                                        @else
                                            <i class="fas fa-clock fa-2x" style="color: var(--success-green);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        @if($dia->es_hoy)
                                            <h5 class="mb-1" style="color: var(--warning-orange);">¡Es hoy!</h5>
                                            <p class="mb-0">Este día no laborable está en curso actualmente.</p>
                                        @elseif($dia->es_pasado)
                                            <h5 class="mb-1" style="color: var(--text-secondary);">Día pasado</h5>
                                            <p class="mb-0">Este día no laborable ya pasó hace {{ abs($dia->dias_restantes) }} días.</p>
                                        @else
                                            <h5 class="mb-1" style="color: var(--success-green);">Próximo día no laborable</h5>
                                            <p class="mb-0">Faltan {{ $dia->dias_restantes }} días para este día no laborable.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Historial del registro -->
                            <div class="info-section">
                                <h6 class="mb-3">
                                    <i class="fas fa-history me-2" style="color: var(--primary-blue);"></i>
                                    Historial del registro
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-plus-circle me-2 text-success"></i>
                                            <div>
                                                <strong>Creado:</strong> {{ $dia->created_at ? $dia->created_at->format('d/m/Y H:i') : 'No disponible' }}
                                                @if($dia->created_at)
                                                    <br><small class="text-muted">{{ $dia->created_at->diffForHumans() }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($dia->updated_at && $dia->updated_at != $dia->created_at)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-edit me-2 text-warning"></i>
                                            <div>
                                                <strong>Última modificación:</strong> {{ $dia->updated_at->format('d/m/Y H:i') }}
                                                <br><small class="text-muted">{{ $dia->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Impacto en citas -->
                    <div class="content-card">
                        <div class="header-modern" style="background: linear-gradient(135deg, var(--warning-orange), #e67e22);">
                            <div class="header-content">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Impacto en el sistema de citas
                                </h5>
                            </div>
                        </div>

                        <div class="body-content">
                            @php
                                $citas = \App\Models\Cita::whereDate('fecha', $dia->fecha)->get();
                                $citasAfectadas = $citas->count();
                            @endphp

                            @if($citasAfectadas > 0)
                                <div class="status-alert alert-warning">
                                    <h6>
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Atención
                                    </h6>
                                    <p>Se encontraron <strong>{{ $citasAfectadas }} cita(s)</strong> programadas para esta fecha:</p>

                                    <div class="table-responsive">
                                        <table class="table table-modern table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Hora</th>
                                                    <th>Estado</th>
                                                    <th>Servicios</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($citas as $cita)
                                                <tr>
                                                    <td>{{ $cita->usuario->nombre ?? 'N/A' }}</td>
                                                    <td>{{ $cita->hora ? \Carbon\Carbon::parse($cita->hora)->format('H:i') : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-modern badge-{{ $cita->estado == 'confirmada' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($cita->estado) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $cita->servicios->pluck('nombre')->join(', ') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <p class="mb-0 mt-3">
                                        <strong>Recomendación:</strong> Contactar a estos clientes para reprogramar sus citas.
                                    </p>
                                </div>
                            @else
                                <div class="status-alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    No hay citas programadas para esta fecha. El día no laborable no afectará ninguna cita existente.
                                </div>
                            @endif

                            <!-- Información sobre disponibilidad -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <div class="info-section text-center">
                                        <i class="fas fa-ban fa-2x mb-3" style="color: var(--danger-red);"></i>
                                        <h6>Efecto en reservas</h6>
                                        <p class="mb-0 small">Los clientes no podrán agendar citas para esta fecha</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-section text-center">
                                        <i class="fas fa-calendar-alt fa-2x mb-3" style="color: var(--primary-blue);"></i>
                                        <h6>Sistema de horarios</h6>
                                        <p class="mb-0 small">Los horarios de trabajo se omitirán automáticamente</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Acciones rápidas -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fas fa-bolt me-2"></i>
                            Acciones rápidas
                        </div>
                        <div class="sidebar-body">
                            <a href="{{ route('admin.dias-no-laborables.edit', $dia->id) }}" class="btn-modern action-btn btn-warning">
                                <i class="fas fa-edit me-2"></i>
                                Editar información
                            </a>

                            <a href="{{ route('admin.dias-no-laborables.create') }}" class="btn-modern action-btn btn-success">
                                <i class="fas fa-plus me-2"></i>
                                Agregar nuevo día
                            </a>

                            <hr style="margin: 1rem 0; border-color: rgba(0,0,0,0.1);">

                            <button type="button" class="btn-modern action-btn btn-outline-danger" onclick="confirmarEliminacion()">
                                <i class="fas fa-trash me-2"></i>
                                Eliminar día
                            </button>
                        </div>
                    </div>

                    <!-- Estadísticas relacionadas -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fas fa-chart-bar me-2"></i>
                            Estadísticas relacionadas
                        </div>
                        <div class="sidebar-body">
                            @php
                                $estadisticas = [
                                    'mismo_mes' => \App\Models\DiaNoLaborable::getNoLaborablesDelMes($dia->fecha->month, $dia->fecha->year)->count(),
                                    'mismo_motivo' => \App\Models\DiaNoLaborable::where('motivo', $dia->motivo)->count(),
                                    'proximos' => \App\Models\DiaNoLaborable::futuros()->count(),
                                    'total' => \App\Models\DiaNoLaborable::count()
                                ];
                            @endphp

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="stat-mini">
                                        <div class="stat-number">{{ $estadisticas['mismo_mes'] }}</div>
                                        <div class="stat-label">En {{ $dia->fecha->translatedFormat('F') }}</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-mini">
                                        <div class="stat-number">{{ $estadisticas['mismo_motivo'] }}</div>
                                        <div class="stat-label">Mismo motivo</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-mini">
                                        <div class="stat-number">{{ $estadisticas['proximos'] }}</div>
                                        <div class="stat-label">Próximos días</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-mini">
                                        <div class="stat-number">{{ $estadisticas['total'] }}</div>
                                        <div class="stat-label">Total registrados</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Próximos días no laborables -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <i class="fas fa-forward me-2"></i>
                            Próximos días no laborables
                        </div>
                        <div class="sidebar-body">
                            @php
                                $proximosDias = \App\Models\DiaNoLaborable::futuros()
                                    ->where('id', '!=', $dia->id)
                                    ->ordenadoPorFecha()
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @if($proximosDias->count() > 0)
                                @foreach($proximosDias as $proximoDia)
                                <div class="upcoming-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $proximoDia->fecha->format('d/m') }}</strong><br>
                                            <small class="text-muted">{{ $proximoDia->motivo }}</small>
                                        </div>
                                        <div>
                                            <span class="badge badge-modern badge-primary">{{ $proximoDia->dias_restantes }}d</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.dias-no-laborables.index') }}" class="btn-modern btn-outline-secondary">
                                        <i class="fas fa-list me-1"></i>
                                        Ver todos
                                    </a>
                                </div>
                            @else
                                <p class="text-muted text-center mb-0">No hay más días no laborables programados.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <i class="fas fa-trash-alt fa-3x" style="color: var(--danger-red);"></i>
                        </div>
                        <h6>¡Atención!</h6>
                        <p>Estás a punto de eliminar el día no laborable:</p>
                        <div class="info-section text-center">
                            <strong>{{ $dia->fecha->format('d/m/Y') }}</strong><br>
                            <span class="text-muted">{{ $motivosDisponibles[$dia->motivo] ?? $dia->motivo }}</span>
                        </div>

                        @if($citasAfectadas > 0)
                        <div class="status-alert alert-warning mt-3">
                            <p><strong>Impacto:</strong> Hay {{ $citasAfectadas }} cita(s) que podrían verse afectadas.</p>
                        </div>
                        @endif

                        <p class="mt-3"><strong>Esta acción no se puede deshacer.</strong></p>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="confirmDelete">
                            <label class="form-check-label" for="confirmDelete">
                                Confirmo que quiero eliminar este día no laborable
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <form method="POST" action="{{ route('admin.dias-no-laborables.destroy', $dia->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="btnEliminar" disabled>
                            <i class="fas fa-trash me-1"></i>
                            Eliminar definitivamente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion() {
            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

        // Habilitar botón de eliminar solo cuando se confirme
        document.getElementById('confirmDelete').addEventListener('change', function() {
            document.getElementById('btnEliminar').disabled = !this.checked;
        });

        // Animaciones de entrada para las tarjetas del sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCards = document.querySelectorAll('.sidebar-card');
            sidebarCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateX(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateX(0)';
                }, (index + 1) * 200);
            });

            // Efectos hover mejorados para mini estadísticas
            const statMinis = document.querySelectorAll('.stat-mini');
            statMinis.forEach(stat => {
                stat.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.05)';
                });
                stat.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>
