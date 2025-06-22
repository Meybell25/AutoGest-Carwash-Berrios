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
            background: var(--info);
            color: white;
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
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
        }

        .bulk-actions select {
            max-width: 150px;
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
                min-width: 100%;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table td {
                padding-left: 40%;
                position: relative;
            }

            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 35%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                color: var(--primary);
            }

            .table-actions {
                justify-content: flex-end;
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

                <div class="pagination">
                    {{ $usuarios->links() }}
                </div>
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
                            placeholder="Ej: juan@example.com" {{ isset($usuario) ? 'disabled' : '' }}>
                        @if (isset($usuario))
                            <small style="color: var(--text-secondary); display: block; margin-top: 5px;">
                                El correo electrónico no puede ser modificado después de crear el usuario
                            </small>
                        @endif
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
                        <select id="rol" name="rol" class="form-control" required
                            {{ isset($usuario) ? 'disabled' : '' }}>
                            <option value="cliente">Cliente</option>
                            <option value="empleado">Empleado</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <small style="color: var(--text-secondary); display: block; margin-top: 5px;">
                            El rol no puede ser modificado después de crear el usuario para mantener la integridad de
                            los datos.
                        </small>
                    </div>
                </div>

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
                        <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 5px;">
                            Requisitos: 8+ caracteres, mayúscula, minúscula, número
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Confirmar
                            Contraseña</label>
                        <div style="position: relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Repite la contraseña" style="padding-right: 40px;">
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                                <i class="fas fa-eye" id="passwordConfirmationEye"></i>
                            </button>
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

    <script>
        // Variables globales
        let allUsersData = @json($usuarios->items());
        let filteredUsers = [];

        // Función para mostrar el modal de usuario
        function mostrarModalUsuario(usuarioId = null) {
            const modal = document.getElementById('usuarioModal');
            const form = document.getElementById('usuarioForm');
            const title = document.getElementById('modalTitleText');
            const rolField = document.getElementById('rol');
            const emailField = document.getElementById('email');

            // Resetear el formulario
            form.reset();
            document.getElementById('usuario_id').value = '';

            if (usuarioId) {
                // Modo edición
                title.textContent = 'Editar Usuario';
                rolField.disabled = true;
                emailField.disabled = true;

                // Buscar el usuario en los datos cargados
                const usuario = allUsersData.find(u => u.id == usuarioId);

                if (usuario) {
                    document.getElementById('usuario_id').value = usuario.id;
                    document.getElementById('nombre').value = usuario.nombre;
                    document.getElementById('email').value = usuario.email;
                    document.getElementById('telefono').value = usuario.telefono || '';
                    rolField.value = usuario.rol;
                    document.getElementById('estado').value = usuario.estado ? '1' : '0';

                    // No cargamos la contraseña por seguridad
                    document.getElementById('password').required = false;
                    document.getElementById('password_confirmation').required = false;
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

                            // Deshabilitar campos sensibles
                            rolField.disabled = true;
                            emailField.disabled = true;

                            // No cargamos la contraseña por seguridad
                            document.getElementById('password').required = false;
                            document.getElementById('password_confirmation').required = false;
                        })
                        .catch(error => {
                            console.error('Error al cargar usuario:', error);
                            Swal.fire('Error', 'No se pudo cargar la información del usuario', 'error');
                            closeModal('usuarioModal');
                        });
                }
            } else {
                // Modo creación
                title.textContent = 'Crear Nuevo Usuario';
                rolField.disabled = false;
                emailField.disabled = false;
                rolField.value = 'cliente'; // Valor por defecto
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;
            }

            modal.style.display = 'flex';
        }

        // Función para editar usuario
        function editarUsuario(usuarioId) {
            mostrarModalUsuario(usuarioId);
        }

        // Función para confirmar eliminación
        function confirmarEliminar(usuarioId) {
            Swal.fire({
                title: '¿Eliminar este usuario?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/usuarios/${usuarioId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Error al eliminar el usuario');
                        })
                        .then(data => {
                            Swal.fire(
                                '¡Eliminado!',
                                'El usuario ha sido eliminado.',
                                'success'
                            ).then(() => {
                                // Recargar la página para ver los cambios
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar el usuario',
                                'error'
                            );
                        });
                }
            });
        }

        // Función para alternar visibilidad de contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(`${inputId}Eye`);

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

        // Función para cerrar modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Función para exportar a Excel
        function exportToExcel() {
            // Obtener los datos filtrados o todos los datos
            const dataToExport = filteredUsers.length > 0 ? filteredUsers : allUsersData;

            // Preparar los datos para la exportación
            const excelData = dataToExport.map(user => ({
                'ID': user.id,
                'Nombre': user.nombre,
                'Email': user.email,
                'Teléfono': user.telefono || 'N/A',
                'Rol': user.rol === 'admin' ? 'Administrador' : (user.rol === 'empleado' ? 'Empleado' :
                    'Cliente'),
                'Estado': user.estado ? 'Activo' : 'Inactivo',
                'Fecha Registro': new Date(user.created_at).toLocaleDateString()
            }));

            // Crear libro de Excel
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(excelData);
            XLSX.utils.book_append_sheet(wb, ws, "Usuarios");

            // Exportar el archivo
            XLSX.writeFile(wb, `usuarios_${new Date().toISOString().slice(0,10)}.xlsx`);
        }

        // Función para exportar a PDF
        function exportToPDF() {
            // Obtener los datos filtrados o todos los datos
            const dataToExport = filteredUsers.length > 0 ? filteredUsers : allUsersData;

            // Preparar los datos para la exportación
            const pdfData = dataToExport.map(user => [
                user.id,
                user.nombre,
                user.email,
                user.telefono || 'N/A',
                user.rol === 'admin' ? 'Administrador' : (user.rol === 'empleado' ? 'Empleado' : 'Cliente'),
                user.estado ? 'Activo' : 'Inactivo',
                new Date(user.created_at).toLocaleDateString()
            ]);

            // Crear PDF
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Título
            doc.setFontSize(18);
            doc.text('Listado de Usuarios', 14, 15);
            doc.setFontSize(12);
            doc.text(`Generado el: ${new Date().toLocaleDateString()}`, 14, 22);

            // Tabla
            doc.autoTable({
                head: [
                    ['ID', 'Nombre', 'Email', 'Teléfono', 'Rol', 'Estado', 'Registro']
                ],
                body: pdfData,
                startY: 30,
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                    halign: 'left'
                },
                headStyles: {
                    fillColor: [46, 125, 50],
                    textColor: 255,
                    fontSize: 9
                }
            });

            // Guardar PDF
            doc.save(`usuarios_${new Date().toISOString().slice(0,10)}.pdf`);
        }

        // Funciones para selección múltiple
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll').checked || document.getElementById('selectAllHeader')
                .checked;
            const checkboxes = document.querySelectorAll('.user-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll;
            });

            updateSelectedCount();
        }

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
                        .then(data => {
                            Swal.fire('Éxito', successMessage, 'success').then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Ocurrió un error al procesar la acción', 'error');
                        });
                }
            });
        }

        // Filtrar tabla (ahora funciona con todos los registros)
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar todos los usuarios (si hay paginación)
            if (window.location.pathname.includes('/admin/usuarios')) {
                fetchAllUsers();
            }

            const searchInput = document.getElementById('searchInput');
            const roleFilter = document.getElementById('roleFilter');
            const statusFilter = document.getElementById('statusFilter');

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const roleValue = roleFilter.value;
                const statusValue = statusFilter.value;

                filteredUsers = allUsersData.filter(user => {
                    const nameMatch = user.nombre.toLowerCase().includes(searchValue);
                    const emailMatch = user.email.toLowerCase().includes(searchValue);
                    const roleMatch = roleValue === '' || user.rol === roleValue;
                    const statusMatch = statusValue === '' ||
                        (statusValue === '1' && user.estado) ||
                        (statusValue === '0' && !user.estado);

                    return (nameMatch || emailMatch) && roleMatch && statusMatch;
                });

                // Actualizar la tabla con los resultados filtrados
                updateTableWithFilteredResults();
            }

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
                            </div>
                        </td>
                    `;

                    tbody.appendChild(row);
                });

                // Actualizar contador de selección
                updateSelectedCount();
            }

            async function fetchAllUsers() {
                try {
                    const response = await fetch('/admin/usuarios/all');
                    if (!response.ok) throw new Error('Error al cargar usuarios');

                    const data = await response.json();
                    allUsersData = data;
                } catch (error) {
                    console.error('Error al cargar todos los usuarios:', error);
                    // Si falla, seguimos con los datos de la primera página
                    allUsersData = @json($usuarios->items());
                }
            }

            searchInput.addEventListener('keyup', filterTable);
            roleFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);

            // Manejar el envío del formulario de usuario
            const usuarioForm = document.getElementById('usuarioForm');
            if (usuarioForm) {
                usuarioForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Habilitar campos deshabilitados temporalmente para el envío
                    const disabledFields = usuarioForm.querySelectorAll('[disabled]');
                    disabledFields.forEach(field => {
                        field.disabled = false;
                    });

                    const formData = new FormData(usuarioForm);
                    const usuarioId = formData.get('id');
                    const method = usuarioId ? 'PUT' : 'POST';
                    const url = usuarioId ? `/admin/usuarios/${usuarioId}` : '/admin/usuarios';

                    fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            disabledFields.forEach(field => {
                                field.disabled = true;
                            });
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire(
                                '¡Éxito!',
                                usuarioId ? 'Usuario actualizado correctamente' :
                                'Usuario creado correctamente',
                                'success'
                            ).then(() => {
                                closeModal('usuarioModal');
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            let errorMessage = 'Ocurrió un error al procesar la solicitud';

                            if (error.errors) {
                                errorMessage = Object.values(error.errors).join('\n');
                            } else if (error.message) {
                                errorMessage = error.message;
                            }

                            Swal.fire(
                                'Error',
                                errorMessage,
                                'error'
                            );
                        });
                });
            }
        });
    </script>
</body>

</html>
