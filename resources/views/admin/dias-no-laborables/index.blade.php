<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Días No Laborables - AutoGest Admin</title>

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

        .btn-dashboard {
            background: rgba(243, 156, 18, 0.2);
            color: white;
            backdrop-filter: blur(10px);
            margin-right: 0.5rem;
        }

        .btn-dashboard:hover {
            background: rgba(243, 156, 18, 0.3);
            color: white;
            transform: translateX(-3px);
        }

        .btn-add {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-add:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .body-content {
            padding: 2rem;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(39, 174, 96, 0.1));
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--success-green), var(--warning-orange));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-modern {
            margin: 0;
            background: transparent;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, var(--text-primary), #34495e);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            position: relative;
        }

        .table-modern tbody tr {
            transition: var(--transition);
            border: none;
        }

        .table-modern tbody tr:hover {
            background: rgba(52, 152, 219, 0.1);
            transform: scale(1.01);
        }

        .table-modern td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
            position: relative;
            overflow: hidden;
        }

        .badge-secondary { background: linear-gradient(135deg, #95a5a6, #7f8c8d); }
        .badge-info { background: linear-gradient(135deg, var(--primary-blue), #2980b9); }
        .badge-warning { background: linear-gradient(135deg, var(--warning-orange), #e67e22); }
        .badge-success { background: linear-gradient(135deg, var(--success-green), #229954); }
        .badge-past { background: linear-gradient(135deg, #bdc3c7, #95a5a6); }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-view {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px) scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

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

        /* Modal moderno */
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

        .alert-success-modern {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(46, 204, 113, 0.1));
            border: 1px solid rgba(39, 174, 96, 0.2);
            border-radius: 12px;
            border-left: 4px solid var(--success-green);
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

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(5deg); }
            66% { transform: translateY(10px) rotate(-5deg); }
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

            .stats-row {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        /* Animaciones de entrada escalonadas */
        .stat-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .table-container {
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.5s;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
            <div class="content-card">
                <!-- Header moderno -->
                <div class="header-modern">
                    <div class="header-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="header-title">
                                <div class="header-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                Días No Laborables
                            </h1>
                            <div class="d-flex">
                                <a href="{{ route('admin.dashboard') }}" class="btn-modern btn-dashboard">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.dias-no-laborables.create') }}" class="btn-modern btn-add">
                                    <i class="fas fa-plus"></i>
                                    Agregar Día
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="body-content">
                    <!-- Alertas de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success-modern alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Estadísticas rápidas -->
                    @php
                        $totalDias = $dias->total();
                        $diasProximos = $dias->where('fecha', '>=', now())->count();
                        $diasEsteMes = $dias->where('fecha', '>=', now()->startOfMonth())->where('fecha', '<=', now()->endOfMonth())->count();
                        $diasPasados = $dias->where('fecha', '<', now())->count();
                    @endphp

                    <div class="stats-row">
                        <div class="stat-card">
                            <div class="stat-number">{{ $totalDias }}</div>
                            <div class="stat-label">Total Registrados</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $diasProximos }}</div>
                            <div class="stat-label">Próximos Días</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $diasEsteMes }}</div>
                            <div class="stat-label">Este Mes</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $diasPasados }}</div>
                            <div class="stat-label">Ya Pasaron</div>
                        </div>
                    </div>

                    @if($dias->count() > 0)
                        <!-- Tabla de días no laborables -->
                        <div class="table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Día de la semana</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th width="150">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dias as $dia)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-calendar fa-lg" style="color: var(--primary-blue);"></i>
                                                </div>
                                                <div>
                                                    <strong style="color: var(--text-primary);">{{ $dia->fecha->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $dia->fecha->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-modern badge-secondary">
                                                {{ $dia->fecha->translatedFormat('l') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-modern badge-info">
                                                {{ $motivosDisponibles[$dia->motivo] ?? $dia->motivo }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($dia->fecha->isToday())
                                                <span class="badge badge-modern badge-warning">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    Hoy
                                                </span>
                                            @elseif($dia->fecha->isPast())
                                                <span class="badge badge-modern badge-past">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Pasado
                                                </span>
                                            @else
                                                <span class="badge badge-modern badge-success">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Próximo
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.dias-no-laborables.show', $dia->id) }}"
                                                   class="btn-action btn-view" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.dias-no-laborables.edit', $dia->id) }}"
                                                   class="btn-action btn-edit" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn-action btn-delete"
                                                        onclick="confirmarEliminacion({{ $dia->id }})" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        @if($dias->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $dias->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Estado vacío -->
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h4>No hay días no laborables registrados</h4>
                            <p class="mb-4">Comienza agregando el primer día no laborable para gestionar la disponibilidad de citas.</p>
                            <a href="{{ route('admin.dias-no-laborables.create') }}" class="btn-modern btn-add">
                                <i class="fas fa-plus me-1"></i>
                                Agregar el primero
                            </a>
                        </div>
                    @endif
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
                        <h6>¿Estás seguro de que deseas eliminar este día no laborable?</h6>
                        <p class="text-muted">Esta acción no se puede deshacer y puede afectar la disponibilidad de citas.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <form id="formEliminar" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion(id) {
            const form = document.getElementById('formEliminar');
            form.action = `{{ route('admin.dias-no-laborables.index') }}/${id}`;
            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

        // Animación de carga para las filas de la tabla
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.table-modern tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.4s ease-out';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, (index + 1) * 100);
            });

            // Efecto de hover mejorado para las tarjetas de estadísticas
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>
