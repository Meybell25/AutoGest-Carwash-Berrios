<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles del Gasto - AutoGest Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #3498db;
            --success-green: #27ae60;
            --warning-orange: #f39c12;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --bg-glass: rgba(255, 255, 255, 0.1);
            --border-glass: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green), var(--warning-orange));
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Formas flotantes decorativas */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 75%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 180px;
            height: 180px;
            top: 50%;
            left: 15%;
            animation-delay: 7s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 25%;
            left: 35%;
            animation-delay: 14s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
        }

        /* Contenedor principal */
        .main-container {
            backdrop-filter: blur(20px);
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            margin: 2rem;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 4rem);
            animation: slideIn 0.8s ease-out;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .header-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green));
            border-radius: 20px 20px 0 0;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .breadcrumb-nav {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            backdrop-filter: blur(10px);
            font-size: 0.9rem;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-outline-modern {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .btn-primary-modern {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Sección de detalles */
        .details-section {
            padding: 2rem;
            animation: fadeInUp 1s ease-out 0.3s both;
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

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
        }

        .details-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .detail-icon.blue { background: linear-gradient(135deg, var(--primary-blue), #5dade2); }
        .detail-icon.green { background: linear-gradient(135deg, var(--success-green), #58d68d); }
        .detail-icon.orange { background: linear-gradient(135deg, var(--warning-orange), #f7dc6f); }
        .detail-icon.purple { background: linear-gradient(135deg, #9b59b6, #bb8fce); }
        .detail-icon.red { background: linear-gradient(135deg, #e74c3c, #ec7063); }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: white;
            font-size: 1rem;
            font-weight: 600;
        }

        .detail-value.large {
            font-size: 1.5rem;
            color: var(--warning-orange);
        }

        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-stock {
            background: linear-gradient(135deg, #3498db, #5dade2);
            color: white;
            border-color: rgba(52, 152, 219, 0.3);
        }

        .badge-sueldos {
            background: linear-gradient(135deg, #27ae60, #58d68d);
            color: white;
            border-color: rgba(39, 174, 96, 0.3);
        }

        .badge-personal {
            background: linear-gradient(135deg, #f39c12, #f7dc6f);
            color: white;
            border-color: rgba(243, 156, 18, 0.3);
        }

        .badge-mantenimiento {
            background: linear-gradient(135deg, #9b59b6, #bb8fce);
            color: white;
            border-color: rgba(155, 89, 182, 0.3);
        }

        .badge-otro {
            background: linear-gradient(135deg, #95a5a6, #bdc3c7);
            color: white;
            border-color: rgba(149, 165, 166, 0.3);
        }

        /* Sidebar de estadísticas */
        .stats-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-icon-large {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Botones de acción */
        .actions-section {
            padding: 0 2rem 2rem;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .actions-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
        }

        .btn-action {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--warning-orange), #f7dc6f);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c, #ec7063);
            color: white;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Timeline de actividad */
        .timeline-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .timeline-title {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 0;
            border-left: 2px solid rgba(255, 255, 255, 0.2);
            padding-left: 1rem;
            margin-left: 10px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--success-green);
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-action {
            color: white;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .timeline-time {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        /* Modal moderno */
        .modal-content-modern {
            background: rgba(44, 62, 80, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(20px);
            color: white;
        }

        .modal-header-modern {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
        }

        .modal-body-modern {
            padding: 1.5rem;
        }

        .modal-footer-modern {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
            }

            .header-title {
                font-size: 1.8rem;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .actions-row {
                grid-template-columns: 1fr;
            }

            .breadcrumb-nav {
                flex-direction: column;
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

    <!-- Contenedor principal -->
    <div class="main-container">
        <!-- Header -->
        <div class="header-section">
            <div class="header-content">
                <h1 class="header-title">
                    <i class="fas fa-receipt me-3"></i>
                    Detalles del Gasto
                </h1>
                <p class="header-subtitle">
                    Información completa del registro de gasto #{{ $gasto->id }}
                </p>
                <div class="breadcrumb-nav">
                    <a href="{{ route('admin.dashboard') }}" class="btn-modern btn-outline-modern">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.gastos.index') }}" class="btn-modern btn-outline-modern">
                        <i class="fas fa-list"></i>
                        Gastos
                    </a>
                    <a href="{{ route('admin.gastos.edit', $gasto->id) }}" class="btn-modern btn-primary-modern">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                </div>
            </div>
        </div>

        <!-- Detalles del gasto -->
        <div class="details-section">
            <div class="details-grid">
                <!-- Información principal -->
                <div class="details-card">
                    <div class="detail-item">
                        <div class="detail-icon blue">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">ID del Gasto</div>
                            <div class="detail-value">#{{ str_pad($gasto->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon orange">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Monto</div>
                            <div class="detail-value large">${{ number_format($gasto->monto, 2) }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon purple">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Tipo de Gasto</div>
                            <div class="detail-value">
                                <span class="badge-modern badge-{{ $gasto->tipo }}">
                                    <i class="fas fa-tag"></i>
                                    {{ $gasto->tipo_formateado }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon green">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Detalle del Gasto</div>
                            <div class="detail-value">{{ $gasto->detalle }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon blue">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Fecha del Gasto</div>
                            <div class="detail-value">
                                {{ $gasto->fecha_gasto->format('d/m/Y') }}
                                <br>
                                <small style="color: rgba(255, 255, 255, 0.7);">
                                    {{ $gasto->fecha_gasto->translatedFormat('l, d \d\e F \d\e Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon green">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Registrado por</div>
                            <div class="detail-value">
                                @if($gasto->usuario)
                                    {{ $gasto->usuario->nombre }}
                                    <br>
                                    <small style="color: rgba(255, 255, 255, 0.7);">
                                        {{ $gasto->usuario->email }}
                                    </small>
                                @else
                                    <span style="color: rgba(255, 255, 255, 0.6);">Usuario no disponible</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon red">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Hace</div>
                            <div class="detail-value">{{ $gasto->fecha_gasto->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar con estadísticas -->
                <div class="stats-sidebar">
                    <!-- Estadísticas rápidas -->
                    @php
                        $estadisticas = [
                            'gastos_mismo_tipo' => \App\Models\Gasto::where('tipo', $gasto->tipo)->count(),
                            'total_tipo' => \App\Models\Gasto::where('tipo', $gasto->tipo)->sum('monto'),
                            'gastos_mismo_mes' => \App\Models\Gasto::whereYear('fecha_gasto', $gasto->fecha_gasto->year)
                                ->whereMonth('fecha_gasto', $gasto->fecha_gasto->month)->count(),
                            'promedio_tipo' => \App\Models\Gasto::where('tipo', $gasto->tipo)->avg('monto'),
                        ];
                    @endphp

                    <div class="stat-card">
                        <div class="stat-icon-large blue">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-value">{{ $estadisticas['gastos_mismo_tipo'] }}</div>
                        <div class="stat-label">Gastos de {{ $gasto->tipo_formateado }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-large orange">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stat-value">${{ number_format($estadisticas['total_tipo'], 2) }}</div>
                        <div class="stat-label">Total en {{ $gasto->tipo_formateado }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-large green">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value">{{ $estadisticas['gastos_mismo_mes'] }}</div>
                        <div class="stat-label">Gastos en {{ $gasto->fecha_gasto->translatedFormat('F Y') }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-large purple">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-value">${{ number_format($estadisticas['promedio_tipo'], 2) }}</div>
                        <div class="stat-label">Promedio {{ $gasto->tipo_formateado }}</div>
                    </div>

                    <!-- Timeline de actividad -->
                    <div class="timeline-card">
                        <h6 class="timeline-title">
                            <i class="fas fa-history"></i>
                            Actividad del Registro
                        </h6>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="timeline-action">Gasto registrado</div>
                                <div class="timeline-time">{{ $gasto->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        @if($gasto->created_at != $gasto->updated_at)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="timeline-action">Última modificación</div>
                                <div class="timeline-time">{{ $gasto->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="actions-section">
            <div class="actions-row">
                <a href="{{ route('admin.gastos.index') }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Volver a la Lista
                </a>
                <a href="{{ route('admin.gastos.edit', $gasto->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i>
                    Editar Gasto
                </a>
                <button type="button" class="btn-action btn-delete" onclick="confirmarEliminacion()">
                    <i class="fas fa-trash"></i>
                    Eliminar Gasto
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title" id="modalEliminarLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <p>¿Estás seguro de que deseas eliminar este gasto?</p>
                    <div class="alert alert-warning">
                        <strong>ID:</strong> #{{ str_pad($gasto->id, 6, '0', STR_PAD_LEFT) }}<br>
                        <strong>Detalle:</strong> {{ $gasto->detalle }}<br>
                        <strong>Monto:</strong> ${{ number_format($gasto->monto, 2) }}<br>
                        <strong>Fecha:</strong> {{ $gasto->fecha_gasto->format('d/m/Y') }}
                    </div>
                    <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <form action="{{ route('admin.gastos.destroy', $gasto->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Eliminar Definitivamente
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

        // Animación de entrada para los elementos
        document.addEventListener('DOMContentLoaded', function() {
            const detailItems = document.querySelectorAll('.detail-item');
            detailItems.forEach((item, index) => {
                item.style.animationDelay = `${0.5 + (index * 0.1)}s`;
                item.classList.add('fade-in-item');
            });

            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${0.7 + (index * 0.1)}s`;
                card.classList.add('fade-in-item');
            });
        });

        // CSS para animaciones
        const style = document.createElement('style');
        style.textContent = `
            .fade-in-item {
                opacity: 0;
                animation: fadeInItem 0.6s ease-out forwards;
            }

            @keyframes fadeInItem {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
