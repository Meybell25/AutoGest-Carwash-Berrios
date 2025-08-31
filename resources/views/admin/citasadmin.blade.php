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

        /* Botón detalles - Nuevo color que armoniza */
        .btn-details {
            background: linear-gradient(135deg, #ff8f00 0%, #f57c00 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 143, 0, 0.3);
        }

        .btn-details:hover {
            background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
            box-shadow: 0 8px 25px rgba(255, 143, 0, 0.4);
            color: white;
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

        /* Mejora en los filtros */
        .filter-group {
            margin-bottom: 1.5rem;
        }

        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
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

            .admin-table {
                display: block;
                overflow-x: auto;
            }

            .admin-table th,
            .admin-table td {
                padding: 12px 8px;
                font-size: 0.85rem;
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
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card">
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
                            <div class="d-flex w-100" style="gap: 10px;">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="{{ route('admin.citasadmin.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-broom"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de citas - INFORMACIÓN SIMPLIFICADA -->
        <div class="card">
            <div class="card-header">
                <h2
                    style="font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); margin-bottom: 10px;">
                    <i class="fas fa-list"></i>
                    Lista de Citas
                </h2>
                @if (request()->anyFilled(['estado', 'fecha', 'buscar']))
                    <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0;">
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
                                                {{ $cita->estado == 'finalizada' ? 'selected' : '' }}>Finalizada
                                            </option>
                                            <option value="cancelada"
                                                {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
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

    <!-- Modal para detalles de cita -->
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cambiar estado de cita
            document.querySelectorAll('.estado-select').forEach(select => {
                select.addEventListener('change', function() {
                    const citaId = this.getAttribute('data-cita-id');
                    const nuevoEstado = this.value;

                    // Mostrar confirmación para cambios de estado importantes
                    if (nuevoEstado === 'cancelada') {
                        Swal.fire({
                            title: '¿Confirmar cancelación?',
                            text: 'Esta acción cancelará la cita. ¿Estás seguro?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, cancelar',
                            cancelButtonText: 'No, mantener'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                actualizarEstadoCita(citaId, nuevoEstado);
                            } else {
                                // Restaurar el valor anterior
                                this.value = this._previousValue;
                            }
                        });
                    } else {
                        actualizarEstadoCita(citaId, nuevoEstado);
                    }

                    // Guardar el valor anterior para posible restauración
                    this._previousValue = this.value;
                });
            });

            function actualizarEstadoCita(citaId, nuevoEstado) {
                fetch(`/admin/citasadmin/${citaId}/actualizar-estado`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            estado: nuevoEstado
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Actualizar el badge de estado
                            const badge = document.querySelector(`.estado-select[data-cita-id="${citaId}"]`)
                                .closest('tr').querySelector('.appointment-status');
                            badge.className = `appointment-status status-${nuevoEstado}`;
                            badge.textContent = data.nuevo_estado;

                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al actualizar el estado'
                        });
                    });
            }

            // Ver detalles de cita
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    const citaId = this.getAttribute('data-cita-id');

                    // Mostrar loading
                    Swal.fire({
                        title: 'Cargando detalles',
                        text: 'Por favor espere...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Ruta corregida: /admin/citasadmin/{id}/detalles
                    fetch(`/admin/citasadmin/${citaId}/detalles`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al cargar los detalles');
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.close();

                            if (data.error) {
                                throw new Error(data.error);
                            }

                            document.getElementById('cita-id').textContent = data.id;

                            let serviciosHTML = '';
                            if (data.servicios && data.servicios.length > 0) {
                                data.servicios.forEach(servicio => {
                                    const precio = servicio.pivot?.precio || servicio
                                        .precio || 0;
                                    serviciosHTML += `
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span>${servicio.nombre}</span>
                                        <span>$${precio.toFixed(2)}</span>
                                    </div>
                                `;
                                });
                            } else {
                                serviciosHTML =
                                    '<p class="text-muted">No hay servicios registrados</p>';
                            }

                            document.getElementById('detalles-cita-content').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Información del Cliente</h6>
                                    <p><strong>Nombre:</strong> ${data.usuario.nombre}</p>
                                    <p><strong>Email:</strong> ${data.usuario.email}</p>
                                    <p><strong>Teléfono:</strong> ${data.usuario.telefono || 'No proporcionado'}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Información del Vehículo</h6>
                                    <p><strong>Marca/Modelo:</strong> ${data.vehiculo.marca} ${data.vehiculo.modelo}</p>
                                    <p><strong>Placa:</strong> ${data.vehiculo.placa}</p>
                                    <p><strong>Año:</strong> ${data.vehiculo.anio}</p>
                                    <p><strong>Color:</strong> ${data.vehiculo.color || 'No especificado'}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="fw-bold">Detalles de la Cita</h6>
                                    <p><strong>Fecha/Hora:</strong> ${new Date(data.fecha_hora).toLocaleString('es-ES')}</p>
                                    <p><strong>Estado:</strong> <span class="appointment-status status-${data.estado}">${data.estado_formatted}</span></p>
                                    ${data.observaciones ? `<p><strong>Observaciones:</strong> ${data.observaciones}</p>` : ''}
                                    <p><strong>Fecha de creación:</strong> ${new Date(data.created_at).toLocaleString('es-ES')}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="fw-bold">Servicios</h6>
                                    ${serviciosHTML}
                                    <div class="d-flex justify-content-between mt-3 fw-bold fs-5 border-top pt-2">
                                        <span>Total:</span>
                                        <span>$${data.total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        `;

                            // Mostrar el modal
                            const modal = new bootstrap.Modal(document.getElementById(
                                'detallesCitaModal'));
                            modal.show();
                        })
                        .catch(error => {
                            Swal.close();
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message ||
                                    'No se pudieron cargar los detalles de la cita'
                            });
                        });
                });
            });
        });
    </script>
</body>

</html>
