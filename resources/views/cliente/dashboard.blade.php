<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente - Carwash Berríos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            --success-gradient: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%);
            --warning-gradient: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #333;
            --text-secondary: #666;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
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
            background: var(--secondary-gradient);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
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

        .welcome-stat {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            padding: 10px 15px;
            border-radius: 10px;
            text-align: center;
            min-width: 80px;
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
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
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
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
            display: flex;
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
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
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
                padding: 15px;
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
        }

        @media (max-width: 480px) {
            .card-header, .card-body {
                padding-left: 20px;
                padding-right: 20px;
            }

            .profile-stats {
                grid-template-columns: 1fr;
            }

            .welcome-stats {
                flex-direction: column;
                align-items: center;
            }

            .welcome-stat {
                width: 100%;
                max-width: 200px;
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

        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.2s; }
        .card:nth-child(4) { animation-delay: 0.3s; }

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
                            <i class="fas fa-hand-wave"></i>
                        </div>
                        ¡Hola, Meybell!
                    </h1>
                    <p>Bienvenida a tu panel de control personal</p>
                    <div class="welcome-stats">
                        <div class="welcome-stat">
                            <span class="number">15</span>
                            <span class="label">Servicios</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">$450</span>
                            <span class="label">Total Gastado</span>
                        </div>
                        <div class="welcome-stat">
                            <span class="number">2</span>
                            <span class="label">Pendientes</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i>
                        Nueva Cita
                    </a>
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-user-edit"></i>
                        Mi Perfil
                    </a>
                    <button class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </button>
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
                        <!-- Próxima cita destacada -->
                        <div class="next-appointment">
                            <div class="appointment-date-time">
                                <div class="date-badge">
                                    <span class="day">28</span>
                                    <span class="month">Jun</span>
                                </div>
                                <div class="time-info">
                                    <div class="time">2:00 PM</div>
                                    <div class="service">Lavado Premium + Encerado</div>
                                </div>
                                <span class="appointment-status status-confirmado">Confirmado</span>
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
                            <div class="appointment-date-time" style="padding: 15px; border-bottom: 1px solid #eee;">
                                <div class="date-badge" style="width: 60px; height: 60px; font-size: 0.8rem;">
                                    <span class="day">05</span>
                                    <span class="month">Jul</span>
                                </div>
                                <div class="time-info">
                                    <div class="time" style="font-size: 1.1rem;">10:30 AM</div>
                                    <div class="service">Lavado Rápido</div>
                                </div>
                                <span class="appointment-status status-pendiente">Pendiente</span>
                            </div>
                        </div>
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
                        <div class="service-history-item">
                            <div class="service-icon">
                                <i class="fas fa-car-wash"></i>
                            </div>
                            <div class="service-details">
                                <h4>Lavado Premium + Encerado</h4>
                                <p><i class="fas fa-calendar"></i> 15 Jun 2025 - 3:00 PM</p>
                                <p><i class="fas fa-car"></i> Honda Civic - ABC-1234</p>
                                <a href="#" class="repeat-service">
                                    <i class="fas fa-redo"></i> Volver a agendar
                                </a>
                            </div>
                            <div class="service-price">$35.00</div>
                        </div>

                        <div class="service-history-item">
                            <div class="service-icon">
                                <i class="fas fa-spray-can"></i>
                            </div>
                            <div class="service-details">
                                <h4>Lavado Completo</h4>
                                <p><i class="fas fa-calendar"></i> 08 Jun 2025 - 11:00 AM</p>
                                <p><i class="fas fa-car"></i> Toyota Corolla - XYZ-5678</p>
                                <a href="#" class="repeat-service">
                                    <i class="fas fa-redo"></i> Volver a agendar
                                </a>
                            </div>
                            <div class="service-price">$25.00</div>
                        </div>

                        <div class="service-history-item">
                            <div class="service-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <div class="service-details">
                                <h4>Lavado Rápido</h4>
                                <p><i class="fas fa-calendar"></i> 01 Jun 2025 - 9:30 AM</p>
                                <p><i class="fas fa-car"></i> Honda Civic - ABC-1234</p>
                                <a href="#" class="repeat-service">
                                    <i class="fas fa-redo"></i> Volver a agendar
                                </a>
                            </div>
                            <div class="service-price">$15.00</div>
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
                        <div class="services-grid">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-spray-can"></i>
                                </div>
                                <h3>Lavado Completo</h3>
                                <p class="description">Exterior e interior completo, aspirado y limpieza de tapicería</p>
                                <div class="price">$25.00</div>
                                <div class="duration">⏱️ 30-40 min</div>
                                <button class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Ahora
                                </button>
                            </div>

                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-car-wash"></i>
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
                                <h3>Meybell García</h3>
                                <p><i class="fas fa-envelope"></i> meybell@email.com</p>
                                <p><i class="fas fa-phone"></i> +503 7890-1234</p>
                                <p><i class="fas fa-calendar"></i> Cliente desde Mar 2024</p>
                            </div>
                            <div class="profile-stats">
                                <div class="profile-stat">
                                    <span class="number">15</span>
                                    <span class="label">Servicios</span>
                                </div>
                                <div class="profile-stat">
                                    <span class="number">4.9</span>
                                    <span class="label">Rating</span>
                                </div>
                            </div>
                            <button class="btn btn-outline" style="margin-top: 15px; width: 100%;">
                                <i class="fas fa-edit"></i>
                                Editar Perfil
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
                            <span style="background: #ff4757; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; margin-left: auto;">3</span>
                        </h2>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        <div class="notification-item unread">
                            <div class="notification-icon success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="notification-content">
                                <h4>Cita Confirmada</h4>
                                <p>Tu cita del 28 de Junio ha sido confirmada</p>
                            </div>
                            <div class="notification-time">Hace 2h</div>
                        </div>

                        <div class="notification-item unread">
                            <div class="notification-icon info">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="notification-content">
                                <h4>¡Promoción Especial!</h4>
                                <p>20% de descuento en lavado premium este fin de semana</p>
                            </div>
                            <div class="notification-time">Hace 5h</div>
                        </div>

                        <div class="notification-item unread">
                            <div class="notification-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="notification-content">
                                <h4>Recordatorio</h4>
                                <p>Tu cita es mañana a las 2:00 PM</p>
                            </div>
                            <div class="notification-time">Hace 1d</div>
                        </div>

                        <div class="notification-item read">
                            <div class="notification-icon info">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="notification-content">
                                <h4>Califica tu servicio</h4>
                                <p>¿Cómo estuvo tu último lavado premium?</p>
                            </div>
                            <div class="notification-time">Hace 3d</div>
                        </div>
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
                        <div class="service-history-item" style="margin-bottom: 15px;">
                            <div class="service-icon" style="background: var(--secondary-gradient);">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="service-details">
                                <h4>Honda Civic 2020</h4>
                                <p><i class="fas fa-palette"></i> Azul Marino</p>
                                <p><i class="fas fa-id-card"></i> ABC-1234</p>
                            </div>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>

                        <div class="service-history-item" style="margin-bottom: 15px;">
                            <div class="service-icon" style="background: var(--success-gradient);">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="service-details">
                                <h4>Toyota Corolla 2019</h4>
                                <p><i class="fas fa-palette"></i> Blanco Perla</p>
                                <p><i class="fas fa-id-card"></i> XYZ-5678</p>
                            </div>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>

                        <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">
                            <i class="fas fa-plus"></i>
                            Agregar Vehículo
                        </button>
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <div class="icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            Métodos de Pago
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 10px;">
                            <div style="background: linear-gradient(45deg, #1e3c72, #2a5298); width: 50px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                                VISA
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #333;">•••• •••• •••• 1234</div>
                                <div style="color: #666; font-size: 0.8rem;">Expira 12/26</div>
                            </div>
                            <div style="color: #4facfe;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                            <div style="background: linear-gradient(45deg, #eb001b, #f79e1b); width: 50px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                                MC
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #333;">•••• •••• •••• 5678</div>
                                <div style="color: #666; font-size: 0.8rem;">Expira 08/27</div>
                            </div>
                        </div>

                        <button class="btn btn-outline" style="width: 100%;">
                            <i class="fas fa-plus"></i>
                            Agregar Método de Pago
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Facturas/Recibos -->
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
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
                    <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 15px; transition: all 0.3s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <h4 style="color: #333; margin-bottom: 5px;">Factura #001-2025</h4>
                                <p style="color: #666; font-size: 0.9rem;">15 Jun 2025 - Lavado Premium</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: #4facfe; font-size: 1.1rem;">$35.00</div>
                                <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">PAGADO</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i>
                                Ver
                            </button>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i>
                                Descargar PDF
                            </button>
                        </div>
                    </div>

                    <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 15px; transition: all 0.3s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <h4 style="color: #333; margin-bottom: 5px;">Factura #002-2025</h4>
                                <p style="color: #666; font-size: 0.9rem;">08 Jun 2025 - Lavado Completo</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: #4facfe; font-size: 1.1rem;">$25.00</div>
                                <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">PAGADO</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i>
                                Ver
                            </button>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i>
                                Descargar PDF
                            </button>
                        </div>
                    </div>

                    <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 15px; transition: all 0.3s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <h4 style="color: #333; margin-bottom: 5px;">Factura #003-2025</h4>
                                <p style="color: #666; font-size: 0.9rem;">01 Jun 2025 - Lavado Rápido</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: #4facfe; font-size: 1.1rem;">$15.00</div>
                                <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">PAGADO</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i>
                                Ver
                            </button>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i>
                                Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-outline">
                        <i class="fas fa-history"></i>
                        Ver Todas las Facturas
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección de Reseñas -->
        <div class="card" style="margin-top: 30px;">
            <div class="card-header">
                <h2>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                    Mis Reseñas y Calificaciones
                </h2>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
                    <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; background: linear-gradient(45deg, #f8f9fa, #ffffff);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h4 style="color: #333; margin-bottom: 5px;">Lavado Premium</h4>
                                <p style="color: #666; font-size: 0.9rem;">15 Jun 2025</p>
                            </div>
                            <div style="display: flex; gap: 2px;">
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                            </div>
                        </div>
                        <p style="color: #333; font-style: italic; line-height: 1.5; margin-bottom: 10px;">"Excelente servicio, mi auto quedó como nuevo. El personal es muy profesional y atento."</p>
                        <div style="color: #4facfe; font-weight: 600; font-size: 0.9rem;">✓ Reseña verificada</div>
                    </div>

                    <div style="border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; background: linear-gradient(45deg, #f8f9fa, #ffffff);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h4 style="color: #333; margin-bottom: 5px;">Lavado Completo</h4>
                                <p style="color: #666; font-size: 0.9rem;">08 Jun 2025</p>
                            </div>
                            <div style="display: flex; gap: 2px;">
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                <i class="far fa-star" style="color: #ddd;"></i>
                            </div>
                        </div>
                        <p style="color: #333; font-style: italic; line-height: 1.5; margin-bottom: 10px;">"Muy buen servicio, rápido y eficiente. El interior quedó impecable."</p>
                        <div style="color: #4facfe; font-weight: 600; font-size: 0.9rem;">✓ Reseña verificada</div>
                    </div>
                </div>

                <!-- Servicios pendientes de calificar -->
                <div style="margin-top: 25px; padding: 20px; background: linear-gradient(45deg, #fff3cd, #fefefe); border-radius: 12px; border-left: 4px solid #ffc107;">
                    <h4 style="color: #856404; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-clock"></i>
                        Servicios Pendientes de Calificar
                    </h4>
                    <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px; border-radius: 8px;">
                        <div>
                            <h5 style="color: #333; margin-bottom: 5px;">Lavado Rápido</h5>
                            <p style="color: #666; font-size: 0.9rem;">01 Jun 2025</p>
                        </div>
                        <button class="btn btn-warning">
                            <i class="fas fa-star"></i>
                            Calificar Servicio
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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

            // Simulación de tiempo real para las notificaciones
            function updateNotificationTimes() {
                const times = document.querySelectorAll('.notification-time');
                // Aquí podrías actualizar los tiempos reales
            }

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
                                <div class="service-icon">
                                    <i class="fas fa-tint"></i>
                                </div>
                                <h3>Lavado Rápido</h3>
                                <p class="description">Limpieza exterior básica, ideal para mantenimiento diario</p>
                                <div class="price">$15.00</div>
                                <div class="duration">⏱️ 15-20 min</div>
                                <button class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                    Agendar Ahora
                                </button>
                            </div>

                            <div class="service-card">