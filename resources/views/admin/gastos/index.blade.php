<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Gastos - AutoGest Admin</title>
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
            top: 10%;
            left: 80%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            left: 10%;
            animation-delay: 7s;
        }

        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 20%;
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
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            backdrop-filter: blur(10px);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-primary-modern {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-outline-modern {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        /* Estadísticas */
        .stats-container {
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
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

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-icon.blue { background: linear-gradient(135deg, var(--primary-blue), #5dade2); }
        .stat-icon.green { background: linear-gradient(135deg, var(--success-green), #58d68d); }
        .stat-icon.orange { background: linear-gradient(135deg, var(--warning-orange), #f7dc6f); }
        .stat-icon.purple { background: linear-gradient(135deg, #9b59b6, #bb8fce); }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Filtros */
        .filters-section {
            padding: 0 2rem 2rem;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .filters-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: white;
            font-weight: 500;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
        }

        .form-select-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: white;
            font-weight: 500;
        }

        .form-select-modern option {
            background: var(--text-primary);
            color: white;
        }

        /* Tabla */
        .table-section {
            padding: 0 2rem 2rem;
            animation: fadeInUp 1s ease-out 0.7s both;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            backdrop-filter: blur(5px);
        }

        .table-modern {
            margin: 0;
            color: white;
        }

        .table-modern thead {
            background: rgba(0, 0, 0, 0.2);
        }

        .table-modern th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-modern td {
            border: none;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        .table-modern tbody tr {
            transition: background-color 0.2s ease;
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .badge-modern {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid;
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

        .btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            border: none;
            color: white;
            transition: all 0.3s ease;
            margin: 0 0.2rem;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-info { background: linear-gradient(135deg, var(--primary-blue), #5dade2); }
        .btn-warning { background: linear-gradient(135deg, var(--warning-orange), #f7dc6f); }
        .btn-danger { background: linear-gradient(135deg, #e74c3c, #ec7063); }

        /* Estado vacío */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
            }

            .header-title {
                font-size: 2rem;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }

        /* Alertas personalizadas */
        .alert-modern {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 1rem 2rem;
            backdrop-filter: blur(10px);
        }

        .alert-success-modern {
            background: rgba(39, 174, 96, 0.15);
            color: white;
            border: 1px solid rgba(39, 174, 96, 0.3);
        }

        .alert-error-modern {
            background: rgba(231, 76, 60, 0.15);
            color: white;
            border: 1px solid rgba(231, 76, 60, 0.3);
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

        /* Paginación */
        .pagination-modern .page-link {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            margin: 0 0.2rem;
            border-radius: 8px;
        }

        .pagination-modern .page-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .pagination-modern .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green));
            border-color: transparent;
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
                    <i class="fas fa-chart-line me-3"></i>
                    Gestión de Gastos
                </h1>
                <p class="header-subtitle">
                    Control financiero y seguimiento de gastos operativos del carwash
                </p>
                <div class="action-buttons">
                    <a href="{{ route('admin.dashboard') }}" class="btn-modern btn-outline-modern">
                        <i class="fas fa-arrow-left"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.gastos.create') }}" class="btn-modern btn-primary-modern">
                        <i class="fas fa-plus"></i>
                        Registrar Gasto
                    </a>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert-modern alert-success-modern">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-modern alert-error-modern">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">${{ number_format($estadisticas['total_mes'], 2) }}</div>
                <div class="stat-label">Total Este Mes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-value">{{ $estadisticas['total_gastos'] }}</div>
                <div class="stat-label">Total de Gastos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-value">${{ number_format($estadisticas['promedio_diario'], 2) }}</div>
                <div class="stat-label">Promedio Diario</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="stat-value">${{ number_format($estadisticas['gasto_mayor'], 2) }}</div>
                <div class="stat-label">Gasto Mayor</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <div class="filters-card">
                <form method="GET" action="{{ route('admin.gastos.index') }}">
                    <div class="filter-row">
                        <div>
                            <label class="form-label text-white-50">Buscar...</label>
                            <input type="text" name="buscar" class="form-control-modern"
                                   placeholder="Buscar por detalle..."
                                   value="{{ request('buscar') }}">
                        </div>
                        <div>
                            <label class="form-label text-white-50">Tipo de Gasto</label>
                            <select name="tipo" class="form-select-modern">
                                <option value="todos">Todos los tipos</option>
                                @foreach($tipos as $key => $tipo)
                                    <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-white-50">Fecha</label>
                            <input type="date" name="fecha" class="form-control-modern"
                                   value="{{ request('fecha') }}">
                        </div>
                        <div>
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-search"></i>
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de gastos -->
        <div class="table-section">
            @if($gastos->count() > 0)
                <div class="table-container">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Detalle</th>
                                <th>Monto</th>
                                <th>Registrado por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gastos as $gasto)
                            <tr>
                                <td>
                                    <strong>{{ $gasto->fecha_gasto->format('d/m/Y') }}</strong><br>
                                    <small class="text-white-50">{{ $gasto->fecha_gasto->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge-modern badge-{{ $gasto->tipo }}">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $gasto->tipo_formateado }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $gasto->detalle }}">
                                        {{ $gasto->detalle }}
                                    </div>
                                </td>
                                <td>
                                    <strong style="font-size: 1.1rem; color: var(--warning-orange);">
                                        ${{ number_format($gasto->monto, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @if($gasto->usuario)
                                        <div>
                                            <i class="fas fa-user me-1"></i>
                                            {{ $gasto->usuario->nombre }}
                                        </div>
                                        <small class="text-white-50">{{ $gasto->usuario->email }}</small>
                                    @else
                                        <span class="text-white-50">Usuario no disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.gastos.show', $gasto->id) }}"
                                           class="btn-action btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.gastos.edit', $gasto->id) }}"
                                           class="btn-action btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-danger"
                                                onclick="confirmarEliminacion({{ $gasto->id }}, '{{ $gasto->detalle }}', {{ $gasto->monto }})"
                                                title="Eliminar">
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
                @if($gastos->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav class="pagination-modern">
                            {{ $gastos->links() }}
                        </nav>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <h3>No hay gastos registrados</h3>
                    <p>Aún no se han registrado gastos. Comienza registrando el primer gasto.</p>
                    <a href="{{ route('admin.gastos.create') }}" class="btn-modern btn-primary-modern mt-3">
                        <i class="fas fa-plus me-2"></i>
                        Registrar Primer Gasto
                    </a>
                </div>
            @endif
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
                        <strong>Detalle:</strong> <span id="gastoDetalle"></span><br>
                        <strong>Monto:</strong> $<span id="gastoMonto"></span>
                    </div>
                    <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <form id="formEliminar" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Eliminar Gasto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion(id, detalle, monto) {
            document.getElementById('gastoDetalle').textContent = detalle;
            document.getElementById('gastoMonto').textContent = parseFloat(monto).toFixed(2);

            const form = document.getElementById('formEliminar');
            form.action = `{{ route('admin.gastos.index') }}/${id}`;

            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

        // Animación de entrada para las filas de la tabla
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${0.9 + (index * 0.1)}s`;
                row.classList.add('fade-in-row');
            });
        });

        // CSS para animación de filas
        const style = document.createElement('style');
        style.textContent = `
            .fade-in-row {
                opacity: 0;
                animation: fadeInRow 0.6s ease-out forwards;
            }

            @keyframes fadeInRow {
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
