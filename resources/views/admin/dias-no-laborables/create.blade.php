<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agregar Día No Laborable - AutoGest</title>

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
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 35px 70px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.3);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green));
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
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
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
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(39, 174, 96, 0.1));
            border: 1px solid rgba(52, 152, 219, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .alert-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-blue), var(--success-green));
        }

        .alert-title {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-action-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-blue), var(--success-green));
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-modern:hover {
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
            color: var(--primary-blue);
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
        }

        /* Animaciones de entrada para los elementos del formulario */
        .form-group-modern {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .form-group-modern:nth-child(1) { animation-delay: 0.1s; }
        .form-group-modern:nth-child(2) { animation-delay: 0.2s; }
        .form-group-modern:nth-child(3) { animation-delay: 0.3s; }
        .alert-modern { animation-delay: 0.4s; }
        .btn-action-group { animation-delay: 0.5s; }

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
                <div class="col-lg-10 col-xl-8">
                    <div class="form-card">
                        <!-- Header moderno -->
                        <div class="card-header-modern">
                            <div class="header-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h1 class="header-title">
                                        <div class="header-icon">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                        Agregar Día No Laborable
                                    </h1>
                                    <a href="{{ route('admin.dias-no-laborables.index') }}" class="btn-modern btn-back">
                                        <i class="fas fa-arrow-left"></i>
                                        Volver
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Cuerpo del formulario -->
                        <div class="form-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.dias-no-laborables.store') }}" method="POST" id="formDiaNoLaborable">
                                @csrf

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
                                                   value="{{ old('fecha') }}"
                                                   min="{{ date('Y-m-d') }}"
                                                   required>
                                            @error('fecha')
                                                <div class="invalid-feedback">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted mt-2">
                                                Solo se pueden agregar fechas futuras o la fecha actual
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="motivo" class="form-label-modern">
                                                <i class="fas fa-tag icon-input"></i>
                                                Motivo <span class="required-star">*</span>
                                            </label>
                                            <select class="form-control form-control-modern form-select-modern @error('motivo') is-invalid @enderror"
                                                    id="motivo"
                                                    name="motivo"
                                                    required>
                                                <option value="">Seleccione un motivo</option>
                                                @foreach($motivosDisponibles as $key => $motivo)
                                                    <option value="{{ $key }}" {{ old('motivo') == $key ? 'selected' : '' }}>
                                                        {{ $motivo }}
                                                    </option>
                                                @endforeach
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

                                <!-- Información importante -->
                                <div class="alert-modern">
                                    <h6 class="alert-title">
                                        <i class="fas fa-info-circle"></i>
                                        Información importante
                                    </h6>
                                    <ul class="mb-0" style="color: var(--text-secondary); line-height: 1.6;">
                                        <li>Los días no laborables afectarán automáticamente la disponibilidad de citas</li>
                                        <li>Los clientes no podrán agendar nuevas citas en estas fechas</li>
                                        <li>Se notificará a los administradores sobre este cambio</li>
                                        <li>Las citas existentes para esta fecha serán marcadas para revisión</li>
                                    </ul>
                                </div>

                                <!-- Vista previa del día seleccionado -->
                                <div id="preview" style="display: none;">
                                    <div class="alert-modern" style="background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(39, 174, 96, 0.1));">
                                        <h6 class="alert-title" style="color: var(--warning-orange);">
                                            <i class="fas fa-eye"></i>
                                            Vista previa
                                        </h6>
                                        <div id="previewContent" style="color: var(--text-secondary);"></div>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="btn-action-group">
                                    <button type="submit" class="btn-modern btn-primary-modern">
                                        <i class="fas fa-save"></i>
                                        Guardar Día No Laborable
                                    </button>
                                    <a href="{{ route('admin.dias-no-laborables.index') }}" class="btn-modern btn-secondary-modern">
                                        <i class="fas fa-times"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </form>
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
            const motivoSelect = document.getElementById('motivo');
            const preview = document.getElementById('preview');
            const previewContent = document.getElementById('previewContent');

            // Actualizar vista previa cuando cambian los campos
            function actualizarVistaPrevia() {
                const fecha = fechaInput.value;
                const motivo = motivoSelect.options[motivoSelect.selectedIndex]?.text;

                if (fecha && motivo && motivo !== 'Seleccione un motivo') {
                    const fechaObj = new Date(fecha + 'T12:00:00');
                    const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    previewContent.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-calendar-times fa-2x" style="color: var(--warning-orange);"></i>
                            </div>
                            <div>
                                <strong style="color: var(--text-primary);">${fechaFormateada}</strong><br>
                                <span style="color: var(--text-secondary);">Motivo: ${motivo}</span>
                            </div>
                        </div>
                    `;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }
            }

            fechaInput.addEventListener('change', actualizarVistaPrevia);
            motivoSelect.addEventListener('change', actualizarVistaPrevia);

            // Validación del formulario con efectos visuales
            document.getElementById('formDiaNoLaborable').addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');

                // Efecto de carga en el botón
                submitBtn.style.transform = 'scale(0.95)';
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                // Validar que se haya seleccionado una fecha y motivo
                if (!fechaInput.value || !motivoSelect.value) {
                    e.preventDefault();
                    submitBtn.style.transform = 'scale(1)';
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Día No Laborable';
                    alert('Por favor, completa todos los campos obligatorios.');
                    return;
                }
            });
        });
    </script>
</body>
</html>
