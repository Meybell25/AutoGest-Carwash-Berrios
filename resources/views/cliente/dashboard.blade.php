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
            gap: 40px;
            margin-bottom: 35px;
            padding: 0 10px;
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
            padding-right: 10px;
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
            background: linear-gradient(135deg, #667eea20, #764ba220) !important;
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #66bb6a !important;
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
            background: linear-gradient(135deg, #81c784, #66bb6a) !important;
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

        .restriction-alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease;
        }

        /* Estilos para campos bloqueados (usar !important para asegurar) */
        .campo-bloqueado {
            background-color: #f8f9fa !important;
            color: #666 !important;
            border-color: #ddd !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }

        /* Para select también */
        .campo-bloqueado select {
            background-color: #f8f9fa !important;
            color: #666 !important;
            cursor: not-allowed !important;
        }

        /* Para inputs readonly */
        input[readonly].campo-bloqueado {
            background-color: #f8f9fa !important;
        }

        .badge.bg-warning.text-dark {
            font-size: 0.65em;
            vertical-align: middle;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .appointment-status.status-pendiente {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2) !important;
            color: #ef6c00 !important;
            border: 1px solid #ffcc80 !important;
        }

        .appointment-status.status-confirmado,
        .appointment-status.status-confirmada {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc) !important;
            color: #0277bd !important;
            border: 1px solid #81d4fa !important;
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

        .appointment-status.status-pendiente:hover {
            background: linear-gradient(135deg, #ffe0b2, #ffcc80) !important;
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

        .service-history-item.finalizada {
            border-left: 4px solid #2e7d32;
            background-color: rgba(46, 125, 50, 0.05);
        }

        .service-history-item.cancelada {
            border-left: 4px solid #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }

        .status-finalizada {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9) !important;
            color: #2e7d32 !important;
            border: 1px solid #a5d6a7 !important;
        }

        .status-cancelada {
            background: linear-gradient(135deg, #fde7f3, #f8bbd9) !important;
            color: #ad1457 !important;
            border: 1px solid #f48fb1 !important;
        }

        .service-icon.status-finalizada {
            background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
            color: white !important;
        }

        .service-icon.status-cancelada {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            color: white !important;
        }

        .service-icon.status-finalizada:hover {
            background: linear-gradient(135deg, #1b5e20, #2e7d32) !important;
            color: white !important;
        }

        .service-icon.status-cancelada:hover {
            background: linear-gradient(135deg, #c82333, #dc3545) !important;
            color: white !important;
        }

        .service-icon {
            background: var(--secondary-gradient);
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

        /*Facturas y recibos*/
        .stats-mini {
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-mini {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        /* Responsive */
        @media (max-width: 768px) {
            .stats-mini {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }


        @media (max-width: 480px) {
            .stats-mini {
                grid-template-columns: 1fr;
            }
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

        /* Estilo para input de fecha cuando es no laborable */
        input[type="date"].dia-no-laborable {
            border-color: #dc3545;
            background-color: #fff5f5;
            animation: shake 0.5s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Tooltip para días no laborables */
        .dia-no-laborable-tooltip {
            position: relative;
            display: inline-block;
        }

        .dia-no-laborable-tooltip::after {
            content: "⚠️ Día no laborable";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
        }

        /* Estilos para indicadores de urgencia en las citas próximas */
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

        .days-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .days-info i {
            margin-right: 5px;
        }

        /* ===== ESTILOS BASE PARA CITAS ===== */
        /* Citas pendientes (estilo base) */
        .next-appointment.pendiente {
            border-left: 5px solid #4facfe !important;
            /* Borde azul */
        }

        .next-appointment.pendiente .date-badge {
            background: var(--secondary-gradient) !important;
            /* Fondo azul/gradiente */
        }

        /* Citas confirmadas base (verde) */
        .next-appointment.confirmada,
        .next-appointment.confirmado {
            border-left: 5px solid #66bb6a !important;
            /* Verde base */
            background-color: rgba(102, 187, 106, 0.05);
        }

        .next-appointment.confirmada .date-badge,
        .next-appointment.confirmado .date-badge {
            background: linear-gradient(135deg, #81c784, #66bb6a) !important;
            /* Verde base */
        }

        /* URGENCIA LEVEL 1: Hoy/Mañana (Rojo) */
        .next-appointment.confirmada.urgent-soon,
        .next-appointment.confirmado.urgent-soon {
            border-left: 5px solid #dc3545 !important;
            /* Rojo */
            background-color: rgba(220, 53, 69, 0.08) !important;
            box-shadow: 0 4px 20px rgba(220, 53, 69, 0.15) !important;
        }

        .next-appointment.confirmada.urgent-soon .date-badge,
        .next-appointment.confirmado.urgent-soon .date-badge {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            /* Rojo */
            animation: urgentPulse 2s infinite;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4) !important;
        }

        /* URGENCIA LEVEL 2: 2-3 días (Naranja) */
        .next-appointment.confirmada.urgent-close,
        .next-appointment.confirmado.urgent-close {
            border-left: 5px solid #fd7e14 !important;
            /* Naranja */
            background-color: rgba(253, 126, 20, 0.06) !important;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.12) !important;
        }

        .next-appointment.confirmada.urgent-close .date-badge,
        .next-appointment.confirmado.urgent-close .date-badge {
            background: linear-gradient(135deg, #fd7e14, #e55100) !important;
            /* Naranja */
            box-shadow: 0 3px 12px rgba(253, 126, 20, 0.3) !important;
        }

        /* URGENCIA LEVEL 3: 4-7 días (Amarillo) */
        .next-appointment.confirmada.coming-soon,
        .next-appointment.confirmado.coming-soon {
            border-left: 5px solid #ffc107 !important;
            /* Amarillo */
            background-color: rgba(255, 193, 7, 0.05) !important;
            box-shadow: 0 3px 12px rgba(255, 193, 7, 0.1) !important;
        }

        .next-appointment.confirmada.coming-soon .date-badge,
        .next-appointment.confirmado.coming-soon .date-badge {
            background: linear-gradient(135deg, #ffc107, #ff8f00) !important;
            /* Amarillo */
            color: #333 !important;
            /* Texto más oscuro para mejor legibilidad */
            box-shadow: 0 3px 10px rgba(255, 193, 7, 0.25) !important;
        }

        /* Animación para citas muy urgentes */
        @keyframes urgentPulse {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            }

            50% {
                transform: scale(1.03);
                box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            }
        }

        /* Hover effects mejorados para cada nivel de urgencia */
        .next-appointment.confirmada.urgent-soon:hover,
        .next-appointment.confirmado.urgent-soon:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.25) !important;
        }

        .next-appointment.confirmada.urgent-close:hover,
        .next-appointment.confirmado.urgent-close:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(253, 126, 20, 0.2) !important;
        }

        .next-appointment.confirmada.coming-soon:hover,
        .next-appointment.confirmado.coming-soon:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 193, 7, 0.15) !important;
        }


        .service-card.selected {
            background-color: #e7f3ff;
            border-color: #4facfe;
            box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.3);
        }

        /* Animación de pulso para citas urgentes confirmadas */
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

        /* Responsive: ajustes para móviles */
        @media (max-width: 768px) {
            .date-badge .days-remaining {
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


        /* Mejorar el estilo del contenedor de información */
        .next-appointment .appointment-date-time {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        /* COLORES PARA BADGE DE FECHA Y BORDE IZQUIERDO */
        /* CITAS PENDIENTES  */
        .next-appointment.pendiente .date-badge {
            background: var(--secondary-gradient) !important;
        }

        .next-appointment.pendiente {
            border-left: 5px solid #4facfe !important;
            /* Borde celeste */
        }

        /* CITAS CONFIRMADAS  */
        .next-appointment.confirmada .date-badge,
        .next-appointment.confirmado .date-badge {
            background: linear-gradient(135deg, #81c784, #66bb6a) !important;
        }

        .next-appointment.confirmada,
        .next-appointment.confirmado {
            border-left: 5px solid #66bb6a !important;
        }

        /* CITAS EN PROCESO - */
        .next-appointment.en_proceso .date-badge,
        .next-appointment.en-proceso .date-badge {
            background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
        }

        .next-appointment.en_proceso,
        .next-appointment.en-proceso {
            border-left: 5px solid #1b5e20 !important;
        }

        /* CITAS FINALIZADAS - */
        .next-appointment.finalizada .date-badge,
        .next-appointment.finalizado .date-badge {
            background: var(--primary-gradient) !important;
        }

        .next-appointment.finalizada,
        .next-appointment.finalizado {
            border-left: 5px solid #764ba2 !important;
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
                gap: 25px;
                padding: 0;
            }

            .sidebar-section {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 25px;
                padding-left: 0;
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

            .main-section {
                padding-right: 0;
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

            .quick-book-btn {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .quick-book-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
            }

            .quick-book-btn:active {
                transform: translateY(-1px);
            }

            /* Efecto de loading para botones de agendado rápido */
            .quick-book-btn.loading {
                pointer-events: none;
                background: #6c757d !important;
                color: transparent !important;
            }

            .quick-book-btn.loading::after {
                content: '';
                position: absolute;
                width: 16px;
                height: 16px;
                border: 2px solid transparent;
                border-top: 2px solid white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                top: 50%;
                left: 50%;
                margin-left: -8px;
                margin-top: -8px;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
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

        /* Estilos para SweetAlert modales grandes (agendado) */
        .swal-large-modal {
            max-width: 900px !important;
            width: 90% !important;
            border-radius: 15px !important;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
            background: linear-gradient(145deg, #ffffff, #f8f9fa) !important;
            border: 1px solid rgba(79, 172, 254, 0.2) !important;
        }

        /* Mejoras al título del modal */
        .swal-large-modal .swal2-title {
            color: #2c3e50 !important;
            font-size: 24px !important;
            font-weight: 600 !important;
            text-align: center !important;
            margin-bottom: 20px !important;
        }

        .swal-large-content {
            font-size: 16px !important;
            line-height: 1.6 !important;
        }

        /* Mejoras específicas para formularios de agendado */
        .swal-large-modal .swal2-html-container {
            margin: 1.5em 0 !important;
            max-height: 600px !important;
            overflow-y: auto !important;
            padding: 0 10px !important;
        }

        /* Estilos para los elementos de selección de vehículos */
        .swal-large-modal label {
            transition: all 0.3s ease !important;
            border: 2px solid #e3e8ef !important;
            background: #f8f9fa !important;
        }

        .swal-large-modal label:hover {
            border-color: #4facfe !important;
            background: #f0f8ff !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.15) !important;
        }

        .swal-large-modal input[type="radio"]:checked+div {
            color: #4facfe !important;
        }

        .swal-large-modal input[type="radio"]:checked {
            accent-color: #4facfe !important;
        }

        /* Estilos mejorados para inputs de fecha y hora */
        .swal-large-modal input[type="date"],
        .swal-large-modal input[type="time"] {
            width: 100% !important;
            padding: 15px 20px !important;
            border: 2px solid #e3e8ef !important;
            border-radius: 10px !important;
            font-size: 18px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            background: #ffffff !important;
            min-height: 55px !important;
            position: relative !important;
        }

        /* Mejoras específicas para el input de fecha - hacer el ícono del calendario más visible */
        .swal-large-modal input[type="date"] {
            padding-right: 60px !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24' fill='%234facfe' stroke='%23ffffff' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3Crect x='6' y='13' width='2' height='2' rx='0.3' ry='0.3' fill='%23ffffff'%3E%3C/rect%3E%3Crect x='11' y='13' width='2' height='2' rx='0.3' ry='0.3' fill='%23ffffff'%3E%3C/rect%3E%3Crect x='16' y='13' width='2' height='2' rx='0.3' ry='0.3' fill='%23ffffff'%3E%3C/rect%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 28px 28px !important;
            cursor: pointer !important;
            box-shadow: inset 0 0 0 1px rgba(79, 172, 254, 0.2) !important;
        }

        /* Mejoras específicas para el input de hora - hacer el ícono del reloj más visible */
        .swal-large-modal input[type="time"] {
            padding-right: 60px !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24' fill='%234facfe' stroke='%23ffffff' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpolyline points='12,6 12,12 16,14'%3E%3C/polyline%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 28px 28px !important;
            cursor: pointer !important;
            box-shadow: inset 0 0 0 1px rgba(79, 172, 254, 0.2) !important;
        }

        .swal-large-modal input[type="date"]:focus,
        .swal-large-modal input[type="time"]:focus {
            border-color: #4facfe !important;
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15) !important;
            transform: translateY(-1px) !important;
        }

        .swal-large-modal input[type="date"]:hover,
        .swal-large-modal input[type="time"]:hover {
            border-color: #4facfe !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.1) !important;
        }

        /* Mejoras para las etiquetas de fecha y hora */
        .swal-large-modal div[style*="margin-bottom: 15px"] label {
            display: block !important;
            margin-bottom: 8px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            color: #2c3e50 !important;
        }

        /* Estilos adicionales para mejorar la visibilidad del calendario */
        .swal-large-modal input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0 !important;
            width: 100% !important;
            height: 100% !important;
            cursor: pointer !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            transform: none !important;
        }

        .swal-large-modal input[type="time"]::-webkit-calendar-picker-indicator {
            opacity: 0 !important;
            width: 100% !important;
            height: 100% !important;
            cursor: pointer !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            transform: none !important;
        }

        /* Animación suave al hacer clic en los inputs */
        .swal-large-modal input[type="date"]:active,
        .swal-large-modal input[type="time"]:active {
            transform: translateY(0px) !important;
            box-shadow: 0 0 0 6px rgba(79, 172, 254, 0.25) !important;
        }

        /* Container para los inputs */
        .swal-large-modal div[style*="text-align: left"]>div {
            margin-bottom: 25px !important;
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .swal-large-modal {
                max-width: 95% !important;
                width: 95% !important;
                margin: 10px !important;
            }

            .swal-large-modal .swal2-html-container {
                font-size: 14px !important;
                max-height: 70vh !important;
            }

            /* Inputs de fecha y hora más grandes en móvil */
            .swal-large-modal input[type="date"],
            .swal-large-modal input[type="time"] {
                min-height: 60px !important;
                font-size: 18px !important;
                padding: 18px 22px !important;
                padding-right: 55px !important;
            }

            /* Iconos más grandes y visible en móvil */
            .swal-large-modal input[type="date"],
            .swal-large-modal input[type="time"] {
                background-size: 32px 32px !important;
                background-position: right 10px center !important;
                padding-right: 65px !important;
            }
        }

        /* Mejoras en los botones */
        .swal-large-modal .swal2-actions {
            margin-top: 25px !important;
        }

        .swal-large-modal .swal2-confirm {
            background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }

        .swal-large-modal .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3) !important;
        }

        .swal-large-modal .swal2-cancel {
            background: #6c757d !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-size: 16px !important;
            margin-right: 15px !important;
        }

        .swal-large-modal .swal2-cancel:hover {
            background: #5a6268 !important;
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

        .scroll-container {
            position: relative;
            height: 400px;
        }

        .card-body.scrollable {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        .card-body.scrollable::-webkit-scrollbar {
            display: none;
            /* Chrome/Safari/Opera */
        }

        .custom-scrollbar {
            position: absolute;
            right: 2px;
            top: 0;
            bottom: 0;
            width: 8px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            z-index: 10;
        }

        .custom-scrollbar-thumb {
            position: absolute;
            width: 100%;
            height: 30px;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .custom-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #3d8bfd, #00d9e8);
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
                    <button onclick="abrirModalVehiculos()" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Agregar Vehículo
                    </button>
                    <a href="#" class="btn btn-primary" onclick="openCitaModal()">
                        <i class="fas fa-calendar-plus"></i>
                        Nueva Cita
                    </a>
                    <button onclick="abrirModalConfiguracion()" class="btn btn-profile">
                        <i class="fas fa-cog"></i> Configurar Cuenta
                    </button>
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
                <!-- Próximas Citas Confirmadas -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h2>
                                <div class="icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                Próximas Citas Confirmadas
                            </h2>
                            <a href="{{ route('cliente.citas') }}" class="btn btn-outline"
                                style="padding: 8px 12px;">
                                <i class="fas fa-list"></i> Ver Todas Las Citas
                            </a>
                        </div>
                    </div>
                    <div class="scroll-container">
                        <div class="card-body scrollable" id="proximas-citas-container">
                            @php
                                // Filtrar solo citas confirmadas
                                $citas_confirmadas = $proximas_citas->filter(function ($cita) {
                                    return $cita->estado === 'confirmada' || $cita->estado === 'confirmado';
                                });
                            @endphp

                            @if ($proximas_citas->count() > 0)
                                @foreach ($proximas_citas->sortBy('fecha_hora') as $cita)
                                    @php
                                        $diasRestantes = now()->diffInDays($cita->fecha_hora, false);
                                        $diasRestantes = $diasRestantes < 0 ? 0 : ceil($diasRestantes);
                                        $urgenciaClass = '';
                                        $urgenciaText = '';

                                        // Aplicar clases de urgencia solo para citas confirmadas
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

                                    <div class="next-appointment confirmada {{ $urgenciaClass }}">
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
                                            <span class="appointment-status status-confirmada">
                                                Confirmada
                                            </span>
                                        </div>
                                        <div class="appointment-actions">
                                            <button class="btn btn-sm btn-warning"
                                                onclick="editCita({{ $cita->id }})">
                                                <i class="fas fa-edit"></i> Modificar
                                            </button>
                                            <button class="btn btn-sm btn-outline"
                                                onclick="cancelCita({{ $cita->id }})">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Mensaje informativo -->
                                <div class="info-message">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        Todas tus citas confirmadas futuras
                                    </small>
                                </div>

                                @if ($citas_confirmadas->count() > 3)
                                    <div style="text-align: center; margin-top: 15px;">
                                        <a href="{{ route('cliente.citas') }}" class="btn btn-outline">
                                            <i class="fas fa-list"></i> Ver todas las citas
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-calendar-check"></i>
                                    <h3>No tienes citas futuras confirmadas</h3>
                                    <p>Agenda una cita y aparecerá aquí cuando sea confirmada</p>
                                    <button onclick="openCitaModal()" class="btn btn-primary"
                                        style="margin-top: 15px;">
                                        <i class="fas fa-calendar-plus"></i>
                                        Agendar Cita
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="custom-scrollbar" id="proximas-citas-scrollbar">
                            <div class="custom-scrollbar-thumb" id="proximas-citas-thumb"></div>
                        </div>
                    </div>
                </div>


                <!-- Historial de Servicios -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h2>
                                <div class="icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                Historial de Servicios
                            </h2>
                            <a href="{{ route('cliente.citas.historial') }}" class="btn btn-outline"
                                style="padding: 8px 12px;">
                                <i class="fas fa-list"></i> Ver Historial Completo
                            </a>
                        </div>
                    </div>
                    <div class="scroll-container">
                        <div class="card-body scrollable" id="historial-container">
                            @if ($historial_citas->count() > 0)
                                @foreach ($historial_citas as $cita)
                                    <div class="service-history-item {{ $cita->estado }}">
                                        <div class="service-icon status-{{ $cita->estado }}">
                                            <i
                                                class="fas fa-{{ $cita->estado === 'finalizada' ? 'check-circle' : 'times-circle' }}"></i>
                                        </div>

                                        <div class="service-details">
                                            <h4>{{ $cita->servicios->pluck('nombre')->join(', ') }}</h4>
                                            <p><i class="fas fa-calendar"></i>
                                                {{ $cita->fecha_hora->format('d M Y - h:i A') }}</p>
                                            <p><i class="fas fa-car"></i> {{ $cita->vehiculo->marca }}
                                                {{ $cita->vehiculo->modelo }}
                                                @if ($cita->vehiculo->placa)
                                                    - {{ $cita->vehiculo->placa }}
                                                @endif
                                            </p>
                                            <span class="appointment-status status-{{ $cita->estado }}">
                                                {{ ucfirst($cita->estado) }}
                                            </span>
                                        </div>
                                        <div class="service-price">
                                            ${{ number_format($cita->servicios->sum('precio'), 2) }}
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
                        <div class="custom-scrollbar" id="historial-scrollbar">
                            <div class="custom-scrollbar-thumb" id="historial-thumb"></div>
                        </div>
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
                        <div class="services-grid" id="servicesGrid">
                            @if (isset($servicios) && $servicios->count() > 0)
                                @php
                                    $serviciosMostrados = 0;
                                    $totalServicios = $servicios->count();
                                @endphp
                                @foreach ($servicios->groupBy('categoria') as $categoria => $serviciosCategoria)
                                    @foreach ($serviciosCategoria as $servicio)
                                        @if ($serviciosMostrados < 4)
                                            <div class="service-card" data-servicio-id="{{ $servicio->id }}"
                                                data-categoria="{{ $servicio->categoria }}">
                                                <div class="service-icon">
                                                    @switch($servicio->categoria)
                                                        @case('auto')
                                                            <i class="fas fa-car"></i>
                                                        @break

                                                        @case('suv')
                                                            <i class="fas fa-truck"></i>
                                                        @break

                                                        @case('moto')
                                                            <i class="fas fa-motorcycle"></i>
                                                        @break

                                                        @default
                                                            <i class="fas fa-spray-can"></i>
                                                    @endswitch
                                                </div>
                                                <h3>{{ $servicio->nombre }}</h3>
                                                <p class="description">{{ $servicio->descripcion }}</p>
                                                <div class="price">${{ number_format($servicio->precio, 2) }}</div>
                                                <div class="duration">⏱️ {{ $servicio->duracion_formatted }}</div>
                                                <button class="btn btn-primary quick-book-btn"
                                                    onclick="quickBookService({{ $servicio->id }}, '{{ $servicio->categoria }}', this)">
                                                    <i class="fas fa-calendar-plus"></i>
                                                    Agendar Ahora
                                                </button>
                                            </div>
                                            @php $serviciosMostrados++; @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                                @if ($totalServicios > 4)
                                    <div class="service-card view-all-card" onclick="verTodosLosServiciosCliente()"
                                        style="cursor: pointer; border: 2px dashed #4facfe; background: rgba(79, 172, 254, 0.05);">
                                        <div class="service-icon"
                                            style="background: rgba(79, 172, 254, 0.1); color: #4facfe;">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <h3 style="color: #4facfe;">Ver Todos</h3>
                                        <p class="description" style="color: #666;">{{ $totalServicios - 4 }}
                                            servicios más disponibles</p>
                                        <div class="price" style="color: #4facfe; font-weight: 600;">
                                            {{ $totalServicios }} total</div>
                                        <div class="duration" style="color: #666;">📋 Catálogo completo</div>
                                        <button class="btn btn-outline"
                                            style="border-color: #4facfe; color: #4facfe;">
                                            <i class="fas fa-eye"></i>
                                            Ver Catálogo
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="service-card">
                                    <div class="service-icon">
                                        <i class="fas fa-spray-can"></i>
                                    </div>
                                    <h3>Lavado Completo</h3>
                                    <p class="description">Exterior e interior completo, aspirado y limpieza de
                                        tapicería</p>
                                    <div class="price">$25.00</div>
                                    <div class="duration">⏱️ 30-40 min</div>
                                    <button class="btn btn-primary" onclick="showNoServicesAlert()">
                                        <i class="fas fa-calendar-plus"></i>
                                        Agendar Ahora
                                    </button>
                                </div>
                            @endif
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
        <!-- Estadísticas Mini -->
        <div class="stats-mini"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px; margin-bottom: 25px;">
            <div class="stat-mini"
                style="background: linear-gradient(135deg, #667eea20, #764ba220); padding: 15px; border-radius: 10px; text-align: center; border-left: 4px solid #667eea;">
                <span class="number"
                    style="font-size: 1.5rem; font-weight: 700; color: #667eea; display: block;">
                    {{ $estadisticas_facturas['total_facturas'] }}
                </span>
                <span class="label"
                    style="font-size: 0.8rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
                    Facturas Totales
                </span>
            </div>
            <div class="stat-mini"
                style="background: linear-gradient(135deg, #4facfe20, #00f2fe20); padding: 15px; border-radius: 10px; text-align: center; border-left: 4px solid #4facfe;">
                <span class="number"
                    style="font-size: 1.5rem; font-weight: 700; color: #4facfe; display: block;">
                    ${{ number_format($estadisticas_facturas['total_gastado'], 2) }}
                </span>
                <span class="label"
                    style="font-size: 0.8rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Gastado
                </span>
            </div>
            <div class="stat-mini"
                style="background: linear-gradient(135deg, #3dd26e20, #35ebc920); padding: 15px; border-radius: 10px; text-align: center; border-left: 4px solid #3dd26e;">
                <span class="number"
                    style="font-size: 1.5rem; font-weight: 700; color: #3dd26e; display: block;">
                    {{ $estadisticas_facturas['facturas_mes_actual'] }}
                </span>
                <span class="label"
                    style="font-size: 0.8rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
                    Este Mes
                </span>
            </div>
            @if ($estadisticas_facturas['total_facturas'] > 0)
                <div class="stat-mini"
                    style="background: linear-gradient(135deg, #fa709a20, #fee14020); padding: 15px; border-radius: 10px; text-align: center; border-left: 4px solid #fa709a;">
                    <span class="number"
                        style="font-size: 1.5rem; font-weight: 700; color: #fa709a; display: block;">
                        ${{ number_format($estadisticas_facturas['promedio_por_factura'], 2) }}
                    </span>
                    <span class="label"
                        style="font-size: 0.8rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
                        Promedio/Factura
                    </span>
                </div>
            @endif
        </div>

        <!-- Información Adicional de Estadísticas -->
        @if ($estadisticas_facturas['vehiculo_mas_utilizado'] || $estadisticas_facturas['servicio_mas_solicitado'])
            <div
                style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #764ba2;">
                <h4 style="color: #764ba2; margin-bottom: 10px; font-size: 1rem;">
                    <i class="fas fa-chart-line"></i> Tus Estadísticas
                </h4>
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 0.85rem;">
                    @if ($estadisticas_facturas['vehiculo_mas_utilizado'])
                        <div>
                            <strong>🚗 Vehículo Más Usado:</strong><br>
                            {{ $estadisticas_facturas['vehiculo_mas_utilizado']['vehiculo']->marca }}
                            {{ $estadisticas_facturas['vehiculo_mas_utilizado']['vehiculo']->modelo }}
                            <small
                                style="color: #666;">({{ $estadisticas_facturas['vehiculo_mas_utilizado']['cantidad'] }}
                                facturas)</small>
                        </div>
                    @endif

                    @if ($estadisticas_facturas['servicio_mas_solicitado'])
                        <div>
                            <strong>✨ Servicio Favorito:</strong><br>
                            {{ $estadisticas_facturas['servicio_mas_solicitado']['servicio']->nombre }}
                            <small
                                style="color: #666;">({{ $estadisticas_facturas['servicio_mas_solicitado']['cantidad'] }}
                                veces)</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Lista de Facturas Recientes -->
        @if ($facturas_dashboard->count() > 0)
            <div class="services-grid">
                @foreach ($facturas_dashboard as $cita)
                    @php
                        $total = $cita->pago ? $cita->pago->monto : $cita->servicios->sum('precio');
                        $fechaFormateada = $cita->fecha_hora->format('d M Y');
                        $numeroFactura = 'FACT-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT);
                        $metodoPago = $cita->pago ? $cita->pago->metodo_formatted : 'No especificado';
                    @endphp

                    <div class="service-card" style="text-align: left; position: relative; overflow: hidden;">
                        <!-- Header con número de factura y precio -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; border-bottom: 1px solid #f1f3f4; padding-bottom: 12px;">
                            <div style="flex: 1;">
                                <h3 style="color: #4facfe; margin: 0 0 8px 0; font-size: 1.2rem; font-weight: 700;">
                                    {{ $numeroFactura }}
                                </h3>
                                <div style="color: #666; font-size: 0.85rem;">
                                    <p style="margin: 4px 0;">
                                        <i class="fas fa-calendar"></i> {{ $fechaFormateada }}
                                    </p>
                                    <p style="margin: 4px 0;">
                                        <i class="fas fa-car"></i> {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                    </p>
                                </div>
                            </div>
                            <div style="text-align: right; min-width: 120px;">
                                <div style="font-weight: 800; color: #4facfe; font-size: 1.4rem; margin-bottom: 8px;">
                                    ${{ number_format($total, 2) }}
                                </div>
                                
                                <!-- Badges en columna -->
                                <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-end;">
                                    <!-- Badge de estado -->
                                    <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; white-space: nowrap;">
                                        <i class="fas fa-check-circle"></i> COMPLETADA
                                    </span>
                                    
                                    <!-- Badge de método de pago -->
                                    @if ($cita->pago)
                                        @switch($cita->pago->metodo)
                                            @case('efectivo')
                                                <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 600; white-space: nowrap;">
                                                    💵 Efectivo
                                                </span>
                                            @break

                                            @case('transferencia')
                                                <span style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 600; white-space: nowrap;">
                                                    🏦 Transferencia
                                                </span>
                                            @break

                                            @case('pasarela')
                                                <span style="background: #6f42c1; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 600; white-space: nowrap;">
                                                    💳 Tarjeta
                                                </span>
                                            @break
                                        @endswitch
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Servicios -->
                        <div style="margin-bottom: 15px;">
                            <p style="font-weight: 600; color: #333; margin-bottom: 10px; font-size: 0.95rem;">
                                <i class="fas fa-spray-can"></i> Servicios Contratados:
                            </p>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach ($cita->servicios->take(3) as $servicio)
                                    <span style="background: #e7f3ff; color: #1976d2; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 500; border: 1px solid #bbdefb;">
                                        {{ $servicio->nombre }}
                                    </span>
                                @endforeach
                                @if ($cita->servicios->count() > 3)
                                    <span style="background: #4facfe; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 600;">
                                        +{{ $cita->servicios->count() - 3 }} más
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($cita->pago && $cita->pago->referencia)
                            <div style="background: #fff3cd; padding: 10px; border-radius: 6px; margin-bottom: 15px; border-left: 4px solid #ffc107;">
                                <small style="color: #856404; font-size: 0.8rem;">
                                    <i class="fas fa-receipt"></i>
                                    <strong>Referencia de pago:</strong> {{ $cita->pago->referencia }}
                                </small>
                            </div>
                        @endif

                        <!-- Botones de acción -->
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button class="btn btn-sm btn-outline" style="flex: 1; padding: 10px;"
                                onclick="verDetalleFactura({{ $cita->id }})">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </button>
                            <button class="btn btn-sm btn-primary" style="flex: 1; padding: 10px;"
                                onclick="descargarFactura({{ $cita->id }})">
                                <i class="fas fa-download"></i> Descargar PDF
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-file-invoice"
                    style="font-size: 3rem; color: #4facfe; margin-bottom: 15px; opacity: 0.7;"></i>
                <h3 style="color: #333; margin-bottom: 10px; font-weight: 600;">No hay facturas disponibles</h3>
                <p style="color: #666; line-height: 1.5;">
                    Aún no tienes servicios finalizados con facturas generadas.<br>
                    Tus facturas aparecerán aquí una vez que completes tus servicios.
                </p>
                <div style="margin-top: 20px;">
                    <a href="{{ route('cliente.citas') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Agendar Servicio
                    </a>
                </div>
            </div>
        @endif

        @if ($facturas_dashboard->count() > 0)
            <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f3f4;">
                <a href="{{ route('cliente.facturas') }}" class="btn btn-outline"
                    style="padding: 10px 20px;">
                    <i class="fas fa-history"></i> Ver Historial Completo de Facturas
                </a>
                <div style="margin-top: 10px;">
                    <small style="color: #666;">
                        <i class="fas fa-info-circle"></i>
                        Tienes {{ $estadisticas_facturas['total_facturas'] }} facturas en total
                    </small>
                </div>
            </div>
        @endif
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

    <!-- Modal de Navegación: Vehículos -->
    <div id="vehiculosNavModal"
        style="display: none !important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; border-radius: 12px; width: 85%; max-width: 1400px; height: 85vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div
                style="padding: 12px 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-car" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Mis Vehículos
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModalNav('vehiculosNavModal')"
                    style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;"
                    onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'"
                    onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>
            <!-- Contenido del modal -->
            <div id="vehiculosNavContent" style="flex: 1; overflow: hidden; background: #f5f5f5;">
                <div class="text-center" style="padding: 60px 20px;">
                    <div
                        style="width: 50px; height: 50px; margin: 0 auto 20px; border: 4px solid #4facfe; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;">
                    </div>
                    <p style="color: #6c757d; font-size: 0.95rem;">Cargando contenido...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Navegación: Configuración -->
    <div id="configuracionNavModal"
        style="display: none !important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; border-radius: 12px; width: 85%; max-width: 1400px; height: 85vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div
                style="padding: 12px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-cog" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Configuración de Cuenta
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModalNav('configuracionNavModal')"
                    style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;"
                    onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'"
                    onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>
            <!-- Contenido del modal -->
            <div id="configuracionNavContent" style="flex: 1; overflow: hidden; background: #f5f5f5;">
                <div class="text-center" style="padding: 60px 20px;">
                    <div
                        style="width: 50px; height: 50px; margin: 0 auto 20px; border: 4px solid #667eea; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;">
                    </div>
                    <p style="color: #6c757d; font-size: 0.95rem;">Cargando contenido...</p>
                </div>
            </div>
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
                                FUNCIONAMIENTO DE FACTURAS
                                =========================================================*/

        // Función para ver el detalle de una factura
        function verDetalleFactura(citaId) {
            Swal.fire({
                title: 'Cargando factura...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/cliente/facturas/${citaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.close();
                        // Aquí puedes mostrar un modal con los detalles de la factura
                        mostrarModalFactura(data.factura);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'No se pudo cargar la factura: ' + error.message, 'error');
                });
        }

        // Función para descargar factura en PDF
        function descargarFactura(citaId) {
            Swal.fire({
                title: 'Generando PDF...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Abrir en una nueva pestaña para descargar
            window.open(`/cliente/facturas/${citaId}/descargar`, '_blank');
            Swal.close();
        }

        // Función para mostrar modal con detalles de factura
function mostrarModalFactura(factura) {
    // Asegurar que total sea un número
    const total = typeof factura.total === 'number' ? factura.total : parseFloat(factura.total) || 0;

    const serviciosList = factura.servicios.map(servicio => {
        const precio = typeof servicio.precio === 'number' ? servicio.precio : parseFloat(servicio.precio) || 0;
        return `
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #f1f3f4;">
                <div style="flex: 1;">
                    <strong style="color: #2c3e50;">${servicio.nombre}</strong>
                    ${servicio.descripcion ? `<br><small style="color: #7f8c8d;">${servicio.descripcion}</small>` : ''}
                </div>
                <div style="text-align: right; min-width: 100px;">
                    <span style="font-weight: 600; color: #27ae60;">$${precio.toFixed(2)}</span>
                </div>
            </div>
        `;
    }).join('');

    // Determinar color y texto del método de pago
    let metodoPagoHtml = '';
    if (factura.metodo_pago) {
        let color = '#6c757d';
        let icono = '💳';
        
        switch(factura.metodo_pago.toLowerCase()) {
            case 'efectivo':
                color = '#28a745';
                icono = '💵';
                break;
            case 'transferencia':
                color = '#17a2b8';
                icono = '🏦';
                break;
            case 'tarjeta':
            case 'pasarela':
                color = '#6f42c1';
                icono = '💳';
                break;
        }
        
        metodoPagoHtml = `
            <div style="background: ${color}15; padding: 12px; border-radius: 8px; margin: 15px 0; border-left: 4px solid ${color};">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: ${color}; display: block; margin-bottom: 5px;">${icono} Método de Pago</strong>
                        <span style="color: #2c3e50; font-size: 0.95rem;">${factura.metodo_pago}</span>
                    </div>
                    <div style="text-align: right;">
                        <span style="background: ${color}; color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            ${factura.estado_pago || 'Completado'}
                        </span>
                    </div>
                </div>
                ${factura.referencia_pago ? `
                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid ${color}30;">
                        <small style="color: #6c757d;">
                            <strong>Referencia:</strong> ${factura.referencia_pago}
                        </small>
                    </div>
                ` : ''}
                ${factura.fecha_pago !== 'N/A' ? `
                    <div style="margin-top: 5px;">
                        <small style="color: #6c757d;">
                            <strong>Fecha de pago:</strong> ${factura.fecha_pago}
                        </small>
                    </div>
                ` : ''}
            </div>
        `;
    }

    const htmlContent = `
        <div style="text-align: left; max-height: 70vh; overflow-y: auto; padding-right: 10px;">
            <!-- Header de la factura -->
            <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                <h3 style="margin: 0 0 10px 0; font-size: 1.5rem;">Factura #${factura.numero}</h3>
                <p style="margin: 5px 0; opacity: 0.9;">Carwash Berríos</p>
                <p style="margin: 5px 0; opacity: 0.9;">${factura.fecha_emision}</p>
            </div>
            
            <!-- Información del servicio -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #4facfe; margin: 0 0 10px 0; font-size: 1rem;">📅 Información del Servicio</h4>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Fecha:</strong> ${factura.fecha_servicio}
                    </p>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Hora:</strong> ${factura.hora_servicio}
                    </p>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Estado:</strong> 
                        <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                            ${factura.estado}
                        </span>
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #4facfe; margin: 0 0 10px 0; font-size: 1rem;">🚗 Información del Vehículo</h4>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Vehículo:</strong> ${factura.vehiculo_marca} ${factura.vehiculo_modelo}
                    </p>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Placa:</strong> ${factura.vehiculo_placa || 'No especificada'}
                    </p>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Color:</strong> ${factura.vehiculo_color || 'No especificado'}
                    </p>
                    <p style="margin: 5px 0; color: #2c3e50;">
                        <strong>Tipo:</strong> ${factura.vehiculo_tipo}
                    </p>
                </div>
            </div>

            <!-- Información del cliente -->
            <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #4facfe;">
                <h4 style="color: #4facfe; margin: 0 0 10px 0; font-size: 1rem;">👤 Información del Cliente</h4>
                <p style="margin: 5px 0; color: #2c3e50;">
                    <strong>Nombre:</strong> ${factura.cliente_nombre}
                </p>
                <p style="margin: 5px 0; color: #2c3e50;">
                    <strong>Email:</strong> ${factura.cliente_email}
                </p>
                <p style="margin: 5px 0; color: #2c3e50;">
                    <strong>Teléfono:</strong> ${factura.cliente_telefono || 'No especificado'}
                </p>
            </div>
            
            <!-- Servicios -->
            <div style="margin-bottom: 20px;">
                <h4 style="color: #4facfe; margin: 0 0 15px 0; font-size: 1rem;">✨ Servicios Contratados</h4>
                <div style="background: white; border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden;">
                    ${serviciosList}
                </div>
            </div>

            <!-- Información de pago -->
            ${metodoPagoHtml}

            <!-- Total -->
            <div style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; padding: 20px; border-radius: 10px; text-align: center; margin-top: 20px;">
                <h3 style="margin: 0; font-size: 1.8rem; font-weight: 700;">
                    Total: $${total.toFixed(2)}
                </h3>
                <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 0.9rem;">
                    ¡Gracias por su preferencia!
                </p>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Detalle de Factura',
        html: htmlContent,
        width: '800px', // Modal más grande
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'factura-detalle-modal',
            container: 'factura-detalle-container'
        }
    });
}
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
            buttonsStyling: true
        });

        // Configuración específica para modales de agendado (más grandes y mejor visualización)
        const swalLargeModal = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline mr-2',
                popup: 'swal-large-modal',
                content: 'swal-large-content'
            },
            buttonsStyling: true,
            width: '800px',
            padding: '2em'
        });

        // Función para verificar si una fecha es no laborable
        async function verificarDiaNoLaborable(fecha) {
            try {
                const response = await fetch('/cliente/verificar-dia-no-laborable?fecha=' + fecha);
                const data = await response.json();

                if (data.es_no_laborable) {
                    return {
                        esNoLaborable: true,
                        motivo: data.motivo || 'Día no laborable'
                    };
                }
                return {
                    esNoLaborable: false
                };
            } catch (error) {
                console.error('Error al verificar día no laborable:', error);
                return {
                    esNoLaborable: false
                };
            }
        }

        // Función para mostrar alerta de día no laborable
        function mostrarAlertaDiaNoLaborable(motivo) {
            Swal.fire({
                title: '⚠️ Día No Laborable',
                html: `<div style="text-align: left;">
            <p>No se pueden agendar citas en esta fecha.</p>
            <p><strong>Motivo:</strong> ${motivo}</p>
            <p style="margin-top: 15px; color: #666; font-size: 0.9em;">
                Por favor selecciona otra fecha disponible.
            </p>
        </div>`,
                icon: 'warning',
                confirmButtonColor: '#4facfe',
                confirmButtonText: 'Entendido'
            });
        }

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
        // Función para cargar horas disponibles (VERSIÓN CORREGIDA)
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

                    const horaFinHorario = new Date();
                    horaFinHorario.setHours(finH, finM, 0, 0);

                    while (horaActual < horaFinHorario) {
                        const horaStr = horaActual.getHours().toString().padStart(2, '0') + ':' +
                            horaActual.getMinutes().toString().padStart(2, '0');

                        // CORRECCIÓN: Solo verificar disponibilidad básica, no duración completa
                        const inicioPropuesta = new Date(`${selectedDate}T${horaStr}`);

                        // Verificar colisión con citas existentes (SOLO inicio, no duración completa)
                        const estaOcupado = citasExistentes.some(cita => {
                            try {
                                const inicioCita = new Date(`${selectedDate}T${cita.hora_inicio}`);
                                const finCita = new Date(inicioCita.getTime() + (cita.duracion || 30) *
                                    60000);

                                // Solo verificar si el horario propuesto está dentro de una cita existente
                                return inicioPropuesta >= inicioCita && inicioPropuesta < finCita;
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
                            // La validación de duración se hará después cuando seleccionen servicios
                        }

                        horaSelect.appendChild(option);
                        horaActual.setMinutes(horaActual.getMinutes() + 30);
                    }
                });

                if (horariosGenerados === 0 && horaSelect.options.length > 1) {
                    horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                } else {
                    console.log(`Horarios cargados - Generados: ${horariosGenerados}`);
                }

            } catch (error) {
                console.error('Error en loadAvailableHours:', error);
                horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
            }
        }

        // Función de fallback para cargar horarios 
        async function loadAvailableHoursFallback(selectedDate, excludeCitaId = null) {
            const horaSelect = document.getElementById('hora');
            horaSelect.innerHTML = '<option value="">Cargando horarios (modo fallback)...</option>';

            try {
                // Obtener horarios ocupados
                let citasExistentes = [];
                try {
                    const url =
                        `/cliente/citas/horarios-ocupados?fecha=${selectedDate}${excludeCitaId ? `&exclude=${excludeCitaId}` : ''}`;
                    console.log('Consultando horarios ocupados (fallback):', url);

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error(`Error ${response.status}`);

                    const data = await response.json();
                    citasExistentes = data.horariosOcupados || [];
                    console.log('Horarios ocupados recibidos (fallback):', citasExistentes);
                } catch (error) {
                    console.error('Error al obtener horarios ocupados (fallback):', error);
                }

                // Obtener horarios programados para este día
                const fechaLocal = createLocalDate(selectedDate);
                const dayOfWeekJS = fechaLocal.getDay();
                const dayOfWeekBackend = getBackendDayFromJSDay(dayOfWeekJS);

                console.log('Consultando horarios programados para día (fallback):', dayOfWeekBackend);

                // Cargar horarios programados desde el endpoint general
                try {
                    const horariosRes = await fetch('{{ route('cliente.horarios-disponibles') }}');
                    if (horariosRes.ok) {
                        const todosHorarios = await horariosRes.json();
                        const horariosDia = todosHorarios.filter(h => h.dia_semana == dayOfWeekBackend);

                        console.log('Horarios para día', dayOfWeekBackend, ':', horariosDia);

                        if (horariosDia.length === 0) {
                            horaSelect.innerHTML = '<option value="">No hay horarios programados</option>';
                            return;
                        }

                        let horariosGenerados = [];

                        // Primero recolectar todos los horarios
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
                                        const inicioCita = new Date(
                                            `${selectedDate}T${cita.hora_inicio}`);
                                        const finCita = new Date(inicioCita.getTime() + (cita
                                            .duracion || 30) * 60000);
                                        const inicioPropuesta = new Date(`${selectedDate}T${horaStr}`);
                                        const finPropuesta = new Date(inicioPropuesta.getTime() + 30 *
                                            60000);

                                        return (
                                            (inicioPropuesta >= inicioCita && inicioPropuesta <
                                                finCita) ||
                                            (finPropuesta > inicioCita && finPropuesta <=
                                                finCita) ||
                                            (inicioPropuesta <= inicioCita && finPropuesta >=
                                                finCita)
                                        );
                                    } catch (e) {
                                        console.error('Error al verificar colisión (fallback):', e);
                                        return false;
                                    }
                                });

                                horariosGenerados.push({
                                    hora: horaStr,
                                    disponible: !estaOcupado
                                });

                                horaActual.setMinutes(horaActual.getMinutes() + 30);
                            }
                        });

                        // ✅ Ordenar horarios
                        horariosGenerados.sort((a, b) => a.hora.localeCompare(b.hora));

                        // Limpiar select
                        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

                        // Agregar horarios ordenados
                        horariosGenerados.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.hora;
                            option.textContent = item.hora;

                            if (!item.disponible) {
                                option.disabled = true;
                                option.textContent += ' (Ocupado)';
                                option.style.color = '#ff6b6b';
                                option.style.fontWeight = 'bold';
                            }

                            horaSelect.appendChild(option);
                        });

                        console.log(`Horarios cargados (fallback) - Generados: ${horariosGenerados.length}`);
                    }
                } catch (error) {
                    console.error('Error cargando horarios programados (fallback):', error);
                    horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                }

            } catch (error) {
                console.error('Error crítico en loadAvailableHoursFallback:', error);
                horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
            }
        }
        // Configuracion del datepicker
        function setupDatePicker() {
            const fechaInput = document.getElementById('fecha');

            // Agrega esta variable para controlar el estado
            let isProcessing = false;

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

            fechaInput.addEventListener('change', async function() {
                if (isProcessing) return; // Evita ejecución múltiple
                isProcessing = true;

                console.log('📅 Fecha cambiada:', this.value);

                if (!this.value) {
                    document.getElementById('hora').innerHTML = '<option value="">Seleccione una hora</option>';
                    isProcessing = false;
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
                        document.getElementById('hora').innerHTML =
                            '<option value="">Seleccione una hora</option>';
                        isProcessing = false;
                        return;
                    }

                    //  Verificar días no laborables con el servidor
                    try {
                        const response = await fetch(`/cliente/verificar-dia-no-laborable?fecha=${this.value}`);
                        if (response.ok) {
                            const data = await response.json();

                            if (data.es_no_laborable) {
                                showDateError(
                                    'Día no laborable',
                                    `No se atienden citas el ${formatFechaBonita(selectedDate)}.<br>
                     <strong>Motivo:</strong> ${data.motivo || 'Día no laborable'}`
                                );
                                this.value = '';
                                document.getElementById('hora').innerHTML =
                                    '<option value="">Seleccione una hora</option>';
                                isProcessing = false;
                                return;
                            }
                        }
                    } catch (error) {
                        console.warn('Error al verificar día no laborable, usando validación local:', error);
                        // Fallback a la validación local existente
                        const diaNoLaborable = diasNoLaborables.find(dia => dia.fecha === this.value);
                        if (diaNoLaborable) {
                            showDateError(
                                'Día no laborable',
                                `No se atienden citas el ${formatFechaBonita(selectedDate)}.<br>
                 <strong>Motivo:</strong> ${diaNoLaborable.motivo || 'Día no laborable'}`
                            );
                            this.value = '';
                            document.getElementById('hora').innerHTML =
                                '<option value="">Seleccione una hora</option>';
                            isProcessing = false;
                            return;
                        }
                    }

                    // ÚNICO LUGAR donde se cargan horarios - al cambiar fecha
                    const citaId = document.getElementById('form_cita_id')?.value;
                    await loadAvailableHours(this.value, citaId);

                } catch (error) {
                    console.error('Error al procesar fecha:', error);
                    showDateError('Error', 'Fecha inválida. Por favor selecciona una fecha válida.');
                    this.value = '';
                } finally {
                    isProcessing = false; // Siempre libera el lock
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


        // Función auxiliar para validar duración de servicios 
        function validateServiceDuration(horaSeleccionada, duracionTotal) {
            try {
                // Solo validar si la duración es mayor a 0
                if (duracionTotal <= 0) return;

                const [horas, minutos] = horaSeleccionada.split(':').map(Number);

                // CORRECCIÓN: Calcular solo la HORA de finalización, no la fecha completa
                const horaFinServicio = (horas * 60 + minutos + duracionTotal) / 60;
                const horasFin = Math.floor(horaFinServicio);
                const minutosFin = Math.round((horaFinServicio - horasFin) * 60);

                // Horario de cierre estándar: 6:00 PM (18:00)
                const horaCierreHoras = 18;
                const horaCierreMinutos = 0;

                // CORRECCIÓN: Calcular diferencia en minutos SOLO de hora
                const minutosFinTotal = horasFin * 60 + minutosFin;
                const minutosCierreTotal = horaCierreHoras * 60 + horaCierreMinutos;

                const minutosDiferencia = minutosFinTotal - minutosCierreTotal;

                // Solo mostrar advertencia si realmente excede el horario
                if (minutosDiferencia > 0) {
                    const horasExtra = (minutosDiferencia / 60).toFixed(1);

                    console.warn(
                        `⚠️ Los servicios seleccionados requieren ${minutosDiferencia} minutos extra (${horasExtra}h)`);

                    // Mostrar información visual según la duración extra
                    const horaSelect = document.getElementById('hora');
                    const duracionInfo = document.getElementById('duracion-info') || createDuracionInfo();

                    if (minutosDiferencia <= 30) {
                        // Tiempo extra mínimo - advertencia suave
                        horaSelect.style.borderColor = '#ffa500';
                        duracionInfo.className = 'alert alert-warning mt-2';
                        duracionInfo.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Tiempo extra: ${Math.round(minutosDiferencia)} min (${horasExtra}h) - Aceptable</span>
                    </div>
                `;
                    } else if (minutosDiferencia <= 60) {
                        // Tiempo extra moderado - advertencia media
                        horaSelect.style.borderColor = '#ff9800';
                        duracionInfo.className = 'alert alert-warning mt-2';
                        duracionInfo.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Tiempo extra: ${Math.round(minutosDiferencia)} min (${horasExtra}h) - Requiere confirmación</span>
                    </div>
                `;
                    } else if (minutosDiferencia <= 90) {
                        // Tiempo extra considerable - advertencia fuerte
                        horaSelect.style.borderColor = '#ff6b6b';
                        duracionInfo.className = 'alert alert-danger mt-2';
                        duracionInfo.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Tiempo extra: ${Math.round(minutosDiferencia)} min (${horasExtra}h) - Considerable</span>
                    </div>
                `;
                    } else {
                        // Tiempo excesivo - error
                        horaSelect.style.borderColor = '#dc3545';
                        duracionInfo.className = 'alert alert-danger mt-2';
                        duracionInfo.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        <span>Tiempo extra: ${Math.round(minutosDiferencia)} min (${horasExtra}h) - Demasiado largo</span>
                    </div>
                `;
                    }

                    // Remover advertencia después de 5 segundos
                    setTimeout(() => {
                        if (horaSelect) {
                            horaSelect.style.borderColor = '';
                        }
                        if (duracionInfo) {
                            duracionInfo.remove();
                        }
                    }, 5000);
                } else {
                    // Limpiar advertencias si no hay exceso
                    const duracionInfo = document.getElementById('duracion-info');
                    if (duracionInfo) {
                        duracionInfo.remove();
                    }
                    const horaSelect = document.getElementById('hora');
                    if (horaSelect) {
                        horaSelect.style.borderColor = '';
                    }
                }
            } catch (error) {
                console.error('Error al validar duración:', error);
            }
        }

        function createDuracionInfo() {
            const horaSelect = document.getElementById('hora');
            if (!horaSelect) return null;

            const duracionInfo = document.createElement('div');
            duracionInfo.id = 'duracion-info';

            // Insertar después del select de hora
            horaSelect.parentNode.insertBefore(duracionInfo, horaSelect.nextSibling);

            return duracionInfo;
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

        async function setSelectedHourForEdit(hora24, maxAttempts = 10, interval = 300) {
            return new Promise((resolve) => {
                let attempts = 0;

                const checkInterval = setInterval(() => {
                    const horaSelect = document.getElementById('hora');
                    attempts++;

                    if (!horaSelect || horaSelect.options.length <= 1) {
                        if (attempts >= maxAttempts) {
                            clearInterval(checkInterval);
                            // Forzar la creación de la opción
                            const newOption = document.createElement('option');
                            newOption.value = hora24;
                            newOption.textContent = `${hora24} (Actual)`;
                            horaSelect.appendChild(newOption);
                            horaSelect.value = hora24;
                            resolve(true);
                        }
                        return;
                    }

                    // Buscar la hora exacta
                    for (let option of horaSelect.options) {
                        if (option.value === hora24) {
                            horaSelect.value = hora24;
                            clearInterval(checkInterval);
                            console.log('Hora establecida exitosamente:', hora24);
                            resolve(true);
                            return;
                        }
                    }

                    // Si no se encuentra después de intentos, crear la opción
                    if (attempts >= maxAttempts) {
                        clearInterval(checkInterval);
                        const newOption = document.createElement('option');
                        newOption.value = hora24;
                        newOption.textContent = `${hora24} (Actual)`;
                        horaSelect.appendChild(newOption);
                        horaSelect.value = hora24;
                        resolve(true);
                    }
                }, interval);
            });
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin' // Asegura que las cookies se incluyan
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

                // Verificar restricción de 24 horas
                if (data.data && data.data.restriccion_24h) {
                    document.getElementById('fecha').readOnly = true;
                    document.getElementById('hora').readOnly = true;
                    document.getElementById('vehiculo_id').readOnly = true;

                    // Agregar clases visuales de bloqueo
                    document.getElementById('fecha').classList.add('campo-bloqueado');
                    document.getElementById('hora').classList.add('campo-bloqueado');
                    document.getElementById('vehiculo_id').classList.add('campo-bloqueado');

                    // Mostrar mensaje al usuario
                    swalWithBootstrapButtons.fire({
                        title: 'Atención',
                        text: 'Solo puedes modificar servicios y observaciones cuando faltan menos de 24 horas para tu cita confirmada',
                        icon: 'info',
                        confirmButtonText: 'Entendido'
                    });
                }

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
                if (citas.length === 0) {
                    try {
                        const response = await fetch('/cliente/dashboard-data', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) throw new Error(`Error ${response.status}: ${response.statusText}`);

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Respuesta no es JSON');
                        }

                        const data = await response.json();
                        if (!data.success) throw new Error(data.message || 'Error en los datos recibidos');

                        // SOLO CITAS CONFIRMADAS para próximas
                        citas = tipo === 'próximas' ? data.data.proximas_citas : data.data.historial_citas;

                        // Ordenar citas próximas por fecha (más cercana primero)
                        if (tipo === 'próximas' && citas.length > 0) {
                            citas.sort((a, b) => new Date(a.fecha_hora) - new Date(b.fecha_hora));
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
                        'No tienes citas futuras confirmadas' :
                        'No hay historial de servicios';

                    const emptyDescription = tipo === 'próximas' ?
                        'Agenda una cita y aparecerá aquí cuando sea confirmada' :
                        'Agenda tu primera cita para comenzar a ver tu historial';

                    container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-${tipo === 'próximas' ? 'calendar-check' : 'history'}"></i>
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
                        const fechaCita = formatearFechaHoraFromServer(cita.fecha_hora);
                        const hoy = new Date();
                        const diasRestantes = Math.ceil((fechaCita - hoy) / (1000 * 60 * 60 * 24));

                        const dia = obtenerDiaDelMes(cita.fecha_hora);
                        const mes = obtenerMesAbreviado(cita.fecha_hora);
                        const hora = formatearSoloHora(cita.fecha_hora);

                        let urgenciaClass = '';
                        let urgenciaText = '';

                        // Aplicar clases de urgencia solo para citas confirmadas
                        if ((cita.estado === 'confirmada' || cita.estado === 'confirmado') && diasRestantes >=
                            0) {
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
                        }

                        // Añadir la clase de estado y urgencia al div principal
                        html += `
                <div class="next-appointment ${cita.estado} ${urgenciaClass}">
                    <div class="appointment-date-time">
                        <div class="date-badge">
                            <span class="day">${dia}</span>
                            <span class="month">${mes}</span>
                            ${diasRestantes <= 7 && (cita.estado === 'confirmada' || cita.estado === 'confirmado') ? `<span class="days-remaining">${urgenciaText}</span>` : ''}
                        </div>
                        <div class="time-info">
                            <div class="time">${formatearSoloHoraAMPM(cita.fecha_hora)}</div>
                            <div class="service">
                                ${cita.servicios.map(s => s.nombre).join(', ')}
                            </div>
                            <div class="vehicle-info">
                                <i class="fas fa-car"></i> ${cita.vehiculo.marca} ${cita.vehiculo.modelo}
                            </div>
                            ${diasRestantes > 7 ? `<div class="days-info"><i class="fas fa-clock"></i> ${urgenciaText}</div>` : ''}
                        </div>
                        <span class="appointment-status status-${cita.estado}">
                            ${cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1)}
                        </span>
                    </div>
                    <div class="appointment-actions">
                        <button class="btn btn-sm btn-warning" onclick="editCita(${cita.id})">
                            <i class="fas fa-edit"></i> Modificar
                        </button>
                        <button class="btn btn-sm btn-outline" onclick="cancelCita(${cita.id})">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
                `;
                    });

                    // Mensaje actualizado (sin referencia a 15 días)
                    html += `
                <div style="text-align: center; margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 8px;">
                    <small style="color: #6c757d;">
                        <i class="fas fa-info-circle"></i>
                        Todas tus citas confirmadas futuras
                    </small>
                </div>
            `;

                    if (citas.length > 3) {
                        html += `
                <div style="text-align: center; margin-top: 15px;">
                  <a href="{{ route('cliente.citas', ['tipo' => 'proximas']) }}" class="btn btn-outline">
    <i class="fas fa-list"></i> Ver todas las citas
</a>
                </div>
                `;
                    }
                } else { // Historial
                    citas.forEach(cita => {
                        const fechaCompleta = formatearFechaCompleta(cita.fecha_hora);
                        const total = cita.servicios.reduce((sum, servicio) => sum + servicio.precio, 0);

                        html += `
                <div class="service-history-item">
                    <div class="service-icon">
                        <i class="fas fa-${cita.estado === 'finalizada' ? 'check-circle' : 'times-circle'}"></i>
                    </div>
                    <div class="service-details">
                        <h4>${cita.servicios.map(s => s.nombre).join(', ')}</h4>
                        <p><i class="fas fa-calendar"></i> ${fechaCompleta}</p>
                        <p><i class="fas fa-car"></i> ${cita.vehiculo.marca} ${cita.vehiculo.modelo} - ${cita.vehiculo.placa}</p>
                        <span class="appointment-status status-${cita.estado}" style="display: inline-block; margin-top: 5px;">
                            ${cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1)}
                        </span>
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

        function formatearSoloHoraAMPM(fechaHora) {
            const fecha = formatearFechaHoraFromServer(fechaHora);
            return fecha.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true // Esto fuerza el formato AM/PM
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

        async function forceCreateCita(formData) {
            try {
                const response = await fetch('{{ route('cliente.citas.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Force-Create': 'true'
                    },
                    body: formData
                });

                let result;
                try {
                    result = await response.json();
                } catch (jsonError) {
                    console.error('Error parsing JSON en forceCreate:', jsonError);
                    throw new Error('Respuesta inválida del servidor');
                }

                console.log('🔍 Respuesta forceCreate:', result);

                if (response.ok && result.success && result.data) {
                    closeCitaModal();

                    const citaData = result.data;
                    let fechaMostrar, horaMostrar;

                    try {
                        const fechaObj = new Date(citaData.fecha_hora);
                        fechaMostrar = fechaObj.toLocaleDateString('es-ES', {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        horaMostrar = fechaObj.toLocaleTimeString('es-ES', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } catch (dateError) {
                        console.warn('Error formateando fecha en forceCreate:', dateError);
                        fechaMostrar = 'Fecha programada';
                        horaMostrar = 'Hora programada';
                    }

                    await swalWithBootstrapButtons.fire({
                        title: '¡Cita agendada con tiempo extendido!',
                        html: `
                    <div style="text-align: left; margin-top: 15px;">
                        <p><strong>Fecha:</strong> ${fechaMostrar}</p>
                        <p><strong>Hora:</strong> ${horaMostrar}</p>
                        <p><strong>Servicios:</strong> ${citaData.servicios_nombres || 'Servicios seleccionados'}</p>
                        <p><strong>Duración total:</strong> ${citaData.duracion_total || 'N/A'} minutos</p>
                        <p><strong>Vehículo:</strong> ${citaData.vehiculo_marca || ''} ${citaData.vehiculo_modelo || ''}</p>
                        ${citaData.vehiculo_placa ? `<p><strong>Placa:</strong> ${citaData.vehiculo_placa}</p>` : ''}
                        <div style="margin-top: 15px; padding: 10px; background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
                            <small style="color: #856404;">
                                <i class="fas fa-info-circle"></i>
                                Esta cita requiere tiempo adicional después del horario normal.
                            </small>
                        </div>
                    </div>
                `,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });

                    await updateCitasSections();
                } else {
                    throw new Error(result.message || 'Error al forzar creación de cita');
                }
            } catch (error) {
                console.error('Error al forzar creación:', error);
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: error.message || 'No se pudo completar la reserva',
                    icon: 'error'
                });
            }
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

                    // Combinar fecha y hora
                    const fecha = document.getElementById('fecha').value;
                    const hora = document.getElementById('hora').value;

                    if (!fecha || !hora) {
                        swalWithBootstrapButtons.fire({
                            title: 'Error',
                            text: 'Debes seleccionar fecha y hora',
                            icon: 'error'
                        });
                        swalInstance.close();
                        return;
                    }

                    const fechaHoraCompleta = `${fecha} ${hora}:00`;
                    console.log('Fecha/hora a enviar:', fechaHoraCompleta);

                    // Eliminar campos individuales y agregar solo fecha_hora
                    formData.delete('fecha');
                    formData.delete('hora');
                    formData.append('fecha_hora', fechaHoraCompleta);

                    // Agregar el ID de la cita si es edición
                    if (isEdit) {
                        formData.append('cita_id', isEdit);
                    }

                    const method = isEdit ? 'PUT' : 'POST';
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        let result;
                        try {
                            result = await response.json();
                        } catch (jsonError) {
                            console.error('Error parsing JSON:', jsonError);
                            throw new Error('Respuesta inválida del servidor');
                        }

                        await swalInstance.close();

                        //  Verificar si es advertencia ANTES de verificar response.ok
                        if (result.es_advertencia === true) {
                            console.log('🔶 Advertencia de tiempo extra detectada:', result);

                            // CREAR MENSAJE AMIGABLE BASADO EN EL NIVEL DE URGENCIA
                            let iconoAdvertencia = 'info';
                            let colorConfirm = '#4facfe';
                            let tituloAdvertencia = 'Información sobre tu cita';

                            if (result.nivel_urgencia === 'warning') {
                                iconoAdvertencia = 'warning';
                                colorConfirm = '#ffc107';
                                tituloAdvertencia = 'Tiempo adicional requerido';
                            } else if (result.nivel_urgencia === 'error') {
                                iconoAdvertencia = 'error';
                                colorConfirm = '#dc3545';
                                tituloAdvertencia = 'Atención necesaria';
                            }

                            // CONSTRUIR HTML AMIGABLE PARA EL USUARIO
                            let advertenciaHtml = `
        <div style="text-align: left; line-height: 1.5;">
            <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid ${colorConfirm};">
                <p style="margin: 0; font-size: 16px; color: #333;">
                    ${result.mensaje_usuario}
                </p>
            </div>
    `;

                            // AGREGAR DETALLES DE LA CITA SI ESTÁN DISPONIBLES
                            if (result.detalles_cita) {
                                const detalles = result.detalles_cita;
                                advertenciaHtml += `
            <div style="background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 6px; padding: 15px; margin-bottom: 15px;">
                <h6 style="margin: 0 0 10px 0; color: #495057; font-weight: bold;">📅 Detalles de tu cita:</h6>
                <div style="display: grid; grid-template-columns: auto 1fr; gap: 8px; font-size: 14px;">
                    <span style="color: #6c757d;">🕐 Inicio:</span>
                    <span style="font-weight: 500;">${detalles.hora_inicio}</span>
                    
                    <span style="color: #6c757d;">🏁 Finalización:</span>
                    <span style="font-weight: 500;">${detalles.hora_finalizacion_estimada}</span>
                    
                    <span style="color: #6c757d;">⏱️ Duración:</span>
                    <span style="font-weight: 500;">${detalles.duracion_servicios} minutos</span>
                    
                    <span style="color: #6c757d;">🏢 Cierre normal:</span>
                    <span style="font-weight: 500;">${detalles.horario_cierre_normal}</span>
                </div>
            </div>
        `;
                            }

                            // MOSTRAR BENEFICIOS SI ESTÁN DISPONIBLES
                            if (result.beneficios && result.beneficios.length > 0) {
                                advertenciaHtml += `
            <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 6px; padding: 15px; margin-bottom: 15px;">
                <h6 style="margin: 0 0 10px 0; color: #155724; font-weight: bold;">✨ Beneficios de tu cita:</h6>
                <ul style="margin: 0; padding-left: 20px; color: #155724;">
        `;

                                result.beneficios.forEach(beneficio => {
                                    advertenciaHtml +=
                                        `<li style="margin-bottom: 5px;">${beneficio}</li>`;
                                });

                                advertenciaHtml += `
                </ul>
            </div>
        `;
                            }

                            // AGREGAR NOTA IMPORTANTE SI ESTÁ DISPONIBLE
                            if (result.nota_importante) {
                                advertenciaHtml += `
            <div style="background-color: #cce5ff; border: 1px solid #80c1ff; border-radius: 6px; padding: 12px; margin-bottom: 15px;">
                <p style="margin: 0; font-size: 14px; color: #0066cc;">
                    <strong>💡 Ten en cuenta:</strong> ${result.nota_importante}
                </p>
            </div>
        `;
                            }

                            advertenciaHtml += `
            <div style="margin-top: 20px; text-align: center; font-size: 14px; color: #666;">
                <p style="margin: 0;">¿Deseas confirmar tu cita con estas condiciones?</p>
            </div>
        </div>
    `;

                            // MOSTRAR DIÁLOGO DE CONFIRMACIÓN  AMIGABLE
                            const advertenciaResult = await swalWithBootstrapButtons.fire({
                                title: tituloAdvertencia,
                                html: advertenciaHtml,
                                icon: iconoAdvertencia,
                                showCancelButton: true,
                                confirmButtonText: '✅ Sí, confirmar mi cita',
                                cancelButtonText: '❌ No, cambiar horario',
                                confirmButtonColor: colorConfirm,
                                cancelButtonColor: '#6c757d',
                                customClass: {
                                    popup: 'swal-wide'
                                },
                                width: '600px'
                            });

                            if (advertenciaResult.isConfirmed) {
                                console.log('🔥 Usuario confirmó continuar con tiempo extra');
                                await forceCreateCita(formData);
                            } else {
                                console.log('❌ Usuario canceló la creación con tiempo extra');
                            }
                            return;
                        }
                        //  DESPUÉS verificar errores normales
                        if (!response.ok) {
                            throw result;
                        }

                        if (!result.success) {
                            throw new Error(result.message || 'Error desconocido');
                        }

                        if (!result.data) {
                            throw new Error('Datos de respuesta incompletos');
                        }

                        // Éxito - cerrar modal
                        closeCitaModal();

                        const citaData = result.data;
                        const fechaHoraCita = citaData.fecha_hora || fechaHoraCompleta;

                        let fechaMostrar, horaMostrar;
                        try {
                            const fechaObj = new Date(fechaHoraCita);
                            fechaMostrar = fechaObj.toLocaleDateString('es-ES', {
                                weekday: 'long',
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });
                            horaMostrar = fechaObj.toLocaleTimeString('es-ES', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        } catch (dateError) {
                            console.warn('Error formateando fecha:', dateError);
                            fechaMostrar = fecha;
                            horaMostrar = hora;
                        }

                        await swalWithBootstrapButtons.fire({
                            title: isEdit ? '¡Cita actualizada!' : '¡Cita agendada!',
                            html: `
                        <div style="text-align: left; margin-top: 15px;">
                            <p><strong>Fecha:</strong> ${fechaMostrar}</p>
                            <p><strong>Hora:</strong> ${horaMostrar}</p>
                            <p><strong>Servicios:</strong> ${citaData.servicios_nombres || 'Servicios seleccionados'}</p>
                            <p><strong>Duración:</strong> ${citaData.duracion_total || 'N/A'} minutos</p>
                            <p><strong>Vehículo:</strong> ${citaData.vehiculo_marca || ''} ${citaData.vehiculo_modelo || ''}</p>
                            ${citaData.vehiculo_placa ? `<p><strong>Placa:</strong> ${citaData.vehiculo_placa}</p>` : ''}
                            ${citaData.precio_total ? `<p><strong>Total:</strong> ${citaData.precio_total}</p>` : ''}
                        </div>
                    `,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });

                        await updateCitasSections();

                    } catch (error) {
                        console.error('Error completo:', error);
                        await swalInstance.close();

                        let errorMessage = 'Ocurrió un error al procesar tu cita.';
                        let errorDetails = '';
                        let showAvailableTimes = false;
                        let availableTimes = [];

                        // MANEJO DE ERRORES NORMAL (sin advertencias)
                        if (error instanceof Error) {
                            errorMessage = error.message;
                        } else if (typeof error === 'object' && error !== null) {
                            if (error.message) {
                                errorMessage = error.message;
                            }
                            if (error.errors) {
                                errorDetails = Object.values(error.errors).flat().join('<br>');
                            }

                            // Manejar horarios disponibles en caso de conflicto
                            if (error.horarios_disponibles && Array.isArray(error
                                    .horarios_disponibles)) {
                                showAvailableTimes = true;
                                availableTimes = error.horarios_disponibles;
                            } else if (error.data && error.data.available_times) {
                                showAvailableTimes = true;
                                availableTimes = error.data.available_times;
                            }
                        }

                        // Mostrar error normal con horarios disponibles si aplica
                        let errorHtml = `
    <div style="text-align: left;">
        <p>⚠️ <strong>Horario no disponible</strong></p>
        <p>Ya existe una cita programada a esta hora.</p>
        ${errorDetails ? `<p style="color: #dc3545; margin-top: 10px;">${errorDetails}</p>` : ''}
`;

                        if (showAvailableTimes && availableTimes.length > 0) {
                            errorHtml += `
        <div style="margin-top: 15px;">
            <p><strong>💡 Horarios disponibles sugeridos:</strong></p>
            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px; background-color: #f8f9fa;">
                ${availableTimes.map(time => `<span class="badge badge-success mr-1 mb-1">${time}</span>`).join('')}
            </div>
        </div>
    `;
                        }

                        errorHtml += `
                        <p style="margin-top: 15px; font-size: 0.9em; color: #666;">
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

        // Función auxiliar mejorada para mostrar información de duración
        function mostrarInfoDuracion() {
            const serviciosSeleccionados = document.querySelectorAll('input[name="servicios[]"]:checked');
            const horaSeleccionada = document.getElementById('hora').value;

            if (serviciosSeleccionados.length > 0 && horaSeleccionada) {
                const duracionTotal = calcularDuracionServiciosSeleccionados();
                validateServiceDuration(horaSeleccionada, duracionTotal);

                // Mostrar información de duración total
                const infoContainer = document.getElementById('servicios-info') || createServiciosInfo();
                if (infoContainer) {
                    infoContainer.innerHTML = `
                <div class="alert alert-info mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clock mr-2"></i>Duración total: ${duracionTotal} minutos</span>
                        <span class="badge badge-primary">${Math.floor(duracionTotal / 60)}h ${duracionTotal % 60}m</span>
                    </div>
                </div>
            `;
                }
            }
        }

        function createServiciosInfo() {
            const serviciosContainer = document.getElementById('serviciosContainer');
            if (!serviciosContainer) return null;

            const infoContainer = document.createElement('div');
            infoContainer.id = 'servicios-info';

            serviciosContainer.parentNode.insertBefore(infoContainer, serviciosContainer.nextSibling);

            return infoContainer;
        }


        //  ESTILOS CSS PARA EL MODAL MÁS ANCHO
        const style = document.createElement('style');
        style.textContent = `
    .swal-wide {
        max-width: 90vw !important;
    }
    
    .swal2-popup.swal-wide {
        font-family: inherit;
    }
    
    .swal2-popup.swal-wide .swal2-html-container {
        max-height: 70vh;
        overflow-y: auto;
    }
`;
        document.head.appendChild(style);

        // Codigo para el scroll personalizado
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar scroll personalizado para Próximas Citas
            setupCustomScroll('proximas-citas-container', 'proximas-citas-scrollbar', 'proximas-citas-thumb');

            // Configurar scroll personalizado para Historial
            setupCustomScroll('historial-container', 'historial-scrollbar', 'historial-thumb');
        });

        function setupCustomScroll(containerId, scrollbarId, thumbId) {
            const container = document.getElementById(containerId);
            const scrollbar = document.getElementById(scrollbarId);
            const thumb = document.getElementById(thumbId);

            if (!container || !scrollbar || !thumb) return;

            // Calcular la relación entre el tamaño del thumb y el contenido
            function updateThumb() {
                const scrollRatio = container.clientHeight / container.scrollHeight;
                const thumbHeight = Math.max(scrollRatio * scrollbar.clientHeight, 30);
                thumb.style.height = `${thumbHeight}px`;

                const maxScroll = container.scrollHeight - container.clientHeight;
                const thumbPosition = (container.scrollTop / maxScroll) * (scrollbar.clientHeight - thumbHeight);
                thumb.style.top = `${thumbPosition}px`;
            }

            // Actualizar al cargar y al redimensionar
            updateThumb();
            window.addEventListener('resize', updateThumb);

            // Mover el scroll al arrastrar el thumb
            let isDragging = false;

            thumb.addEventListener('mousedown', function(e) {
                isDragging = true;
                const startY = e.clientY;
                const startTop = parseFloat(thumb.style.top) || 0;

                function moveThumb(e) {
                    if (!isDragging) return;

                    const deltaY = e.clientY - startY;
                    let newTop = startTop + deltaY;

                    const maxTop = scrollbar.clientHeight - thumb.clientHeight;
                    newTop = Math.max(0, Math.min(maxTop, newTop));

                    thumb.style.top = `${newTop}px`;

                    // Mover el contenido
                    const scrollRatio = newTop / (scrollbar.clientHeight - thumb.clientHeight);
                    container.scrollTop = scrollRatio * (container.scrollHeight - container.clientHeight);
                }

                function stopDrag() {
                    isDragging = false;
                    document.removeEventListener('mousemove', moveThumb);
                    document.removeEventListener('mouseup', stopDrag);
                }

                document.addEventListener('mousemove', moveThumb);
                document.addEventListener('mouseup', stopDrag);
                e.preventDefault();
            });

            // Mover el thumb al hacer scroll con la rueda del mouse
            container.addEventListener('scroll', function() {
                if (!isDragging) {
                    updateThumb();
                }
            });

            // Permitir hacer clic en la barra para mover el scroll
            scrollbar.addEventListener('click', function(e) {
                if (e.target === thumb) return;

                const clickPosition = e.clientY - scrollbar.getBoundingClientRect().top;
                const thumbHeight = parseFloat(thumb.style.height);
                const newTop = clickPosition - (thumbHeight / 2);

                const maxTop = scrollbar.clientHeight - thumbHeight;
                const adjustedTop = Math.max(0, Math.min(maxTop, newTop));

                thumb.style.top = `${adjustedTop}px`;

                // Mover el contenido
                const scrollRatio = adjustedTop / (scrollbar.clientHeight - thumbHeight);
                container.scrollTop = scrollRatio * (container.scrollHeight - container.clientHeight);
            });
        }
        // Función para debug de conflictos de horario
        async function debugHorarioConflict(fecha, hora, duracion) {
            try {
                console.log('🔍 Debugging horario:', {
                    fecha,
                    hora,
                    duracion
                });

                const response = await fetch('{{ route('cliente.debug-horarios') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        fecha: fecha,
                        hora: hora,
                        duracion: duracion
                    })
                });

                const data = await response.json();

                if (data.success) {
                    console.group('🔍 DEBUG HORARIOS - RESULTADO');
                    console.log('📍 Cita propuesta:', {
                        'Fecha/Hora': data.data.fecha_cita,
                        'Duración': data.data.duracion_minutos + ' minutos',
                        'Hora fin': data.data.hora_fin
                    });

                    console.log('📋 Citas superpuestas encontradas:', data.data.citas_superpuestas);
                    console.log('✅ Horarios disponibles:', data.data.horarios_disponibles);
                    console.log('ℹ️ ' + data.data.query_explicacion);
                    console.groupEnd();

                    // Mostrar también en alerta para fácil visualización
                    if (data.data.citas_superpuestas.length > 0) {
                        Swal.fire({
                            title: '🔍 Debug - Conflictos Encontrados',
                            html: `
                        <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                            <p><strong>Cita propuesta:</strong> ${data.data.fecha_cita} (${data.data.duracion_minutos} min)</p>
                            <p><strong>Conflictos encontrados:</strong> ${data.data.citas_superpuestas.length}</p>
                            
                            ${data.data.citas_superpuestas.map(cita => `
                                                                                                                                                        <div style="border: 1px solid #ff6b6b; padding: 10px; margin: 10px 0; border-radius: 5px;">
                                                                                                                                                            <p><strong>Cita ID:</strong> ${cita.id}</p>
                                                                                                                                                            <p><strong>Horario:</strong> ${cita.fecha_hora} (${cita.duracion_total} min)</p>
                                                                                                                                                            <p><strong>Servicios:</strong> ${cita.servicios.join(', ')}</p>
                                                                                                                                                            <p><strong>Vehículo:</strong> ${cita.vehiculo}</p>
                                                                                                                                                            <p><strong>Estado:</strong> ${cita.estado}</p>
                                                                                                                                                        </div>
                                                                                                                                                    `).join('')}
                            
                            <p><strong>Horarios disponibles:</strong> ${data.data.horarios_disponibles.join(', ') || 'Ninguno'}</p>
                        </div>
                    `,
                            width: '800px',
                            confirmButtonText: 'Entendido'
                        });
                    } else {
                        Swal.fire({
                            title: '✅ Sin conflictos',
                            text: 'No se encontraron citas superpuestas para este horario',
                            icon: 'success'
                        });
                    }

                } else {
                    console.error('Error en debug:', data.message);
                    Swal.fire('Error', 'Error en debug: ' + data.message, 'error');
                }

                return data;
            } catch (error) {
                console.error('Error en debug:', error);
                Swal.fire('Error', 'Error al ejecutar debug: ' + error.message, 'error');
            }
        }

        // Función para calcular duración total de servicios seleccionados
        function calcularDuracionTotal() {
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

        function ejecutarDebug() {
            const fecha = document.getElementById('fecha').value;
            const hora = document.getElementById('hora').value;
            const duracion = calcularDuracionTotal();

            if (fecha && hora) {
                debugHorarioConflict(fecha, hora, duracion);
            } else {
                Swal.fire('Info', 'Selecciona fecha y hora primero', 'info');
            }
        }

        /*=========================================================
                        FUNCIONALIDAD DE AGENDADO RÁPIDO
                        =========================================================*/

        // Función principal para agendar servicio rápidamente
        async function quickBookService(servicioId, categoria, buttonElement) {
            // Agregar efecto de loading al botón
            if (buttonElement) {
                buttonElement.classList.add('loading');
                buttonElement.disabled = true;
            }

            try {
                console.log('Iniciando agendado rápido para servicio:', servicioId, 'categoría:', categoria);

                // Verificar si el usuario tiene vehículos
                const vehiculosCompatibles = await getVehiculosCompatibles(categoria);

                if (vehiculosCompatibles.length === 0) {
                    await showNoVehiclesAlert(categoria);
                    return;
                }

                // Siempre permitir al usuario elegir el vehículo para mayor claridad
                const vehiculoSeleccionado = await selectVehicleDialog(vehiculosCompatibles, categoria);
                if (!vehiculoSeleccionado) return; // Usuario canceló

                // Mostrar diálogo de selección de fecha y hora
                const citaData = await selectDateTimeDialog(servicioId, vehiculoSeleccionado.id);
                if (!citaData) return; // Usuario canceló

                // Crear la cita
                await createQuickBooking(vehiculoSeleccionado.id, servicioId, citaData);

            } catch (error) {
                console.error('Error en agendado rápido:', error);
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Hubo un problema al procesar tu solicitud. Por favor, inténtalo de nuevo.',
                    icon: 'error'
                });
            } finally {
                // Remover efecto de loading al botón
                if (buttonElement) {
                    buttonElement.classList.remove('loading');
                    buttonElement.disabled = false;
                }
            }
        }

        // Obtener vehículos compatibles con la categoría del servicio
        async function getVehiculosCompatibles(categoria) {
            const vehiculos = @json($mis_vehiculos ?? []);

            // Filtrar vehículos por categoría compatible
            return vehiculos.filter(vehiculo => {
                return vehiculo.tipo.toLowerCase() === categoria.toLowerCase();
            });
        }

        // Mostrar alerta cuando no hay vehículos compatibles
        async function showNoVehiclesAlert(categoria) {
            const result = await swalWithBootstrapButtons.fire({
                title: 'Sin vehículos compatibles',
                html: `
                    <p>No tienes vehículos de tipo <strong>${categoria}</strong> registrados.</p>
                    <p>¿Te gustaría registrar un nuevo vehículo?</p>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Registrar Vehículo',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                window.location.href = '{{ route('vehiculos.create') }}';
            }
        }

        // Diálogo para seleccionar vehículo
        async function selectVehicleDialog(vehiculos, categoria) {
            const vehiculosCount = vehiculos.length;
            const categoryName = categoria === 'sedan' ? 'sedán' : categoria === 'pickup' ? 'SUV/Pickup' : categoria;

            const {
                value: vehiculoId
            } = await swalLargeModal.fire({
                title: `Selecciona tu vehículo ${categoryName.toUpperCase()}`,
                html: `
                    <div style="text-align: left;">
                        <p style="margin-bottom: 15px;">
                            ${vehiculosCount === 1 ? 
                                `Confirma el vehículo para tu servicio:` : 
                                `Tienes ${vehiculosCount} vehículos compatibles. ¿Cuál deseas lavar?`
                            }
                        </p>
                        <div style="max-height: 300px; overflow-y: auto;">
                            ${vehiculos.map(v => `
                                                            <label style="display: block; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;" 
                                                                   onmouseover="this.style.backgroundColor='#f0f8ff'" 
                                                                   onmouseout="this.style.backgroundColor='white'">
                                                                <input type="radio" name="vehicle-select" value="${v.id}" style="margin-right: 10px;">
                                                                <div>
                                                                    <strong style="color: #2c3e50;">${v.marca} ${v.modelo}</strong>
                                                                    ${v.placa ? `<br><small style="color: #7f8c8d;">Placa: ${v.placa}</small>` : ''}
                                                                    <br><small style="color: #27ae60; font-weight: 600;">Tipo: ${categoryName}</small>
                                                                    ${v.color ? `<br><small style="color: #8e44ad;">Color: ${v.color}</small>` : ''}
                                                                </div>
                                                            </label>
                                                        `).join('')}
                        </div>
                    </div>
                `,
                icon: 'car',
                showCancelButton: true,
                confirmButtonText: vehiculosCount === 1 ? 'Confirmar vehículo' : 'Continuar con este vehículo',
                cancelButtonText: 'Cancelar',
                width: '500px',
                preConfirm: () => {
                    const selected = document.querySelector('input[name="vehicle-select"]:checked');
                    if (!selected) {
                        Swal.showValidationMessage('Debes seleccionar un vehículo');
                        return false;
                    }
                    return parseInt(selected.value);
                }
            });

            if (vehiculoId) {
                return vehiculos.find(v => v.id === vehiculoId);
            }
            return null;
        }

        // Diálogo para seleccionar fecha y hora
        async function selectDateTimeDialog(servicioId, vehiculoId) {
            const today = new Date();
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 1);

            const {
                value: formData
            } = await swalLargeModal.fire({
                title: 'Selecciona fecha y hora',
                html: `
                    <div style="text-align: left;">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Fecha:</label>
                            <input type="date" id="swal-date-input" class="swal2-input" 
                                   min="${today.toISOString().split('T')[0]}" 
                                   max="${maxDate.toISOString().split('T')[0]}" 
                                   style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Hora:</label>
                            <select id="swal-hour-input" class="swal2-input" style="width: 100%;">
                                <option value="">Primero selecciona una fecha</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Observaciones (opcional):</label>
                            <textarea id="swal-observations-input" class="swal2-textarea" 
                                      placeholder="Información adicional sobre el servicio..." 
                                      style="width: 100%; height: 60px;"></textarea>
                        </div>
                    </div>
                `,
                icon: 'calendar',
                showCancelButton: true,
                confirmButtonText: 'Agendar Cita',
                cancelButtonText: 'Cancelar',
                width: '500px',
                didOpen: () => {
                    const dateInput = document.getElementById('swal-date-input');
                    const hourInput = document.getElementById('swal-hour-input');

                    dateInput.addEventListener('change', async function() {
                        if (this.value) {
                            hourInput.innerHTML =
                                '<option value="">Cargando horarios...</option>';
                            const horarios = await loadAvailableHoursForQuickBook(this.value);

                            if (horarios.length === 0) {
                                hourInput.innerHTML =
                                    '<option value="">No hay horarios disponibles</option>';
                            } else {
                                hourInput.innerHTML =
                                    '<option value="">Selecciona una hora</option>' +
                                    horarios.map(h => `<option value="${h}">${h}</option>`)
                                    .join('');
                            }
                        }
                    });
                },
                preConfirm: () => {
                    const fecha = document.getElementById('swal-date-input').value;
                    const hora = document.getElementById('swal-hour-input').value;
                    const observaciones = document.getElementById('swal-observations-input').value;

                    if (!fecha) {
                        Swal.showValidationMessage('Debes seleccionar una fecha');
                        return false;
                    }
                    if (!hora) {
                        Swal.showValidationMessage('Debes seleccionar una hora');
                        return false;
                    }

                    return {
                        fecha: fecha,
                        hora: hora,
                        observaciones: observaciones
                    };
                }
            });

            return formData;
        }

        // Cargar horarios disponibles para el agendado rápido
        async function loadAvailableHoursForQuickBook(fecha) {
            try {
                const baseUrl = '{{ url('/cliente/horarios-disponibles') }}';
                const response = await fetch(`${baseUrl}/${fecha}`);
                if (!response.ok) {
                    throw new Error('Error al cargar horarios');
                }

                const horarios = await response.json();
                return horarios.map(h => h.hora).filter(h => h);
            } catch (error) {
                console.error('Error cargando horarios para agendado rápido:', error);
                return [];
            }
        }

        // Crear la cita con agendado rápido
        async function createQuickBooking(vehiculoId, servicioId, citaData) {
            try {
                const fechaHora = `${citaData.fecha} ${citaData.hora}`;

                // Debug: verificar token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                console.log('CSRF Token:', csrfToken ? 'Existe' : 'No encontrado');

                const requestData = {
                    vehiculo_id: vehiculoId,
                    fecha_hora: fechaHora,
                    servicios: [servicioId],
                    observaciones: citaData.observaciones || null
                };

                console.log('Datos a enviar:', requestData);

                const response = await fetch('{{ route('cliente.citas.store-simple') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);

                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    // Cita creada exitosamente
                    const citaData = data.data || {};
                    await swalLargeModal.fire({
                        title: '¡Cita agendada!',
                        html: `
                            <div style="text-align: center;">
                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 3rem; margin-bottom: 15px;"></i>
                                <p><strong>Tu cita ha sido agendada exitosamente</strong></p>
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; text-align: left;">
                                    <p><strong>Fecha:</strong> ${new Date(fechaHora).toLocaleDateString('es-ES', { 
                                        weekday: 'long', 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    })}</p>
                                    <p><strong>Hora:</strong> ${citaData.hora || 'No disponible'}</p>
                                    <p><strong>Servicio:</strong> ${citaData.servicios_nombres || 'Servicio seleccionado'}</p>
                                    <p><strong>Vehículo:</strong> ${citaData.vehiculo_marca || ''} ${citaData.vehiculo_modelo || ''}</p>
                                    ${citaData.duracion_total ? `<p><strong>Duración estimada:</strong> ${citaData.duracion_total} minutos</p>` : ''}
                                    <p><strong>Precio:</strong> $${citaData.precio_total || '0.00'}</p>
                                </div>
                                <p style="color: #6c757d; font-size: 0.9rem;">
                                    Recibirás una confirmación pronto. Puedes ver el estado de tu cita en la sección "Próximas Citas".
                                </p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Perfecto',
                        timer: 0,
                        showConfirmButton: true
                    });

                    // Recargar datos del dashboard
                    location.reload();

                } else if (data.es_advertencia) {
                    // Mostrar advertencia de tiempo extra
                    const confirmarExtension = await swalLargeModal.fire({
                        title: data.message,
                        html: `
                            <div style="text-align: left;">
                                <p style="margin-bottom: 15px;">${data.mensaje_usuario}</p>
                                <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 15px 0;">
                                    <h4 style="margin-top: 0; color: #1976d2;">Detalles de la cita:</h4>
                                    <p><strong>Hora de inicio:</strong> ${data.detalles_cita.hora_inicio}</p>
                                    <p><strong>Hora estimada de finalización:</strong> ${data.detalles_cita.hora_finalizacion_estimada}</p>
                                    <p><strong>Tiempo extra requerido:</strong> ${data.detalles_cita.tiempo_extra_minutos} minutos</p>
                                </div>
                                <div style="background: #f1f8e9; padding: 15px; border-radius: 8px; margin: 15px 0;">
                                    <h4 style="margin-top: 0; color: #388e3c;">Beneficios:</h4>
                                    <ul style="margin: 0; padding-left: 20px;">
                                        ${data.beneficios.map(b => `<li>${b}</li>`).join('')}
                                    </ul>
                                </div>
                                <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 0;">
                                    ${data.nota_importante}
                                </p>
                            </div>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, agendar con tiempo extra',
                        cancelButtonText: 'Cancelar y elegir otro horario'
                    });

                    if (confirmarExtension.isConfirmed) {
                        // Reenviar solicitud con fuerza
                        await createQuickBookingForced(vehiculoId, servicioId, citaData);
                    }

                } else {
                    // Error al crear la cita
                    let errorMessage = data.message || 'Error desconocido';
                    let htmlContent = `<p>${errorMessage}</p>`;

                    if (data.horarios_disponibles && data.horarios_disponibles.length > 0) {
                        htmlContent += `
                            <div style="margin-top: 15px; text-align: left;">
                                <p><strong>Horarios disponibles para esta fecha:</strong></p>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 5px; margin-top: 10px;">
                                    ${data.horarios_disponibles.slice(0, 12).map(h => 
                                        `<span style="background: #e9ecef; padding: 5px 8px; border-radius: 4px; text-align: center; font-size: 0.9rem;">${h}</span>`
                                    ).join('')}
                                </div>
                            </div>
                        `;
                    }

                    await swalWithBootstrapButtons.fire({
                        title: 'No se pudo agendar',
                        html: htmlContent,
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                }

            } catch (error) {
                console.error('Error al crear cita rápida:', error);

                // Mostrar más detalles del error
                let errorMessage = 'No se pudo procesar tu solicitud.';
                if (error.message) {
                    errorMessage += ' Detalle: ' + error.message;
                }

                await swalWithBootstrapButtons.fire({
                    title: 'Error de conexión',
                    text: errorMessage,
                    icon: 'error',
                    footer: 'Por favor, verifica tu conexión e inténtalo de nuevo.'
                });
            }
        }

        // Crear cita con fuerza (tiempo extra confirmado)
        async function createQuickBookingForced(vehiculoId, servicioId, citaData) {
            return await createQuickBooking(vehiculoId, servicioId, citaData); // Ya tiene X-Force-Create: true
        }

        // Función para mostrar alerta cuando no hay servicios disponibles (fallback)
        function showNoServicesAlert() {
            swalWithBootstrapButtons.fire({
                title: 'Servicio no disponible',
                text: 'Este servicio no está disponible temporalmente. Por favor, contacta directamente al establecimiento.',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        }

        // Función para mostrar todos los servicios en un modal
        async function verTodosLosServiciosCliente() {
            try {
                const response = await fetch('/cliente/servicios/all');
                if (!response.ok) {
                    throw new Error('Error al cargar los servicios');
                }
                const servicios = await response.json();

                let serviciosHtml = '';
                let serviciosPorCategoria = {};

                // Agrupar servicios por categoría
                servicios.forEach(servicio => {
                    const categoria = servicio.categoria || 'otros';
                    if (!serviciosPorCategoria[categoria]) {
                        serviciosPorCategoria[categoria] = [];
                    }
                    serviciosPorCategoria[categoria].push(servicio);
                });

                // Generar HTML por categorías
                Object.keys(serviciosPorCategoria).forEach(categoria => {
                    const categoriaIcon = categoria === 'sedan' ? '🚗' :
                        categoria === 'pickup' ? '🚙' :
                        categoria === 'moto' ? '🏍️' : '🚙';
                    const categoriaNombre = categoria === 'sedan' ? 'Sedán' :
                        categoria === 'pickup' ? 'Pickup/SUV' :
                        categoria === 'moto' ? 'Motocicleta' :
                        categoria.charAt(0).toUpperCase() + categoria.slice(1);

                    serviciosHtml += `
                        <div style="margin-bottom: 25px;">
                            <h4 style="color: #4facfe; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #4facfe; padding-bottom: 8px;">
                                ${categoriaIcon} ${categoriaNombre}
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                    `;

                    serviciosPorCategoria[categoria].forEach(servicio => {
                        serviciosHtml += `
                            <div class="service-card-modal" style="background: #f8f9fa; border-radius: 12px; padding: 18px; border: 1px solid #e3e8ef; transition: all 0.3s ease;">
                                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; margin-right: 12px;">
                                        <i class="fas fa-spray-can"></i>
                                    </div>
                                    <div>
                                        <h5 style="margin: 0; color: #2c3e50; font-size: 16px;">${servicio.nombre}</h5>
                                        <div style="color: #4facfe; font-weight: 600; font-size: 18px;">$${parseFloat(servicio.precio).toFixed(2)}</div>
                                    </div>
                                </div>
                                <p style="color: #666; font-size: 14px; margin-bottom: 12px; line-height: 1.4;">${servicio.descripcion}</p>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <span style="color: #666; font-size: 13px;">⏱️ ${servicio.duracion_formatted}</span>
                                    <span style="background: rgba(79, 172, 254, 0.1); color: #4facfe; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                        ${servicio.activo ? 'Disponible' : 'No disponible'}
                                    </span>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="quickBookService(${servicio.id}, '${servicio.categoria}', this)" style="width: 100%; padding: 10px; font-size: 14px;">
                                    <i class="fas fa-calendar-plus"></i> Agendar Ahora
                                </button>
                            </div>
                        `;
                    });

                    serviciosHtml += '</div></div>';
                });

                swalLargeModal.fire({
                    title: 'Todos los Servicios Disponibles',
                    html: `
                        <div style="text-align: left; max-height: 600px; overflow-y: auto; padding: 0 10px;">
                            <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                                <p style="margin: 0; color: #1976d2;">
                                    <i class="fas fa-info-circle"></i> 
                                    Selecciona cualquier servicio para agendar tu cita
                                </p>
                            </div>
                            ${serviciosHtml}
                        </div>
                    `,
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Cerrar',
                    width: '1000px',
                    customClass: {
                        popup: 'swal-large-modal services-catalog-modal'
                    }
                });

                // Agregar estilos adicionales al modal
                const style = document.createElement('style');
                style.textContent = `
                    .services-catalog-modal .service-card-modal:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                        border-color: #4facfe;
                    }
                `;
                document.head.appendChild(style);

            } catch (error) {
                console.error('Error al cargar servicios:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudieron cargar los servicios. Por favor, intenta de nuevo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }

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

            // ==============================================
            // FUNCIONES PARA MODALES DE NAVEGACIÓN
            // ==============================================

            function abrirModalVehiculos() {
                const modal = document.getElementById('vehiculosNavModal');
                if (!modal) {
                    console.error('Modal vehiculosNavModal no encontrado');
                    return;
                }
                modal.style.setProperty('display', 'flex', 'important');
                const content = document.getElementById('vehiculosNavContent');

                content.innerHTML = `
                    <iframe
                        id="vehiculosNavIframe"
                        src="{{ route('vehiculos.index') }}"
                        sandbox="allow-same-origin allow-scripts allow-forms allow-modals allow-popups allow-downloads"
                        style="width: 100%; height: 100%; border: none; opacity: 0; transition: opacity 0.3s ease;"
                        onload="
                            this.style.opacity='1';
                            try {
                                const iframeDoc = this.contentDocument || this.contentWindow.document;

                                // Ocultar botones de volver/regresar
                                const allElements = iframeDoc.querySelectorAll('a, button');
                                allElements.forEach(element => {
                                    const text = element.textContent.toLowerCase();
                                    const href = element.getAttribute('href') || '';
                                    if (text.includes('volver') || text.includes('regresar') ||
                                        text.includes('dashboard') || href.includes('/cliente/dashboard')) {
                                        element.style.display = 'none';
                                    }
                                });

                                // Ajustar diseño compacto
                                const container = iframeDoc.querySelector('.container, .container-fluid');
                                if (container) {
                                    container.style.padding = '10px 15px';
                                    container.style.maxWidth = '100%';
                                }

                                iframeDoc.querySelectorAll('.card-body').forEach(card => {
                                    card.style.padding = '0.8rem';
                                });

                                iframeDoc.querySelectorAll('.mb-4, .mb-3').forEach(elem => {
                                    elem.style.marginBottom = '0.5rem';
                                });

                                // Observer para modales internos
                                const observer = new MutationObserver((mutations) => {
                                    mutations.forEach((mutation) => {
                                        mutation.addedNodes.forEach((node) => {
                                            if (node.nodeType === 1 && node.classList &&
                                                (node.classList.contains('modal') || node.classList.contains('swal2-container'))) {
                                                node.style.zIndex = '1100';
                                                const backdrop = iframeDoc.querySelector('.modal-backdrop, .swal2-backdrop');
                                                if (backdrop) backdrop.style.zIndex = '1090';
                                            }
                                        });
                                    });
                                });

                                observer.observe(iframeDoc.body, { childList: true, subtree: true });
                            } catch(e) {
                                console.log('No se pudo modificar iframe:', e);
                            }
                        "
                    ></iframe>
                `;
            }

            function abrirModalConfiguracion() {
                const modal = document.getElementById('configuracionNavModal');
                if (!modal) {
                    console.error('Modal configuracionNavModal no encontrado');
                    return;
                }
                modal.style.setProperty('display', 'flex', 'important');
                const content = document.getElementById('configuracionNavContent');

                content.innerHTML = `
                    <iframe
                        id="configuracionNavIframe"
                        src="{{ route('configuracion.index') }}"
                        sandbox="allow-same-origin allow-scripts allow-forms allow-modals allow-popups allow-downloads"
                        style="width: 100%; height: 100%; border: none; opacity: 0; transition: opacity 0.3s ease;"
                        onload="
                            this.style.opacity='1';
                            try {
                                const iframeDoc = this.contentDocument || this.contentWindow.document;

                                // Ocultar botones de volver/regresar
                                const allElements = iframeDoc.querySelectorAll('a, button');
                                allElements.forEach(element => {
                                    const text = element.textContent.toLowerCase();
                                    const href = element.getAttribute('href') || '';
                                    if (text.includes('volver') || text.includes('regresar') ||
                                        text.includes('dashboard') || href.includes('/cliente/dashboard')) {
                                        element.style.display = 'none';
                                    }
                                });

                                // Ajustar diseño compacto
                                const container = iframeDoc.querySelector('.container, .container-fluid');
                                if (container) {
                                    container.style.padding = '10px 15px';
                                    container.style.maxWidth = '100%';
                                }

                                iframeDoc.querySelectorAll('.card-body').forEach(card => {
                                    card.style.padding = '0.8rem';
                                });

                                iframeDoc.querySelectorAll('.mb-4, .mb-3').forEach(elem => {
                                    elem.style.marginBottom = '0.5rem';
                                });

                                // Observer para modales internos
                                const observer = new MutationObserver((mutations) => {
                                    mutations.forEach((mutation) => {
                                        mutation.addedNodes.forEach((node) => {
                                            if (node.nodeType === 1 && node.classList &&
                                                (node.classList.contains('modal') || node.classList.contains('swal2-container'))) {
                                                node.style.zIndex = '1100';
                                                const backdrop = iframeDoc.querySelector('.modal-backdrop, .swal2-backdrop');
                                                if (backdrop) backdrop.style.zIndex = '1090';
                                            }
                                        });
                                    });
                                });

                                observer.observe(iframeDoc.body, { childList: true, subtree: true });
                            } catch(e) {
                                console.log('No se pudo modificar iframe:', e);
                            }
                        "
                    ></iframe>
                `;
            }

            function closeModalNav(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.setProperty('display', 'none', 'important');
                }
            }
        </script>
    @endpush

    <!-- Scripts adicionales para modales de navegación -->
    <script>
        // ==============================================
        // FUNCIONES PARA MODALES DE NAVEGACIÓN (GLOBAL)
        // ==============================================

        function abrirModalVehiculos() {
            console.log('Intentando abrir modal de vehículos...');
            const modal = document.getElementById('vehiculosNavModal');
            if (!modal) {
                console.error('Modal vehiculosNavModal no encontrado');
                return;
            }
            console.log('Modal encontrado, mostrando...');
            modal.style.setProperty('display', 'flex', 'important');
            const content = document.getElementById('vehiculosNavContent');

            content.innerHTML = `
                <iframe
                    id="vehiculosNavIframe"
                    src="{{ route('vehiculos.index') }}"
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals allow-popups allow-downloads"
                    style="width: 100%; height: 100%; border: none; opacity: 0; transition: opacity 0.3s ease;"
                    onload="
                        this.style.opacity='1';
                        try {
                            const iframeDoc = this.contentDocument || this.contentWindow.document;

                            // Ocultar botones de volver/regresar
                            const allElements = iframeDoc.querySelectorAll('a, button');
                            allElements.forEach(element => {
                                const text = element.textContent.toLowerCase();
                                const href = element.getAttribute('href') || '';
                                if (text.includes('volver') || text.includes('regresar') ||
                                    text.includes('dashboard') || href.includes('/cliente/dashboard')) {
                                    element.style.display = 'none';
                                }
                            });

                            // Ajustar diseño compacto
                            const container = iframeDoc.querySelector('.container, .container-fluid');
                            if (container) {
                                container.style.padding = '10px 15px';
                                container.style.maxWidth = '100%';
                            }

                            iframeDoc.querySelectorAll('.card-body').forEach(card => {
                                card.style.padding = '0.8rem';
                            });

                            iframeDoc.querySelectorAll('.mb-4, .mb-3').forEach(elem => {
                                elem.style.marginBottom = '0.5rem';
                            });

                            // Observer para modales internos
                            const observer = new MutationObserver((mutations) => {
                                mutations.forEach((mutation) => {
                                    mutation.addedNodes.forEach((node) => {
                                        if (node.nodeType === 1 && node.classList &&
                                            (node.classList.contains('modal') || node.classList.contains('swal2-container'))) {
                                            node.style.zIndex = '1100';
                                            const backdrop = iframeDoc.querySelector('.modal-backdrop, .swal2-backdrop');
                                            if (backdrop) backdrop.style.zIndex = '1090';
                                        }
                                    });
                                });
                            });

                            observer.observe(iframeDoc.body, { childList: true, subtree: true });
                        } catch(e) {
                            console.log('No se pudo modificar iframe:', e);
                        }
                    "
                ></iframe>
            `;
        }

        function abrirModalConfiguracion() {
            console.log('Intentando abrir modal de configuración...');
            const modal = document.getElementById('configuracionNavModal');
            if (!modal) {
                console.error('Modal configuracionNavModal no encontrado');
                return;
            }
            console.log('Modal encontrado, mostrando...');
            modal.style.setProperty('display', 'flex', 'important');
            const content = document.getElementById('configuracionNavContent');

            content.innerHTML = `
                <iframe
                    id="configuracionNavIframe"
                    src="{{ route('configuracion.index') }}"
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals allow-popups allow-downloads"
                    style="width: 100%; height: 100%; border: none; opacity: 0; transition: opacity 0.3s ease;"
                    onload="
                        this.style.opacity='1';
                        try {
                            const iframeDoc = this.contentDocument || this.contentWindow.document;

                            // Ocultar botones de volver/regresar
                            const allElements = iframeDoc.querySelectorAll('a, button');
                            allElements.forEach(element => {
                                const text = element.textContent.toLowerCase();
                                const href = element.getAttribute('href') || '';
                                if (text.includes('volver') || text.includes('regresar') ||
                                    text.includes('dashboard') || href.includes('/cliente/dashboard')) {
                                    element.style.display = 'none';
                                }
                            });

                            // Ajustar diseño compacto
                            const container = iframeDoc.querySelector('.container, .container-fluid');
                            if (container) {
                                container.style.padding = '10px 15px';
                                container.style.maxWidth = '100%';
                            }

                            iframeDoc.querySelectorAll('.card-body').forEach(card => {
                                card.style.padding = '0.8rem';
                            });

                            iframeDoc.querySelectorAll('.mb-4, .mb-3').forEach(elem => {
                                elem.style.marginBottom = '0.5rem';
                            });

                            // Observer para modales internos
                            const observer = new MutationObserver((mutations) => {
                                mutations.forEach((mutation) => {
                                    mutation.addedNodes.forEach((node) => {
                                        if (node.nodeType === 1 && node.classList &&
                                            (node.classList.contains('modal') || node.classList.contains('swal2-container'))) {
                                            node.style.zIndex = '1100';
                                            const backdrop = iframeDoc.querySelector('.modal-backdrop, .swal2-backdrop');
                                            if (backdrop) backdrop.style.zIndex = '1090';
                                        }
                                    });
                                });
                            });

                            observer.observe(iframeDoc.body, { childList: true, subtree: true });
                        } catch(e) {
                            console.log('No se pudo modificar iframe:', e);
                        }
                    "
                ></iframe>
            `;
        }

        function closeModalNav(modalId) {
            console.log('Cerrando modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.setProperty('display', 'none', 'important');
            }
        }
    </script>

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
