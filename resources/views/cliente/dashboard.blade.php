<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Cliente - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
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
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        /* Reemplaza .welcome-stat con este */
        .welcome-stat {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-100);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            transition: all 0.3s ease;
        }

        .welcome-stat:hover::before {
            transform: scaleX(1);
        }

        .welcome-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        .welcome-stat .number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #4facfe;
        }

        .welcome-stat .label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
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
        /* Reemplaza .next-appointment con este */
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

        .profile-info p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2px;
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
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 25px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
            }

            .header {
                padding: 15px 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-section h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }

            .welcome-stats {
                justify-content: center;
                flex-wrap: wrap;
            }

            .header-actions {
                width: 100%;
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

            .service-history-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .service-price {
                text-align: left;
                margin-top: 10px;
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
        }

        .modal-content {
            background: var(--glass-bg);
            margin: 5% auto;
            padding: 25px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
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

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
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
                    <button type="button" id="openVehiculoBtn" class="btn btn-primary" onclick="openVehiculoModal()">
                        <i class="fas fa-plus"></i>
                        Agregar Vehículo
                    </button>
                    <a href="{{ route('cliente.citas') }}" class="btn btn-primary">
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
                        @if (isset($mis_citas) && count($mis_citas) > 0)
                            <!-- Próxima cita destacada -->
                            @php $nextAppointment = $mis_citas->first(); @endphp
                            <div class="next-appointment">
                                <div class="appointment-date-time">
                                    <div class="date-badge">
                                        <span
                                            class="day">{{ \Carbon\Carbon::parse($nextAppointment->fecha_hora)->format('d') }}</span>
                                        <span
                                            class="month">{{ \Carbon\Carbon::parse($nextAppointment->fecha_hora)->format('M') }}</span>
                                    </div>
                                    <div class="time-info">
                                        <div class="time">
                                            {{ \Carbon\Carbon::parse($nextAppointment->fecha_hora)->format('h:i A') }}
                                        </div>
                                        <div class="service">
                                            @if ($nextAppointment->servicios && count($nextAppointment->servicios) > 0)
                                                {{ $nextAppointment->servicios->pluck('nombre')->join(', ') }}
                                            @else
                                                Sin servicios especificados
                                            @endif
                                        </div>
                                    </div>
                                    <span class="appointment-status status-{{ $nextAppointment->estado }}">
                                        {{ ucfirst($nextAppointment->estado) }}
                                    </span>
                                </div>
                                <div class="appointment-actions">
                                    <button class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Modificar
                                    </button>
                                    <button class="btn btn-sm btn-outline">
                                        <i class="fas fa-times"></i>
                                        Cancelar
                                    </button>
                                </div>
                            </div>

                            <!-- Otras citas próximas -->
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach ($mis_citas->slice(1) as $cita)
                                    <div class="appointment-date-time"
                                        style="padding: 15px; border-bottom: 1px solid #eee;">
                                        <div class="date-badge" style="width: 60px; height: 60px; font-size: 0.8rem;">
                                            <span
                                                class="day">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d') }}</span>
                                            <span
                                                class="month">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('M') }}</span>
                                        </div>
                                        <div class="time-info">
                                            <div class="time" style="font-size: 1.1rem;">
                                                {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</div>
                                            <div class="service">
                                                @if ($cita->servicios && count($cita->servicios) > 0)
                                                    {{ $cita->servicios->pluck('nombre')->join(', ') }}
                                                @else
                                                    Sin servicios especificados
                                                @endif
                                            </div>
                                        </div>
                                        <span class="appointment-status status-{{ $cita->estado }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-alt"></i>
                                <h3>No tienes citas programadas</h3>
                                <p>Agenda tu primera cita de lavado</p>
                                <a href="{{ route('cliente.citas') }}" class="btn btn-primary"
                                    style="margin-top: 15px;">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Cita
                                </a>
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
                        @if (isset($mis_citas) && count($mis_citas) > 0)
                            @foreach ($mis_citas as $cita)
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
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d M Y - h:i A') }}</p>
                                        <p><i class="fas fa-car"></i> {{ $cita->vehiculo->marca }}
                                            {{ $cita->vehiculo->modelo }} - {{ $cita->vehiculo->placa }}</p>
                                        <a href="#" class="repeat-service">
                                            <i class="fas fa-redo"></i> Volver a agendar
                                        </a>
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
                                <p><i class="fas fa-envelope"></i> {{ $user->email ?? 'No especificado' }}</p>
                                <p><i class="fas fa-phone"></i> {{ $user->telefono ?? 'No especificado' }}</p>
                                <p><i class="fas fa-calendar"></i> Cliente desde:
                                    {{ $user->created_at->format('M Y') }}</p>
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
                    <div class="card-body">
                        @if (isset($mis_vehiculos) && count($mis_vehiculos) > 0)
                            @foreach ($mis_vehiculos as $vehiculo)
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
                                    <a href="{{ route('cliente.citas') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-car"></i>
                                <h3>No tienes vehículos registrados</h3>
                                <p>Agrega tu primer vehículo para comenzar a agendar citas</p>
                            </div>
                        @endif

                        <a href="{{ route('cliente.vehiculos') }}" class="btn btn-outline"
                            style="width: 100%; margin-top: 10px;">
                            <i class="fas fa-plus"></i>
                            Agregar Vehículo
                        </a>
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
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-car"></i> Nuevo Vehículo
            </h2>

            <form action="{{ route('vehiculos.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <input type="text" id="marca" name="marca" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                    <input type="text" id="modelo" name="modelo" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                <div class="mb-4">
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select id="tipo" name="tipo" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione</option>
                        <option value="sedan">Sedán</option>
                        <option value="pickup">Pickup</option>
                        <option value="camion">Camión</option>
                        <option value="moto">Moto</option>
                    </select>
                </div>


                <div class="mb-4">
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" id="color" name="color" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="placa" class="block text-sm font-medium text-gray-700 mb-1">Placa</label>
                    <input type="text" id="placa" name="placa" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="fecha_registro" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
                        Registro</label>
                    <input type="date" id="fecha_registro" name="fecha_registro" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeVehiculoModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Guardar Vehículo
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
            }
        });

        // Función para marcar notificaciones como leídas
        //function markAsRead(notificacionId) {
        //fetch(`/notificaciones/${notificacionId}/marcar-leida`, {
        // method: 'POST',
        //  headers: {
        //    'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //   'Content-Type': 'application/json'
        //}
        //}).then(response => {
        //  if(response.ok) {
        //    location.reload();
        //}
        //});
        //}

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
                                                                                                                                                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">${servicio.nombre}</td>
                                                                                                                                                            <td style="text-align: right; padding: 8px; border-bottom: 1px solid #ddd;">$${servicio.precio.toFixed(2)}</td>
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
