<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Empleado - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #f8fafc;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;

            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--gray-700) 100%);
            --gradient-success: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            --gradient-warning: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

            --border-radius: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;

            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(315deg, #512da8, #00695c, #0d47a1);
            min-height: 100vh;
            color: var(--gray-900);
            line-height: 1.6;
            background-attachment: fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Partículas flotantes de fondo con colores de la paleta del empleado */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(81, 45, 168, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 105, 92, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(13, 71, 161, 0.05) 0%, transparent 50%);
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


        /* Contenedor principal ajustado */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
            min-height: calc(100vh - 200px);
        }

        /* BARRA DE DESPLAZAMIENTO PERSONALIZADA  */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        /* FONDO BLANCO (track) */
        ::-webkit-scrollbar-track {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* BARRA DESLIZANTE MORADA (thumb) */
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            border-radius: 10px;
            border: 2px solid #ffffff;
            transition: all 0.3s ease;
        }

        /* Efectos hover/interacción */
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            transform: scaleX(1.05);
        }

        /* Efecto activo (al hacer clic) */
        ::-webkit-scrollbar-thumb:active {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        }

        /* Para Firefox */
        html {
            scrollbar-width: thin;
            scrollbar-color: #6d28d9 #ffffff;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            border-radius: var(--border-radius-xl);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .welcome-section {
            flex: 1;
            min-width: 300px;
        }

        .welcome-section h1 {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-icon {
            background: var(--gradient-primary);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .welcome-icon i {
            z-index: 2;
            text-shadow: none;
            text-stroke: 0.5px white;
            -webkit-text-stroke: 0.5px white;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .welcome-section p {
            color: var(--gray-600);
            font-size: 1.125rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .welcome-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .welcome-stat {
            background: var(--white);
            padding: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-100);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .welcome-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: var(--transition);
        }

        .welcome-stat:hover::before {
            transform: scaleX(1);
        }

        .welcome-stat:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .welcome-stat .number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
        }

        .welcome-stat .label {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Botones */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            /* Usa la variable siempre */
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        /* Efecto hover para TODOS los botones */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary) !important;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white !important;
        }

        .btn-success {
            background: var(--gradient-success);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-warning {
            background: var(--gradient-warning);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-profile {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-profile:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }

        /* ======================
        ESTILOS PARA ÍCONOS
        ====================== */
        /* Íconos en botones principales */
        .btn i,
        .btn-outline i,
        .btn-primary i,
        .btn-success i,
        .btn-warning i {
            color: inherit !important;
            /* Hereda el color del texto del botón */
            transition: all 0.3s ease;
        }

        /* Casos específicos */

        .btn-outline i {
            color: var(--primary) !important;
            /* Azul para botones outline */
        }

        .btn-outline:hover i {
            color: white !important;
            /* Blanco al hacer hover */
        }

        /* Íconos en cards (títulos) */
        .card-header .icon i {
            color: white !important;
            font-size: 1.3rem !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
        }


        .card .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary) !important;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .card .btn-outline:hover {
            background: var(--primary);
            color: white !important;
        }


        /* Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .main-section,
        .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* Cards */
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: var(--transition);
            color: var(--primary) !important;
        }

        .card:hover {
            color: var(--color-white) !important;
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        }

        .card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
            border-bottom: 1px solid var(--gray-200);
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--gray-800);
        }

        .card-header .icon {
            background: var(--gradient-primary);
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            color: white;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .card-body {
            padding: 1.5rem 2rem;
        }

        /* Citas */
        .appointment-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .appointment-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--primary));
            opacity: 0.1;
            transition: var(--transition);
        }

        .appointment-card:hover::before {
            width: 100%;
        }

        .appointment-card:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-lg);
        }

        .appointment-card h3 {
            font-size: 1.125rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--gray-800);
        }

        .appointment-card p {
            margin-bottom: 0.5rem;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .appointment-card i {
            color: var(--primary);
            width: 16px;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Estados estilo badges  */
        .appointment-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-pendiente {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #ef6c00;
            border: 1px solid #ffcc80;
        }

        .status-confirmado,
        .status-confirmada {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc);
            color: #0277bd;
            border: 1px solid #81d4fa;
        }

        .status-en-proceso,
        .status-en_proceso {
            background: linear-gradient(135deg, #f1e6ff, #e1bee7);
            color: #6a1b9a;
            border: 1px solid #ce93d8;
        }

        .status-finalizado,
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

        /* Efectos hover para los badges */
        .appointment-status:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .status-pendiente:hover {
            background: linear-gradient(135deg, #ffe0b2, #ffcc80);
        }

        .status-confirmado:hover,
        .status-confirmada:hover {
            background: linear-gradient(135deg, #b3e5fc, #81d4fa);
        }

        .status-en-proceso:hover,
        .status-en_proceso:hover {
            background: linear-gradient(135deg, #e1bee7, #ce93d8);
        }

        .status-finalizado:hover,
        .status-finalizada:hover {
            background: linear-gradient(135deg, #c8e6c9, #a5d6a7);
        }

        .status-cancelada:hover {
            background: linear-gradient(135deg, #f8bbd9, #f48fb1);
        }

        .service-tag {
            display: inline-block;
            background: var(--gray-100);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
            font-weight: 500;
            border: 1px solid var(--gray-200);
        }

        .appointment-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        /* Historial */
        .service-history-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--white);
            border-radius: var(--border-radius-lg);
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-100);
            transition: var(--transition);
        }

        .service-history-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .service-icon {
            background: var(--gradient-primary);
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
            box-shadow: var(--shadow-md);
        }

        .service-details {
            flex: 1;
        }

        .service-details h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: var(--gray-800);
            font-weight: 600;
        }

        .service-details p {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 0.25rem;
        }

        .service-price {
            font-weight: 700;
            color: var(--success);
            font-size: 1.125rem;
        }

        /* Tareas */
        .task-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--white);
            border-radius: var(--border-radius);
            margin-bottom: 0.75rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-100);
            transition: var(--transition);
        }

        .task-item:hover {
            box-shadow: var(--shadow-md);
        }

        .task-item input[type="checkbox"] {
            margin-right: 0.75rem;
            transform: scale(1.2);
            accent-color: var(--primary);
        }

        .task-details h4 {
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            color: var(--gray-800);
            font-weight: 600;
        }

        .task-details p {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        /* Acciones rápidas */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            border-radius: var(--border-radius-lg);
            background: var(--white);
            border: 2px solid var(--gray-100);
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .quick-action-btn:hover {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .quick-action-btn:hover i {
            color: white;
        }

        .quick-action-btn span {
            font-size: 0.875rem;
            font-weight: 600;
            transition: var(--transition);
        }

        /* Perfil */
        .profile-summary {
            text-align: center;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            font-size: 2rem;
            margin: 0 auto 1rem;
            box-shadow: var(--shadow-lg);
            position: relative;
        }

        .profile-avatar::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--gradient-primary);
            z-index: -1;
            opacity: 0.3;
            color: white !important;
            animation: pulse 2s infinite;
        }

        .profile-summary h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }

        .profile-summary p {
            color: var(--gray-600);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .profile-summary .profile-summary-icon i.fas {
            color: var(--primary);
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }

        .profile-summary .profile-summary-icon i.fa-phone {
            color: var(--primary);
        }

        .profile-summary .profile-summary-icon i.fa-id-badge {
            color: var(--primary);
        }

        /* Modales */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: 1rem;
        }

        /* Modal de agenda (más grande) */
        .modal-agenda {
            z-index: 1000;
        }

        .modal-agenda .modal-content {
            max-width: 1200px;
            width: 95%;
            max-height: 90vh;
        }

        /* Modales que se abren SOBRE la agenda */
        .modal-detalle {
            z-index: 1100;
        }

        .modal-finalizar {
            z-index: 1100;
        }

        .modal-observaciones {
            z-index: 1100;
        }

        .modal-historial {
            z-index: 1050;
        }

        .modal-historial .modal-content {
            max-width: 1200px;
            width: 95%;
        }

        .modal-bitacora {
            z-index: 1050;
        }

        .modal-bitacora .modal-content {
            max-width: 1200px;
            width: 95%;
        }

        .modal-configuracion {
            z-index: 1050;
        }

        .modal-content {
            background: white;
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-xl);
            position: relative;
            max-height: 85vh;
            overflow-y: auto;
            margin: auto;
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
            transition: var(--transition);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            color: var(--gray-600);
            background: var(--gray-100);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Estados vacíos */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }

        .empty-state p {
            font-size: 0.9rem;
        }

        /* Estilos para Modal de Agenda */
        .agenda-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary);
        }

        .agenda-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }

        .agenda-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: var(--border-radius);
        }

        .agenda-filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .agenda-filter-group label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
        }

        .agenda-filter-group select,
        .agenda-filter-group input {
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            transition: var(--transition);
            background: white;
        }

        .agenda-filter-group select:focus,
        .agenda-filter-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .agenda-citas-container {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .agenda-cita-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.5rem;
            align-items: center;
        }

        .agenda-cita-card:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-lg);
        }

        .agenda-cita-time {
            text-align: center;
            padding: 1rem;
            background: var(--gradient-primary);
            border-radius: var(--border-radius);
            color: white;
            min-width: 100px;
        }

        .agenda-cita-time-hour {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .agenda-cita-time-date {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .agenda-cita-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .agenda-cita-info p {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .agenda-cita-info i {
            color: var(--primary);
            width: 16px;
        }

        .agenda-cita-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 120px;
        }

        .agenda-loading {
            text-align: center;
            padding: 3rem;
            color: var(--gray-500);
        }

        .agenda-loading i {
            font-size: 3rem;
            color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .agenda-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
        }

        .agenda-pagination button {
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            background: white;
            color: var(--gray-700);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        .agenda-pagination button:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .agenda-pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .agenda-pagination .page-info {
            padding: 0 1rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .header {
                padding: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .welcome-section {
                min-width: auto;
            }

            .welcome-section h1 {
                font-size: 1.75rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .welcome-stats {
                grid-template-columns: repeat(3, 1fr);
            }

            .header-actions {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .card-body {
                padding: 1rem;
            }

            .appointment-card {
                padding: 1rem;
            }

            .appointment-actions {
                justify-content: center;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .agenda-cita-card {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .agenda-cita-actions {
                flex-direction: row;
                justify-content: center;
            }

            .agenda-filters {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .welcome-stats {
                grid-template-columns: 1fr;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }

            .modal-content {
                padding: 1.5rem;
                margin: 1rem;
            }
        }

        /* Animaciones adicionales */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.6s ease-out;
        }

        .appointment-card {
            animation: fadeIn 0.4s ease-out;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }

        /* Footer */
        .footer {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            margin-top: auto;
            border-top-left-radius: var(--border-radius-xl);
            border-top-right-radius: var(--border-radius-xl);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .footer-content {
            padding: 40px 30px;
            text-align: center;
            border-radius: var(--border-radius-xl);
        }

        .footer-brand {
            margin-bottom: 15px;
        }

        .footer-brand h3 {
            font-size: 28px;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 8px 0;
            text-shadow: none;
        }

        .footer-slogan {
            font-size: 14px;
            color: var(--gray-600);
            font-style: italic;
            margin-bottom: 25px;
            opacity: 0.8;
        }

        .footer-info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-800);
            font-size: 15px;
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateY(-2px);
        }

        .info-item i {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            color: white;
            border-radius: 50%;
            font-size: 10px;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .location-link {
            color: var(--gray-800);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .location-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }

        .location-link:hover::after {
            width: 100%;
        }

        .location-link:hover {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            margin: 20px 0;
            opacity: 0.3;
        }

        .footer-copyright {
            color: var(--gray-600);
            font-size: 13px;
            opacity: 0.8;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .social-icon:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .social-icon.facebook:hover {
            background: #1877f2;
            color: white;
            border-color: #1877f2;
        }

        .social-icon.whatsapp:hover {
            background: #25d366;
            color: white;
            border-color: #25d366;
        }

        .social-icon.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
            border-color: #bc1888;
        }

        .social-icon.twitter:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .schedule-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .schedule-main {
            font-weight: 500;
            color: var(--gray-800);
        }

        .schedule-closed {
            font-size: 13px;
            color: var(--gray-600);
            opacity: 0.8;
        }

        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 50%;
            animation: sparkle 2s infinite;
        }

        .sparkle:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .sparkle:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 0.5s;
        }

        .sparkle:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 1s;
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: 0;
                transform: scale(0);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Contact Button in Footer */
        .footer-contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gradient-primary);
            color: white;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            margin: 15px 0;
        }

        .footer-contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .footer-contact-btn i {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer-info {
                flex-direction: column;
                gap: 15px;
            }

            .footer-content {
                padding: 30px 20px;
            }

            .footer-brand h3 {
                font-size: 24px;
            }

            .social-icons {
                gap: 12px;
            }

            .social-icon {
                width: 36px;
                height: 36px;
            }
        }

        @media (max-width: 480px) {
            .footer-brand h3 {
                font-size: 20px;
            }

            .footer-slogan {
                font-size: 12px;
            }

            .info-item {
                font-size: 14px;
            }
        }

        @keyframes primaryPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
            }
        }

        @keyframes accentPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(6, 182, 212, 0);
            }
        }

        .pulse-primary {
            animation: primaryPulse 2s infinite;
        }

        .pulse-accent {
            animation: accentPulse 2s infinite;
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
                        ¡Bienvenido, {{ Auth::user()->nombre ?? 'Empleado' }}!
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
                    <button onclick="abrirModalAgenda()" class="btn btn-primary">
                        <i class="fas fa-calendar-day"></i> Ver Agenda
                    </button>
                    <button onclick="abrirModalConfiguracion()" class="btn btn-profile">
                        <i class="fas fa-cog"></i> Configurar Cuenta
                    </button>
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
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            Citas para Hoy
                        </h2>
                        <button onclick="abrirModalAgenda()" class="btn btn-sm btn-primary" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-calendar-alt"></i> Ver Agenda Completa
                        </button>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto; padding: 0;">
                        @if (isset($citas_hoy) && count($citas_hoy) > 0)
                            @foreach ($citas_hoy as $cita)
                                @php
                                    $estadoColors = [
                                        'confirmada' => ['bg' => 'linear-gradient(135deg, #3b82f6, #2563eb)', 'icon' => 'fa-check-circle'],
                                        'en_proceso' => ['bg' => 'linear-gradient(135deg, #f59e0b, #d97706)', 'icon' => 'fa-hourglass-half'],
                                        'pendiente' => ['bg' => 'linear-gradient(135deg, #6b7280, #4b5563)', 'icon' => 'fa-clock']
                                    ];
                                    $estado = $estadoColors[$cita->estado] ?? $estadoColors['pendiente'];
                                    $total = $cita->servicios->sum(function($s) { return $s->pivot->precio ?? $s->precio; });
                                @endphp
                                <div style="padding: 1rem; border-bottom: 1px solid var(--gray-200); display: grid; grid-template-columns: auto 1fr auto; gap: 1rem; align-items: start; transition: background 0.2s;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='white'">
                                    <!-- Hora y Estado -->
                                    <div style="display: flex; flex-direction: column; align-items: center; min-width: 80px;">
                                        <div style="background: {{ $estado['bg'] }}; width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                            <i class="fas {{ $estado['icon'] }}" style="color: white; font-size: 1.5rem;"></i>
                                        </div>
                                        <span style="font-size: 0.9rem; font-weight: 600; color: var(--gray-700);">
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}
                                        </span>
                                    </div>

                                    <!-- Información de la Cita -->
                                    <div style="min-width: 0;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.4rem;">
                                            <h4 style="margin: 0; font-size: 1rem; color: var(--gray-800); font-weight: 600;">
                                                <i class="fas fa-user" style="color: var(--primary); font-size: 0.85rem;"></i>
                                                {{ $cita->usuario?->nombre ?? 'Cliente no especificado' }}
                                            </h4>
                                            <span class="status-badge status-{{ $cita->estado }}" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                                {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                            </span>
                                        </div>

                                        <p style="margin: 0.25rem 0; font-size: 0.85rem; color: var(--gray-600);">
                                            <i class="fas fa-car" style="color: var(--primary);"></i>
                                            {{ $cita->vehiculo?->marca ?? 'N/A' }} {{ $cita->vehiculo?->modelo ?? '' }}
                                            @if($cita->vehiculo?->placa)
                                                <span style="background: var(--gray-200); padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 600; margin-left: 0.25rem;">{{ $cita->vehiculo->placa }}</span>
                                            @endif
                                        </p>

                                        <div style="margin-top: 0.5rem; display: flex; flex-wrap: wrap; gap: 0.3rem;">
                                            @foreach ($cita->servicios as $servicio)
                                                <span style="font-size: 0.75rem; padding: 0.2rem 0.5rem; background: var(--primary-light); color: var(--primary); border-radius: 4px; font-weight: 500;">
                                                    {{ $servicio->nombre }}
                                                </span>
                                            @endforeach
                                        </div>

                                        @if ($cita->observaciones_cliente)
                                            <p style="margin: 0.5rem 0 0 0; font-size: 0.8rem; color: var(--gray-500); font-style: italic;">
                                                <i class="fas fa-comment-dots" style="color: var(--warning);"></i> {{ Str::limit($cita->observaciones_cliente, 50) }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Acciones y Precio -->
                                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; min-width: 120px;">
                                        <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">
                                            ${{ number_format($total, 2) }}
                                        </div>

                                        <div style="display: flex; gap: 0.3rem; flex-wrap: wrap; justify-content: flex-end;">
                                            @if ($cita->estado == 'confirmada')
                                                <button onclick="cambiarEstadoCita({{ $cita->id }}, 'en_proceso')" class="btn btn-sm btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: var(--primary); color: white; border: none;">
                                                    <i class="fas fa-play"></i> Iniciar
                                                </button>
                                            @elseif ($cita->estado == 'en_proceso')
                                                <button onclick="mostrarModalFinalizar({{ $cita->id }})" class="btn btn-sm btn-success" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: var(--success); color: white; border: none;">
                                                    <i class="fas fa-check"></i> Finalizar
                                                </button>
                                                <button onclick="mostrarModalObservaciones({{ $cita->id }})" class="btn btn-sm" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: transparent; color: var(--primary); border: 2px solid var(--primary);">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            <button onclick="verDetalleCita({{ $cita->id }})" class="btn btn-sm" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: transparent; color: var(--primary); border: 2px solid var(--primary);">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 3rem 1rem;">
                                <i class="fas fa-calendar-times" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                                <h3 style="color: var(--gray-600); margin: 0 0 0.5rem 0;">No hay citas programadas para hoy</h3>
                                <p style="color: var(--gray-500); margin: 0;">Revisa la agenda para ver futuras citas</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historial Reciente -->
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-history"></i>
                            </div>
                            Historial Reciente
                        </h2>
                        <button onclick="abrirModalHistorial()" class="btn btn-sm btn-outline" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-external-link-alt"></i> Ver Todo
                        </button>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @if (isset($historial_reciente) && count($historial_reciente) > 0)
                            @foreach ($historial_reciente as $cita)
                                @php
                                    $total = $cita->servicios->sum(function($s) { return $s->pivot->precio ?? $s->precio; });
                                    $metodoIcons = [
                                        'efectivo' => ['icon' => 'fa-money-bill-wave', 'color' => '#10b981'],
                                        'transferencia' => ['icon' => 'fa-exchange-alt', 'color' => '#3b82f6'],
                                        'pasarela' => ['icon' => 'fa-credit-card', 'color' => '#8b5cf6']
                                    ];
                                    $metodoData = $metodoIcons[$cita->pago?->metodo ?? 'efectivo'] ?? $metodoIcons['efectivo'];
                                @endphp
                                <div style="padding: 1rem; border-bottom: 1px solid var(--gray-200); display: grid; grid-template-columns: auto 1fr auto; gap: 1rem; align-items: center; transition: background 0.2s;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='white'">
                                    <!-- Icono de Estado -->
                                    <div style="background: linear-gradient(135deg, #10b981, #059669); width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
                                        <i class="fas fa-check-circle" style="color: white; font-size: 1.3rem;"></i>
                                    </div>

                                    <!-- Información -->
                                    <div style="min-width: 0;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem;">
                                            <h4 style="margin: 0; font-size: 0.95rem; color: var(--gray-800); font-weight: 600;">
                                                <i class="fas fa-user" style="color: var(--primary); font-size: 0.8rem;"></i>
                                                {{ $cita->usuario?->nombre ?? 'Cliente no especificado' }}
                                            </h4>
                                            <span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; background: var(--success-light); color: var(--success); border-radius: 4px; font-weight: 600;">
                                                FINALIZADO
                                            </span>
                                        </div>

                                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.3rem;">
                                            <p style="margin: 0; font-size: 0.8rem; color: var(--gray-600);">
                                                <i class="fas fa-calendar-check" style="color: var(--success);"></i>
                                                {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d M Y') }}
                                            </p>
                                            <p style="margin: 0; font-size: 0.8rem; color: var(--gray-600);">
                                                <i class="fas fa-car" style="color: var(--primary);"></i>
                                                {{ $cita->vehiculo?->marca ?? 'N/A' }} {{ $cita->vehiculo?->modelo ?? '' }}
                                                @if($cita->vehiculo?->placa)
                                                    <span style="background: var(--gray-200); padding: 0.1rem 0.3rem; border-radius: 3px; font-weight: 600; margin-left: 0.2rem; font-size: 0.75rem;">{{ $cita->vehiculo->placa }}</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                            @foreach($cita->servicios as $servicio)
                                                <span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; background: var(--primary-light); color: var(--primary); border-radius: 4px; font-weight: 500;">
                                                    {{ $servicio->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Precio y Método de Pago -->
                                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.4rem; min-width: 100px;">
                                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--success);">
                                            ${{ number_format($total, 2) }}
                                        </div>
                                        @if($cita->pago)
                                            <div style="display: flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; padding: 0.25rem 0.5rem; background: {{ $metodoData['color'] }}15; color: {{ $metodoData['color'] }}; border-radius: 6px; font-weight: 600; border: 1px solid {{ $metodoData['color'] }}40;">
                                                <i class="fas {{ $metodoData['icon'] }}" style="font-size: 0.85rem;"></i>
                                                <span style="text-transform: capitalize;">{{ $cita->pago->metodo }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 3rem 1rem;">
                                <i class="fas fa-history" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                                <h3 style="color: var(--gray-600); margin: 0 0 0.5rem 0;">No hay historial reciente</h3>
                                <p style="color: var(--gray-500); margin: 0;">Los servicios que completes aparecerán aquí</p>
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
                                    <input type="checkbox" id="task-{{ $tarea->id }}"
                                        onchange="marcarTareaCompleta({{ $tarea->id }}, this)"
                                        style="margin-right: 10px;">
                                    <div class="task-details">
                                        <h4>{{ $tarea->titulo }}</h4>
                                        <p>{{ $tarea->descripcion }}</p>
                                        <small>Asignada por:
                                            {{ $tarea->asignador?->nombre ?? 'Administración' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-check-circle"
                                    style="font-size: 2rem; color: #166088; margin-bottom: 10px;"></i>
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
                            <button class="quick-action-btn" onclick="abrirModalHistorial()">
                                <i class="fa-solid fa-history" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Ver Historial</span>
                            </button>
                            <button class="quick-action-btn" onclick="abrirModalBitacora()">
                                <i class="fa-solid fa-address-book"
                                    style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Ver Bitácora</span>
                            </button>
                            <button class="quick-action-btn" onclick="abrirModalAgenda()">
                                <i class="fa-solid fa-calendar-alt" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Ver Agenda</span>
                            </button>
                            <button class="quick-action-btn" onclick="abrirModalConfiguracion()">
                                <i class="fa-solid fa-cog" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Configuración</span>
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
                            <div class="profile-summary-icon">
                                <h3>{{ Auth::user()->nombre ?? 'Empleado' }}</h3>
                                <p><i class="fas fa-envelope" style="color: var(--primary);"></i>
                                    {{ Auth::user()->email ?? 'No especificado' }}</p>
                                <p><i class="fas fa-phone" style="color: var(--primary);"></i>
                                    {{ Auth::user()->telefono ?? 'No especificado' }}</p>
                                <p><i class="fas fa-id-badge" style="color: var(--primary);"></i> Rol: Empleado</p>
                                <p><i class="fas fa-calendar" style="color: var(--primary);"></i> Miembro desde
                                    {{ Auth::user()->created_at->format('M Y') }}</p>
                            </div>

                            <button onclick="openEditModal()" class="btn btn-outline">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Cierra dashboard-grid -->
    </div> <!-- Cierra dashboard-container -->

    <!-- Footer -->
    <footer class="footer">
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>

        <div class="footer-content">
            <div class="footer-brand">
                <h3><i class="fas fa-car-wash"></i> AutoGest Carwash Berrios</h3>
                <p class="footer-slogan">✨ "Donde tu auto brilla como nuevo" ✨</p>
            </div>

            <div class="footer-info">
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <span>75855197</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <a href="https://maps.app.goo.gl/PhHLaky3ZPrhtdb88" target="_blank" class="location-link">
                        Ver ubicación en mapa
                    </a>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Lun - Sáb: 7:00 AM - 6:00 PM | Dom: Cerrado</span>
                </div>
            </div>

            <div class="social-icons">
                <a href="#" class="social-icon facebook" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://wa.me/50375855197" class="social-icon whatsapp" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="social-icon instagram" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <div class="footer-divider"></div>

            <p class="footer-copyright">
                &copy; 2025 AutoGest Carwash Berrios. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <!-- Modales -->
    <div id="finalizarCitaModal" class="modal modal-finalizar">
        <div class="modal-content">
            <span class="close-modal" onclick="closeFinalizarModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i> Finalizar Servicio
            </h2>

            <!-- Información de la cita -->
            <div id="infoFinalizarCita" style="background: var(--gray-50); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1rem;">
                <p style="margin: 0.25rem 0;"><strong>Cliente:</strong> <span id="finalizar_cliente">-</span></p>
                <p style="margin: 0.25rem 0;"><strong>Vehículo:</strong> <span id="finalizar_vehiculo">-</span></p>
                <p style="margin: 0.25rem 0; font-size: 1.25rem; color: var(--primary);"><strong>Total a cobrar:</strong> <span id="finalizar_total">$0.00</span></p>
            </div>

            <form id="finalizarCitaForm">
                @csrf
                <input type="hidden" id="cita_id_finalizar" name="cita_id">
                <input type="hidden" id="total_cita" value="0">

                <div class="form-group">
                    <label for="metodo_pago"><span style="color: red;">*</span> Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" class="form-control" required>
                        <option value="">-- Seleccione método de pago --</option>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta</option>
                        <option value="transferencia">🏦 Transferencia</option>
                    </select>
                </div>

                <div id="efectivoFields" style="display: none;">
                    <div class="form-group">
                        <label for="monto_recibido"><span style="color: red;">*</span> Monto Recibido ($):</label>
                        <input type="number" step="0.01" id="monto_recibido" name="monto_recibido"
                            class="form-control" placeholder="0.00">
                        <small id="cambio_calculado" style="display: none; color: var(--success); font-weight: 600; margin-top: 0.5rem;">
                            Cambio: $<span id="cambio_monto">0.00</span>
                        </small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="observaciones_finalizar">Observaciones (opcional):</label>
                    <textarea id="observaciones_finalizar" name="observaciones" rows="3" class="form-control"
                        placeholder="Agregar observaciones sobre el servicio finalizado..."></textarea>
                </div>

                <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-check"></i> Confirmar Finalización y Registrar Pago
                </button>
            </form>
        </div>
    </div>

    <div id="observacionesModal" class="modal modal-observaciones">
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

    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">
                <i class="fas fa-user-edit"></i> Editar Perfil
            </h2>
            <form id="profileFormEmpleado">
                @csrf
                <div class="form-group">
                    <label for="modalNombre">Nombre:</label>
                    <input type="text" id="modalNombre" name="nombre" class="form-control"
                        value="{{ Auth::user()->nombre ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="modalTelefono">Teléfono:</label>
                    <input type="tel" id="modalTelefono" name="telefono" class="form-control"
                        value="{{ Auth::user()->telefono ?? '' }}" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <div id="detalleCitaModal" class="modal modal-detalle">
        <div class="modal-content">
            <span class="close-modal" onclick="closeDetalleModal()">&times;</span>
            <div id="detalleCitaContent"></div>
        </div>
    </div>

    <!-- Modal de Agenda Completa -->
    <div id="agendaModal" class="modal modal-agenda">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModalAgenda()">&times;</span>

            <div class="agenda-header">
                <h2>
                    <i class="fas fa-calendar-alt"></i>
                    Agenda Completa
                </h2>
            </div>

            <!-- Filtros -->
            <div class="agenda-filters">
                <div class="agenda-filter-group">
                    <label for="agenda-estado">Estado</label>
                    <select id="agenda-estado" onchange="aplicarFiltrosAgenda()">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="finalizada">Finalizada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="agenda-filter-group">
                    <label for="agenda-fecha-desde">Desde</label>
                    <input type="date" id="agenda-fecha-desde" onchange="aplicarFiltrosAgenda()">
                </div>

                <div class="agenda-filter-group">
                    <label for="agenda-fecha-hasta">Hasta</label>
                    <input type="date" id="agenda-fecha-hasta" onchange="aplicarFiltrosAgenda()">
                </div>

                <div class="agenda-filter-group">
                    <label>&nbsp;</label>
                    <button onclick="limpiarFiltrosAgenda()" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-redo"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Contenedor de citas -->
            <div id="agendaCitasContainer" class="agenda-citas-container">
                <div class="agenda-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Cargando citas...</p>
                </div>
            </div>

            <!-- Paginación -->
            <div id="agendaPaginacion" class="agenda-pagination" style="display: none;">
                <button onclick="cambiarPaginaAgenda('prev')" id="btnPrevAgenda">
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>
                <span class="page-info">
                    Página <span id="paginaActualAgenda">1</span> de <span id="totalPaginasAgenda">1</span>
                </span>
                <button onclick="cambiarPaginaAgenda('next')" id="btnNextAgenda">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Historial -->
    <div id="historialModal" class="modal modal-historial">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModalHistorial()">&times;</span>

            <div class="agenda-header">
                <h2>
                    <i class="fas fa-history"></i>
                    Historial de Servicios
                </h2>
            </div>

            <!-- Estadísticas -->
            <div id="estadisticasHistorial" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 1.5rem; border-radius: var(--border-radius); color: white;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Servicios Completados</div>
                    <div style="font-size: 2rem; font-weight: 700;"><span id="stat_total_servicios">0</span></div>
                </div>
                <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 1.5rem; border-radius: var(--border-radius); color: white;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Ingresos Generados</div>
                    <div style="font-size: 2rem; font-weight: 700;">$<span id="stat_ingresos">0.00</span></div>
                </div>
                <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); padding: 1.5rem; border-radius: var(--border-radius); color: white;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Hoy</div>
                    <div style="font-size: 2rem; font-weight: 700;"><span id="stat_servicios_hoy">0</span></div>
                </div>
                <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 1.5rem; border-radius: var(--border-radius); color: white;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Este Mes</div>
                    <div style="font-size: 2rem; font-weight: 700;"><span id="stat_servicios_mes">0</span></div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="agenda-filters">
                <div class="agenda-filter-group">
                    <label for="historial-fecha-desde">Desde</label>
                    <input type="date" id="historial-fecha-desde" onchange="aplicarFiltrosHistorial()">
                </div>
                <div class="agenda-filter-group">
                    <label for="historial-fecha-hasta">Hasta</label>
                    <input type="date" id="historial-fecha-hasta" onchange="aplicarFiltrosHistorial()">
                </div>
                <div class="agenda-filter-group">
                    <label>&nbsp;</label>
                    <button onclick="limpiarFiltrosHistorial()" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-redo"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Contenedor de historial -->
            <div id="historialCitasContainer" class="agenda-citas-container">
                <div class="agenda-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Cargando historial...</p>
                </div>
            </div>

            <!-- Paginación -->
            <div id="historialPaginacion" class="agenda-pagination" style="display: none;">
                <button onclick="cambiarPaginaHistorial('prev')" id="btnPrevHistorial">
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>
                <span class="page-info">
                    Página <span id="paginaActualHistorial">1</span> de <span id="totalPaginasHistorial">1</span>
                </span>
                <button onclick="cambiarPaginaHistorial('next')" id="btnNextHistorial">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Bitácora -->
    <div id="bitacoraModal" class="modal modal-bitacora">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModalBitacora()">&times;</span>

            <div class="agenda-header">
                <h2>
                    <i class="fas fa-address-book"></i>
                    Bitácora de Acciones
                </h2>
            </div>

            <!-- Filtros -->
            <div class="agenda-filters">
                <div class="agenda-filter-group">
                    <label for="bitacora-accion">Tipo de Acción</label>
                    <select id="bitacora-accion" onchange="aplicarFiltrosBitacora()">
                        <option value="">Todas las acciones</option>
                        <option value="cambiar_estado_cita">Cambiar Estado Cita</option>
                        <option value="agregar_observaciones_cita">Agregar Observaciones</option>
                        <option value="finalizar_cita">Finalizar Cita</option>
                        <option value="actualizar_perfil">Actualizar Perfil</option>
                    </select>
                </div>
                <div class="agenda-filter-group">
                    <label for="bitacora-fecha-desde">Desde</label>
                    <input type="date" id="bitacora-fecha-desde" onchange="aplicarFiltrosBitacora()">
                </div>
                <div class="agenda-filter-group">
                    <label for="bitacora-fecha-hasta">Hasta</label>
                    <input type="date" id="bitacora-fecha-hasta" onchange="aplicarFiltrosBitacora()">
                </div>
                <div class="agenda-filter-group">
                    <label>&nbsp;</label>
                    <button onclick="limpiarFiltrosBitacora()" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-redo"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Contenedor de bitácora -->
            <div id="bitacoraContainer" class="agenda-citas-container">
                <div class="agenda-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Cargando bitácora...</p>
                </div>
            </div>

            <!-- Paginación -->
            <div id="bitacoraPaginacion" class="agenda-pagination" style="display: none;">
                <button onclick="cambiarPaginaBitacora('prev')" id="btnPrevBitacora">
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>
                <span class="page-info">
                    Página <span id="paginaActualBitacora">1</span> de <span id="totalPaginasBitacora">1</span>
                </span>
                <button onclick="cambiarPaginaBitacora('next')" id="btnNextBitacora">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Configuración -->
    <div id="configuracionModal" class="modal modal-configuracion">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="cerrarModalConfiguracion()">&times;</span>
            <h2 style="margin-bottom: 1.5rem;">
                <i class="fas fa-cog"></i> Configuración de Cuenta
            </h2>

            <!-- Información Personal -->
            <form id="configuracionForm">
                @csrf
                <h3 style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); padding-bottom: 0.5rem;">
                    <i class="fas fa-user"></i> Información Personal
                </h3>

                <div class="form-group">
                    <label for="config_nombre"><span style="color: red;">*</span> Nombre Completo:</label>
                    <input type="text" id="config_nombre" name="nombre" class="form-control"
                           value="{{ Auth::user()->nombre }}" required>
                </div>

                <div class="form-group">
                    <label for="config_telefono"><span style="color: red;">*</span> Teléfono:</label>
                    <input type="tel" id="config_telefono" name="telefono" class="form-control"
                           value="{{ Auth::user()->telefono }}" required>
                </div>

                <div class="form-group">
                    <label for="config_email">Email:</label>
                    <input type="email" id="config_email" class="form-control"
                           value="{{ Auth::user()->email }}" disabled>
                    <small style="color: var(--gray-500);">El email no se puede modificar</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    <i class="fas fa-save"></i> Guardar Información Personal
                </button>
            </form>

            <!-- Cambiar Contraseña -->
            <form id="cambiarPasswordForm" style="margin-top: 2rem;">
                @csrf
                <h3 style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); padding-bottom: 0.5rem;">
                    <i class="fas fa-lock"></i> Cambiar Contraseña
                </h3>

                <div class="form-group">
                    <label for="config_password_actual"><span style="color: red;">*</span> Contraseña Actual:</label>
                    <input type="password" id="config_password_actual" name="password_actual" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="config_password_nueva"><span style="color: red;">*</span> Nueva Contraseña:</label>
                    <input type="password" id="config_password_nueva" name="password_nueva" class="form-control" required minlength="8">
                    <small style="color: var(--gray-500);">Mínimo 8 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="config_password_confirmacion"><span style="color: red;">*</span> Confirmar Nueva Contraseña:</label>
                    <input type="password" id="config_password_confirmacion" name="password_confirmacion" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
            </form>
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

        // Funciones del modal de edición de perfil
        function openEditModal() {
            const modal = document.getElementById('editProfileModal');
            if (modal) {
                modal.style.display = 'flex';
                document.getElementById('modalNombre').focus();
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editProfileModal');
            if (modal) modal.style.display = 'none';
        }

        // Manejo del formulario AJAX con actualización de UI
        document.getElementById('profileFormEmpleado')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const nombre = document.getElementById('modalNombre').value.trim();
            const telefono = document.getElementById('modalTelefono').value.trim();

            try {
                const response = await fetch('{{ route('perfil.update-ajax') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        telefono: telefono
                    })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Error en la respuesta');

                // Actualizar UI
                document.querySelector('.welcome-section h1').innerHTML = `
            <div class="welcome-icon">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            ¡Bienvenido, ${nombre}!
        `;

                document.querySelector('.profile-summary h3').textContent = nombre;
                document.querySelector('.profile-summary p:nth-of-type(2)').innerHTML =
                    `<i class="fas fa-phone"></i> ${telefono}`;

                Toast.fire({
                    icon: 'success',
                    title: 'Perfil actualizado'
                });
                closeEditModal();

            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
            }
        });

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeEditModal();
            }
        });

        // Funciones para modales
        function mostrarModalFinalizar(citaId) {
            // Cargar información de la cita
            fetch(`/empleado/citas/${citaId}/detalles`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cita = data.cita;
                        document.getElementById('cita_id_finalizar').value = citaId;
                        document.getElementById('finalizar_cliente').textContent = cita.usuario.nombre;
                        document.getElementById('finalizar_vehiculo').textContent = `${cita.vehiculo.marca} ${cita.vehiculo.modelo} - ${cita.vehiculo.placa}`;
                        document.getElementById('finalizar_total').textContent = `$${cita.total.toFixed(2)}`;
                        document.getElementById('total_cita').value = cita.total;
                        document.getElementById('finalizarCitaModal').style.display = 'flex';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al cargar información de la cita'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar información'
                    });
                });
        }

        function closeFinalizarModal() {
            document.getElementById('finalizarCitaModal').style.display = 'none';
            document.getElementById('finalizarCitaForm').reset();
            document.getElementById('efectivoFields').style.display = 'none';
            document.getElementById('cambio_calculado').style.display = 'none';
        }

        function mostrarModalObservaciones(citaId) {
            document.getElementById('cita_id_observaciones').value = citaId;
            document.getElementById('observacionesModal').style.display = 'flex';
        }

        function closeObservacionesModal() {
            document.getElementById('observacionesModal').style.display = 'none';
        }

        function verDetalleCita(citaId) {

            // Obtener detalles de la cita mediante AJAX
            fetch(`/empleado/citas/${citaId}/detalles`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cita = data.cita;
                        const detalleContent = `
                            <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fas fa-calendar-check"></i> Detalle de Cita #${cita.id}
                            </h2>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-user"></i> Cliente
                                    </h3>
                                    <p><strong>Nombre:</strong> ${cita.usuario.nombre}</p>
                                    <p><strong>Email:</strong> ${cita.usuario.email}</p>
                                    <p><strong>Teléfono:</strong> ${cita.usuario.telefono || 'Sin teléfono'}</p>
                                </div

                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-car"></i> Vehículo
                                    </h3>
                                    <p><strong>Marca:</strong> ${cita.vehiculo.marca}</p>
                                    <p><strong>Modelo:</strong> ${cita.vehiculo.modelo}</p>
                                    <p><strong>Placa:</strong> ${cita.vehiculo.placa}</p>
                                    <p><strong>Color:</strong> ${cita.vehiculo.color || 'No especificado'}</p>
                                </div>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-calendar"></i> Información de la Cita
                                </h3>
                                <p><strong>Fecha y hora:</strong> ${cita.fecha_hora_formatted}</p>
                                <p><strong>Estado:</strong> <span class="appointment-status status-${cita.estado}">${cita.estado_formatted}</span></p>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-concierge-bell"></i> Servicios
                                </h3>
                                ${cita.servicios.map(s => `
                                    <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
                                        <i class="fas fa-check" style="color: var(--success);"></i>
                                        ${s.nombre} - $${parseFloat(s.pivot.precio).toFixed(2)}
                                    </div>
                                `).join('')}
                                <div style="text-align: right; margin-top: 1rem; font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                    Total: $${cita.total.toFixed(2)}
                                </div>
                            </div>

                            ${cita.observaciones ? `
                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-comment"></i> Observaciones
                                    </h3>
                                    <p style="padding: 1rem; background: var(--gray-100); border-radius: var(--border-radius); white-space: pre-wrap;">
                                        ${cita.observaciones}
                                    </p>
                                </div>
                            ` : ''}
                        `;

                        document.getElementById('detalleCitaContent').innerHTML = detalleContent;
                        document.getElementById('detalleCitaModal').style.display = 'flex';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al cargar detalles'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar detalles'
                    });
                });
        }

        function closeDetalleModal() {
            document.getElementById('detalleCitaModal').style.display = 'none';
        }

        // Manejar cambio de método de pago
        // Mostrar/ocultar campo de efectivo según método de pago
        document.getElementById('metodo_pago').addEventListener('change', function() {
            const efectivoFields = document.getElementById('efectivoFields');
            const montoRecibidoInput = document.getElementById('monto_recibido');

            if (this.value === 'efectivo') {
                efectivoFields.style.display = 'block';
                montoRecibidoInput.required = true;
            } else {
                efectivoFields.style.display = 'none';
                montoRecibidoInput.required = false;
                montoRecibidoInput.value = '';
                document.getElementById('cambio_calculado').style.display = 'none';
            }
        });

        // Calcular cambio en tiempo real
        document.getElementById('monto_recibido').addEventListener('input', function() {
            const totalCita = parseFloat(document.getElementById('total_cita').value) || 0;
            const montoRecibido = parseFloat(this.value) || 0;
            const cambio = montoRecibido - totalCita;

            if (montoRecibido >= totalCita && montoRecibido > 0) {
                document.getElementById('cambio_monto').textContent = cambio.toFixed(2);
                document.getElementById('cambio_calculado').style.display = 'block';
            } else {
                document.getElementById('cambio_calculado').style.display = 'none';
            }
        });

        // Cambiar estado de cita
        function cambiarEstadoCita(citaId, estado) {
            fetch(`/empleado/citas/${citaId}/estado`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        estado: estado
                    })
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

            const citaId = document.getElementById('cita_id_finalizar').value;
            const formData = new FormData(this);

            // Renombrar campo para que coincida con el backend
            formData.set('observaciones_finalizacion', formData.get('observaciones'));
            formData.delete('observaciones');
            formData.delete('cita_id');

            fetch(`/empleado/citas/${citaId}/finalizar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar éxito con información del pago
                        let mensaje = data.message;
                        if (data.pago && data.pago.cambio > 0) {
                            mensaje += `<br><strong>Cambio: $${data.pago.cambio.toFixed(2)}</strong>`;
                        }
                        if (data.pago && data.pago.comprobante) {
                            mensaje += `<br><small>Comprobante: ${data.pago.comprobante}</small>`;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: '¡Cita Finalizada!',
                            html: mensaje,
                            confirmButtonColor: '#10b981',
                            timer: 5000,
                            timerProgressBar: true
                        });

                        closeFinalizarModal();
                        setTimeout(() => location.reload(), 2000);
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

        // Función para cerrar modal de finalizar
        function closeFinalizarModal() {
            document.getElementById('finalizarCitaModal').style.display = 'none';
            document.getElementById('finalizarCitaForm').reset();
        }

        // Formulario para guardar observaciones
        document.getElementById('observacionesForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const citaId = document.getElementById('cita_id_observaciones').value;

            fetch(`/empleado/citas/${citaId}/observaciones`, {
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
                    body: JSON.stringify({
                        completada: checkbox.checked
                    })
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
                // Solo cerrar si no es el modal de agenda o si se cierra el de agenda, no cerrar el de detalles
                if (event.target.id === 'agendaModal') {
                    cerrarModalAgenda();
                } else if (event.target.id === 'detalleCitaModal') {
                    closeDetalleModal();
                } else if (event.target.id === 'finalizarCitaModal') {
                    closeFinalizarModal();
                } else if (event.target.id === 'observacionesModal') {
                    closeObservacionesModal();
                }
            }
        });

        // ==========================================
        // FUNCIONES DEL MODAL DE AGENDA COMPLETA
        // ==========================================

        let agendaState = {
            paginaActual: 1,
            totalPaginas: 1,
            filtros: {
                estado: '',
                fecha_desde: '',
                fecha_hasta: ''
            }
        };

        // Abrir modal de agenda
        function abrirModalAgenda() {
            document.getElementById('agendaModal').style.display = 'flex';
            cargarCitasAgenda();
        }

        // Cerrar modal de agenda
        function cerrarModalAgenda() {
            document.getElementById('agendaModal').style.display = 'none';
            // Resetear filtros y paginación
            agendaState.paginaActual = 1;
            agendaState.filtros = { estado: '', fecha_desde: '', fecha_hasta: '' };
            document.getElementById('agenda-estado').value = '';
            document.getElementById('agenda-fecha-desde').value = '';
            document.getElementById('agenda-fecha-hasta').value = '';
        }

        // Cargar citas con AJAX
        function cargarCitasAgenda() {
            const container = document.getElementById('agendaCitasContainer');
            container.innerHTML = `
                <div class="agenda-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Cargando citas...</p>
                </div>
            `;

            // Construir URL con parámetros
            const params = new URLSearchParams({
                page: agendaState.paginaActual,
                ...agendaState.filtros
            });

            // Filtrar parámetros vacíos
            for (let [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }

            fetch(`/empleado/citas?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    renderizarCitasAgenda(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Error al cargar las citas</h3>
                            <p>Por favor, intenta nuevamente</p>
                        </div>
                    `;
                });
        }

        // Renderizar citas en el modal
        function renderizarCitasAgenda(data) {
            const container = document.getElementById('agendaCitasContainer');
            const paginacion = document.getElementById('agendaPaginacion');

            if (!data.data || data.data.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>No hay citas</h3>
                        <p>No se encontraron citas con los filtros seleccionados</p>
                    </div>
                `;
                paginacion.style.display = 'none';
                return;
            }

            // Actualizar estado de paginación
            agendaState.paginaActual = data.current_page;
            agendaState.totalPaginas = data.last_page;

            // Renderizar citas
            let html = '';
            data.data.forEach(cita => {
                const fechaHora = new Date(cita.fecha_hora);
                const hora = fechaHora.toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const fecha = fechaHora.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                html += `
                    <div class="agenda-cita-card">
                        <div class="agenda-cita-time">
                            <span class="agenda-cita-time-hour">${hora}</span>
                            <span class="agenda-cita-time-date">${fecha}</span>
                        </div>

                        <div class="agenda-cita-info">
                            <h3>
                                <i class="fas fa-user"></i>
                                ${cita.usuario.nombre}
                                <span class="status-badge status-${cita.estado}">
                                    ${cita.estado.replace('_', ' ').replace(/^\w/, c => c.toUpperCase())}
                                </span>
                            </h3>
                            <p>
                                <i class="fas fa-car"></i>
                                ${cita.vehiculo.marca} ${cita.vehiculo.modelo} - ${cita.vehiculo.placa}
                            </p>
                            <p>
                                <i class="fas fa-phone"></i>
                                ${cita.usuario.telefono || 'Sin teléfono'}
                            </p>
                            <div style="margin-top: 0.5rem;">
                                ${cita.servicios.map(s => `
                                    <span class="service-tag">${s.nombre} ($${parseFloat(s.pivot.precio).toFixed(2)})</span>
                                `).join('')}
                            </div>
                            ${cita.observaciones ? `
                                <p style="margin-top: 0.5rem;">
                                    <i class="fas fa-comment"></i>
                                    <strong>Obs:</strong> ${cita.observaciones.substring(0, 50)}${cita.observaciones.length > 50 ? '...' : ''}
                                </p>
                            ` : ''}
                        </div>

                        <div class="agenda-cita-actions">
                            ${cita.estado === 'confirmada' ? `
                                <button onclick="cambiarEstadoDesdeAgenda(${cita.id}, 'en_proceso')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-play"></i> Iniciar
                                </button>
                            ` : ''}
                            ${cita.estado === 'en_proceso' ? `
                                <button onclick="mostrarModalFinalizarDesdeAgenda(${cita.id})" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Finalizar
                                </button>
                                <button onclick="mostrarModalObservacionesDesdeAgenda(${cita.id})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Observaciones
                                </button>
                            ` : ''}
                            <button onclick="verDetalleDesdeAgenda(${cita.id})" class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Mostrar/actualizar paginación
            if (data.last_page > 1) {
                paginacion.style.display = 'flex';
                document.getElementById('paginaActualAgenda').textContent = data.current_page;
                document.getElementById('totalPaginasAgenda').textContent = data.last_page;
                document.getElementById('btnPrevAgenda').disabled = data.current_page === 1;
                document.getElementById('btnNextAgenda').disabled = data.current_page === data.last_page;
            } else {
                paginacion.style.display = 'none';
            }
        }

        // Aplicar filtros
        function aplicarFiltrosAgenda() {
            agendaState.filtros.estado = document.getElementById('agenda-estado').value;
            agendaState.filtros.fecha_desde = document.getElementById('agenda-fecha-desde').value;
            agendaState.filtros.fecha_hasta = document.getElementById('agenda-fecha-hasta').value;
            agendaState.paginaActual = 1; // Resetear a primera página
            cargarCitasAgenda();
        }

        // Limpiar filtros
        function limpiarFiltrosAgenda() {
            document.getElementById('agenda-estado').value = '';
            document.getElementById('agenda-fecha-desde').value = '';
            document.getElementById('agenda-fecha-hasta').value = '';
            agendaState.filtros = { estado: '', fecha_desde: '', fecha_hasta: '' };
            agendaState.paginaActual = 1;
            cargarCitasAgenda();
        }

        // Cambiar página
        function cambiarPaginaAgenda(direccion) {
            if (direccion === 'prev' && agendaState.paginaActual > 1) {
                agendaState.paginaActual--;
            } else if (direccion === 'next' && agendaState.paginaActual < agendaState.totalPaginas) {
                agendaState.paginaActual++;
            }
            cargarCitasAgenda();
        }

        // Cambiar estado desde agenda
        function cambiarEstadoDesdeAgenda(citaId, nuevoEstado) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas cambiar el estado de la cita a "${nuevoEstado.replace('_', ' ')}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/empleado/citas/${citaId}/estado`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ estado: nuevoEstado })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                });
                                cargarCitasAgenda(); // Recargar la agenda
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
            });
        }

        // Mostrar modal de finalizar desde agenda
        function mostrarModalFinalizarDesdeAgenda(citaId) {
            // Usar la misma función que desde el dashboard
            mostrarModalFinalizar(citaId);
        }

        // Mostrar modal de observaciones desde agenda
        function mostrarModalObservacionesDesdeAgenda(citaId) {
            document.getElementById('cita_id_observaciones').value = citaId;
            document.getElementById('observacionesModal').style.display = 'flex';
        }

        // Ver detalle desde agenda (se abre sobre el modal de agenda)
        function verDetalleDesdeAgenda(citaId) {
            fetch(`/empleado/citas/${citaId}/detalles`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cita = data.cita;
                        const detalleContent = `
                            <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fas fa-calendar-check"></i> Detalle de Cita #${cita.id}
                            </h2>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-user"></i> Cliente
                                    </h3>
                                    <p><strong>Nombre:</strong> ${cita.usuario.nombre}</p>
                                    <p><strong>Email:</strong> ${cita.usuario.email}</p>
                                    <p><strong>Teléfono:</strong> ${cita.usuario.telefono || 'Sin teléfono'}</p>
                                </div>

                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-car"></i> Vehículo
                                    </h3>
                                    <p><strong>Marca:</strong> ${cita.vehiculo.marca}</p>
                                    <p><strong>Modelo:</strong> ${cita.vehiculo.modelo}</p>
                                    <p><strong>Placa:</strong> ${cita.vehiculo.placa}</p>
                                    <p><strong>Color:</strong> ${cita.vehiculo.color || 'No especificado'}</p>
                                </div>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-calendar"></i> Información de la Cita
                                </h3>
                                <p><strong>Fecha y hora:</strong> ${cita.fecha_hora_formatted}</p>
                                <p><strong>Estado:</strong> <span class="appointment-status status-${cita.estado}">${cita.estado_formatted}</span></p>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-concierge-bell"></i> Servicios
                                </h3>
                                ${cita.servicios.map(s => `
                                    <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
                                        <i class="fas fa-check" style="color: var(--success);"></i>
                                        ${s.nombre} - $${parseFloat(s.pivot.precio).toFixed(2)}
                                    </div>
                                `).join('')}
                                <div style="text-align: right; margin-top: 1rem; font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                    Total: $${cita.total.toFixed(2)}
                                </div>
                            </div>

                            ${cita.observaciones ? `
                                <div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--gray-800);">
                                        <i class="fas fa-comment"></i> Observaciones
                                    </h3>
                                    <p style="padding: 1rem; background: var(--gray-100); border-radius: var(--border-radius); white-space: pre-wrap;">
                                        ${cita.observaciones}
                                    </p>
                                </div>
                            ` : ''}
                        `;

                        document.getElementById('detalleCitaContent').innerHTML = detalleContent;
                        document.getElementById('detalleCitaModal').style.display = 'flex';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al cargar detalles'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar detalles'
                    });
                });
        }

        // ==========================================
        // FUNCIONES DEL MODAL DE HISTORIAL
        // ==========================================

        let historialState = {
            paginaActual: 1,
            totalPaginas: 1,
            filtros: {
                fecha_desde: '',
                fecha_hasta: ''
            }
        };

        function abrirModalHistorial() {
            document.getElementById('historialModal').style.display = 'flex';
            cargarHistorial();
        }

        function cerrarModalHistorial() {
            document.getElementById('historialModal').style.display = 'none';
            historialState.paginaActual = 1;
            historialState.filtros = { fecha_desde: '', fecha_hasta: '' };
            document.getElementById('historial-fecha-desde').value = '';
            document.getElementById('historial-fecha-hasta').value = '';
        }

        function cargarHistorial() {
            const container = document.getElementById('historialCitasContainer');
            container.innerHTML = `
                <div class="agenda-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Cargando historial...</p>
                </div>
            `;

            const params = new URLSearchParams({
                page: historialState.paginaActual,
                ...historialState.filtros
            });

            for (let [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }

            fetch(`/empleado/historial?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    renderizarHistorial(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Error al cargar el historial</h3>
                            <p>Por favor, intenta nuevamente</p>
                        </div>
                    `;
                });
        }

        function renderizarHistorial(data) {
            // Actualizar estadísticas
            if (data.estadisticas) {
                document.getElementById('stat_total_servicios').textContent = data.estadisticas.total_servicios || 0;
                document.getElementById('stat_ingresos').textContent = parseFloat(data.estadisticas.ingresos_generados || 0).toFixed(2);
                document.getElementById('stat_servicios_hoy').textContent = data.estadisticas.servicios_hoy || 0;
                document.getElementById('stat_servicios_mes').textContent = data.estadisticas.servicios_mes || 0;
            }

            const container = document.getElementById('historialCitasContainer');
            const paginacion = document.getElementById('historialPaginacion');

            if (!data.citas.data || data.citas.data.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>No hay servicios finalizados</h3>
                        <p>Aún no has completado ningún servicio</p>
                    </div>
                `;
                paginacion.style.display = 'none';
                return;
            }

            historialState.paginaActual = data.citas.current_page;
            historialState.totalPaginas = data.citas.last_page;

            let html = '';
            data.citas.data.forEach(cita => {
                const fechaHora = new Date(cita.fecha_hora);
                const fecha = fechaHora.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const total = cita.servicios.reduce((sum, s) => sum + parseFloat(s.pivot.precio), 0);

                // Mapeo de métodos de pago
                const metodosPago = {
                    'efectivo': 'EFECTIVO',
                    'tarjeta': 'TARJETA',
                    'transferencia': 'TRANSFERENCIA'
                };

                html += `
                    <div class="agenda-cita-card" style="border-left-color: var(--success);">
                        <div class="agenda-cita-time" style="background: var(--success-gradient);">
                            <span class="agenda-cita-time-hour"><i class="fas fa-check-circle"></i></span>
                            <span class="agenda-cita-time-date">${fecha}</span>
                        </div>

                        <div class="agenda-cita-info">
                            <h3>
                                <i class="fas fa-user"></i>
                                ${cita.usuario.nombre}
                                ${cita.pago ? `<span class="status-badge" style="background: var(--success); color: white; border-color: var(--success); margin-left: 0.5rem;">
                                    ${metodosPago[cita.pago.metodo] || cita.pago.metodo.toUpperCase()}
                                </span>` : ''}
                            </h3>
                            <p>
                                <i class="fas fa-car"></i>
                                ${cita.vehiculo.marca} ${cita.vehiculo.modelo} - ${cita.vehiculo.placa}
                            </p>
                            <p>
                                <i class="fas fa-dollar-sign"></i>
                                <strong>Total:</strong> $${total.toFixed(2)}
                            </p>
                            <div style="margin-top: 0.5rem;">
                                ${cita.servicios.map(s => `
                                    <span class="service-tag">${s.nombre}</span>
                                `).join('')}
                            </div>
                        </div>

                        <div class="agenda-cita-actions">
                            <button onclick="verDetalleDesdeAgenda(${cita.id})" class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            if (data.citas.last_page > 1) {
                paginacion.style.display = 'flex';
                document.getElementById('paginaActualHistorial').textContent = data.citas.current_page;
                document.getElementById('totalPaginasHistorial').textContent = data.citas.last_page;
                document.getElementById('btnPrevHistorial').disabled = data.citas.current_page === 1;
                document.getElementById('btnNextHistorial').disabled = data.citas.current_page === data.citas.last_page;
            } else {
                paginacion.style.display = 'none';
            }
        }

        function aplicarFiltrosHistorial() {
            historialState.filtros.fecha_desde = document.getElementById('historial-fecha-desde').value;
            historialState.filtros.fecha_hasta = document.getElementById('historial-fecha-hasta').value;
            historialState.paginaActual = 1;
            cargarHistorial();
        }

        function limpiarFiltrosHistorial() {
            document.getElementById('historial-fecha-desde').value = '';
            document.getElementById('historial-fecha-hasta').value = '';
            historialState.filtros = { fecha_desde: '', fecha_hasta: '' };
            historialState.paginaActual = 1;
            cargarHistorial();
        }

        function cambiarPaginaHistorial(direccion) {
            if (direccion === 'prev' && historialState.paginaActual > 1) {
                historialState.paginaActual--;
            } else if (direccion === 'next' && historialState.paginaActual < historialState.totalPaginas) {
                historialState.paginaActual++;
            }
            cargarHistorial();
        }

        // ==========================================
        // FUNCIONES DEL MODAL DE BITÁCORA
        // ==========================================

        let bitacoraState = {
            paginaActual: 1,
            totalPaginas: 1,
            filtros: {
                accion: '',
                fecha_desde: '',
                fecha_hasta: ''
            }
        };

        function abrirModalBitacora() {
            document.getElementById('bitacoraModal').style.display = 'flex';
            cargarBitacora();
        }

        function cerrarModalBitacora() {
            document.getElementById('bitacoraModal').style.display = 'none';
            bitacoraState.paginaActual = 1;
            bitacoraState.filtros = { accion: '', fecha_desde: '', fecha_hasta: '' };
            document.getElementById('bitacora-accion').value = '';
            document.getElementById('bitacora-fecha-desde').value = '';
            document.getElementById('bitacora-fecha-hasta').value = '';
        }

        function cargarBitacora() {
            const container = document.getElementById('bitacoraContainer');
            container.innerHTML = `
                <div class="agenda-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Cargando bitácora...</p>
                </div>
            `;

            const params = new URLSearchParams({
                page: bitacoraState.paginaActual,
                ...bitacoraState.filtros
            });

            for (let [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }

            fetch(`/empleado/bitacora?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    renderizarBitacora(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Error al cargar la bitácora</h3>
                            <p>Por favor, intenta nuevamente</p>
                        </div>
                    `;
                });
        }

        function renderizarBitacora(data) {
            const container = document.getElementById('bitacoraContainer');
            const paginacion = document.getElementById('bitacoraPaginacion');

            if (!data.data || data.data.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-book"></i>
                        <h3>No hay registros</h3>
                        <p>No se encontraron acciones registradas</p>
                    </div>
                `;
                paginacion.style.display = 'none';
                return;
            }

            bitacoraState.paginaActual = data.current_page;
            bitacoraState.totalPaginas = data.last_page;

            let html = '<div style="display: grid; gap: 1rem;">';
            data.data.forEach(registro => {
                const fecha = new Date(registro.created_at).toLocaleString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const iconos = {
                    'cambiar_estado_cita': 'fa-exchange-alt',
                    'agregar_observaciones_cita': 'fa-edit',
                    'finalizar_cita': 'fa-check-circle',
                    'actualizar_perfil': 'fa-user-edit'
                };

                const colores = {
                    'cambiar_estado_cita': '#3b82f6',
                    'agregar_observaciones_cita': '#f59e0b',
                    'finalizar_cita': '#10b981',
                    'actualizar_perfil': '#8b5cf6'
                };

                const icono = iconos[registro.accion] || 'fa-info-circle';
                const color = colores[registro.accion] || '#6b7280';

                html += `
                    <div style="background: white; border-left: 4px solid ${color}; padding: 1rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm);">
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas ${icono}"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--gray-800); margin-bottom: 0.25rem;">
                                    ${registro.accion.split('_').map(p => p.charAt(0).toUpperCase() + p.slice(1)).join(' ')}
                                </div>
                                <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">
                                    ${registro.detalles}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--gray-500);">
                                    <i class="fas fa-clock"></i> ${fecha}
                                    ${registro.tabla_afectada ? ` | <i class="fas fa-table"></i> ${registro.tabla_afectada}` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            container.innerHTML = html;

            if (data.last_page > 1) {
                paginacion.style.display = 'flex';
                document.getElementById('paginaActualBitacora').textContent = data.current_page;
                document.getElementById('totalPaginasBitacora').textContent = data.last_page;
                document.getElementById('btnPrevBitacora').disabled = data.current_page === 1;
                document.getElementById('btnNextBitacora').disabled = data.current_page === data.last_page;
            } else {
                paginacion.style.display = 'none';
            }
        }

        function aplicarFiltrosBitacora() {
            bitacoraState.filtros.accion = document.getElementById('bitacora-accion').value;
            bitacoraState.filtros.fecha_desde = document.getElementById('bitacora-fecha-desde').value;
            bitacoraState.filtros.fecha_hasta = document.getElementById('bitacora-fecha-hasta').value;
            bitacoraState.paginaActual = 1;
            cargarBitacora();
        }

        function limpiarFiltrosBitacora() {
            document.getElementById('bitacora-accion').value = '';
            document.getElementById('bitacora-fecha-desde').value = '';
            document.getElementById('bitacora-fecha-hasta').value = '';
            bitacoraState.filtros = { accion: '', fecha_desde: '', fecha_hasta: '' };
            bitacoraState.paginaActual = 1;
            cargarBitacora();
        }

        function cambiarPaginaBitacora(direccion) {
            if (direccion === 'prev' && bitacoraState.paginaActual > 1) {
                bitacoraState.paginaActual--;
            } else if (direccion === 'next' && bitacoraState.paginaActual < bitacoraState.totalPaginas) {
                bitacoraState.paginaActual++;
            }
            cargarBitacora();
        }

        // ==========================================
        // FUNCIONES DEL MODAL DE CONFIGURACIÓN
        // ==========================================

        function abrirModalConfiguracion() {
            document.getElementById('configuracionModal').style.display = 'flex';
        }

        function cerrarModalConfiguracion() {
            document.getElementById('configuracionModal').style.display = 'none';
            document.getElementById('configuracionForm').reset();
            document.getElementById('cambiarPasswordForm').reset();
            // Restaurar valores originales
            document.getElementById('config_nombre').value = '{{ Auth::user()->nombre }}';
            document.getElementById('config_telefono').value = '{{ Auth::user()->telefono }}';
        }

        // Formulario de configuración
        document.getElementById('configuracionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/empleado/perfil/actualizar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT'
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

                        // Actualizar información en el dashboard
                        const welcomeMessage = document.querySelector('.welcome-message h1');
                        if (welcomeMessage) {
                            welcomeMessage.innerHTML = `
                                <i class="fas fa-hand-sparkles"></i>
                                ¡Bienvenido, ${data.usuario.nombre}!
                            `;
                        }

                        const profileName = document.querySelector('.profile-summary-icon h3');
                        if (profileName) {
                            profileName.textContent = data.usuario.nombre;
                        }

                        const profilePhone = document.querySelector('.profile-summary-icon p:nth-of-type(2)');
                        if (profilePhone) {
                            profilePhone.innerHTML = `<i class="fas fa-phone" style="color: var(--primary);"></i> ${data.usuario.telefono}`;
                        }

                        cerrarModalConfiguracion();
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
                        title: 'Error al actualizar el perfil'
                    });
                });
        });

        // Formulario de cambiar contraseña
        document.getElementById('cambiarPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const passwordNueva = document.getElementById('config_password_nueva').value;
            const passwordConfirmacion = document.getElementById('config_password_confirmacion').value;

            // Validar que las contraseñas coincidan
            if (passwordNueva !== passwordConfirmacion) {
                Toast.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden'
                });
                return;
            }

            const formData = new FormData(this);

            fetch('/empleado/perfil/cambiar-password', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT'
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

                        // Limpiar formulario
                        document.getElementById('cambiarPasswordForm').reset();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Error al cambiar la contraseña'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cambiar la contraseña'
                    });
                });
        });

        // Actualizar cerrar modales al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                if (event.target.id === 'agendaModal') {
                    cerrarModalAgenda();
                } else if (event.target.id === 'detalleCitaModal') {
                    closeDetalleModal();
                } else if (event.target.id === 'finalizarCitaModal') {
                    closeFinalizarModal();
                } else if (event.target.id === 'observacionesModal') {
                    closeObservacionesModal();
                } else if (event.target.id === 'historialModal') {
                    cerrarModalHistorial();
                } else if (event.target.id === 'bitacoraModal') {
                    cerrarModalBitacora();
                } else if (event.target.id === 'configuracionModal') {
                    cerrarModalConfiguracion();
                }
            }
        });
    </script>
</body>

</html>
