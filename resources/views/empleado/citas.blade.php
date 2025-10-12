<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agenda de Citas - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #f8fafc;
            --white: #ffffff;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;

            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

            --border-radius: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(315deg, #512da8, #00695c, #0d47a1);
            min-height: 100vh;
            color: var(--gray-900);
            line-height: 1.6;
            background-attachment: fixed;
            background-size: cover;
            padding: 1.5rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Botones */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            box-shadow: var(--shadow-md);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary {
            background: var(--gradient-primary);
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
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        /* Card */
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--white) 100%);
            border-bottom: 2px solid var(--primary);
        }

        .card-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Filtros */
        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Tabla de citas */
        .citas-grid {
            display: grid;
            gap: 1.5rem;
        }

        .cita-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            border-left: 4px solid var(--primary);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.5rem;
            align-items: center;
        }

        .cita-card:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-lg);
        }

        .cita-time {
            text-align: center;
            padding: 1rem;
            background: var(--gradient-primary);
            border-radius: var(--border-radius);
            color: white;
            min-width: 100px;
        }

        .cita-time-hour {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .cita-time-date {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .cita-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .cita-info p {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cita-info i {
            color: var(--primary);
            width: 16px;
        }

        .service-tag {
            display: inline-block;
            background: var(--gray-100);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            margin-right: 0.5rem;
            margin-top: 0.5rem;
            color: var(--gray-700);
            font-weight: 500;
            border: 1px solid var(--gray-200);
        }

        .cita-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Estados */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
        }

        .status-pendiente {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #ef6c00;
            border: 1px solid #ffcc80;
        }

        .status-confirmada {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc);
            color: #0277bd;
            border: 1px solid #81d4fa;
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

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            background: white;
            border: 1px solid var(--gray-300);
            color: var(--gray-700);
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination a:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination .active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            width: 90%;
            max-width: 600px;
            box-shadow: var(--shadow-xl);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
            transition: var(--transition);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            color: var(--gray-600);
            background: var(--gray-100);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            transition: var(--transition);
            font-family: inherit;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cita-card {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .cita-actions {
                flex-direction: row;
                justify-content: center;
            }

            .filters {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1>
                    <i class="fas fa-calendar-alt"></i>
                    Agenda de Citas
                </h1>
                <a href="{{ route('empleado.dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Card principal -->
        <div class="card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    Todas las Citas
                </h2>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <form method="GET" action="{{ route('empleado.citas') }}" class="filters">
                    <div class="filter-group">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" onchange="this.form.submit()">
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

                    <div class="filter-group">
                        <label for="fecha_desde">Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                            onchange="this.form.submit()">
                    </div>

                    <div class="filter-group">
                        <label for="fecha_hasta">Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            onchange="this.form.submit()">
                    </div>

                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <a href="{{ route('empleado.citas') }}" class="btn btn-outline">
                            <i class="fas fa-redo"></i> Limpiar Filtros
                        </a>
                    </div>
                </form>

                <!-- Lista de citas -->
                <div class="citas-grid">
                    @forelse($citas as $cita)
                        <div class="cita-card">
                            <!-- Tiempo -->
                            <div class="cita-time">
                                <span class="cita-time-hour">{{ $cita->fecha_hora->format('H:i') }}</span>
                                <span class="cita-time-date">{{ $cita->fecha_hora->format('d/m/Y') }}</span>
                            </div>

                            <!-- Información -->
                            <div class="cita-info">
                                <h3>
                                    <i class="fas fa-user"></i>
                                    {{ $cita->usuario->nombre }}
                                    <span class="status-badge status-{{ $cita->estado }}">
                                        {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                    </span>
                                </h3>
                                <p>
                                    <i class="fas fa-car"></i>
                                    {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }} -
                                    {{ $cita->vehiculo->placa }}
                                </p>
                                <p>
                                    <i class="fas fa-phone"></i>
                                    {{ $cita->usuario->telefono ?? 'Sin teléfono' }}
                                </p>
                                <div>
                                    @foreach ($cita->servicios as $servicio)
                                        <span class="service-tag">
                                            {{ $servicio->nombre }}
                                            (${{ number_format($servicio->pivot->precio, 2) }})
                                        </span>
                                    @endforeach
                                </div>
                                @if ($cita->observaciones)
                                    <p style="margin-top: 0.5rem;">
                                        <i class="fas fa-comment"></i>
                                        <strong>Obs:</strong> {{ Str::limit($cita->observaciones, 50) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Acciones -->
                            <div class="cita-actions">
                                @if ($cita->estado == 'confirmada')
                                    <button onclick="cambiarEstado({{ $cita->id }}, 'en_proceso')"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-play"></i> Iniciar
                                    </button>
                                @elseif($cita->estado == 'en_proceso')
                                    <button onclick="mostrarModalObservaciones({{ $cita->id }})"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Observaciones
                                    </button>
                                @endif

                                <button onclick="verDetalle({{ $cita->id }})" class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i> Ver Detalle
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h3>No hay citas</h3>
                            <p>No se encontraron citas con los filtros seleccionados</p>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                @if ($citas->hasPages())
                    <div class="pagination">
                        {{ $citas->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Observaciones -->
    <div id="observacionesModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModalObservaciones()">&times;</span>
            <h2 style="margin-bottom: 1.5rem;">
                <i class="fas fa-edit"></i> Agregar Observaciones
            </h2>
            <form id="observacionesForm">
                @csrf
                <input type="hidden" id="cita_id_obs" name="cita_id">

                <div class="form-group">
                    <label for="observaciones">Observaciones del servicio:</label>
                    <textarea id="observaciones" name="observaciones" rows="6"
                        placeholder="Escribe aquí las observaciones sobre el servicio..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Guardar Observaciones
                </button>
            </form>
        </div>
    </div>

    <!-- Modal de Detalle -->
    <div id="detalleModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModalDetalle()">&times;</span>
            <div id="detalleContent"></div>
        </div>
    </div>

    <script>
        // Configuración de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Cambiar estado de cita
        function cambiarEstado(citaId, nuevoEstado) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas cambiar el estado de la cita a "${nuevoEstado.replace('_', ' ')}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/empleado/citas/${citaId}/estado`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                estado: nuevoEstado
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                });
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Error al cambiar el estado'
                            });
                        });
                }
            });
        }

        // Modal de observaciones
        function mostrarModalObservaciones(citaId) {
            document.getElementById('cita_id_obs').value = citaId;
            document.getElementById('observacionesModal').style.display = 'flex';
        }

        function cerrarModalObservaciones() {
            document.getElementById('observacionesModal').style.display = 'none';
            document.getElementById('observacionesForm').reset();
        }

        // Guardar observaciones
        document.getElementById('observacionesForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const citaId = document.getElementById('cita_id_obs').value;

            fetch(`/empleado/citas/${citaId}/observaciones`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        cerrarModalObservaciones();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al guardar observaciones'
                    });
                });
        });

        // Ver detalle
        function verDetalle(citaId) {
            fetch(`/empleado/citas/${citaId}/detalles`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cita = data.cita;
                        const detalleHTML = `
                            <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fas fa-calendar-check"></i> Detalle de Cita #${cita.id}
                            </h2>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-user"></i> Cliente
                                    </h3>
                                    <p><strong>Nombre:</strong> ${cita.usuario.nombre}</p>
                                    <p><strong>Email:</strong> ${cita.usuario.email}</p>
                                    <p><strong>Teléfono:</strong> ${cita.usuario.telefono || 'Sin teléfono'}</p>
                                </div>

                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-car"></i> Vehículo
                                    </h3>
                                    <p><strong>Marca:</strong> ${cita.vehiculo.marca}</p>
                                    <p><strong>Modelo:</strong> ${cita.vehiculo.modelo}</p>
                                    <p><strong>Placa:</strong> ${cita.vehiculo.placa}</p>
                                    <p><strong>Color:</strong> ${cita.vehiculo.color || 'No especificado'}</p>
                                </div>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-calendar"></i> Información de la Cita
                                </h3>
                                <p><strong>Fecha y hora:</strong> ${cita.fecha_hora_formatted}</p>
                                <p><strong>Estado:</strong> <span class="status-badge status-${cita.estado}">${cita.estado_formatted}</span></p>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-concierge-bell"></i> Servicios
                                </h3>
                                ${cita.servicios.map(s => `
                                    <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
                                        <i class="fas fa-check" style="color: var(--success);"></i>
                                        ${s.nombre} - $${parseFloat(s.pivot.precio).toFixed(2)}
                                    </div>
                                `).join('')}
                                <div style="text-align: right; margin-top: 1rem; font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                    Total: $${cita.total.toFixed(2)}
                                </div>
                            </div>

                            ${cita.observaciones ? `
                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-comment"></i> Observaciones
                                    </h3>
                                    <p style="padding: 1rem; background: var(--gray-100); border-radius: var(--border-radius);">
                                        ${cita.observaciones}
                                    </p>
                                </div>
                            ` : ''}
                        `;

                        document.getElementById('detalleContent').innerHTML = detalleHTML;
                        document.getElementById('detalleModal').style.display = 'flex';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al cargar detalles'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar detalles'
                    });
                });
        }

        function cerrarModalDetalle() {
            document.getElementById('detalleModal').style.display = 'none';
        }

        // Cerrar modales al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                cerrarModalObservaciones();
                cerrarModalDetalle();
            }
        });
    </script>
</body>

</html>
