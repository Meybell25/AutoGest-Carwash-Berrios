<!DOCTYPE html>
<html lang="es" class="user-{{ Auth::check() ? Auth::user()->rol : 'guest' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoGest Carwash Berrios')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ========== ESTILOS BASE COMUNES ========== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            line-height: 1.6;
            background-attachment: fixed;
            color: #333;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        /* ========== FONDOS ESPECÍFICOS POR ROL ========== */

        /* CLIENTE - Fondo Morado */
        body.user-cliente {
            background: linear-gradient(135deg, #bbadfd 0%, #5b21b6 50%, #452383 100%);
        }

        body.user-cliente::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(187, 173, 253, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(91, 33, 182, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(69, 35, 131, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        /* ADMIN - Fondo Verde/Azul */
        body.user-admin {
            background: linear-gradient(135deg, #2e7d32 0%, #00695c 50%, #0277bd 100%);
        }

        body.user-admin::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(46, 125, 50, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 105, 92, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(2, 119, 189, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        /* EMPLEADO - Fondo Azul/Verde Oscuro */
        body.user-empleado {
            background: linear-gradient(135deg, #2563eb 0%, #00695c 50%, #0d47a1 100%);
        }

        body.user-empleado::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 105, 92, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(13, 71, 161, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        /* INVITADO (No logueado) - Fondo Morado Claro */
        body.user-guest {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #6b46c1 100%);
        }

        body.user-guest::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(107, 70, 193, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        /* ========== ANIMACIÓN DE PARTÍCULAS FLOTANTES ========== */
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

        /* ========== ESTILOS PARA COMPONENTES ========== */
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        }

        .btn-warning {
            background: linear-gradient(45deg, #e98f12 0%, #dec11e 100%);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
        }

        .btn-danger {
            background: linear-gradient(45deg, #c95165 0%, #d0407c 100%);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 117, 140, 0.4);
        }

        .is-valid {
            border-color: #28a745 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 0.25rem;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .is-invalid~.invalid-feedback,
        .is-invalid~.invalid-tooltip {
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .card {
                margin: 10px 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="@yield('body-class', '')">
    <div class="container py-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline mr-2'
            },
            buttonsStyling: false
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                swalWithBootstrapButtons.fire({
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            @endif

            @if (session('error'))
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            @endif

            @if ($errors->any())
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    html: `@foreach ($errors->all() as $error)
                    • {{ $error }}<br>
                @endforeach`,
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>