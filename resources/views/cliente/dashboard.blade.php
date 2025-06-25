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
            box-shadow: var(--shadow-sm);
            margin-top: 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
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
                        </h2>
                    </div>
                    <div class="card-body">
                        @php
                            // Filtrar citas futuras con estados específicos
                            $proximas_citas = $mis_citas
                                ->where('fecha_hora', '>=', now())
                                ->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso'])
                                ->sortBy('fecha_hora');
                        @endphp

                        @if ($proximas_citas->count() > 0)
                            @foreach ($proximas_citas->take(3) as $cita)
                                <div class="next-appointment {{ $loop->first ? 'highlighted' : '' }}">
                                    <!-- Mantener el mismo contenido de la cita -->
                                    <div class="appointment-date-time">
                                        <div class="date-badge">
                                            <span class="day">{{ $cita->fecha_hora->format('d') }}</span>
                                            <span class="month">{{ $cita->fecha_hora->format('M') }}</span>
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
                                <p>Agenda tu primera cita de lavado</p>
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
                        @php
                            // Filtrar citas pasadas o con estados finalizados/cancelados
                            $historial_citas = $mis_citas
                                ->filter(function ($cita) {
                                    return $cita->fecha_hora < now() ||
                                        in_array($cita->estado, ['finalizada', 'cancelada']);
                                })
                                ->sortByDesc('fecha_hora');
                        @endphp

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

    <!-- Modal para crear cita -->
    <div id="createCitaModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeCitaModal()">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;">
                <i class="fas fa-calendar-plus"></i> Nueva Cita
            </h2>

            <form id="citaForm">
                @csrf

                <!-- Selección de vehículo -->
                <div class="form-group">
                    <label for="vehiculo_id">Vehículo:</label>
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

                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <select id="hora" name="hora" required>
                        <option value="">Seleccione una hora</option>
                        <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Servicios Disponibles:</label>
                    <div id="serviciosContainer"
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                        <!-- Los servicios se cargarán dinámicamente según el tipo de vehículo -->
                    </div>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="3"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-outline" onclick="closeCitaModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cita
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
        // Configuración global de SweetAlert
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline mr-2'
            },
            buttonsStyling: false
        });

        /*=========================================================
        FUNCIONAMIENTO DE CREAR CITAS
        =========================================================*/
        // Variables globales
        let horariosDisponibles = [];
        let todosServiciosDisponibles = [];
        let serviciosFiltrados = [];
        let diasNoLaborables = [];


        // Funciones del modal de citas
        function openCitaModal(vehiculoId = null) {
            // Verificar estado del usuario primero
            checkUserStatus().then(isActive => {
                if (!isActive) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cuenta inactiva',
                        text: 'Tu cuenta está inactiva. No puedes crear nuevas citas.',
                        icon: 'error'
                    });
                    return;
                }

                const modal = document.getElementById('createCitaModal');
                modal.style.display = 'block';

                // Resetear el formulario
                document.getElementById('citaForm').reset();

                // Cargar datos necesarios
                loadInitialData().then(() => {
                    // Si se proporciona un vehículo, establecerlo
                    if (vehiculoId) {
                        document.getElementById('vehiculo_id').value = vehiculoId;
                        cargarServiciosPorTipo();
                    }
                });
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
                // Cargar datos en paralelo
                const [horariosRes, serviciosRes, noLaborablesRes] = await Promise.all([
                    fetch('{{ route('cliente.horarios-disponibles') }}'),
                    fetch('{{ route('cliente.servicios-disponibles') }}'),
                    fetch('{{ route('cliente.dias-no-laborables') }}')
                ]);

                // Verificar respuestas
                if (!horariosRes.ok || !serviciosRes.ok || !noLaborablesRes.ok) {
                    throw new Error('Error al cargar datos iniciales');
                }

                // Procesar respuestas
                horariosDisponibles = await horariosRes.json();
                todosServiciosDisponibles = await serviciosRes.json();
                diasNoLaborables = await noLaborablesRes.json();

                console.log('Datos cargados:', {
                    horarios: horariosDisponibles,
                    servicios: todosServiciosDisponibles,
                    diasNoLaborables: diasNoLaborables
                });

                // Configurar datepicker
                setupDatePicker();

            } catch (error) {
                console.error('Error cargando datos:', error);
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'No se pudieron cargar los datos necesarios. Por favor recarga la página.',
                    icon: 'error'
                });
            }
        }

        // Función para cargar horas disponibles (actualizada)
        function loadAvailableHours(dayOfWeek) {
            const horaSelect = document.getElementById('hora');
            horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

            // Asegurarse que dayOfWeek es un número
            const diaSemana = typeof dayOfWeek === 'string' ? parseInt(dayOfWeek) : dayOfWeek;

            // Filtrar horarios para el día seleccionado
            const horariosDia = horariosDisponibles.filter(h => {
                const horarioDia = typeof h.dia_semana === 'string' ? parseInt(h.dia_semana) : h.dia_semana;
                return horarioDia === diaSemana;
            });

            if (horariosDia.length === 0) {
                horaSelect.innerHTML = '<option value="">No hay horarios disponibles para este día</option>';
                return;
            }

            // Generar opciones de hora cada 30 minutos
            horariosDia.forEach(horario => {
                const [inicioHora, inicioMinuto] = horario.hora_inicio.split(':').map(Number);
                const [finHora, finMinuto] = horario.hora_fin.split(':').map(Number);

                let currentHora = inicioHora;
                let currentMinuto = inicioMinuto;

                while (currentHora < finHora || (currentHora === finHora && currentMinuto < finMinuto)) {
                    const horaStr =
                        `${currentHora.toString().padStart(2, '0')}:${currentMinuto.toString().padStart(2, '0')}`;
                    const option = document.createElement('option');
                    option.value = horaStr;
                    option.textContent = horaStr;
                    horaSelect.appendChild(option);

                    // Incrementar 30 minutos
                    currentMinuto += 30;
                    if (currentMinuto >= 60) {
                        currentMinuto -= 60;
                        currentHora += 1;
                    }
                }
            });
        }

        // Configuración del datepicker (actualizada)
        function setupDatePicker() {
            const fechaInput = document.getElementById('fecha');

            // Establecer límites de fecha (hoy hasta 1 mes después)
            const today = new Date();
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 1);

            fechaInput.min = formatDateForInput(today);
            fechaInput.max = formatDateForInput(maxDate);

            fechaInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const dayOfWeek = selectedDate.getDay(); // 0 = Domingo, 1 = Lunes, etc.

                // Validar día no laborable
                const fechaStr = selectedDate.toISOString().split('T')[0];
                const diaNoLaborable = diasNoLaborables.find(dia => dia.fecha === fechaStr);

                if (diaNoLaborable) {
                    showDateError(
                        'Día no laborable',
                        `No se atienden citas el ${formatFechaBonita(selectedDate)}.<br>
                 <strong>Motivo:</strong> ${diaNoLaborable.motivo || 'Día no laborable'}`
                    );
                    return;
                }

                // Validar domingos
                if (dayOfWeek === 0) {
                    showDateError(
                        'Domingo no laborable',
                        'No atendemos los domingos. Por favor selecciona otro día.'
                    );
                    return;
                }

                // Si pasa todas las validaciones, cargar horarios
                loadAvailableHours(dayOfWeek);
            });
        }

        // Función para formatear fecha como YYYY-MM-DD (para input date)
        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Función para formatear fecha bonita (ej: "Lunes, 25 de Junio")
        function formatFechaBonita(date) {
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            };
            return date.toLocaleDateString('es-ES', options);
        }

        // Función mejorada para mostrar errores
        function showDateError(title, message) {
            swalWithBootstrapButtons.fire({
                title: title,
                html: message,
                icon: 'warning',
                confirmButtonColor: '#4facfe'
            });

            // Resetear selección
            document.getElementById('fecha').value = '';
            document.getElementById('hora').innerHTML = '<option value="">Seleccione una hora</option>';
        }

        // Función para cargar servicios según el tipo de vehículo seleccionado
        function cargarServiciosPorTipo() {
            const vehiculoSelect = document.getElementById('vehiculo_id');
            const selectedOption = vehiculoSelect.options[vehiculoSelect.selectedIndex];
            const tipoVehiculo = selectedOption?.dataset.tipo?.toLowerCase(); // Asegurar minúsculas

            if (!tipoVehiculo) {
                document.getElementById('serviciosContainer').innerHTML = '<p>Seleccione un vehículo primero</p>';
                return;
            }

            // Filtrar servicios por categoría (comparando en minúsculas)
            const serviciosFiltrados = [];
            for (const categoria in todosServiciosDisponibles) {
                if (categoria.toLowerCase() === tipoVehiculo) {
                    serviciosFiltrados.push(...todosServiciosDisponibles[categoria]);
                }
            }

            renderServicios(serviciosFiltrados);
        }

        // Función para renderizar servicios
        function renderServicios(servicios) {
            const container = document.getElementById('serviciosContainer');
            container.innerHTML = '';

            if (servicios.length === 0) {
                container.innerHTML = '<p>No hay servicios disponibles para este tipo de vehículo</p>';
                return;
            }

            servicios.forEach(servicio => {
                const servicioDiv = document.createElement('div');
                servicioDiv.className = 'service-card';
                servicioDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="servicio_${servicio.id}" name="servicios[]" value="${servicio.id}">
                <div>
                    <h4 style="margin: 0; font-size: 1rem;">${servicio.nombre}</h4>
                    <p style="margin: 0; font-size: 0.8rem; color: #666;">
                        $${servicio.precio.toFixed(2)} • ${formatDuration(servicio.duracion_min)}
                    </p>
                </div>
            </div>
        `;
                container.appendChild(servicioDiv);
            });
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

        // Manejar envío del formulario
        document.getElementById('citaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Mostrar loader
            const swalInstance = swalWithBootstrapButtons.fire({
                title: 'Procesando cita...',
                html: 'Estamos reservando tu cita, por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(this);
            const isEdit = formData.has('_method'); // Verificar si es edición
            const citaId = isEdit ? this.action.split('/').pop() : null;

            try {
                const response = await fetch(this.action, {
                    method: this.method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        vehiculo_id: formData.get('vehiculo_id'),
                        fecha_hora: new Date(`${formData.get('fecha')}T${formData.get('hora')}`)
                            .toISOString(),
                        servicios: Array.from(document.querySelectorAll(
                            'input[name="servicios[]"]:checked')).map(el => el.value),
                        observaciones: formData.get('observaciones'),
                        _method: formData.get('_method') // Para edición
                    })
                });

                const result = await response.json();

                await swalInstance.close();

                if (!response.ok) {
                    throw new Error(result.message || 'Error al procesar la cita');
                }

                // Éxito - Mostrar alerta mejorada
                const selectedDate = new Date(`${formData.get('fecha')}T${formData.get('hora')}`);
                await swalWithBootstrapButtons.fire({
                    title: isEdit ? '¡Cita actualizada!' : '¡Cita agendada!',
                    html: `
                <div style="text-align: left; margin-top: 15px;">
                    <p><strong>Fecha:</strong> ${selectedDate.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</p>
                    <p><strong>Hora:</strong> ${selectedDate.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}</p>
                    <p><strong>Servicios:</strong> ${result.servicios_count} seleccionados</p>
                </div>
            `,
                    icon: 'success',
                    confirmButtonColor: '#4facfe',
                    showCancelButton: true,
                    confirmButtonText: 'Ver mis citas',
                    cancelButtonText: 'Quedarme aquí'
                }).then((result) => {
                    closeCitaModal();
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('cliente.citas') }}';
                    } else {
                        // Actualizar dinámicamente sin recargar
                        updateCitasSections();
                    }
                });

            } catch (error) {
                console.error('Error:', error);
                await swalInstance.close();

                let errorMessage = 'Ocurrió un error al procesar tu cita.';
                let showAvailableTimes = false;
                let availableTimes = [];

                // Manejo específico para error de horario ocupado
                if (error.message.includes('Duplicate entry') || error.message.includes(
                        'horario ya está ocupado')) {
                    errorMessage =
                        'Lo sentimos, ese horario ya está ocupado. Por favor selecciona otro horario.';

                    // Si el servidor envió horarios alternativos
                    if (error.response && error.response.data.available_times) {
                        showAvailableTimes = true;
                        availableTimes = error.response.data.available_times;
                    }
                }

                const errorHtml = `
            <div style="text-align: left;">
                <p>${errorMessage}</p>
                ${showAvailableTimes ? `
                                                <p style="margin-top: 10px;"><strong>Horarios disponibles cercanos:</strong></p>
                                                <ul style="margin-top: 5px;">
                                                    ${availableTimes.map(time => `<li>${time}</li>`).join('')}
                                                </ul>
                                            ` : ''}
                <p style="margin-top: 10px; font-size: 0.9em; color: #666;">
                    Por favor intenta nuevamente con un horario diferente.
                </p>
            </div>
        `;

                swalWithBootstrapButtons.fire({
                    title: 'Error al agendar',
                    html: errorHtml,
                    icon: 'error',
                    confirmButtonColor: '#ff6b6b'
                });
            }
        });

        // Función para actualizar las secciones de citas dinámicamente
        async function updateCitasSections() {
            try {
                // Mostrar skeleton loading
                const citasContainer = document.querySelector('.main-section');
                citasContainer.innerHTML = `
            <div class="skeleton-loading">
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>
        `;

                // Obtener datos actualizados
                const response = await fetch('{{ route('cliente.citas.dashboard-data') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Error al obtener datos');

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error en los datos recibidos');
                }

                // Actualizar Próximas Citas
                updateCitasSection('próximas', data.proximas_citas);

                // Actualizar Historial
                updateCitasSection('historial', data.historial_citas);

                // Mostrar notificación de éxito
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Tus citas se han actualizado'
                });

            } catch (error) {
                console.error('Error al actualizar citas:', error);

                // Mostrar error pero mantener el contenido anterior
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo actualizar la lista. Por favor recarga la página.',
                    icon: 'error'
                });
            }
        }

        // Función para actualizar una sección específica de citas
        function updateCitasSection(tipo, citas) {
            const container = tipo === 'próximas' ?
                document.querySelector('.card:first-child .card-body') :
                document.querySelector('.card:nth-child(2) .card-body');

            if (citas.length === 0) {
                container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-${tipo === 'próximas' ? 'calendar-alt' : 'history'}"></i>
                <h3>${tipo === 'próximas' ? 'No tienes citas programadas' : 'No hay historial de servicios'}</h3>
                <p>${tipo === 'próximas' ? 'Agenda tu primera cita de lavado' : 'Agenda tu primera cita para comenzar a ver tu historial'}</p>
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
                    const fecha = new Date(cita.fecha_hora);
                    html += `
                <div class="next-appointment ${index === 0 ? 'highlighted' : ''}">
                    <div class="appointment-date-time">
                        <div class="date-badge">
                            <span class="day">${fecha.getDate()}</span>
                            <span class="month">${fecha.toLocaleString('es-ES', { month: 'short' })}</span>
                        </div>
                        <div class="time-info">
                            <div class="time">${fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}</div>
                            <div class="service">
                                ${cita.servicios.map(s => s.nombre).join(', ')}
                            </div>
                            <div class="vehicle-info">
                                <i class="fas fa-car"></i> ${cita.vehiculo.marca} ${cita.vehiculo.modelo}
                            </div>
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
                    const fecha = new Date(cita.fecha_hora);
                    const total = cita.servicios.reduce((sum, servicio) => sum + servicio.precio, 0);

                    html += `
                <div class="service-history-item">
                    <div class="service-icon">
                        <i class="fas fa-soap"></i>
                    </div>
                    <div class="service-details">
                        <h4>${cita.servicios.map(s => s.nombre).join(', ')}</h4>
                        <p><i class="fas fa-calendar"></i> ${fecha.toLocaleString('es-ES', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
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
                        $${total.toFixed(2)}
                    </div>
                </div>
            `;
                });
            }

            container.innerHTML = html;
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

        function editCita(citaId) {
            // Mostrar loader mientras se carga la cita
            const swalInstance = swalWithBootstrapButtons.fire({
                title: 'Cargando cita...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/cliente/citas/${citaId}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Error al cargar la cita');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    swalInstance.close();

                    if (data.success) {
                        // Rellenar el modal con los datos de la cita
                        openCitaModal();
                        document.getElementById('vehiculo_id').value = data.vehiculo_id;
                        document.getElementById('fecha').value = data.fecha_hora.split('T')[0];

                        // Configurar hora después de cargar los horarios
                        const hora = data.fecha_hora.split('T')[1].substring(0, 5);
                        setTimeout(() => {
                            document.getElementById('hora').value = hora;
                        }, 500);

                        cargarServiciosPorTipo().then(() => {
                            // Seleccionar los servicios
                            data.servicios.forEach(servicioId => {
                                const checkbox = document.getElementById(`servicio_${servicioId}`);
                                if (checkbox) checkbox.checked = true;
                            });

                            // Cambiar el formulario para edición
                            const form = document.getElementById('citaForm');
                            form.action = `/cliente/citas/${citaId}`;
                            form.method = 'POST'; // Usar POST con método spoofing para PUT
                            form.innerHTML += `<input type="hidden" name="_method" value="PUT">`;

                            swalWithBootstrapButtons.fire({
                                title: 'Editar cita',
                                text: 'Puedes modificar los detalles de tu cita',
                                icon: 'info',
                                confirmButtonColor: '#4facfe'
                            });
                        });
                    } else {
                        throw new Error(data.message || 'Error al procesar la cita');
                    }
                })
                .catch(error => {
                    swalInstance.close();
                    console.error('Error al editar cita:', error);

                    let errorMessage = 'Ocurrió un error al cargar la cita para edición';
                    try {
                        const errorData = JSON.parse(error.message);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = error.message || errorMessage;
                    }

                    swalWithBootstrapButtons.fire({
                        title: 'Error',
                        html: `
                <div style="text-align: left;">
                    <p>${errorMessage}</p>
                    <p style="margin-top: 10px; font-size: 0.9em; color: #666;">Por favor intenta nuevamente.</p>
                </div>
            `,
                        icon: 'error',
                        confirmButtonColor: '#ff6b6b'
                    });
                });
        }

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
