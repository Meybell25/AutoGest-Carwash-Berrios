<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - AutoGest Carwash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    @vite(['resources/js/app.js'])
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
            padding: 25px;
            position: relative;
        }

        /* ======================
        HEADER
        ====================== */
        .header {
            background: var(--bg-light);
            backdrop-filter: var(--blur);
            padding: 30px 35px;
            border-radius: 24px;
            margin-bottom: 35px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
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
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-icon {
            background: var(--secondary-gradient);
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

        .welcome-icon:hover {
            transform: rotate(0deg) scale(1.1);
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
            border: 1px solid var(--border-primary);
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
            background: var(--primary-gradient);
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
            color: var(--text-secondary);
            font-weight: 500;
        }

        @keyframes gradientBorder {
            0% {
                background-position: 0% 50%;
            }

            .welcome-stat50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
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

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(56, 142, 60, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            box-shadow: 0 8px 25px rgba(56, 142, 60, 0.4);
        }

        .btn-info {
            background: var(--info-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(2, 119, 189, 0.3);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #01579b 0%, #003d6b 100%);
            box-shadow: 0 8px 25px rgba(2, 119, 189, 0.4);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        /* ======================
        LAYOUT
        ====================== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 35px;
            margin-bottom: 35px;
        }

        .main-section,
        .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* ======================
        TARJETAS
        ====================== */
        .card {
            background: var(--bg-light);
            backdrop-filter: var(--blur);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .card::before {
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

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            padding: 25px 30px 0;
            border-bottom: 2px solid var(--border-primary);
            margin-bottom: 25px;
        }

        .card-header h2 {
            font-size: 1.50rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--gray-800);
            margin-bottom: 10px;
        }


        .card-header .icon {
            background: var(--secondary-gradient);
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            color: white;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .card-header .icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .card-body {
            padding: 0 30px 30px;
        }


        /* ======================
        ESTADÍSTICAS
        ====================== */
        .admin-stat-card {
            background: var(--bg-surface);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
            border-left: 5px solid;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .admin-stat-card::before {
            content: none !important;
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(39, 174, 96, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .admin-stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-card-primary,
        .stat-card-success,
        .stat-card-warning,
        .stat-card-danger {
            background: white !important;
            border-left: 5px solid;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Colores de los bordes izquierdos */
        .stat-card-primary {
            border-left-color: var(--primary);
        }

        .stat-card-success {
            border-left-color: var(--success);
        }

        .stat-card-warning {
            border-left-color: var(--info);
        }

        .stat-card-danger {
            border-left-color: var(--danger);
        }

        .stat-value {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 8px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 20px;
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .icon-primary {
            background: var(--primary-gradient);
        }

        .icon-success {
            background: var(--success-gradient);
        }

        .icon-warning {
            background: var(--info-gradient);
        }

        .icon-danger {
            background: var(--danger-gradient);
        }

        /* ======================
        TABLAS
        ====================== */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .admin-table th {
            background: var(--light);
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .admin-table td {
            padding: 18px 15px;
            border-bottom: 1px solid var(--border-primary);
            background: var(--bg-surface);
        }

        .admin-table tr:hover td {
            background: rgba(39, 174, 96, 0.03);
            transform: scale(1.01);
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .table-btn {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .table-btn:hover {
            transform: scale(1.15) rotate(5deg);
        }

        .btn-view {
            background: var(--info-gradient);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
        }

        .btn-delete {
            background: var(--danger-gradient);
            color: white;
        }

        /* ======================
        BADGES
        ====================== */
        .badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .badge-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .badge-success {
            background: var(--success-gradient);
            color: white;
        }

        .badge-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .badge-danger {
            background: var(--danger-gradient);
            color: white;
        }

        .badge-info {
            background: var(--info-gradient);
            color: white;
        }

        .badge-pendiente {
            background: linear-gradient(135deg, #ff8f00 0%, #ef6c00 100%);
            color: white;
        }

        .badge-pendiente {
            background: linear-gradient(135deg, #ffb74d 0%, #ff9800 100%);
            color: white;
        }

        .badge-confirmado {
            background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
            color: white;
        }

        .badge-en_proceso {
            background: linear-gradient(135deg, #7e57c2 0%, #5e35b1 100%);
            color: white;
        }

        .badge-cancelada {
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
            color: white;
        }

        .badge-finalizada {
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
            color: white;
        }

        .badge {
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        /* ======================
        PESTAÑAS
        ====================== */
        .tab-container {
            margin-top: 25px;
        }

        .tab-buttons {
            display: flex;
            border-bottom: 2px solid var(--border-primary);
            margin-bottom: 25px;
            gap: 5px;
        }

        .tab-button {
            padding: 15px 25px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            position: relative;
            border-radius: 10px 10px 0 0;
            transition: var(--transition);
        }

        .tab-button:hover {
            background: rgba(39, 174, 96, 0.05);
            color: var(--primary);
        }

        .tab-button.active {
            background: var(--primary);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

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

        /* ======================
        GRÁFICOS
        ====================== */
        .chart-container {
            position: relative;
            height: 320px;
            margin-bottom: 25px;
            border-radius: 15px;
            overflow: hidden;
        }

        /* ======================
        SERVICIOS
        ====================== */
        .service-history-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid var(--border-primary);
            border-radius: 18px;
            margin-bottom: 15px;
            transition: var(--transition);
            background: var(--bg-surface);
        }

        .service-history-item:hover {
            border-color: var(--primary);
            background: rgba(39, 174, 96, 0.03);
            transform: translateX(10px);
        }

        .service-icon {
            background: var(--success-gradient);
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin-right: 20px;
            box-shadow: var(--shadow-sm);
        }

        .service-details {
            flex: 1;
        }

        .service-details h4 {
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .service-badge-1 {
            background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%);
        }

        .service-badge-2 {
            background: linear-gradient(135deg, #0277bd 0%, #0288d1 100%);
        }

        .service-badge-3 {
            background: linear-gradient(135deg, #5e35b1 0%, #7e57c2 100%);
        }

        .service-badge-4 {
            background: linear-gradient(135deg, #d84315 0%, #ff8f00 100%);
        }

        .service-badge-5 {
            background: linear-gradient(135deg, #00838f 0%, #00695c 100%);
        }

        /* Service icons */
        .service-icon i {
            color: white !important;
        }


        .service-badge-1,
        .service-badge-2,
        .service-badge-3,
        .service-badge-4,
        .service-badge-5 {
            color: white !important;
        }

        /* ======================
        NOTIFICACIONES
        ====================== */
        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            background: var(--bg-surface);
            transition: var(--transition);
        }

        .notification-item:hover {
            transform: translateX(8px);
            box-shadow: var(--shadow-md);
        }

        .notification-item.unread {
            background: linear-gradient(45deg, rgba(39, 174, 96, 0.08), rgba(82, 160, 136, 0.08));
            border-left: 4px solid var(--primary);
        }

        .notification-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.1rem;
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .notification-icon.info {
            background: var(--info-gradient);
        }

        .notification-icon.success {
            background: var(--success-gradient);
        }

        .notification-icon.warning {
            background: var(--warning-gradient);
        }

        /* ======================
        MODALES
        ====================== */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(44, 62, 80, 0.7);
            backdrop-filter: var(--blur);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--bg-light);
            backdrop-filter: var(--blur);
            margin: 5% auto;
            padding: 35px;
            border-radius: 25px;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-light);
            animation: modalSlideIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .close-modal {
            color: var(--primary);
            float: right;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-modal:hover {
            transform: scale(1.2) rotate(90deg);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--primary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--border-primary);
            border-radius: 12px;
            font-size: 16px;
            background: var(--bg-surface);
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            outline: none;
        }

        /* ======================
        FOOTER
        ====================== */
        .footer {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            margin-top: 40px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        .footer-content {
            padding: 50px 35px;
            text-align: center;
            color: var(--text-primary);
            position: relative;
            z-index: 1;
        }

        .footer-brand {
            margin-bottom: 15px;
        }

        .footer-brand h3 {
            font-size: 28px;
            font-weight: 700;
            background: var(--primary-gradient);
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
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 25px;
        }


        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            text-align: left;
            max-width: 100%;
        }

        .info-item:hover {
            transform: translateY(-2px);
        }

        .info-item i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            color: white !important;
            border-radius: 50%;
            font-size: 12px;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
            flex-shrink: 0;
            line-height: 24px;
            text-align: center;
        }

        .info-item:last-child {
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .location-link {
            color: var(--text-primary);
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
            background: var(--primary-gradient);
            transition: width 0.3s ease;
        }

        .location-link:hover::after {
            width: 100%;
        }

        .location-link:hover {
            color: var(--primary);
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            margin: 20px 0;
            opacity: 0.3;
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
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
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

        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary-gradient);
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

        /* ======================
        PERFIL DE USUARIO
        ====================== */
        .profile-card {
            background: var(--bg-light);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--primary-gradient);
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            box-shadow: var(--shadow-md);
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-primary);
        }

        .profile-role {
            font-size: 0.9rem;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 15px;
            padding: 5px 15px;
            background: rgba(39, 174, 96, 0.1);
            border-radius: 20px;
            display: inline-block;
        }

        .profile-info {
            text-align: left;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .profile-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: var(--text-secondary);
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* ======================
        ÍCONOS
        ====================== */
        .fas,
        .fa-solid,
        .far,
        .fa-regular,
        .fab,
        .fa-brands {
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }

        .card-header .icon i,
        .welcome-icon i {
            color: white !important;
            font-size: 1.6rem;
            z-index: 1000;
        }



        .welcome-icon::before,
        .welcome-icon::after {
            content: none !important;
            display: none !important;
        }

        .btn i {
            color: inherit !important;
            margin-right: 8px;
        }

        .profile-info-item i {
            width: 24px !important;
            height: 24px !important;
            font-size: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: var(--primary) !important;
            color: white !important;
            border-radius: 50% !important;
            margin-right: 10px !important;
            flex-shrink: 0;
        }

        /* ======================
            FORMULARIOS
            ====================== */
        .search-filter-container {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-box,
        .filter-select {
            flex: 1;
            min-width: 200px;
        }

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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* ======================
            PAGINACIÓN
            ===================== */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }

        .page-link {
            padding: 10px 15px;
            border: 2px solid rgba(39, 174, 96, 0.2);
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
            transform: translateY(-2px);
        }

        /* ======================
            ESTADO VACÍO
            ====================== */
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

        .empty-state h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        /* ======================
            CONFIGURACIÓN DE CUENTA
            ====================== */
        .settings-form {
            background: var(--bg-light);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-md);
        }

        .settings-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-primary);
        }

        .settings-section h3 {
            font-size: 1.2rem;
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-section h3 i {
            font-size: 1.1rem;
        }

        /* ======================
            FORMULARIO DE HORARIOS
            ====================== */
        .schedule-form {
            background: var(--bg-light);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-md);
            margin-bottom: 30px;
        }

        /* ======================
            RESPONSIVE DESIGN
            ====================== */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .welcome-section h1 {
                font-size: 2.2rem;
            }

            .admin-stat-card {
                padding: 20px;
            }

            .stat-value {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 992px) {
            .dashboard-container {
                padding: 20px 15px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .welcome-section h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 15px;
            }

            .welcome-stats {
                justify-content: center;
                gap: 15px;
            }

            .header-actions {
                justify-content: center;
                flex-wrap: wrap;
            }

            .card-header,
            .card-body {
                padding: 20px 25px;
            }

            .admin-table {
                font-size: 0.85rem;
            }

            .admin-table th,
            .admin-table td {
                padding: 12px 8px;
            }

            .table-actions {
                flex-direction: column;
                gap: 5px;
            }

            .chart-container {
                height: 250px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .welcome-section h1 {
                font-size: 1.8rem;
            }

            .welcome-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .search-filter-container {
                flex-direction: column;
                gap: 15px;
            }

            .admin-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .admin-table thead,
            .admin-table tbody,
            .admin-table th,
            .admin-table td,
            .admin-table tr {
                display: block;
            }

            .admin-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .admin-table tr {
                border: 2px solid var(--border-primary);
                border-radius: 15px;
                margin-bottom: 15px;
                padding: 15px;
                background: var(--bg-surface);
            }

            .admin-table td {
                border: none;
                padding: 8px 0;
                position: relative;
                padding-left: 40%;
                background: transparent;
            }

            .admin-table td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 0;
                width: 35%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 700;
                color: var(--primary);
            }

            .tab-buttons {
                flex-wrap: wrap;
                gap: 8px;
            }

            .tab-button {
                padding: 12px 18px;
                font-size: 0.9rem;
                flex: 1;
                min-width: 100px;
                text-align: center;
            }

            .modal-content {
                margin: 10% auto;
                padding: 25px;
                width: 95%;
            }

            .footer-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .info-item {
                max-width: 100%;
                white-space: normal;
            }

            .info-item:last-child {
                white-space: normal;
            }

            .stat-value {
                font-size: 2rem;
            }

            .service-history-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .service-icon {
                margin-right: 0;
                align-self: center;
            }
        }

        @media (max-width: 576px) {
            .dashboard-container {
                padding: 15px 10px;
            }

            .header {
                padding: 20px 20px;
                border-radius: 18px;
            }

            .welcome-section h1 {
                font-size: 1.6rem;
            }

            .welcome-stats {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
                gap: 10px;
            }

            .welcome-stat {
                padding: 12px 15px;
                min-width: 70px;
            }

            .welcome-stat .number {
                font-size: 1.2rem;
            }

            .welcome-stat .label {
                font-size: 0.75rem;
            }

            .card {
                border-radius: 18px;
            }

            .card-header,
            .card-body {
                padding: 15px 20px;
            }

            .card-header h2 {
                font-size: 1.3rem;
                gap: 12px;
            }

            .card-header .icon {
                width: 35px;
                height: 35px;
                font-size: 1.1rem;
            }

            .admin-stat-card {
                padding: 20px 15px;
                text-align: center;
            }

            .stat-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
                margin: 0 auto 15px;
            }

            .stat-value {
                font-size: 1.8rem;
            }

            .btn {
                padding: 12px 18px;
                font-size: 0.9rem;
                gap: 8px;
            }

            .btn-sm {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .table-btn {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }

            .badge {
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .chart-container {
                height: 200px;
            }

            .pagination {
                gap: 5px;
            }

            .page-link {
                padding: 8px 12px;
                font-size: 0.85rem;
            }

            .notification-item {
                padding: 15px;
                flex-direction: column;
            }

            .notification-icon {
                margin-right: 0;
                margin-bottom: 10px;
                align-self: flex-start;
            }
        }

        @media (max-width: 480px) {
            .welcome-section h1 {
                font-size: 1.4rem;
            }

            .header-actions {
                width: 100%;
            }

            .btn {
                flex: 1;
                justify-content: center;
                min-width: 120px;
            }

            .modal-content {
                margin: 5% auto;
                padding: 20px;
                width: 98%;
                border-radius: 15px;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 12px;
                font-size: 16px;
            }

            .service-history-item {
                padding: 15px;
            }

            .empty-state {
                padding: 30px 15px;
            }

            .empty-state i {
                font-size: 2.5rem;
            }

            .empty-state h3 {
                font-size: 1.1rem;
            }
        }

        /* ======================
            ANIMACIONES
            ====================== */
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

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
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

        .admin-stat-card {
            animation: slideInUp 0.6s ease-out;
        }

        .admin-stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .admin-stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .admin-stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .admin-stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .notification-item {
            animation: slideInLeft 0.5s ease-out;
        }

        .service-history-item {
            animation: slideInUp 0.4s ease-out;
        }

        .welcome-stat {
            animation: pulse 2s infinite;
        }

        .welcome-stat:nth-child(1) {
            animation-delay: 0s;
        }

        .welcome-stat:nth-child(2) {
            animation-delay: 0.5s;
        }

        .welcome-stat:nth-child(3) {
            animation-delay: 1s;
        }

        /* ======================
            SCROLLBAR PERSONALIZADA
            ====================== */
        /* Para navegadores WebKit (Chrome, Safari, Edge) */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(39, 174, 96, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.8) 0%, rgba(82, 160, 136, 0.9) 100%);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #27ae60 0%, #52a088 100%);
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(39, 174, 96, 0.5);
        }

        ::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* Para Firefox */
        html {
            scrollbar-width: thin;
            scrollbar-color: #27ae60 #f8fafc;
        }

        /* Animación de brillo al hacer hover */
        @keyframes scrollbar-glow {
            0% {
                box-shadow: 0 0 0 rgba(39, 174, 96, 0);
            }

            50% {
                box-shadow: 0 0 8px rgba(39, 174, 96, 0.7);
            }

            100% {
                box-shadow: 0 0 0 rgba(39, 174, 96, 0);
            }
        }

        ::-webkit-scrollbar-thumb:hover {
            animation: scrollbar-glow 1.5s infinite;
        }

        /* ======================
            Nueva clase para contenedores de íconos
            ====================== */
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


        .icon-container>i {
            color: white !important;
            font-size: 1.3rem;
            position: relative;
            z-index: 100;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            /* Para mejor contraste */
        }

        /* Efecto hover */
        .icon-container:hover {
            transform: scale(1.1) rotate(5deg);
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
                            <!-- <i class="fas fa-user-cog"></i>-->
                            <i class="fas fa-cog"></i>
                        </div>
                        Panel de Administración
                    </h1>
                    <p>Gestiona todos los aspectos de tu negocio de lavado</p>
                    <div class="welcome-stats">
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['usuarios_totales'] ?? 0 }}</span>
                            <span class="label">Usuarios</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">{{ $stats['citas_hoy'] ?? 0 }}</span>
                            <span class="label">Citas Hoy</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">${{ number_format($stats['ingresos_hoy'] ?? 0, 2) }}</span>
                            <span class="label">Ingresos Hoy</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="#" class="btn btn-primary" onclick="mostrarModalUsuario()">
                        <i class="fas fa-user-plus"></i>
                        Crear Usuarios
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="btn btn-success">
                        <i class="fas fa-chart-bar"></i>
                        Reportes
                    </a>
                    <a href="{{ route('configuracion.index') }}" class="btn btn-info">
                        <i class="fas fa-cog"></i>
                        Configuración
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

        <!-- Estadísticas Rápidas -->
        <div class="dashboard-grid">
            <div class="main-section">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="admin-stat-card stat-card-primary">
                        <div class="stat-icon icon-primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value">{{ $stats['citas_hoy'] ?? 0 }}</div>
                        <div class="stat-label">Citas Hoy</div>
                    </div>
                    <div class="admin-stat-card stat-card-success">
                        <div class="stat-icon icon-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">${{ number_format($stats['ingresos_hoy'] ?? 0, 2) }}</div>
                        <div class="stat-label">Ingresos Hoy</div>
                    </div>
                    <div class="admin-stat-card stat-card-warning">
                        <div class="stat-icon icon-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value">{{ $stats['nuevos_clientes_mes'] ?? 0 }}</div>
                        <div class="stat-label">Nuevos Clientes (Mes)</div>
                    </div>
                    <div class="admin-stat-card stat-card-danger">
                        <div class="stat-icon icon-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-value">{{ $stats['citas_canceladas_mes'] ?? 0 }}</div>
                        <div class="stat-label">Cancelaciones (Mes)</div>
                    </div>
                </div>

                <!-- Gestión de Horarios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            Gestión de Horarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <h3 style="color: var(--text-primary);">Configuración de Horarios de Trabajo</h3>
                            <button class="btn btn-primary" onclick="mostrarModalHorario()">
                                <i class="fas fa-plus"></i> Agregar Horario
                            </button>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Día">Lunes</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(1)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(1)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Martes</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(2)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(2)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Miércoles</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(3)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(3)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Jueves</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(4)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(4)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Viernes</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(5)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(5)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Sábado</td>
                                        <td data-label="Hora Inicio">07:00 AM</td>
                                        <td data-label="Hora Fin">06:00 PM</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(6)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-delete" title="Desactivar"
                                                    onclick="desactivarHorario(6)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Día">Domingo</td>
                                        <td data-label="Hora Inicio">-</td>
                                        <td data-label="Hora Fin">-</td>
                                        <td data-label="Estado">
                                            <span class="badge badge-danger">Inactivo</span>
                                        </td>
                                        <td data-label="Acciones">
                                            <div class="table-actions">
                                                <button class="table-btn btn-edit" title="Editar"
                                                    onclick="editarHorario(0)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="table-btn btn-success" title="Activar"
                                                    onclick="activarHorario(0)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            Rendimiento Mensual
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="tab-container">
                            <div class="tab-buttons">
                                <button class="tab-button active"
                                    onclick="openTab(event, 'ingresosTab')">Ingresos</button>
                                <button class="tab-button" onclick="openTab(event, 'citasTab')">Citas</button>
                                <button class="tab-button" onclick="openTab(event, 'serviciosTab')">Servicios</button>
                            </div>

                            <div id="ingresosTab" class="tab-content active">
                                <div class="chart-container">
                                    <canvas id="ingresosChart"></canvas>
                                </div>
                            </div>

                            <div id="citasTab" class="tab-content">
                                <div class="chart-container">
                                    <canvas id="citasChart"></canvas>
                                </div>
                            </div>

                            <div id="serviciosTab" class="tab-content">
                                <div class="chart-container">
                                    <canvas id="serviciosChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimas Citas -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            Últimas Citas
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="search-filter-container">
                            <div class="search-box">
                                <input type="text" placeholder="Buscar citas..." class="form-control">
                            </div>
                            <div class="filter-select">
                                <select class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="finalizada">Finalizada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Vehículo</th>
                                        <th>Fecha/Hora</th>
                                        <th>Servicios</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ultimas_citas as $cita)
                                        <tr>
                                            <td data-label="ID">#{{ $cita->id }}</td>
                                            <td data-label="Cliente">{{ $cita->usuario->nombre }}</td>
                                            <td data-label="Vehículo">{{ $cita->vehiculo->marca }}
                                                {{ $cita->vehiculo->modelo }}</td>
                                            <td data-label="Fecha/Hora">{{ $cita->fecha_hora->format('d/m/Y H:i') }}
                                            </td>
                                            <td data-label="Servicios">
                                                @foreach ($cita->servicios as $index => $servicio)
                                                    <span
                                                        class="badge service-badge-{{ ($index % 5) + 1 }}">{{ $servicio->nombre }}</span>
                                                @endforeach
                                            </td>
                                            <td data-label="Total">
                                                ${{ number_format($cita->servicios->sum('pivot.precio'), 2) }}</td>
                                            <td data-label="Estado">
                                                <span
                                                    class="badge badge-{{ $cita->estado == 'pendiente'
                                                        ? 'pendiente'
                                                        : ($cita->estado == 'confirmado'
                                                            ? 'confirmado'
                                                            : ($cita->estado == 'en_proceso'
                                                                ? 'en_proceso'
                                                                : ($cita->estado == 'finalizada'
                                                                    ? 'finalizada'
                                                                    : 'cancelada'))) }}">
                                                    {{ $cita->estado_formatted }}
                                                </span>
                                            </td>
                                            <td data-label="Acciones">
                                                <div class="table-actions">
                                                    <button class="table-btn btn-view" title="Ver"
                                                        onclick="verDetalleCita({{ $cita->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="table-btn btn-edit" title="Editar"
                                                        onclick="editarCita({{ $cita->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="table-btn btn-delete" title="Cancelar"
                                                        onclick="cancelarCita({{ $cita->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination">
                            <a href="#" class="page-link">&laquo;</a>
                            <a href="#" class="page-link active">1</a>
                            <a href="#" class="page-link">2</a>
                            <a href="#" class="page-link">3</a>
                            <a href="#" class="page-link">&raquo;</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Sidebar -->
            <div class="sidebar-section">
                <!-- Card de Perfil -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            Mi Perfil
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="profile-card">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-name">{{ Auth::user()->nombre }}</div>
                            <div class="profile-role">Administrador</div>

                            <div class="profile-info">
                                <div class="profile-info-item">
                                    <i class="fas fa-envelope" style="color: white;"></i>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-phone" style="color: white;"></i>
                                    <span>{{ Auth::user()->telefono ?? 'No especificado' }}</span>
                                </div>
                                <div class="profile-info-item">
                                    <i class="fas fa-calendar" style="color: white;"></i>
                                    <span>Miembro desde {{ Auth::user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>

                            <button class="btn btn-outline" style="width: 100%; margin-top: 20px;"
                                onclick="editarPerfil()">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Usuarios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-users"></i>
                            </div>
                            Resumen de Usuarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--primary);">
                                    {{ $stats['usuarios_totales'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Usuarios Totales</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--success);">
                                    {{ $stats['nuevos_clientes_mes'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Nuevos
                                    ({{ now()->translatedFormat('F') }})</div>
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: var(--text-primary);">
                                Distribución por Rol
                            </h3>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="usuariosChart"></canvas>
                            </div>
                        </div>

                        <a href="{{ route('admin.usuarios') }}" class="btn btn-outline" style="width: 100%;">
                            <i class="fas fa-list"></i> Ver Todos los Usuarios
                        </a>
                    </div>
                </div>

                <!-- Servicios Populares -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-award"></i>
                            </div>
                            Servicios Populares
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach ($servicios_populares as $servicio)
                            <div class="service-history-item" style="margin-bottom: 10px;">
                                <div class="service-icon" style="background: var(--secondary-gradient);">
                                    <i class="fas fa-spray-can"></i>
                                </div>
                                <div class="service-details">
                                    <h4>{{ $servicio->nombre }}</h4>
                                    <p>${{ number_format($servicio->precio, 2) }} - {{ $servicio->duracion }} min</p>
                                    <p><i class="fas fa-chart-line"></i> {{ $servicio->veces_contratado }} veces este
                                        mes</p>
                                </div>
                                <button class="btn btn-sm btn-outline" onclick="editarServicio({{ $servicio->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        @endforeach

                        <button class="btn btn-primary" style="width: 100%; margin-top: 10px;"
                            onclick="nuevoServicio()">
                            <i class="fas fa-plus"></i> Agregar Servicio
                        </button>
                    </div>
                </div>

                <!-- Notificaciones del Sistema -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            Alertas del Sistema
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($alertas as $alerta)
                            <div class="notification-item {{ $alerta->leida ? 'read' : 'unread' }}">
                                <div class="notification-icon {{ $alerta->tipo }}">
                                    <i class="fas fa-{{ $alerta->icono }}"></i>
                                </div>
                                <div class="notification-content">
                                    <h4>{{ $alerta->titulo }}</h4>
                                    <p>{{ $alerta->mensaje }}</p>
                                    <small>{{ $alerta->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach

                        @if (count($alertas) == 0)
                            <div class="empty-state" style="padding: 20px;">
                                <i class="fas fa-check-circle"></i>
                                <h3>No hay alertas</h3>
                                <p>No hay notificaciones importantes en este momento</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal para ver detalle de cita -->
            <div id="detalleCitaModal" class="modal">
                <div class="modal-content" style="max-width: 700px;">
                    <span class="close-modal" onclick="closeModal('detalleCitaModal')">&times;</span>
                    <div id="detalleCitaContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>

            <!-- Modal para editar cita -->
            <div id="editarCitaModal" class="modal">
                <div class="modal-content" style="max-width: 700px;">
                    <span class="close-modal" onclick="closeModal('editarCitaModal')">&times;</span>
                    <h2 style="color: var(--primary); margin-bottom: 20px;">
                        <i class="fas fa-edit"></i> Editar Cita
                    </h2>
                    <form id="editarCitaForm">
                        <!-- Formulario se llenará dinámicamente -->
                    </form>
                </div>
            </div>

            <!-- Modal para nuevo/editar servicio -->
            <div id="servicioModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('servicioModal')">&times;</span>
                    <h2 id="servicioModalTitle">
                        <i class="fas fa-plus"></i> Nuevo Servicio
                    </h2>
                    <form id="servicioForm">
                        <input type="hidden" id="servicio_id" name="id">

                        <div class="form-group">
                            <label for="servicio_nombre">Nombre del Servicio:</label>
                            <input type="text" id="servicio_nombre" name="nombre" required class="form-control"
                                placeholder="Ej: Lavado Premium">
                        </div>

                        <div class="form-group">
                            <label for="servicio_descripcion">Descripción:</label>
                            <textarea id="servicio_descripcion" name="descripcion" rows="3" class="form-control"
                                placeholder="Describe los detalles del servicio..."></textarea>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="servicio_precio">Precio ($):</label>
                                <input type="number" step="0.01" id="servicio_precio" name="precio" required
                                    class="form-control" placeholder="0.00">
                            </div>

                            <div class="form-group">
                                <label for="servicio_duracion">Duración (min):</label>
                                <input type="number" id="servicio_duracion" name="duracion" required
                                    class="form-control" placeholder="30">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="servicio_activo">Estado:</label>
                            <select id="servicio_activo" name="activo" class="form-control">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Guardar Servicio
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal para gestión de horarios -->
            <div id="horarioModal" class="modal">
                <div class="modal-content" style="max-width: 500px;">
                    <span class="close-modal" onclick="closeModal('horarioModal')">&times;</span>
                    <h2 id="horarioModalTitle">
                        <i class="fas fa-clock"></i> Agregar Horario
                    </h2>
                    <form id="horarioForm">
                        <input type="hidden" id="horario_id" name="id">

                        <div class="form-group">
                            <label for="horario_dia">Día de la semana:</label>
                            <select id="horario_dia" class="form-control" required>
                                <option value="">Seleccione un día</option>
                                <option value="0">Domingo</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                            </select>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="horario_inicio">Hora de inicio:</label>
                                <input type="time" id="horario_inicio" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="horario_fin">Hora de fin:</label>
                                <input type="time" id="horario_fin" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="horario_activo">Estado:</label>
                            <select id="horario_activo" class="form-control">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Guardar Horario
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal para editar perfil -->
            <div id="perfilModal" class="modal">
                <div class="modal-content" style="max-width: 500px;">
                    <span class="close-modal" onclick="closeModal('perfilModal')">&times;</span>
                    <h2 style="color: var(--primary); margin-bottom: 20px;">
                        <i class="fas fa-user-edit"></i> Editar Perfil
                    </h2>
                    <form id="perfilForm">
                        @csrf
                        <div class="form-group">
                            <label for="perfil_nombre">Nombre:</label>
                            <input type="text" id="perfil_nombre" name="nombre" required class="form-control"
                                value="{{ Auth::user()->nombre }}">
                        </div>

                        <div class="form-group">
                            <label for="perfil_telefono">Teléfono:</label>
                            <input type="tel" id="perfil_telefono" name="telefono" class="form-control"
                                value="{{ Auth::user()->telefono ?? '' }}">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal para crear usuarios -->
    <div id="usuarioModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <span class="close-modal" onclick="closeModal('usuarioModal')">&times;</span>
            <h2 style="color: var(--primary); margin-bottom: 20px;">
                <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
            </h2>
            <form id="usuarioForm">
                @csrf
                <div class="form-group">
                    <label for="usuario_nombre">Nombre Completo:</label>
                    <input type="text" id="usuario_nombre" name="nombre" required class="form-control"
                        placeholder="Ej: Juan Pérez">
                </div>

                <div class="form-group">
                    <label for="usuario_email">Email:</label>
                    <input type="email" id="usuario_email" name="email" required class="form-control"
                        placeholder="Ej: usuario@example.com">
                </div>

                <div class="form-group">
                    <label for="usuario_telefono">Teléfono:</label>
                    <input type="tel" id="usuario_telefono" name="telefono" class="form-control"
                        placeholder="Ej: 75855197">
                </div>

                <div class="form-group">
                    <label for="usuario_rol">Rol:</label>
                    <select id="usuario_rol" name="rol" class="form-control" required>
                        <option value="">Seleccione un rol</option>
                        <option value="cliente">Cliente</option>
                        <option value="empleado">Empleado</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="usuario_password">Contraseña:</label>
                        <input type="password" id="usuario_password" name="password" required class="form-control"
                            placeholder="Mínimo 8 caracteres">
                    </div>

                    <div class="form-group">
                        <label for="usuario_password_confirmation">Confirmar Contraseña:</label>
                        <input type="password" id="usuario_password_confirmation" name="password_confirmation"
                            required class="form-control" placeholder="Repite la contraseña">
                    </div>
                </div>

                <div class="form-group">
                    <label for="usuario_estado">Estado:</label>
                    <select id="usuario_estado" name="estado" class="form-control">
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Crear Usuario
                </button>
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
                <div class="info-item" style="white-space: nowrap;">
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
                <br>Versión del sistema: 2.10.1
            </p>
        </div>
    </footer>

    <script>
        // Configuración global de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });



        // Configuración global de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Inicializar Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Suscribirse al canal
        const channel = pusher.subscribe('usuarios');

        // Escuchar eventos
        channel.bind('UsuarioCreado', function(data) {
            console.log('Evento recibido:', data);
            actualizarGraficoUsuarios();
            Toast.fire({
                icon: 'success',
                title: `Nuevo ${data.usuario.rol}: ${data.usuario.nombre}`,
                timer: 5000
            });
        });


        // LOGS DE DEPURACION
        console.log('Pusher config:', {
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        pusher.connection.bind('state_change', function(states) {
            console.log('Pusher connection state changed:', states);
        });

        pusher.connection.bind('error', function(err) {
            console.error('Pusher error:', err);
        });

        // Inicializar gráficos
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de ingresos mensuales
            const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');
            const ingresosChart = new Chart(ingresosCtx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                        'Dic'
                    ],
                    datasets: [{
                        label: 'Ingresos 2023',
                        data: [1200, 1900, 1500, 2000, 2200, 2500, 2800, 2600, 2300, 2000, 1800,
                            2100
                        ],
                        backgroundColor: 'rgba(39, 174, 96, 0.2)',
                        borderColor: 'rgba(39, 174, 96, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de citas mensuales
            const citasCtx = document.getElementById('citasChart').getContext('2d');
            const citasChart = new Chart(citasCtx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                        'Dic'
                    ],
                    datasets: [{
                        label: 'Citas Completadas',
                        data: [45, 60, 55, 70, 75, 80, 85, 80, 70, 65, 60, 65],
                        backgroundColor: 'rgba(211, 84, 0, 0.7)',
                        borderColor: 'rgba(211, 84, 0, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Citas Canceladas',
                        data: [5, 8, 6, 10, 7, 5, 4, 8, 10, 7, 9, 6],
                        backgroundColor: 'rgba(231, 76, 60, 0.7)',
                        borderColor: 'rgba(231, 76, 60, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Gráfico de servicios populares
            const serviciosCtx = document.getElementById('serviciosChart').getContext('2d');
            const serviciosChart = new Chart(serviciosCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Lavado Completo', 'Lavado Premium', 'Detallado VIP', 'Aspirado', 'Encerado'],
                    datasets: [{
                        data: [35, 25, 15, 15, 10],
                        backgroundColor: [
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(243, 156, 18, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(231, 76, 60, 0.7)'
                        ],
                        borderColor: [
                            'rgba(39, 174, 96, 1)',
                            'rgba(52, 152, 219, 1)',
                            'rgba(243, 156, 18, 1)',
                            'rgba(155, 89, 182, 1)',
                            'rgba(231, 76, 60, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

            // Gráfico de distribución de usuarios
            const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
            const usuariosChart = new Chart(usuariosCtx, {
                type: 'pie',
                data: {
                    labels: ['Clientes', 'Empleados', 'Administradores'],
                    datasets: [{
                        data: [
                            {{ $rolesDistribucion['clientes'] }},
                            {{ $rolesDistribucion['empleados'] }},
                            {{ $rolesDistribucion['administradores'] }}
                        ],
                        backgroundColor: [
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgba(39, 174, 96, 1)',
                            'rgba(52, 152, 219, 1)',
                            'rgba(155, 89, 182, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });


        async function actualizarGraficoUsuarios() {
            try {
                const response = await fetch('{{ route('admin.dashboard.data') }}');
                const data = await response.json();

                // 1. Actualizar gráfico de roles
                if (typeof usuariosChart !== 'undefined') {
                    usuariosChart.data.datasets[0].data = [
                        data.rolesDistribucion.clientes,
                        data.rolesDistribucion.empleados,
                        data.rolesDistribucion.administradores
                    ];
                    usuariosChart.update();
                }

                // 2. Actualizar welcome stats (header)
                const welcomeStats = document.querySelectorAll('.welcome-stat');
                if (welcomeStats.length >= 3) {
                    welcomeStats[0].querySelector('.number').textContent = data.stats.usuarios_totales;
                    welcomeStats[1].querySelector('.number').textContent = data.stats.citas_hoy;
                    welcomeStats[2].querySelector('.number').textContent = `$${data.stats.ingresos_hoy.toFixed(2)}`;
                }

                // 3. Actualizar card de resumen de usuarios
                const cardCounters = document.querySelectorAll('.card-body [style*="grid-template-columns"] div');
                if (cardCounters.length >= 2) {
                    cardCounters[0].querySelector('div:first-child').textContent = data.stats.usuarios_totales;
                    cardCounters[1].querySelector('div:first-child').textContent = data.stats.nuevos_clientes_mes;
                }

            } catch (error) {
                console.error('Error al actualizar datos:', error);
            }
        }

        // Actualizar cada 10 segundos
        setInterval(actualizarGraficoUsuarios, 10000);

        // Actualizar cuando la pestaña vuelve a estar activa
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                actualizarGraficoUsuarios();
            }
        });

        // Funciones para pestañas
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }

            const tabButtons = document.getElementsByClassName('tab-button');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }

            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        // Funciones para modales
        function verDetalleCita(citaId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            const detalleContent = `
                <h2 style="color: var(--primary); margin-bottom: 20px;">
                    <i class="fas fa-calendar-check"></i> Detalle de Cita #${citaId}
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                            <i class="fas fa-user"></i> Información del Cliente
                        </h3>
                        <p><strong>Nombre:</strong> Juan Pérez</p>
                        <p><strong>Teléfono:</strong> 5555-1234</p>
                        <p><strong>Email:</strong> juan@example.com</p>
                        <p><strong>Cliente desde:</strong> Ene 2023</p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </h3>
                        <p><strong>Marca/Modelo:</strong> Toyota Corolla</p>
                        <p><strong>Año:</strong> 2020</p>
                        <p><strong>Color:</strong> Rojo</p>
                        <p><strong>Placa:</strong> P123456</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                        <i class="fas fa-concierge-bell"></i> Servicios Contratados
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--light);">
                                <th style="padding: 10px; text-align: left;">Servicio</th>
                                <th style="padding: 10px; text-align: right;">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary);">Lavado Completo</td>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary); text-align: right;">$25.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary);">Aspirado Interior</td>
                                <td style="padding: 10px; border-bottom: 1px solid var(--border-primary); text-align: right;">$15.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; font-weight: bold;">Total</td>
                                <td style="padding: 10px; font-weight: bold; text-align: right;">$40.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                        <i class="fas fa-info-circle"></i> Información Adicional
                    </h3>
                    <p><strong>Fecha/Hora:</strong> 15 Jun 2023 - 10:00 AM</p>
                    <p><strong>Estado:</strong> <span class="badge badge-success">Finalizada</span></p>
                    <p><strong>Empleado asignado:</strong> Carlos López</p>
                    <p><strong>Observaciones del cliente:</strong> Por favor prestar atención a las manchas en los asientos traseros.</p>
                    <p><strong>Observaciones del empleado:</strong> Se detectó pequeño rayón en la puerta derecha, cliente fue notificado.</p>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button class="btn btn-outline" onclick="imprimirRecibo(${citaId})">
                        <i class="fas fa-print"></i> Imprimir Recibo
                    </button>
                    <button class="btn btn-primary" onclick="editarCita(${citaId})">
                        <i class="fas fa-edit"></i> Editar Cita
                    </button>
                </div>
            `;

            document.getElementById('detalleCitaContent').innerHTML = detalleContent;
            document.getElementById('detalleCitaModal').style.display = 'flex';
        }

        function editarCita(citaId) {
            // Simulación de formulario 
            const formContent = `
                <div class="form-group">
                    <label for="edit_cliente">Cliente:</label>
                    <select id="edit_cliente" class="form-control" required>
                        <option value="1" selected>Juan Pérez</option>
                        <option value="2">María González</option>
                        <option value="3">Carlos López</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_vehiculo">Vehículo:</label>
                    <select id="edit_vehiculo" class="form-control" required>
                        <option value="1" selected>Toyota Corolla (P123456)</option>
                        <option value="2">Honda Civic (P654321)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_fecha">Fecha:</label>
                    <input type="date" id="edit_fecha" class="form-control" value="2023-06-15" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_hora">Hora:</label>
                    <input type="time" id="edit_hora" class="form-control" value="10:00" required>
                </div>
                
                <div class="form-group">
                    <label>Servicios:</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <input type="checkbox" id="serv1" checked>
                            <label for="serv1">Lavado Completo ($25.00)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="serv2" checked>
                            <label for="serv2">Aspirado Interior ($15.00)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="serv3">
                            <label for="serv3">Encerado ($20.00)</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_empleado">Empleado Asignado:</label>
                    <select id="edit_empleado" class="form-control" required>
                        <option value="1" selected>Carlos López</option>
                        <option value="2">Ana Martínez</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_estado">Estado:</label>
                    <select id="edit_estado" class="form-control" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="finalizada" selected>Finalizada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_observaciones">Observaciones:</label>
                    <textarea id="edit_observaciones" rows="3" class="form-control">Por favor prestar atención a las manchas en los asientos traseros.</textarea>
                </div>
                
                <button type="button" class="btn btn-success" style="width: 100%;" onclick="guardarCambiosCita(${citaId})">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            `;

            document.getElementById('editarCitaForm').innerHTML = formContent;
            document.getElementById('detalleCitaModal').style.display = 'none';
            document.getElementById('editarCitaModal').style.display = 'flex';
        }

        function cancelarCita(citaId) {
            Swal.fire({
                title: '¿Cancelar esta cita?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, volver'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí iría la petición AJAX para cancelar la cita
                    Toast.fire({
                        icon: 'success',
                        title: 'Cita cancelada correctamente'
                    });

                    // Simulación de recarga de datos
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            });
        }

        function guardarCambiosCita(citaId) {
            // Aquí iría la petición AJAX para guardar los cambios
            Toast.fire({
                icon: 'success',
                title: 'Cambios guardados correctamente'
            });

            closeModal('editarCitaModal');

            // Simulación de recarga de datos
            setTimeout(() => {
                verDetalleCita(citaId);
            }, 500);
        }

        function imprimirRecibo(citaId) {
            // Aquí iría la lógica para imprimir el recibo
            window.open(`/admin/citas/${citaId}/recibo`, '_blank');
        }

        function nuevoServicio() {
            document.getElementById('servicioModalTitle').innerHTML = '<i class="fas fa-plus"></i> Nuevo Servicio';
            document.getElementById('servicio_id').value = '';
            document.getElementById('servicio_nombre').value = '';
            document.getElementById('servicio_descripcion').value = '';
            document.getElementById('servicio_precio').value = '';
            document.getElementById('servicio_duracion').value = '';
            document.getElementById('servicio_activo').value = '1';
            document.getElementById('servicioModal').style.display = 'flex';
        }

        function editarServicio(servicioId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            document.getElementById('servicioModalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Servicio';
            document.getElementById('servicio_id').value = servicioId;
            document.getElementById('servicio_nombre').value = 'Lavado Completo';
            document.getElementById('servicio_descripcion').value =
                'Lavado exterior e interior completo con aspirado y limpieza de tapicería';
            document.getElementById('servicio_precio').value = '25.00';
            document.getElementById('servicio_duracion').value = '30';
            document.getElementById('servicio_activo').value = '1';
            document.getElementById('servicioModal').style.display = 'flex';
        }

        function mostrarModalHorario() {
            document.getElementById('horarioModalTitle').innerHTML = '<i class="fas fa-plus"></i> Agregar Horario';
            document.getElementById('horario_id').value = '';
            document.getElementById('horario_dia').value = '';
            document.getElementById('horario_inicio').value = '';
            document.getElementById('horario_fin').value = '';
            document.getElementById('horario_activo').value = '1';
            document.getElementById('horarioModal').style.display = 'flex';
        }

        function editarHorario(diaSemana) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

            document.getElementById('horarioModalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Horario';
            document.getElementById('horario_id').value = diaSemana;
            document.getElementById('horario_dia').value = diaSemana;
            document.getElementById('horario_inicio').value = '07:00';
            document.getElementById('horario_fin').value = '18:00';
            document.getElementById('horario_activo').value = '1';
            document.getElementById('horarioModal').style.display = 'flex';
        }

        function activarHorario(diaSemana) {
            // Aquí iría la petición AJAX para activar el horario
            Toast.fire({
                icon: 'success',
                title: 'Horario activado correctamente'
            });

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }

        function desactivarHorario(diaSemana) {
            // Aquí iría la petición AJAX para desactivar el horario
            Toast.fire({
                icon: 'success',
                title: 'Horario desactivado correctamente'
            });

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }

        // Función para abrir el modal de edición de perfil
        function editarPerfil() {
            document.getElementById('perfil_nombre').value = '{{ Auth::user()->nombre }}';
            document.getElementById('perfil_telefono').value = '{{ Auth::user()->telefono ?? '' }}';
            document.getElementById('perfilModal').style.display = 'flex';
        }

        // Manejar envío del formulario de perfil
        document.getElementById('perfilForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route('perfil.update-ajax') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre: document.getElementById('perfil_nombre').value,
                        telefono: document.getElementById('perfil_telefono').value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar la UI
                    document.querySelector('.profile-name').textContent = data.user.nombre;
                    if (data.user.telefono) {
                        document.querySelector('.profile-info-item:nth-child(2) span').textContent = data.user
                            .telefono;
                    }

                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });

                    closeModal('perfilModal');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message || 'Error al actualizar el perfil'
                });
            }
        });

        // Manejar envío del formulario de horario
        document.getElementById('horarioForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Aquí iría la petición AJAX para guardar el horario
            const isNew = document.getElementById('horario_id').value === '';

            Toast.fire({
                icon: 'success',
                title: `Horario ${isNew ? 'creado' : 'actualizado'} correctamente`
            });

            closeModal('horarioModal');

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });

        // Manejar envío del formulario de servicio
        document.getElementById('servicioForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Aquí iría la petición AJAX para guardar el servicio
            const isNew = document.getElementById('servicio_id').value === '';

            Toast.fire({
                icon: 'success',
                title: `Servicio ${isNew ? 'creado' : 'actualizado'} correctamente`
            });

            closeModal('servicioModal');

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });

        // Manejar envío del formulario de perfil
        document.getElementById('perfilForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Aquí iría la petición AJAX para guardar el perfil
            Toast.fire({
                icon: 'success',
                title: 'Perfil actualizado correctamente'
            });

            closeModal('perfilModal');

            // Simulación de recarga de datos
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });

        // Función para cerrar modales
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Cerrar modales al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal('detalleCitaModal');
                closeModal('editarCitaModal');
                closeModal('servicioModal');
                closeModal('horarioModal');
                closeModal('perfilModal');
                closeModal('usuarioModal');
            }
        });

        // Función para mostrar el modal de usuario
        function mostrarModalUsuario() {
            document.getElementById('usuarioModal').style.display = 'flex';
        }

        // Manejar envío del formulario de usuario
        document.getElementById('usuarioForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                nombre: document.getElementById('usuario_nombre').value,
                email: document.getElementById('usuario_email').value,
                telefono: document.getElementById('usuario_telefono').value,
                rol: document.getElementById('usuario_rol').value,
                password: document.getElementById('usuario_password').value,
                password_confirmation: document.getElementById('usuario_password_confirmation').value,
                estado: document.getElementById('usuario_estado').value
            };

            try {
                const response = await fetch('{{ route('admin.usuarios.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Usuario creado correctamente'
                    });

                    closeModal('usuarioModal');

                    // Limpiar el formulario
                    document.getElementById('usuarioForm').reset();

                    actualizarGraficoUsuarios();

                    // Recargar la página para ver los cambios
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Error al crear el usuario');
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
            }
        });
    </script>
</body>

</html>
