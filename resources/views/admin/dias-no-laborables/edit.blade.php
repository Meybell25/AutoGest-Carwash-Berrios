<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Día No Laborable - AutoGest</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #3498db;
            --success-green: #27ae60;
            --warning-orange: #f39c12;
            --danger-red: #e74c3c;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --bg-light: #ecf0f1;
            --shadow-light: rgba(52, 152, 219, 0.1);
            --shadow-medium: rgba(52, 152, 219, 0.2);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green), var(--warning-orange));
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.7;
            overflow-x: hidden;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 1rem;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: none;
            overflow: hidden;
            transition: var(--transition);
            animation: slideInUp 0.6s ease-out;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            margin-bottom: 2rem;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 35px 70px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.3);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--warning-orange), #e67e22);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .card-header-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            margin: 0 0.25rem;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-dashboard {
            background: rgba(52, 152, 219, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-dashboard:hover {
            background: rgba(52, 152, 219, 0.3);
            color: white;
            transform: translateX(-3px);
        }

        .btn-view {
            background: rgba(52, 152, 219, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-view:hover {
            background: rgba(52, 152, 219, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-3px);
        }

        .form-body {
            padding: 2.5rem;
        }

        .current-info-card {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(39, 174, 96, 0.1));
            border: 1px solid rgba(52, 152, 219, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .current-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-blue), var(--success-green));
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-icon {
            width: 30px;
            height: 30px;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
        }

        .form-group-modern {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label-modern {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            width: 100%;
        }

        .form-control-modern:focus {
            border-color: var(--warning-orange);
            box-shadow: 0 0 0 4px rgba(243, 156, 18, 0.1);
            outline: none;
            background: white;
        }

        .form-select-modern {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 3rem;
        }

        .alert-modern {
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .alert-warning-modern {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(230, 126, 34, 0.1));
            border-left: 4px solid var(--warning-orange);
        }

        .alert-info-modern {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(39, 174, 96, 0.1));
            border-left: 4px solid var(--primary-blue);
        }

        .alert-success-modern {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(46, 204, 113, 0.1));
            border-left: 4px solid var(--success-green);
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .preview-card {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(39, 174, 96, 0.1));
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--warning-orange);
            animation: fadeIn 0.3s ease-out;
        }

        .preview-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .preview-icon {
            width: 60px;
            height: 60px;
            background: rgba(243, 156, 18, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--warning-orange);
        }

        .btn-action-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--warning-orange), #e67e22);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(243, 156, 18, 0.3);
            color: white;
        }

        .btn-info-modern {
            background: linear-gradient(135deg, var(--primary-blue), #2980b9);
            color: white;
        }

        .btn-info-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
            color: white;
        }

        .btn-secondary-modern {
            background: rgba(127, 140, 141, 0.1);
            color: var(--text-secondary);
            border: 2px solid rgba(127, 140, 141, 0.2);
        }

        .btn-secondary-modern:hover {
            background: rgba(127, 140, 141, 0.2);
            color: var(--text-primary);
            transform: translateY(-2px);
        }

        .history-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            animation: slideInUp 0.6s ease-out;
            animation-delay: 0.3s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .history-header {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 1rem;
        }

        .history-body {
            padding: 1.5rem;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            background: var(--warning-orange);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            background: var(--primary-blue);
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            background: var(--success-green);
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .icon-input {
            color: var(--warning-orange);
            font-size: 1.1rem;
        }

        .required-star {
            color: #e74c3c;
            font-weight: bold;
        }

        .is-invalid {
            border-color: #e74c3c !important;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-warning { background: linear-gradient(135deg, var(--warning-orange), #e67e22); }
        .badge-danger { background: linear-gradient(135deg, var(--danger-red), #c0392b); }
        .badge-secondary { background: linear-gradient(135deg, #95a5a6, #7f8c8d); }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(5deg); }
            66% { transform: translateY(10px) rotate(-5deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .card-header-modern {
                padding: 1.5rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .btn-action-group {
                grid-template-columns: 1fr;
            }

            .header-title {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .header-content .d-flex {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animaciones de entrada escalonadas */
        .form-group-modern {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .form-group-modern:nth-child(1) { animation-delay: 0.1s; }
        .form-group-modern:nth-child(2) { animation-delay: 0.2s; }
        .form-group-modern:nth-child(3) { animation-delay: 0.3s; }
        .form-group-modern:nth-child(4) { animation-delay: 0.4s; }
        .alert-modern { animation-delay: 0.5s; }
        .btn-action-group { animation-delay: 0.6s; }

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
    </style>
</head>
<body>
    <!-- Formas flotantes decorativas -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Card principal de edición -->
                    <div class="form-card">
                        <!-- Header moderno -->
                        <div class="card-header-modern">
                            <div class="header-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h1 class="header-title">
                                        <div class="header-icon">
                                            <i class="fas fa-calendar-edit"></i>
                                        </div>
                                        Editar Día No Laborable
                                    </h1>
                                    <div class="d-flex flex-wrap">
                                        <a href="{{ route('admin.dashboard') }}" class="btn-modern btn-dashboard">
                                            <i class="fas fa-tachometer-alt"></i>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('admin.dias-no-laborables.show', $dia->id) }}" class="btn-modern btn-view">
                                            <i class="fas fa-eye"></i>
                                            Ver detalles
                                        </a>
                                        <a href="{{ route('admin.dias-no-laborables.index') }}" class="btn-modern btn-back">
                                            <i class="fas fa-arrow-left"></i>
                                            Volver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cuerpo del formulario -->
                        <div class="form-body">
                            <!-- Información actual -->
                            <div class="current-info-card">
                                <h6 class="alert-title" style="color: var(--primary-blue);">
                                    <i class="fas fa-info-circle"></i>
                                    Información actual
                                </h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div>
                                            <strong>Fecha:</strong> {{ $dia->fecha->format('d/m/Y') }}<br>
                                            <small class="text-muted">{{ $dia->fecha->translatedFormat('l') }}</small>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <strong>Motivo:</strong> {{ $motivosDisponibles[$dia->motivo] ?? $dia->motivo }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    @if($dia->dias_restantes > 0)
                                        <span class="badge badge-status badge-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $dia->dias_restantes }} días restantes
                                        </span>
                                    @elseif($dia->es_hoy)
                                        <span class="badge badge-status badge-danger">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Es hoy
                                        </span>
                                    @else
                                        <span class="badge badge-status badge-secondary">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Ya pasó
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Alertas de éxito -->
                            @if(session('success'))
                                <div class="alert-modern alert-success-modern">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.dias-no-laborables.update', $dia->id) }}" method="POST" id="formEditar">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="fecha" class="form-label-modern">
                                                <i class="fas fa-calendar icon-input"></i>
                                                Fecha <span class="required-star">*</span>
                                            </label>
                                            <input type="date"
                                                   class="form-control form-control-modern @error('fecha') is-invalid @enderror"
                                                   id="fecha"
                                                   name="fecha"
                                                   value="{{ old('fecha', $dia->fecha->format('Y-m-d')) }}"
                                                   min="{{ date('Y-m-d') }}"
                                                   required>
                                            @error('fecha')
                                                <div class="invalid-feedback">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted mt-2">
                                                Solo se pueden usar fechas futuras o la fecha actual
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="motivo_select" class="form-label-modern">
                                                <i class="fas fa-tag icon-input"></i>
                                                Motivo <span class="required-star">*</span>
                                            </label>
                                            <select class="form-control form-control-modern form-select-modern @error('motivo') is-invalid @enderror"
                                                    id="motivo_select"
                                                    onchange="toggleMotivoPersonalizado()">
                                                <option value="">Seleccione un motivo</option>
                                                @foreach($motivosDisponibles as $key => $motivo)
                                                    <option value="{{ $key }}" {{ (old('motivo', $dia->motivo) == $key) ? 'selected' : '' }}>
                                                        {{ $motivo }}
                                                    </option>
                                                @endforeach
                                                <option value="personalizado" {{ (!array_key_exists(old('motivo', $dia->motivo), $motivosDisponibles)) ? 'selected' : '' }}>
                                                    Otro (personalizado)
                                                </option>
                                            </select>
                                            @error('motivo')
                                                <div class="invalid-feedback">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Campo de motivo personalizado -->
                                <div class="form-group-modern" id="motivoPersonalizadoContainer"
                                     style="display: {{ (!array_key_exists(old('motivo', $dia->motivo), $motivosDisponibles)) ? 'block' : 'none' }};">
                                    <label for="motivo_personalizado" class="form-label-modern">
                                        <i class="fas fa-edit icon-input"></i>
                                        Motivo personalizado <span class="required-star">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-modern"
                                           id="motivo_personalizado"
                                           placeholder="Describe el motivo específico"
                                           value="{{ (!array_key_exists(old('motivo', $dia->motivo), $motivosDisponibles)) ? old('motivo', $dia->motivo) : '' }}"
                                           maxlength="255">
                                    <small class="form-text text-muted mt-2">Máximo 255 caracteres</small>
                                </div>

                                <!-- Campo oculto para el motivo final -->
                                <input type="hidden" name="motivo" id="motivo_final" value="{{ old('motivo', $dia->motivo) }}">

                                <!-- Información sobre cambios -->
                                <div class="alert-modern alert-warning-modern">
                                    <h6 class="alert-title" style="color: var(--warning-orange);">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Importante
                                    </h6>
                                    <ul class="mb-0" style="color: var(--text-secondary); line-height: 1.6;">
                                        <li>Si cambias la fecha, se verificará que no exista otro día no laborable en esa fecha</li>
                                        <li>Los cambios afectarán inmediatamente la disponibilidad de citas</li>
                                        <li>Se notificará a los administradores sobre esta modificación</li>
                                        @if($dia->es_pasado)
                                            <li style="color: var(--danger-red);"><strong>Este día ya pasó. ¿Estás seguro de editarlo?</strong></li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Vista previa de cambios -->
                                <div id="vistaPrevia" style="display: none;">
                                    <div class="preview-card">
                                        <h6 class="alert-title" style="color: var(--warning-orange);">
                                            <i class="fas fa-eye"></i>
                                            Vista previa de cambios
                                        </h6>
                                        <div class="preview-content">
                                            <div class="preview-icon">
                                                <i class="fas fa-calendar-times"></i>
                                            </div>
                                            <div>
                                                <strong id="fechaPreview"></strong><br>
                                                <span class="text-muted" id="diaPreview"></span><br>
                                                <span class="badge bg-info" id="motivoPreview"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="btn-action-group">
                                    <button type="submit" class="btn-modern btn-primary-modern" id="btnActualizar">
                                        <i class="fas fa-save"></i>
                                        Actualizar Cambios
                                    </button>
                                    <a href="{{ route('admin.dias-no-laborables.show', $dia->id) }}" class="btn-modern btn-info-modern">
                                        <i class="fas fa-eye"></i>
                                        Ver detalles
                                    </a>
                                    <a href="{{ route('admin.dias-no-laborables.index') }}" class="btn-modern btn-secondary-modern">
                                        <i class="fas fa-times"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Card de historial -->
                    <div class="history-card">
                        <div class="history-header">
                            <h6 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Información del registro
                            </h6>
                        </div>
                        <div class="history-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-plus-circle me-2" style="color: var(--success-green);"></i>
                                        <div>
                                            <strong>Creado:</strong><br>
                                            <small class="text-muted">
                                                {{ $dia->created_at ? $dia->created_at->format('d/m/Y H:i') : 'No disponible' }}
                                                @if($dia->created_at)
                                                    <br>{{ $dia->created_at->diffForHumans() }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-edit me-2" style="color: var(--warning-orange);"></i>
                                        <div>
                                            <strong>Última modificación:</strong><br>
                                            <small class="text-muted">
                                                {{ $dia->updated_at && $dia->updated_at != $dia->created_at ? $dia->updated_at->format('d/m/Y H:i') : 'Sin modificaciones' }}
                                                @if($dia->updated_at && $dia->updated_at != $dia->created_at)
                                                    <br>{{ $dia->updated_at->diffForHumans() }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.getElementById('fecha');
            const motivoSelect = document.getElementById('motivo_select');
            const motivoPersonalizado = document.getElementById('motivo_personalizado');
            const motivoFinal = document.getElementById('motivo_final');
            const vistaPrevia = document.getElementById('vistaPrevia');
            const fechaPreview = document.getElementById('fechaPreview');
            const diaPreview = document.getElementById('diaPreview');
            const motivoPreview = document.getElementById('motivoPreview');

            // Valores originales para comparar cambios
            const fechaOriginal = '{{ $dia->fecha->format('Y-m-d') }}';
            const motivoOriginal = '{{ $dia->motivo }}';

            // Actualizar vista previa cuando cambian los campos
            function actualizarVistaPrevia() {
                const fecha = fechaInput.value;
                const motivo = getMotivo();

                // Solo mostrar vista previa si hay cambios
                const hayFechaCambiada = fecha !== fechaOriginal;
                const hayMotivoCambiado = motivo !== motivoOriginal;

                if ((hayFechaCambiada || hayMotivoCambiado) && fecha && motivo) {
                    const fechaObj = new Date(fecha + 'T12:00:00');

                    fechaPreview.textContent = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    diaPreview.textContent = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long'
                    });

                    motivoPreview.textContent = motivo;
                    vistaPrevia.style.display = 'block';
                } else {
                    vistaPrevia.style.display = 'none';
                }
            }

            function getMotivo() {
                if (motivoSelect.value === 'personalizado') {
                    return motivoPersonalizado.value;
                } else if (motivoSelect.value) {
                    return motivoSelect.options[motivoSelect.selectedIndex].text;
                }
                return '';
            }

            fechaInput.addEventListener('change', actualizarVistaPrevia);
            motivoSelect.addEventListener('change', function() {
                toggleMotivoPersonalizado();
                actualizarVistaPrevia();
            });
            motivoPersonalizado.addEventListener('input', actualizarVistaPrevia);

            // Validación del formulario con efectos visuales
            document.getElementById('formEditar').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('btnActualizar');
                const motivo = getMotivo();

                if (!motivo) {
                    e.preventDefault();
                    alert('Por favor seleccione o ingrese un motivo.');
                    return;
                }

                // Establecer el valor final del motivo
                if (motivoSelect.value === 'personalizado') {
                    motivoFinal.value = motivoPersonalizado.value;
                } else {
                    motivoFinal.value = motivoSelect.value;
                }

                // Confirmar si está editando un día pasado
                @if($dia->es_pasado)
                if (!confirm('Este día ya pasó. ¿Estás seguro de que quieres editarlo?')) {
                    e.preventDefault();
                    return;
                }
                @endif

                // Efecto de carga en el botón
                submitBtn.style.transform = 'scale(0.95)';
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
            });

            // Inicializar vista previa
            actualizarVistaPrevia();
        });

        function toggleMotivoPersonalizado() {
            const motivoSelect = document.getElementById('motivo_select');
            const container = document.getElementById('motivoPersonalizadoContainer');
            const input = document.getElementById('motivo_personalizado');

            if (motivoSelect.value === 'personalizado') {
                container.style.display = 'block';
                input.required = true;
                input.focus();
            } else {
                container.style.display = 'none';
                input.required = false;
            }
        }
    </script>
</body>
</html>
