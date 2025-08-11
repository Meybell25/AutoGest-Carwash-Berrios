<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Cliente - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(45deg, #4facfe 0%, #1be9f4 100%);
            --success-gradient: linear-gradient(45deg, #3dd26e 0%, #35ebc9 100%);
            --warning-gradient: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #333;
            --text-secondary: #666;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);

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

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Contenedores principales */
        .dashboard-container,
        .header,
        .card {
            max-width: 100%;
            overflow: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
        }


        /* Textos largos */
        .service-details h4,
        .service-details p {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #bbadfd, #5b21b6, #452383);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
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
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(250, 112, 154, 0.05) 0%, transparent 50%);
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

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header con bienvenida mejorada */
        .header {
            background: rgba(245, 245, 245, 0.95);
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
            background: var(--secondary-gradient);
            border-radius: 20px 20px 0 0;
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
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-icon {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
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


        .welcome-section p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .welcome-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }


        .welcome-stat {
            background: white !important;
            padding: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.1);
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
            background: var(--secondary-gradient);
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
            font-size: 1.2rem;
            font-weight: 700;
            color: #4facfe;
            display: block;
        }

        .welcome-stat .label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .header-actions {
            padding: 12px;
            margin-top: 15px;

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
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #4facfe;
            color: #4facfe;
        }

        .btn-outline:hover {
            background: #4facfe;
            color: white;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-profile {
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .btn-profile {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .btn-profile:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
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

        /* Dashboard Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .main-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Cards Base */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 172, 254, 0.1));
            opacity: 0.1;
            transition: all 0.3s ease;
        }

        .card:hover::before {
            width: 100%;
        }

        .card-header {
            padding: 20px 25px 0;
            border-bottom: 2px solid #f1f3f4;
            margin-bottom: 20px;
            position: relative;
        }

        .card-header h2 {
            color: #4facfe;
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .card-header .icon {
            background: var(--secondary-gradient);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }


        .card-body {
            padding: 0 25px 25px;
        }

        /* Próximas Citas */
        .next-appointment {
            background: linear-gradient(135deg, #667eea20, #764ba220);
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #4facfe;
            margin-bottom: 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .next-appointment::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 172, 254, 0.1));
            transition: all 0.3s ease;
        }

        .next-appointment:hover::before {
            width: 100%;
        }

        .next-appointment:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .appointment-date-time {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .date-badge {
            background: var(--secondary-gradient);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            text-align: center;
            min-width: 80px;
        }

        .date-badge .day {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .date-badge .month {
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .time-info {
            flex: 1;
        }

        .time-info .time {
            font-size: 1.3rem;
            font-weight: 700;
            color: #4facfe;
            margin-bottom: 5px;
        }

        .time-info .service {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .appointment-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmado {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-en-proceso {
            background: #f8d7da;
            color: #721c24;
        }

        .status-finalizado {
            background: #d4edda;
            color: #155724;
        }

        .appointment-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        /* Historial de Servicios */
        .service-history-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #f1f3f4;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .service-history-item:hover {
            border-color: #4facfe;
            background: #f8f9fa;
            transform: translateX(3px);
        }

        .service-icon {
            background: var(--success-gradient);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .service-details {
            flex: 1;
        }

        .service-details h4 {
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .service-details p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 3px;
        }

        .service-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #4facfe;
            text-align: right;
        }

        .repeat-service {
            background: #e9ecef;
            color: var(--text-secondary);
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 5px;
            display: inline-block;
        }

        .repeat-service:hover {
            background: #4facfe;
            color: white;
        }

        /* Servicios Disponibles */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: var(--glass-bg);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .service-card:hover {
            transform: translateY(-5px);
            border-color: #4facfe;
            box-shadow: var(--shadow-hover);
        }

        .service-card .service-icon {
            background: var(--secondary-gradient);
            margin: 0 auto 15px;
        }

        .service-card h3 {
            color: var(--text-primary);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .service-card .description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .service-card .price {
            font-size: 1.5rem;
            font-weight: 800;
            color: #4facfe;
            margin-bottom: 5px;
        }

        .service-card .duration {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-bottom: 15px;
        }

        .service-card.selected {
            background-color: #e7f3ff;
            border-color: #4facfe;
            box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.3);
        }

        .service-card input[type="checkbox"]:checked+div {
            font-weight: bold;
            color: #4facfe;
        }

        /* Perfil del Cliente - Sidebar */
        .profile-summary {
            padding: 20px;
            text-align: center;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--secondary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 15px;
            box-shadow: var(--shadow-lg);
            position: relative;
            transition: all 0.3s ease;
        }

        .profile-avatar::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--secondary-gradient);
            z-index: -1;
            opacity: 0.3;
            animation: pulse 2s infinite;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-info h3 {
            color: var(--text-primary);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-info-item i {
            background: var(--secondary-gradient) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            padding: 0 5px;
            border-radius: 3px;
            display: inline-block;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .profile-stat {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .profile-stat .number {
            font-size: 1.4rem;
            font-weight: 700;
            color: #4facfe;
            display: block;
        }

        .profile-stat .label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Notificaciones */
        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .notification-item.unread {
            background: linear-gradient(45deg, #4facfe10, #00f2fe10);
            border-left: 4px solid #4facfe;
        }

        .notification-item.read {
            background: #f8f9fa;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
        }

        .notification-icon.info {
            background: var(--info-gradient);
            color: white;
        }

        .notification-icon.success {
            background: var(--success-gradient);
            color: white;
        }

        .notification-icon.warning {
            background: var(--warning-gradient);
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .notification-content h4 {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-content p {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .notification-time {
            color: var(--text-secondary);
            font-size: 0.75rem;
            white-space: nowrap;
        }

        /* Estilos para indicadores de urgencia en las citas próximas */
        .next-appointment:first-child {
            border: 2px solid transparent;
            box-shadow: var(--shadow-lg);
            position: relative;
            border-left: 4px solid transparent;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        }

        .next-appointment:first-child .date-badge {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }

        .next-appointment:nth-child(2) .date-badge {
            background: var(--secondary-gradient) !important;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
            animation: none;
        }

        .next-appointment:first-child:hover,
        .next-appointment:nth-child(2):hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .next-appointment:first-child .date-badge,
        .next-appointment:nth-child(2) .date-badge {
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .next-appointment:first-child .date-badge .days-remaining,
        .next-appointment:nth-child(2) .date-badge .days-remaining {
            background-color: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 4px;
            padding: 2px 6px;
            margin-top: 3px;
            font-weight: 600;
        }

        .next-appointment:nth-child(2) {
            border-left: 4px solid transparent;
            background: linear-gradient(45deg, rgba(79, 172, 254, 0.05), rgba(27, 233, 244, 0.05));
        }

        /* Animación suave para la primera cita */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }

            50% {
                transform: scale(1.02);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            }
        }

        /* Responsive: ajustes para móviles */
        @media (max-width: 768px) {

            .next-appointment:first-child .date-badge .days-remaining,
            .next-appointment:nth-child(2) .date-badge .days-remaining {
                font-size: 9px;
                padding: 1px 4px;
            }
        }

        /* Indicador de días restantes en el badge de fecha */
        .date-badge .days-remaining {
            display: block;
            font-size: 10px;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
            padding: 1px 4px;
            margin-top: 2px;
            font-weight: 500;
        }

        /* Información de días cuando no está en el badge */
        .days-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .days-info i {
            margin-right: 5px;
        }



        /* Clases de urgencia para las citas */
        /* Clases de urgencia para las citas */
        .next-appointment.urgent-soon {
            border-left: 4px solid #dc3545;
            background-color: #fff5f5;
        }

        .next-appointment.urgent-soon .date-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
            animation: pulse 2s infinite;
        }

        .next-appointment.urgent-close {
            border-left: 4px solid #fd7e14;
            background-color: #fff8f0;
        }

        .next-appointment.urgent-close .date-badge {
            background: linear-gradient(135deg, #fd7e14, #e5650b);
        }

        .next-appointment.coming-soon {
            border-left: 4px solid #ffc107;
            background-color: #fffbf0;
        }

        .next-appointment.coming-soon .date-badge {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }

        /* Animación de pulso para citas urgentes */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Mejorar el estilo del contenedor de información */
        .next-appointment .appointment-date-time {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        /* Responsive para dispositivos móviles */
        @media (max-width: 768px) {
            .days-remaining {
                font-size: 9px !important;
                padding: 1px 3px !important;
            }

            .days-info {
                font-size: 11px;
            }
        }

        /* Estilo para el mensaje informativo */
        .info-message {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
            text-align: center;
        }

        .info-message small {
            color: #6c757d;
            font-size: 12px;
        }

        .info-message i {
            margin-right: 5px;
            color: #17a2b8;
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            color: #4facfe;
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .empty-state p {
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-section {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 25px;
            }

            .header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 10px;
            }
        }

        @media (max-width: 992px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .next-appointment {
                flex-direction: column;
            }

            .date-badge {
                margin-bottom: 10px;
            }
        }


        @media (max-width: 768px) {
            .welcome-section {
                text-align: center;
            }

            .welcome-section h1 {
                align-items: center;
            }

            .dashboard-container {
                padding: 15px;
            }

            .header {
                padding: 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-stats {
                justify-content: center;
                flex-wrap: wrap;
            }


            .btn {
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .appointment-date-time {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card {
                width: 100%;
                margin: 0 auto;
            }

            .card-header,
            .card-body {
                padding: 15px;
            }

            .service-card[style*="text-align: left"] {
                padding: 15px;
            }

            .service-card[style*="text-align: left"]>div {
                flex-direction: column;
            }

            .service-card[style*="text-align: left"] .btn-sm {
                width: 100%;
                margin-bottom: 5px;
            }

            .welcome-section h1 {
                font-size: 2rem;
                flex-direction: column;
                align-items: flex-start;
            }

            .welcome-icon {
                margin-bottom: 10px;
            }

            .header-actions {
                grid-template-columns: 1fr 1fr;
            }

            .service-history-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .service-price {
                margin-top: 10px;
                align-self: flex-end;
            }

            .profile-stats {
                grid-template-columns: 1fr;
            }

            .service-card {
                text-align: center;
                padding: 20px 15px;
            }

            .service-card .btn {
                width: 100%;
            }

            .notification-item {
                padding: 12px;
            }

            .notification-icon {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .header-actions {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .services-grid {
                grid-template-columns: 1fr;
            }

            .header-actions {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
            }

            .appointment-actions {
                flex-direction: column;
            }

            .appointment-actions .btn {
                width: 100%;
                margin-bottom: 5px;
            }

            .welcome-stats {
                grid-template-columns: 1fr;
            }

            .notification-item {
                flex-direction: column;
            }

            .notification-time {
                margin-top: 5px;
                align-self: flex-start;
            }

            .service-history-item {
                padding: 12px;
            }

            .service-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .service-card[style*="text-align: left"] {
                text-align: center;
            }
        }

        @media (max-width: 480px) {

            input,
            textarea,
            select {
                font-size: 16px;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .service-history-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .service-icon {
                margin-bottom: 10px;
                margin-right: 0;
            }

            .service-price {
                text-align: left;
                margin-top: 10px;
                width: 100%;
            }

            .appointment-date-time {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .welcome-stats {
                flex-direction: column;
            }

            .welcome-stat {
                width: 100%;
                max-width: none;
            }

            .form-group input {
                width: 100%;
                box-sizing: border-box;
            }

            .profile-stats {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 400px) {
            .dashboard-container {
                padding: 10px;
            }

            .header {
                padding: 15px;
            }

            .card-header h2 {
                font-size: 1.2rem;
            }

            .card-header .icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .date-badge {
                min-width: 70px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 360px) {
            .btn {
                padding: 10px 12px;
                font-size: 0.8rem;
            }

            .dashboard-container {
                padding: 8px;
            }

            .service-card {
                padding: 15px 10px;
            }

            .header {
                padding: 10px 15px;
            }

            .card-header,
            .card-body {
                padding: 10px;
            }

            .welcome-section h1 {
                font-size: 1.8rem;
            }

            .card-header h2 {
                font-size: 1.2rem;
            }

            .welcome-icon,
            .card-header .icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
        }

        /* Animations */
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

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .card:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Scrollbar customization */
        .card-body::-webkit-scrollbar {
            width: 6px;
        }

        .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            border-radius: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            padding: 20px 0;
        }

        .modal-content {
            background: var(--glass-bg);
            margin: 5% auto;
            padding: 25px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #4facfe;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        /* Estilos para el modal de citas */
        .service-card input[type="checkbox"] {
            accent-color: #4facfe;
            cursor: pointer;
        }

        .service-card:hover {
            background-color: #f8f9fa;
            border-color: #4facfe;
            transform: translateY(-2px);
        }

        .service-card.selected {
            background-color: #e7f3ff;
            border-color: #4facfe;
        }

        /* Estilos para el selector de hora */
        #hora {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: white;
        }

        /* Estilos para el calendario */
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        /* Efecto para los servicios seleccionados */
        .service-card input[type="checkbox"]:checked+div h4 {
            color: #4facfe;
            font-weight: bold;
        }

        /* Responsive para el grid de servicios */
        @media (max-width: 768px) {
            #serviciosContainer {
                grid-template-columns: 1fr;
            }
        }

        /* Estilos para el select de vehículos */
        #vehiculo_id {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 15px;
            background-color: white;
        }

        /* Estilos para las tarjetas de servicio */
        .service-card {
            transition: all 0.3s ease;
            padding: 12px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .service-card:hover {
            background-color: #f0f7ff;
            border-color: #4facfe;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .service-card input[type="checkbox"]:checked+div h4 {
            color: #4facfe;
            font-weight: bold;
        }

        /* Estilos para el contenedor de servicios */
        #serviciosContainer {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        /* Estilos para mensajes de error en el calendario */
        .swal2-popup .swal2-html-container {
            text-align: left;
            max-height: 200px;
            overflow-y: auto;
        }

        .swal2-popup .swal2-title {
            color: #dc3545;
        }

        /* Estilo para el input de fecha cuando hay error */
        input:invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }

        /* Estilo para días no disponibles en el datepicker */
        input[type="date"]::after {
            content: "✓";
            color: green;
            position: absolute;
            right: 10px;
            top: 10px;
        }

        /* Estilo para el input de fecha cuando es inválido */
        input[type="date"]:invalid {
            border-color: #ff6b6b;
            background-color: #fff5f5;
        }

        /* Estilo para el contenedor de días no disponibles */
        .dia-no-disponible {
            color: #ff6b6b;
            text-decoration: line-through;
        }

        /* Mejora para los mensajes de error */
        .swal2-popup .swal2-html-container {
            text-align: left;
            line-height: 1.6;
        }

        .swal2-popup .swal2-title {
            color: #4facfe;
            font-size: 1.5em;
        }

        /* Estilo para el select de vehículos */
        #vehiculo_id {
            transition: all 0.3s;
        }

        #vehiculo_id:invalid {
            border-color: #ff6b6b;
        }

        /* Estilos para la selección de hora */
        #hora {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .swal2-popup {
            border-radius: 15px !important;
            padding: 25px !important;
            box-shadow: var(--shadow-xl) !important;
        }

        .swal2-title {
            font-size: 1.5rem !important;
            color: #4facfe !important;
        }

        .swal2-content {
            font-size: 1rem !important;
        }

        .swal2-confirm {
            background: var(--secondary-gradient) !important;
            border: none !important;
            box-shadow: none !important;
        }

        .swal2-cancel {
            border: 2px solid #4facfe !important;
            color: #4facfe !important;
        }

        .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(79, 172, 254, 0.3) !important;
        }

        .swal2-icon.swal2-success [class^="swal2-success-line"] {
            background-color: #4facfe !important;
        }

        .swal2-icon.swal2-error {
            border-color: #ff6b6b !important;
            color: #ff6b6b !important;
        }

        .swal2-icon.swal2-error [class^="swal2-x-mark-line"] {
            background-color: #ff6b6b !important;
        }

        .skeleton-loading {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
        }

        .skeleton-card {
            background: #f0f0f0;
            border-radius: 10px;
            height: 120px;
            position: relative;
            overflow: hidden;
        }

        .skeleton-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .card-body.scrollable {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        /* Personalizar scrollbar */
        .card-body.scrollable::-webkit-scrollbar {
            width: 8px;
        }

        .card-body.scrollable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .card-body.scrollable::-webkit-scrollbar-thumb {
            background: #4facfe;
            border-radius: 10px;
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
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .footer-content {
            padding: 40px 30px;
            text-align: center;
            border-radius: 25px;
        }

        .footer-brand {
            margin-bottom: 15px;
        }

        .footer-brand h3 {
            font-size: 28px;
            font-weight: 700;
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 8px 0;
            text-shadow: none;
        }

        .footer-slogan {
            font-size: 14px;
            color: var(--text-secondary);
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
            color: var(--text-primary);
            font-size: 15px;
            transition: all 0.3s ease;
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
            background: var(--secondary-gradient);
            color: white;
            border-radius: 50%;
            font-size: 10px;
            box-shadow: 0 2px 8px rgba(79, 172, 254, 0.3);
        }

        .location-link {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .location-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--secondary-gradient);
            transition: width 0.3s ease;
        }

        .location-link:hover::after {
            width: 100%;
        }

        .location-link:hover {
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin: 20px 0;
        }

        .footer-copyright {
            color: var(--text-secondary);
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
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-soft);
        }

        .social-icon:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: var(--shadow-hover);
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

        .schedule-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .schedule-main {
            font-weight: 500;
            color: var(--text-primary);
        }

        .schedule-closed {
            font-size: 13px;
            color: var(--text-secondary);
            opacity: 0.8;
        }

        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--secondary-gradient);
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
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header con bienvenida personalizada -->
        <div class="header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <div class="welcome-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        ¡Hola, {{ $user->nombre ?? 'Cliente' }}!
                    </h1>
                    <p>Gestiona tus citas de lavado de forma sencilla</p>
                    <div class="welcome-stats">
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['total_citas'] ?? 0 }}</span>
                            <span class="label">Servicios</span>
                        </div>
                        <div class="welcome-stat">
                            @php
                                $totalGastado = 0;
                                if (isset($mis_citas)) {
                                    foreach ($mis_citas as $cita) {
                                        if ($cita->estado == 'finalizada' || $cita->estado == 'pagada') {
                                            $totalGastado += $cita->servicios->sum('precio');
                                        }
                                    }
                                }
                            @endphp
                            <span class="number">${{ number_format($totalGastado, 2) }}</span>
                            <span class="label">Total Gastado</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['citas_pendientes'] ?? 0 }}</span>
                            <span class="label">Pendientes</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Agregar Vehículo
                    </a>
                    <a href="#" class="btn btn-primary" onclick="openCitaModal()">
                        <i class="fas fa-calendar-plus"></i>
                        Nueva Cita
                    </a>
                    <a href="{{ route('configuracion.index') }}" class="btn btn-profile">
                        <i class="fas fa-cog"></i> Configurar Cuenta
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline">
                            <i class="fas fa-sign-out-alt"></i>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Sección Principal -->
            <div class="main-section">
                <!-- Próximas Citas -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            Próximas Citas
                            <small style="font-size: 12px; color: #6c757d; font-weight: normal;">
                                (Próximos 15 días)
                            </small>
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if ($proximas_citas->count() > 0)
                            @foreach ($proximas_citas as $cita)
                                @php
                                    // Calcular días restantes
                                    $diasRestantes = now()->diffInDays($cita->fecha_hora, false);
                                    $diasRestantes = $diasRestantes < 0 ? 0 : ceil($diasRestantes);

                                    // Determinar clase de urgencia
                                    $urgenciaClass = '';
                                    $urgenciaText = '';

                                    if ($diasRestantes <= 1) {
                                        $urgenciaClass = 'urgent-soon';
                                        $urgenciaText = $diasRestantes == 0 ? 'Hoy' : 'Mañana';
                                    } elseif ($diasRestantes <= 3) {
                                        $urgenciaClass = 'urgent-close';
                                        $urgenciaText = "En {$diasRestantes} días";
                                    } elseif ($diasRestantes <= 7) {
                                        $urgenciaClass = 'coming-soon';
                                        $urgenciaText = "En {$diasRestantes} días";
                                    } else {
                                        $urgenciaText = "En {$diasRestantes} días";
                                    }
                                @endphp

                                <div
                                    class="next-appointment {{ $loop->first ? 'highlighted' : '' }} {{ $urgenciaClass }}">
                                    <div class="appointment-date-time">
                                        <div class="date-badge">
                                            <span class="day">{{ $cita->fecha_hora->format('d') }}</span>
                                            <span class="month">{{ $cita->fecha_hora->format('M') }}</span>
                                            @if ($diasRestantes <= 7)
                                                <span class="days-remaining">{{ $urgenciaText }}</span>
                                            @endif
                                        </div>
                                        <div class="time-info">
                                            <div class="time">{{ $cita->fecha_hora->format('h:i A') }}</div>
                                            <div class="service">
                                                {{ $cita->servicios->pluck('nombre')->join(', ') }}
                                            </div>
                                            <div class="vehicle-info">
                                                <i class="fas fa-car"></i> {{ $cita->vehiculo->marca }}
                                                {{ $cita->vehiculo->modelo }}
                                            </div>
                                            @if ($diasRestantes > 7)
                                                <div class="days-info">
                                                    <i class="fas fa-clock"></i> {{ $urgenciaText }}
                                                </div>
                                            @endif
                                        </div>
                                        <span
                                            class="appointment-status status-{{ str_replace('_', '-', $cita->estado) }}">
                                            {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                        </span>
                                    </div>
                                    <div class="appointment-actions">
                                        @if ($cita->estado == 'pendiente' || $cita->estado == 'confirmada')
                                            <button class="btn btn-sm btn-warning"
                                                onclick="editCita({{ $cita->id }})">
                                                <i class="fas fa-edit"></i> Modificar
                                            </button>
                                            <button class="btn btn-sm btn-outline"
                                                onclick="cancelCita({{ $cita->id }})">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <!-- Mensaje informativo sobre el filtro -->
                            <div class="info-message">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    Se muestran solo las citas de los próximos 15 días
                                </small>
                            </div>

                            @if ($proximas_citas->count() > 3)
                                <div style="text-align: center; margin-top: 15px;">
                                    <a href="{{ route('cliente.citas') }}" class="btn btn-outline">
                                        <i class="fas fa-list"></i> Ver todas las citas
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-alt"></i>
                                <h3>No tienes citas programadas</h3>
                                <p>En los próximos 15 días</p>
                                <small style="color: #6c757d; display: block; margin-top: 5px;">
                                    Agenda una cita y aparecerá aquí si está dentro de este período
                                </small>
                                <button onclick="openCitaModal()" class="btn btn-primary" style="margin-top: 15px;">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Cita
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historial de Servicios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-history"></i>
                            </div>
                            Historial de Servicios
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if ($historial_citas->count() > 0)
                            @foreach ($historial_citas as $cita)
                                <div class="service-history-item">
                                    <div class="service-icon">
                                        <i class="fas fa-soap"></i>
                                    </div>
                                    <div class="service-details">
                                        <h4>
                                            @if ($cita->servicios && count($cita->servicios) > 0)
                                                {{ $cita->servicios->pluck('nombre')->join(', ') }}
                                            @else
                                                Servicio no especificado
                                            @endif
                                        </h4>
                                        <p><i class="fas fa-calendar"></i>
                                            {{ $cita->fecha_hora->format('d M Y - h:i A') }}</p>
                                        <p><i class="fas fa-car"></i> {{ $cita->vehiculo->marca }}
                                            {{ $cita->vehiculo->modelo }} - {{ $cita->vehiculo->placa }}</p>
                                        <p class="appointment-status status-{{ str_replace('_', '-', $cita->estado) }}"
                                            style="display: inline-block; margin-top: 5px;">
                                            {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                        </p>
                                        @if ($cita->estado == 'finalizada')
                                            <a href="#" class="repeat-service"
                                                onclick="repeatService({{ $cita->id }})">
                                                <i class="fas fa-redo"></i> Volver a agendar
                                            </a>
                                        @endif
                                    </div>
                                    <div class="service-price">
                                        @php
                                            $total = $cita->servicios->sum('precio');
                                        @endphp
                                        ${{ number_format($total, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-history"></i>
                                <h3>No hay historial de servicios</h3>
                                <p>Agenda tu primera cita para comenzar a ver tu historial</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Servicios Disponibles -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-car-side"></i>
                            </div>
                            Servicios Disponibles
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="services-grid">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-spray-can"></i>
                                </div>
                                <h3>Lavado Completo</h3>
                                <p class="description">Exterior e interior completo, aspirado y limpieza de tapicería
                                </p>
                                <div class="price">$25.00</div>
                                <div class="duration">⏱️ 30-40 min</div>
                                <button class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Ahora
                                </button>
                            </div>

                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fa-solid fa-ring"></i>
                                </div>
                                <h3>Lavado Premium</h3>
                                <p class="description">Servicio completo + encerado, protección UV y brillado</p>
                                <div class="price">$35.00</div>
                                <div class="duration">⏱️ 45-60 min</div>
                                <button class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Ahora
                                </button>
                            </div>

                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-gem"></i>
                                </div>
                                <h3>Detallado VIP</h3>
                                <p class="description">Servicio premium completo, pulido, cera premium y protección</p>
                                <div class="price">$55.00</div>
                                <div class="duration">⏱️ 90-120 min</div>
                                <button class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Ahora
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facturas y Recibos -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            Facturas y Recibos
                        </h2>
                    </div>
                    <div class="card-body">
                        @if (isset($mis_citas) && count($mis_citas) > 0)
                            <div class="services-grid">
                                @foreach ($mis_citas->take(3) as $cita)
                                    <div class="service-card" style="text-align: left;">
                                        <div
                                            style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                            <div>
                                                <h3>Factura
                                                    #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
                                                </h3>
                                                <p style="color: #666; font-size: 0.9rem;">
                                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                @php
                                                    $total = $cita->servicios->sum('precio');
                                                @endphp
                                                <div
                                                    style="font-weight: 700; color: #4facfe; font-size: 1.3rem; text-align: right;">
                                                    ${{ number_format($total, 2) }}</div>
                                                <span
                                                    style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; display: inline-block; margin-top: 5px;">PAGADO</span>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <p><strong>Servicios:</strong></p>
                                            <ul style="padding-left: 20px; margin-top: 5px;">
                                                @foreach ($cita->servicios as $servicio)
                                                    <li>{{ $servicio->nombre }} -
                                                        ${{ number_format($servicio->precio, 2) }}</li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                                            <button class="btn btn-sm btn-outline" style="flex: 1;">
                                                <i class="fas fa-eye"></i> Ver
                                            </button>
                                            <button class="btn btn-sm btn-primary" style="flex: 1;">
                                                <i class="fas fa-download"></i> PDF
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-file-invoice"></i>
                                <h3>No hay facturas disponibles</h3>
                                <p>Agenda tu primera cita para generar facturas</p>
                            </div>
                        @endif

                        <div style="text-align: center; margin-top: 20px;">
                            <button class="btn btn-outline">
                                <i class="fas fa-history"></i> Ver Todas las Facturas
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Sidebar -->
            <div class="sidebar-section">
                <!-- Perfil del Cliente -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            Mi Perfil
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="profile-summary">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <h3>{{ $user->nombre ?? 'Cliente' }}</h3>
                                <p>
                                    <i class="fas fa-envelope"
                                        style="background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 0 5px; border-radius: 3px;"></i>
                                    {{ $user->email ?? 'No especificado' }}
                                </p>
                                <p>
                                    <i class="fas fa-phone"
                                        style="background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 0 5px; border-radius: 3px;"></i>
                                    {{ $user->telefono ?? 'No especificado' }}
                                </p>
                                <p>
                                    <i class="fas fa-calendar"
                                        style="background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 0 5px; border-radius: 3px;"></i>
                                    Cliente desde: {{ $user->created_at->format('M Y') }}
                                </p>
                            </div>

                            <button onclick="openEditModal()" class="btn btn-outline"
                                style="margin-top: 15px; width: 100%;">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notificaciones -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            Notificaciones
                            {{-- Comentado temporalmente hasta que tengamos los controladores --}}
                            <!-- @if ($notificacionesNoLeidas > 0)
<span style="background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; margin-left: auto;">{{ $notificacionesNoLeidas }}</span>
@endif -->
                            <span
                                style="background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; margin-left: auto;">0</span>
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        {{-- Comentado el forelse original --}}
                        <!-- @forelse($notificaciones as $notificacion)
-->

                        {{-- Ejemplo estático de notificación (puedes dejarlo o quitarlo) --}}
                        <div class="notification-item unread">
                            <div class="notification-icon info">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="notification-content">
                                <h4>Notificación del Sistema</h4>
                                <p>Ejemplo de notificación (modo desarrollo)</p>
                            </div>
                            <div class="notification-time">
                                Hace unos momentos <span style="color: #4facfe;">(Hoy)</span>
                            </div>
                        </div>

                        {{-- Estado vacío (si prefieres mostrar esto en lugar del ejemplo) --}}
                    <!-- @empty -->
                        <div class="empty-state" style="padding: 20px;">
                            <i class="fas fa-bell-slash"></i>
                            <h3>No hay notificaciones</h3>
                            <p>No tienes ninguna notificación pendiente</p>
                        </div>
                        <!--
@endforelse -->
                        {{-- Comentado el enlace a todas las notificaciones --}}
                        <!-- @if ($notificaciones->count() > 0)
-->
                        <div style="text-align: center; margin-top: 15px;">
                            <a href="#" class="btn btn-outline">
                                <i class="fas fa-list"></i> Ver todas las notificaciones
                            </a>
                        </div>
                        <!--
@endif -->
                    </div>
                </div>

                <!-- Mis Vehículos -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-car"></i>
                            </div>
                            Mis Vehículos
                        </h2>
                    </div>
                    <div class="card-body" id="misVehiculosContainer">
                        @if (isset($vehiculos_dashboard) && count($vehiculos_dashboard) > 0)
                            @foreach ($vehiculos_dashboard as $vehiculo)
                                <div class="service-history-item" style="margin-bottom: 15px;">
                                    <div class="service-icon" style="background: var(--secondary-gradient);">
                                        @switch($vehiculo->tipo)
                                            @case('sedan')
                                                <i class="fas fa-car"></i>
                                            @break

                                            @case('pickup')
                                                <i class="fas fa-truck-pickup"></i>
                                            @break

                                            @case('camion')
                                                <i class="fas fa-truck"></i>
                                            @break

                                            @case('moto')
                                                <i class="fas fa-motorcycle"></i>
                                            @break

                                            @default
                                                <i class="fas fa-car"></i>
                                        @endswitch
                                    </div>
                                    <div class="service-details">
                                        <h4>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h4>
                                        <p><i class="fas fa-palette"></i> {{ $vehiculo->color }}</p>
                                        <p><i class="fas fa-id-card"></i> {{ $vehiculo->placa }}</p>
                                    </div>
                                    <button class="btn btn-sm btn-primary"
                                        onclick="openCitaModal('{{ $vehiculo->id }}')">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-car"></i>
                                <h3>No tienes vehículos registrados</h3>
                                <p>Agrega tu primer vehículo para comenzar a agendar citas</p>
                            </div>
                        @endif
                        <button type="button" id="openVehiculoBtn" class="btn btn-outline"
                            style="width: 100%; margin-top: 10px;" onclick="openVehiculoModal()">
                            <i class="fas fa-plus"></i>
                            Agregar Vehículo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar perfil -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;">
                <i class="fas fa-user-edit"></i> Editar Perfil
            </h2>
            <form id="profileForm">
                @csrf
                <div class="form-group">
                    <label for="modalNombre">Nombre:</label>
                    <input type="text" id="modalNombre" name="nombre" value="{{ $user->nombre ?? '' }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="modalTelefono">Teléfono:</label>
                    <input type="text" id="modalTelefono" name="telefono" value="{{ $user->telefono ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- Modal para imprimir recibo -->
    <div id="receiptModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeReceiptModal()">&times;</span>
            <div id="receiptContent" style="background: white; padding: 20px; border-radius: 10px;">
                <!-- Contenido del recibo se generará dinámicamente -->
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print"></i> Imprimir Recibo
                </button>
                <button class="btn btn-outline" onclick="downloadReceipt()">
                    <i class="fas fa-download"></i> Descargar PDF
                </button>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal para agregar vehículo -->
    <div id="vehiculoModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeVehiculoModal()">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;">
                <i class="fas fa-car"></i> Nuevo Vehículo
            </h2>

            <form id="vehiculoForm" action="{{ route('vehiculos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" id="marca" name="marca" required>
                </div>

                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" id="modelo" name="modelo" required>
                </div>


                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Seleccione</option>
                        <option value="sedan">Sedán</option>
                        <option value="pickup">Pickup</option>
                        <option value="camion">Camión</option>
                        <option value="moto">Moto</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" id="color" name="color" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="placa">Placa</label>
                    <input type="text" id="placa" name="placa" required>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeVehiculoModal()" class="btn btn-outline">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar Vehículo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para crear/editar cita  -->
    <div id="createCitaModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeCitaModal()">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;">
                <i class="fas fa-calendar-plus"></i> <span id="modalTitle">Nueva Cita</span>
            </h2>

            <form id="citaForm" method="POST" action="{{ route('cliente.citas.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!-- Campo oculto para ID de cita (solo en edición) -->
                <input type="hidden" id="form_cita_id" name="cita_id" value="">

                <!-- Selección de vehículo -->
                <div class="form-group">
                    <label for="vehiculo_id">Vehículo: <span style="color: red;">*</span></label>
                    <select id="vehiculo_id" name="vehiculo_id" required onchange="cargarServiciosPorTipo()">
                        <option value="">Seleccione un vehículo</option>
                        @foreach ($mis_vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->id }}" data-tipo="{{ $vehiculo->tipo }}">
                                {{ $vehiculo->marca }} {{ $vehiculo->modelo }} - {{ $vehiculo->placa }}
                                ({{ ucfirst($vehiculo->tipo) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div class="form-group">
                    <label for="fecha">Fecha: <span style="color: red;">*</span></label>
                    <input type="date" id="fecha" name="fecha" required min="{{ date('Y-m-d') }}"
                        max="{{ date('Y-m-d', strtotime('+1 month')) }}">
                </div>

                <!-- Hora -->
                <div class="form-group">
                    <label for="hora">Hora: <span style="color: red;">*</span></label>
                    <select id="hora" name="hora" required>
                        <option value="">Seleccione una hora</option>
                        <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                    </select>
                </div>

                <!-- Servicios -->
                <div class="form-group">
                    <label>Servicios Disponibles: <span style="color: red;">*</span></label>
                    <div id="serviciosContainer"
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 10px; min-height: 100px;">
                        <p>Seleccione un vehículo primero</p>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="3" maxlength="500"
                        placeholder="Información adicional sobre su vehículo o servicio requerido..."></textarea>
                </div>

                <!-- Botones -->
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-outline" onclick="closeCitaModal()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-save"></i> <span id="submitText">Guardar Cita</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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

    <script>
        /*=========================================================
                                                                                    FUNCIONAMIENTO DE CREAR CITAS
                                                                                =========================================================*/

        // Variables globales
        let horariosDisponibles = [];
        let todosServiciosDisponibles = [];
        let serviciosFiltrados = [];
        let diasNoLaborables = [];

        // Configuración global de SweetAlert
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline mr-2'
            },
            buttonsStyling: false
        });

        function setModalMode(isEdit = false) {
            const modalTitle = document.getElementById('modalTitle');
            const submitText = document.getElementById('submitText');

            if (modalTitle) {
                modalTitle.textContent = isEdit ? 'Editar Cita' : 'Nueva Cita';
            }

            if (submitText) {
                submitText.textContent = isEdit ? 'Actualizar Cita' : 'Guardar Cita';
            }
        }

        // Funciones del modal de citas
        async function openCitaModal(vehiculoId = null) {
            return new Promise(async (resolve, reject) => {
                try {
                    const isActive = await checkUserStatus();
                    if (!isActive) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cuenta inactiva',
                            text: 'Tu cuenta está inactiva. No puedes crear nuevas citas.',
                            icon: 'error'
                        });
                        return reject('Cuenta inactiva');
                    }

                    const modal = document.getElementById('createCitaModal');
                    if (!modal) {
                        return reject('Modal de cita no encontrado');
                    }

                    // Resetear completamente el formulario
                    const citaForm = document.getElementById('citaForm');
                    if (citaForm) {
                        citaForm.reset();
                        citaForm.action = '{{ route('cliente.citas.store') }}';

                        // Eliminar cualquier campo _method
                        const methodInput = citaForm.querySelector('[name="_method"]');
                        if (methodInput) methodInput.remove();

                        // Limpiar ID de cita
                        const citaIdInput = document.getElementById('form_cita_id');
                        if (citaIdInput) citaIdInput.value = '';

                        // Restablecer el título y texto del botón
                        setModalMode(false); // Modo creación

                        // Limpiar servicios seleccionados
                        document.querySelectorAll('.service-card.selected').forEach(card => {
                            card.classList.remove('selected');
                        });

                        // Resetear select de hora
                        const horaSelect = document.getElementById('hora');
                        if (horaSelect) {
                            horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
                        }
                    }

                    // Cargar datos iniciales
                    const loading = swalWithBootstrapButtons.fire({
                        title: 'Preparando formulario...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    try {
                        await loadInitialData();
                        loading.close();

                        // Mostrar modal
                        modal.style.display = 'block';
                        await new Promise(resolve => setTimeout(resolve, 100));

                        // Establecer vehículo si se proporciona (NO carga horarios aún)
                        if (vehiculoId) {
                            const vehiculoSelect = document.getElementById('vehiculo_id');
                            if (vehiculoSelect) {
                                vehiculoSelect.value = vehiculoId;
                                await cargarServiciosPorTipo();
                            }
                        }

                        console.log(' Modal abierto para CREAR nueva cita');
                        resolve();
                    } catch (error) {
                        loading.close();
                        reject(error);
                    }
                } catch (error) {
                    reject(error);
                }
            });
        }

        function closeCitaModal() {
            document.getElementById('createCitaModal').style.display = 'none';
            document.getElementById('citaForm').reset();
        }

        async function checkUserStatus() {
            try {
                const response = await fetch('{{ route('cliente.check-status') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                return data.is_active;
            } catch (error) {
                console.error('Error al verificar estado:', error);
                return false;
            }
        }

        // Función para cargar datos iniciales
        async function loadInitialData() {
            try {
                console.log('Iniciando carga de datos...');

                // Cargar datos en paralelo
                const [horariosRes, serviciosRes, noLaborablesRes] = await Promise.all([
                    fetch('{{ route('cliente.horarios-disponibles') }}').catch(e => {
                        console.error('Error cargando horarios:', e);
                        return {
                            ok: false
                        };
                    }),
                    fetch('{{ route('cliente.servicios-disponibles') }}').catch(e => {
                        console.error('Error cargando servicios:', e);
                        return {
                            ok: false
                        };
                    }),
                    fetch('{{ route('cliente.dias-no-laborables') }}').catch(e => {
                        console.error('Error cargando días no laborables:', e);
                        return {
                            ok: false
                        };
                    })
                ]);

                // Verificar respuestas y procesar
                if (horariosRes.ok) {
                    horariosDisponibles = await horariosRes.json();
                    console.log('Horarios cargados:', horariosDisponibles.length);
                } else {
                    horariosDisponibles = [];
                    console.error('Error cargando horarios disponibles');
                }

                if (serviciosRes.ok) {
                    todosServiciosDisponibles = await serviciosRes.json();
                    console.log('Servicios cargados:', Object.keys(todosServiciosDisponibles));
                } else {
                    todosServiciosDisponibles = {};
                    console.error('Error cargando servicios disponibles');
                }

                if (noLaborablesRes.ok) {
                    diasNoLaborables = await noLaborablesRes.json();
                    console.log('Días no laborables cargados:', diasNoLaborables.length);
                } else {
                    diasNoLaborables = [];
                    console.error('Error cargando días no laborables');
                }

                // Configurar datepicker
                setupDatePicker();

                console.log('Datos iniciales cargados completamente');
                return true;

            } catch (error) {
                console.error('Error crítico cargando datos iniciales:', error);

                // Configurar valores por defecto
                horariosDisponibles = horariosDisponibles || [];
                todosServiciosDisponibles = todosServiciosDisponibles || {};
                diasNoLaborables = diasNoLaborables || [];

                swalWithBootstrapButtons.fire({
                    title: 'Error de conexión',
                    text: 'Hubo problemas cargando algunos datos. Algunas funciones pueden estar limitadas.',
                    icon: 'warning'
                });

                return false;
            }
        }

        // Función para cargar horas disponibles 
        async function loadAvailableHours(selectedDate, excludeCitaId = null) {
            const horaSelect = document.getElementById('hora');

            console.log('Cargando horarios para fecha:', selectedDate, '| Excluir cita:', excludeCitaId);

            horaSelect.innerHTML = '<option value="">Cargando horarios...</option>';

            try {
                // Usar la fecha local correctamente
                const fechaLocal = createLocalDate(selectedDate);
                const dayOfWeekJS = fechaLocal.getDay(); // 0=Domingo, 1=Lunes, etc.
                const dayOfWeekBackend = getBackendDayFromJSDay(dayOfWeekJS);

                console.log('Fecha seleccionada:', selectedDate);
                console.log('Día JS:', dayOfWeekJS, 'Día Backend:', dayOfWeekBackend);

                // Validar si es domingo
                if (dayOfWeekJS === 0) {
                    horaSelect.innerHTML = '<option value="">No hay horarios (No atendemos domingos)</option>';
                    return;
                }

                // Verificar día no laborable
                const diaNoLaborable = diasNoLaborables.find(dia => dia.fecha === selectedDate);
                if (diaNoLaborable) {
                    horaSelect.innerHTML = `<option value="">${diaNoLaborable.motivo || 'Día no laborable'}</option>`;
                    return;
                }

                // Obtener horarios ocupados
                let citasExistentes = [];
                try {
                    const url =
                        `/cliente/citas/horarios-ocupados?fecha=${selectedDate}${excludeCitaId ? `&exclude=${excludeCitaId}` : ''}`;
                    console.log('Consultando horarios ocupados:', url);

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error(`Error ${response.status}`);

                    const data = await response.json();
                    citasExistentes = data.horariosOcupados || [];

                    console.log('Horarios ocupados recibidos:', citasExistentes);
                } catch (error) {
                    console.error('Error al obtener horarios ocupados:', error);
                    // Continuar sin horarios ocupados
                }

                // Generar opciones de horario
                horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

                const horariosDia = horariosDisponibles.filter(h => h.dia_semana == dayOfWeekBackend);

                console.log('Horarios disponibles para día', dayOfWeekBackend, ':', horariosDia);

                if (horariosDia.length === 0) {
                    horaSelect.innerHTML = '<option value="">No hay horarios programados</option>';
                    return;
                }

                let horariosGenerados = 0;

                horariosDia.forEach(horario => {
                    const [inicioH, inicioM] = horario.hora_inicio.split(':').map(Number);
                    const [finH, finM] = horario.hora_fin.split(':').map(Number);

                    let horaActual = new Date();
                    horaActual.setHours(inicioH, inicioM, 0, 0);

                    const horaFin = new Date();
                    horaFin.setHours(finH, finM, 0, 0);

                    while (horaActual < horaFin) {
                        const horaStr = horaActual.getHours().toString().padStart(2, '0') + ':' +
                            horaActual.getMinutes().toString().padStart(2, '0');

                        // Verificar colisión con citas existentes
                        const estaOcupado = citasExistentes.some(cita => {
                            try {
                                const inicioCita = new Date(`${selectedDate}T${cita.hora_inicio}`);
                                const finCita = new Date(inicioCita.getTime() + (cita.duracion || 30) *
                                    60000);

                                const inicioPropuesta = new Date(`${selectedDate}T${horaStr}`);
                                const finPropuesta = new Date(inicioPropuesta.getTime() +
                                    calcularDuracionServiciosSeleccionados() * 60000);

                                return (
                                    (inicioPropuesta >= inicioCita && inicioPropuesta < finCita) ||
                                    (finPropuesta > inicioCita && finPropuesta <= finCita) ||
                                    (inicioPropuesta <= inicioCita && finPropuesta >= finCita)
                                );
                            } catch (e) {
                                console.error('Error al verificar colisión:', e);
                                return false;
                            }
                        });

                        const option = document.createElement('option');
                        option.value = horaStr;
                        option.textContent = horaStr;

                        if (estaOcupado) {
                            option.disabled = true;
                            option.textContent += ' (Ocupado)';
                            option.style.color = '#ff6b6b';
                        } else {
                            horariosGenerados++;
                        }

                        horaSelect.appendChild(option);
                        horaActual.setMinutes(horaActual.getMinutes() + 30);
                    }
                });

                if (horariosGenerados === 0 && horaSelect.options.length > 1) {
                    horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                }

                console.log(`Horarios cargados - Generados: ${horariosGenerados}`);

            } catch (error) {
                console.error('Error en loadAvailableHours:', error);
                horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
            }
        }

        // Configuracion del datepicker
        function setupDatePicker() {
            const fechaInput = document.getElementById('fecha');

            // Establecer fechas mínima y máxima correctamente
            const hoy = new Date();
            const unMesAdelante = new Date();
            unMesAdelante.setMonth(unMesAdelante.getMonth() + 1);

            fechaInput.min = getLocalDateString(hoy);
            fechaInput.max = getLocalDateString(unMesAdelante);

            console.log('Datepicker configurado:', {
                min: fechaInput.min,
                max: fechaInput.max,
                today: getLocalDateString(hoy)
            });

            fechaInput.addEventListener('change', function() {
                console.log('📅 Fecha cambiada:', this.value);

                if (!this.value) {
                    document.getElementById('hora').innerHTML = '<option value="">Seleccione una hora</option>';
                    return;
                }

                try {
                    const selectedDate = createLocalDate(this.value);
                    const dayOfWeekJS = selectedDate.getDay();

                    console.log('Fecha parseada:', selectedDate);
                    console.log('Día de la semana JS:', dayOfWeekJS);

                    // Validar domingos primero
                    if (dayOfWeekJS === 0) {
                        showDateError('Domingo no laborable',
                            'No trabajamos los domingos. Por favor selecciona otro día.');
                        this.value = '';
                        document.getElementById('hora').innerHTML = '<option value="">Seleccione una hora</option>';
                        return;
                    }

                    // Verificar días no laborables
                    const diaNoLaborable = diasNoLaborables.find(dia => dia.fecha === this.value);
                    if (diaNoLaborable) {
                        showDateError(
                            'Día no laborable',
                            `No se atienden citas el ${formatFechaBonita(selectedDate)}.<br>
                     <strong>Motivo:</strong> ${diaNoLaborable.motivo || 'Día no laborable'}`
                        );
                        this.value = '';
                        return;
                    }

                    //  ÚNICO LUGAR donde se cargan horarios - al cambiar fecha
                    const citaId = document.getElementById('form_cita_id')?.value;
                    loadAvailableHours(this.value, citaId);

                } catch (error) {
                    console.error('Error al procesar fecha:', error);
                    showDateError('Error', 'Fecha inválida. Por favor selecciona una fecha válida.');
                    this.value = '';
                }
            });
        }

        function calcularDuracionServiciosSeleccionados() {
            let total = 0;
            document.querySelectorAll('input[name="servicios[]"]:checked').forEach(checkbox => {
                const servicioId = checkbox.value;
                // Buscar el servicio en todos los servicios disponibles
                for (const categoria in todosServiciosDisponibles) {
                    const servicio = todosServiciosDisponibles[categoria].find(s => s.id == servicioId);
                    if (servicio) {
                        total += servicio.duracion_min;
                        break;
                    }
                }
            });
            return total || 30; // Default 30 mins si no hay selección
        }

        // Funcion para formatear fecha como YYYY-MM-DD (para input date)
        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Funcion para formatear fecha bonita (ej: "Lunes, 25 de Junio")
        function formatFechaBonita(date) {
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            };
            return date.toLocaleDateString('es-ES', options);
        }

        function showDateError(title, message) {
            swalWithBootstrapButtons.fire({
                title: title,
                html: message,
                icon: 'warning',
                confirmButtonColor: '#4facfe'
            });

            // Resetear seleccion
            document.getElementById('fecha').value = '';
            document.getElementById('hora').innerHTML = '<option value="">Seleccione una hora</option>';
        }

        // Función para cargar servicios según el tipo de vehículo seleccionado
        async function cargarServiciosPorTipo() {
            return new Promise(async (resolve, reject) => {
                try {
                    const vehiculoSelect = document.getElementById('vehiculo_id');
                    const serviciosContainer = document.getElementById('serviciosContainer');

                    if (!vehiculoSelect) {
                        console.error('Select de vehículo no encontrado');
                        return reject('Select de vehículo no encontrado');
                    }

                    if (!serviciosContainer) {
                        console.error('Container de servicios no encontrado');
                        return reject('Container de servicios no encontrado');
                    }

                    const selectedOption = vehiculoSelect.options[vehiculoSelect.selectedIndex];
                    const tipoVehiculo = selectedOption?.dataset.tipo?.toLowerCase();

                    if (!tipoVehiculo) {
                        serviciosContainer.innerHTML = '<p>Seleccione un vehículo primero</p>';
                        return resolve();
                    }

                    // Mostrar loading
                    serviciosContainer.innerHTML = '<p>Cargando servicios...</p>';

                    // Si no tenemos los servicios disponibles, cargarlos
                    if (!todosServiciosDisponibles || Object.keys(todosServiciosDisponibles).length === 0) {
                        console.log('Cargando servicios desde servidor...');
                        await loadInitialData();
                    }

                    // Filtrar servicios por tipo
                    const serviciosFiltrados = [];
                    for (const categoria in todosServiciosDisponibles) {
                        if (categoria.toLowerCase() === tipoVehiculo) {
                            serviciosFiltrados.push(...todosServiciosDisponibles[categoria]);
                        }
                    }

                    console.log('Servicios filtrados para', tipoVehiculo, ':', serviciosFiltrados);

                    if (serviciosFiltrados.length === 0) {
                        console.error('No se encontraron servicios para:', tipoVehiculo);
                        console.log('Todos los servicios disponibles:', todosServiciosDisponibles);
                        serviciosContainer.innerHTML =
                            '<p>No hay servicios disponibles para este tipo de vehículo</p>';
                        return resolve();
                    }

                    await renderServicios(serviciosFiltrados);
                    resolve();

                } catch (error) {
                    console.error('Error en cargarServiciosPorTipo:', error);
                    const serviciosContainer = document.getElementById('serviciosContainer');
                    if (serviciosContainer) {
                        serviciosContainer.innerHTML = '<p>Error al cargar servicios</p>';
                    }
                    reject(error);
                }
            });
        }

        // Función renderServicios 
        function renderServicios(servicios) {
            return new Promise((resolve, reject) => {
                try {
                    const container = document.getElementById('serviciosContainer');

                    if (!container) {
                        return reject('Container de servicios no encontrado');
                    }

                    container.innerHTML = '';

                    if (servicios.length === 0) {
                        container.innerHTML = '<p>No hay servicios disponibles para este tipo de vehículo</p>';
                        return resolve();
                    }

                    servicios.forEach(servicio => {
                        const servicioDiv = document.createElement('label');
                        servicioDiv.className = 'service-card';
                        servicioDiv.htmlFor = `servicio_${servicio.id}`;
                        servicioDiv.innerHTML = `
                    <input type="checkbox" id="servicio_${servicio.id}" name="servicios[]" value="${servicio.id}">
                    <div>
                        <h4>${servicio.nombre}</h4>
                        <p>$${servicio.precio.toFixed(2)} • ${formatDuration(servicio.duracion_min)}</p>
                        <p class="description">${servicio.descripcion || ''}</p>
                    </div>
                `;
                        container.appendChild(servicioDiv);

                        const checkbox = servicioDiv.querySelector('input');
                        if (checkbox) {
                            // SOLO cambiar la apariencia visual, NO recargar horarios
                            checkbox.addEventListener('change', function() {
                                servicioDiv.classList.toggle('selected', this.checked);

                                // Log para debug (opcional)
                                const duracionTotal = calcularDuracionServiciosSeleccionados();
                                console.log(
                                    `Servicio ${this.checked ? 'seleccionado' : 'deseleccionado'}: ${servicio.nombre}`
                                );
                                console.log(`Duración total actualizada: ${duracionTotal} minutos`);

                                // Opcional: Validar que la duración no exceda el horario laboral
                                const horaSelect = document.getElementById('hora');
                                if (horaSelect && horaSelect.value && this.checked) {
                                    validateServiceDuration(horaSelect.value, duracionTotal);
                                }
                            });
                        }
                    });
                    console.log(' Servicios renderizados exitosamente SIN recargar horarios:', servicios.length);
                    setTimeout(() => resolve(), 50);

                } catch (error) {
                    console.error('Error en renderServicios:', error);
                    reject(error);
                }
            });
        }


        // Función auxiliar para validar duración de servicios (opcional)
        function validateServiceDuration(horaSeleccionada, duracionTotal) {
            try {
                const [horas, minutos] = horaSeleccionada.split(':').map(Number);
                const horaInicio = new Date();
                horaInicio.setHours(horas, minutos, 0, 0);
                const horaFin = new Date(horaInicio.getTime() + duracionTotal * 60000);

                // Verificar si excede las 6:00 PM (18:00)
                if (horaFin.getHours() > 18 || (horaFin.getHours() === 18 && horaFin.getMinutes() > 0)) {
                    console.warn('⚠️ Los servicios seleccionados podrían exceder el horario laboral (6:00 PM)');

                    // Mostrar advertencia visual sutil (opcional)
                    const horaSelect = document.getElementById('hora');
                    if (horaSelect) {
                        horaSelect.style.borderColor = '#ffa500';
                        horaSelect.title = 'Los servicios seleccionados podrían exceder el horario laboral';

                        // Remover advertencia después de 3 segundos
                        setTimeout(() => {
                            horaSelect.style.borderColor = '';
                            horaSelect.title = '';
                        }, 3000);
                    }
                }
            } catch (error) {
                console.error('Error al validar duración:', error);
            }
        }

        function formatTime24to12(time24) {
            const [hours, minutes] = time24.split(':');
            const period = hours >= 12 ? 'PM' : 'AM';
            const hours12 = hours % 12 || 12;
            return `${hours12}:${minutes} ${period}`;
        }

        // Función para formatear duración
        function formatDuration(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;

            if (hours > 0) {
                return mins > 0 ? `${hours}h ${mins}min` : `${hours}h`;
            }
            return `${mins}min`;
        }

        async function setSelectedHourForEdit(hora24, maxAttempts = 5) {
            let attempts = 0;

            console.log('Configurando hora para edición:', hora24);

            while (attempts < maxAttempts) {
                const horaSelect = document.getElementById('hora');

                if (!horaSelect || horaSelect.options.length <= 1) {
                    console.log(`Esperando que se carguen las opciones de hora... intento ${attempts + 1}`);
                    await new Promise(resolve => setTimeout(resolve, 300));
                    attempts++;
                    continue;
                }

                // Buscar la hora en las opciones disponibles
                let horaEncontrada = false;
                for (let option of horaSelect.options) {
                    if (option.value === hora24 && !option.disabled) {
                        horaSelect.value = hora24;
                        horaEncontrada = true;
                        console.log(' Hora de edición configurada:', hora24);
                        return true;
                    }
                }

                // Si la hora no está disponible, agregarla (es una edición válida)
                if (!horaEncontrada && attempts === maxAttempts - 1) {
                    console.log('⚠️ Hora de edición no encontrada en opciones, agregando:', hora24);
                    const option = document.createElement('option');
                    option.value = hora24;
                    option.textContent = hora24 + ' (Horario actual)';
                    option.selected = true;
                    horaSelect.appendChild(option);
                    return true;
                }

                attempts++;
                await new Promise(resolve => setTimeout(resolve, 300));
            }

            console.error(' No se pudo configurar la hora para edición:', hora24);
            return false;
        }

        function cancelCita(citaId) {
            swalWithBootstrapButtons.fire({
                title: '¿Cancelar cita?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, mantener'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/cliente/citas/${citaId}/cancelar`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
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
                        .then(data => {
                            if (data.success) {
                                swalWithBootstrapButtons.fire({
                                    title: 'Cancelada',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    // Recargar solo si fue exitoso
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            let errorMsg = typeof error === 'string' ? error :
                                (error.message || 'Error al cancelar la cita');

                            swalWithBootstrapButtons.fire({
                                title: 'Error',
                                text: errorMsg,
                                icon: 'error'
                            });
                        });
                }
            });
        }

        // Función para editar citas
        async function editCita(citaId) {
            console.log('Elementos DOM disponibles:', {
                modal: !!document.getElementById('createCitaModal'),
                form: !!document.getElementById('citaForm'),
                vehiculo: !!document.getElementById('vehiculo_id'),
                fecha: !!document.getElementById('fecha'),
                hora: !!document.getElementById('hora'),
                formCitaId: !!document.getElementById('form_cita_id'),
                servicios: !!document.getElementById('serviciosContainer'),
                observaciones: !!document.getElementById('observaciones')
            });
            console.log(' Editando cita ID:', citaId);

            const swalInstance = swalWithBootstrapButtons.fire({
                title: 'Cargando cita...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                // 1. Obtener datos de la cita
                const response = await fetch(`/cliente/citas/${citaId}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Error ${response.status}`);
                }

                const data = await response.json();
                if (!data.success) throw new Error(data.message);

                swalInstance.close();

                // 2. Abrir modal limpio
                await openCitaModal();
                setModalMode(true); // Modo edición

                await new Promise(resolve => setTimeout(resolve, 300));

                // 3. Configurar formulario
                const form = document.getElementById('citaForm');
                const vehiculoSelect = document.getElementById('vehiculo_id');
                const fechaInput = document.getElementById('fecha');
                const formCitaId = document.getElementById('form_cita_id');
                const observacionesInput = document.getElementById('observaciones');

                if (!form) throw new Error('Formulario no encontrado');

                // Configurar para edición
                form.action = `/cliente/citas/${citaId}`;
                let methodInput = form.querySelector('[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PUT';

                if (formCitaId) formCitaId.value = citaId;

                // 4. Rellenar datos básicos
                if (vehiculoSelect && data.data.vehiculo_id) {
                    vehiculoSelect.value = data.data.vehiculo_id;
                }

                if (observacionesInput) {
                    observacionesInput.value = data.data.observaciones || '';
                }

                // 5. Cargar servicios por tipo de vehículo
                if (data.data.vehiculo_id) {
                    await cargarServiciosPorTipo();
                    await new Promise(resolve => setTimeout(resolve, 200));
                }

                // 6. Establecer fecha (esto disparará la carga de horarios)
                if (fechaInput && data.data.fecha) {
                    fechaInput.value = data.data.fecha;

                    // Simular evento change para cargar horarios
                    const changeEvent = new Event('change');
                    fechaInput.dispatchEvent(changeEvent);

                    // Esperar a que se carguen los horarios
                    await new Promise(resolve => setTimeout(resolve, 800));
                }

                // 7. Configurar hora (DESPUÉS de que se carguen los horarios)
                if (data.data.hora) {
                    await setSelectedHourForEdit(data.data.hora);
                }

                // 8. Seleccionar servicios
                if (data.data.cita && data.data.cita.servicios) {
                    data.data.cita.servicios.forEach(servicio => {
                        const checkbox = document.querySelector(
                            `input[name="servicios[]"][value="${servicio.id}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            const serviceCard = checkbox.closest('.service-card');
                            if (serviceCard) {
                                serviceCard.classList.add('selected');
                            }
                        }
                    });
                }

                console.log('✅ Cita cargada para edición exitosamente');

            } catch (error) {
                swalInstance.close();
                console.error('Error en editCita:', error);
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al cargar la cita',
                    icon: 'error'
                });
            }
        }

        // Función auxiliar para configurar la hora seleccionada con reintentos
        async function setSelectedHour(hora24, maxAttempts = 3) {
            let attempts = 0;

            while (attempts < maxAttempts) {
                const horaSelect = document.getElementById('hora');

                if (!horaSelect) {
                    console.error('Select de hora no encontrado, intento:', attempts + 1);
                    await new Promise(resolve => setTimeout(resolve, 200));
                    attempts++;
                    continue;
                }

                // Verificar si la hora ya existe en las opciones
                let horaEncontrada = false;
                for (let option of horaSelect.options) {
                    if (option.value === hora24) {
                        horaSelect.value = hora24;
                        horaEncontrada = true;
                        console.log('Hora configurada exitosamente:', hora24);
                        return; // Éxito
                    }
                }

                // Si no se encontró la hora, agregarla como disponible
                if (!horaEncontrada) {
                    const option = document.createElement('option');
                    option.value = hora24;
                    option.textContent = hora24;
                    option.selected = true;
                    horaSelect.appendChild(option);
                    horaSelect.value = hora24;
                    console.log('Hora agregada y configurada:', hora24);
                    return; // Éxito
                }

                attempts++;
                await new Promise(resolve => setTimeout(resolve, 300));
            }

            console.error('No se pudo configurar la hora después de', maxAttempts, 'intentos');
        }

        // Función auxiliar para seleccionar servicios con validación
        async function selectServices(servicios) {
            let attempts = 0;
            const maxAttempts = 5;

            while (attempts < maxAttempts) {
                let serviciosEncontrados = 0;

                servicios.forEach(servicio => {
                    const checkbox = document.querySelector(
                        `input[name="servicios[]"][value="${servicio.id}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const serviceCard = checkbox.closest('.service-card');
                        if (serviceCard) {
                            serviceCard.classList.add('selected');
                        }
                        serviciosEncontrados++;
                    }
                });

                if (serviciosEncontrados === servicios.length) {
                    console.log('Todos los servicios seleccionados exitosamente:', serviciosEncontrados);
                    return; // Éxito
                }

                console.log(
                    `Intento ${attempts + 1}: ${serviciosEncontrados}/${servicios.length} servicios encontrados`);
                attempts++;
                await new Promise(resolve => setTimeout(resolve, 200));
            }

            console.error('No se pudieron seleccionar todos los servicios después de', maxAttempts, 'intentos');
        }

        // Función para actualizar las secciones de citas
        async function updateCitasSections(tipo = 'próximas', citas = []) {
            try {
                // Si no se proporcionan citas, obtenerlas del servidor
                if (citas.length === 0) {
                    try {
                        const response = await fetch('/cliente/dashboard-data', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Error ${response.status}: ${response.statusText}`);
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Respuesta no es JSON');
                        }

                        const data = await response.json();

                        if (!data.success) {
                            throw new Error(data.message || 'Error en los datos recibidos');
                        }

                        citas = tipo === 'próximas' ? data.proximas_citas : data.historial_citas;

                        // NUEVO: Ordenar citas próximas por fecha (más cercana primero) si no vienen ya ordenadas
                        if (tipo === 'próximas' && citas.length > 0) {
                            citas.sort((a, b) => {
                                const fechaA = new Date(a.fecha_hora);
                                const fechaB = new Date(b.fecha_hora);
                                return fechaA - fechaB; // Ascendente: más cercana primero
                            });
                            console.log('📅 Citas próximas ordenadas de más cercana a más lejana');
                        }
                    } catch (error) {
                        console.error('Error al obtener datos de citas:', error);
                        await swalWithBootstrapButtons.fire({
                            title: 'Error',
                            text: 'No se pudieron cargar los datos actualizados. Recargando página...',
                            icon: 'error'
                        });
                        location.reload();
                        return;
                    }
                }

                const container = tipo === 'próximas' ?
                    document.querySelector('.card:first-child .card-body') :
                    document.querySelector('.card:nth-child(2) .card-body');

                if (!container) {
                    console.error('Contenedor de citas no encontrado');
                    return;
                }

                if (citas.length === 0) {
                    const emptyMessage = tipo === 'próximas' ?
                        'No tienes citas programadas en los próximos 15 días' :
                        'No hay historial de servicios';

                    const emptyDescription = tipo === 'próximas' ?
                        'Agenda una cita y aparecerá aquí si está dentro de los próximos 15 días' :
                        'Agenda tu primera cita para comenzar a ver tu historial';

                    container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-${tipo === 'próximas' ? 'calendar-alt' : 'history'}"></i>
                    <h3>${emptyMessage}</h3>
                    <p>${emptyDescription}</p>
                    ${tipo === 'próximas' ? `
                                                            <button onclick="openCitaModal()" class="btn btn-primary" style="margin-top: 15px;">
                                                                <i class="fas fa-calendar-plus"></i>
                                                                Agendar Cita
                                                            </button>` : ''}
                </div>
            `;
                    return;
                }

                let html = '';

                if (tipo === 'próximas') {
                    citas.forEach((cita, index) => {
                        // Calcular días restantes para la cita
                        const fechaCita = formatearFechaHoraFromServer(cita.fecha_hora);
                        const hoy = new Date();
                        const diasRestantes = Math.ceil((fechaCita - hoy) / (1000 * 60 * 60 * 24));

                        // CORREGIDO: Usar funciones de formateo seguras
                        const dia = obtenerDiaDelMes(cita.fecha_hora);
                        const mes = obtenerMesAbreviado(cita.fecha_hora);
                        const hora = formatearSoloHora(cita.fecha_hora);

                        // Determinar clase de urgencia basada en días restantes
                        let urgenciaClass = '';
                        let urgenciaText = '';

                        if (diasRestantes <= 1) {
                            urgenciaClass = 'urgent-soon';
                            urgenciaText = diasRestantes === 0 ? 'Hoy' : 'Mañana';
                        } else if (diasRestantes <= 3) {
                            urgenciaClass = 'urgent-close';
                            urgenciaText = `En ${diasRestantes} días`;
                        } else if (diasRestantes <= 7) {
                            urgenciaClass = 'coming-soon';
                            urgenciaText = `En ${diasRestantes} días`;
                        } else {
                            urgenciaText = `En ${diasRestantes} días`;
                        }

                        html += `
                <div class="next-appointment ${index === 0 ? 'highlighted' : ''} ${urgenciaClass}">
                    <div class="appointment-date-time">
                        <div class="date-badge">
                            <span class="day">${dia}</span>
                            <span class="month">${mes}</span>
                            ${diasRestantes <= 7 ? `<span class="days-remaining">${urgenciaText}</span>` : ''}
                        </div>
                        <div class="time-info">
                            <div class="time">${hora}</div>
                            <div class="service">
                                ${cita.servicios.map(s => s.nombre).join(', ')}
                            </div>
                            <div class="vehicle-info">
                                <i class="fas fa-car"></i> ${cita.vehiculo.marca} ${cita.vehiculo.modelo}
                            </div>
                            ${diasRestantes > 7 ? `<div class="days-info"><i class="fas fa-clock"></i> ${urgenciaText}</div>` : ''}
                        </div>
                        <span class="appointment-status status-${cita.estado.replace('_', '-')}">
                            ${cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1).replace('_', ' ')}
                        </span>
                    </div>
                    <div class="appointment-actions">
                        ${['pendiente', 'confirmada'].includes(cita.estado) ? `
                                                                <button class="btn btn-sm btn-warning" onclick="editCita(${cita.id})">
                                                                    <i class="fas fa-edit"></i> Modificar
                                                                </button>
                                                                <button class="btn btn-sm btn-outline" onclick="cancelCita(${cita.id})">
                                                                    <i class="fas fa-times"></i> Cancelar
                                                                </button>` : ''}
                    </div>
                </div>
                `;
                    });

                    // Agregar información sobre el filtro de 15 días
                    html += `
                <div style="text-align: center; margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 8px;">
                    <small style="color: #6c757d;">
                        <i class="fas fa-info-circle"></i>
                        Se muestran solo las citas de los próximos 15 días
                    </small>
                </div>
            `;

                    if (citas.length > 3) {
                        html += `
                <div style="text-align: center; margin-top: 15px;">
                    <a href="{{ route('cliente.citas') }}" class="btn btn-outline">
                        <i class="fas fa-list"></i> Ver todas las citas
                    </a>
                </div>
                `;
                    }
                } else { // Historial
                    citas.forEach(cita => {
                        // CORREGIDO: Usar función de formateo segura
                        const fechaCompleta = formatearFechaCompleta(cita.fecha_hora);
                        const total = cita.servicios.reduce((sum, servicio) => sum + servicio.precio, 0);

                        html += `
                <div class="service-history-item">
                    <div class="service-icon">
                        <i class="fas fa-soap"></i>
                    </div>
                    <div class="service-details">
                        <h4>${cita.servicios.map(s => s.nombre).join(', ')}</h4>
                        <p><i class="fas fa-calendar"></i> ${fechaCompleta}</p>
                        <p><i class="fas fa-car"></i> ${cita.vehiculo.marca} ${cita.vehiculo.modelo} - ${cita.vehiculo.placa}</p>
                        <span class="appointment-status status-${cita.estado.replace('_', '-')}" style="display: inline-block; margin-top: 5px;">
                            ${cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1).replace('_', ' ')}
                        </span>
                        ${cita.estado === 'finalizada' ? `
                                                                <a href="#" class="repeat-service" onclick="repeatService(${cita.id})">
                                                                    <i class="fas fa-redo"></i> Volver a agendar
                                                                </a>` : ''}
                    </div>
                    <div class="service-price">
                        ${total.toFixed(2)}
                    </div>
                </div>
                `;
                    });
                }

                container.innerHTML = html;

                console.log(`✅ Sección de citas "${tipo}" actualizada correctamente con ${citas.length} elementos`);

            } catch (error) {
                console.error('Error al actualizar secciones de citas:', error);
                await swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Ocurrió un problema al actualizar la vista. Recargando página...',
                    icon: 'error'
                });
                location.reload();
            }
        }
        // Función para repetir servicio desde el historial
        function repeatService(citaId) {
            fetch(`/cliente/citas/${citaId}/repeat`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Rellenar el modal con los datos de la cita anterior
                        document.getElementById('vehiculo_id').value = data.vehiculo_id;
                        cargarServiciosPorTipo().then(() => {
                            // Seleccionar los servicios anteriores
                            data.servicios.forEach(servicioId => {
                                const checkbox = document.getElementById(`servicio_${servicioId}`);
                                if (checkbox) checkbox.checked = true;
                            });

                            // Abrir el modal
                            openCitaModal();

                            swalWithBootstrapButtons.fire({
                                title: 'Servicio cargado',
                                text: 'Hemos cargado los detalles de tu cita anterior. Por favor revisa y confirma la nueva fecha.',
                                icon: 'info',
                                confirmButtonColor: '#4facfe'
                            });
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    swalWithBootstrapButtons.fire({
                        title: 'Error',
                        text: error.message || 'No se pudo cargar la cita anterior',
                        icon: 'error'
                    });
                });
        }

        async function generateAvailableTimesFromOccupied(fecha, horariosOcupados) {
            try {
                const fechaDate = new Date(fecha);
                const dayOfWeek = fechaDate.getDay();

                // Obtener horarios programados para este día
                const horariosDia = horariosDisponibles.filter(h => h.dia_semana == dayOfWeek);
                if (horariosDia.length === 0) return [];

                let disponibles = [];

                horariosDia.forEach(horario => {
                    const [inicioH, inicioM] = horario.hora_inicio.split(':').map(Number);
                    const [finH, finM] = horario.hora_fin.split(':').map(Number);

                    let horaActual = new Date();
                    horaActual.setHours(inicioH, inicioM, 0, 0);

                    const horaFin = new Date();
                    horaFin.setHours(finH, finM, 0, 0);

                    while (horaActual < horaFin) {
                        const horaStr = horaActual.getHours().toString().padStart(2, '0') + ':' +
                            horaActual.getMinutes().toString().padStart(2, '0');

                        // Verificar si está ocupado
                        const estaOcupado = horariosOcupados.some(cita => {
                            try {
                                const inicioCita = new Date(`${fecha}T${cita.hora_inicio}`);
                                const finCita = new Date(inicioCita.getTime() + (cita.duracion || 30) *
                                    60000);
                                const inicioPropuesta = new Date(`${fecha}T${horaStr}`);
                                const finPropuesta = new Date(inicioPropuesta.getTime() + 30 * 60000);

                                return (
                                    (inicioPropuesta >= inicioCita && inicioPropuesta < finCita) ||
                                    (finPropuesta > inicioCita && finPropuesta <= finCita) ||
                                    (inicioPropuesta <= inicioCita && finPropuesta >= finCita)
                                );
                            } catch (e) {
                                return false;
                            }
                        });

                        if (!estaOcupado) {
                            disponibles.push(horaStr);
                        }

                        horaActual.setMinutes(horaActual.getMinutes() + 30);
                    }
                });

                return disponibles;
            } catch (error) {
                console.error('Error generando horarios disponibles:', error);
                return [];
            }
        }

        // FUNCIÓN para convertir día de JavaScript a formato backend
        function getBackendDayFromJSDay(jsDay) {
            // JavaScript: 0=Domingo, 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado
            // Backend: 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado, 7=Domingo

            if (jsDay === 0) {
                return 7; // Domingo
            }
            return jsDay; // Lunes=1, Martes=2, etc.
        }

        // FUNCIÓN para obtener fecha en timezone local
        function getLocalDateString(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // FUNCIÓN para crear fecha desde string sin problemas de timezone
        function createLocalDate(dateString) {
            const [year, month, day] = dateString.split('-').map(Number);
            return new Date(year, month - 1, day); // month - 1 porque los meses en JS van de 0-11
        }

        /**
         * Formatea una fecha/hora del servidor para mostrar correctamente
         * Maneja tanto timestamps como strings de fecha
         */
        function formatearFechaHoraFromServer(fechaHora) {
            try {
                let fecha;

                if (typeof fechaHora === 'string') {
                    // Si viene como string del servidor (formato: "2025-08-13 15:30:00" o ISO)
                    if (fechaHora.includes('T')) {
                        // Formato ISO: remover timezone para evitar conversión
                        fecha = new Date(fechaHora.split('T')[0] + 'T' + fechaHora.split('T')[1].split('.')[0]);
                    } else {
                        // Formato "YYYY-MM-DD HH:mm:ss" - crear fecha local
                        const [datePart, timePart] = fechaHora.split(' ');
                        const [year, month, day] = datePart.split('-').map(Number);
                        const [hour, minute] = (timePart || '00:00').split(':').map(Number);
                        fecha = new Date(year, month - 1, day, hour, minute);
                    }
                } else {
                    // Si ya es un objeto Date
                    fecha = new Date(fechaHora);
                }

                return fecha;
            } catch (error) {
                console.error('Error al formatear fecha del servidor:', error, fechaHora);
                return new Date(); // Fallback a fecha actual
            }
        }

        /**
         * Formatea solo la fecha (sin hora)
         */
        function formatearSoloFecha(fechaHora, opciones = {}) {
            const fecha = formatearFechaHoraFromServer(fechaHora);

            const opcionesDefault = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };

            return fecha.toLocaleDateString('es-ES', {
                ...opcionesDefault,
                ...opciones
            });
        }
        /**
         * Formatea solo la hora
         */
        function formatearSoloHora(fechaHora) {
            const fecha = formatearFechaHoraFromServer(fechaHora);
            return fecha.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        /**
         * Formatea fecha completa (fecha + hora)
         */
        function formatearFechaCompleta(fechaHora) {
            const fecha = formatearFechaHoraFromServer(fechaHora);
            return fecha.toLocaleString('es-ES', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        /**
         * Obtiene solo el día del mes
         */
        function obtenerDiaDelMes(fechaHora) {
            const fecha = formatearFechaHoraFromServer(fechaHora);
            return fecha.getDate();
        }

        /**
         * Obtiene el mes abreviado
         */
        function obtenerMesAbreviado(fechaHora) {
            const fecha = formatearFechaHoraFromServer(fechaHora);
            return fecha.toLocaleDateString('es-ES', {
                month: 'short'
            });
        }

        // Manejar envío del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const citaForm = document.getElementById('citaForm');

            if (citaForm) {
                citaForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Validar que al menos un servicio esté seleccionado
                    const serviciosSeleccionados = document.querySelectorAll(
                        'input[name="servicios[]"]:checked');
                    if (serviciosSeleccionados.length === 0) {
                        swalWithBootstrapButtons.fire('Error', 'Debes seleccionar al menos un servicio',
                            'error');
                        return;
                    }

                    const isEdit = document.getElementById('form_cita_id').value;

                    // Mostrar loader
                    const swalInstance = swalWithBootstrapButtons.fire({
                        title: isEdit ? 'Actualizando cita...' : 'Procesando cita...',
                        html: isEdit ? 'Estamos actualizando tu cita, por favor espera' :
                            'Estamos reservando tu cita, por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const form = this;
                    const formData = new FormData(form);

                    // IMPORTANTE: Agregar el ID de la cita si es edición
                    if (isEdit) {
                        formData.append('cita_id', isEdit);
                    }

                    // Configurar método HTTP correcto
                    const method = isEdit ? 'PUT' : 'POST';

                    // Para PUT necesitamos agregar _method
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }

                    console.log('Enviando formulario:', {
                        url: form.action,
                        method: method,
                        isEdit: isEdit,
                        citaId: isEdit
                    });

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST', // Siempre POST, Laravel maneja _method
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        const result = await response.json();
                        await swalInstance.close();

                        if (!response.ok) {
                            throw new Error(result.message || 'Error al procesar la cita');
                        }

                        // Éxito
                        closeCitaModal();

                        await swalWithBootstrapButtons.fire({
                            title: isEdit ? '¡Cita actualizada!' : '¡Cita agendada!',
                            html: `
                    <div style="text-align: left; margin-top: 15px;">
                        <p><strong>Fecha:</strong> ${new Date(result.data.fecha_hora).toLocaleDateString('es-ES', { 
                            weekday: 'long', 
                            day: 'numeric', 
                            month: 'long', 
                            year: 'numeric' 
                        })}</p>
                        <p><strong>Hora:</strong> ${new Date(result.data.fecha_hora).toLocaleTimeString('es-ES', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        })}</p>
                        <p><strong>Servicios:</strong> ${result.data.servicios_nombres}</p>
                        <p><strong>Vehículo:</strong> ${result.data.vehiculo_marca} ${result.data.vehiculo_modelo}</p>
                        ${result.data.vehiculo_placa ? `<p><strong>Placa:</strong> ${result.data.vehiculo_placa}</p>` : ''}
                    </div>
                `,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });

                        await updateCitasSections();

                    } catch (error) {
                        console.error('Error:', error);
                        await swalInstance.close();

                        let errorMessage = 'Ocurrió un error al procesar tu cita.';
                        let errorDetails = '';
                        let showAvailableTimes = false;
                        let availableTimes = [];

                        if (error.message) {
                            if (typeof error.message === 'string') {
                                errorMessage = error.message;

                                if (error.message.includes('No atendemos domingos')) {
                                    errorMessage =
                                        'No trabajamos los domingos. Por favor selecciona otro día.';
                                    await swalWithBootstrapButtons.fire({
                                        title: 'Domingo no laborable',
                                        text: errorMessage,
                                        icon: 'warning',
                                        confirmButtonColor: '#4facfe'
                                    });
                                    return;
                                } else if (error.message.includes('horario ya está ocupado') ||
                                    error.message.includes('horario seleccionado está ocupado')) {
                                    errorMessage =
                                        'Lo sentimos, ese horario ya está ocupado. Por favor selecciona otro horario.';
                                    showAvailableTimes = true;

                                    const fecha = document.getElementById('fecha').value;
                                    const citaId = document.getElementById('form_cita_id').value;
                                    if (fecha) {
                                        try {
                                            const url =
                                                `/cliente/citas/horarios-ocupados?fecha=${fecha}${citaId ? `&exclude=${citaId}` : ''}`;
                                            const response = await fetch(url);
                                            const data = await response.json();

                                            // Extraer horarios disponibles de la respuesta
                                            if (data.horariosOcupados) {
                                                // Generar horarios disponibles
                                                availableTimes =
                                                    await generateAvailableTimesFromOccupied(fecha, data
                                                        .horariosOcupados);
                                            }
                                        } catch (err) {
                                            console.error('Error al obtener horarios disponibles:',
                                                err);
                                        }
                                    }
                                }
                            } else if (error.message.message) {
                                errorMessage = error.message.message;
                                if (error.message.errors) {
                                    errorDetails = Object.values(error.message.errors).join('<br>');
                                }
                            }
                        }

                        const errorHtml = `
                <div style="text-align: left;">
                    <p>${errorMessage}</p>
                    ${errorDetails ? `<p style="color: #dc3545; margin-top: 10px;">${errorDetails}</p>` : ''}
                    ${showAvailableTimes && availableTimes.length > 0 ? `
                                                                                                                            <p style="margin-top: 10px;"><strong>Horarios disponibles:</strong></p>
                                                                                                                            <ul style="margin-top: 5px; max-height: 150px; overflow-y: auto;">
                                                                                                                                ${availableTimes.map(time => `<li>${time}</li>`).join('')}
                                                                                                                            </ul>
                                                                                                                        ` : ''}
                    <p style="margin-top: 10px; font-size: 0.9em; color: #666;">
                        Por favor intenta nuevamente con un horario diferente.
                    </p>
                </div>
            `;

                        await swalWithBootstrapButtons.fire({
                            title: isEdit ? 'Error al actualizar' : 'Error al agendar',
                            html: errorHtml,
                            icon: 'error',
                            confirmButtonColor: '#ff6b6b'
                        });
                    }
                });
            }
        });

        // Script para debug - funciones para probar el manejo de fechas
        async function debugFechas(fechaStr = null) {
            const hoy = new Date();
            const fechaTest = fechaStr || getLocalDateString(hoy);

            console.group('🔍 DEBUG DE FECHAS');
            console.log('📅 Fecha de prueba:', fechaTest);

            // Test 1: Crear fecha local
            const fechaLocal = createLocalDate(fechaTest);
            console.log('📅 Fecha local creada:', fechaLocal);
            console.log('📅 getDay() (JS):', fechaLocal.getDay(), '- Nombre:', fechaLocal.toLocaleDateString('es-ES', {
                weekday: 'long'
            }));
            console.log('📅 Día backend convertido:', getBackendDayFromJSDay(fechaLocal.getDay()));

            // Test 2: Verificar con servidor
            try {
                const response = await fetch(`/cliente/debug-fechas?fecha=${fechaTest}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('🗄️ Información del servidor:', data);

                    // Comparar
                    console.log('🔄 COMPARACIÓN:');
                    console.log('   JS dayOfWeek:', fechaLocal.getDay());
                    console.log('   Servidor dayOfWeek (JS format):', data.dia_semana_js);
                    console.log('   JS convertido a backend:', getBackendDayFromJSDay(fechaLocal.getDay()));
                    console.log('   Servidor dayOfWeekIso:', data.dia_semana_iso);
                    console.log('   ✅ Coinciden?', getBackendDayFromJSDay(fechaLocal.getDay()) === data.dia_semana_iso);

                    // Mostrar horarios disponibles
                    if (data.horarios_coincidentes && data.horarios_coincidentes.length > 0) {
                        console.log('⏰ Horarios disponibles:', data.horarios_coincidentes);
                    } else {
                        console.log('❌ No hay horarios para este día');
                    }
                }
            } catch (error) {
                console.error('❌ Error al consultar servidor:', error);
            }

            console.groupEnd();
        }

        // Test automático para los próximos 7 días
        async function testProximos7Dias() {
            console.group('🧪 TEST PRÓXIMOS 7 DÍAS');

            for (let i = 0; i < 7; i++) {
                const fecha = new Date();
                fecha.setDate(fecha.getDate() + i);
                const fechaStr = getLocalDateString(fecha);

                console.log(`\n--- DÍA ${i + 1}: ${fechaStr} ---`);
                await debugFechas(fechaStr);

                // Pequeña pausa para no saturar
                await new Promise(resolve => setTimeout(resolve, 100));
            }

            console.groupEnd();
        }

        // Función para test rápido en consola
        function testFechaRapido() {
            const hoy = new Date();
            console.log('Hoy es:', hoy.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            }));
            console.log('getDay():', hoy.getDay());
            console.log('Convertido a backend:', getBackendDayFromJSDay(hoy.getDay()));

            // Test crear fecha desde string
            const fechaStr = getLocalDateString(hoy);
            const fechaRecreada = createLocalDate(fechaStr);
            console.log('Fecha string:', fechaStr);
            console.log('Fecha recreada:', fechaRecreada);
            console.log('¿Son el mismo día?',
                hoy.getDate() === fechaRecreada.getDate() &&
                hoy.getMonth() === fechaRecreada.getMonth() &&
                hoy.getFullYear() === fechaRecreada.getFullYear()
            );
        }

        // Auto-ejecutar test básico cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Sistema de fechas cargado');

            // Test básico
            setTimeout(() => {
                console.log('\n🔧 Ejecutando test básico de fechas...');
                testFechaRapido();
            }, 1000);
        });

        // Exponer funciones globalmente para uso en consola
        window.debugFechas = debugFechas;
        window.testProximos7Dias = testProximos7Dias;
        window.testFechaRapido = testFechaRapido;

        /*=========================================================
            FUNCIONAMIENTO DE PERFIL DEL CLIENTE
            =========================================================*/

        // Funciones del modal
        function openEditModal() {
            const modal = document.getElementById('editProfileModal');
            if (modal) {
                modal.style.display = 'block';
                document.getElementById('modalNombre')?.focus();
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editProfileModal');
            if (modal) modal.style.display = 'none';
        }

        // Manejo del formulario AJAX con validaciones
        document.getElementById('profileForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Obtener valores
            const nombre = document.getElementById('modalNombre').value.trim();
            const telefono = document.getElementById('modalTelefono').value.trim();

            // Validaciones
            if (!nombre) {
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'El nombre es requerido',
                    icon: 'error'
                });
                document.getElementById('modalNombre').focus();
                return;
            }

            if (!telefono) {
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'El teléfono es requerido',
                    icon: 'error'
                });
                document.getElementById('modalTelefono').focus();
                return;
            }

            // Validación estricta: exactamente 8 dígitos
            const telefonoRegex = /^\d{8}$/;
            if (!telefonoRegex.test(telefono)) {
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'El teléfono debe tener exactamente 8 dígitos numéricos',
                    icon: 'error'
                });
                document.getElementById('modalTelefono').focus();
                return;
            }

            try {
                const response = await fetch('{{ route('perfil.update-ajax') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        telefono: telefono,
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error en la respuesta del servidor');
                }

                // Éxito - Cerrar modal y actualizar UI
                closeEditModal();
                swalWithBootstrapButtons.fire({
                    title: '¡Éxito!',
                    text: data.message || 'Perfil actualizado correctamente',
                    icon: 'success'
                });

                // Actualizar la UI
                if (document.querySelector('.profile-info h3')) {
                    document.querySelector('.profile-info h3').textContent = nombre;
                }
                if (document.querySelector('.profile-info p:nth-of-type(2)')) {
                    document.querySelector('.profile-info p:nth-of-type(2)').innerHTML =
                        `<i class="fas fa-phone"></i> ${telefono}`;
                }
                if (document.querySelector('.welcome-section h1')) {
                    document.querySelector('.welcome-section h1').textContent = `¡Hola, ${nombre}!`;
                }

            } catch (error) {
                console.error('Error:', error);
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: error.message || 'Error al actualizar el perfil',
                    icon: 'error'
                });
            }
        });

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeEditModal();
                closeCitaModal();
            }
        });


        // Función para generar recibo
        function generateReceipt(citaId) {
            fetch(`/citas/${citaId}/recibo`)
                .then(response => response.json())
                .then(data => {
                    const receiptContent = document.getElementById('receiptContent');
                    receiptContent.innerHTML = `
                        <div style="text-align: center; margin-bottom: 20px;">
                            <h2 style="color: #4facfe;">Carwash Berríos</h2>
                            <p>Recibo de Servicio</p>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <p><strong>Fecha:</strong> ${data.fecha}</p>
                            <p><strong>Cliente:</strong> ${data.cliente}</p>
                            <p><strong>Vehículo:</strong> ${data.vehiculo}</p>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <thead>
                                <tr style="background: #f1f3f4;">
                                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Servicio</th>
                                    <th style="text-align: right; padding: 8px; border-bottom: 1px solid #ddd;">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.servicios.map(servicio => `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">${servicio.nombre}</td>                                                                                                                                                <td style="text-align: right; padding: 8px; border-bottom: 1px solid #ddd;">$${servicio.precio.toFixed(2)}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    `).join('')}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="padding: 8px; text-align: right; font-weight: bold;">Total:</td>
                                    <td style="text-align: right; padding: 8px; font-weight: bold;">$${data.total.toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px; text-align: right;">Estado:</td>
                                    <td style="text-align: right; padding: 8px;">
                                        <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                            ${data.estado.toUpperCase()}
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="text-align: center; margin-top: 30px; font-size: 0.9rem; color: #666;">
                            <p>¡Gracias por su preferencia!</p>
                            <p>Recibo #${data.id}</p>
                        </div>
                    `;

                    document.getElementById('receiptModal').style.display = 'block';
                });
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').style.display = 'none';
        }

        function printReceipt() {
            const printContent = document.getElementById('receiptContent').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        function downloadReceipt() {
            // Aquí iría la lógica para generar y descargar el PDF
            alert('Descargando recibo como PDF...');
        }

        /*=========================================================
        FUNCIONAMIENTO DE INTERACTIVIDAD Y ANIMACIONES
        =========================================================*/

        // Simulación de interactividad
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de entrada para las cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Efecto hover mejorado para service cards
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Marcas notificaciones como leídas al hacer clic
            const notifications = document.querySelectorAll('.notification-item.unread');
            notifications.forEach(notification => {
                notification.addEventListener('click', function() {
                    this.classList.remove('unread');
                    this.classList.add('read');
                });
            });

            // Efecto de pulsación para botones
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Crear efecto ripple
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>

    <script>
        /*=========================================================
                                                                                                                                                                                                                                                                            FUNCIONAMIENTO DE MODAL VEHICULOS
                                                                                                                                                                                                                                                                            =========================================================*/
        function openVehiculoModal() {
            document.getElementById('vehiculoModal').style.display = 'block';
        }

        function closeVehiculoModal() {
            document.getElementById('vehiculoModal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('vehiculoModal');
            const openBtn = document.getElementById('openVehiculoBtn');
            const closeBtn = modal?.querySelector('.close-modal');

            openBtn?.addEventListener('click', openVehiculoModal);
            closeBtn?.addEventListener('click', closeVehiculoModal);

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeVehiculoModal();
                }
            });
        });
    </script>


    @push('scripts')
        <script>
            /*=========================================================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                FUNCIONAMIENTO DE CRUD VEHICULOS
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                =========================================================*/
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('vehiculoForm');
                form?.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    try {
                        const resp = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        const data = await resp.json();
                        if (!resp.ok) throw new Error(data.message || 'Error');

                        localStorage.setItem('vehiculoActualizado', Date.now());
                        form.reset();
                        closeVehiculoModal();
                        await actualizarMisVehiculos();
                        swalWithBootstrapButtons.fire({
                            title: '¡Éxito!',
                            text: 'Vehículo guardado correctamente',
                            icon: 'success'
                        });
                    } catch (error) {
                        swalWithBootstrapButtons.fire({
                            title: 'Error',
                            text: error.message || 'Error al guardar el vehículo',
                            icon: 'error'
                        });
                    }
                });

                window.addEventListener('storage', function(e) {
                    if (e.key === 'vehiculoActualizado') {
                        actualizarMisVehiculos();
                    }
                });
            });

            async function actualizarMisVehiculos() {
                try {
                    const response = await fetch('{{ route('cliente.mis-vehiculos-ajax') }}');
                    const data = await response.json();
                    const container = document.getElementById('misVehiculosContainer');
                    if (!container) return;

                    if (data.vehiculos.length > 0) {
                        container.innerHTML = '';
                        data.vehiculos.forEach(v => {
                            let icon = 'car';
                            if (v.tipo === 'pickup') icon = 'truck-pickup';
                            else if (v.tipo === 'camion') icon = 'truck';
                            else if (v.tipo === 'moto') icon = 'motorcycle';

                            container.innerHTML += `
                            <div class="service-history-item" style="margin-bottom: 15px;">
                                <div class="service-icon" style="background: var(--secondary-gradient);">
                                    <i class="fas fa-${icon}"></i>
                                </div>
                                <div class="service-details">
                                    <h4>${v.marca ?? ''} ${v.modelo ?? ''}</h4>
                                    <p><i class="fas fa-palette"></i> ${v.color ?? ''}</p>
                                    <p><i class="fas fa-id-card"></i> ${v.placa}</p>
                                </div>
                                <a href='{{ route('cliente.citas') }}' class="btn btn-sm btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                            </div>`;
                        });
                    } else {
                        container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-car"></i>
                            <h3>No tienes vehículos registrados</h3>
                            <p>Agrega tu primer vehículo para comenzar a agendar citas</p>
                        </div>`;
                    }
                } catch (err) {
                    console.error('Error al actualizar vehiculos', err);
                }
            }
        </script>
    @endpush



    <style>
        /* Efecto ripple para botones */
        .btn {
            overflow: hidden;
            position: relative;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
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

        .skeleton-loading {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
        }

        .skeleton-card {
            background: #f0f0f0;
            border-radius: 10px;
            height: 120px;
            position: relative;
            overflow: hidden;
        }

        .skeleton-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            100% {
                left: 100%;
            }
        }

        /* Mejoras adicionales de hover */
        .service-history-item:hover {
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.15);
        }

        .notification-item:hover {
            background: linear-gradient(45deg, #4facfe05, #00f2fe05) !important;
            cursor: pointer;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
    </style>
</body>

</html>
