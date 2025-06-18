<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Empleado - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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
        }

        /* Contenedor principal ajustado */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
            min-height: calc(100vh - 200px);
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
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
            background: var(--gradient-primary) !important;
            color: white !important;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: none !important;
        }

        .btn-profile:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }

        .btn i {
            transition: all 0.3s ease;
            color: inherit !important;
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

        /* Asegurar que los iconos sean visibles siempre */
        .btn-outline i {
            color: var(--primary) !important;
        }

        .btn-outline:hover i {
            color: white !important;
        }



        .btn-profile i {
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
            justify-content: center;
            color: white;
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

        .status-pendiente {
            background: rgba(251, 191, 36, 0.1);
            color: #92400e;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }

        .status-en-proceso {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .status-finalizado {
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.2);
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
            color: white;
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
        }

        .modal-content {
            background: white;
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-xl);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            margin: 20px 0;
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

        /* Ajustes para iconos */
        .fa-user-tie {
            color: white !important;
        }

        .btn-outline i,
        .btn-primary i,
        .btn-success i {
            color: white !important;
        }

        .appointment-actions .btn-outline i {
            color: var(--primary) !important;
        }

        .appointment-actions .btn-outline:hover i {
            color: white !important;
        }

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
                    <a href="{{ route('empleado.citas') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-day"></i> Ver Agenda
                    </a>
                    <a href="{{ route('configuracion.index') }}" class="btn btn-profile">
                        <i class="fas fa-cog"></i> Configurar Cuenta
                    </a>
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
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            Citas para Hoy
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        @if (isset($citas_hoy) && count($citas_hoy) > 0)
                            @foreach ($citas_hoy as $cita)
                                <div class="appointment-card">
                                    <h3>
                                        <i class="fas fa-user"></i>
                                        {{ $cita->usuario?->nombre ?? 'Cliente no especificado' }}
                                        <span class="status-badge status-{{ $cita->estado }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </h3>
                                    <p>
                                        <i class="fas fa-car"></i>
                                        {{ $cita->vehiculo?->marca ?? 'Marca no especificada' }}
                                        {{ $cita->vehiculo?->modelo ?? 'Modelo no especificado' }}
                                        @if ($cita->vehiculo)
                                            - {{ $cita->vehiculo->placa ?? 'Sin placa' }}
                                        @endif
                                    </p>
                                    <p><i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</p>

                                    <div style="margin: 10px 0;">
                                        @foreach ($cita->servicios as $servicio)
                                            <span class="service-tag">{{ $servicio->nombre }}
                                                (${{ number_format($servicio->pivot->precio ?? $servicio->precio, 2) }})
                                            </span>
                                        @endforeach
                                    </div>

                                    @if ($cita->observaciones_cliente)
                                        <p><i class="fas fa-comment"></i> <strong>Observaciones:</strong>
                                            {{ $cita->observaciones_cliente }}</p>
                                    @endif

                                    <div class="appointment-actions">
                                        @if ($cita->estado == 'pendiente')
                                            <button onclick="cambiarEstadoCita({{ $cita->id }}, 'en_proceso')"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-play"></i> Iniciar
                                            </button>
                                        @elseif ($cita->estado == 'en_proceso')
                                            <button onclick="mostrarModalFinalizar({{ $cita->id }})"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Finalizar
                                            </button>
                                            <button onclick="mostrarModalObservaciones({{ $cita->id }})"
                                                class="btn btn-sm btn-outline">
                                                <i class="fas fa-edit"></i> Observaciones
                                            </button>
                                        @endif

                                        <button onclick="verDetalleCita({{ $cita->id }})"
                                            class="btn btn-sm btn-outline">
                                            <i class="fas fa-eye"></i> Detalles
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-calendar-times"
                                    style="font-size: 2rem; color: #166088; margin-bottom: 10px;"></i>
                                <h3>No hay citas programadas para hoy</h3>
                                <p>Revisa el calendario para ver futuras citas</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historial Reciente -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-history"></i>
                            </div>
                            Historial Reciente
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if (isset($historial) && count($historial) > 0)
                            @foreach ($historial as $cita)
                                <div class="service-history-item">
                                    <div class="service-icon">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div class="service-details">
                                        <h4>{{ $cita->usuario?->nombre ?? 'Cliente no especificado' }}</h4>
                                        <p><i class="fas fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d M Y - h:i A') }}</p>
                                        <p>
                                            <i class="fas fa-car"></i>
                                            {{ $cita->vehiculo?->marca ?? 'Marca no especificada' }}
                                            {{ $cita->vehiculo?->modelo ?? '' }}
                                        </p>
                                        @if ($cita->observaciones_empleado)
                                            <p><i class="fas fa-comment"></i>
                                                {{ Str::limit($cita->observaciones_empleado, 50) }}</p>
                                        @endif
                                    </div>
                                    <div class="service-price">
                                        ${{ number_format($cita->servicios->sum('pivot.precio') ?? 0, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-history"
                                    style="font-size: 2rem; color: #166088; margin-bottom: 10px;"></i>
                                <h3>No hay historial reciente</h3>
                                <p>Los servicios que completes aparecerán aquí</p>
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
                            <button class="quick-action-btn">
                                <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Nueva Cita</span>
                            </button>
                            <button class="quick-action-btn">
                                <i class="fas fa-car" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Registrar Vehículo</span>
                            </button>
                            <button class="quick-action-btn">
                                <i class="fas fa-file-invoice" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Generar Recibo</span>
                            </button>
                            <button class="quick-action-btn">
                                <i class="fas fa-question" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <span>Ayuda</span>
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
                            <div>
                                <h3>{{ Auth::user()->nombre ?? 'Empleado' }}</h3>
                                <p><i class="fas fa-envelope"></i> {{ Auth::user()->email ?? 'No especificado' }}</p>
                                <p><i class="fas fa-id-badge"></i> Rol: Empleado</p>
                                <p><i class="fas fa-calendar"></i> Miembro desde
                                    {{ Auth::user()->created_at->format('M Y') }}</p>
                            </div>

                            <button onclick="openEditModal()" class="btn btn-outline"
                                style="margin-top: 15px; width: 100%;">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>
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
    <div id="finalizarCitaModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeFinalizarModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i> Finalizar Servicio
            </h2>
            <form id="finalizarCitaForm">
                @csrf
                <input type="hidden" id="cita_id_finalizar" name="cita_id">

                <div class="form-group">
                    <label for="observaciones_finalizar">Observaciones:</label>
                    <textarea id="observaciones_finalizar" name="observaciones" rows="4" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" class="form-control">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>

                <div id="efectivoFields">
                    <div class="form-group">
                        <label for="monto_recibido">Monto Recibido ($):</label>
                        <input type="number" step="0.01" id="monto_recibido" name="monto_recibido"
                            class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-check"></i> Confirmar Finalización
                </button>
            </form>
        </div>
    </div>

    <div id="observacionesModal" class="modal">
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

    <script>
        // Configuración global de SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Funciones para modales
        function mostrarModalFinalizar(citaId) {
            document.getElementById('cita_id_finalizar').value = citaId;
            document.getElementById('finalizarCitaModal').style.display = 'flex';
        }

        function closeFinalizarModal() {
            document.getElementById('finalizarCitaModal').style.display = 'none';
        }

        function mostrarModalObservaciones(citaId) {
            document.getElementById('cita_id_observaciones').value = citaId;
            document.getElementById('observacionesModal').style.display = 'flex';
        }

        function closeObservacionesModal() {
            document.getElementById('observacionesModal').style.display = 'none';
        }

        function verDetalleCita(citaId) {
            // Simulación de datos - en una aplicación real harías una petición AJAX
            const detalleContent = `
                <h2 style="margin-bottom: 15px;">
                    <i class="fas fa-calendar-check"></i> Detalle de Cita
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                            <i class="fas fa-user"></i> Información del Cliente
                        </h3>
                        <p><strong>Nombre:</strong> Juan Pérez</p>
                        <p><strong>Teléfono:</strong> 5555-1234</p>
                        <p><strong>Email:</strong> juan@example.com</p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                            <i class="fas fa-car"></i> Información del Vehículo
                        </h3>
                        <p><strong>Marca/Modelo:</strong> Toyota Corolla</p>
                        <p><strong>Año:</strong> 2020</p>
                        <p><strong>Color:</strong> Rojo</p>
                        <p><strong>Placa:</strong> P123456</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                        <i class="fas fa-concierge-bell"></i> Servicios
                    </h3>
                    <ul style="list-style: none;">
                        <li style="padding: 6px 0; border-bottom: 1px solid #eee;">
                            <i class="fas fa-check" style="color: #166088;"></i> Lavado Completo - $25.00
                        </li>
                        <li style="padding: 6px 0; border-bottom: 1px solid #eee;">
                            <i class="fas fa-check" style="color: #166088;"></i> Aspirado Interior - $15.00
                        </li>
                    </ul>
                    <div style="text-align: right; margin-top: 10px; font-weight: bold;">
                        Total: $40.00
                    </div>
                </div>
                
                <div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">
                        <i class="fas fa-comment"></i> Observaciones
                    </h3>
                    <p style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        El cliente solicita especial atención a las manchas en los asientos traseros.
                    </p>
                </div>
            `;

            document.getElementById('detalleCitaContent').innerHTML = detalleContent;
            document.getElementById('detalleCitaModal').style.display = 'flex';
        }

        function closeDetalleModal() {
            document.getElementById('detalleCitaModal').style.display = 'none';
        }

        // Manejar cambio de método de pago
        document.getElementById('metodo_pago').addEventListener('change', function() {
            const efectivoFields = document.getElementById('efectivoFields');
            efectivoFields.style.display = this.value === 'efectivo' ? 'block' : 'none';
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

            const formData = new FormData(this);

            fetch('/empleado/citas/finalizar', {
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
                        title: 'Error al finalizar la cita'
                    });
                });
        });

        // Formulario para guardar observaciones
        document.getElementById('observacionesForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/empleado/citas/observaciones', {
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
                document.getElementById('finalizarCitaModal').style.display = 'none';
                document.getElementById('observacionesModal').style.display = 'none';
                document.getElementById('detalleCitaModal').style.display = 'none';
            }
        });
    </script>
</body>

</html>
