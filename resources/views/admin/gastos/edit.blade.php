<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Gasto - AutoGest Admin</title>
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
            top: 30%;
            left: 30%;
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

        /* Contenido principal */
        .content-section {
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

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }

        /* Formulario */
        .form-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            color: white;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: white;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            box-shadow: 0 0 0 0.3rem rgba(255, 255, 255, 0.1);
            outline: none;
        }

        .form-select-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: white;
            font-weight: 500;
            width: 100%;
        }

        .form-select-modern option {
            background: var(--text-primary);
            color: white;
        }

        .is-invalid {
            border-color: #e74c3c !important;
        }

        .invalid-feedback {
            color: #ff6b7a;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Información del gasto actual */
        .current-info-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
        }

        .current-info-title {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .current-info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .current-info-item:last-child {
            border-bottom: none;
        }

        .current-info-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .current-info-value {
            color: white;
            font-weight: 600;
        }

        .current-monto {
            font-size: 1.2rem;
            color: var(--warning-orange);
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Vista previa de cambios */
        .preview-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .preview-title {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .preview-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .preview-value {
            color: white;
            font-weight: 600;
        }

        .preview-value.changed {
            color: var(--warning-orange);
            animation: highlight 2s ease-in-out infinite;
        }

        @keyframes highlight {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .monto-preview {
            font-size: 1.2rem;
            color: var(--warning-orange);
        }

        /* Comparación de cambios */
        .changes-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .changes-title {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .change-item {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 10px;
            background: rgba(243, 156, 18, 0.1);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }

        .change-label {
            color: white;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .change-comparison {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .change-old {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: line-through;
        }

        .change-new {
            color: var(--warning-orange);
            font-weight: 600;
        }

        .change-arrow {
            color: var(--success-green);
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

        .btn-action:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-action:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--success-green), #58d68d);
            color: white;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c, #ec7063);
            color: white;
        }

        /* Loading state */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alertas */
        .alert-modern {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 0 2rem 1rem;
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

        .alert-warning-modern {
            background: rgba(243, 156, 18, 0.15);
            color: white;
            border: 1px solid rgba(243, 156, 18, 0.3);
        }

        /* Info cards */
        .info-card {
            background: rgba(52, 152, 219, 0.15);
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .info-card h6 {
            color: white;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .info-card ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .info-card li {
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
            }

            .header-title {
                font-size: 1.8rem;
            }

            .content-grid {
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
                    <i class="fas fa-edit me-3"></i>
                    Editar Gasto
                </h1>
                <p class="header-subtitle">
                    Modifica la información del gasto #{{ str_pad($gasto->id, 6, '0', STR_PAD_LEFT) }}
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
                    <a href="{{ route('admin.gastos.show', $gasto->id) }}" class="btn-modern btn-primary-modern">
                        <i class="fas fa-eye"></i>
                        Ver Detalles
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

        <!-- Contenido -->
        <div class="content-section">
            <div class="content-grid">
                <!-- Formulario -->
                <div>
                    <!-- Información actual del gasto -->
                    <div class="current-info-card">
                        <h6 class="current-info-title">
                            <i class="fas fa-info-circle"></i>
                            Información Actual
                        </h6>
                        <div class="current-info-item">
                            <span class="current-info-label">ID:</span>
                            <span class="current-info-value">#{{ str_pad($gasto->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="current-info-item">
                            <span class="current-info-label">Tipo:</span>
                            <span class="current-info-value">{{ $gasto->tipo_formateado }}</span>
                        </div>
                        <div class="current-info-item">
                            <span class="current-info-label">Monto Actual:</span>
                            <span class="current-info-value current-monto">${{ number_format($gasto->monto, 2) }}</span>
                        </div>
                        <div class="current-info-item">
                            <span class="current-info-label">Fecha Original:</span>
                            <span class="current-info-value">{{ $gasto->fecha_gasto->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="form-card">
                        <!-- Información -->
                        <div class="info-card">
                            <h6><i class="fas fa-edit me-2"></i>Edición de Gasto</h6>
                            <ul>
                                <li>Modifica únicamente los campos que necesites cambiar</li>
                                <li>La fecha del gasto no puede ser futura</li>
                                <li>Los cambios se reflejarán inmediatamente en el sistema</li>
                                <li>Se guardará un registro de esta modificación</li>
                            </ul>
                        </div>

                        <form id="gastoForm" action="{{ route('admin.gastos.update', $gasto->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Usuario responsable -->
                                <div class="col-md-6 form-group">
                                    <label class="form-label-modern">
                                        <i class="fas fa-user"></i>
                                        Usuario Responsable <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <select name="usuario_id" id="usuario_id"
                                            class="form-select-modern @error('usuario_id') is-invalid @enderror"
                                            required>
                                        <option value="">Selecciona un usuario</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}"
                                                    {{ (old('usuario_id', $gasto->usuario_id) == $usuario->id) ? 'selected' : '' }}>
                                                {{ $usuario->nombre }} - {{ $usuario->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('usuario_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tipo de gasto -->
                                <div class="col-md-6 form-group">
                                    <label class="form-label-modern">
                                        <i class="fas fa-tags"></i>
                                        Tipo de Gasto <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <select name="tipo" id="tipo"
                                            class="form-select-modern @error('tipo') is-invalid @enderror"
                                            required>
                                        <option value="">Selecciona un tipo</option>
                                        @foreach($tipos as $key => $tipo)
                                            <option value="{{ $key }}"
                                                    {{ (old('tipo', $gasto->tipo) == $key) ? 'selected' : '' }}>
                                                {{ $tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Detalle del gasto -->
                                <div class="col-md-8 form-group">
                                    <label class="form-label-modern">
                                        <i class="fas fa-edit"></i>
                                        Detalle del Gasto <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <input type="text" name="detalle" id="detalle"
                                           class="form-control-modern @error('detalle') is-invalid @enderror"
                                           placeholder="Describe el gasto detalladamente..."
                                           value="{{ old('detalle', $gasto->detalle) }}"
                                           maxlength="255"
                                           required>
                                    @error('detalle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-white-50 mt-1">
                                        <span id="contadorCaracteres">{{ strlen($gasto->detalle) }}</span> / 255 caracteres
                                    </small>
                                </div>

                                <!-- Monto -->
                                <div class="col-md-4 form-group">
                                    <label class="form-label-modern">
                                        <i class="fas fa-dollar-sign"></i>
                                        Monto <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <input type="number" name="monto" id="monto"
                                           class="form-control-modern @error('monto') is-invalid @enderror"
                                           placeholder="0.00"
                                           value="{{ old('monto', $gasto->monto) }}"
                                           min="0.01"
                                           step="0.01"
                                           required>
                                    @error('monto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Fecha del gasto -->
                                <div class="col-md-6 form-group">
                                    <label class="form-label-modern">
                                        <i class="fas fa-calendar-alt"></i>
                                        Fecha del Gasto <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <input type="date" name="fecha_gasto" id="fecha_gasto"
                                           class="form-control-modern @error('fecha_gasto') is-invalid @enderror"
                                           value="{{ old('fecha_gasto', $gasto->fecha_gasto->format('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                    @error('fecha_gasto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Vista previa de cambios -->
                    <div class="preview-card">
                        <h6 class="preview-title">
                            <i class="fas fa-eye me-2"></i>
                            Vista Previa
                        </h6>
                        <div class="preview-item">
                            <span class="preview-label">Usuario:</span>
                            <span class="preview-value" id="previewUsuario">{{ $gasto->usuario ? $gasto->usuario->nombre : '-' }}</span>
                        </div>
                        <div class="preview-item">
                            <span class="preview-label">Tipo:</span>
                            <span class="preview-value" id="previewTipo">{{ $gasto->tipo_formateado }}</span>
                        </div>
                        <div class="preview-item">
                            <span class="preview-label">Detalle:</span>
                            <span class="preview-value" id="previewDetalle">{{ $gasto->detalle }}</span>
                        </div>
                        <div class="preview-item">
                            <span class="preview-label">Fecha:</span>
                            <span class="preview-value" id="previewFecha">{{ $gasto->fecha_gasto->format('d/m/Y') }}</span>
                        </div>
                        <div class="preview-item">
                            <span class="preview-label">Monto:</span>
                            <span class="preview-value monto-preview" id="previewMonto">${{ number_format($gasto->monto, 2) }}</span>
                        </div>
                    </div>

                    <!-- Comparación de cambios -->
                    <div class="changes-card" id="changesCard" style="display: none;">
                        <h6 class="changes-title">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Cambios Detectados
                        </h6>
                        <div id="changesList"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="actions-section">
            <div class="actions-row">
                <a href="{{ route('admin.gastos.show', $gasto->id) }}" class="btn-action btn-cancel">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
                <button type="submit" form="gastoForm" class="btn-action btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    <span class="btn-text">Guardar Cambios</span>
                </button>
                <button type="button" class="btn-action btn-delete" onclick="confirmarEliminacion()">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('gastoForm');
            const submitBtn = document.getElementById('submitBtn');
            const changesCard = document.getElementById('changesCard');
            const changesList = document.getElementById('changesList');
            const contadorCaracteres = document.getElementById('contadorCaracteres');

            // Campos del formulario
            const usuarioSelect = document.getElementById('usuario_id');
            const tipoSelect = document.getElementById('tipo');
            const detalleInput = document.getElementById('detalle');
            const montoInput = document.getElementById('monto');
            const fechaInput = document.getElementById('fecha_gasto');

            // Elementos de vista previa
            const previewUsuario = document.getElementById('previewUsuario');
            const previewTipo = document.getElementById('previewTipo');
            const previewDetalle = document.getElementById('previewDetalle');
            const previewMonto = document.getElementById('previewMonto');
            const previewFecha = document.getElementById('previewFecha');

            // Valores originales
            const originalValues = {
                usuario_id: '{{ $gasto->usuario_id }}',
                usuario_nombre: '{{ $gasto->usuario ? $gasto->usuario->nombre : "" }}',
                tipo: '{{ $gasto->tipo }}',
                tipo_formateado: '{{ $gasto->tipo_formateado }}',
                detalle: '{{ $gasto->detalle }}',
                monto: '{{ $gasto->monto }}',
                fecha_gasto: '{{ $gasto->fecha_gasto->format("Y-m-d") }}',
                fecha_formateada: '{{ $gasto->fecha_gasto->format("d/m/Y") }}'
            };

            const tiposDisponibles = @json($tipos);

            // Contador de caracteres
            detalleInput.addEventListener('input', function() {
                contadorCaracteres.textContent = this.value.length;
                actualizarVistaPrevia();
            });

            // Actualizar vista previa en tiempo real
            [usuarioSelect, tipoSelect, detalleInput, montoInput, fechaInput].forEach(field => {
                field.addEventListener('input', actualizarVistaPrevia);
                field.addEventListener('change', actualizarVistaPrevia);
            });

            function actualizarVistaPrevia() {
                const changes = [];

                // Usuario
                const usuarioOption = usuarioSelect.options[usuarioSelect.selectedIndex];
                if (usuarioSelect.value) {
                    const nuevoUsuario = usuarioOption.text.split(' - ')[0];
                    previewUsuario.textContent = nuevoUsuario;
                    previewUsuario.classList.toggle('changed', usuarioSelect.value !== originalValues.usuario_id);

                    if (usuarioSelect.value !== originalValues.usuario_id) {
                        changes.push({
                            label: 'Usuario Responsable',
                            old: originalValues.usuario_nombre,
                            new: nuevoUsuario
                        });
                    }
                } else {
                    previewUsuario.textContent = '-';
                }

                // Tipo
                if (tipoSelect.value) {
                    const nuevoTipo = tiposDisponibles[tipoSelect.value];
                    previewTipo.textContent = nuevoTipo;
                    previewTipo.classList.toggle('changed', tipoSelect.value !== originalValues.tipo);

                    if (tipoSelect.value !== originalValues.tipo) {
                        changes.push({
                            label: 'Tipo de Gasto',
                            old: originalValues.tipo_formateado,
                            new: nuevoTipo
                        });
                    }
                } else {
                    previewTipo.textContent = '-';
                }

                // Detalle
                if (detalleInput.value.trim()) {
                    previewDetalle.textContent = detalleInput.value;
                    previewDetalle.classList.toggle('changed', detalleInput.value !== originalValues.detalle);

                    if (detalleInput.value !== originalValues.detalle) {
                        changes.push({
                            label: 'Detalle',
                            old: originalValues.detalle,
                            new: detalleInput.value
                        });
                    }
                } else {
                    previewDetalle.textContent = '-';
                }

                // Monto
                if (montoInput.value && parseFloat(montoInput.value) > 0) {
                    const nuevoMonto = '$' + parseFloat(montoInput.value).toFixed(2);
                    previewMonto.textContent = nuevoMonto;
                    previewMonto.classList.toggle('changed', parseFloat(montoInput.value) !== parseFloat(originalValues.monto));

                    if (parseFloat(montoInput.value) !== parseFloat(originalValues.monto)) {
                        changes.push({
                            label: 'Monto',
                            old: '$' + parseFloat(originalValues.monto).toFixed(2),
                            new: nuevoMonto
                        });
                    }
                } else {
                    previewMonto.textContent = '$0.00';
                }

                // Fecha
                if (fechaInput.value) {
                    const fecha = new Date(fechaInput.value + 'T00:00:00');
                    const fechaFormateada = fecha.toLocaleDateString('es-ES');
                    previewFecha.textContent = fechaFormateada;
                    previewFecha.classList.toggle('changed', fechaInput.value !== originalValues.fecha_gasto);

                    if (fechaInput.value !== originalValues.fecha_gasto) {
                        changes.push({
                            label: 'Fecha del Gasto',
                            old: originalValues.fecha_formateada,
                            new: fechaFormateada
                        });
                    }
                } else {
                    previewFecha.textContent = '-';
                }

                // Mostrar/ocultar sección de cambios
                if (changes.length > 0) {
                    changesList.innerHTML = changes.map(change => `
                        <div class="change-item">
                            <div class="change-label">${change.label}</div>
                            <div class="change-comparison">
                                <span class="change-old">${change.old}</span>
                                <i class="fas fa-arrow-right change-arrow"></i>
                                <span class="change-new">${change.new}</span>
                            </div>
                        </div>
                    `).join('');
                    changesCard.style.display = 'block';
                } else {
                    changesCard.style.display = 'none';
                }
            }

            // Manejo del envío del formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validar campos requeridos
                const requiredFields = [usuarioSelect, tipoSelect, detalleInput, montoInput, fechaInput];
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Por favor, completa todos los campos obligatorios.');
                    return;
                }

                // Validar monto
                if (parseFloat(montoInput.value) <= 0) {
                    montoInput.classList.add('is-invalid');
                    alert('El monto debe ser mayor a $0.00');
                    return;
                }

                // Cambiar estado del botón
                submitBtn.disabled = true;
                const btnText = submitBtn.querySelector('.btn-text');
                const btnIcon = submitBtn.querySelector('i');

                btnIcon.className = 'loading';
                btnText.textContent = 'Guardando...';

                // Enviar formulario
                setTimeout(() => {
                    form.submit();
                }, 1000);
            });

            // Inicializar vista previa
            actualizarVistaPrevia();
        });

        function confirmarEliminacion() {
            if (confirm('¿Estás seguro de que quieres eliminar este gasto?\n\nEsta acción no se puede deshacer.')) {
                // Crear formulario para DELETE
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.gastos.destroy", $gasto->id) }}';

                // CSRF Token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Method DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
