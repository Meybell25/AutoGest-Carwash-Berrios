<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora - AutoGest Carwash Berrios</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ======================
        ESTILOS GENERALES
        ====================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

            --primary-gradient: linear-gradient(135deg, #2e7d32 0%, #00695c 100%);
            --accent-gradient: linear-gradient(45deg, #ff8f00 0%, #ef6c00 100%);
            --secondary-gradient: linear-gradient(135deg, #00695c 0%, #0277bd 100%);
            --success-gradient: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
            --warning-gradient: linear-gradient(135deg, #d84315 0%, #bf360c 100%);
            --danger-gradient: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
            --info-gradient: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
            --dark-gradient: linear-gradient(135deg, #263238 0%, #37474f 100%);

            /* Texto */
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --text-light: #ecf0f1;

            /* Fondos */
            --bg-light: rgba(255, 255, 255, 0.95);
            --bg-dark: rgba(44, 62, 80, 0.95);
            --bg-surface: rgba(255, 255, 255, 0.98);

            /* Bordes */
            --border-light: rgba(255, 255, 255, 0.2);
            --border-primary: rgba(39, 174, 96, 0.2);

            /* Sombras */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);

            /* Efectos */
            --blur: blur(20px);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3498db, #27ae60, #f39c12);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.7;
            overflow-x: hidden;
            padding: 20px;
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
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }
            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        @keyframes shimmer {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        /* ======================
        BOTONES
        ====================== */
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
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 105, 92, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00695c 0%, #004d40 100%);
            box-shadow: 0 8px 25px rgba(0, 105, 92, 0.4);
        }

        /* ======================
        ESTILOS DE BITÁCORA 
        ====================== */
        .bitacora-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
            position: relative;
        }

        /* Header de bitácora */
        .bitacora-header {
            background: var(--bg-light);
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

        .bitacora-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
            animation: shimmer 3s ease-in-out infinite;
        }

        .bitacora-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bitacora-title h1 {
            margin: 0;
            font-size: 1.8rem;
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Tarjeta de bitácora */
        .bitacora-card {
            background: var(--bg-light);
            backdrop-filter: var(--blur);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            margin-bottom: 35px;
        }

        .bitacora-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--secondary-gradient);
            opacity: 0;
            transition: var(--transition);
        }

        .bitacora-card:hover::before {
            opacity: 1;
        }

        .bitacora-card-header {
            padding: 25px 30px 0;
            border-bottom: 2px solid var(--border-primary);
            margin-bottom: 25px;
        }

        .bitacora-card-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .bitacora-card-body {
            padding: 0 30px 30px;
        }

        /* Filtros */
        .search-filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-button {
            height: fit-content;
            margin-bottom: 8px;
        }

        /* Formularios */
        .form-control {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid var(--border-primary);
            border-radius: 12px;
            font-size: 15px;
            background: var(--bg-surface);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            outline: none;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Tabla de bitácora */
        .bitacora-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            background: var(--bg-surface);
        }

        .bitacora-table th {
            background: var(--light);
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .bitacora-table td {
            padding: 18px 15px;
            border-bottom: 1px solid var(--border-primary);
            background: var(--bg-surface);
        }

        .bitacora-table tr:hover td {
            background: rgba(39, 174, 96, 0.03);
        }

        /* Estado vacío */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
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

        /* Íconos */
        .icon-container {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary-gradient);
            position: relative;
            margin-bottom: 15px;
            z-index: 1;
        }

        .icon-container > i {
            color: white !important;
            font-size: 1.3rem;
            position: relative;
            z-index: 100;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        /* Efecto hover */
        .icon-container:hover {
            transform: scale(1.1) rotate(5deg);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .bitacora-container {
                padding: 20px 15px;
            }
            
            .bitacora-header {
                padding: 20px;
            }
            
            .bitacora-card-header,
            .bitacora-card-body {
                padding: 20px 25px;
            }
        }

        @media (max-width: 768px) {
            .bitacora-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
                padding: 20px;
            }
            
            .search-filter-container {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .bitacora-table {
                display: block;
                overflow-x: auto;
            }
            
            .bitacora-table th,
            .bitacora-table td {
                padding: 12px 8px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .bitacora-container {
                padding: 15px 10px;
            }
            
            .bitacora-header {
                padding: 20px;
                border-radius: 18px;
            }
            
            .bitacora-card {
                border-radius: 18px;
            }
            
            .bitacora-card-header,
            .bitacora-card-body {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="bitacora-container">
        <!-- Header -->
        <div class="bitacora-header">
            <div class="bitacora-title">
                <div class="icon-container">
                    <i class="fas fa-book"></i>
                </div>
                <h1>Bitácora del Sistema</h1>
            </div>
            <div class="header-actions">
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Card principal -->
        <div class="bitacora-card">
            <div class="bitacora-card-header">
                <h2>
                    <i class="fas fa-clipboard-list"></i>
                    Registro de todas las actividades realizadas en el sistema
                </h2>
            </div>
            <div class="bitacora-card-body">
                <!-- Filtros -->
                <form method="GET" class="search-filter-container">
                    <div class="filter-group">
                        <label for="usuario_id" class="form-label">Usuario</label>
                        <select name="usuario_id" id="usuario_id" class="form-control">
                            <option value="">Todos los usuarios</option>
                            <option value="1">Administrador</option>
                            <option value="2">Empleado 1</option>
                            <option value="3">Empleado 2</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="fecha_inicio" class="form-label">Desde</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="">
                    </div>
                    
                    <div class="filter-group">
                        <label for="fecha_fin" class="form-label">Hasta</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="">
                    </div>
                    
                    <div class="filter-group filter-button">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla con IP -->
                <div style="overflow-x: auto; margin-top: 20px;">
                    <table class="bitacora-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Dirección IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15/03/2023 10:25:36</td>
                                <td>Administrador</td>
                                <td>Inició sesión en el sistema</td>
                                <td>192.168.1.10</td>
                            </tr>
                            <tr>
                                <td>15/03/2023 10:30:15</td>
                                <td>Administrador</td>
                                <td>Creó un nuevo usuario</td>
                                <td>192.168.1.10</td>
                            </tr>
                            <tr>
                                <td>15/03/2023 11:05:42</td>
                                <td>Empleado 1</td>
                                <td>Registró un nuevo servicio</td>
                                <td>192.168.1.15</td>
                            </tr>
                            <tr>
                                <td>15/03/2023 12:30:18</td>
                                <td>Empleado 2</td>
                                <td>Actualizó información de cliente</td>
                                <td>192.168.1.20</td>
                            </tr>
                            <tr>
                                <td>15/03/2023 14:15:33</td>
                                <td>Administrador</td>
                                <td>Generó reporte de ventas</td>
                                <td>192.168.1.10</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="pagination">
                    <a href="#" class="page-link active">1</a>
                    <a href="#" class="page-link">2</a>
                    <a href="#" class="page-link">3</a>
                    <a href="#" class="page-link">Siguiente</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Simulación de datos y funcionalidades
        document.addEventListener('DOMContentLoaded', function() {
            // Simular mensajes de éxito/error
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Operación realizada correctamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            }
            
            // Funcionalidad de filtrado
            const filterForm = document.querySelector('form');
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Simular filtrado
                Swal.fire({
                    title: 'Filtrando',
                    text: 'Aplicando filtros...',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 1000
                });
            });
        });
    </script>
</body>
</html>