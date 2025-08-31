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

        .badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            color: white;
        }

        .badge-pendiente {
            background: linear-gradient(135deg, #ffb74d 0%, #ff9800 100%);
        }

        .badge-confirmada {
            background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
        }

        .badge-en_proceso {
            background: linear-gradient(135deg, #7e57c2 0%, #5e35b1 100%);
        }

        .badge-finalizada {
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
        }

        .badge-cancelada {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        }

        .estado-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 2px solid var(--border-primary);
            background: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
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
                <div class="row">
                    <div class="col-md-4">
                        <label for="filtro-estado" class="form-label">Filtrar por estado:</label>
                        <select id="filtro-estado" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="finalizada">Finalizada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filtro-fecha" class="form-label">Filtrar por fecha:</label>
                        <input type="date" id="filtro-fecha" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="buscar" class="form-label">Buscar:</label>
                        <input type="text" id="buscar" class="form-control" placeholder="Cliente, vehículo...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de citas -->
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-list me-2"></i> Lista de Citas</h2>
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
                                    <td>{{ $cita->usuario->nombre }}<br><small>{{ $cita->usuario->email }}</small></td>
                                    <td>{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}<br><small>{{ $cita->vehiculo->placa }}</small></td>
                                    <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @foreach($cita->servicios as $servicio)
                                            <span class="badge bg-secondary mb-1">{{ $servicio->nombre }}</span><br>
                                        @endforeach
                                    </td>
                                    <td>${{ number_format($cita->total, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $cita->estado }}">{{ $cita->estado_formatted }}</span>
                                    </td>
                                    <td>
                                        <select class="estado-select" data-cita-id="{{ $cita->id }}">
                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="en_proceso" {{ $cita->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                            <option value="finalizada" {{ $cita->estado == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        <button class="btn btn-sm btn-info mt-2 view-details" data-cita-id="{{ $cita->id }}">
                                            <i class="fas fa-eye"></i> Detalles
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No hay citas registradas</h4>
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
                    
                    fetch(`/admin/citasadmin/${citaId}/actualizar-estado`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ estado: nuevoEstado })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Actualizar el badge de estado
                            const badge = this.closest('tr').querySelector('.badge');
                            badge.className = `badge badge-${nuevoEstado}`;
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
                });
            });

            // Ver detalles de cita
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    const citaId = this.getAttribute('data-cita-id');
                    
                    fetch(`/admin/citas/${citaId}`)
                    .then(response => response.json())
                    .then(data => {
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
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Detalles de la Cita</h6>
                                    <p><strong>Fecha/Hora:</strong> ${new Date(data.fecha_hora).toLocaleString('es-ES')}</p>
                                    <p><strong>Estado:</strong> <span class="badge badge-${data.estado}">${data.estado_formatted}</span></p>
                                    ${data.observaciones ? `<p><strong>Observaciones:</strong> ${data.observaciones}</p>` : ''}
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Servicios</h6>
                                    ${serviciosHTML}
                                    <div class="d-flex justify-content-between mt-2 fw-bold">
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
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los detalles de la cita'
                        });
                    });
                });
            });

            // Filtros
            document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
            document.getElementById('filtro-fecha').addEventListener('change', aplicarFiltros);
            document.getElementById('buscar').addEventListener('input', aplicarFiltros);
            
            function aplicarFiltros() {
                const estado = document.getElementById('filtro-estado').value;
                const fecha = document.getElementById('filtro-fecha').value;
                const busqueda = document.getElementById('buscar').value.toLowerCase();
                
                const rows = document.querySelectorAll('.admin-table tbody tr');
                
                rows.forEach(row => {
                    let mostrar = true;
                    
                    // Filtrar por estado
                    if (estado) {
                        const estadoCita = row.querySelector('.badge').classList[1].replace('badge-', '');
                        if (estadoCita !== estado) {
                            mostrar = false;
                        }
                    }
                    
                    // Filtrar por fecha
                    if (fecha) {
                        const fechaCita = row.cells[3].textContent.split(' ')[0];
                        if (fechaCita !== fecha.split('-').reverse().join('/')) {
                            mostrar = false;
                        }
                    }
                    
                    // Filtrar por búsqueda
                    if (busqueda) {
                        const textoFila = row.textContent.toLowerCase();
                        if (!textoFila.includes(busqueda)) {
                            mostrar = false;
                        }
                    }
                    
                    row.style.display = mostrar ? '' : 'none';
                });
            }
        });
    </script>
</body>
</html>