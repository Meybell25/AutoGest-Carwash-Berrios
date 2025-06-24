<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - AutoGest Carwash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <style>
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
            --border-light: rgba(255, 255, 255, 0.2);
            --border-primary: rgba(39, 174, 96, 0.2);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3498db, #27ae60, #f39c12);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Partículas flotantes de fondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(39, 174, 96, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(52, 152, 219, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(243, 156, 18, 0.05) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
            position: relative;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border: 1px solid var(--border-light);
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }

        .btn-primary:hover {
            background: #1e5e24;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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
            background: var(--success);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-info {
            background: rgba(2, 119, 189, 0.1);
            color: var(--info);
        }

        .btn-info:hover {
            background: var(--info);
            color: white;
        }

        .border-red-500 {
            border-color: #ef4444 !important;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .hidden {
            display: none;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid var(--border-light);
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .search-filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            width: 100%;
        }

        .search-box,
        .filter-select,
        .export-buttons {
            flex: 1;
            min-width: 250px;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8fafc;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid #eee;
        }

        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .table tr:hover td {
            background: rgba(39, 174, 96, 0.03);
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-primary {
            background: rgba(46, 125, 50, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background: rgba(56, 142, 60, 0.1);
            color: var(--success);
        }

        .badge-danger {
            background: rgba(198, 40, 40, 0.1);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(2, 119, 189, 0.1);
            color: var(--info);
        }

        .badge-warning {
            background: rgba(216, 67, 21, 0.1);
            color: var(--warning);
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .btn-edit {
            background: rgba(255, 152, 0, 0.1);
            color: #ff9800;
        }

        .btn-edit:hover {
            background: #ff9800;
            color: white;
        }

        .btn-delete {
            background: rgba(198, 40, 40, 0.1);
            color: #c62828;
        }

        .btn-delete:hover {
            background: #c62828;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
        }

        .page-link {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .page-link:hover,
        .page-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
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

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .select-all-checkbox {
            margin-right: 8px;
        }

        .bulk-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
        }

        .bulk-actions>* {
            width: 100% !important;
            max-width: 100% !important;
        }


        .bulk-actions select {
            max-width: 150px;
        }

        .tab-btn {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            transition: var(--transition);
            border-bottom: 2px solid transparent;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .export-buttons {
            display: flex;
            gap: 15px;
        }


        .btn-export {
            width: 100%;
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        /* Barra de fortaleza de contraseña */
        .password-strength-meter {
            height: 5px;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength-meter-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        /* Colores para los diferentes niveles de fortaleza */
        .password-weak {
            background-color: #ff5252;
            width: 25%;
        }

        .password-medium {
            background-color: #ffb74d;
            width: 50%;
        }

        .password-strong {
            background-color: #4caf50;
            width: 75%;
        }

        .password-very-strong {
            background-color: #2e7d32;
            width: 100%;
        }

        .password-strength-text {
            font-size: 0.8rem;
            margin-top: 5px;
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .search-filter-container {
                flex-direction: column;
                gap: 10px;
            }

            .search-box,
            .filter-select,
            .export-buttons {
                width: 100%;
                min-width: 100%;
                margin-bottom: 10px;
            }


            .export-buttons {
                gap: 10px;
            }

            .btn-export {
                width: 100%;
                margin: 0;
            }

            /* Mostrar el checkbox en responsive */
            #usersTable th:first-child,
            #usersTable td:first-child {
                display: block;
                position: relative;
                padding-left: 15px;
            }

            #usersTable td:first-child:before {
                content: 'Seleccionar';
                position: absolute;
                left: 15px;
                width: 40%;
                padding-right: 10px;
                font-weight: 600;
                color: var(--primary);
            }

            #usersTable td:first-child {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }

            #usersTable td:first-child input {
                margin-left: auto;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table td {
                padding-left: 50%;
                position: relative;
                min-height: 40px;
                display: flex;
                align-items: center;
                word-break: break-word;
                white-space: normal;
            }

            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                font-weight: 600;
                color: var(--primary);
            }

            .table-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .badge {
                display: inline-block;
                width: fit-content;
                max-width: 100%;
            }

            .table-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
                gap: 5px;
            }

            /* Header - Botones mejor espaciados */
            .header-actions {
                flex-direction: column;
                gap: 10px !important;
                width: 100%;
            }

            .header-actions .btn {
                width: 100%;
                justify-content: center;
            }

            /* Tabla principal de usuarios */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            #usersTable {
                min-width: 100%;
            }

            #usersTable thead {
                display: none;
            }

            #usersTable tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #eee;
                border-radius: 8px;
                padding: 10px;
            }

            #usersTable td {
                padding-left: 45%;
                position: relative;
                min-height: 40px;
                display: flex;
                align-items: center;
                word-break: break-word;
                white-space: normal;
                border: none;
            }

            #usersTable td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 40%;
                padding-right: 10px;
                font-weight: 600;
                color: var(--primary);
            }

            /* Ajustes para badges */
            #usersTable .badge {
                display: inline-block;
                width: fit-content;
                max-width: 100%;
            }

            /* Ajustes para acciones */
            #usersTable .table-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
                gap: 5px;
            }

            /* Modales - Ajustes específicos */
            .modal-content {
                width: 95% !important;
                padding: 15px !important;
            }

            /* Tablas en modales */
            #vehiculosTable,
            #citasTable {
                font-size: 0.85rem;
            }

            #vehiculosTable th,
            #citasTable th,
            {
            padding: 8px 5px;
        }


        #vehiculosTable td,
        #citasTable td {
            padding-left: 50%;
            position: relative;
            min-height: 40px;
            display: flex;
            align-items: center;
            word-break: break-word;
            white-space: normal;
        }

        #vehiculosTable td:before,
        #citasTable td:before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            padding-right: 10px;
            font-weight: 600;
            color: var(--primary);
        }

        #vehiculosTable thead,
        #citasTable thead {
            display: none;
        }

        #vehiculosTable tr,
        #citasTable tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        #vehiculosTable .badge,
        #citasTable .badge {
            display: inline-block;
            width: fit-content;
        }

        .btn-export {
            margin: 0.5rem 0;
        }

        .table td[data-label="Email"] {
            word-break: break-word;
            white-space: normal;
        }
        }

        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .table td {
                padding-left: 45%;
            }

            .table td:before {
                width: 40%;
            }

            table {
                font-size: 0.8rem;
            }

            td,
            th {
                padding: 0.3rem;
                display: block;
                width: 100%;
            }

            tr {
                margin-bottom: 1rem;
                display: block;
                border: 1px solid #ddd;
            }

            #usersTable td {
                padding-left: 40%;
            }

            #usersTable td:before {
                width: 35%;
            }
        }

        @media (max-width: 480px) {

            #vehiculosTable td,
            #citasTable td {
                padding-left: 40%;
            }

            #vehiculosTable td:before,
            #citasTable td:before {
                width: 35%;
            }

            #usersTable td {
                padding-left: 35%;
            }

            #usersTable td:before {
                width: 30%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-users"></i>
                Gestión de Usuarios
            </h1>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Regresar
                </a>
                <button class="btn btn-primary" onclick="mostrarModalUsuario()">
                    <i class="fas fa-user-plus"></i>
                    Nuevo Usuario
                </button>
            </div>
        </div>

        <!-- Card con tabla de usuarios -->
        <div class="card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    Listado de Usuarios
                </h2>
                <div class="search-filter-container">
                    <div class="search-box">
                        <input type="text" class="form-control" placeholder="Buscar usuarios..." id="searchInput">
                    </div>
                    <div class="filter-select">
                        <select class="form-control" id="roleFilter">
                            <option value="">Todos los roles</option>
                            <option value="admin">Administrador</option>
                            <option value="empleado">Empleado</option>
                            <option value="cliente">Cliente</option>
                        </select>
                    </div>
                    <div class="filter-select">
                        <select class="form-control" id="statusFilter">
                            <option value="">Todos los estados</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                    <div class="export-buttons">
                        <button class="btn btn-success" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions" style="display: none;">
                    <input type="checkbox" id="selectAll" class="select-all-checkbox" onchange="toggleSelectAll()">
                    <span id="selectedCount">0 seleccionados</span>
                    <select class="form-control" id="bulkActionSelect">
                        <option value="">Acción masiva</option>
                        <option value="activate">Activar</option>
                        <option value="deactivate">Desactivar</option>
                        <option value="delete">Eliminar</option>
                    </select>
                    <button class="btn btn-primary btn-sm" onclick="applyBulkAction()">Aplicar</button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllHeader" onchange="toggleSelectAll()"></th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td><input type="checkbox" class="user-checkbox" value="{{ $usuario->id }}"
                                            onchange="updateSelectedCount()"></td>
                                    <td data-label="ID">{{ $usuario->id }}</td>
                                    <td data-label="Nombre">{{ $usuario->nombre }}</td>
                                    <td data-label="Email">{{ $usuario->email }}</td>
                                    <td data-label="Teléfono">{{ $usuario->telefono ?? 'N/A' }}</td>
                                    <td data-label="Rol">
                                        @if ($usuario->rol == 'admin')
                                            <span class="badge badge-danger">Administrador</span>
                                        @elseif($usuario->rol == 'empleado')
                                            <span class="badge badge-info">Empleado</span>
                                        @else
                                            <span class="badge badge-primary">Cliente</span>
                                        @endif
                                    </td>
                                    <td data-label="Estado">
                                        @if ($usuario->estado)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-warning">Inactivo</span>
                                        @endif
                                    </td>
                                    <td data-label="Registro">{{ $usuario->created_at->format('d/m/Y') }}</td>
                                    <td data-label="Acciones">
                                        <div class="table-actions">
                                            <button class="action-btn btn-edit" title="Editar"
                                                onclick="editarUsuario({{ $usuario->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if ($usuario->rol != 'admin')
                                                <button class="action-btn btn-delete" title="Eliminar"
                                                    onclick="confirmarEliminar({{ $usuario->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                            <button class="action-btn btn-info" title="Ver Registros"
                                                onclick="mostrarRegistrosUsuario({{ $usuario->id }})">
                                                <i class="fas fa-car"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($usuarios->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <h3>No se encontraron usuarios</h3>
                        <p>No hay usuarios registrados en el sistema</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Modal para crear/editar usuario -->
    <div id="usuarioModal" class="modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; border-radius: 12px; padding: 25px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
            <span class="close-modal" onclick="closeModal('usuarioModal')"
                style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: var(--text-secondary);">&times;</span>

            <h2 id="modalUsuarioTitle"
                style="margin-bottom: 20px; font-size: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-user-plus"></i>
                <span id="modalTitleText">Crear Nuevo Usuario</span>
            </h2>

            <form id="usuarioForm" style="margin-top: 20px;">
                @csrf
                <input type="hidden" id="usuario_id" name="id">

                <div class="form-grid"
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="nombre"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Nombre
                            Completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                            placeholder="Ej: Juan Pérez">
                    </div>

                    <div class="form-group">
                        <label for="email"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Correo
                            Electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" required
                            placeholder="Ej: juan@example.com" readonly>
                        <div id="email-error" class="hidden text-sm text-red-600 mt-1"></div>
                        <small id="emailHelp"
                            style="color: var(--text-secondary); display: block; margin-top: 5px; display: none;">
                            El correo electrónico no puede ser modificado
                        </small>
                    </div>
                </div>

                <div class="form-grid"
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="telefono"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control"
                            placeholder="Ej: 75855197" pattern="[0-9]{8}" maxlength="8">
                    </div>

                    <div class="form-group">
                        <label for="rol"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Rol</label>
                        <select id="rol" name="rol" class="form-control" required readonly>
                            <option value="cliente">Cliente</option>
                            <option value="empleado">Empleado</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <small id="rolHelp"
                            style="color: var(--text-secondary); display: block; margin-top: 5px; display: none;">
                            El rol no puede ser modificado después de crear el usuario
                        </small>
                    </div>
                </div>

                <!-- Sección de contraseñas mejorada -->
                <div id="passwordFields" style="display: block; margin-bottom: 15px;">
                    <div class="form-grid"
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="password"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Contraseña</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Mínimo 8 caracteres" style="padding-right: 40px;">
                                <button type="button" onclick="togglePassword('password')"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                                    <i class="fas fa-eye" id="passwordEye"></i>
                                </button>
                            </div>
                            <div class="password-requirements">
                                <div class="password-strength-meter">
                                    <div class="password-strength-meter-fill" id="passwordStrengthBar"></div>
                                </div>
                                <div class="password-strength-text" id="passwordStrengthText">Fortaleza de la
                                    contraseña</div>
                                <ul style="columns: 2; column-gap: 20px; margin-top: 10px;">
                                    <li id="req-length">Mínimo 8 caracteres</li>
                                    <li id="req-uppercase">1 letra mayúscula</li>
                                    <li id="req-lowercase">1 letra minúscula</li>
                                    <li id="req-number">1 número</li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Confirmar
                                Contraseña</label>
                            <div style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repite la contraseña"
                                    style="padding-right: 40px;">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                                    <i class="fas fa-eye" id="passwordConfirmationEye"></i>
                                </button>
                            </div>
                            <div id="passwordMatchMessage" style="font-size: 0.8rem; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="estado"
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem;">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
            </form>
        </div>
    </div>

    <!-- Modal para ver registros del usuario (vehículos y citas) -->
    <div id="registrosModal" class="modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; border-radius: 12px; padding: 25px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; position: relative;">
            <span class="close-modal" onclick="closeModal('registrosModal')"
                style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: var(--text-secondary);">&times;</span>

            <h2 id="modalRegistrosTitle"
                style="margin-bottom: 20px; font-size: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-car"></i>
                <span id="modalRegistrosText">Registros del Usuario</span>
            </h2>

            <div class="tabs-container" style="margin-bottom: 20px;">
                <div class="tabs" style="display: flex; border-bottom: 1px solid #eee;">
                    <button id="vehiculosTab" class="tab-btn active" onclick="switchTab('vehiculos')"
                        style="padding: 10px 20px; border: none; background: none; cursor: pointer; font-weight: 600; border-bottom: 2px solid var(--primary);">
                        Vehículos
                    </button>
                    <button id="citasTab" class="tab-btn" onclick="switchTab('citas')"
                        style="padding: 10px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: var(--text-secondary);">
                        Citas
                    </button>
                </div>
            </div>

            <div id="vehiculosContent" class="tab-content">
                <div class="empty-state" id="vehiculosEmpty" style="display: none;">
                    <i class="fas fa-car-crash"></i>
                    <h3>No hay vehículos registrados</h3>
                    <p>Este usuario no tiene vehículos asociados</p>
                </div>

                <div class="table-responsive">
                    <table class="table" id="vehiculosTable" style="display: none;">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Tipo</th>
                                <th>Color</th>
                            </tr>
                        </thead>
                        <tbody id="vehiculosBody">
                            <!-- Datos de vehículos se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="citasContent" class="tab-content" style="display: none;">
                <div class="empty-state" id="citasEmpty" style="display: none;">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No hay citas registradas</h3>
                    <p>Este usuario no tiene citas asociadas</p>
                </div>

                <div class="table-responsive">
                    <table class="table" id="citasTable" style="display: none;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Servicios</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="citasBody">
                            <!-- Datos de citas se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // =============================================
        // VARIABLES GLOBALES
        // =============================================
        let allUsersData = @json($usuarios);
        let filteredUsers = [];
        let currentUser = {
            id: {{ Auth::id() }},
            rol: '{{ Auth::user()->rol ?? '' }}'
        };

        // =============================================
        // ASEGURAR RESPONSIVE DE TABLAS
        // =============================================

        // Asegurar que las tablas sean responsive
        function initResponsiveTables() {
            document.querySelectorAll('.table-responsive').forEach(tableContainer => {
                if (window.innerWidth < 768) {
                    tableContainer.classList.add('force-responsive');
                }
            });
        }

        // Ejecutar al cargar y al redimensionar
        window.addEventListener('resize', initResponsiveTables);
        document.addEventListener('DOMContentLoaded', initResponsiveTables);

        // =============================================
        // FUNCIONES PRINCIPALES
        // =============================================

        // Función para editar usuario
        function editarUsuario(usuarioId) {
            const usuario = allUsersData.find(u => u.id == usuarioId);

            if (usuario && usuario.rol === 'admin') {
                Swal.fire({
                    title: 'Acción restringida',
                    text: 'No puedes editar la información de un administrador desde esta interfaz',
                    icon: 'warning'
                });
                return;
            }

            mostrarModalUsuario(usuarioId);
        }

        // Función para mostrar el modal de usuario 
        function mostrarModalUsuario(usuarioId = null) {
            const modal = document.getElementById('usuarioModal');
            const form = document.getElementById('usuarioForm');
            const title = document.getElementById('modalTitleText');
            const rolField = document.getElementById('rol');
            const emailField = document.getElementById('email');
            const passwordFields = document.getElementById('passwordFields');

            // Resetear el formulario y listeners
            form.reset();
            document.getElementById('usuario_id').value = '';

            // Remover cualquier listener previo de email
            const emailInput = document.getElementById('email');
            const newEmailInput = emailInput.cloneNode(true);
            emailInput.parentNode.replaceChild(newEmailInput, emailInput);

            if (usuarioId) {
                // Modo edición
                document.getElementById('modalTitleText').textContent = 'Editar Usuario';
                document.getElementById('email').readOnly = true;
                document.getElementById('rol').readOnly = true;
                document.getElementById('emailHelp').style.display = 'block';
                document.getElementById('rolHelp').style.display = 'block';
                passwordFields.style.display = 'none';
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;

                // Buscar el usuario en los datos cargados
                const usuario = allUsersData.find(u => u.id == usuarioId);

                if (usuario) {
                    document.getElementById('usuario_id').value = usuario.id;
                    document.getElementById('nombre').value = usuario.nombre;
                    document.getElementById('email').value = usuario.email;
                    document.getElementById('telefono').value = usuario.telefono || '';
                    document.getElementById('rol').value = usuario.rol;
                    document.getElementById('estado').value = usuario.estado ? '1' : '0';
                } else {
                    // Si no está en los datos cargados, hacer petición al servidor
                    fetch(`/admin/usuarios/${usuarioId}/edit`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Usuario no encontrado');
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('usuario_id').value = data.id;
                            document.getElementById('nombre').value = data.nombre;
                            document.getElementById('email').value = data.email;
                            document.getElementById('telefono').value = data.telefono || '';
                            document.getElementById('rol').value = data.rol;
                            document.getElementById('estado').value = data.estado ? '1' : '0';
                        })
                        .catch(error => {
                            console.error('Error al cargar usuario:', error);
                            Swal.fire('Error', 'No se pudo cargar la información del usuario', 'error');
                            closeModal('usuarioModal');
                        });
                }
            } else {
                // Modo creación
                document.getElementById('modalTitleText').textContent = 'Crear Nuevo Usuario';
                document.getElementById('email').readOnly = false;
                document.getElementById('rol').readOnly = false;
                document.getElementById('emailHelp').style.display = 'none';
                document.getElementById('email').removeAttribute('readonly');
                document.getElementById('rolHelp').style.display = 'none';
                passwordFields.style.display = 'block';
                document.getElementById('rol').value = 'cliente'; // Valor por defecto
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;

                // Validación en tiempo real para email (solo en creación)
                document.getElementById('email').addEventListener('blur', async function() {
                    const email = this.value;
                    if (!email) return;

                    try {
                        const usuarioId = document.getElementById('usuario_id').value;
                        const url =
                            `{{ route('admin.usuarios.check-email') }}?email=${encodeURIComponent(email)}${usuarioId ? '&exclude_id=' + usuarioId : ''}`;

                        const response = await fetch(url);

                        if (!response.ok) {
                            throw new Error('Error al verificar email');
                        }

                        const data = await response.json();

                        if (!data.available) {
                            this.setCustomValidity(data.message);
                            this.classList.add('border-red-500');
                            document.getElementById('email-error').textContent = data.message;
                            document.getElementById('email-error').classList.remove('hidden');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('border-red-500');
                            document.getElementById('email-error').classList.add('hidden');
                        }
                    } catch (error) {
                        console.error('Error al verificar email:', error);
                        // No mostrar error al usuario para no confundirlo
                    }
                });
            }

            // Resetear validaciones visuales
            document.querySelectorAll('.password-requirements li').forEach(li => {
                li.style.color = '#6b7280';
            });
            document.getElementById('passwordMatchMessage').textContent = '';

            // Resetear el botón de submit
            const submitBtn = document.querySelector('#usuarioForm button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Usuario';

            modal.style.display = 'flex';
        }

        // Función para confirmar eliminación 
        function confirmarEliminar(usuarioId) {
            const usuario = allUsersData.find(u => u.id == usuarioId);

            if (usuario && usuario.rol === 'admin') {
                Swal.fire({
                    title: 'Acción restringida',
                    text: 'No puedes eliminar cuentas de administrador',
                    icon: 'warning'
                });
                return;
            }

            Swal.fire({
                title: '¿Eliminar este usuario?',
                html: `<p>Esta acción solo es posible si:</p>
                   <ul>
                     <li>El usuario está inactivo</li>
                     <li>No tiene citas pendientes</li>
                   </ul>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Verificar y eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/usuarios/${usuarioId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(async (data) => {
                            if (data.success) {
                                await Swal.fire('¡Eliminado!', data.message, 'success');
                                await fetchAllUsers();
                                closeModal('usuarioModal');
                            }
                        })
                        .catch(error => {
                            console.error('Error al eliminar:', error);
                            Swal.fire('Error', error.message || 'Error al eliminar', 'error');
                        });
                }
            });
        }

        // Función para alternar visibilidad de contraseña 
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(`${inputId}Eye`);

            if (!input || !eyeIcon) {
                console.error(`Elemento no encontrado para inputId: ${inputId}`);
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function evaluatePasswordStrength(password) {
            let strength = 0;
            const strengthText = document.getElementById('passwordStrengthText');
            const strengthBar = document.getElementById('passwordStrengthBar');

            // Resetear completamente
            strengthBar.className = 'password-strength-meter-fill';
            strengthBar.style.backgroundColor = 'transparent';
            strengthBar.style.width = '0';
            strengthText.textContent = '';

            // Si está vacío, salir sin evaluar
            if (password.length === 0) {
                return false;
            }

            // Evaluar fortaleza
            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            // Aplicar estilos según fortaleza
            let color, width, text;
            switch (strength) {
                case 0:
                case 1:
                    color = '#ff5252';
                    width = '25%';
                    text = 'Débil';
                    break;
                case 2:
                    color = '#ffb74d';
                    width = '50%';
                    text = 'Moderada';
                    break;
                case 3:
                    color = '#4caf50';
                    width = '75%';
                    text = 'Fuerte';
                    break;
                case 4:
                case 5:
                    color = '#2e7d32';
                    width = '100%';
                    text = 'Muy fuerte';
                    break;
            }

            // Aplicar cambios visuales
            strengthBar.style.backgroundColor = color;
            strengthBar.style.width = width;
            strengthText.textContent = text;
            strengthText.style.color = color;

            return strength >= 3;
        }

        function validatePasswordStrength(password) {
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);

            // Actualizar lista de requisitos
            document.getElementById('req-length').style.color = hasMinLength ? '#10b981' : '#6b7280';
            document.getElementById('req-uppercase').style.color = hasUpperCase ? '#10b981' : '#6b7280';
            document.getElementById('req-lowercase').style.color = hasLowerCase ? '#10b981' : '#6b7280';
            document.getElementById('req-number').style.color = hasNumber ? '#10b981' : '#6b7280';

            // Evaluar fortaleza general
            evaluatePasswordStrength(password);

            return hasMinLength && hasUpperCase && hasLowerCase && hasNumber;
        }

        document.getElementById('password')?.addEventListener('input', function() {
            validatePasswordStrength(this.value);
            if (document.getElementById('password_confirmation').value.length > 0) {
                validatePasswordMatch();
            }
        });

        // Función para validar coincidencia de contraseñas
        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const messageElement = document.getElementById('passwordMatchMessage');

            if (confirmPassword.length === 0) {
                messageElement.textContent = '';
                messageElement.style.color = '';
                return false;
            }

            if (password === confirmPassword) {
                messageElement.textContent = 'Las contraseñas coinciden';
                messageElement.style.color = '#10b981';
                return true;
            } else {
                messageElement.textContent = 'Las contraseñas no coinciden';
                messageElement.style.color = '#ef4444';
                return false;
            }
        }

        // Inicializar validaciones del formulario
        function initPasswordValidations() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    validatePasswordStrength(this.value);
                    if (confirmPasswordInput.value.length > 0) {
                        validatePasswordMatch();
                    }
                });
            }

            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', validatePasswordMatch);
            }
        }



        // Función para cerrar modal 
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Función para exportar a Excel 
        async function exportToExcel() {
            try {
                const response = await fetch('/admin/usuarios/all?export=true');
                if (!response.ok) throw new Error('Error al obtener datos');

                const allUsers = await response.json();

                const excelData = allUsers.map(user => ({
                    'ID': user.id,
                    'Nombre': user.nombre,
                    'Email': user.email,
                    'Teléfono': user.telefono || 'N/A',
                    'Rol': user.rol === 'admin' ? 'Administrador' : (user.rol === 'empleado' ? 'Empleado' :
                        'Cliente'),
                    'Estado': user.estado ? 'Activo' : 'Inactivo',
                    'Registro': new Date(user.created_at).toLocaleDateString()
                }));

                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.json_to_sheet(excelData);
                XLSX.utils.book_append_sheet(wb, ws, "Usuarios");
                XLSX.writeFile(wb, `usuarios_${new Date().toISOString().slice(0,10)}.xlsx`);
            } catch (error) {
                console.error('Error al exportar:', error);
                Swal.fire('Error', 'No se pudo generar el archivo Excel', 'error');
            }
        }

        // Función para exportar a PDF 
        async function exportToPDF() {
            try {
                const response = await fetch('/admin/usuarios/all?export=true');
                if (!response.ok) throw new Error('Error al obtener datos');

                const allUsers = await response.json();

                const pdfData = allUsers.map(user => [
                    user.id,
                    user.nombre,
                    user.email,
                    user.telefono || 'N/A',
                    user.rol === 'admin' ? 'Administrador' : (user.rol === 'empleado' ? 'Empleado' : 'Cliente'),
                    user.estado ? 'Activo' : 'Inactivo',
                    new Date(user.created_at).toLocaleDateString()
                ]);

                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                doc.setFontSize(18);
                doc.text('Listado Completo de Usuarios', 14, 15);
                doc.setFontSize(12);
                doc.text(`Generado el: ${new Date().toLocaleDateString()}`, 14, 22);

                doc.autoTable({
                    head: [
                        ['ID', 'Nombre', 'Email', 'Teléfono', 'Rol', 'Estado', 'Registro']
                    ],
                    body: pdfData,
                    startY: 30,
                    styles: {
                        fontSize: 8,
                        cellPadding: 2
                    }
                });

                doc.save(`usuarios_completo_${new Date().toISOString().slice(0,10)}.pdf`);
            } catch (error) {
                console.error('Error al exportar:', error);
                Swal.fire('Error', 'No se pudo generar el archivo PDF', 'error');
            }
        }

        // Función para actualizar contador de selección 
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            selectedCount.textContent = `${checkboxes.length} seleccionados`;

            if (checkboxes.length > 0) {
                bulkActions.style.display = 'flex';
            } else {
                bulkActions.style.display = 'none';
            }
        }

        // Función para aplicar acciones masivas 
        function applyBulkAction() {
            const action = document.getElementById('bulkActionSelect').value;
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const userIds = Array.from(checkboxes).map(cb => cb.value);

            if (!action || userIds.length === 0) {
                Swal.fire('Error', 'Selecciona una acción y al menos un usuario', 'error');
                return;
            }

            let endpoint, method, successMessage;

            switch (action) {
                case 'activate':
                    endpoint = '/admin/usuarios/bulk-activate';
                    method = 'POST';
                    successMessage = 'Usuarios activados correctamente';
                    break;
                case 'deactivate':
                    endpoint = '/admin/usuarios/bulk-deactivate';
                    method = 'POST';
                    successMessage = 'Usuarios desactivados correctamente';
                    break;
                case 'delete':
                    endpoint = '/admin/usuarios/bulk-delete';
                    method = 'DELETE';
                    successMessage = 'Usuarios eliminados correctamente';
                    break;
                default:
                    return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Esta acción afectará a ${userIds.length} usuarios`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(endpoint, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: userIds
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al procesar la acción');
                            }
                            return response.json();
                        })
                        .then(async (data) => {
                            await Swal.fire('Éxito', successMessage, 'success');
                            await fetchAllUsers();
                            closeModal('usuarioModal');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Ocurrió un error al procesar la acción', 'error');
                        });
                }
            });
        }

        // Función para cargar todos los usuarios 
        async function fetchAllUsers() {
            try {
                const response = await fetch('/admin/usuarios/all');
                if (!response.ok) throw new Error('Error al cargar usuarios');
                allUsersData = await response.json();
                updateTableWithFilteredResults();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudieron cargar los usuarios', 'error');
            }
        }

        // Función para seleccionar/deseleccionar todos 
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            updateSelectedCount();
        }

        // Función para filtrar la tabla 
        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const roleValue = document.getElementById('roleFilter').value;
            const statusValue = document.getElementById('statusFilter').value;

            filteredUsers = allUsersData.filter(user => {
                const nameMatch = user.nombre.toLowerCase().includes(searchValue);
                const emailMatch = user.email.toLowerCase().includes(searchValue);
                const roleMatch = roleValue === '' || user.rol === roleValue;
                const statusMatch = statusValue === '' ||
                    (statusValue === '1' && user.estado) ||
                    (statusValue === '0' && !user.estado);

                return (nameMatch || emailMatch) && roleMatch && statusMatch;
            });

            updateTableWithFilteredResults();
        }
        // Función para actualizar tabla con resultados filtrados 
        function updateTableWithFilteredResults() {
            const tbody = document.querySelector('#usersTable tbody');
            tbody.innerHTML = '';

            const usersToShow = filteredUsers.length > 0 ? filteredUsers : allUsersData;

            usersToShow.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td><input type="checkbox" class="user-checkbox" value="${user.id}" onchange="updateSelectedCount()"></td>
                <td data-label="ID">${user.id}</td>
                <td data-label="Nombre">${user.nombre}</td>
                <td data-label="Email">${user.email}</td>
                <td data-label="Teléfono">${user.telefono || 'N/A'}</td>
                <td data-label="Rol">
                    ${user.rol == 'admin' ? 
                        '<span class="badge badge-danger">Administrador</span>' : 
                        user.rol == 'empleado' ? 
                        '<span class="badge badge-info">Empleado</span>' : 
                        '<span class="badge badge-primary">Cliente</span>'}
                </td>
                <td data-label="Estado">
                    ${user.estado ? 
                        '<span class="badge badge-success">Activo</span>' : 
                        '<span class="badge badge-warning">Inactivo</span>'}
                </td>
                <td data-label="Registro">${new Date(user.created_at).toLocaleDateString()}</td>
                <td data-label="Acciones">
                    <div class="table-actions">
                        <button class="action-btn btn-edit" title="Editar" onclick="editarUsuario(${user.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${user.rol != 'admin' ? `
                                                        <button class="action-btn btn-delete" title="Eliminar" onclick="confirmarEliminar(${user.id})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>` : ''}
                        <button class="action-btn btn-info" title="Ver Registros" onclick="mostrarRegistrosUsuario(${user.id})">
                            <i class="fas fa-car"></i>
                        </button>
                    </div>
                </td>
            `;
                tbody.appendChild(row);
            });

            updateSelectedCount();
        }

        // Función para manejar envío de formulario 
        async function handleUsuarioFormSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const usuarioId = form.querySelector('#usuario_id').value;

            // Resetear errores visuales
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
            });
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });

            // Deshabilitar el botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

            try {
                // Primero verificar el email nuevamente
                const email = form.email.value;
                const checkEmailUrl =
                    `{{ route('admin.usuarios.check-email') }}?email=${encodeURIComponent(email)}${usuarioId ? '&exclude_id=' + usuarioId : ''}`;
                const checkResponse = await fetch(checkEmailUrl);

                if (!checkResponse.ok) throw new Error('Error al verificar email');

                const checkData = await checkResponse.json();

                if (!checkData.available) {
                    throw new Error(checkData.message);
                }

                // Si el email está disponible, proceder con el envío
                const response = await fetch(usuarioId ? `/admin/usuarios/${usuarioId}` : '/admin/usuarios', {
                    method: usuarioId ? 'PUT' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre: form.nombre.value.trim(),
                        email: email,
                        telefono: form.telefono.value.trim() || null,
                        estado: form.estado.value === '1',
                        rol: form.rol.value,
                        password: form.password?.value,
                        password_confirmation: form.password_confirmation?.value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al procesar la solicitud');
                }

                // Éxito - mostrar mensaje y recargar
                await Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                await fetchAllUsers();
                closeModal('usuarioModal');

            } catch (error) {
                // Mostrar error específico para email
                if (error.message.includes('correo electrónico')) {
                    form.email.classList.add('border-red-500');
                    document.getElementById('email-error').textContent = error.message;
                    document.getElementById('email-error').classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message,
                        footer: usuarioId ? `ID: ${usuarioId}` : ''
                    });
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Usuario';
            }
        }

        // =============================================
        // FUNCIONES DE REGISTROS DE USUARIO 
        // =============================================

        function mostrarRegistrosUsuario(usuarioId) {
            const modal = document.getElementById('registrosModal');
            const usuario = allUsersData.find(u => u.id == usuarioId);

            if (!usuario) {
                Swal.fire('Error', 'No se pudo cargar la información del usuario', 'error');
                return;
            }

            if (currentUser && currentUser.rol === 'admin' && usuario.rol === 'admin' && usuario.id !== currentUser.id) {
                Swal.fire('Acceso restringido', 'Solo puedes ver tu propia información', 'warning');
                return;
            }

            document.getElementById('modalRegistrosText').textContent = `Registros de ${usuario.nombre}`;

            Swal.fire({
                title: 'Cargando información',
                html: 'Obteniendo vehículos y citas del usuario...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/admin/usuarios/${usuarioId}/registros`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Error al cargar registros');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.close();
                    mostrarVehiculos(data.vehiculos);
                    mostrarCitas(data.citas);
                    modal.style.display = 'flex';
                    switchTab('vehiculos');
                })
                .catch(error => {
                    Swal.fire('Error', 'No se pudieron cargar los registros del usuario', 'error');
                    console.error('Error:', error);
                });
        }

        function mostrarVehiculos(vehiculos) {
            const vehiculosBody = document.getElementById('vehiculosBody');
            const vehiculosTable = document.getElementById('vehiculosTable');
            const vehiculosEmpty = document.getElementById('vehiculosEmpty');

            vehiculosBody.innerHTML = '';

            if (vehiculos.length === 0) {
                vehiculosTable.style.display = 'none';
                vehiculosEmpty.style.display = 'block';
                return;
            }

            vehiculosEmpty.style.display = 'none';
            vehiculosTable.style.display = 'table';

            vehiculos.forEach(vehiculo => {
                const row = document.createElement('tr');
                row.innerHTML = `
            <td data-label="Placa">${vehiculo.placa || 'N/A'}</td>
            <td data-label="Marca">${vehiculo.marca || 'N/A'}</td>
            <td data-label="Modelo">${vehiculo.modelo || 'N/A'}</td>
            <td data-label="Tipo">${vehiculo.tipo || 'N/A'}</td>
            <td data-label="Color">${vehiculo.color || 'N/A'}</td>
        `;
                vehiculosBody.appendChild(row);
            });
        }

        function mostrarCitas(citas) {
            const citasBody = document.getElementById('citasBody');
            const citasTable = document.getElementById('citasTable');
            const citasEmpty = document.getElementById('citasEmpty');

            citasBody.innerHTML = '';

            if (citas.length === 0) {
                citasTable.style.display = 'none';
                citasEmpty.style.display = 'block';
                return;
            }

            citasEmpty.style.display = 'none';
            citasTable.style.display = 'table';

            citas.forEach(cita => {
                const servicios = cita.servicios.map(s => s.nombre).join(', ');
                const total = cita.servicios.reduce((sum, s) => sum + s.precio, 0);

                const row = document.createElement('tr');
                row.innerHTML = `
            <td data-label="ID">${cita.id}</td>
            <td data-label="Fecha">${new Date(cita.fecha_hora).toLocaleDateString()}</td>
            <td data-label="Hora">${new Date(cita.fecha_hora).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</td>
            <td data-label="Estado">
                ${cita.estado === 'pendiente' ? 
                    '<span class="badge badge-warning">Pendiente</span>' : 
                 cita.estado === 'completada' ? 
                    '<span class="badge badge-success">Completada</span>' : 
                    '<span class="badge badge-danger">Cancelada</span>'}
            </td>
            <td data-label="Servicios">${servicios}</td>
            <td data-label="Total">$${total.toFixed(2)}</td>
        `;
                citasBody.appendChild(row);
            });
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.style.borderBottom = 'none';
                btn.style.color = 'var(--text-secondary)';
            });

            if (tabName === 'vehiculos') {
                document.getElementById('vehiculosContent').style.display = 'block';
                document.getElementById('vehiculosTab').style.borderBottom = '2px solid var(--primary)';
                document.getElementById('vehiculosTab').style.color = 'var(--text-primary)';
            } else {
                document.getElementById('citasContent').style.display = 'block';
                document.getElementById('citasTab').style.borderBottom = '2px solid var(--primary)';
                document.getElementById('citasTab').style.color = 'var(--text-primary)';
            }
        }

        // =============================================
        // INICIALIZACIÓN 
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            fetchAllUsers();
            initPasswordValidations();

            document.getElementById('searchInput').addEventListener('keyup', filterTable);
            document.getElementById('roleFilter').addEventListener('change', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);

            const usuarioForm = document.getElementById('usuarioForm');
            if (usuarioForm) {
                usuarioForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validar contraseña si estamos en modo creación
                    if (document.getElementById('passwordFields').style.display !== 'none') {
                        const password = document.getElementById('password').value;
                        const isPasswordStrong = validatePasswordStrength(password);
                        const doPasswordsMatch = validatePasswordMatch();

                        if (!isPasswordStrong || !doPasswordsMatch) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error en la contraseña',
                                text: 'Por favor, asegúrate de que la contraseña cumpla con todos los requisitos y que ambas contraseñas coincidan.'
                            });
                            return;
                        }
                    }

                    // Si todo está bien, enviar el formulario
                    handleUsuarioFormSubmit(e);
                });
            }
        });
    </script>
</body>

</html>
