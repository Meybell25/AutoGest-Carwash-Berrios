<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Administración - AutoGest Carwash</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.x/dist/tailwind.min.js"></script>
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
            background: transparent;
            border: none;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
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
        MODALES PERSONALIZADOS (NO BOOTSTRAP)
        ====================== */
        .modal.custom-modal {
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

        .modal.custom-modal .modal-content {
            background: var(--bg-light);
            backdrop-filter: var(--blur);
            margin: 5% auto;
            padding: 20px;
            border-radius: 25px;
            width: 100%;
            max-width: 600px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-light);
            animation: modalSlideIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        #usuarioModal .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }

        #usuarioModal {
            overflow: hidden;
        }

        #passwordMatchMessage {
            font-size: 0.8rem;
            margin-top: 5px;
            height: 18px;
        }

        .text-success {
            color: #10b981;
        }

        .text-danger {
            color: #ef4444;
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

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            z-index: 1000;
            /* Asegura que esté por encima de todo */
            color: var(--primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            transform: scale(1.2) rotate(90deg);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group .relative {
            position: relative;
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

        /* Estilos para el formulario de usuario en el modal */

        .password-input-container {
            position: relative;
            width: 100%;
        }

        /* Estilo para el input de contraseña */
        .password-input {
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            z-index: 10;
            padding: 5px;
            font-size: 1rem;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Asegurar que el input tenga espacio para el botón */
        #password,
        #password_confirmation {
            padding-right: 35px !important;
        }

        /* Estilo para los iconos dentro del botón */
        .password-toggle i {
            font-size: 1rem;
        }

        /* Estilo para los mensajes de validación */
        .password-requirements {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Barra de fortaleza de contraseña */
        .password-strength-meter {
            height: 5px;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength-meter-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        /* Colores para los diferentes niveles de fortaleza */
        .password-weak {
            background-color: #ff5252;
            width: 25%;
        }

        .password-medium {
            background-color: #ffb74d;
            width: 50%;
        }

        .password-strong {
            background-color: #4caf50;
            width: 75%;
        }

        .password-very-strong {
            background-color: #2e7d32;
            width: 100%;
        }

        .password-strength-text {
            font-size: 0.8rem;
            margin-top: 5px;
            color: var(--text-secondary);
        }

        .text-green-500 {
            color: #10b981;
        }

        .text-red-500 {
            color: #ef4444;
        }

        .text-gray-400 {
            color: #9ca3af;
        }

        /* Z-index para modales anidados - asegurar que modales de edición aparezcan encima */
        #modalAgregarDiaDashboard.modal {
            z-index: 1070;
            /* Mayor que el modal principal (1055) */
        }

        #modalAgregarDiaDashboard .modal-backdrop {
            z-index: 1069;
            /* Backdrop del modal de edición */
        }

        #gastoModal.modal {
            z-index: 1070;
            /* Mayor que el modal principal */
        }

        #gastoModal .modal-backdrop {
            z-index: 1069;
            /* Backdrop del modal de edición */
        }

        /* Asegurar que los modales principales tengan z-index base */
        #modalVerTodosDiasNoLaborables.modal {
            z-index: 1055;
        }

        #modalVerTodosGastos.modal {
            z-index: 1055;
        }

        #modalVerTodosServicios.modal {
            z-index: 1055;
        }

        /* Modal de servicios secundario con z-index superior */
        #servicioModal.modal {
            z-index: 1070 !important;
        }

        /* Cuando servicioModal está activo sobre modalVerTodosServicios */
        .modal.show#servicioModal {
            z-index: 1070 !important;
            background-color: rgba(0, 0, 0, 0.8) !important;
        }

        /* Estilos específicos para modal de servicios cuando está superpuesto */
        #servicioModal .modal-content {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }

        /* Animación de aparición para modal superpuesto */
        #servicioModal.show .modal-content {
            animation: modalNestedSlideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes modalNestedSlideIn {
            0% {
                transform: scale(0.8) translateY(-50px);
                opacity: 0;
            }

            100% {
                transform: scale(1.02) translateY(0);
                opacity: 1;
            }
        }

        /* Forzar z-index para modales de edición cuando están activos */
        .modal.show#modalAgregarDiaDashboard {
            z-index: 1070 !important;
        }

        .modal.show#gastoModal {
            z-index: 1070 !important;
        }

        /* Backdrop específico para modales secundarios */
        body.modal-open .modal-backdrop+.modal-backdrop {
            z-index: 1069 !important;
        }

        .password-requirements ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .password-requirements li {
            margin-bottom: 0.3rem;
            transition: color 0.3s ease;
        }

        /* Estilos para el spinner */
        .fa-spinner.fa-spin {
            margin-right: 8px;
        }

        .password-match-message {
            margin-top: 5px;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .password-match-message.valid {
            color: #28a745;
        }

        .password-match-message.invalid {
            color: #dc3545;
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

        /* Arreglos para el modal de perfil */
        #perfilModal.modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        #perfilModal .modal-content {
            background: white;
            border-radius: 12px;
            padding: 25px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        #perfilModal .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-secondary);
        }

        #perfilModal h2 {
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #perfilForm .form-group {
            margin-bottom: 20px;
        }

        #perfilForm label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }

        #perfilForm .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border-primary);
            border-radius: 8px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        #perfilForm .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            outline: none;
        }

        #perfilForm button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #perfilForm button[type="submit"]:hover {
            background: var(--secondary);
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
            Estilos para la tabla de citas de hoy
            ====================== */

        #citasHoyTable tr[data-activo="cancelada"] {
            opacity: 0.7;
            background-color: rgba(245, 245, 245, 0.5);
        }

        #citasHoyTable tr[data-activo="finalizada"] {
            background-color: rgba(232, 245, 233, 0.3);
        }

        #citasHoyTable tr[data-activo="en_proceso"] {
            background-color: rgba(225, 245, 254, 0.3);
        }

        /* Mejora para badges de activo */
        .badge-pendiente {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #ef6c00;
            border: 1px solid #ffcc80;
        }

        .badge-confirmada {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc);
            color: #0277bd;
            border: 1px solid #81d4fa;
        }

        .badge-en_proceso {
            background: linear-gradient(135deg, #f1e6ff, #e1bee7);
            color: #6a1b9a;
            border: 1px solid #ce93d8;
        }

        .badge-finalizada {
            background: linear-gradient(135deg, #e0f2e0, #c8e6c9);
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .badge-cancelada {
            background: linear-gradient(135deg, #fde7f3, #f8bbd9);
            color: #ad1457;
            border: 1px solid #f48fb1;
        }

        .badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        /* ======================
            FORMULARIOS
            ====================== */
        .search-filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
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

        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination-list {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item {
            margin: 0 5px;
        }

        .page-link {
            padding: 8px 12px;
            border: 1px solid var(--border-primary);
            border-radius: 5px;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-link:hover,
        .page-item.active .page-link {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            pointer-events: none;
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
                gap: 15px;
                justify-content: center;
            }

            .header-actions .btn {
                padding: 12px 15px;
                font-size: 0.9rem;
                min-width: auto;
                flex: 1 1 auto;
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

            /* Mejoras para cards */
            .card {
                border-radius: 18px;
            }

            .card-header,
            .card-body {
                padding: 15px 20px;
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

            .modal.custom-modal .modal-content {
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

            /* Mejoras para días no laborables */
            #diasNoLaborablesTable td[data-label="Motivo"] {
                white-space: normal;
                word-break: break-word;
            }

            /* Mejoras para gestión de gastos */
            .search-filter-container {
                flex-direction: column;
            }

            #gastosTable td {
                padding-left: 45% !important;
            }

            #gastosTable td[data-label="Detalle"] {
                white-space: normal;
                word-break: break-word;
            }

            .admin-table {
                font-size: 0.85rem;
            }

            .admin-table td:before {
                width: 40%;
                padding-right: 8px;
                font-size: 0.8rem;
            }

            .card-header-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .card-header-actions h3 {
                margin-bottom: 0;
                white-space: normal;
                word-break: break-word;
                width: 100%;
            }

            .card-header-actions .btn {
                align-self: flex-end;
            }

            #usuarioModal .modal-content {
                max-height: 85vh;
                width: 95%;
                margin: 2% auto;
            }

            #usuarioForm {
                min-height: min-content;
            }

            .close-modal {
                top: 10px;
                right: 15px;
                font-size: 24px;
                background: rgba(255, 255, 255, 0.9);
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .header-actions {
                gap: 12px;
                justify-content: space-between;
            }

            .header-actions .btn {
                flex: 1 1 calc(50% - 6px);
                min-width: calc(50% - 6px);
                margin-bottom: 0;
            }

            .header-actions .btn i {
                margin-right: 5px;
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

            /* Ajustes para móviles pequeños */
            #diasNoLaborablesTable td,
            #gastosTable td {
                padding: 8px 5px;
                font-size: 0.85rem;
            }

            .table-actions {
                flex-direction: column;
                gap: 5px;
            }

            .table-btn {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            #usuarioForm .form-grid {
                grid-template-columns: 1fr !important;
                gap: 15px;
            }

            #usuarioForm .password-input-container {
                position: relative;
            }

            #usuarioForm .password-toggle {
                right: 10px;
            }

            #usuarioForm .form-control {
                padding: 12px 35px 12px 12px;
            }

            .header-actions {
                flex-direction: column;
                gap: 10px;
            }

            .header-actions .btn {
                width: 100%;
                flex: 1 1 100%;
                min-width: 100%;
                margin-bottom: 0;
            }

            .header-actions .btn i {
                margin-right: 8px;
            }

            #usuarioModal {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            #usuarioModal .modal-content {
                max-height: 95vh;
                margin-top: 10px;
            }

            #usuarioModal input,
            #usuarioModal select,
            #usuarioModal textarea {
                font-size: 16px;
            }

            #usuarioModal .form-group {
                margin-bottom: 15px;
            }

            #usuarioModal .password-requirements {
                columns: 1;
            }

            .close-modal {
                top: 8px;
                right: 12px;
                font-size: 22px;
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

            .modal.custom-modal .modal-content {
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

            body {
                word-break: break-word;
            }

            .info-item:last-child {
                white-space: normal !important;
            }

            .card-header-actions .btn {
                width: 100%;
                text-align: center;
            }

            .password-requirements ul {
                padding-left: 1.2rem;
                font-size: 0.75rem;
            }

            .password-requirements li {
                margin-bottom: 5px;
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
                            <span class="number">{{ $stats['citas_confirmadas_hoy'] ?? 0 }}</span>
                            <span class="label">Citas Confirmadas Hoy</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">${{ number_format($stats['ingresos_hoy'] ?? 0, 2) }}</span>
                            <span class="label">Ingresos Hoy</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-primary" onclick="mostrarModalUsuario()">
                        <i class="fas fa-user-plus"></i>
                        Crear Usuarios
                    </button>
                    <button onclick="abrirModalCitasAdmin()" class="btn btn-success">
                        <i class="fas fa-calendar"></i>
                        Citas
                    </button>
                    <button onclick="abrirModalBitacora()" class="btn btn-primary">
                        <i class="fas fa-book"></i>
                        Bitácora
                    </button>
                    <button onclick="abrirModalConfiguracion()" class="btn btn-info">
                        <i class="fas fa-cog"></i>
                        Configuración
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

        <!-- Estadísticas Rápidas -->
        <div class="dashboard-grid">
            <div class="main-section">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="admin-stat-card stat-card-primary">
                        <div class="stat-icon icon-primary">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value">{{ $stats['citas_pendientes'] ?? 0 }}</div>
                        <div class="stat-label">Citas Pendientes</div>
                    </div>
                    <div class="admin-stat-card stat-card-success">
                        <div class="stat-icon icon-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">${{ number_format($stats['ingresos_mensuales'] ?? 0, 2) }}</div>
                        <div class="stat-label">Ingresos Mensuales</div>
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

                <!-- Contenedor para Días No Laborables -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            Días No Laborables
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="card-header-actions"
                            style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <h3 style="color: var(--text-primary); margin: 0; max-width: 50%;">Días festivos y feriados
                            </h3>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="abrirModalVerTodosDiasNoLaborables()">
                                    <i class="fas fa-list"></i> Ver todos
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" id="btnAgregarDiaDashboard"
                                    onclick="abrirModalDiaDashboard(); return false;">
                                    <i class="fas fa-plus"></i> Agregar Día
                                </button>
                            </div>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Motivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $diasNoLaborables = \App\Models\DiaNoLaborable::orderBy('fecha', 'desc')
                                            ->limit(3)
                                            ->get();
                                        $motivosDisponibles = \App\Models\DiaNoLaborable::getMotivosDisponibles();
                                    @endphp

                                    @forelse($diasNoLaborables as $dia)
                                        <tr>
                                            <td data-label="Fecha">{{ $dia->fecha->format('d/m/Y') }}</td>
                                            <td data-label="Motivo">
                                                {{ $motivosDisponibles[$dia->motivo] ?? $dia->motivo }}
                                            </td>
                                            <td data-label="Acciones">
                                                <div class="table-actions">
                                                    <button onclick="editarDiaNoLaborable({{ $dia->id }})"
                                                        class="table-btn btn-edit" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="table-btn btn-delete" title="Eliminar"
                                                        onclick="eliminarDiaNoLaborable({{ $dia->id }}, '{{ $dia->fecha->format('d/m/Y') }}', '{{ $motivosDisponibles[$dia->motivo] ?? $dia->motivo }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                style="text-align: center; padding: 20px; color: #666;">
                                                No hay días no laborables registrados.
                                                <br>
                                                <a href="{{ route('admin.dias-no-laborables.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">
                                                    Agregar el primero
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($diasNoLaborables->count() > 0)
                            <div style="text-align: center; margin-top: 15px;">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="abrirModalVerTodosDiasNoLaborables()">
                                    <i class="fas fa-list"></i> Ver todos los días no laborables
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contenedor para Gestión de Gastos-->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            Gestión de Gastos
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="card-header-actions"
                            style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <h3 style="color: var(--text-primary); margin: 0; max-width: 50%;">Control financiero y
                                seguimiento</h3>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="abrirModalVerTodosGastos()">
                                    <i class="fas fa-chart-pie"></i> Ver todos
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalGasto()">
                                    <i class="fas fa-plus"></i> Registrar Gasto
                                </button>
                            </div>
                        </div>

                        <!-- Solo obtener gastos recientes para la tabla -->
                        @php
                            $gastosRecientes = \App\Models\Gasto::with([
                                'usuario' => function ($query) {
                                    $query->whereIn('rol', ['admin', 'empleado']);
                                },
                            ])
                                ->where('monto', '>', 0)
                                ->whereNotNull('detalle')
                                ->where('detalle', '!=', '')
                                ->latest('fecha_gasto')
                                ->limit(10)
                                ->get();

                            $tiposGastos = \App\Models\Gasto::getTipos();
                        @endphp

                        <!-- Tabla de gastos recientes -->
                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Detalle</th>
                                        <th>Monto</th>
                                        <th>Registrado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gastosRecientes as $gasto)
                                        <tr>
                                            <td data-label="Fecha">
                                                {{ $gasto->fecha_gasto->format('d/m/Y') }}
                                                <br>
                                                <small
                                                    style="color: #666;">{{ $gasto->fecha_gasto->diffForHumans() }}</small>
                                            </td>
                                            <td data-label="Tipo">
                                                <span class="badge"
                                                    style="
                                @if ($gasto->tipo == 'stock') background: #3498db;
                                @elseif($gasto->tipo == 'sueldos') background: #27ae60;
                                @elseif($gasto->tipo == 'personal') background: #f39c12;
                                @elseif($gasto->tipo == 'mantenimiento') background: #9b59b6;
                                @else background: #95a5a6; @endif
                                color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                                    {{ $tiposGastos[$gasto->tipo] ?? $gasto->tipo }}
                                                </span>
                                            </td>
                                            <td data-label="Detalle">
                                                <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                    title="{{ $gasto->detalle }}">
                                                    {{ $gasto->detalle }}
                                                </div>
                                            </td>
                                            <td data-label="Monto">
                                                <strong style="color: #e74c3c; font-size: 1.1rem;">
                                                    ${{ number_format($gasto->monto, 2) }}
                                                </strong>
                                            </td>
                                            <td data-label="Realizado por">
                                                @if ($gasto->usuario)
                                                    <div>
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $gasto->usuario->nombre }}
                                                    </div>
                                                    <small style="color: #666;">{{ $gasto->usuario->email }}</small>
                                                @else
                                                    <span style="color: #666;">Usuario no disponible</span>
                                                @endif
                                            </td>
                                            <td data-label="Acciones">
                                                <div class="table-actions">
                                                    <button onclick="verDetalleGasto({{ $gasto->id }})"
                                                        class="table-btn btn-view" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button onclick="editarGasto({{ $gasto->id }})"
                                                        class="table-btn btn-edit" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="table-btn btn-delete" title="Eliminar"
                                                        onclick="eliminarGastoModal({{ $gasto->id }}, '{{ addslashes($gasto->detalle) }}', {{ $gasto->monto }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                style="text-align: center; padding: 40px; color: #666;">
                                                <i class="fas fa-receipt"
                                                    style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                                <br>
                                                <h4 style="color: #666; margin-bottom: 10px;">No hay gastos registrados
                                                </h4>
                                                <p style="margin-bottom: 20px;">Comienza registrando tu primer gasto en
                                                    el sistema.</p>
                                                <button onclick="abrirModalGasto()" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Registrar Primer Gasto
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Enlaces de acciones solo si hay gastos -->
                        @if ($gastosRecientes->count() > 0)
                            <div
                                style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="abrirModalVerTodosGastos()">
                                        <i class="fas fa-chart-pie me-1"></i> Ver Todos los Gastos
                                    </button>
                                    <button onclick="abrirModalGasto()" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Registrar Nuevo Gasto
                                    </button>
                                </div>
                                <small style="color: #666; margin-top: 10px; display: block;">
                                    Mostrando los últimos {{ $gastosRecientes->count() }} gastos registrados
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Script para eliminar gastos -->
                <script>
                    function eliminarGasto(id, detalle, monto) {
                        const confirmar = confirm(
                            `¿Estás seguro de que quieres eliminar este gasto?\n\n` +
                            `Detalle: ${detalle}\n` +
                            `Monto: $${parseFloat(monto).toFixed(2)}\n\n` +
                            `Esta acción no se puede deshacer.`
                        );

                        if (confirmar) {
                            // Crear formulario dinámicamente para enviar DELETE
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/gastos/${id}`;

                            // Token CSRF
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (csrfToken) {
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = csrfToken;
                                form.appendChild(csrfInput);
                            }

                            // Método DELETE
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            // Enviar formulario
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                </script>
                <!-- Gráficos -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h2>
                                <div class="card-header-icon icon-container">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                Rendimiento Mensual
                            </h2>
                            <button onclick="abrirModalReportes()" class="btn btn-primary"
                                style="padding: 8px 12px;">
                                <i class="fas fa-chart-bar"></i> Ver Reportes Completos
                            </button>
                        </div>
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
                            Últimas Citas - Hoy ({{ now()->format('d/m/Y') }})
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="search-filter-container">
                            <div class="search-box">
                                <input type="text" id="searchCitas" placeholder="Buscar citas..."
                                    class="form-control">
                            </div>
                            <div class="filter-select">
                                <select id="filterEstado" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
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
                                <tbody id="citasHoyTable">
                                    @php
                                        // Obtener citas de hoy ordenadas por hora
                                        $citasHoy = \App\Models\Cita::with(['usuario', 'vehiculo', 'servicios', 'pago'])
                                            ->whereDate('fecha_hora', today())
                                            ->orderBy('fecha_hora', 'asc')
                                            ->get();
                                    @endphp

                                    @forelse($citasHoy as $cita)
                                        <tr data-estado="{{ $cita->estado }}">
                                            <td data-label="ID">#{{ $cita->id }}</td>
                                            <td data-label="Cliente">{{ $cita->usuario->nombre }}</td>
                                            <td data-label="Vehículo">{{ $cita->vehiculo->marca }}
                                                {{ $cita->vehiculo->modelo }}</td>
                                            <td data-label="Fecha/Hora">{{ $cita->fecha_hora->format('H:i') }}</td>
                                            <td data-label="Servicios">
                                                @foreach ($cita->servicios as $index => $servicio)
                                                    <span
                                                        class="badge service-badge-{{ ($index % 5) + 1 }}">{{ $servicio->nombre }}</span>
                                                @endforeach
                                            </td>
                                            <td data-label="Total">${{ number_format($cita->total, 2) }}</td>
                                            <td data-label="Estado">
                                                <span class="appointment-status status-{{ $cita->estado }}">
                                                    {{ $cita->estado_formatted }}
                                                </span>
                                            </td>
                                            <td data-label="Acciones">
                                                <div class="table-actions">
                                                    <select class="estado-select-dashboard"
                                                        data-cita-id="{{ $cita->id }}"
                                                        style="width: 120px; padding: 5px; margin-bottom: 5px;">
                                                        <option value="pendiente"
                                                            {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>
                                                            Pendiente</option>
                                                        <option value="confirmada"
                                                            {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>
                                                            Confirmada</option>
                                                        <option value="en_proceso"
                                                            {{ $cita->estado == 'en_proceso' ? 'selected' : '' }}>En
                                                            Proceso</option>
                                                        <option value="finalizada"
                                                            {{ $cita->estado == 'finalizada' ? 'selected' : '' }}
                                                            {{ !$cita->tienePagoCompletado() ? 'disabled' : '' }}>
                                                            Finalizada</option>
                                                        <option value="cancelada"
                                                            {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>
                                                            Cancelada</option>
                                                    </select>

                                                    @if ($cita->estado == 'en_proceso' && !$cita->tienePagoCompletado())
                                                        <button class="btn btn-success btn-sm btn-pagar-dashboard mt-1"
                                                            data-cita-id="{{ $cita->id }}">
                                                            <i class="fas fa-credit-card me-1"></i> Pagar
                                                        </button>
                                                    @endif

                                                    @if ($cita->tienePagoCompletado())
                                                        <div class="text-center mt-1">
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i> Pagado
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <button class="btn btn-details btn-sm mt-1 view-details-dashboard"
                                                        data-cita-id="{{ $cita->id }}">
                                                        <i class="fas fa-eye me-1"></i> Detalles
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="empty-state">
                                                <i class="fas fa-calendar-times"></i>
                                                <h4>No hay citas para hoy</h4>
                                                <p>No hay citas programadas para el día de hoy</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-container">
                            <nav class="pagination" aria-label="Paginación de citas">
                                <ul class="pagination-list" id="paginationCitas">
                                    <!-- Los elementos de paginación se generarán dinámicamente -->
                                </ul>
                            </nav>
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
                                onclick="mostrarModal('perfilModal')">
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
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Usuarios Totales
                                </div>
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

                        <button onclick="abrirModalUsuarios()" class="btn btn-outline" style="width: 100%;">
                            <i class="fas fa-list"></i> Ver Todos los Usuarios
                        </button>
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
                                    <p>${{ number_format($servicio->precio, 2) }} -
                                        {{ $servicio->duracion_min ?? $servicio->duracion }} min
                                    </p>
                                    <p>
                                        @if ($servicio->categoria === 'sedan')
                                            🚗 Sedán
                                        @elseif($servicio->categoria === 'pickup')
                                            🚙 Pickup/SUV
                                        @elseif($servicio->categoria === 'moto')
                                            🏍️ Motocicleta
                                        @else
                                            <i class="fas fa-car"></i> {{ ucfirst($servicio->categoria) }}
                                        @endif
                                    </p>
                                    <p><i class="fas fa-chart-line"></i> {{ $servicio->citas_count ?? 0 }} veces
                                        este
                                        mes</p>
                                </div>
                                <button class="btn btn-sm btn-outline" onclick="editarServicio({{ $servicio->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        @endforeach

                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button type="button" class="btn btn-outline-primary" style="flex: 1;"
                                onclick="abrirModalVerTodosServicios()">
                                <i class="fas fa-list me-1"></i> Ver Todos
                            </button>
                            <button class="btn btn-primary" style="flex: 1;" onclick="nuevoServicio()">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                        </div>
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
            <div id="servicioModal" class="modal"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                <div class="modal-content"
                    style="background: white; border-radius: 12px; padding: 25px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
                    <span class="close-modal" onclick="cerrarModal('servicioModal')"
                        style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: var(--text-secondary);">&times;</span>
                    <h2 id="servicioModalTitle"
                        style="margin-bottom: 20px; font-size: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-plus"></i> Nuevo Servicio
                    </h2>
                    <div id="servicioModalContent">
                        <!-- El contenido se llenará dinámicamente -->
                    </div>
                </div>
            </div>

            <!-- Modal para ver todos los servicios -->
            <div class="modal fade" id="modalVerTodosServicios" tabindex="-1"
                aria-labelledby="modalVerTodosServiciosLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header"
                            style="background: linear-gradient(135deg, #007bff, #6c5ce7); color: white;">
                            <h5 class="modal-title" id="modalVerTodosServiciosLabel">
                                <i class="fas fa-cogs me-2"></i>
                                Gestión de Servicios
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Controles superiores -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="buscarServicio"
                                            placeholder="Buscar servicio..." onkeyup="filtrarServicios()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="filtroCategoria" onchange="filtrarServicios()">
                                        <option value="">Todos los tipos</option>
                                        <option value="sedan">🚗 Sedán</option>
                                        <option value="pickup">🚙 Pickup/SUV</option>
                                        <option value="moto">🏍️ Motocicleta</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-success w-100" onclick="nuevoServicioModal()">
                                        <i class="fas fa-plus"></i> Nuevo Servicio
                                    </button>
                                </div>
                            </div>

                            <!-- Lista de servicios -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Precio</th>
                                            <th>Duración</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaServicios">
                                        <!-- Los servicios se cargarán aquí dinámicamente -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación y estadísticas -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <small class="text-muted" id="estadisticasServicios">
                                        <!-- Estadísticas se cargarán aquí -->
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div id="paginacionServicios">
                                        <!-- Paginación se cargará aquí si es necesaria -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Horarios (Bootstrap, estilo igual a Días No Laborables) -->
            <div class="modal fade modal-horarios" id="horarioCRUDModal" tabindex="-1"
                aria-labelledby="horarioCRUDModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="horarioCRUDModalTitle">
                                <i class="fas fa-clock me-2"></i>
                                Agregar Horario
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="horarioForm">
                                <input type="hidden" id="horario_id" name="id">

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="horario_dia" class="form-label"><i
                                                class="fas fa-calendar-week me-1"></i> Día de la semana</label>
                                        <select id="horario_dia" class="form-control" required>
                                            <option value="">Seleccione un día</option>
                                            <option value="1">Lunes</option>
                                            <option value="2">Martes</option>
                                            <option value="3">Miércoles</option>
                                            <option value="4">Jueves</option>
                                            <option value="5">Viernes</option>
                                            <option value="6">Sábado</option>
                                        </select>
                                        <div class="invalid-feedback d-block" id="error_horario_dia"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="horario_activo" class="form-label"><i
                                                class="fas fa-toggle-on me-1"></i> Estado</label>
                                        <div class="form-check form-switch" style="padding-top: 8px;">
                                            <input class="form-check-input" type="checkbox"
                                                id="horario_activo_switch" checked>
                                            <label class="form-check-label" for="horario_activo_switch">Activo</label>
                                        </div>
                                        <input type="hidden" id="horario_activo" value="1">
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="horario_inicio" class="form-label"><i
                                                class="fas fa-clock me-1"></i> Hora de inicio</label>
                                        <input type="time" id="horario_inicio" class="form-control" required>
                                        <div class="invalid-feedback d-block" id="error_horario_inicio"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="horario_fin" class="form-label"><i class="fas fa-clock me-1"></i>
                                            Hora de fin</label>
                                        <input type="time" id="horario_fin" class="form-control" required>
                                        <div class="invalid-feedback d-block" id="error_horario_fin"></div>
                                    </div>
                                </div>

                                <div class="alert alert-info" role="alert">
                                    <div style="font-weight:600; margin-bottom:6px;"><i
                                            class="fas fa-info-circle me-1"></i> Información importante</div>
                                    <ul style="margin:0; padding-left: 18px;">
                                        <li>Los horarios aplican de Lunes a Sábado.</li>
                                        <li>La hora de inicio debe ser menor a la hora de fin.</li>
                                        <li>No se permiten horarios duplicados por día y hora de inicio.</li>
                                        <li>Los cambios afectan la disponibilidad para agendar citas.</li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                            <button type="button" class="btn btn-primary" id="btnGuardarHorario">
                                <i class="fas fa-save me-1"></i> Guardar Horario
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal para Días No Laborables -->
            <div id="diaNoLaborableModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal('diaNoLaborableModal')">&times;</span>
                    <h2 id="diaNoLaborableModalTitle">
                        <i class="fas fa-calendar-times"></i> Agregar Día No Laborable
                    </h2>
                    <form id="diaNoLaborableForm">
                        <div class="form-group">
                            <label for="diaNoLaborableFecha">Fecha:</label>
                            <input type="date" id="diaNoLaborableFecha" name="fecha" required
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="diaNoLaborableMotivo">Motivo (opcional):</label>
                            <input type="text" id="diaNoLaborableMotivo" name="motivo" class="form-control"
                                placeholder="Ej: Feriado nacional">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal para Gastos (Bootstrap) -->
            <div class="modal fade modal-gastos" id="gastoModal" tabindex="-1" aria-labelledby="gastoModalTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="gastoModalTitle">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Registrar Gasto
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Alerta para mensajes -->
                            <div id="alertaGastoModal" class="alert" style="display: none;"></div>

                            <form id="gastoForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="gastoTipo" class="form-label">Tipo de Gasto</label>
                                        <select id="gastoTipo" name="tipo" class="form-control" required>
                                            <option value="">Seleccione tipo</option>
                                            <option value="stock">📦 Stock</option>
                                            <option value="sueldos">👥 Sueldos</option>
                                            <option value="personal">👤 Personal</option>
                                            <option value="mantenimiento">🔧 Mantenimiento</option>
                                            <option value="otro">📄 Otro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gastoMonto" class="form-label">Monto</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" id="gastoMonto" name="monto"
                                                required class="form-control" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="gastoDetalle" class="form-label">Detalle del Gasto</label>
                                    <textarea id="gastoDetalle" name="detalle" rows="3" required class="form-control"
                                        placeholder="Descripción detallada del gasto..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="gastoFecha" class="form-label">Fecha del Gasto</label>
                                    <input type="date" id="gastoFecha" name="fecha" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-primary" id="btnGuardarGasto">
                                <i class="fas fa-save me-1"></i>
                                Registrar Gasto
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para ver detalles del gasto -->
            <div id="detalleGastoModal" class="modal"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1100; align-items: center; justify-content: center;">
                <div class="modal-content"
                    style="background: white; border-radius: 12px; padding: 25px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
                    <span class="close-modal" onclick="closeModal('detalleGastoModal')"
                        style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: var(--text-secondary);">&times;</span>
                    <h2 id="detalleGastoModalTitle" style="color: var(--primary); margin-bottom: 20px;">
                        <i class="fas fa-receipt"></i> Detalle del Gasto
                    </h2>
                    <div id="detalleGastoContent">
                        <!-- Contenido dinámico se insertará aquí -->
                    </div>
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

    <!-- Modal para detalles de cita  -->
    <div class="modal fade" id="detallesCitaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Cita #<span id="cita-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detalles-cita-content">
                    <!-- Los detalles se cargarán aquí via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para pago= -->
    <div class="modal fade" id="pagoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="pago-modal-content">
                <!-- El contenido se cargará via AJAX -->
            </div>
        </div>
    </div>


    <!-- Modal para crear nuevo usuario -->
    <div id="usuarioModal" class="modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; border-radius: 12px; padding: 25px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
            <span class="close-modal" onclick="closeModal('usuarioModal')"
                style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: var(--text-secondary);">&times;</span>

            <h2 id="modalUsuarioTitle"
                style="margin-bottom: 20px; font-size: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-user-plus"></i>
                <span id="modalTitleText">Crear Nuevo Usuario</span>
            </h2>

            <form id="usuarioForm" style="margin-top: 20px;">
                @csrf
                <input type="hidden" id="usuario_id" name="id">

                <div class="form-grid"
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="nombre"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Nombre
                            Completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                            placeholder="Ej: Juan Pérez">
                    </div>

                    <div class="form-group">
                        <label for="email"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Correo
                            Electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" required
                            placeholder="Ej: juan@example.com" readonly>
                        <div id="email-error" class="hidden text-sm text-red-600 mt-1"></div>
                        <small id="emailHelp"
                            style="color: var(--text-secondary); display: block; margin-top: 5px; display: none;">
                            El correo electrónico no puede ser modificado
                        </small>
                    </div>
                </div>

                <div class="form-grid"
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="telefono"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control"
                            placeholder="Ej: 75855197" pattern="[0-9]{8}" maxlength="8">
                    </div>

                    <div class="form-group">
                        <label for="rol"
                            style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Rol</label>
                        <select id="rol" name="rol" class="form-control" required readonly>
                            <option value="cliente">Cliente</option>
                            <option value="empleado">Empleado</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <small id="rolHelp"
                            style="color: var(--text-secondary); display: block; margin-top: 5px; display: none;">
                            El rol no puede ser modificado después de crear el usuario
                        </small>
                    </div>
                </div>

                <!-- Sección de contraseñas -->
                <div id="passwordFields" style="display: block; margin-bottom: 15px;">
                    <div class="password-fields-container">
                        <div class="form-group">
                            <label for="password"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Contraseña</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Mínimo 8 caracteres" style="padding-right: 40px;">
                                <button type="button" onclick="togglePassword('password')"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                                    <i class="fas fa-eye" id="passwordEye"></i>
                                </button>
                            </div>
                            <div class="password-requirements">
                                <div class="password-strength-meter">
                                    <div class="password-strength-meter-fill" id="passwordStrengthBar"></div>
                                </div>
                                <div class="password-strength-text" id="passwordStrengthText">Fortaleza de la
                                    contraseña</div>
                                <ul style="columns: 2; column-gap: 20px; margin-top: 10px;">
                                    <li id="req-length">Mínimo 8 caracteres</li>
                                    <li id="req-uppercase">1 letra mayúscula</li>
                                    <li id="req-lowercase">1 letra minúscula</li>
                                    <li id="req-number">1 número</li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group confirm-password-field">
                            <label for="password_confirmation"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Confirmar
                                Contraseña</label>
                            <div style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repite la contraseña"
                                    style="padding-right: 40px;">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                                    <i class="fas fa-eye" id="passwordConfirmationEye"></i>
                                </button>
                            </div>
                            <div id="passwordMatchMessage" class="password-match-message"
                                style="margin-top: 5px; font-size: 0.8rem;"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="activo"
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Estado</label>
                    <select id="activo" name="activo" class="form-control">
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem;">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
            </form>
        </div>
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
        // =============================================
        // INICIALIZACIÓN AL CARGAR LA PÁGINA
        // =============================================

        // Configuración de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Variables globales para gráficos
        let usuariosChart, ingresosChart, citasChart, serviciosChart;

        document.querySelectorAll('#usuarioModal input, #usuarioModal select').forEach(el => {
            el.addEventListener('focus', function() {
                setTimeout(() => {
                    this.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 300);
            });
        });

        // =============================================
        // FUNCIONES DE CITAS DE HOY EN DASHBOARD
        // =============================================

        // Inicializar sistema de citas en dashboard
        function initializeDashboardCitas() {
            initializeEstadoSelectsDashboard();
            setupPagoButtonsDashboard();
            setupDetallesButtonsDashboard();
            setupFiltrosDashboard();

            console.log('Sistema de Citas del Dashboard inicializado correctamente');
        }

        // 1. INICIALIZACIÓN DE SELECTS DE ESTADO EN DASHBOARD
        function initializeEstadoSelectsDashboard() {
            document.querySelectorAll('.estado-select-dashboard').forEach(select => {
                select._currentValue = select.value;
                select._previousValue = select.value;

                select.addEventListener('change', function() {
                    handleEstadoChangeDashboard(this);
                });
            });
        }

        // 2. MANEJO DE CAMBIO DE ESTADO EN DASHBOARD
        async function handleEstadoChangeDashboard(selectElement) {
            const citaId = selectElement.getAttribute('data-cita-id');
            const nuevoEstado = selectElement.value;
            const estadoActual = selectElement._currentValue;

            // Validar cambio a finalizada
            if (nuevoEstado === 'finalizada') {
                const verificacion = await verificarPagoCita(citaId);

                if (!verificacion.success || !verificacion.tiene_pago_completado) {
                    showAlert('warning', 'Pago requerido',
                        'No se puede finalizar la cita sin un pago registrado. Por favor, registre el pago primero.'
                    );
                    selectElement.value = estadoActual;
                    return;
                }
            }

            // Confirmación para cancelación
            if (nuevoEstado === 'cancelada') {
                const confirmacion = await showConfirmation(
                    '¿Confirmar cancelación?',
                    'Esta acción cancelará la cita. ¿Estás seguro?',
                    'warning'
                );

                if (!confirmacion) {
                    selectElement.value = selectElement._previousValue;
                    return;
                }
            }

            // Actualizar estado
            await actualizarEstadoCitaDashboard(citaId, nuevoEstado, selectElement);
        }

        // 3. ACTUALIZAR ESTADO DE CITA EN DASHBOARD
        async function actualizarEstadoCitaDashboard(citaId, nuevoEstado, selectElement) {
            try {
                showLoading('Actualizando estado...');

                const response = await fetch(`/admin/citasadmin/${citaId}/actualizar-estado`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: nuevoEstado
                    })
                });

                const data = await response.json();
                hideLoading();

                if (data.success) {
                    // Actualizar badge de estado
                    updateEstadoBadgeDashboard(citaId, nuevoEstado, data.nuevo_estado);

                    // Actualizar valores de control
                    if (selectElement) {
                        selectElement._previousValue = nuevoEstado;
                        selectElement._currentValue = nuevoEstado;
                    }

                    showAlert('success', '¡Éxito!', data.message, 2000);

                    // Recargar después de 2 segundos para actualizar botones y estadísticas
                    setTimeout(() => window.location.reload(), 2000);

                } else {
                    throw new Error(data.message);
                }

            } catch (error) {
                hideLoading();
                console.error('Error al actualizar estado:', error);

                showAlert('error', 'Error', error.message || 'Ocurrió un error al actualizar el estado');

                // Revertir cambio en el select
                if (selectElement) {
                    selectElement.value = selectElement._previousValue;
                }
            }
        }

        // 4. ACTUALIZAR BADGE DE ESTADO EN DASHBOARD
        function updateEstadoBadgeDashboard(citaId, estadoCodigo, estadoTexto) {
            const row = document.querySelector(`.estado-select-dashboard[data-cita-id="${citaId}"]`)?.closest('tr');
            if (!row) return;

            const badge = row.querySelector('.appointment-status');
            if (badge) {
                badge.className = `appointment-status status-${estadoCodigo}`;
                badge.textContent = estadoTexto;
            }
        }

        // 5. CONFIGURAR BOTONES DE PAGO EN DASHBOARD
        function setupPagoButtonsDashboard() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-pagar-dashboard') || e.target.closest(
                        '.btn-pagar-dashboard')) {
                    e.preventDefault();

                    const button = e.target.classList.contains('btn-pagar-dashboard') ? e.target : e.target
                        .closest('.btn-pagar-dashboard');
                    const citaId = button.getAttribute('data-cita-id');

                    if (!citaId) {
                        showAlert('error', 'Error', 'ID de cita no encontrado');
                        return;
                    }

                    openPagoModal(citaId);
                }
            });
        }

        // 6. CONFIGURAR BOTONES DE DETALLES EN DASHBOARD
        function setupDetallesButtonsDashboard() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('view-details-dashboard') || e.target.closest(
                        '.view-details-dashboard')) {
                    const button = e.target.classList.contains('view-details-dashboard') ? e.target : e.target
                        .closest('.view-details-dashboard');
                    const citaId = button.getAttribute('data-cita-id');
                    openDetallesModal(citaId);
                }
            });
        }

        // 7. CONFIGURAR FILTROS EN DASHBOARD
        function setupFiltrosDashboard() {
            const searchInput = document.getElementById('searchCitas');
            const estadoFilter = document.getElementById('filterEstado');
            const citasTable = document.getElementById('citasHoyTable');

            if (searchInput && estadoFilter && citasTable) {
                searchInput.addEventListener('input', function() {
                    filtrarCitasDashboard();
                });

                estadoFilter.addEventListener('change', function() {
                    filtrarCitasDashboard();
                });
            }

            // Inicializar paginación
            setTimeout(() => {
                inicializarPaginacionCitasDashboard();
            }, 500);
        }

        // 8. FILTRAR CITAS EN DASHBOARD
        function filtrarCitasDashboard() {
            const searchInput = document.getElementById('searchCitas');
            const estadoFilter = document.getElementById('filterEstado');
            const citasTable = document.getElementById('citasHoyTable');

            const searchText = searchInput.value.toLowerCase();
            const estadoValue = estadoFilter.value;
            const rows = citasTable.getElementsByTagName('tr');

            for (let row of rows) {
                let mostrar = true;
                const cells = row.getElementsByTagName('td');
                const estado = row.getAttribute('data-estado');

                // Filtrar por estado
                if (estadoValue && estado !== estadoValue) {
                    mostrar = false;
                }

                // Filtrar por texto de búsqueda
                if (mostrar && searchText) {
                    let textoEncontrado = false;
                    for (let cell of cells) {
                        if (cell.textContent.toLowerCase().includes(searchText)) {
                            textoEncontrado = true;
                            break;
                        }
                    }
                    mostrar = textoEncontrado;
                }

                row.style.display = mostrar ? '' : 'none';
            }
            inicializarPaginacionCitasDashboard();
        }

        // 9. PAGINACIÓN PARA DASHBOARD
        let paginaActualCitasDashboard = 1;
        let elementosPorPaginaDashboard = 10;
        let citasTotalesDashboard = [];

        function inicializarPaginacionCitasDashboard() {
            const filasCitas = document.querySelectorAll('#citasHoyTable tr[data-estado]');
            citasTotalesDashboard = Array.from(filasCitas).filter(row => row.style.display !== 'none');

            mostrarPaginaCitasDashboard(1);
            generarControlesPaginacionDashboard();
        }

        function mostrarPaginaCitasDashboard(numeroPagina) {
            paginaActualCitasDashboard = numeroPagina;

            // Ocultar todas las citas
            citasTotalesDashboard.forEach(cita => {
                cita.style.display = 'none';
            });

            // Calcular índices de los elementos a mostrar
            const inicio = (numeroPagina - 1) * elementosPorPaginaDashboard;
            const fin = inicio + elementosPorPaginaDashboard;

            // Mostrar solo los elementos de la página actual
            for (let i = inicio; i < fin && i < citasTotalesDashboard.length; i++) {
                if (citasTotalesDashboard[i]) {
                    citasTotalesDashboard[i].style.display = '';
                }
            }

            generarControlesPaginacionDashboard();
        }

        function generarControlesPaginacionDashboard() {
            const totalPaginas = Math.ceil(citasTotalesDashboard.length / elementosPorPaginaDashboard);
            const contenedorPaginacion = document.getElementById('paginationCitas');

            contenedorPaginacion.innerHTML = '';

            // Botón Anterior
            const liAnterior = document.createElement('li');
            liAnterior.className = `page-item ${paginaActualCitasDashboard === 1 ? 'disabled' : ''}`;
            liAnterior.innerHTML = `
        <a class="page-link" href="#" aria-label="Anterior" onclick="cambiarPaginaCitasDashboard(${paginaActualCitasDashboard - 1}); return false;">
            <span aria-hidden="true">«</span>
        </a>
    `;
            contenedorPaginacion.appendChild(liAnterior);

            // Números de página
            const inicioPaginas = Math.max(1, paginaActualCitasDashboard - 2);
            const finPaginas = Math.min(totalPaginas, inicioPaginas + 4);

            for (let i = inicioPaginas; i <= finPaginas; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === paginaActualCitasDashboard ? 'active' : ''}`;
                li.innerHTML =
                    `<a class="page-link" href="#" onclick="cambiarPaginaCitasDashboard(${i}); return false;">${i}</a>`;
                contenedorPaginacion.appendChild(li);
            }

            // Botón Siguiente
            const liSiguiente = document.createElement('li');
            liSiguiente.className = `page-item ${paginaActualCitasDashboard === totalPaginas ? 'disabled' : ''}`;
            liSiguiente.innerHTML = `
        <a class="page-link" href="#" aria-label="Siguiente" onclick="cambiarPaginaCitasDashboard(${paginaActualCitasDashboard + 1}); return false;">
            <span aria-hidden="true">»</span>
        </a>
    `;
            contenedorPaginacion.appendChild(liSiguiente);
        }

        function cambiarPaginaCitasDashboard(numeroPagina) {
            const totalPaginas = Math.ceil(citasTotalesDashboard.length / elementosPorPaginaDashboard);

            if (numeroPagina < 1) numeroPagina = 1;
            if (numeroPagina > totalPaginas) numeroPagina = totalPaginas;

            mostrarPaginaCitasDashboard(numeroPagina);
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboardCitas();
        });
        // =============================================
        // FUNCIONES DE USUARIO Y VALIDACIÓN
        // =============================================


        // Función para mostrar el modal de usuario
        function mostrarModalUsuario(usuarioId = null) {
            const modal = document.getElementById('usuarioModal');
            const form = document.getElementById('usuarioForm');
            const title = document.getElementById('modalTitleText');
            const rolField = document.getElementById('rol');
            const emailField = document.getElementById('email');
            const passwordFields = document.getElementById('passwordFields');

            // Resetear el formulario y listeners
            form.reset();
            document.getElementById('usuario_id').value = '';

            // Remover cualquier listener previo de email
            const emailInput = document.getElementById('email');
            const newEmailInput = emailInput.cloneNode(true);
            emailInput.parentNode.replaceChild(newEmailInput, emailInput);

            if (usuarioId) {
                // Modo edición
                document.getElementById('modalTitleText').textContent = 'Editar Usuario';
                document.getElementById('email').readOnly = true;
                document.getElementById('rol').readOnly = true;
                document.getElementById('emailHelp').style.display = 'block';
                document.getElementById('rolHelp').style.display = 'block';
                passwordFields.style.display = 'none';
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;

                // Buscar el usuario en los datos cargados
                const usuario = allUsersData.find(u => u.id == usuarioId);

                if (usuario) {
                    document.getElementById('usuario_id').value = usuario.id;
                    document.getElementById('nombre').value = usuario.nombre;
                    document.getElementById('email').value = usuario.email;
                    document.getElementById('telefono').value = usuario.telefono || '';
                    document.getElementById('rol').value = usuario.rol;
                    document.getElementById('activo').value = usuario.activo ? '1' : '0';
                } else {
                    // Si no está en los datos cargados, hacer petición al servidor
                    fetch(`/admin/usuarios/${usuarioId}/edit`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Usuario no encontrado');
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('usuario_id').value = data.id;
                            document.getElementById('nombre').value = data.nombre;
                            document.getElementById('email').value = data.email;
                            document.getElementById('telefono').value = data.telefono || '';
                            document.getElementById('rol').value = data.rol;
                            document.getElementById('activo').value = data.activo ? '1' : '0';
                        })
                        .catch(error => {
                            console.error('Error al cargar usuario:', error);
                            Swal.fire('Error', 'No se pudo cargar la información del usuario', 'error');
                            closeModal('usuarioModal');
                        });
                }
            } else {
                // Modo creación
                document.getElementById('modalTitleText').textContent = 'Crear Nuevo Usuario';
                document.getElementById('email').readOnly = false;
                document.getElementById('rol').readOnly = false;
                document.getElementById('emailHelp').style.display = 'none';
                document.getElementById('email').removeAttribute('readonly');
                document.getElementById('rolHelp').style.display = 'none';
                passwordFields.style.display = 'block';
                document.getElementById('rol').value = 'cliente'; // Valor por defecto
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;

                // Validación en tiempo real para email (solo en creación)
                document.getElementById('email').addEventListener('blur', async function() {
                    const email = this.value;
                    if (!email) return;

                    try {
                        const usuarioId = document.getElementById('usuario_id').value;
                        const url =
                            `{{ route('admin.usuarios.check-email') }}?email=${encodeURIComponent(email)}${usuarioId ? '&exclude_id=' + usuarioId : ''}`;

                        const response = await fetch(url);

                        if (!response.ok) {
                            throw new Error('Error al verificar email');
                        }

                        const data = await response.json();

                        if (!data.available) {
                            this.setCustomValidity(data.message);
                            this.classList.add('border-red-500');
                            document.getElementById('email-error').textContent = data.message;
                            document.getElementById('email-error').classList.remove('hidden');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('border-red-500');
                            document.getElementById('email-error').classList.add('hidden');
                        }
                    } catch (error) {
                        console.error('Error al verificar email:', error);
                        // No mostrar error al usuario para no confundirlo
                    }
                });
            }

            // Resetear validaciones visuales
            document.querySelectorAll('.password-requirements li').forEach(li => {
                li.style.color = '#6b7280';
            });
            document.getElementById('passwordMatchMessage').textContent = '';

            // Resetear el botón de submit
            const submitBtn = document.querySelector('#usuarioForm button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Usuario';

            modal.style.display = 'flex';

            initPasswordValidations();

        }

        // Función para alternar visibilidad de contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            let eyeIcon;

            if (inputId === 'password') {
                eyeIcon = document.getElementById('passwordEye');
            } else {
                eyeIcon = document.getElementById('passwordConfirmationEye');
            }

            if (!input || !eyeIcon) {
                console.error(`Elemento no encontrado para inputId: ${inputId}`);
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function evaluatePasswordStrength(password) {
            let strength = 0;
            const strengthText = document.getElementById('passwordStrengthText');
            const strengthBar = document.getElementById('passwordStrengthBar');

            // Resetear completamente
            strengthBar.className = 'password-strength-meter-fill';
            strengthBar.style.backgroundColor = 'transparent';
            strengthBar.style.width = '0';
            strengthText.textContent = '';

            // Si está vacío, salir sin evaluar
            if (password.length === 0) {
                return false;
            }

            // Evaluar fortaleza
            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            // Aplicar estilos según fortaleza
            let color, width, text;
            switch (strength) {
                case 0:
                case 1:
                    color = '#ff5252';
                    width = '25%';
                    text = 'Débil';
                    break;
                case 2:
                    color = '#ffb74d';
                    width = '50%';
                    text = 'Moderada';
                    break;
                case 3:
                    color = '#4caf50';
                    width = '75%';
                    text = 'Fuerte';
                    break;
                case 4:
                case 5:
                    color = '#2e7d32';
                    width = '100%';
                    text = 'Muy fuerte';
                    break;
            }

            // Aplicar cambios visuales
            strengthBar.style.backgroundColor = color;
            strengthBar.style.width = width;
            strengthText.textContent = text;
            strengthText.style.color = color;

            return strength >= 3;
        }

        function validatePasswordStrength(password) {
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);

            // Actualizar lista de requisitos
            document.getElementById('req-length').style.color = hasMinLength ? '#10b981' : '#6b7280';
            document.getElementById('req-uppercase').style.color = hasUpperCase ? '#10b981' : '#6b7280';
            document.getElementById('req-lowercase').style.color = hasLowerCase ? '#10b981' : '#6b7280';
            document.getElementById('req-number').style.color = hasNumber ? '#10b981' : '#6b7280';

            // Evaluar fortaleza general
            evaluatePasswordStrength(password);

            return hasMinLength && hasUpperCase && hasLowerCase && hasNumber;
        }

        document.getElementById('password')?.addEventListener('input', function() {
            validatePasswordStrength(this.value);
            if (document.getElementById('password_confirmation').value.length > 0) {
                validatePasswordMatch();
            }
        });

        // Función para validar coincidencia de contraseñas
        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const messageElement = document.getElementById('passwordMatchMessage');

            // Limpiar clases previas
            messageElement.classList.remove('text-success', 'text-danger');

            messageElement.className = 'password-match-message';
            messageElement.textContent = '';

            if (confirmPassword.length === 0) {
                return false;
            }

            if (password === confirmPassword) {
                messageElement.textContent = 'Las contraseñas coinciden';
                messageElement.classList.add('valid');
                return true;
            } else {
                messageElement.textContent = 'Las contraseñas no coinciden';
                messageElement.classList.add('invalid');
                return false;
            }
        }


        // Inicializar validaciones del formulario
        function initPasswordValidations() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            if (passwordInput && confirmPasswordInput) {
                // Validar mientras se escribe en campo de contraseña
                passwordInput.addEventListener('input', function() {
                    validatePasswordStrength(this.value);
                    if (confirmPasswordInput.value.length > 0) {
                        validatePasswordMatch();
                    }
                });

                // Validar mientras se escribe en campo de confirmación
                confirmPasswordInput.addEventListener('input', function() {
                    if (passwordInput.value.length > 0) {
                        validatePasswordMatch();
                    } else {
                        document.getElementById('passwordMatchMessage').textContent = '';
                    }
                });
            } else {
                console.error('No se encontraron los inputs de contraseña');
            }
        }

        // Función para manejar envío de formulario
        async function handleUsuarioFormSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const usuarioId = form.querySelector('#usuario_id').value;

            // Resetear errores visuales
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
            });
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });

            // Deshabilitar el botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

            try {
                // Primero verificar el email nuevamente
                const email = form.email.value;
                const checkEmailUrl =
                    `{{ route('admin.usuarios.check-email') }}?email=${encodeURIComponent(email)}${usuarioId ? '&exclude_id=' + usuarioId : ''}`;
                const checkResponse = await fetch(checkEmailUrl);

                if (!checkResponse.ok) throw new Error('Error al verificar email');

                const checkData = await checkResponse.json();

                if (!checkData.available) {
                    throw new Error(checkData.message);
                }

                // Si el email está disponible, proceder con el envío
                const response = await fetch(usuarioId ? `/admin/usuarios/${usuarioId}` : '/admin/usuarios', {
                    method: usuarioId ? 'PUT' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre: form.nombre.value.trim(),
                        email: email,
                        telefono: form.telefono.value.trim() || null,
                        activo: form.activo.value === '1',
                        rol: form.rol.value,
                        password: form.password?.value,
                        password_confirmation: form.password_confirmation?.value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al procesar la solicitud');
                }

                // Éxito - mostrar mensaje y recargar
                await Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                await fetchAllUsers();
                closeModal('usuarioModal');

            } catch (error) {
                // Mostrar error específico para email
                if (error.message.includes('correo electrónico')) {
                    form.email.classList.add('border-red-500');
                    document.getElementById('email-error').textContent = error.message;
                    document.getElementById('email-error').classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message,
                        footer: usuarioId ? `ID: ${usuarioId}` : ''
                    });
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Usuario';
            }
        }

        // =============================================
// PLUGIN PARA MOSTRAR MENSAJE CUANDO NO HAY DATOS
// =============================================
Chart.register({
    id: 'noDataPlugin',
    afterDraw: function(chart) {
        if (chart.data.datasets.length === 0 || 
            (chart.data.datasets[0].data.length === 0) || 
            (chart.data.datasets[0].data.every(item => item === 0))) {
            
            let ctx = chart.ctx;
            let width = chart.width;
            let height = chart.height;
            
            chart.clear();
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = '16px Arial';
            ctx.fillStyle = '#999';
            ctx.fillText('No hay datos disponibles', width / 2, height / 2);
            ctx.restore();
        }
    }
});

        // =============================================
        // FUNCIONES DE INICIALIZACIÓN
        // =============================================

        function inicializarGraficoUsuarios(data) {
            if (!document.getElementById('usuariosChart')) {
                console.error('No se encontró el elemento usuariosChart');
                return;
            }

            const ctx = document.getElementById('usuariosChart').getContext('2d');
            usuariosChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Clientes', 'Empleados', 'Administradores'],
                    datasets: [{
                        data: [data.clientes, data.empleados, data.administradores],
                        backgroundColor: [
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: getCommonChartOptions('bottom')
            });
        }
        console.log(document.getElementById('usuariosChart')); // Debería mostrar el elemento canvas

        function inicializarGraficoIngresos(data) {
            if (!document.getElementById('ingresosChart')) {
                console.error('No se encontró el elemento ingresosChart');
                return;
            }

            const ctx = document.getElementById('ingresosChart').getContext('2d');
            ingresosChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ingresos ' + new Date().getFullYear(),
                        data: data,
                        backgroundColor: 'rgba(39, 174, 96, 0.2)',
                        borderColor: 'rgba(39, 174, 96, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    ...getCommonChartOptions('top'),
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '$' + value
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: context => '$' + context.raw.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        function inicializarGraficoServicios(data) {
            if (!document.getElementById('serviciosChart')) {
                console.error('No se encontró el elemento serviciosChart');
                return;
            }

            const ctx = document.getElementById('serviciosChart').getContext('2d');

            // Preparar colores para los servicios
            const colores = [
                'rgba(39, 174, 96, 0.7)',
                'rgba(52, 152, 219, 0.7)',
                'rgba(243, 156, 18, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(231, 76, 60, 0.7)'
            ];

            serviciosChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: colores,
                        borderWidth: 1
                    }]
                },
                options: getCommonChartOptions('right')
            });
        }

        function inicializarGraficoCitas(data) {
            if (!document.getElementById('citasChart')) {
                console.error('No se encontró el elemento citasChart');
                return;
            }

            const ctx = document.getElementById('citasChart').getContext('2d');
            citasChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                            label: 'Citas Completadas',
                            data: data.completadas,
                            backgroundColor: 'rgba(46, 125, 50, 0.7)'
                        },
                        {
                            label: 'Citas Canceladas',
                            data: data.canceladas,
                            backgroundColor: 'rgba(198, 40, 40, 0.7)'
                        }
                    ]
                },
                options: {
                    ...getCommonChartOptions('top'),
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
        }

        function getCommonChartOptions(legendPosition) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: legendPosition
                    }
                }
            };
        }

        // =============================================
        // FUNCIONES DE ACTUALIZACIÓN DE DATOS
        // =============================================

        // En la función actualizarDatosDashboard
        async function actualizarDatosDashboard() {
            try {
                const response = await fetch('{{ route('admin.dashboard.data') }}');
                if (!response.ok) throw new Error('Error en la respuesta del servidor');

                const data = await response.json();

                if (!data.stats || !data.rolesDistribucion) {
                    throw new Error('Formato de datos incorrecto');
                }

                actualizarEstadisticas(data.stats);

                // Inicializar gráficos con datos reales
                if (document.getElementById('ingresosChart')) {
                    inicializarGraficoIngresos(data.stats.ingresos_por_mes || Array(12).fill(0));
                }

                if (document.getElementById('citasChart')) {
  
    const citasData = data.stats.citas_por_mes || {};
    inicializarGraficoCitas({
        completadas: citasData.completadas || Array(12).fill(0),
        canceladas: citasData.canceladas || Array(12).fill(0)
    });
}

                if (document.getElementById('serviciosChart')) {
                    inicializarGraficoServicios(data.stats.servicios_populares_data || {});
                }

                if (document.getElementById('usuariosChart')) {
                    inicializarGraficoUsuarios(data.rolesDistribucion);
                }

                return true;
            } catch (error) {
                console.error('Error al actualizar datos:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar datos',
                    text: error.message
                });
                return false;
            }
        }

        function actualizarEstadisticas(stats) {
            const welcomeStats = document.querySelectorAll('.welcome-stat .number');
            if (welcomeStats.length >= 3) {
                welcomeStats[0].textContent = stats.usuarios_totales ?? 0;
                welcomeStats[1].textContent = stats.citas_hoy ?? 0;
                welcomeStats[2].textContent = `$${(stats.ingresos_hoy ?? 0).toFixed(2)}`;
            } else {
                console.warn('No se encontraron los elementos de estadísticas principales');
            }

            const cardCounters = document.querySelectorAll('.card-body [style*="grid-template-columns"] div');
            if (cardCounters.length >= 2) {
                const numberElements = cardCounters[0].querySelectorAll('div:first-child');
                if (numberElements.length > 0) {
                    numberElements[0].textContent = stats.usuarios_totales ?? 0;
                }
                if (numberElements.length > 1) {
                    numberElements[1].textContent = stats.nuevos_clientes_mes ?? 0;
                }
            }
        }

        function actualizarGraficoUsuarios(data) {
            if (usuariosChart) {
                usuariosChart.data.datasets[0].data = [
                    data.clientes,
                    data.empleados,
                    data.administradores
                ];
                usuariosChart.update();
            } else {
                inicializarGraficoUsuarios(data);
            }
        }

        // =============================================
        // FUNCIONES DE INTERFAZ
        // =============================================

        // Funciones para pestañas
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName('tab-content');
            const tabButtons = document.getElementsByClassName('tab-button');

            Array.from(tabContents).forEach(content => content.classList.remove('active'));
            Array.from(tabButtons).forEach(button => button.classList.remove('active'));

            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        // Funciones para modales
        function mostrarModal(modalId, title = '', content = '') {
            if (title) document.getElementById(`${modalId}Title`).innerHTML = title;
            if (content) document.getElementById(`${modalId}Content`).innerHTML = content;
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // =============================================
        // FUNCIONES ESPECÍFICAS
        // =============================================

        // Gestión de citas
        function verDetalleCita(citaId) {
            const detalleContent = `
        <h2 style="color: var(--primary); margin-bottom: 20px;">
            <i class="fas fa-calendar-check"></i> Detalle de Cita #${citaId}
        </h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                    <i class="fas fa-user"></i> Información del Cliente
                </h3>
                <p><strong>Nombre:</strong> ${citaId.nombre || 'Juan Pérez'}</p>
                <p><strong>Teléfono:</strong> ${citaId.telefono || '5555-1234'}</p>
                <p><strong>Email:</strong> ${citaId.email || 'juan@example.com'}</p>
            </div>
            <div>
                <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                    <i class="fas fa-car"></i> Información del Vehículo
                </h3>
                <p><strong>Marca/Modelo:</strong> ${citaId.vehiculo || 'Toyota Corolla'}</p>
                <p><strong>Placa:</strong> ${citaId.placa || 'P123456'}</p>
            </div>
        </div>
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: var(--primary);">
                <i class="fas fa-concierge-bell"></i> Servicios
            </h3>
            <ul>
                ${citaId.servicios ? citaId.servicios.map(s => `<li>${s.nombre} - $${s.precio}</li>`).join('') : '<li>Lavado Completo - $25.00</li>'}
            </ul>
        </div>
    `;
            mostrarModal('detalleCitaModal', '<i class="fas fa-calendar-check"></i> Detalle de Cita', detalleContent);
        }

        function editarCita(citaId) {
            const formContent = `
        <form id="editarCitaForm">
            <div class="form-group">
                <label for="editFecha">Fecha:</label>
                <input type="date" id="editFecha" class="form-control" value="${new Date().toISOString().split('T')[0]}" required>
            </div>
            <div class="form-group">
                <label for="editHora">Hora:</label>
                <input type="time" id="editHora" class="form-control" value="10:00" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    `;
            closeModal('detalleCitaModal');
            mostrarModal('editarCitaModal', '<i class="fas fa-edit"></i> Editar Cita', formContent);
        }
        /*
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
                    Toast.fire({
                        icon: 'success',
                        title: 'Cita cancelada correctamente'
                    });
                    actualizarDatosDashboard();
                }
            });
        }
        */
        // Gestión de servicios
        function nuevoServicio() {
            const formContent = `
                <form id="servicioForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="servicioNombre" class="form-label">
                                    <i class="fas fa-tag"></i> Nombre del Servicio <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="servicioNombre" name="nombre" class="form-control" 
                                       placeholder="Ej: Lavado Completo Premium" required maxlength="255">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="servicioPrecio" class="form-label">
                                    <i class="fas fa-dollar-sign"></i> Precio <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="servicioPrecio" name="precio" class="form-control" 
                                           step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="servicioDuracion" class="form-label">
                                    <i class="fas fa-clock"></i> Duración (minutos) <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="servicioDuracion" name="duracion_min" class="form-control" 
                                       min="1" max="480" placeholder="60" required>
                                <small class="text-muted">Máximo 8 horas (480 minutos)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="servicioDescripcion" class="form-label">
                                    <i class="fas fa-align-left"></i> Descripción <span class="text-danger">*</span>
                                </label>
                                <textarea id="servicioDescripcion" name="descripcion" class="form-control" 
                                          rows="3" placeholder="Descripción detallada del servicio..." 
                                          required maxlength="1000"></textarea>
                                <small class="text-muted">Máximo 1000 caracteres</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="servicioCategoria" class="form-label">
                                    <i class="fas fa-car"></i> Tipo de Vehículo <span class="text-danger">*</span>
                                </label>
                                <select id="servicioCategoria" name="categoria" class="form-control" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="sedan">🚗 Sedán</option>
                                    <option value="pickup">🚙 Pickup/SUV</option>
                                    <option value="moto">🏍️ Motocicleta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="servicioEstado" class="form-label">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <select id="servicioEstado" name="activo" class="form-control" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModal('servicioModal')">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Servicio
                        </button>
                    </div>
                </form>
            `;

            mostrarModal('servicioModal', '<i class="fas fa-plus"></i> Nuevo Servicio', formContent);

            // Event listener para el formulario
            document.getElementById('servicioForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                await crearServicio();
            });
        }

        function editarServicio(servicioId) {
            // Primero obtenemos los datos del servicio
            fetch(`/admin/servicios/${servicioId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const servicio = data.servicio;
                        const formContent = `
                            <form id="editarServicioForm">
                                <input type="hidden" id="servicioId" value="${servicioId}">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="editServicioNombre" class="form-label">
                                                <i class="fas fa-tag"></i> Nombre del Servicio <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="editServicioNombre" name="nombre" class="form-control" 
                                                   value="${servicio.nombre}" required maxlength="255">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="editServicioPrecio" class="form-label">
                                                <i class="fas fa-dollar-sign"></i> Precio <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" id="editServicioPrecio" name="precio" class="form-control" 
                                                       step="0.01" min="0" value="${servicio.precio}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="editServicioDuracion" class="form-label">
                                                <i class="fas fa-clock"></i> Duración (minutos) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" id="editServicioDuracion" name="duracion_min" class="form-control" 
                                                   min="1" max="480" value="${servicio.duracion_min}" required>
                                            <small class="text-muted">Máximo 8 horas (480 minutos)</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="editServicioDescripcion" class="form-label">
                                                <i class="fas fa-align-left"></i> Descripción <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="editServicioDescripcion" name="descripcion" class="form-control" 
                                                      rows="3" required maxlength="1000">${servicio.descripcion}</textarea>
                                            <small class="text-muted">Máximo 1000 caracteres</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="editServicioCategoria" class="form-label">
                                                <i class="fas fa-car"></i> Tipo de Vehículo <span class="text-danger">*</span>
                                            </label>
                                            <select id="editServicioCategoria" name="categoria" class="form-control" required>
                                                <option value="">Seleccionar tipo</option>
                                                <option value="sedan" ${servicio.categoria === 'sedan' ? 'selected' : ''}>🚗 Sedán</option>
                                                <option value="pickup" ${servicio.categoria === 'pickup' ? 'selected' : ''}>🚙 Pickup/SUV</option>
                                                <option value="moto" ${servicio.categoria === 'moto' ? 'selected' : ''}>🏍️ Motocicleta</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="editServicioEstado" class="form-label">
                                                <i class="fas fa-toggle-on"></i> Estado
                                            </label>
                                            <select id="editServicioEstado" name="activo" class="form-control" required>
                                                <option value="1" ${servicio.activo ? 'selected' : ''}>Activo</option>
                                                <option value="0" ${!servicio.activo ? 'selected' : ''}>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-danger" onclick="confirmarEliminarServicio(${servicioId})">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-secondary" onclick="cerrarModal('servicioModal')">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Actualizar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        `;

                        mostrarModal('servicioModal', '<i class="fas fa-edit"></i> Editar Servicio', formContent);

                        // Event listener para el formulario
                        document.getElementById('editarServicioForm').addEventListener('submit', async function(e) {
                            e.preventDefault();
                            await actualizarServicio(servicioId);
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo obtener el servicio', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudieron cargar los datos del servicio. Error: ' + error.message,
                        'error');
                });
        }

        // Función para crear servicio
        async function crearServicio() {
            const form = document.getElementById('servicioForm');
            const formData = new FormData(form);

            // Convertir FormData a objeto para facilitar el manejo
            const data = {};
            formData.forEach((value, key) => {
                if (key === 'activo' || key === 'duracion_min') {
                    data[key] = parseInt(value);
                } else if (key === 'precio') {
                    data[key] = parseFloat(value);
                } else {
                    data[key] = value;
                }
            });

            // Convertir activo a boolean
            data.activo = data.activo === 1;

            try {
                const response = await fetch('/admin/servicios', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    cerrarModal('servicioModal');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Verificar si venimos del modal principal
                        const parentModal = document.getElementById('servicioModal').getAttribute(
                            'data-parent-modal');
                        if (parentModal === 'modalVerTodosServicios') {
                            // Recargar solo la lista de servicios en el modal principal
                            cargarTodosLosServicios();
                            // Limpiar el atributo
                            document.getElementById('servicioModal').removeAttribute('data-parent-modal');
                        } else {
                            // Recargar toda la página si no viene del modal principal
                            location.reload();
                        }
                    });
                } else {
                    if (result.errors) {
                        let errorMessage = '';
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => errorMessage += error + '\n');
                        });
                        Swal.fire('Error de validación', errorMessage, 'error');
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión. Intenta nuevamente.', 'error');
            }
        }

        // Función para actualizar servicio
        async function actualizarServicio(servicioId) {
            const form = document.getElementById('editarServicioForm');
            const formData = new FormData(form);

            // Convertir FormData a objeto
            const data = {};
            formData.forEach((value, key) => {
                if (key === 'activo' || key === 'duracion_min') {
                    data[key] = parseInt(value);
                } else if (key === 'precio') {
                    data[key] = parseFloat(value);
                } else {
                    data[key] = value;
                }
            });

            // Convertir activo a boolean
            data.activo = data.activo === 1;

            try {
                const response = await fetch(`/admin/servicios/${servicioId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    cerrarModal('servicioModal');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Verificar si venimos del modal principal
                        const parentModal = document.getElementById('servicioModal').getAttribute(
                            'data-parent-modal');
                        if (parentModal === 'modalVerTodosServicios') {
                            // Recargar solo la lista de servicios en el modal principal
                            cargarTodosLosServicios();
                            // Limpiar el atributo
                            document.getElementById('servicioModal').removeAttribute('data-parent-modal');
                        } else {
                            // Recargar toda la página si no viene del modal principal
                            location.reload();
                        }
                    });
                } else {
                    if (result.errors) {
                        let errorMessage = '';
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => errorMessage += error + '\n');
                        });
                        Swal.fire('Error de validación', errorMessage, 'error');
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión. Intenta nuevamente.', 'error');
            }
        }

        // Función para confirmar eliminación de servicio
        function confirmarEliminarServicio(servicioId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer. El servicio será eliminado permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarServicio(servicioId);
                }
            });
        }

        // Función para eliminar servicio
        async function eliminarServicio(servicioId) {
            try {
                const response = await fetch(`/admin/servicios/${servicioId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    cerrarModal('servicioModal');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Verificar si venimos del modal principal o recargar la lista directamente
                        if (typeof cargarTodosLosServicios === 'function') {
                            cargarTodosLosServicios();
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Error de conexión. Intenta nuevamente.', 'error');
            }
        }

        // Gestión de horarios
        function mostrarModalHorario() {
            const formContent = `
        <form id="horarioForm">
            <div class="form-group">
                <label for="horarioDia">Día:</label>
                <select id="horarioDia" class="form-control" required>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <!-- Más días -->
                </select>
            </div>
            <div class="form-group">
                <label for="horarioInicio">Hora Inicio:</label>
                <input type="time" id="horarioInicio" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Horario</button>
        </form>
    `;
            mostrarModal('horarioModal', '<i class="fas fa-plus"></i> Agregar Horario', formContent);
        }

        function editarHorario(horarioId) {
            const formContent = `
        <form id="editarHorarioForm">
            <input type="hidden" id="horarioId" value="${horarioId}">
            <div class="form-group">
                <label for="editHorarioDia">Día:</label>
                <select id="editHorarioDia" class="form-control" required>
                    <option value="Lunes" selected>Lunes</option>
                    <option value="Martes">Martes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editHorarioInicio">Hora Inicio:</label>
                <input type="time" id="editHorarioInicio" class="form-control" value="08:00" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Horario</button>
        </form>
    `;
            mostrarModal('horarioModal', '<i class="fas fa-edit"></i> Editar Horario', formContent);
        }

        // Gestión de perfil
        function editarPerfil() {
            const formContent = `
        <form id="perfilForm">
            <div class="form-group">
                <label for="perfilNombre">Nombre:</label>
                <input type="text" id="perfilNombre" class="form-control" value="{{ Auth::user()->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="perfilTelefono">Teléfono:</label>
                <input type="tel" id="perfilTelefono" class="form-control" value="{{ Auth::user()->telefono ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    `;
            mostrarModal('perfilModal', '<i class="fas fa-user-edit"></i> Editar Perfil', formContent);
        }

        // =============================================
        // EVENT LISTENERS
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            // 1. PRIMERO VERIFICAR QUE LOS CONTENEDORES DE GRÁFICOS EXISTAN
            if (!document.getElementById('usuariosChart')) {
                console.error('No se encontró el elemento usuariosChart');
            }

            if (!document.getElementById('ingresosChart')) {
                console.error('No se encontró el elemento ingresosChart');
            }

            if (!document.getElementById('citasChart')) {
                console.error('No se encontró el elemento citasChart');
            }

            if (!document.getElementById('serviciosChart')) {
                console.error('No se encontró el elemento serviciosChart');
            }

            // 2. CARGAR DATOS - LOS GRÁFICOS SE INICIALIZARÁN DENTRO DE actualizarDatosDashboard
            actualizarDatosDashboard();

            // Inicializar validaciones del formulario de usuario
            if (!document.getElementById('password') || !document.getElementById('password_confirmation')) {
                console.error('Elementos de contraseña no encontrados');
            } else {
                initPasswordValidations();
            }

            cargarDiasNoLaborables();

            // Asignar el evento al botón de crear usuario
            document.querySelector('.btn-primary[onclick="mostrarModalUsuario()"]').addEventListener('click',
                mostrarModalUsuario);

            // Configurar intervalo para actualizaciones (5 segundos)
            setInterval(actualizarDatosDashboard, 5000);

            // Actualizar cuando la pestaña vuelve a estar activa
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) actualizarDatosDashboard();
            });

            // Listeners para botones de pestañas
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    openTab(e, this.getAttribute('data-tab'));
                });
            });

            // Listener para cerrar modales al hacer clic fuera
            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    ['detalleCita', 'editarCita', 'servicio', 'horario', 'perfil', 'usuario',
                        'detalleGasto'
                    ].forEach(
                        modal => {
                            closeModal(`${modal}Modal`);
                        });
                }
            });

            // Listener para botones de cerrar modal
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    closeModal(this.closest('.modal').id);
                });
            });

            // Inicializar el formulario de usuario
            const usuarioForm = document.getElementById('usuarioForm');
            if (usuarioForm) {
                usuarioForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validar contraseña si estamos en modo creación
                    if (document.getElementById('passwordFields').style.display !== 'none') {
                        const password = document.getElementById('password').value;
                        const isPasswordStrong = validatePasswordStrength(password);
                        const doPasswordsMatch = validatePasswordMatch();

                        if (!isPasswordStrong || !doPasswordsMatch) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error en la contraseña',
                                text: 'Por favor, asegúrate de que la contraseña cumpla con todos los requisitos y que ambas contraseñas coincidan.'
                            });
                            return;
                        }
                    }

                    // Si todo está bien, enviar el formulario
                    handleUsuarioFormSubmit(e);
                });
            }
        });


        // Perfil de usuario
        document.getElementById('perfilForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                nombre: document.getElementById('perfil_nombre').value,
                telefono: document.getElementById('perfil_telefono').value,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route('perfil.update-ajax') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Perfil actualizado correctamente'
                    });

                    // ACTUALIZACIÓN DEL SIDEBAR
                    // 1. Actualizar el nombre en el perfil
                    const profileName = document.querySelector('.profile-name');
                    if (profileName) profileName.textContent = formData.nombre;

                    // 2. Actualizar el teléfono en el perfil
                    const profilePhone = document.querySelector('.profile-info-item:nth-child(2) span');
                    if (profilePhone) profilePhone.textContent = formData.telefono || 'No especificado';

                    // Cerrar el modal
                    closeModal('perfilModal');

                } else {
                    throw new Error(data.message || 'Error al actualizar el perfil');
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
            }
        });

        // Formulario de usuario
        document.getElementById('usuarioForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validar contraseña si estamos en modo creación
            if (document.getElementById('passwordFields').style.display !== 'none') {
                const password = document.getElementById('password').value;
                const isPasswordStrong = validatePasswordStrength(password);
                const doPasswordsMatch = validatePasswordMatch();

                if (!isPasswordStrong || !doPasswordsMatch) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la contraseña',
                        text: 'Por favor, asegúrate de que la contraseña cumpla con todos los requisitos y que ambas contraseñas coincidan.'
                    });
                    return;
                }
            }

            const formData = {
                nombre: document.getElementById('nombre').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                rol: document.getElementById('rol').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                activo: document.getElementById('activo').value,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route('admin.usuarios.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
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
                    await actualizarDatosDashboard();
                } else {
                    let errorMessage = 'Error al crear el usuario';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).join('\n');
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
            }
        });

        // Formulario de servicio
        document.getElementById('servicioForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                nombre: document.getElementById('servicioNombre').value,
                precio: document.getElementById('servicioPrecio').value
            };

            try {
                const response = await fetch('{{ route('admin.servicios.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Servicio creado correctamente'
                    });
                    closeModal('servicioModal');
                    await actualizarDatosDashboard();
                } else {
                    throw new Error(data.message || 'Error al crear el servicio');
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
            }
        });

        // Event listener para el botón de guardar gasto
        document.getElementById('btnGuardarGasto')?.addEventListener('click', async function(e) {
            e.preventDefault();

            const form = document.getElementById('gastoForm');
            const gastoId = form.getAttribute('data-id');
            const isEdit = form.getAttribute('data-mode') === 'edit';

            const formData = {
                usuario_id: {{ Auth::id() }}, // Usuario actual autenticado
                tipo: document.getElementById('gastoTipo').value,
                detalle: document.getElementById('gastoDetalle').value,
                monto: document.getElementById('gastoMonto').value,
                fecha_gasto: document.getElementById('gastoFecha').value
            };

            // Para Laravel, agregar _method para PUT requests
            if (isEdit) {
                formData._method = 'PUT';
            }

            try {
                const url = isEdit ? `{{ url('/admin/gastos') }}/${gastoId}` :
                    '{{ route('admin.gastos.store') }}';
                const method = 'POST'; // Siempre POST, Laravel maneja _method internamente

                console.log('Enviando petición:', {
                    url: url,
                    method: method,
                    isEdit: isEdit,
                    gastoId: gastoId,
                    formData: formData
                });

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });

                console.log('Respuesta recibida:', {
                    status: response.status,
                    statusText: response.statusText,
                    url: response.url,
                    headers: response.headers.get('content-type')
                });

                // Verificar si la respuesta es JSON antes de parsear
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const textResponse = await response.text();
                    console.error('Respuesta no es JSON:', textResponse);
                    throw new Error('El servidor no devolvió una respuesta JSON válida');
                }

                const data = await response.json();
                console.log('Datos de respuesta:', data);

                if (response.ok) {
                    Toast.fire({
                        icon: 'success',
                        title: isEdit ? 'Gasto actualizado correctamente' :
                            'Gasto registrado correctamente'
                    });
                    // Cerrar modal con Bootstrap
                    if (modalInstanceGasto) {
                        modalInstanceGasto.hide();
                    }

                    // Limpiar atributos del formulario
                    form.removeAttribute('data-id');
                    form.removeAttribute('data-mode');

                    // Restaurar título del modal
                    document.getElementById('gastoModalTitle').innerHTML =
                        '<i class="fas fa-money-bill-wave me-2"></i> Registrar Gasto';

                    // Verificar si hay un modal principal abierto
                    const modalPrincipal = document.getElementById('modalVerTodosGastos');
                    const modalPrincipalAbierto = modalPrincipal && modalPrincipal.classList.contains('show');

                    if (modalPrincipalAbierto) {
                        // Si hay modal principal abierto, recargar solo sus datos
                        console.log('🔄 Recargando datos del modal principal de gastos...');
                        setTimeout(() => {
                            if (typeof cargarTodosGastos === 'function') {
                                cargarTodosGastos();
                            }
                        }, 1000);
                    } else {
                        // Si no hay modal principal, recargar toda la página
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                } else {
                    // Manejar errores de validación específicos
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        throw new Error(errorMessages.join('\n'));
                    }
                    throw new Error(data.message || `Error al ${isEdit ? 'actualizar' : 'registrar'} el gasto`);
                }
            } catch (error) {
                console.error('Error al procesar gasto:', error);
                Toast.fire({
                    icon: 'error',
                    title: `Error al ${isEdit ? 'actualizar' : 'registrar'} el gasto`,
                    text: error.message
                });
            }
        });

        // Asignación de eventos a botones dinámicos
        document.addEventListener('click', function(e) {
            // Botones de ver detalle de cita (solo en tabla de citas)
            if (e.target.closest('#citasHoyTable .btn-view')) {
                const row = e.target.closest('tr');
                const citaId = row ? row.getAttribute('data-id') : null;
                if (citaId) verDetalleCita(citaId);
            }

            // Botones de editar cita (solo en tabla de citas)
            if (e.target.closest('#citasHoyTable .btn-edit')) {
                const row = e.target.closest('tr');
                const citaId = row ? row.getAttribute('data-id') : null;
                if (citaId) editarCita(citaId);
            }

            // Botones de cancelar cita (solo dentro de la tabla de citas)
            if (e.target.closest('#citasHoyTable .btn-delete')) {
                const row = e.target.closest('tr');
                const citaId = row ? row.getAttribute('data-id') : null;
                if (citaId) cancelarCita(citaId);
            }

            // Botones de editar servicio
            if (e.target.closest('.btn-edit-servicio')) {
                const servicioId = e.target.closest('.service-history-item').getAttribute('data-id');
                editarServicio(servicioId);
            }
        });

        // Botón para mostrar modal de nuevo servicio
        document.getElementById('btnNuevoServicio')?.addEventListener('click', nuevoServicio);

        // Botón para mostrar modal de horario
        document.getElementById('btnAgregarHorario')?.addEventListener('click', mostrarModalHorario);

        // Botón para editar perfil
        document.getElementById('btnEditarPerfil')?.addEventListener('click', editarPerfil);


        function eliminarDia(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este día no laborable?')) {
                // Crear formulario dinámicamente para enviar DELETE
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/dias-no-laborables/${id}`;

                // Token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                // Método DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Enviar formulario
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <!-- Estilos específicos para el modal -->
    <style>
        .modal-dias-no-laborables .modal-content {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-dias-no-laborables .modal-header {
            background: linear-gradient(135deg, #3498db, #27ae60);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-dias-no-laborables .modal-body {
            padding: 2rem;
            background: #f8f9fa;
        }

        .modal-dias-no-laborables .modal-footer {
            background: #f8f9fa;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-dias-no-laborables .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .modal-dias-no-laborables .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .modal-dias-no-laborables .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .modal-dias-no-laborables .btn-primary {
            background: linear-gradient(135deg, #3498db, #27ae60);
            border: none;
        }

        .modal-dias-no-laborables .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        /* Estilos para modal-horarios (igual al de días no laborables) */
        .modal-horarios .modal-content {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-horarios .modal-header {
            background: linear-gradient(135deg, #27ae60, #00695c);
            color: white;
            border: none;
            padding: 1.25rem 1.75rem;
        }

        .modal-horarios .modal-body,
        .modal-horarios .modal-footer {
            background: #f8f9fa;
            border: none;
        }

        .modal-horarios .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.65rem 0.9rem;
            transition: all 0.2s ease;
        }

        .modal-horarios .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.12);
        }

        .modal-horarios .btn-primary {
            background: linear-gradient(135deg, #27ae60, #00695c);
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
        }

        .modal-dias-no-laborables .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .modal-dias-no-laborables .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .modal-dias-no-laborables .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
        }

        .modal-dias-no-laborables .alert-info {
            background: rgba(52, 152, 219, 0.1);
            border-left-color: #3498db;
            color: #2c3e50;
        }

        .modal-dias-no-laborables .alert-warning {
            background: rgba(243, 156, 18, 0.1);
            border-left-color: #f39c12;
            color: #2c3e50;
        }

        .modal-dias-no-laborables .invalid-feedback {
            display: block;
            font-size: 0.875rem;
            color: #e74c3c;
        }

        .modal-dias-no-laborables .is-invalid {
            border-color: #e74c3c;
        }

        /* Estilos específicos para el modal de ver todos */
        .modal-dias-no-laborables .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-dias-no-laborables .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-dias-no-laborables .card-body {
            padding: 1.5rem;
        }

        .modal-dias-no-laborables .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .modal-dias-no-laborables .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px 0 0 8px;
        }

        .modal-dias-no-laborables .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .modal-dias-no-laborables .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Estilos específicos para el modal de gastos */
        .modal-gastos .modal-content {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-gastos .modal-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-gastos .modal-body {
            padding: 2rem;
            background: #f8f9fa;
        }

        .modal-gastos .modal-footer {
            background: #f8f9fa;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-gastos .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .modal-gastos .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .modal-gastos .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .modal-gastos .btn-primary {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
        }

        .modal-gastos .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .modal-gastos .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .modal-gastos .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .modal-gastos .input-group-text {
            background: #e9ecef;
            border: 2px solid #e9ecef;
            border-radius: 8px 0 0 8px;
            font-weight: 600;
            color: #28a745;
        }

        .modal-gastos .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
        }

        .modal-gastos .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-left-color: #28a745;
            color: #2c3e50;
        }

        .modal-gastos .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-left-color: #dc3545;
            color: #2c3e50;
        }
    </style>

    <!-- Modal para agregar día no laborable -->
    <div class="modal fade modal-dias-no-laborables" id="modalAgregarDiaDashboard" tabindex="-1"
        aria-labelledby="modalAgregarDiaDashboardLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarDiaDashboardLabel">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Agregar Día No Laborable
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close" id="btnCerrarModalDashboard"></button>
                </div>
                <div class="modal-body">
                    <!-- Alerta para mensajes -->
                    <div id="alertaModalDashboard" class="alert" style="display: none;"></div>

                    <form id="formAgregarDiaDashboard">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fechaModalDashboard" class="form-label">
                                        <i class="fas fa-calendar text-primary"></i>
                                        Fecha <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="fechaModalDashboard"
                                        name="fecha" min="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        Solo se pueden agregar fechas futuras o la fecha actual
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="motivoModalDashboard" class="form-label">
                                        <i class="fas fa-tag text-primary"></i>
                                        Motivo <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="motivoModalDashboard" name="motivo"
                                        onchange="toggleMotivoPersonalizadoModalDashboard()" required>
                                        <option value="">Seleccione un motivo</option>
                                        <option value="feriado">Feriado Nacional</option>
                                        <option value="mantenimiento">Mantenimiento de Instalaciones</option>
                                        <option value="vacaciones">Vacaciones del Personal</option>
                                        <option value="emergencia">Emergencia</option>
                                        <option value="evento_especial">Evento Especial</option>
                                        <option value="otro">Otro (personalizado)</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Campo de motivo personalizado -->
                        <div class="row" id="motivoPersonalizadoContainer" style="display: none;">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="motivoPersonalizadoModalDashboard" class="form-label">
                                        <i class="fas fa-edit text-primary"></i>
                                        Especifique el motivo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="motivoPersonalizadoModalDashboard"
                                        name="motivo_personalizado"
                                        placeholder="Describa el motivo específico del día no laborable..."
                                        maxlength="255">
                                    <div class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        Máximo 255 caracteres
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Información importante -->
                        <div class="alert alert-info" style="margin-top: 1.5rem;">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle"></i>
                                Información importante
                            </h6>
                            <ul class="mb-0">
                                <li>Los días no laborables afectarán automáticamente la disponibilidad de citas</li>
                                <li>Los clientes no podrán agendar nuevas citas en estas fechas</li>
                                <li>Se notificará a los administradores sobre este cambio</li>
                                <li>Las citas existentes para esta fecha serán marcadas para revisión</li>
                            </ul>
                        </div>

                        <!-- Vista previa del día seleccionado -->
                        <div id="previewModalDashboard" style="display: none; margin-top: 1.5rem;">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="fas fa-eye"></i>
                                    Vista previa
                                </h6>
                                <div id="previewContentModalDashboard"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModalDashboard"
                        data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="btnGuardarDiaDashboard">
                        <i class="fas fa-save me-1"></i>
                        Guardar Día No Laborable
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver todos los días no laborables -->
    <div class="modal fade modal-dias-no-laborables" id="modalVerTodosDiasNoLaborables" tabindex="-1"
        aria-labelledby="modalVerTodosDiasNoLaborablesLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerTodosDiasNoLaborablesLabel">
                        <i class="fas fa-calendar-times me-2"></i>
                        Todos los Días No Laborables
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="buscarDiaNoLaborable"
                                    placeholder="Buscar por fecha (25/12, diciembre, lunes) o motivo...">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control" id="filtrarPorMotivo">
                                <option value="">Todos los motivos</option>
                                <option value="feriado">Feriado Nacional</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="vacaciones">Vacaciones</option>
                                <option value="emergencia">Emergencia</option>
                                <option value="evento_especial">Evento Especial</option>
                                <option value="otro">Motivos Personalizados</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                onclick="limpiarFiltrosDiasNoLaborables()" title="Limpiar filtros">
                                <i class="fas fa-eraser"></i>
                            </button>
                            <div id="contadorResultados" class="text-center text-muted small mt-1">
                                <!-- Contador dinámico -->
                            </div>
                        </div>
                    </div>

                    <div id="loadingDiasNoLaborables" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando días no laborables...</p>
                    </div>

                    <div id="contenidoDiasNoLaborables">
                        <!-- Contenido dinámico se carga aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="abrirModalDiaDashboard()"
                        data-bs-dismiss="modal">
                        <i class="fas fa-plus me-1"></i>
                        Agregar Nuevo Día
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver todos los gastos -->
    <div class="modal fade modal-gastos" id="modalVerTodosGastos" tabindex="-1"
        aria-labelledby="modalVerTodosGastosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerTodosGastosLabel">
                        <i class="fas fa-chart-pie me-2"></i>
                        Todos los Gastos
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Estadísticas y gráfica -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribución de Gastos
                                        por
                                        Tipo</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficaGastos" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Estadísticas</h6>
                                </div>
                                <div class="card-body">
                                    <div id="estadisticasGastos">
                                        <!-- Estadísticas dinámicas -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros de búsqueda -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="buscarGasto"
                                    placeholder="Buscar por detalle, monto o tipo...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filtrarPorTipoGasto">
                                <option value="">Todos los tipos</option>
                                <option value="stock">📦 Stock</option>
                                <option value="sueldos">👥 Sueldos</option>
                                <option value="personal">👤 Personal</option>
                                <option value="mantenimiento">🔧 Mantenimiento</option>
                                <option value="otro">📄 Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filtrarPorMes">
                                <option value="">Todos los meses</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                onclick="limpiarFiltrosGastos()" title="Limpiar filtros">
                                <i class="fas fa-eraser"></i>
                            </button>
                            <div id="contadorResultadosGastos" class="text-center text-muted small mt-1">
                                <!-- Contador dinámico -->
                            </div>
                        </div>
                    </div>

                    <!-- Loading spinner -->
                    <div id="loadingGastos" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando gastos...</p>
                    </div>

                    <!-- Contenido de gastos -->
                    <div id="contenidoGastos">
                        <!-- Contenido dinámico se carga aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="abrirModalGasto()"
                        data-bs-dismiss="modal">
                        <i class="fas fa-plus me-1"></i>
                        Agregar Nuevo Gasto
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // === FUNCIONALIDAD DEL MODAL DÍAS NO LABORABLES ===
        console.log('Script de días no laborables cargado');

        let modalInstanceDashboard;

        // Hacer la función disponible globalmente
        function abrirModalDiaDashboard() {
            console.log('Abriendo modal de días no laborables desde dashboard');

            try {
                // Limpiar el formulario
                const form = document.getElementById('formAgregarDiaDashboard');
                if (form) {
                    form.reset();
                    // Limpiar atributos de edición si existen
                    form.removeAttribute('data-id');
                    form.removeAttribute('data-mode');
                }

                // Ocultar campo personalizado
                const motivoPersonalizadoContainer = document.getElementById('motivoPersonalizadoContainer');
                const motivoPersonalizadoInput = document.getElementById('motivoPersonalizadoModalDashboard');
                if (motivoPersonalizadoContainer) {
                    motivoPersonalizadoContainer.style.display = 'none';
                }
                if (motivoPersonalizadoInput) {
                    motivoPersonalizadoInput.value = '';
                    motivoPersonalizadoInput.required = false;
                }

                // Restaurar título del modal
                const modalTitleElement = document.getElementById('modalAgregarDiaDashboard').querySelector('.modal-title');
                if (modalTitleElement) {
                    modalTitleElement.innerHTML = '<i class="fas fa-calendar-times"></i> Agregar Día No Laborable';
                }

                const preview = document.getElementById('previewModalDashboard');
                if (preview) {
                    preview.style.display = 'none';
                }

                const alerta = document.getElementById('alertaModalDashboard');
                if (alerta) {
                    alerta.style.display = 'none';
                }

                // Quitar clases de error
                const inputs = document.querySelectorAll('#modalAgregarDiaDashboard .form-control');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                    const feedback = input.parentElement.querySelector('.invalid-feedback');
                    if (feedback) feedback.textContent = '';
                });

                // Abrir modal - intentar diferentes formas
                const modalElement = document.getElementById('modalAgregarDiaDashboard');
                if (modalElement) {
                    // Limpiar cualquier backdrop existente antes de abrir
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });

                    // Remover clases modal-open del body
                    document.body.classList.remove('modal-open');

                    // Intentar con Bootstrap 5
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        modalInstanceDashboard = new bootstrap.Modal(modalElement);

                        // Agregar event listener para cuando se oculte el modal
                        modalElement.addEventListener('hidden.bs.modal', function() {
                            console.log('Modal completamente oculto, ejecutando limpieza...');
                            limpiarModalCompletamente();
                        });

                        // También agregar listener para cuando se esté ocultando
                        modalElement.addEventListener('hide.bs.modal', function() {
                            console.log('Modal iniciando proceso de ocultación...');
                        });

                        modalInstanceDashboard.show();
                        console.log('Modal abierto con Bootstrap 5');
                    }
                    // Intentar con jQuery Bootstrap si está disponible
                    else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalElement).modal('show');
                        console.log('Modal abierto con jQuery Bootstrap');
                    }
                    // Fallback manual
                    else {
                        modalElement.style.display = 'block';
                        modalElement.classList.add('show');
                        document.body.classList.add('modal-open');
                        console.log('Modal abierto manualmente');
                    }
                } else {
                    console.error('No se encontró el elemento del modal en dashboard');
                }
            } catch (error) {
                console.error('Error al abrir el modal en dashboard:', error);
            }
        }

        // También hacer disponible globalmente
        window.abrirModalDiaDashboard = abrirModalDiaDashboard;

        // =============================================
        // GESTIÓN DE MODAL DE GASTOS
        // =============================================

        // Variable para la instancia del modal de gastos
        let modalInstanceGasto;

        // Función para abrir el modal de gastos
        function abrirModalGasto() {
            console.log('Abriendo modal de gastos con Bootstrap');

            try {
                // Limpiar el formulario
                const form = document.getElementById('gastoForm');
                if (form) {
                    form.reset();
                    // Limpiar atributos de edición si existen
                    form.removeAttribute('data-id');
                    form.removeAttribute('data-mode');

                    // Establecer la fecha actual por defecto
                    const fechaInput = document.getElementById('gastoFecha');
                    if (fechaInput) {
                        const today = new Date().toISOString().split('T')[0];
                        fechaInput.value = today;
                    }
                }

                // Limpiar alertas
                const alerta = document.getElementById('alertaGastoModal');
                if (alerta) {
                    alerta.style.display = 'none';
                }

                // Restaurar título del modal
                document.getElementById('gastoModalTitle').innerHTML =
                    '<i class="fas fa-money-bill-wave me-2"></i> Registrar Gasto';

                // Abrir el modal con Bootstrap
                const modalElement = document.getElementById('gastoModal');
                if (modalElement) {
                    // Limpiar cualquier backdrop existente
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });

                    modalInstanceGasto = new bootstrap.Modal(modalElement);

                    // Event listener para limpieza cuando se cierre
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        console.log('Modal de gastos cerrado, limpiando...');
                        limpiarModalGastos();
                    });

                    modalInstanceGasto.show();
                    console.log('Modal de gastos abierto correctamente');
                }
            } catch (error) {
                console.error('Error al abrir el modal de gastos:', error);
            }
        }

        // Función para limpiar el modal de gastos
        function limpiarModalGastos() {
            // Limpiar todos los backdrops
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                backdrop.remove();
            });

            // Asegurar que body no tenga clase modal-open
            document.body.classList.remove('modal-open');

            // Restaurar estilos del body
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        // Función para mostrar alertas en el modal de gastos
        function mostrarAlertaGasto(mensaje, tipo = 'danger') {
            const alerta = document.getElementById('alertaGastoModal');
            if (alerta) {
                alerta.className = `alert alert-${tipo}`;
                alerta.innerHTML = `
                    <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${mensaje}
                `;
                alerta.style.display = 'block';

                // Scroll al inicio del modal
                document.querySelector('#gastoModal .modal-body').scrollTop = 0;
            }
        }

        // Hacer la función disponible globalmente
        window.abrirModalGasto = abrirModalGasto;

        // =============================================
        // FUNCIONES PARA EDICIÓN DE DÍAS NO LABORABLES
        // =============================================

        // Función para editar día no laborable
        function editarDiaNoLaborable(diaId) {
            console.log('🔧 Editando día no laborable desde modal principal:', diaId);

            // Verificar si hay algún modal principal abierto
            const modalPrincipal = document.getElementById('modalVerTodosDiasNoLaborables');
            if (modalPrincipal && modalPrincipal.classList.contains('show')) {
                console.log('📋 Modal principal de días detectado como abierto');
            }

            // PRIMERO: Abrir el modal y configurar el modo de edición
            console.log('🚀 Abriendo modal de edición...');
            abrirModalDiaDashboard();

            // Si hay un modal principal abierto, ajustar z-index
            if (modalPrincipal && modalPrincipal.classList.contains('show')) {
                setTimeout(() => {
                    const modalEdicion = document.getElementById('modalAgregarDiaDashboard');
                    const backdrop = document.querySelector('.modal-backdrop:last-of-type');

                    if (modalEdicion) {
                        modalEdicion.style.zIndex = '1070';
                        console.log('📐 Z-index del modal de edición ajustado a 1070');
                    }

                    if (backdrop) {
                        backdrop.style.zIndex = '1069';
                        console.log('📐 Z-index del backdrop ajustado a 1069');
                    }
                }, 100);
            }

            // Cambiar el título del modal
            const modalTitleElement = document.getElementById('modalAgregarDiaDashboard').querySelector('.modal-title');
            if (modalTitleElement) {
                modalTitleElement.innerHTML = '<i class="fas fa-edit"></i> Editar Día No Laborable';
            }

            // Configurar el formulario para edición
            const form = document.getElementById('formAgregarDiaDashboard');
            form.setAttribute('data-id', diaId);
            form.setAttribute('data-mode', 'edit');

            // SEGUNDO: Obtener datos del día no laborable después de abrir el modal
            fetch(`{{ url('/admin/dias-no-laborables') }}/${diaId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);

                    // Esperar un momento para que el modal se renderice completamente
                    setTimeout(() => {
                        const fechaInput = document.getElementById('fechaModalDashboard');
                        const motivoSelect = document.getElementById('motivoModalDashboard');

                        console.log('Elementos del modal después de abrirlo:');
                        console.log('fechaInput:', fechaInput ? 'ENCONTRADO' : 'NO ENCONTRADO');
                        console.log('motivoSelect:', motivoSelect ? 'ENCONTRADO' : 'NO ENCONTRADO');

                        if (fechaInput) {
                            // Formatear fecha para el input date (YYYY-MM-DD)
                            let fechaFormateada = data.fecha;
                            if (data.fecha && data.fecha.includes('T')) {
                                fechaFormateada = data.fecha.split('T')[0];
                            } else if (data.fecha && data.fecha.includes(' ')) {
                                fechaFormateada = data.fecha.split(' ')[0];
                            }

                            fechaInput.value = fechaFormateada;
                            console.log('Fecha asignada:', fechaFormateada);
                        }

                        if (motivoSelect) {
                            const motivosDisponibles = ['feriado', 'mantenimiento', 'vacaciones', 'emergencia',
                                'evento_especial', 'otro'
                            ];

                            if (motivosDisponibles.includes(data.motivo)) {
                                // Es un motivo predefinido
                                motivoSelect.value = data.motivo;
                                console.log('Motivo predefinido asignado:', data.motivo);
                            } else {
                                // Es un motivo personalizado
                                motivoSelect.value = 'otro';
                                const motivoPersonalizadoInput = document.getElementById(
                                    'motivoPersonalizadoModalDashboard');
                                if (motivoPersonalizadoInput) {
                                    motivoPersonalizadoInput.value = data.motivo;
                                    console.log('Motivo personalizado asignado:', data.motivo);
                                }
                                // Mostrar el campo personalizado
                                toggleMotivoPersonalizadoModalDashboard();
                            }
                        }

                        // Actualizar vista previa con los datos cargados
                        if (typeof actualizarVistaPreviaModalDashboard === 'function') {
                            actualizarVistaPreviaModalDashboard();
                        }

                    }, 200); // Incrementé el tiempo de espera
                })
                .catch(error => {
                    console.error('Error al cargar día no laborable:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar los datos del día no laborable'
                    });
                });
        }

        // Función para eliminar día no laborable
        function eliminarDiaNoLaborable(diaId, fecha, motivo) {
            Swal.fire({
                title: '¿Eliminar día no laborable?',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p><strong>Fecha:</strong> ${fecha}</p>
                        <p><strong>Motivo:</strong> ${motivo}</p>
                    </div>
                    <p style="color: #e74c3c; margin-top: 15px;">Esta acción no se puede deshacer.</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Eliminar vía AJAX
                    fetch(`{{ url('/admin/dias-no-laborables') }}/${diaId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Día no laborable eliminado correctamente'
                                });
                                // Recargar la página para actualizar la tabla
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                throw new Error(data.message || 'Error al eliminar');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Error al eliminar el día no laborable'
                            });
                        });
                }
            });
        }

        // =============================================
        // FUNCIONES PARA GESTIÓN DE GASTOS
        // =============================================

        // Función para ver detalle de gasto
        function verDetalleGasto(gastoId) {
            console.log('Viendo detalle de gasto:', gastoId);

            fetch(`{{ url('/admin/gastos') }}/${gastoId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos del gasto recibidos:', data);

                    const modalContent = `
                        <div style="text-align: left; padding: 20px;">
                            <div style="margin-bottom: 15px;">
                                <strong>Fecha del Gasto:</strong> ${new Date(data.fecha_gasto).toLocaleDateString('es-ES')}
                            </div>
                            <div style="margin-bottom: 15px;">
                                <strong>Tipo:</strong> 
                                <span style="background: ${getTipoColor(data.tipo)}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    ${getTipoLabel(data.tipo)}
                                </span>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <strong>Monto:</strong> 
                                <span style="color: #e74c3c; font-size: 1.2rem; font-weight: bold;">
                                    $${parseFloat(data.monto).toFixed(2)}
                                </span>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <strong>Detalle:</strong><br>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 5px;">
                                    ${data.detalle}
                                </div>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <strong>Registrado por:</strong> ${data.usuario ? data.usuario.nombre : 'Usuario no disponible'}
                            </div>
                            <div style="margin-bottom: 15px;">
                                <strong>Fecha de registro:</strong> ${new Date(data.created_at).toLocaleDateString('es-ES')} a las ${new Date(data.created_at).toLocaleTimeString('es-ES')}
                            </div>
                        </div>
                    `;

                    // Insertar contenido en el modal específico de gastos
                    document.getElementById('detalleGastoContent').innerHTML = modalContent;

                    // Mostrar el modal específico de gastos
                    document.getElementById('detalleGastoModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error completo al cargar gasto:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar los datos del gasto',
                        text: error.message
                    });
                });
        }

        // Función para editar gasto
        function editarGasto(gastoId) {
            console.log('🔧 Editando gasto desde modal principal:', gastoId);

            // Verificar si hay algún modal principal abierto
            const modalPrincipal = document.getElementById('modalVerTodosGastos');
            if (modalPrincipal && modalPrincipal.classList.contains('show')) {
                console.log('📋 Modal principal de gastos detectado como abierto');
            }

            fetch(`{{ url('/admin/gastos') }}/${gastoId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Llenar el formulario existente con los datos
                    document.getElementById('gastoTipo').value = data.tipo;
                    document.getElementById('gastoDetalle').value = data.detalle;
                    document.getElementById('gastoMonto').value = data.monto;

                    // Asignar la fecha (ya viene en formato Y-m-d desde el servidor)
                    console.log('Fecha del servidor:', data.fecha_gasto, typeof data.fecha_gasto);

                    // El servidor ahora envía la fecha ya formateada como YYYY-MM-DD
                    document.getElementById('gastoFecha').value = data.fecha_gasto || new Date().toISOString().split(
                        'T')[0];

                    console.log('Fecha asignada al input:', document.getElementById('gastoFecha').value);

                    // Cambiar el título del modal
                    document.getElementById('gastoModalTitle').innerHTML =
                        '<i class="fas fa-edit me-2"></i> Editar Gasto';

                    // Agregar el ID al formulario para identificar que es edición
                    const form = document.getElementById('gastoForm');
                    form.setAttribute('data-id', gastoId);
                    form.setAttribute('data-mode', 'edit');

                    // Abrir el modal con Bootstrap
                    const modalElement = document.getElementById('gastoModal');
                    if (modalElement) {
                        modalInstanceGasto = new bootstrap.Modal(modalElement);
                        modalInstanceGasto.show();

                        // Si hay un modal principal abierto, ajustar z-index después de mostrar el modal
                        if (modalPrincipal && modalPrincipal.classList.contains('show')) {
                            setTimeout(() => {
                                modalElement.style.zIndex = '1070';
                                const backdrop = document.querySelector('.modal-backdrop:last-of-type');
                                if (backdrop) {
                                    backdrop.style.zIndex = '1069';
                                }
                                console.log('📐 Z-index del modal de gastos y backdrop ajustados');
                            }, 150);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al cargar gasto:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar los datos del gasto'
                    });
                });
        }

        // Funciones auxiliares para gastos
        function getTipoColor(tipo) {
            const colors = {
                'stock': '#3498db',
                'sueldos': '#27ae60',
                'personal': '#f39c12',
                'mantenimiento': '#9b59b6'
            };
            return colors[tipo] || '#95a5a6';
        }

        function getTipoLabel(tipo) {
            const labels = {
                'stock': 'Stock',
                'sueldos': 'Sueldos',
                'personal': 'Personal',
                'mantenimiento': 'Mantenimiento'
            };
            return labels[tipo] || tipo;
        }

        // Función para eliminar gasto con modal de confirmación
        function eliminarGastoModal(gastoId, detalle, monto) {
            Swal.fire({
                title: '¿Eliminar gasto?',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p><strong>Detalle:</strong> ${detalle}</p>
                        <p><strong>Monto:</strong> <span style="color: #e74c3c; font-weight: bold;">$${parseFloat(monto).toFixed(2)}</span></p>
                    </div>
                    <p style="color: #e74c3c; margin-top: 15px;">Esta acción no se puede deshacer.</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Eliminar vía AJAX
                    fetch(`{{ url('/admin/gastos') }}/${gastoId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Gasto eliminado correctamente'
                                });
                                // Recargar la página para actualizar la tabla
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                throw new Error(data.message || 'Error al eliminar');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Error al eliminar el gasto'
                            });
                        });
                }
            });
        }

        // Hacer todas las funciones disponibles globalmente
        window.editarDiaNoLaborable = editarDiaNoLaborable;
        window.eliminarDiaNoLaborable = eliminarDiaNoLaborable;
        window.verDetalleGasto = verDetalleGasto;
        window.editarGasto = editarGasto;
        window.eliminarGastoModal = eliminarGastoModal;

        // Función para limpiar completamente el modal
        function limpiarModalCompletamente() {
            console.log('Ejecutando limpieza completa del modal...');

            // Remover todos los backdrops
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                console.log('Removiendo backdrop:', backdrop);
                backdrop.remove();
            });

            // Limpiar clases del body
            document.body.classList.remove('modal-open');

            // Restaurar estilos del body
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            // Ocultar el modal específico
            const modalElement = document.getElementById('modalAgregarDiaDashboard');
            if (modalElement) {
                modalElement.style.display = 'none';
                modalElement.classList.remove('show');
                modalElement.setAttribute('aria-hidden', 'true');
                modalElement.removeAttribute('aria-modal');
            }

            console.log('Limpieza completa terminada');
        }

        // Función para cerrar el modal
        function cerrarModalDiaDashboard() {
            console.log('Cerrando modal dashboard');

            try {
                if (modalInstanceDashboard) {
                    modalInstanceDashboard.hide();
                    console.log('Modal cerrado con Bootstrap');

                    // Limpiar la instancia después de cerrar
                    modalInstanceDashboard = null;
                } else {
                    // Si no hay instancia, intentar obtener el modal y cerrarlo
                    const modalElement = document.getElementById('modalAgregarDiaDashboard');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                }

                // Ejecutar limpieza adicional después de un breve delay
                setTimeout(limpiarModalCompletamente, 300);

            } catch (error) {
                console.error('Error al cerrar el modal:', error);

                // Fallback: limpiar inmediatamente
                limpiarModalCompletamente();
            }
        }

        // Hacer disponible globalmente
        window.cerrarModalDiaDashboard = cerrarModalDiaDashboard;
        window.limpiarModalCompletamente = limpiarModalCompletamente;

        // Función de emergencia para llamar desde consola si hay problemas
        window.emergenciaLimpiarModal = function() {
            console.log('🚨 FUNCIÓN DE EMERGENCIA ACTIVADA 🚨');

            // Cerrar todos los modales de Bootstrap
            document.querySelectorAll('.modal.show').forEach(modal => {
                const instance = bootstrap.Modal.getInstance(modal);
                if (instance) {
                    instance.hide();
                }
            });

            // Limpieza completa después de un delay
            setTimeout(() => {
                limpiarModalCompletamente();

                // Limpieza adicional super agresiva
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                document.documentElement.style.overflow = '';

                console.log('🔧 Limpieza de emergencia completada');
            }, 100);
        };

        // Función para mostrar alertas en el modal del dashboard
        function mostrarAlertaModalDashboard(mensaje, tipo = 'danger') {
            const alerta = document.getElementById('alertaModalDashboard');
            alerta.className = `alert alert-${tipo}`;
            alerta.innerHTML = `
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${mensaje}
            `;
            alerta.style.display = 'block';

            // Scroll al inicio del modal
            document.querySelector('#modalAgregarDiaDashboard .modal-body').scrollTop = 0;
        }

        // Función para actualizar la vista previa en el modal del dashboard
        function actualizarVistaPreviaModalDashboard() {
            const fechaInput = document.getElementById('fechaModalDashboard');
            const motivoSelect = document.getElementById('motivoModalDashboard');
            const motivoPersonalizadoInput = document.getElementById('motivoPersonalizadoModalDashboard');
            const preview = document.getElementById('previewModalDashboard');
            const previewContent = document.getElementById('previewContentModalDashboard');

            const fecha = fechaInput.value;

            // Obtener el motivo correcto (personalizado o predefinido)
            let motivo;
            if (motivoSelect.value === 'otro') {
                motivo = motivoPersonalizadoInput.value.trim() || 'Otro (sin especificar)';
            } else {
                motivo = motivoSelect.options[motivoSelect.selectedIndex]?.text;
            }

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
                            <i class="fas fa-calendar-times fa-2x text-warning"></i>
                        </div>
                        <div>
                            <strong>${fechaFormateada}</strong><br>
                            <span class="text-muted">Motivo: ${motivo}</span>
                        </div>
                    </div>
                `;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        // Event listeners para el modal del dashboard
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando modal dashboard');

            const btnDashboard = document.getElementById('btnAgregarDiaDashboard');
            const fechaModalDashboard = document.getElementById('fechaModalDashboard');
            const motivoModalDashboard = document.getElementById('motivoModalDashboard');
            const btnGuardarDashboard = document.getElementById('btnGuardarDiaDashboard');
            const btnCancelarDashboard = document.getElementById('btnCancelarModalDashboard');
            const btnCerrarDashboard = document.getElementById('btnCerrarModalDashboard');

            console.log('Elementos encontrados:', {
                btnDashboard: !!btnDashboard,
                fechaModalDashboard: !!fechaModalDashboard,
                motivoModalDashboard: !!motivoModalDashboard,
                btnGuardarDashboard: !!btnGuardarDashboard,
                btnCancelarDashboard: !!btnCancelarDashboard,
                btnCerrarDashboard: !!btnCerrarDashboard
            });

            // Event listener para el botón de agregar día (backup)
            if (btnDashboard) {
                btnDashboard.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Botón dashboard clickeado via addEventListener');
                    abrirModalDiaDashboard();
                });
                console.log('Event listener agregado al botón dashboard');
            }

            // Los botones de cerrar ya tienen data-bs-dismiss="modal" para que Bootstrap los maneje automáticamente

            // Vista previa en tiempo real
            if (fechaModalDashboard) fechaModalDashboard.addEventListener('change',
                actualizarVistaPreviaModalDashboard);
            if (motivoModalDashboard) motivoModalDashboard.addEventListener('change',
                actualizarVistaPreviaModalDashboard);

            // Manejar envío del formulario
            if (btnGuardarDashboard) {
                btnGuardarDashboard.addEventListener('click', async function() {
                    const originalText = this.innerHTML;
                    const originalDisabled = this.disabled;

                    try {
                        // Cambiar activo del botón
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';

                        // Verificar si estamos en modo edición
                        const form = document.getElementById('formAgregarDiaDashboard');
                        const diaId = form.getAttribute('data-id');
                        const isEdit = form.getAttribute('data-mode') === 'edit';

                        // Obtener datos del formulario
                        const formData = new FormData(form);

                        // Manejar motivo personalizado
                        const motivoFinal = obtenerMotivoCompleto();
                        formData.set('motivo', motivoFinal);

                        // Determinar URL y método
                        const url = isEdit ? `{{ url('/admin/dias-no-laborables') }}/${diaId}` :
                            '{{ route('admin.dias-no-laborables.store') }}';
                        const method = isEdit ? 'PUT' : 'POST';

                        // Si es edición, necesitamos convertir FormData a JSON para PUT
                        let body, headers;
                        if (isEdit) {
                            const dataObj = {};
                            formData.forEach((value, key) => {
                                dataObj[key] = value;
                            });
                            body = JSON.stringify(dataObj);
                            headers = {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            };
                        } else {
                            body = formData;
                            headers = {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            };
                        }

                        // Enviar petición
                        const response = await fetch(url, {
                            method: method,
                            body: body,
                            headers: headers
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Éxito - cerrar modal y mostrar mensaje
                            cerrarModalDiaDashboard();

                            // Limpiar atributos del formulario
                            form.removeAttribute('data-id');
                            form.removeAttribute('data-mode');

                            // Restaurar título del modal
                            document.getElementById('modalAgregarDiaDashboard').querySelector(
                                    '.modal-title').innerHTML =
                                '<i class="fas fa-calendar-times"></i> Agregar Día No Laborable';

                            // Mostrar notificación de éxito
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: isEdit ? 'Día no laborable actualizado exitosamente' :
                                        'Día no laborable agregado exitosamente',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                alert(isEdit ? 'Día no laborable actualizado exitosamente' :
                                    'Día no laborable agregado exitosamente');
                            }

                            // Verificar si hay un modal principal abierto
                            const modalPrincipal = document.getElementById(
                                'modalVerTodosDiasNoLaborables');
                            const modalPrincipalAbierto = modalPrincipal && modalPrincipal.classList
                                .contains('show');

                            if (modalPrincipalAbierto) {
                                // Si hay modal principal abierto, recargar solo sus datos
                                console.log('🔄 Recargando datos del modal principal de días...');
                                setTimeout(() => {
                                    if (typeof cargarTodosDiasNoLaborables === 'function') {
                                        cargarTodosDiasNoLaborables();
                                    }
                                }, 1000);
                            } else {
                                // Si no hay modal principal, recargar toda la página
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }

                        } else {
                            // Error de validación
                            if (data.errors) {
                                // Limpiar errores anteriores
                                const inputs = document.querySelectorAll(
                                    '#modalAgregarDiaDashboard .form-control');
                                inputs.forEach(input => {
                                    input.classList.remove('is-invalid');
                                    const feedback = input.parentElement.querySelector(
                                        '.invalid-feedback');
                                    if (feedback) feedback.textContent = '';
                                });

                                // Mostrar errores específicos
                                Object.keys(data.errors).forEach(field => {
                                    const input = document.querySelector(
                                        `#modalAgregarDiaDashboard [name="${field}"]`);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        const feedback = input.parentElement.querySelector(
                                            '.invalid-feedback');
                                        if (feedback) {
                                            feedback.textContent = data.errors[field][0];
                                        }
                                    }
                                });

                                mostrarAlertaModalDashboard(
                                    'Por favor, corrige los errores en el formulario.');
                            } else {
                                mostrarAlertaModalDashboard(data.message ||
                                    `Error al ${isEdit ? 'actualizar' : 'guardar'} el día no laborable.`
                                );
                            }
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        mostrarAlertaModalDashboard(
                            'Error de conexión. Por favor, inténtalo de nuevo.');

                    } finally {
                        // Restaurar activo del botón
                        this.disabled = originalDisabled;
                        this.innerHTML = originalText;
                    }
                });
            }

            // =============================================
            // FUNCIONES PARA MOTIVO PERSONALIZADO
            // =============================================

            // Función para mostrar/ocultar campo de motivo personalizado
            window.toggleMotivoPersonalizadoModalDashboard = function() {
                const motivoSelect = document.getElementById('motivoModalDashboard');
                const container = document.getElementById('motivoPersonalizadoContainer');
                const input = document.getElementById('motivoPersonalizadoModalDashboard');

                if (motivoSelect.value === 'otro') {
                    container.style.display = 'block';
                    input.required = true;
                    input.focus();
                } else {
                    container.style.display = 'none';
                    input.required = false;
                    input.value = '';
                }

                // Actualizar vista previa
                actualizarVistaPreviaModalDashboard();
            };

            // Agregar event listener para el campo personalizado
            const motivoPersonalizadoInput = document.getElementById('motivoPersonalizadoModalDashboard');
            if (motivoPersonalizadoInput) {
                motivoPersonalizadoInput.addEventListener('input', actualizarVistaPreviaModalDashboard);
            }
        });

        // =============================================
        // FUNCIÓN PERSONALIZADA PARA OBTENER MOTIVO
        // =============================================
        function obtenerMotivoCompleto() {
            const motivoSelect = document.getElementById('motivoModalDashboard');
            const motivoPersonalizado = document.getElementById('motivoPersonalizadoModalDashboard');

            if (motivoSelect.value === 'otro') {
                return motivoPersonalizado.value.trim();
            } else {
                return motivoSelect.value;
            }
        }

        // =============================================
        // MODAL PARA VER TODOS LOS DÍAS NO LABORABLES
        // =============================================

        let todosDiasNoLaborables = [];
        let todosDiasNoLaborablesFiltrados = [];

        // Función para abrir el modal de ver todos
        function abrirModalVerTodosDiasNoLaborables() {
            console.log('🔍 Función abrirModalVerTodosDiasNoLaborables() llamada');
            const modalElement = document.getElementById('modalVerTodosDiasNoLaborables');
            console.log('🔍 Modal element encontrado:', modalElement);

            if (!modalElement) {
                console.error('❌ Modal modalVerTodosDiasNoLaborables no encontrado');
                return;
            }

            try {
                const modal = new bootstrap.Modal(modalElement);
                console.log('🔍 Bootstrap Modal creado:', modal);
                modal.show();
                console.log('✅ Modal mostrado exitosamente');
                cargarTodosDiasNoLaborables();
            } catch (error) {
                console.error('❌ Error al abrir modal:', error);
            }
        }

        // Función para cargar todos los días no laborables
        async function cargarTodosDiasNoLaborables() {
            const loading = document.getElementById('loadingDiasNoLaborables');
            const contenido = document.getElementById('contenidoDiasNoLaborables');

            loading.style.display = 'block';
            contenido.innerHTML = '';

            try {
                const response = await fetch('{{ route('admin.dias-no-laborables.index') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar los datos');
                }

                todosDiasNoLaborables = await response.json();
                todosDiasNoLaborablesFiltrados = [...todosDiasNoLaborables];
                mostrarDiasNoLaborables(todosDiasNoLaborablesFiltrados);

            } catch (error) {
                console.error('Error:', error);
                contenido.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar los días no laborables. Por favor, inténtalo de nuevo.
                    </div>
                `;
            } finally {
                loading.style.display = 'none';
            }
        }

        // Función para mostrar los días no laborables
        function mostrarDiasNoLaborables(dias) {
            const contenido = document.getElementById('contenidoDiasNoLaborables');
            const contador = document.getElementById('contadorResultados');

            // Actualizar contador de resultados
            if (contador) {
                const total = todosDiasNoLaborables.length;
                const mostrados = dias.length;

                if (mostrados === total) {
                    contador.innerHTML = `<strong>${total}</strong> días`;
                } else {
                    contador.innerHTML = `<strong>${mostrados}</strong> de <strong>${total}</strong> días`;
                }
            }

            if (dias.length === 0) {
                contenido.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron días no laborables</h5>
                        <p class="text-muted">No hay días que coincidan con los criterios de búsqueda.</p>
                        <button type="button" class="btn btn-outline-primary mt-3" onclick="limpiarFiltrosDiasNoLaborables()">
                            <i class="fas fa-eraser me-1"></i>
                            Limpiar filtros
                        </button>
                    </div>
                `;
                return;
            }

            let html = '<div class="row">';

            dias.forEach(dia => {
                // Usar la fecha formateada que envía el servidor
                const fecha = new Date(dia.fecha + 'T12:00:00');
                const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                const esHoy = dia.es_hoy;
                const esFuturo = dia.es_futuro;
                const esPasado = dia.es_pasado;

                let badgeClass = 'bg-secondary';
                let iconStatus = 'fas fa-check-circle';
                let statusText = 'Pasado';

                if (esHoy) {
                    badgeClass = 'bg-danger';
                    iconStatus = 'fas fa-exclamation-circle';
                    statusText = 'Es hoy';
                } else if (esFuturo) {
                    badgeClass = 'bg-warning';
                    iconStatus = 'fas fa-clock';
                    statusText = dia.dias_restantes + ' días restantes';
                }

                html += `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100" style="border-left: 4px solid var(--primary);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calendar-day me-2"></i>
                                        ${fecha.toLocaleDateString('es-ES')}
                                    </h6>
                                    <span class="badge ${badgeClass}">
                                        <i class="${iconStatus} me-1"></i>
                                        ${statusText}
                                    </span>
                                </div>
                                <p class="card-subtitle text-muted small mb-2">${fechaFormateada}</p>
                                <p class="card-text">
                                    <strong>Motivo:</strong><br>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="${dia.motivo}">
                                        ${dia.motivo}
                                    </span>
                                </p>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editarDiaNoLaborable(${dia.id})">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarDiaNoLaborable(${dia.id}, '${fecha.toLocaleDateString('es-ES')}', '${dia.motivo.replace(/'/g, "\\'")}')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            contenido.innerHTML = html;
        }

        // Event listeners para filtros
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscarDiaNoLaborable');
            const filtroMotivo = document.getElementById('filtrarPorMotivo');

            if (buscarInput) {
                buscarInput.addEventListener('input', filtrarDiasNoLaborables);
            }

            if (filtroMotivo) {
                filtroMotivo.addEventListener('change', filtrarDiasNoLaborables);
            }
        });

        // Función auxiliar para normalizar fechas en diferentes formatos
        function normalizarFecha(fecha) {
            if (window.debugFiltros) console.log('🔍 Normalizando fecha:', fecha, typeof fecha);

            try {
                // Manejar diferentes tipos de entrada
                let fechaStr = fecha;

                if (typeof fecha === 'object' && fecha !== null) {
                    if (fecha.date) {
                        fechaStr = fecha.date;
                    } else {
                        fechaStr = fecha.toString();
                    }
                }

                // Crear el objeto Date de forma consistente
                let fechaObj;

                if (fechaStr.includes('T')) {
                    // Ya tiene tiempo incluido
                    fechaObj = new Date(fechaStr);
                } else if (fechaStr.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    // Formato YYYY-MM-DD
                    fechaObj = new Date(fechaStr + 'T12:00:00');
                } else {
                    // Intentar parseado directo
                    fechaObj = new Date(fechaStr);
                }

                if (window.debugFiltros) console.log('📅 Objeto Date creado:', fechaObj);

                if (isNaN(fechaObj.getTime())) {
                    console.error('❌ Fecha inválida:', fechaStr);
                    return null;
                }

                const dia = fechaObj.getDate().toString().padStart(2, '0');
                const mes = (fechaObj.getMonth() + 1).toString().padStart(2, '0');
                const año = fechaObj.getFullYear();

                // Crear texto formateado con try-catch para manejar errores de localización
                let textoCompleto, mesTexto, diaSemana;

                try {
                    textoCompleto = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }).toLowerCase();

                    mesTexto = fechaObj.toLocaleDateString('es-ES', {
                        month: 'long'
                    }).toLowerCase();
                    diaSemana = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long'
                    }).toLowerCase();
                } catch (e) {
                    console.warn('⚠️ Error en localización, usando fallback');
                    const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                    ];
                    const diasSemana = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];

                    mesTexto = meses[fechaObj.getMonth()];
                    diaSemana = diasSemana[fechaObj.getDay()];
                    textoCompleto = `${diaSemana}, ${dia} de ${mesTexto} de ${año}`;
                }

                const resultado = {
                    original: fechaStr,
                    ddmmyyyy: `${dia}/${mes}/${año}`,
                    ddmm: `${dia}/${mes}`,
                    dd: dia,
                    mm: mes,
                    yyyy: año.toString(),
                    texto: textoCompleto,
                    mesTexto: mesTexto,
                    diaSemana: diaSemana,
                    fechaObj: fechaObj // Incluir el objeto Date para referencia
                };

                if (window.debugFiltros) console.log('✅ Fecha normalizada:', resultado);
                return resultado;

            } catch (error) {
                console.error('❌ Error al normalizar fecha:', error, fecha);
                return null;
            }
        }

        // Función mejorada para filtrar días no laborables
        function filtrarDiasNoLaborables() {
            const busqueda = document.getElementById('buscarDiaNoLaborable').value.toLowerCase().trim();
            const motivoFiltro = document.getElementById('filtrarPorMotivo').value;

            if (window.debugFiltros) {
                console.log('🔍 === INICIANDO FILTRADO ===');
                console.log('Búsqueda:', busqueda);
                console.log('Filtro motivo:', motivoFiltro);
                console.log('Total de días a filtrar:', todosDiasNoLaborables.length);
            }

            todosDiasNoLaborablesFiltrados = todosDiasNoLaborables.filter((dia, index) => {
                if (window.debugFiltros) console.log(`\n🔸 Procesando día ${index + 1}:`, dia);

                let coincideBusqueda = true;

                if (busqueda) {
                    if (window.debugFiltros) console.log(`🔍 Buscando "${busqueda}" en día:`, dia.fecha);

                    // Normalizar la fecha del día
                    const fechaNormalizada = normalizarFecha(dia.fecha);

                    if (fechaNormalizada) {
                        if (window.debugFiltros) {
                            console.log('📋 Formatos disponibles para búsqueda:');
                            console.log('  - DD/MM/YYYY:', fechaNormalizada.ddmmyyyy);
                            console.log('  - DD/MM:', fechaNormalizada.ddmm);
                            console.log('  - DD:', fechaNormalizada.dd);
                            console.log('  - MM:', fechaNormalizada.mm);
                            console.log('  - YYYY:', fechaNormalizada.yyyy);
                            console.log('  - Texto completo:', fechaNormalizada.texto);
                            console.log('  - Mes:', fechaNormalizada.mesTexto);
                            console.log('  - Día semana:', fechaNormalizada.diaSemana);
                            console.log('  - Original:', fechaNormalizada.original);
                            console.log('  - Motivo:', dia.motivo.toLowerCase());
                        }

                        // Buscar en múltiples formatos de fecha
                        const coincidencias = {
                            ddmmyyyy: fechaNormalizada.ddmmyyyy.includes(busqueda),
                            ddmm: fechaNormalizada.ddmm.includes(busqueda),
                            dd: fechaNormalizada.dd.includes(busqueda),
                            mm: fechaNormalizada.mm.includes(busqueda),
                            yyyy: fechaNormalizada.yyyy.includes(busqueda),
                            texto: fechaNormalizada.texto.includes(busqueda),
                            mesTexto: fechaNormalizada.mesTexto.includes(busqueda),
                            diaSemana: fechaNormalizada.diaSemana.includes(busqueda),
                            motivo: dia.motivo.toLowerCase().includes(busqueda),
                            original: dia.fecha.includes(busqueda)
                        };

                        if (window.debugFiltros) console.log('🎯 Coincidencias encontradas:', coincidencias);

                        coincideBusqueda = Object.values(coincidencias).some(coincide => coincide);

                        if (window.debugFiltros) console.log('✅ ¿Coincide búsqueda?', coincideBusqueda);

                    } else {
                        if (window.debugFiltros) console.warn(
                            '⚠️ No se pudo normalizar la fecha, buscando solo en motivo');
                        coincideBusqueda = dia.motivo.toLowerCase().includes(busqueda);
                        if (window.debugFiltros) console.log('✅ ¿Coincide en motivo?', coincideBusqueda);
                    }
                } else {
                    if (window.debugFiltros) console.log('ℹ️ Sin búsqueda de texto, todos pasan');
                }

                // Verificar filtro de motivo
                let coincideMotivo = true;
                if (motivoFiltro) {
                    if (motivoFiltro === 'otro') {
                        coincideMotivo = !['feriado', 'mantenimiento', 'vacaciones', 'emergencia',
                            'evento_especial'
                        ].includes(dia.motivo);
                        if (window.debugFiltros) console.log('🔍 Filtro "otro" - ¿Es motivo personalizado?',
                            coincideMotivo);
                    } else {
                        coincideMotivo = dia.motivo === motivoFiltro;
                        if (window.debugFiltros) console.log('🔍 Filtro motivo específico - ¿Coincide?',
                            coincideMotivo);
                    }
                } else {
                    if (window.debugFiltros) console.log('ℹ️ Sin filtro de motivo, todos pasan');
                }

                const resultado = coincideBusqueda && coincideMotivo;
                if (window.debugFiltros) console.log('🎯 RESULTADO FINAL:', resultado ? '✅ INCLUIDO' :
                    '❌ EXCLUIDO');

                return resultado;
            });

            console.log(
                `\n🏁 FILTRADO COMPLETADO: ${todosDiasNoLaborablesFiltrados.length} de ${todosDiasNoLaborables.length} días`
            );
            mostrarDiasNoLaborables(todosDiasNoLaborablesFiltrados);
        }

        // Función para limpiar filtros
        function limpiarFiltrosDiasNoLaborables() {
            document.getElementById('buscarDiaNoLaborable').value = '';
            document.getElementById('filtrarPorMotivo').value = '';
            filtrarDiasNoLaborables();
        }

        // Variable para controlar el debugging detallado
        window.debugFiltros = true; // Cambiar a false para desactivar logs detallados

        // Función para activar/desactivar debug desde consola
        window.toggleDebugFiltros = function() {
            window.debugFiltros = !window.debugFiltros;
            console.log('🐛 Debug de filtros:', window.debugFiltros ? 'ACTIVADO' : 'DESACTIVADO');
        };

        // Hacer las funciones disponibles globalmente
        window.abrirModalVerTodosDiasNoLaborables = abrirModalVerTodosDiasNoLaborables;
        window.limpiarFiltrosDiasNoLaborables = limpiarFiltrosDiasNoLaborables;

        // =============================================
        // GESTIÓN DE MODAL VER TODOS LOS GASTOS
        // =============================================

        let todosGastos = [];
        let todosGastosFiltrados = [];
        let graficaGastosInstance = null;

        // Función para abrir el modal de ver todos los gastos
        function abrirModalVerTodosGastos() {
            console.log('🔍 Función abrirModalVerTodosGastos() llamada');
            const modalElement = document.getElementById('modalVerTodosGastos');
            console.log('🔍 Modal element encontrado:', modalElement);

            if (!modalElement) {
                console.error('❌ Modal modalVerTodosGastos no encontrado');
                return;
            }

            try {
                const modal = new bootstrap.Modal(modalElement);
                console.log('🔍 Bootstrap Modal creado:', modal);

                // Agregar event listener para cuando se oculte el modal
                modalElement.addEventListener('hidden.bs.modal', function() {
                    console.log('Modal de gastos completamente oculto, ejecutando limpieza...');
                    limpiarModalGastos();
                });

                modal.show();
                console.log('✅ Modal mostrado exitosamente');
                cargarTodosGastos();
            } catch (error) {
                console.error('❌ Error al abrir modal:', error);
            }
        }

        // Función para cargar todos los gastos
        async function cargarTodosGastos() {
            const loading = document.getElementById('loadingGastos');
            const contenido = document.getElementById('contenidoGastos');

            loading.style.display = 'block';
            contenido.innerHTML = '';

            try {
                const response = await fetch('{{ route('admin.gastos.index') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const gastos = await response.json();
                console.log('Gastos cargados:', gastos);

                todosGastos = gastos;
                todosGastosFiltrados = gastos;

                loading.style.display = 'none';

                // Mostrar gastos y estadísticas
                mostrarGastos(todosGastosFiltrados);
                generarEstadisticasGastos(todosGastos);
                generarGraficaGastos(todosGastos);

            } catch (error) {
                console.error('Error al cargar gastos:', error);
                loading.style.display = 'none';
                contenido.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Error al cargar gastos</h5>
                        <p class="text-muted">Hubo un problema al cargar los datos.</p>
                        <button class="btn btn-primary" onclick="cargarTodosGastos()">
                            <i class="fas fa-refresh me-1"></i>
                            Reintentar
                        </button>
                    </div>
                `;
            }
        }

        // Función para mostrar los gastos
        function mostrarGastos(gastos) {
            const contenido = document.getElementById('contenidoGastos');
            const contador = document.getElementById('contadorResultadosGastos');

            // Actualizar contador de resultados
            if (contador) {
                const total = todosGastos.length;
                const mostrados = gastos.length;

                if (mostrados === total) {
                    contador.innerHTML = `<strong>${total}</strong> gastos`;
                } else {
                    contador.innerHTML = `<strong>${mostrados}</strong> de <strong>${total}</strong> gastos`;
                }
            }

            if (gastos.length === 0) {
                contenido.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron gastos</h5>
                        <p class="text-muted">No hay gastos que coincidan con los criterios de búsqueda.</p>
                        <button type="button" class="btn btn-outline-primary mt-3" onclick="limpiarFiltrosGastos()">
                            <i class="fas fa-eraser me-1"></i>
                            Limpiar filtros
                        </button>
                    </div>
                `;
                return;
            }

            let html = '<div class="row">';

            gastos.forEach(gasto => {
                const fecha = new Date(gasto.fecha_gasto + 'T12:00:00');
                const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Determinar color y emoji por tipo
                const tipoInfo = getTipoGastoInfo(gasto.tipo);

                html += `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 gasto-card" data-tipo="${gasto.tipo}">
                            <div class="card-header" style="background: ${tipoInfo.gradient}; color: white;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        ${tipoInfo.emoji} ${tipoInfo.nombre}
                                    </h6>
                                    <span class="badge bg-light text-dark">
                                        $${parseFloat(gasto.monto).toLocaleString('es-ES', {minimumFractionDigits: 2})}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text mb-2">
                                    <strong>Detalle:</strong><br>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="${gasto.detalle}">
                                        ${gasto.detalle}
                                    </span>
                                </p>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${fechaFormateada}
                                </p>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    Registrado por: ${gasto.usuario}
                                </p>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-primary" onclick="verDetalleGasto(${gasto.id})">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="editarGasto(${gasto.id})">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarGastoModal(${gasto.id}, '${gasto.detalle.replace(/'/g, "\\'")}', ${gasto.monto})">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            contenido.innerHTML = html;
        }

        // Función auxiliar para obtener información del tipo de gasto
        function getTipoGastoInfo(tipo) {
            const tipos = {
                'stock': {
                    nombre: 'Stock',
                    emoji: '📦',
                    color: '#007bff',
                    gradient: 'linear-gradient(135deg, #007bff, #0056b3)'
                },
                'sueldos': {
                    nombre: 'Sueldos',
                    emoji: '👥',
                    color: '#28a745',
                    gradient: 'linear-gradient(135deg, #28a745, #1e7e34)'
                },
                'personal': {
                    nombre: 'Personal',
                    emoji: '👤',
                    color: '#17a2b8',
                    gradient: 'linear-gradient(135deg, #17a2b8, #117a8b)'
                },
                'mantenimiento': {
                    nombre: 'Mantenimiento',
                    emoji: '🔧',
                    color: '#ffc107',
                    gradient: 'linear-gradient(135deg, #ffc107, #e0a800)'
                },
                'otro': {
                    nombre: 'Otro',
                    emoji: '📄',
                    color: '#6c757d',
                    gradient: 'linear-gradient(135deg, #6c757d, #545b62)'
                }
            };

            return tipos[tipo] || tipos['otro'];
        }

        // Event listeners para filtros
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscarGasto');
            const filtroTipo = document.getElementById('filtrarPorTipoGasto');
            const filtroMes = document.getElementById('filtrarPorMes');

            if (buscarInput) {
                buscarInput.addEventListener('input', filtrarGastos);
            }

            if (filtroTipo) {
                filtroTipo.addEventListener('change', filtrarGastos);
            }

            if (filtroMes) {
                filtroMes.addEventListener('change', filtrarGastos);
            }
        });

        // Función para filtrar gastos
        function filtrarGastos() {
            const busqueda = document.getElementById('buscarGasto').value.toLowerCase().trim();
            const tipoFiltro = document.getElementById('filtrarPorTipoGasto').value;
            const mesFiltro = document.getElementById('filtrarPorMes').value;

            console.log('Filtrando gastos - Búsqueda:', busqueda, 'Tipo:', tipoFiltro, 'Mes:', mesFiltro);

            todosGastosFiltrados = todosGastos.filter(gasto => {
                let coincideBusqueda = true;
                let coincideTipo = true;
                let coincideMes = true;

                // Filtro por búsqueda en detalle, monto o tipo
                if (busqueda) {
                    const tipoInfo = getTipoGastoInfo(gasto.tipo);
                    coincideBusqueda =
                        gasto.detalle.toLowerCase().includes(busqueda) ||
                        gasto.monto.toString().includes(busqueda) ||
                        gasto.tipo.toLowerCase().includes(busqueda) ||
                        tipoInfo.nombre.toLowerCase().includes(busqueda);
                }

                // Filtro por tipo
                if (tipoFiltro) {
                    coincideTipo = gasto.tipo === tipoFiltro;
                }

                // Filtro por mes
                if (mesFiltro) {
                    const fechaGasto = new Date(gasto.fecha_gasto + 'T12:00:00');
                    const mesGasto = (fechaGasto.getMonth() + 1).toString().padStart(2, '0');
                    coincideMes = mesGasto === mesFiltro;
                }

                return coincideBusqueda && coincideTipo && coincideMes;
            });

            console.log(`Resultados: ${todosGastosFiltrados.length} de ${todosGastos.length} gastos`);
            mostrarGastos(todosGastosFiltrados);

            // Actualizar gráfica con datos filtrados
            if (graficaGastosInstance) {
                generarGraficaGastos(todosGastosFiltrados);
            }
        }

        // Función para limpiar filtros
        function limpiarFiltrosGastos() {
            document.getElementById('buscarGasto').value = '';
            document.getElementById('filtrarPorTipoGasto').value = '';
            document.getElementById('filtrarPorMes').value = '';
            filtrarGastos();
        }

        // Función para generar estadísticas de gastos
        function generarEstadisticasGastos(gastos) {
            const estadisticas = document.getElementById('estadisticasGastos');

            if (gastos.length === 0) {
                estadisticas.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <p>No hay datos para mostrar</p>
                    </div>
                `;
                return;
            }

            // Calcular estadísticas
            const totalGastos = gastos.length;
            const montoTotal = gastos.reduce((sum, gasto) => sum + parseFloat(gasto.monto), 0);
            const promedioGasto = montoTotal / totalGastos;

            // Agrupar por tipo
            const gastosPorTipo = {};
            gastos.forEach(gasto => {
                if (!gastosPorTipo[gasto.tipo]) {
                    gastosPorTipo[gasto.tipo] = {
                        cantidad: 0,
                        monto: 0
                    };
                }
                gastosPorTipo[gasto.tipo].cantidad++;
                gastosPorTipo[gasto.tipo].monto += parseFloat(gasto.monto);
            });

            // Encontrar tipo más gastado
            let tipoMasGastado = '';
            let montoMasAlto = 0;
            Object.keys(gastosPorTipo).forEach(tipo => {
                if (gastosPorTipo[tipo].monto > montoMasAlto) {
                    montoMasAlto = gastosPorTipo[tipo].monto;
                    tipoMasGastado = tipo;
                }
            });

            const tipoInfo = getTipoGastoInfo(tipoMasGastado);

            estadisticas.innerHTML = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Total de Gastos</span>
                        <strong class="h5 mb-0 text-primary">${totalGastos}</strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Monto Total</span>
                        <strong class="h5 mb-0 text-success">$${montoTotal.toLocaleString('es-ES', {minimumFractionDigits: 2})}</strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Promedio por Gasto</span>
                        <strong class="h5 mb-0 text-info">$${promedioGasto.toLocaleString('es-ES', {minimumFractionDigits: 2})}</strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 75%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Tipo más Gastado</span>
                        <strong class="h6 mb-0">
                            ${tipoInfo.emoji} ${tipoInfo.nombre}
                        </strong>
                    </div>
                    <div class="small text-muted">
                        $${montoMasAlto.toLocaleString('es-ES', {minimumFractionDigits: 2})} (${((montoMasAlto/montoTotal)*100).toFixed(1)}%)
                    </div>
                    <div class="progress mt-1" style="height: 6px;">
                        <div class="progress-bar" style="width: ${(montoMasAlto/montoTotal)*100}%; background: ${tipoInfo.color}"></div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="text-muted mb-3">Distribución por Tipo</h6>
                    ${Object.keys(gastosPorTipo).map(tipo => {
                        const info = getTipoGastoInfo(tipo);
                        const porcentaje = (gastosPorTipo[tipo].monto / montoTotal) * 100;
                        return `
                                                                                                            <div class="mb-2">
                                                                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                                                                    <small>${info.emoji} ${info.nombre}</small>
                                                                                                                    <small><strong>${porcentaje.toFixed(1)}%</strong></small>
                                                                                                                </div>
                                                                                                                <div class="progress" style="height: 4px;">
                                                                                                                    <div class="progress-bar" style="width: ${porcentaje}%; background: ${info.color}"></div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        `;
                    }).join('')}
                </div>
            `;
        }

        // Función para generar gráfica de gastos
        function generarGraficaGastos(gastos) {
            const canvas = document.getElementById('graficaGastos');
            const ctx = canvas.getContext('2d');

            if (gastos.length === 0) {
                // Limpiar canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#6c757d';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos para mostrar', canvas.width / 2, canvas.height / 2);
                return;
            }

            // Agrupar gastos por tipo
            const gastosPorTipo = {};
            gastos.forEach(gasto => {
                if (!gastosPorTipo[gasto.tipo]) {
                    gastosPorTipo[gasto.tipo] = 0;
                }
                gastosPorTipo[gasto.tipo] += parseFloat(gasto.monto);
            });

            // Preparar datos para Chart.js
            const labels = [];
            const data = [];
            const backgroundColors = [];

            Object.keys(gastosPorTipo).forEach(tipo => {
                const tipoInfo = getTipoGastoInfo(tipo);
                labels.push(`${tipoInfo.emoji} ${tipoInfo.nombre}`);
                data.push(gastosPorTipo[tipo]);
                backgroundColors.push(tipoInfo.color);
            });

            // Destruir gráfica anterior si existe
            if (graficaGastosInstance) {
                graficaGastosInstance.destroy();
            }

            // Crear nueva gráfica
            graficaGastosInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverBorderWidth: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: $${context.parsed.toLocaleString('es-ES', {minimumFractionDigits: 2})} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        }


        // Hacer funciones disponibles globalmente
        window.abrirModalVerTodosGastos = abrirModalVerTodosGastos;
        window.limpiarFiltrosGastos = limpiarFiltrosGastos;

        // =============================================
        // GESTIÓN DEL MODAL DE SERVICIOS
        // =============================================

        // Variable para almacenar todos los servicios
        let todosLosServicios = [];

        // Función para abrir el modal de todos los servicios
        function abrirModalVerTodosServicios() {
            const modalElement = document.getElementById('modalVerTodosServicios');
            if (!modalElement) {
                console.error('Modal modalVerTodosServicios no encontrado');
                return;
            }

            try {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                // Cargar servicios cuando se abre el modal
                cargarTodosLosServicios();

            } catch (error) {
                console.error('Error al abrir modal:', error);
            }
        }

        // Función para cargar todos los servicios
        async function cargarTodosLosServicios() {
            try {
                const response = await fetch('/admin/servicios/all');
                const data = await response.json();

                if (data.success) {
                    todosLosServicios = data.servicios;
                    mostrarServicios(todosLosServicios);
                    actualizarEstadisticasServicios(data.estadisticas);
                } else {
                    throw new Error(data.message || 'Error al cargar servicios');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('tablaServicios').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error al cargar los servicios
                        </td>
                    </tr>
                `;
            }
        }

        // Función para mostrar servicios en la tabla
        function mostrarServicios(servicios) {
            const tbody = document.getElementById('tablaServicios');

            if (servicios.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            <i class="fas fa-inbox"></i> No hay servicios que mostrar
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = servicios.map(servicio => `
                <tr>
                    <td>
                        <strong>${servicio.nombre}</strong>
                        <br>
                        <small class="text-muted">${servicio.descripcion}</small>
                    </td>
                    <td>${servicio.categoria_formatted}</td>
                    <td>$${parseFloat(servicio.precio).toFixed(2)}</td>
                    <td>${servicio.duracion_min} min</td>
                    <td>
                        <span class="badge ${servicio.activo ? 'bg-success' : 'bg-secondary'}">
                            ${servicio.estado_formatted}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" onclick="editarServicioDesdeModal(${servicio.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-${servicio.activo ? 'warning' : 'success'} btn-sm" 
                                onclick="toggleEstadoServicio(${servicio.id}, ${servicio.activo})" 
                                title="${servicio.activo ? 'Desactivar' : 'Activar'}">
                                <i class="fas fa-toggle-${servicio.activo ? 'on' : 'off'}"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="eliminarServicioDesdeModal(${servicio.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Función para filtrar servicios
        function filtrarServicios() {
            const busqueda = document.getElementById('buscarServicio').value.toLowerCase();
            const categoria = document.getElementById('filtroCategoria').value;

            let serviciosFiltrados = todosLosServicios.filter(servicio => {
                const coincideBusqueda = servicio.nombre.toLowerCase().includes(busqueda) ||
                    servicio.descripcion.toLowerCase().includes(busqueda);
                const coincideCategoria = !categoria || servicio.categoria === categoria;

                return coincideBusqueda && coincideCategoria;
            });

            mostrarServicios(serviciosFiltrados);
        }

        // Función para actualizar estadísticas
        function actualizarEstadisticasServicios(estadisticas) {
            document.getElementById('estadisticasServicios').innerHTML = `
                <strong>Total:</strong> ${estadisticas.total} servicios | 
                <strong>Activos:</strong> ${estadisticas.activos} | 
                <strong>Inactivos:</strong> ${estadisticas.inactivos}
            `;
        }

        // Función para crear servicio desde el modal principal
        function nuevoServicioModal() {
            // NO cerrar el modal principal, mantenerlo abierto
            // Simplemente abrir el modal de crear servicio encima
            nuevoServicio();

            // Marcar que venimos del modal principal
            document.getElementById('servicioModal').setAttribute('data-parent-modal', 'modalVerTodosServicios');
        }

        // Función para editar servicio desde el modal principal
        function editarServicioDesdeModal(servicioId) {
            // Usar la función existente de editar
            editarServicio(servicioId);

            // Marcar que venimos del modal principal
            setTimeout(() => {
                document.getElementById('servicioModal').setAttribute('data-parent-modal',
                    'modalVerTodosServicios');
            }, 100);
        }

        // Función para cambiar estado del servicio
        async function toggleEstadoServicio(servicioId, estadoActual) {
            try {
                const response = await fetch(`/admin/servicios/${servicioId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Estado actualizado',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Recargar servicios
                    cargarTodosLosServicios();
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cambiar el estado del servicio', 'error');
            }
        }

        // Función para eliminar servicio desde el modal
        function eliminarServicioDesdeModal(servicioId) {
            confirmarEliminarServicio(servicioId);
            // No necesitamos setTimeout aquí, la función eliminarServicio ya maneja la recarga
        }

        // Hacer funciones disponibles globalmente
        window.abrirModalVerTodosServicios = abrirModalVerTodosServicios;
        window.filtrarServicios = filtrarServicios;
        window.nuevoServicioModal = nuevoServicioModal;
        window.editarServicioDesdeModal = editarServicioDesdeModal;
        window.toggleEstadoServicio = toggleEstadoServicio;
        window.eliminarServicioDesdeModal = eliminarServicioDesdeModal;
    </script>

    <script>
        // Horarios - bloque en Dashboard (inyección dinámica para mantener estilos y orden)
        (function() {
            const container = document.querySelector('.dashboard-container');
            if (!container) return;

            const cardHtml = `
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="card-header-icon icon-container">
                                <i class="fas fa-clock"></i>
                            </div>
                            Horarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="card-header-actions" style="display:flex; justify-content:space-between; margin-bottom:20px;">
                            <h3 style="color: var(--text-primary); margin: 0; max-width: 50%;">Gestión de bloques horarios</h3>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary btn-sm" id="btnAgregarHorario">
                                    <i class="fas fa-plus"></i> Agregar horario
                                </button>
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Activo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="horariosTableBody">
                                    <tr><td colspan="5" style="text-align:center; padding:20px; color:#666;">Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="horariosEmptyState" style="display:none; text-align:center; margin-top:15px;">
                            <p style="color:#666;">No hay horarios configurados.</p>
                            <button type="button" class="btn btn-primary btn-sm" id="btnAgregarHorarioEmpty">Agregar el primero</button>
                        </div>
                    </div>
                </div>
            `;

            // Insert after "Días No Laborables" card if exists, else append at end
            const anchorCard = document.querySelector('i.fa-calendar-day')?.closest('.card');
            if (anchorCard && anchorCard.nextSibling) {
                anchorCard.parentNode.insertBefore(document.createRange().createContextualFragment(cardHtml), anchorCard
                    .nextSibling);
            } else {
                container.insertAdjacentHTML('beforeend', cardHtml);
            }

            // State
            let horarios = [];
            const tableBody = document.getElementById('horariosTableBody');
            const emptyState = document.getElementById('horariosEmptyState');
            const btnAgregarHorario = document.getElementById('btnAgregarHorario');
            const btnAgregarHorarioEmpty = document.getElementById('btnAgregarHorarioEmpty');

            function nombreDia(n) {
                const dias = {
                    1: 'Lunes',
                    2: 'Martes',
                    3: 'Miércoles',
                    4: 'Jueves',
                    5: 'Viernes',
                    6: 'Sábado'
                };
                return dias[n] || n;
            }

            function renderTabla() {
                if (!horarios || horarios.length === 0) {
                    tableBody.innerHTML = '';
                    emptyState.style.display = 'block';
                    return;
                }
                emptyState.style.display = 'none';
                tableBody.innerHTML = horarios.map(h => `
                    <tr>
                        <td data-label="Día">${nombreDia(h.dia_semana)}</td>
                        <td data-label="Inicio">${h.hora_inicio}</td>
                        <td data-label="Fin">${h.hora_fin}</td>
                        <td data-label="Activo">
                            <span class="badge ${h.activo ? 'badge-success' : 'badge-danger'}">${h.activo ? 'Sí' : 'No'}</span>
                        </td>
                        <td data-label="Acciones">
                            <div class="table-actions">
                                <button class="table-btn btn-edit" title="Editar" data-action="edit" data-id="${h.id}"><i class="fas fa-edit"></i></button>
                                <button class="table-btn ${h.activo ? 'btn-danger' : 'btn-success'}" title="${h.activo ? 'Desactivar' : 'Activar'}" data-action="toggle" data-id="${h.id}">
                                    <i class="fas ${h.activo ? 'fa-ban' : 'fa-check'}"></i>
                                </button>
                                <button class="table-btn btn-delete" title="Eliminar" data-action="delete" data-id="${h.id}"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }

            async function cargarHorarios() {
                try {
                    const res = await fetch('{{ route('admin.horarios.index') }}', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    horarios = (json && json.data) ? json.data : [];
                    renderTabla();
                } catch (e) {
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'error',
                        title: 'Error al cargar horarios'
                    });
                }
            }

            function clearHorarioErrors() {
                ['horario_dia', 'horario_inicio', 'horario_fin'].forEach(id => {
                    const el = document.getElementById(id);
                    el?.classList.remove('is-invalid');
                });
                ['error_horario_dia', 'error_horario_inicio', 'error_horario_fin'].forEach(id => {
                    const e = document.getElementById(id);
                    if (e) e.innerHTML = '';
                });
            }

            function abrirModalCrearHorario() {
                const form = document.getElementById('horarioForm');
                if (!form) return;
                form.reset();
                clearHorarioErrors();
                document.getElementById('horario_id').value = '';
                document.getElementById('horario_dia').value = '';
                document.getElementById('horario_activo').value = '1';
                const sw = document.getElementById('horario_activo_switch');
                if (sw) sw.checked = true;
                const modal = new bootstrap.Modal(document.getElementById('horarioCRUDModal'));
                document.getElementById('horarioCRUDModalTitle').innerHTML =
                    '<i class="fas fa-clock me-2"></i> Agregar Horario';
                modal.show();
                setTimeout(() => document.getElementById('horario_dia')?.focus(), 100);
            }

            async function abrirModalEditarHorario(id) {
                try {
                    const res = await fetch(`/admin/horarios/${id}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const {
                        data
                    } = await res.json();
                    const form = document.getElementById('horarioForm');
                    if (!form) return;
                    document.getElementById('horario_id').value = data.id;
                    document.getElementById('horario_dia').value = data.dia_semana;
                    document.getElementById('horario_inicio').value = data.hora_inicio;
                    document.getElementById('horario_fin').value = data.hora_fin;
                    const sw = document.getElementById('horario_activo_switch');
                    if (sw) sw.checked = !!data.activo;
                    document.getElementById('horario_activo').value = data.activo ? '1' : '0';
                    const modal = new bootstrap.Modal(document.getElementById('horarioCRUDModal'));
                    document.getElementById('horarioCRUDModalTitle').innerHTML =
                        '<i class="fas fa-edit me-2"></i> Editar Horario';
                    modal.show();
                } catch (e) {
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'error',
                        title: 'No se pudo cargar el horario'
                    });
                }
            }

            async function toggleHorario(id) {
                try {
                    const res = await fetch(`/admin/horarios/${id}/toggle`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                    });
                    const {
                        data,
                        message
                    } = await res.json();
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'success',
                        title: message || 'Actualizado'
                    });
                    // Update in memory
                    horarios = horarios.map(h => h.id === data.id ? data : h);
                    renderTabla();
                } catch (e) {
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'error',
                        title: 'No se pudo actualizar'
                    });
                }
            }

            async function eliminarHorario(id) {
                const confirm = await Swal.fire({
                    title: '¿Eliminar?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar'
                });
                if (!confirm.isConfirmed) return;
                try {
                    const res = await fetch(`/admin/horarios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    if (json && json.success) {
                        if (typeof Toast !== 'undefined') Toast.fire({
                            icon: 'success',
                            title: json.message || 'Eliminado'
                        });
                        horarios = horarios.filter(h => h.id !== id);
                        renderTabla();
                    } else {
                        if (typeof Toast !== 'undefined') Toast.fire({
                            icon: 'error',
                            title: 'No se pudo eliminar'
                        });
                    }
                } catch (e) {
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'error',
                        title: 'No se pudo eliminar'
                    });
                }
            }

            // Confirmación consistente para eliminar horario
            async function confirmarEliminarHorario(id) {
                const confirm = await Swal.fire({
                    title: '¿Eliminar horario?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, Eliminar',
                    cancelButtonText: 'No, Mantener',
                    reverseButtons: true,
                });
                if (!confirm.isConfirmed) return;
                try {
                    const res = await fetch(`/admin/horarios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    if (json && json.success) {
                        if (typeof Toast !== 'undefined') Toast.fire({
                            icon: 'success',
                            title: json.message || 'Eliminado'
                        });
                        horarios = horarios.filter(h => h.id !== id);
                        renderTabla();
                    } else {
                        if (typeof Toast !== 'undefined') Toast.fire({
                            icon: 'error',
                            title: 'No se pudo eliminar'
                        });
                    }
                } catch (e) {
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'error',
                        title: 'No se pudo eliminar'
                    });
                }
            }

            // Delegación de eventos en tabla
            tableBody.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-action]');
                if (!btn) return;
                const id = parseInt(btn.getAttribute('data-id'));
                const action = btn.getAttribute('data-action');
                if (action === 'edit') return abrirModalEditarHorario(id);
                if (action === 'toggle') return toggleHorario(id);
                if (action === 'delete') return confirmarEliminarHorario(id);
            });

            // Abrir crear desde botones
            btnAgregarHorario?.addEventListener('click', abrirModalCrearHorario);
            btnAgregarHorarioEmpty?.addEventListener('click', abrirModalCrearHorario);

            // Manejo submit del modal existente
            const horarioForm = document.getElementById('horarioForm');
            const btnGuardarHorario = document.getElementById('btnGuardarHorario');

            function applyErrors(err) {
                clearHorarioErrors();
                if (err && err.errors) {
                    if (err.errors.dia_semana) {
                        document.getElementById('horario_dia')?.classList.add('is-invalid');
                        document.getElementById('error_horario_dia').innerHTML = err.errors.dia_semana[0];
                    }
                    if (err.errors.hora_inicio) {
                        document.getElementById('horario_inicio')?.classList.add('is-invalid');
                        document.getElementById('error_horario_inicio').innerHTML = err.errors.hora_inicio[0];
                    }
                    if (err.errors.hora_fin) {
                        document.getElementById('horario_fin')?.classList.add('is-invalid');
                        document.getElementById('error_horario_fin').innerHTML = err.errors.hora_fin[0];
                    }
                }
            }

            function buildPayload() {
                return {
                    dia_semana: document.getElementById('horario_dia').value,
                    hora_inicio: document.getElementById('horario_inicio').value,
                    hora_fin: document.getElementById('horario_fin').value,
                    activo: document.getElementById('horario_activo').value,
                };
            }

            // Submit via botón footer
            btnGuardarHorario?.addEventListener('click', async () => {
                horarioForm?.dispatchEvent(new Event('submit'));
            });

            // Sincronizar switch con valor oculto
            document.getElementById('horario_activo_switch')?.addEventListener('change', (e) => {
                document.getElementById('horario_activo').value = e.target.checked ? '1' : '0';
            });

            horarioForm?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const id = document.getElementById('horario_id').value;
                const payload = buildPayload();
                // Validación simple cliente
                if (!payload.dia_semana || !payload.hora_inicio || !payload.hora_fin) {
                    applyErrors({
                        errors: {
                            dia_semana: !payload.dia_semana ? ['Seleccione un día'] : [],
                            hora_inicio: !payload.hora_inicio ? ['Ingrese hora de inicio'] : [],
                            hora_fin: !payload.hora_fin ? ['Ingrese hora de fin'] : []
                        }
                    });
                    return;
                }
                if (payload.hora_inicio >= payload.hora_fin) {
                    applyErrors({
                        errors: {
                            hora_fin: ['La hora de fin debe ser mayor a la hora de inicio']
                        }
                    });
                    return;
                }
                try {
                    const res = await fetch(id ? `/admin/horarios/${id}` : '/admin/horarios', {
                        method: id ? 'PUT' : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    if (!res.ok) {
                        const err = await res.json();
                        if (err && err.errors) {
                            applyErrors(err);
                            return;
                        }
                        throw new Error('Error de servidor');
                    }
                    const {
                        data,
                        message
                    } = await res.json();
                    const modalEl = document.getElementById('horarioCRUDModal');
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                    if (typeof Toast !== 'undefined') Toast.fire({
                        icon: 'success',
                        title: message || 'Guardado'
                    });
                    // Actualizar en memoria
                    if (id) {
                        horarios = horarios.map(h => h.id === data.id ? data : h);
                    } else {
                        horarios.push(data);
                        horarios.sort((a, b) => a.dia_semana - b.dia_semana || (a.hora_inicio > b
                            .hora_inicio ? 1 : -1));
                    }
                    renderTabla();
                } catch (e) {
                    Swal.fire('Error', 'No se pudo guardar', 'error');
                }
            });

            // Cargar data al iniciar
            cargarHorarios();
        })();

        // ==============================================
        // FUNCIONES PARA MODALES DE NAVEGACIÓN
        // ==============================================

        // Abrir Modal de Citas Admin con diseño mejorado
        function abrirModalCitasAdmin() {
            document.getElementById('citasAdminModal').style.display = 'flex';
            const content = document.getElementById('citasAdminContent');

            content.innerHTML = `
                <iframe
                    id="citasIframe"
                    src="{{ route('admin.citasadmin.index') }}"
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals allow-popups allow-downloads"
                    style="width: 100%; height: 100%; border: none; opacity: 0; transition: opacity 0.3s ease;"
                    onload="
                        this.style.opacity='1';
                        try {
                            const iframeDoc = this.contentDocument || this.contentWindow.document;

                            // Ocultar resumen de estadísticas
                            const allCards = iframeDoc.querySelectorAll('.card');
                            allCards.forEach(card => {
                                const cardHeader = card.querySelector('.card-header');
                                if (cardHeader) {
                                    const headerText = cardHeader.textContent;
                                    if (headerText.includes('Resumen de Citas') ||
                                        headerText.includes('Total de Citas') ||
                                        headerText.includes('Desglose por Estado')) {
                                        card.style.display = 'none';
                                    }
                                }
                            });

                            // Ocultar botones de volver/regresar al dashboard
                            const allElements = iframeDoc.querySelectorAll('a, button');
                            allElements.forEach(element => {
                                const text = element.textContent.toLowerCase();
                                const href = element.getAttribute('href') || '';
                                if (text.includes('volver') || text.includes('regresar') ||
                                    text.includes('dashboard') || href.includes('/admin/dashboard')) {
                                    element.style.display = 'none';
                                }
                            });

                            // Ajustar diseño para vista más compacta
                            const container = iframeDoc.querySelector('.container, .container-fluid');
                            if (container) {
                                container.style.padding = '10px 15px';
                                container.style.maxWidth = '100%';
                            }

                            // Reducir padding de cards
                            iframeDoc.querySelectorAll('.card-body').forEach(card => {
                                card.style.padding = '0.8rem';
                            });

                            // Reducir márgenes
                            iframeDoc.querySelectorAll('.mb-4, .mb-3').forEach(elem => {
                                elem.style.marginBottom = '0.5rem';
                            });

                            // IMPORTANTE: mantener los modales internos por encima de sus backdrops
                            const ensureModalStacking = () => {
                                iframeDoc.querySelectorAll('.modal').forEach(modal => {
                                    modal.style.zIndex = '1200';
                                });

                                iframeDoc.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                                    backdrop.style.zIndex = '1190';
                                });

                                iframeDoc.querySelectorAll('.swal2-container').forEach(alert => {
                                    alert.style.zIndex = '1300';
                                });

                                iframeDoc.querySelectorAll('.swal2-backdrop').forEach(backdrop => {
                                    backdrop.style.zIndex = '1290';
                                });
                            };

                            ensureModalStacking();

                            const observer = new MutationObserver((mutations) => {
                                let needsUpdate = false;

                                mutations.forEach((mutation) => {
                                    if (mutation.type === 'attributes' && mutation.target.classList &&
                                        (mutation.target.classList.contains('modal') || mutation.target.classList.contains('swal2-container'))) {
                                        needsUpdate = true;
                                    }

                                    mutation.addedNodes.forEach((node) => {
                                        if (node.nodeType === 1 && node.classList &&
                                            (node.classList.contains('modal') ||
                                                node.classList.contains('modal-backdrop') ||
                                                node.classList.contains('swal2-container') ||
                                                node.classList.contains('swal2-backdrop'))) {
                                            needsUpdate = true;
                                        }
                                    });
                                });

                                if (needsUpdate) {
                                    ensureModalStacking();
                                }
                            });

                            observer.observe(iframeDoc.body, {
                                childList: true,
                                subtree: true,
                                attributes: true,
                                attributeFilter: ['class']
                            });
                        } catch(e) {
                            console.log('No se pudo modificar iframe:', e);
                        }
                    "
                ></iframe>
            `;
        }

        // Abrir Modal de Bitácora
        function abrirModalBitacora() {
            document.getElementById('bitacoraModal').style.display = 'flex';
            const content = document.getElementById('bitacoraContent');

            content.innerHTML = `
                <iframe
                    id="bitacoraIframe"
                    src="{{ route('admin.bitacora.index') }}"
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
                                    text.includes('dashboard') || href.includes('/admin/dashboard')) {
                                    element.style.display = 'none';
                                }
                            });

                            // Ajustar diseño para vista más compacta
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

                            // IMPORTANTE: Interceptar modales internos para ajustar z-index
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

        // Abrir Modal de Configuración
        function abrirModalConfiguracion() {
            document.getElementById('configuracionModal').style.display = 'flex';
            const content = document.getElementById('configuracionContent');

            content.innerHTML = `
                <iframe
                    id="configuracionIframe"
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
                                    text.includes('dashboard') || href.includes('/admin/dashboard')) {
                                    element.style.display = 'none';
                                }
                            });

                            // Ajustar diseño para vista más compacta
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

                            // IMPORTANTE: Interceptar modales internos para ajustar z-index
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

        // ==============================================
        // MODAL DE REPORTES COMPLETOS
        // ==============================================

        let reporteCharts = {
            ingresos: null,
            citas: null,
            servicios: null,
            pagos: null
        };

        function abrirModalReportes() {
            document.getElementById('reportesModal').style.display = 'flex';

            // Establecer año actual
            const anoActual = new Date().getFullYear();
            document.getElementById('reporteAno').value = anoActual;
            document.getElementById('reporteMes').value = 0;

            // Cargar datos iniciales
            setTimeout(() => {
                actualizarReportes();
            }, 100);
        }

        async function actualizarReportes() {
            const ano = document.getElementById('reporteAno').value;
            const mes = parseInt(document.getElementById('reporteMes').value);

            try {
                // Obtener datos del servidor
                const response = await fetch(`{{ route('admin.dashboard.data') }}`);
                const data = await response.json();

                if (!data.stats) {
                    throw new Error('No se pudieron cargar los datos');
                }

                // Actualizar estadísticas rápidas
                actualizarEstadisticasReportes(data.stats, mes);

                // Actualizar gráficos
                actualizarGraficosReportes(data.stats, mes);

                // Actualizar tabla de top servicios
                actualizarTopServicios(data.stats.servicios_populares_data || {});

            } catch (error) {
                console.error('Error al cargar reportes:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar reportes'
                });
            }
        }

        function actualizarEstadisticasReportes(stats, mes) {
            // Calcular totales según el mes seleccionado
            let ingresoTotal = 0;
            let citasCompletadas = 0;
            let citasCanceladas = 0;
            let serviciosTotal = 0;

            if (mes === 0) {
                // Todos los meses
                ingresoTotal = stats.ingresos_por_mes ? stats.ingresos_por_mes.reduce((a, b) => a + b, 0) : 0;
                citasCompletadas = stats.citas_por_mes && stats.citas_por_mes.completadas ?
                    stats.citas_por_mes.completadas.reduce((a, b) => a + b, 0) : 0;
                citasCanceladas = stats.citas_por_mes && stats.citas_por_mes.canceladas ?
                    stats.citas_por_mes.canceladas.reduce((a, b) => a + b, 0) : 0;
            } else {
                // Mes específico (índice del array es mes-1)
                ingresoTotal = stats.ingresos_por_mes && stats.ingresos_por_mes[mes-1] ? stats.ingresos_por_mes[mes-1] : 0;
                citasCompletadas = stats.citas_por_mes && stats.citas_por_mes.completadas && stats.citas_por_mes.completadas[mes-1] ?
                    stats.citas_por_mes.completadas[mes-1] : 0;
                citasCanceladas = stats.citas_por_mes && stats.citas_por_mes.canceladas && stats.citas_por_mes.canceladas[mes-1] ?
                    stats.citas_por_mes.canceladas[mes-1] : 0;
            }

            // Calcular servicios total (estimado como citas completadas * promedio de servicios por cita)
            serviciosTotal = Math.round(citasCompletadas * 1.5);

            // Calcular tasa de cancelación
            const totalCitas = citasCompletadas + citasCanceladas;
            const tasaCancelacion = totalCitas > 0 ? ((citasCanceladas / totalCitas) * 100).toFixed(1) : 0;

            // Actualizar UI
            document.getElementById('reporteIngresoTotal').textContent = '$' + ingresoTotal.toFixed(2);
            document.getElementById('reporteCitasCompletadas').textContent = citasCompletadas;
            document.getElementById('reporteServiciosTotal').textContent = serviciosTotal;
            document.getElementById('reporteTasaCancelacion').textContent = tasaCancelacion + '%';
        }

        function actualizarGraficosReportes(stats, mes) {
            // Destruir gráficos anteriores
            Object.keys(reporteCharts).forEach(key => {
                if (reporteCharts[key]) {
                    reporteCharts[key].destroy();
                }
            });

            // Gráfico de Ingresos
            const ctxIngresos = document.getElementById('reporteIngresosChart').getContext('2d');
            reporteCharts.ingresos = new Chart(ctxIngresos, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ingresos',
                        data: stats.ingresos_por_mes || Array(12).fill(0),
                        backgroundColor: 'rgba(46, 125, 50, 0.2)',
                        borderColor: 'rgba(46, 125, 50, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: context => '$' + context.raw.toLocaleString()
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '$' + value
                            }
                        }
                    }
                }
            });

            // Gráfico de Citas por Estado
            const citasData = stats.citas_por_mes || { completadas: Array(12).fill(0), canceladas: Array(12).fill(0) };
            const ctxCitas = document.getElementById('reporteCitasChart').getContext('2d');
            reporteCharts.citas = new Chart(ctxCitas, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [
                        {
                            label: 'Completadas',
                            data: citasData.completadas || Array(12).fill(0),
                            backgroundColor: 'rgba(25, 118, 210, 0.7)',
                            borderRadius: 6
                        },
                        {
                            label: 'Canceladas',
                            data: citasData.canceladas || Array(12).fill(0),
                            backgroundColor: 'rgba(198, 40, 40, 0.7)',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico de Servicios Populares
            const serviciosData = stats.servicios_populares_data || {};
            const ctxServicios = document.getElementById('reporteServiciosChart').getContext('2d');
            reporteCharts.servicios = new Chart(ctxServicios, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(serviciosData),
                    datasets: [{
                        data: Object.values(serviciosData),
                        backgroundColor: [
                            'rgba(46, 125, 50, 0.8)',
                            'rgba(25, 118, 210, 0.8)',
                            'rgba(245, 124, 0, 0.8)',
                            'rgba(156, 39, 176, 0.8)',
                            'rgba(229, 57, 53, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });

            // Gráfico de Métodos de Pago (simulado)
            const ctxPagos = document.getElementById('reportePagosChart').getContext('2d');
            reporteCharts.pagos = new Chart(ctxPagos, {
                type: 'pie',
                data: {
                    labels: ['Efectivo', 'Tarjeta', 'Transferencia'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: [
                            'rgba(76, 175, 80, 0.8)',
                            'rgba(33, 150, 243, 0.8)',
                            'rgba(255, 152, 0, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }

        function actualizarTopServicios(serviciosData) {
            const tbody = document.getElementById('topServiciosTable');

            if (Object.keys(serviciosData).length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding: 40px;">No hay datos disponibles</td></tr>';
                return;
            }

            // Convertir objeto a array y ordenar
            const serviciosArray = Object.entries(serviciosData).map(([nombre, veces]) => ({
                nombre,
                veces
            })).sort((a, b) => b.veces - a.veces).slice(0, 10);

            // Generar HTML
            let html = '';
            serviciosArray.forEach((servicio, index) => {
                const ingresos = servicio.veces * 25; // Estimado $25 por servicio
                const promedio = ingresos / servicio.veces;

                html += `
                    <tr>
                        <td style="font-weight: 600;">${index + 1}</td>
                        <td>${servicio.nombre}</td>
                        <td>${servicio.veces}</td>
                        <td style="color: #2e7d32; font-weight: 600;">$${ingresos.toFixed(2)}</td>
                        <td>$${promedio.toFixed(2)}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // ==============================================
        // MODAL DE GESTIÓN DE USUARIOS
        // ==============================================

        function abrirModalUsuarios() {
            document.getElementById('usuariosModal').style.display = 'flex';
            const content = document.getElementById('usuariosContent');

            content.innerHTML = `
                <iframe
                    id="usuariosIframe"
                    src="{{ route('admin.usuarios.index') }}"
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
                                    text.includes('dashboard') || href.includes('/admin/dashboard')) {
                                    element.style.display = 'none';
                                }
                            });

                            // Ajustar diseño para vista más compacta
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

                            // IMPORTANTE: Interceptar clicks en modales internos para ajustar z-index
                            const observer = new MutationObserver((mutations) => {
                                mutations.forEach((mutation) => {
                                    mutation.addedNodes.forEach((node) => {
                                        if (node.nodeType === 1 && node.classList &&
                                            (node.classList.contains('modal') || node.classList.contains('swal2-container'))) {
                                            // Ajustar z-index de modales internos
                                            node.style.zIndex = '1100';

                                            // Si es un backdrop, también ajustarlo
                                            const backdrop = iframeDoc.querySelector('.modal-backdrop, .swal2-backdrop');
                                            if (backdrop) {
                                                backdrop.style.zIndex = '1090';
                                            }
                                        }
                                    });
                                });
                            });

                            observer.observe(iframeDoc.body, {
                                childList: true,
                                subtree: true
                            });
                        } catch(e) {
                            console.log('No se pudo modificar iframe:', e);
                        }
                    "
                ></iframe>
            `;
        }
    </script>

    <!-- Modal para Gestión de Citas -->
    <div id="citasAdminModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 85%; max-width: 1400px; height: 85vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div style="padding: 12px 20px; background: linear-gradient(135deg, #2e7d32 0%, #00695c 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-calendar-alt" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Gestión de Citas
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModal('citasAdminModal')" style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'" onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>
            <!-- Contenido del modal -->
            <div id="citasAdminContent" style="flex: 1; overflow: hidden; background: #f5f5f5;">
                <div class="text-center" style="padding: 60px 20px;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 20px; border: 4px solid var(--primary); border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="color: #6c757d; font-size: 0.95rem;">Cargando contenido...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Bitácora -->
    <div id="bitacoraModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 85%; max-width: 1400px; height: 85vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div style="padding: 12px 20px; background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-book" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Bitácora del Sistema
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModal('bitacoraModal')" style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'" onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>
            <!-- Contenido del modal -->
            <div id="bitacoraContent" style="flex: 1; overflow: hidden; background: #f5f5f5;">
                <div class="text-center" style="padding: 60px 20px;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 20px; border: 4px solid #1976d2; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="color: #6c757d; font-size: 0.95rem;">Cargando contenido...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Configuración -->
    <div id="configuracionModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 85%; max-width: 1400px; height: 85vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div style="padding: 12px 20px; background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-cog" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Configuración del Sistema
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModal('configuracionModal')" style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'" onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>
            <!-- Contenido del modal -->
            <div id="configuracionContent" style="flex: 1; overflow: hidden; background: #f5f5f5;">
                <div class="text-center" style="padding: 60px 20px;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 20px; border: 4px solid #f57c00; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="color: #6c757d; font-size: 0.95rem;">Cargando contenido...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reportes Completos -->
    <div id="reportesModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 90%; max-width: 1600px; height: 90vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div style="padding: 12px 20px; background: linear-gradient(135deg, #6a1b9a 0%, #8e24aa 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-bar" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Reportes Completos del Sistema
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModal('reportesModal')" style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'" onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>

            <!-- Contenido del modal -->
            <div id="reportesContent" style="flex: 1; overflow-y: auto; padding: 25px; background: #f5f5f5;">
                <!-- Selector de Período -->
                <div style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 0.9rem;">Año:</label>
                            <select id="reporteAno" class="form-control" style="width: 120px; padding: 8px 12px; border-radius: 8px;">
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 0.9rem;">Mes:</label>
                            <select id="reporteMes" class="form-control" style="width: 150px; padding: 8px 12px; border-radius: 8px;">
                                <option value="0">Todos los meses</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button onclick="actualizarReportes()" class="btn btn-primary" style="margin-top: 24px; padding: 8px 20px; border-radius: 8px;">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <!-- Grid de Estadísticas Rápidas -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                    <!-- Ingresos Totales -->
                    <div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-dollar-sign" style="font-size: 2rem; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 0.85rem; opacity: 0.95; font-weight: 500;">Ingresos Totales</div>
                                <div id="reporteIngresoTotal" style="font-size: 1.8rem; font-weight: 700; margin-top: 5px;">$0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Citas Completadas -->
                    <div style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-check-circle" style="font-size: 2rem; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 0.85rem; opacity: 0.95; font-weight: 500;">Citas Completadas</div>
                                <div id="reporteCitasCompletadas" style="font-size: 1.8rem; font-weight: 700; margin-top: 5px;">0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Servicios Realizados -->
                    <div style="background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); color: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 12px rgba(245, 124, 0, 0.3);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-tools" style="font-size: 2rem; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 0.85rem; opacity: 0.95; font-weight: 500;">Servicios Realizados</div>
                                <div id="reporteServiciosTotal" style="font-size: 1.8rem; font-weight: 700; margin-top: 5px;">0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasa de Cancelación -->
                    <div style="background: linear-gradient(135deg, #c62828 0%, #8e0000 100%); color: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-ban" style="font-size: 2rem; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 0.85rem; opacity: 0.95; font-weight: 500;">Tasa Cancelación</div>
                                <div id="reporteTasaCancelacion" style="font-size: 1.8rem; font-weight: 700; margin-top: 5px;">0%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos en Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 20px;">
                    <!-- Gráfico de Ingresos Detallado -->
                    <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #2e7d32; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-chart-line"></i> Ingresos por Mes
                        </h3>
                        <div style="height: 300px;">
                            <canvas id="reporteIngresosChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Citas -->
                    <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #1976d2; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-calendar-check"></i> Citas por Estado
                        </h3>
                        <div style="height: 300px;">
                            <canvas id="reporteCitasChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Servicios Populares -->
                    <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #f57c00; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-star"></i> Servicios Más Solicitados
                        </h3>
                        <div style="height: 300px;">
                            <canvas id="reporteServiciosChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Métodos de Pago -->
                    <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #6a1b9a; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-credit-card"></i> Métodos de Pago
                        </h3>
                        <div style="height: 300px;">
                            <canvas id="reportePagosChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Top Servicios -->
                <div style="background: white; border-radius: 10px; padding: 20px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-trophy"></i> Top 10 Servicios
                    </h3>
                    <div style="overflow-x: auto;">
                        <table class="admin-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Servicio</th>
                                    <th>Veces Solicitado</th>
                                    <th>Ingresos Generados</th>
                                    <th>Promedio por Servicio</th>
                                </tr>
                            </thead>
                            <tbody id="topServiciosTable">
                                <tr>
                                    <td colspan="5" class="text-center" style="padding: 40px;">Cargando datos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Gestión de Usuarios -->
    <div id="usuariosModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 85%; max-width: 1400px; height: 85vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
            <!-- Header compacto -->
            <div style="padding: 12px 20px; background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-users" style="font-size: 1.2rem; color: white;"></i>
                    <h2 style="margin: 0; color: white; font-size: 1.2rem; font-weight: 600;">
                        Gestión de Usuarios
                    </h2>
                </div>
                <span class="close-modal" onclick="closeModal('usuariosModal')" style="font-size: 24px; cursor: pointer; color: white; line-height: 1; transition: all 0.2s ease; opacity: 0.9; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.opacity='1'" onmouseout="this.style.background='transparent'; this.style.opacity='0.9'">&times;</span>
            </div>
            <!-- Contenido del modal -->
            <div id="usuariosContent" style="flex: 1; overflow: hidden; background: #f5f5f5;">
                <div class="text-center" style="padding: 60px 20px;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 20px; border: 4px solid #0288d1; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="color: #6c757d; font-size: 0.95rem;">Cargando contenido...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
