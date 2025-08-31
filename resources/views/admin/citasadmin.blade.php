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
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3498db, #27ae60, #f39c12);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px 35px;
            border-radius: 24px;
            margin-bottom: 35px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

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
        }

        .btn-primary {
            background: linear-gradient(135deg, #00695c 0%, #2e7d32 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
            color: white;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }

        .card-header {
            padding: 25px 30px 0;
            border-bottom: 2px solid var(--border-primary);
            margin-bottom: 25px;
        }

        .card-body {
            padding: 0 30px 30px;
        }

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
            font-weight: 700;
            color: var(--text-primary);
        }

        .admin-table td {
            padding: 18px 15px;
            border-bottom: 1px solid var(--border-primary);
            background: rgba(255, 255, 255, 0.98);
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
            padding: 8px 12px;
            border-radius: 8px;
            border: 2px solid var(--border-primary);
            background: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
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
            border: 2px solid rgba(39, 174, 96, 0.2);
            border-radius: 10px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .page-link:hover,
        .page-link.active {
            background: var(--primary);
            color: white;
        }

        .filter-badge {
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-calendar me-2"></i> Administración de Citas</h1>
                    <p class="mb-0">Gestiona y actualiza el estado de todas las citas del sistema</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card">
            <div class="card-body">
                <form id="filtros-form" method="GET" action="{{ route('admin.citasadmin.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filtro-estado" class="form-label">Filtrar por estado:</label>
                            <select id="filtro-estado" name="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-fecha" class="form-label">Filtrar por fecha:</label>
                            <input type="date" id="filtro-fecha" name="fecha" class="form-control" value="{{ request('fecha') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="buscar" class="form-label">Buscar:</label>
                            <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Cliente, vehículo, placa..." value="{{ request('buscar') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2 w-100">
                                <i class="fas fa-filter me-1"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.citasadmin.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-broom me-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de citas -->
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-list me-2"></i> Lista de Citas</h2>
                @if(request()->anyFilled(['estado', 'fecha', 'buscar']))
                    <p class="text-muted mb-0 mt-2">
                        Filtros aplicados: 
                        @if(request('estado'))
                            <span class="filter-badge">Estado: {{ ucfirst(request('estado')) }}</span>
                        @endif
                        @if(request('fecha'))
                            <span class="filter-badge">Fecha: {{ request('fecha') }}</span>
                        @endif
                        @if(request('buscar'))
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
                                        <strong>{{ $cita->usuario->nombre }}</strong><br>
                                        <small class="text-muted">{{ $cita->usuario->email }}</small><br>
                                        <small class="text-muted">{{ $cita->usuario->telefono ?? 'Sin teléfono' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}</strong><br>
                                        <small class="text-muted">Placa: {{ $cita->vehiculo->placa }}</small><br>
                                        <small class="text-muted">Año: {{ $cita->vehiculo->anio }}</small>
                                    </td>
                                    <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @foreach($cita->servicios as $servicio)
                                            <span class="service-badge">{{ $servicio->nombre }}</span>
                                        @endforeach
                                    </td>
                                    <td>${{ number_format($cita->total, 2) }}</td>
                                    <td>
                                        <span class="appointment-status status-{{ $cita->estado }}">{{ $cita->estado_formatted }}</span>
                                    </td>
                                    <td>
                                        <select class="estado-select" data-cita-id="{{ $cita->id }}">
                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="en_proceso" {{ $cita->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                            <option value="finalizada" {{ $cita->estado == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        <button class="btn btn-info mt-2 w-100 view-details" data-cita-id="{{ $cita->id }}">
                                            <i class="fas fa-eye me-1"></i> Detalles
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No hay citas registradas</h4>
                                        @if(request()->anyFilled(['estado', 'fecha', 'buscar']))
                                            <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                                            <a href="{{ route('admin.citasadmin.index') }}" class="btn btn-primary mt-2">
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
                @if($citas->hasPages())
                    <div class="pagination">
                        @if($citas->onFirstPage())
                            <span class="page-link disabled">&laquo;</span>
                        @else
                            <a href="{{ $citas->previousPageUrl() }}" class="page-link">&laquo;</a>
                        @endif

                        @foreach(range(1, $citas->lastPage()) as $page)
                            <a href="{{ $citas->url($page) }}" class="page-link {{ $citas->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        @if($citas->hasMorePages())
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
                    body: JSON.stringify({ estado: nuevoEstado })
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
                    
                    fetch(`/admin/citas/${citaId}/detalles`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al cargar los detalles');
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.close();
                        document.getElementById('cita-id').textContent = data.id;
                        
                        let serviciosHTML = '';
                        data.servicios.forEach(servicio => {
                            serviciosHTML += `
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span>${servicio.nombre}</span>
                                    <span>$${servicio.pivot.precio.toFixed(2)}</span>
                                </div>
                            `;
                        });
                        
                        document.getElementById('detalles-cita-content').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información del Cliente</h6>
                                    <p><strong>Nombre:</strong> ${data.usuario.nombre}</p>
                                    <p><strong>Email:</strong> ${data.usuario.email}</p>
                                    <p><strong>Teléfono:</strong> ${data.usuario.telefono || 'No proporcionado'}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Información del Vehículo</h6>
                                    <p><strong>Marca/Modelo:</strong> ${data.vehiculo.marca} ${data.vehiculo.modelo}</p>
                                    <p><strong>Placa:</strong> ${data.vehiculo.placa}</p>
                                    <p><strong>Año:</strong> ${data.vehiculo.anio}</p>
                                    <p><strong>Color:</strong> ${data.vehiculo.color || 'No especificado'}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Detalles de la Cita</h6>
                                    <p><strong>Fecha/Hora:</strong> ${new Date(data.fecha_hora).toLocaleString('es-ES')}</p>
                                    <p><strong>Estado:</strong> <span class="appointment-status status-${data.estado}">${data.estado_formatted}</span></p>
                                    ${data.observaciones ? `<p><strong>Observaciones:</strong> ${data.observaciones}</p>` : ''}
                                    <p><strong>Fecha de creación:</strong> ${new Date(data.created_at).toLocaleString('es-ES')}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Servicios</h6>
                                    ${serviciosHTML}
                                    <div class="d-flex justify-content-between mt-3 fw-bold fs-5 border-top pt-2">
                                        <span>Total:</span>
                                        <span>$${data.total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Mostrar el modal
                        const modal = new bootstrap.Modal(document.getElementById('detallesCitaModal'));
                        modal.show();
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los detalles de la cita'
                        });
                    });
                });
            });
        });
    </script>
</body>
</html>