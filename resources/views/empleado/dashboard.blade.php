<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Empleado - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #4a6fa5 0%, #166088 100%);
            --secondary-gradient: linear-gradient(45deg, #166088 0%, #4a6fa5 100%);
            --success-gradient: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%);
            --warning-gradient: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #333;
            --text-secondary: #666;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: var(--glass-bg);
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-section {
            flex: 1;
        }

        .welcome-section h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-icon {
            background: var(--secondary-gradient);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .welcome-section p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .welcome-stats {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .welcome-stat {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 10px;
            text-align: center;
            min-width: 80px;
        }

        .welcome-stat .number {
            font-size: 1.1rem;
            font-weight: 700;
            color: #166088;
        }

        .welcome-stat .label {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Botones */
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--secondary-gradient);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #166088;
            color: #166088;
        }

        .btn-outline:hover {
            background: #166088;
            color: white;
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        /* Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .main-section, .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Cards */
        .card {
            background: var(--glass-bg);
            border-radius: 15px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header .icon {
            background: var(--secondary-gradient);
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .card-body {
            padding: 15px 20px;
        }

        /* Citas */
        .appointment-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #166088;
        }

        .appointment-card h3 {
            font-size: 1rem;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .status-en-proceso {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-finalizado {
            background: #d4edda;
            color: #155724;
        }

        .service-tag {
            display: inline-block;
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .appointment-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        /* Historial */
        .service-history-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .service-icon {
            background: var(--secondary-gradient);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
        }

        .service-details {
            flex: 1;
        }

        .service-details h4 {
            font-size: 0.95rem;
            margin-bottom: 3px;
        }

        .service-details p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .service-price {
            font-weight: 600;
            color: #166088;
        }

        /* Tareas */
        .task-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .task-details h4 {
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .task-details p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Acciones rápidas */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
        }

        /* Perfil */
        .profile-summary {
            text-align: center;
        }

        .profile-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--secondary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 10px;
        }

        /* Modales */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
        }

        .close-modal {
            float: right;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome-stats {
                justify-content: center;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <div class="welcome-icon">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        ¡Bienvenido, {{ $user->nombre ?? 'Empleado' }}!
                    </h1>
                    <p>Panel de control para gestión de citas y servicios</p>
                    <div class="welcome-stats">
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['citas_hoy'] ?? 0 }}</span>
                            <span class="label">Citas Hoy</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['citas_proceso'] ?? 0 }}</span>
                            <span class="label">En Proceso</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['citas_finalizadas'] ?? 0 }}</span>
                            <span class="label">Finalizadas</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('empleado.citas') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-day"></i> Ver Agenda
                    </a>
                    <a href="{{ route('configuracion.index') }}" class="btn btn-outline">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="dashboard-grid">
            <div class="main-section">
                <!-- Citas de Hoy -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            Citas para Hoy
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        @if (isset($citas_hoy) && count($citas_hoy) > 0)
                            @foreach ($citas_hoy as $cita)
                                <div class="appointment-card">
                                    <h3>
                                        <i class="fas fa-user"></i> {{ $cita->cliente->nombre }}
                                        <span class="status-badge status-{{ $cita->estado }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </h3>
                                    <p><i class="fas fa-car"></i> {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }} - {{ $cita->vehiculo->placa }}</p>
                                    <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</p>
                                    
                                    <div style="margin: 10px 0;">
                                        @foreach ($cita->servicios as $servicio)
                                            <span class="service-tag">{{ $servicio->nombre }} (${{ number_format($servicio->precio, 2) }})</span>
                                        @endforeach
                                    </div>
                                    
                                    @if ($cita->observaciones_cliente)
                                        <p><i class="fas fa-comment"></i> <strong>Observaciones:</strong> {{ $cita->observaciones_cliente }}</p>
                                    @endif
                                    
                                    <div class="appointment-actions">
                                        @if ($cita->estado == 'pendiente')
                                            <button onclick="cambiarEstadoCita({{ $cita->id }}, 'en_proceso')" class="btn btn-sm btn-primary">
                                                <i class="fas fa-play"></i> Iniciar
                                            </button>
                                        @elseif ($cita->estado == 'en_proceso')
                                            <button onclick="mostrarModalFinalizar({{ $cita->id }})" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Finalizar
                                            </button>
                                            <button onclick="mostrarModalObservaciones({{ $cita->id }})" class="btn btn-sm btn-outline">
                                                <i class="fas fa-edit"></i> Observaciones
                                            </button>
                                        @endif
                                        
                                        <button onclick="verDetalleCita({{ $cita->id }})" class="btn btn-sm btn-outline">
                                            <i class="fas fa-eye"></i> Detalles
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-calendar-times" style="font-size: 2rem; color: #166088; margin-bottom: 10px;"></i>
                                <h3>No hay citas programadas para hoy</h3>
                                <p>Revisa el calendario para ver futuras citas</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historial Reciente -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-history"></i>
                            </div>
                            Historial Reciente
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if (isset($historial) && count($historial) > 0)
                            @foreach ($historial as $cita)
                                <div class="service-history-item">
                                    <div class="service-icon">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div class="service-details">
                                        <h4>{{ $cita->cliente->nombre }}</h4>
                                        <p><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d M Y - h:i A') }}</p>
                                        <p><i class="fas fa-car"></i> {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}</p>
                                        @if ($cita->observaciones_empleado)
                                            <p><i class="fas fa-comment"></i> {{ Str::limit($cita->observaciones_empleado, 50) }}</p>
                                        @endif
                                    </div>
                                    <div class="service-price">
                                        ${{ number_format($cita->servicios->sum('precio'), 2) }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-history" style="font-size: 2rem; color: #166088; margin-bottom: 10px;"></i>
                                <h3>No hay historial reciente</h3>
                                <p>Los servicios que completes aparecerán aquí</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar-section">
                <!-- Tareas y Recordatorios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            Tareas Pendientes
                        </h2>
                    </div>
                    <div class="card-body">
                        @if (isset($tareas) && count($tareas) > 0)
                            @foreach ($tareas as $tarea)
                                <div class="task-item">
                                    <input type="checkbox" id="task-{{ $tarea->id }}" onchange="marcarTareaCompleta({{ $tarea->id }}, this)" style="margin-right: 10px;">
                                    <div class="task-details">
                                        <h4>{{ $tarea->titulo }}</h4>
                                        <p>{{ $tarea->descripcion }}</p>
                                        <small>Asignada por: {{ $tarea->asignador->nombre }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-check-circle" style="font-size: 2rem; color: #166088; margin-bottom: 10px;"></i>
                                <h3>No hay tareas pendientes</h3>
                                <p>¡Buen trabajo! No tienes tareas asignadas.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            Acciones Rápidas
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <button class="quick-action-btn">
                                <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Nueva Cita</span>
                            </button>
                            <button class="quick-action-btn">
                                <i class="fas fa-car" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Registrar Vehículo</span>
                            </button>
                            <button class="quick-action-btn">
                                <i class="fas fa-file-invoice" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Generar Recibo</span>
                            </button>
                            <button class="quick-action-btn">
                                <i class="fas fa-question" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Ayuda</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Perfil del Empleado -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            Mi Perfil
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="profile-summary">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h3>{{ $user->nombre ?? 'Empleado' }}</h3>
                                <p><i class="fas fa-envelope"></i> {{ $user->email ?? 'No especificado' }}</p>
                                <p><i class="fas fa-id-badge"></i> Rol: Empleado</p>
                                <p><i class="fas fa-calendar"></i> Miembro desde {{ $user->created_at->format('M Y') }}</p>
                            </div>
                            
                            <button onclick="openEditModal()" class="btn btn-outline" style="margin-top: 15px; width: 100%;">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    <div id="finalizarCitaModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeFinalizarModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i> Finalizar Servicio
            </h2>
            <form id="finalizarCitaForm">
                @csrf
                <input type="hidden" id="cita_id_finalizar" name="cita_id">
                
                <div class="form-group">
                    <label for="observaciones_finalizar">Observaciones:</label>
                    <textarea id="observaciones_finalizar" name="observaciones" rows="4" class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" class="form-control">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
                
                <div id="efectivoFields">
                    <div class="form-group">
                        <label for="monto_recibido">Monto Recibido ($):</label>
                        <input type="number" step="0.01" id="monto_recibido" name="monto_recibido" class="form-control">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-check"></i> Confirmar Finalización
                </button>
            </form>
        </div>
    </div>

    <div id="observacionesModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeObservacionesModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">
                <i class="fas fa-edit"></i> Agregar Observaciones
            </h2>
            <form id="observacionesForm">
                @csrf
                <input type="hidden" id="cita_id_observaciones" name="cita_id">
                
                <div class="form-group">
                    <label for="observaciones_texto">Observaciones:</label>
                    <textarea id="observaciones_texto" name="observaciones" rows="6" class="form-control"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-save"></i> Guardar Observaciones
                </button>
            </form>
        </div>
    </div>

    <div id="detalleCitaModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeDetalleModal()">&times;</span>
            <div id="detalleCitaContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>

    <script>
        // Configuración global de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Funciones para modales
        function mostrarModalFinalizar(citaId) {
            document.getElementById('cita_id_finalizar').value = citaId;
            document.getElementById('finalizarCitaModal').style.display = 'flex';
        }
        
        function closeFinalizarModal() {
            document.getElementById('finalizarCitaModal').style.display = 'none';
        }
        
        function mostrarModalObservaciones(citaId) {
            document.getElementById('cita_id_observaciones').value = citaId;
            document.getElementById('observacionesModal').style.display = 'flex';
        }
        
        function closeObservacionesModal() {
            document.getElementById('observacionesModal').style.display = 'none';
        }
        
        function verDetalleCita(citaId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            const detalleContent = `
                <h2 style="margin-bottom: 15px;">
                    <i class="fas fa-calendar-check"></i> Detalle de Cita
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                            <i class="fas fa-user"></i> Información del Cliente
                        </h3>
                        <p><strong>Nombre:</strong> Juan Pérez</p>
                        <p><strong>Teléfono:</strong> 5555-1234</p>
                        <p><strong>Email:</strong> juan@example.com</p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </h3>
                        <p><strong>Marca/Modelo:</strong> Toyota Corolla</p>
                        <p><strong>Año:</strong> 2020</p>
                        <p><strong>Color:</strong> Rojo</p>
                        <p><strong>Placa:</strong> P123456</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                        <i class="fas fa-concierge-bell"></i> Servicios
                    </h3>
                    <ul style="list-style: none;">
                        <li style="padding: 6px 0; border-bottom: 1px solid #eee;">
                            <i class="fas fa-check" style="color: #166088;"></i> Lavado Completo - $25.00
                        </li>
                        <li style="padding: 6px 0; border-bottom: 1px solid #eee;">
                            <i class="fas fa-check" style="color: #166088;"></i> Aspirado Interior - $15.00
                        </li>
                    </ul>
                    <div style="text-align: right; margin-top: 10px; font-weight: bold;">
                        Total: $40.00
                    </div>
                </div>
                
                <div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                        <i class="fas fa-comment"></i> Observaciones
                    </h3>
                    <p style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        El cliente solicita especial atención a las manchas en los asientos traseros.
                    </p>
                </div>
            `;
            
            document.getElementById('detalleCitaContent').innerHTML = detalleContent;
            document.getElementById('detalleCitaModal').style.display = 'flex';
        }
        
        function closeDetalleModal() {
            document.getElementById('detalleCitaModal').style.display = 'none';
        }
        
        // Manejar cambio de método de pago
        document.getElementById('metodo_pago').addEventListener('change', function() {
            const efectivoFields = document.getElementById('efectivoFields');
            efectivoFields.style.display = this.value === 'efectivo' ? 'block' : 'none';
        });
        
        // Cambiar estado de cita
        function cambiarEstadoCita(citaId, estado) {
            fetch(`/empleado/citas/${citaId}/estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ estado: estado })
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
        
        // Formulario para finalizar cita
        document.getElementById('finalizarCitaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/empleado/citas/finalizar', {
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
                    title: 'Error al finalizar la cita'
                });
            });
        });
        
        // Formulario para guardar observaciones
        document.getElementById('observacionesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/empleado/citas/observaciones', {
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
        
        // Marcar tarea como completa
        function marcarTareaCompleta(tareaId, checkbox) {
            fetch(`/empleado/tareas/${tareaId}/completar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ completada: checkbox.checked })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    checkbox.checked = !checkbox.checked;
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !checkbox.checked;
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar tarea'
                });
            });
        }
        
        // Cerrar modales al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                document.getElementById('finalizarCitaModal').style.display = 'none';
                document.getElementById('observacionesModal').style.display = 'none';
                document.getElementById('detalleCitaModal').style.display = 'none';
            }
        });
    </script>
</body>

</html>