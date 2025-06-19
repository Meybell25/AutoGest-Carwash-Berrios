<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - AutoGest Carwash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Nueva paleta de colores moderna */
            --primary-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            --secondary-gradient: linear-gradient(45deg, #fd79a8 0%, #e84393 100%);
            --success-gradient: linear-gradient(45deg, #00b894 0%, #55efc4 100%);
            --warning-gradient: linear-gradient(45deg, #fdcb6e 0%, #e17055 100%);
            --danger-gradient: linear-gradient(45deg, #d63031 0%, #74b9ff 100%);
            --info-gradient: linear-gradient(45deg, #a29bfe 0%, #6c5ce7 100%);
            --dark-gradient: linear-gradient(135deg, #2d3436 0%, #636e72 100%);
            --light-gradient: linear-gradient(135deg, #ddd6fe 0%, #f3e8ff 100%);

            /* Colores base */
            --coral-primary: #ff6b6b;
            --coral-secondary: #fd79a8;
            --emerald-green: #00b894;
            --sunset-orange: #fdcb6e;
            --deep-purple: #6c5ce7;
            --slate-gray: #2d3436;
            --soft-lavender: #ddd6fe;

            /* Fondos y superficies */
            --glass-bg: rgba(255, 255, 255, 0.92);
            --glass-bg-dark: rgba(45, 52, 54, 0.95);
            --glass-border: rgba(255, 107, 107, 0.15);
            --surface-elevated: rgba(255, 255, 255, 0.98);

            /* Texto */
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --text-accent: #ff6b6b;
            --text-light: rgba(255, 255, 255, 0.95);

            /* Sombras modernas */
            --shadow-soft: 0 8px 25px rgba(255, 107, 107, 0.08);
            --shadow-medium: 0 15px 35px rgba(255, 107, 107, 0.12);
            --shadow-strong: 0 25px 50px rgba(255, 107, 107, 0.15);
            --shadow-colored: 0 8px 32px rgba(108, 92, 231, 0.1);

            /* Efectos glassmorphism */
            --blur-intensity: blur(20px);
            --backdrop-filter: saturate(180%) blur(20px);

            /* Transiciones suaves */
            --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --transition-spring: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 35%, #ff6b6b 70%, #fd79a8 100%);
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
                radial-gradient(circle at 20% 80%, rgba(255, 107, 107, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(108, 92, 231, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 184, 148, 0.05) 0%, transparent 50%);
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

        /* Header mejorado */
        .header {
            background: var(--glass-bg);
            backdrop-filter: var(--backdrop-filter);
            padding: 30px 35px;
            border-radius: 24px;
            margin-bottom: 35px;
            box-shadow: var(--shadow-medium);
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

        .welcome-section h1 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 18px;
            letter-spacing: -0.5px;
        }

        .welcome-icon {
            background: var(--primary-gradient);
            width: 55px;
            height: 55px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            box-shadow: var(--shadow-soft);
            transform: rotate(-5deg);
            transition: var(--transition-spring);
        }

        .welcome-icon:hover {
            transform: rotate(0deg) scale(1.1);
        }

        .welcome-section p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .welcome-stats {
            display: flex;
            gap: 25px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .welcome-stat {
            background: var(--surface-elevated);
            padding: 15px 20px;
            border-radius: 15px;
            text-align: center;
            min-width: 90px;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(255, 107, 107, 0.1);
            transition: var(--transition-smooth);
        }

        .welcome-stat:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .welcome-stat .number {
            font-size: 1.4rem;
            font-weight: 800;
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-stat .label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Botones modernos */
        .btn {
            padding: 14px 24px;
            border: none;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-smooth);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-strong);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--coral-primary);
            color: var(--coral-primary);
            backdrop-filter: var(--blur-intensity);
        }

        .btn-outline:hover {
            background: var(--coral-primary);
            color: white;
            transform: translateY(-3px);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        /* Layout mejorado */
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

        /* Cards con glassmorphism */
        .card {
            background: var(--glass-bg);
            backdrop-filter: var(--backdrop-filter);
            border-radius: 24px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--glass-border);
            transition: var(--transition-smooth);
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
            transition: var(--transition-smooth);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-strong);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            padding: 25px 30px 0;
            border-bottom: 2px solid rgba(255, 107, 107, 0.1);
            margin-bottom: 25px;
        }

        .card-header h2 {
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.5rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
            letter-spacing: -0.3px;
        }

        .card-header .icon {
            background: var(--secondary-gradient);
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            box-shadow: var(--shadow-soft);
        }

        .card-body {
            padding: 0 30px 30px;
        }

        /* Stats Cards con efectos modernos */
        .admin-stat-card {
            background: var(--surface-elevated);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-soft);
            border-left: 5px solid;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .admin-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .admin-stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-strong);
        }

        .stat-card-primary {
            border-left-color: var(--coral-primary);
        }

        .stat-card-success {
            border-left-color: var(--emerald-green);
        }

        .stat-card-warning {
            border-left-color: var(--sunset-orange);
        }

        .stat-card-danger {
            border-left-color: var(--deep-purple);
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
            box-shadow: var(--shadow-soft);
        }

        .icon-primary {
            background: var(--primary-gradient);
        }

        .icon-success {
            background: var(--success-gradient);
        }

        .icon-warning {
            background: var(--warning-gradient);
        }

        .icon-danger {
            background: var(--info-gradient);
        }

        /* Tablas modernas */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
        }

        .admin-table th {
            background: var(--light-gradient);
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .admin-table td {
            padding: 18px 15px;
            border-bottom: 1px solid rgba(255, 107, 107, 0.08);
            background: var(--surface-elevated);
        }

        .admin-table tr:hover td {
            background: rgba(255, 107, 107, 0.03);
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
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-soft);
        }

        .table-btn:hover {
            transform: scale(1.15) rotate(5deg);
        }

        .btn-view {
            background: var(--info-gradient);
            color: white;
        }

        .btn-edit {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-delete {
            background: var(--danger-gradient);
            color: white;
        }

        /* Badges modernos */
        .badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-soft);
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

        /* Tabs modernos */
        .tab-container {
            margin-top: 25px;
        }

        .tab-buttons {
            display: flex;
            border-bottom: 2px solid rgba(255, 107, 107, 0.1);
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
            transition: var(--transition-smooth);
        }

        .tab-button:hover {
            background: rgba(255, 107, 107, 0.05);
            color: var(--coral-primary);
        }

        .tab-button.active {
            background: var(--coral-primary);
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

        /* Charts */
        .chart-container {
            position: relative;
            height: 320px;
            margin-bottom: 25px;
            border-radius: 15px;
            overflow: hidden;
        }

        /* Service items modernos */
        .service-history-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid rgba(255, 107, 107, 0.1);
            border-radius: 18px;
            margin-bottom: 15px;
            transition: var(--transition-smooth);
            background: var(--surface-elevated);
        }

        .service-history-item:hover {
            border-color: var(--coral-primary);
            background: rgba(255, 107, 107, 0.03);
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
            box-shadow: var(--shadow-soft);
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

        /* Notificaciones modernas */
        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            background: var(--surface-elevated);
            transition: var(--transition-smooth);
        }

        .notification-item:hover {
            transform: translateX(8px);
            box-shadow: var(--shadow-medium);
        }

        .notification-item.unread {
            background: linear-gradient(45deg, rgba(255, 107, 107, 0.08), rgba(253, 121, 168, 0.08));
            border-left: 4px solid var(--coral-primary);
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
            box-shadow: var(--shadow-soft);
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

        /* Modal moderno */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(45, 52, 54, 0.7);
            backdrop-filter: var(--blur-intensity);
        }

        .modal-content {
            background: var(--glass-bg);
            backdrop-filter: var(--backdrop-filter);
            margin: 5% auto;
            padding: 35px;
            border-radius: 25px;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-strong);
            border: 1px solid var(--glass-border);
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
            color: var(--coral-primary);
            float: right;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition-smooth);
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
            color: var(--coral-primary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 107, 107, 0.1);
            border-radius: 12px;
            font-size: 16px;
            background: var(--surface-elevated);
            transition: var(--transition-smooth);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--coral-primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            outline: none;
        }

        /* Footer moderno */
        .footer {
            background: var(--glass-bg-dark);
            backdrop-filter: var(--backdrop-filter);
            border: 1px solid rgba(255, 107, 107, 0.2);
            border-radius: 30px;
            margin-top: 40px;
            box-shadow: var(--shadow-strong);
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
        }

        .footer-content {
            padding: 50px 35px;
            text-align: center;
            color: var(--text-light);
        }

        .footer-brand h3 {
            font-size: 32px;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
        }

        .footer-slogan {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            font-style: italic;
            margin-bottom: 30px;
        }

        .footer-info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 35px;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 500;
        }

        .info-item i {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            color: white;
            border-radius: 50%;
            font-size: 12px;
            box-shadow: var(--shadow-soft);
        }

        .location-link {
            color: inherit;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .location-link:hover {
            color: var(--coral-primary);
            text-decoration: underline;
        }

        .footer-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--coral-primary), transparent);
            margin: 25px 0;
            border-radius: 1px;
        }

        .footer-copyright {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Search y filtros */
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
            border: 2px solid rgba(255, 107, 107, 0.1);
            border-radius: 12px;
            font-size: 15px;
            background: var(--surface-elevated);
            transition: var(--transition-smooth);
        }

        .form-control:focus {
            border-color: var(--coral-primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            outline: none;
        }

        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }

        .page-link {
            padding: 10px 15px;
            border: 2px solid rgba(255, 107, 107, 0.2);
            border-radius: 10px;
            color: var(--coral-primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-smooth);
        }

        .page-link:hover,
        .page-link.active {
            background: var(--coral-primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--coral-primary);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        /* Responsive */
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
                border: 2px solid rgba(255, 107, 107, 0.1);
                border-radius: 15px;
                margin-bottom: 15px;
                padding: 15px;
                background: var(--surface-elevated);
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
                color: var(--coral-primary);
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
                gap: 20px;
                text-align: center;
            }

            .info-item {
                justify-content: center;
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

            .footer {
                border-radius: 20px 20px 0 0;
            }

            .footer-content {
                padding: 30px 20px;
            }

            .footer-brand h3 {
                font-size: 24px;
            }

            .footer-slogan {
                font-size: 14px;
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
                /* Evita zoom en iOS */
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

        /* Animaciones adicionales para mejor UX */
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

        /* Estados de carga y loading */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Scroll personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
        }

        /* Print styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }

            .header-actions,
            .btn,
            .table-actions,
            .footer {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .admin-table {
                font-size: 12px !important;
            }
        }

        /* Accesibilidad mejorada */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus styles para accesibilidad */
        .btn:focus,
        .form-control:focus,
        .table-btn:focus,
        .tab-button:focus {
            outline: 3px solid rgba(255, 107, 107, 0.5);
            outline-offset: 2px;
        }

        /* Mejoras de contraste para mejor legibilidad */
        @media (prefers-contrast: high) {
            .card {
                border: 2px solid var(--coral-primary);
            }

            .btn {
                border: 2px solid currentColor;
            }

            .badge {
                border: 1px solid rgba(0, 0, 0, 0.3);
            }
        }
    </style>
</head>

<body>
    <!-- Contenido del dashboard admin (igual que antes) -->
    <div class="dashboard-container">
        <!-- Header con bienvenida personalizada -->
        <div class="header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <div class="welcome-icon">
                            <i class="fa-solid fa-user-shield"></i>
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
                    <a href="{{ route('admin.citas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Nueva Cita
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="btn btn-success">
                        <i class="fas fa-chart-bar"></i>
                        Reportes
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

                <!-- Gráficos -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
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
                            <div class="icon">
                                <i class="fas fa-calendar-alt"></i>
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
                                                @foreach ($cita->servicios as $servicio)
                                                    <span class="badge badge-primary">{{ $servicio->nombre }}</span>
                                                @endforeach
                                            </td>
                                            <td data-label="Total">
                                                ${{ number_format($cita->servicios->sum('pivot.precio'), 2) }}</td>
                                            <td data-label="Estado">
                                                <span
                                                    class="badge badge-{{ $cita->estado == 'pendiente'
                                                        ? 'warning'
                                                        : ($cita->estado == 'en_proceso'
                                                            ? 'info'
                                                            : ($cita->estado == 'finalizada'
                                                                ? 'success'
                                                                : 'danger')) }}">
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
                <!-- Resumen de Usuarios -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            Resumen de Usuarios
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: #4ca1af;">
                                    {{ $stats['usuarios_totales'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Usuarios Totales</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
                                    {{ $stats['nuevos_clientes_mes'] ?? 0 }}</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">Nuevos (Mes)</div>
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: var(--text-primary);">
                                Distribución por Rol</h3>
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
                            <div class="icon">
                                <i class="fas fa-star"></i>
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
                            <div class="icon">
                                <i class="fas fa-bell"></i>
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
            <h2 style="color: #4facfe; margin-bottom: 20px;">
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
                        <input type="number" id="servicio_duracion" name="duracion" required class="form-control"
                            placeholder="30">
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

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <h3><i class="fas fa-car-wash"></i> AutoGest Carwash Berrios</h3>
                <p class="footer-slogan">✨ "Sistema de Administración Integral" ✨</p>
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
                        backgroundColor: 'rgba(76, 161, 175, 0.2)',
                        borderColor: 'rgba(76, 161, 175, 1)',
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
                        backgroundColor: 'rgba(67, 233, 123, 0.7)',
                        borderColor: 'rgba(67, 233, 123, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Citas Canceladas',
                        data: [5, 8, 6, 10, 7, 5, 4, 8, 10, 7, 9, 6],
                        backgroundColor: 'rgba(255, 117, 140, 0.7)',
                        borderColor: 'rgba(255, 117, 140, 1)',
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
                            'rgba(76, 161, 175, 0.7)',
                            'rgba(67, 233, 123, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(23, 162, 184, 0.7)',
                            'rgba(108, 117, 125, 0.7)'
                        ],
                        borderColor: [
                            'rgba(76, 161, 175, 1)',
                            'rgba(67, 233, 123, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(108, 117, 125, 1)'
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
                        data: [85, 10, 5],
                        backgroundColor: [
                            'rgba(76, 161, 175, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(220, 53, 69, 0.7)'
                        ],
                        borderColor: [
                            'rgba(76, 161, 175, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)'
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
                        }
                    }
                }
            });
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
                <h2 style="color: #4facfe; margin-bottom: 20px;">
                    <i class="fas fa-calendar-check"></i> Detalle de Cita #${citaId}
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                            <i class="fas fa-user"></i> Información del Cliente
                        </h3>
                        <p><strong>Nombre:</strong> Juan Pérez</p>
                        <p><strong>Teléfono:</strong> 5555-1234</p>
                        <p><strong>Email:</strong> juan@example.com</p>
                        <p><strong>Cliente desde:</strong> Ene 2023</p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </h3>
                        <p><strong>Marca/Modelo:</strong> Toyota Corolla</p>
                        <p><strong>Año:</strong> 2020</p>
                        <p><strong>Color:</strong> Rojo</p>
                        <p><strong>Placa:</strong> P123456</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
                        <i class="fas fa-concierge-bell"></i> Servicios Contratados
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f1f3f4;">
                                <th style="padding: 10px; text-align: left;">Servicio</th>
                                <th style="padding: 10px; text-align: right;">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">Lavado Completo</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">$25.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">Aspirado Interior</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">$15.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; font-weight: bold;">Total</td>
                                <td style="padding: 10px; font-weight: bold; text-align: right;">$40.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #4facfe;">
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
            document.getElementById('detalleCitaModal').style.display = 'block';
        }

        function editarCita(citaId) {
            // Simulación de formulario - en una aplicación real harías una petición AJAX
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
            document.getElementById('editarCitaModal').style.display = 'block';
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
            document.getElementById('servicioModal').style.display = 'block';
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
            document.getElementById('servicioModal').style.display = 'block';
        }

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
            }
        });
    </script>
</body>

</html>
