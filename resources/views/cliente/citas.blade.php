<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas - Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
            --border-radius: 0.75rem;
            --border-radius-lg: 1rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #bbadfd, #5b21b6, #452383);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: var(--text-secondary);
            margin-top: 10px;
            font-size: 1.1rem;
        }

        /* Navegación entre vistas */
        .view-nav {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            padding: 10px;
            box-shadow: var(--shadow-soft);
            gap: 10px;
        }

        .nav-btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .nav-btn:not(.active) {
            background: transparent;
            color: var(--text-secondary);
        }

        .nav-btn:not(.active):hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--text-primary);
        }

        /* Botón flotante para nueva cita */
        .fab-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: var(--shadow-hover);
            transition: var(--transition);
            z-index: 1000;
        }

        .fab-button:hover {
            transform: scale(1.1);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .filters-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 25px;
            margin-bottom: 30px;
        }

        /* Contadores específicos para próximas citas */
        .counters-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: var(--border-radius);
        }

        .counter-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            min-width: 120px;
        }

        .counter-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .counter-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .counter-total .counter-value {
            color: #667eea;
        }

        .counter-pendiente .counter-value {
            color: #ef6c00;
        }

        .counter-confirmada .counter-value {
            color: #0277bd;
        }

        .counter-en_proceso .counter-value {
            color: #6a1b9a;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group select,
        .filter-group input {
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: var(--text-primary);
            border: 2px solid #e1e5e9;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #dc3545;
            color: #dc3545;
        }

        .btn-outline:hover {
            background: #dc3545;
            color: white;
        }

        .citas-grid {
            display: grid;
            gap: 20px;
        }

        .cita-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 25px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .cita-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 172, 254, 0.1));
            opacity: 0.1;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .cita-card:hover::before {
            width: 100%;
        }

        .cita-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        /* COLORES DE BORDE PARA PRÓXIMAS CITAS */
        .cita-card.pendiente {
            border-left: 5px solid #4facfe !important;
        }

        .cita-card.confirmada,
        .cita-card.confirmado {
            border-left: 5px solid #66bb6a !important;
            /* Verde por defecto */
        }

        .cita-card.en_proceso,
        .cita-card.en-proceso {
            border-left: 5px solid #1b5e20 !important;
        }

        /* Estilos para citas urgentes - 3 niveles */
        .cita-card.confirmada.urgent-soon,
        /* Menos de 24h - Rojo */
        .cita-card.confirmado.urgent-soon {
            border-left: 5px solid #dc3545 !important;
            background-color: rgba(255, 245, 245, 0.8) !important;
        }

        .cita-card.confirmada.urgent-close,
        /* 1-2 días - Naranja */
        .cita-card.confirmado.urgent-close {
            border-left: 5px solid #fd7e14 !important;
            background-color: rgba(255, 248, 240, 0.8) !important;
        }

        .cita-card.confirmada.coming-soon,
        /* 3-5 días - Amarillo */
        .cita-card.confirmado.coming-soon {
            border-left: 5px solid #ffc107 !important;
            background-color: rgba(255, 251, 240, 0.8) !important;
        }

        /* Animación de pulso solo para el badge (mejor que para toda la card) */
        @keyframes pulseBadge {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .cita-card.confirmada.urgent-soon .date-badge,
        .cita-card.confirmado.urgent-soon .date-badge {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            animation: pulseBadge 2s infinite;
        }

        /* Añadir estos estilos */
        .restriccion-alerta {
            background: #fff3cd;
            color: #856404;
            padding: 12px 15px;
            border-radius: 8px;
            margin: -15px 0 20px 0;
            border-left: 4px solid #ffeeba;
            display: flex;
            align-items: center;
            animation: fadeIn 0.3s ease;
            z-index: 10;
            position: relative;
        }

        .restriccion-alerta i {
            color: #ffc107;
            margin-right: 10px;
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

        .modal {
            z-index: 9999;
            /* MUY ALTO PARA ESTAR POR ENCIMA DE TODO */
        }

        .modal-content {
            z-index: 10000;
            /* ASEGURAR QUE ESTÉ POR ENCIMA */
        }

        /* AUMENTAR EL Z-INDEX DE SWEETALERT2 PARA QUE APAREZCA SOBRE EL MODAL */
        .swal2-container {
            z-index: 100000 !important;
            /* Aumentado a 100000 */
        }

        /* Estilos para campos deshabilitados */
        .form-group input:disabled,
        .form-group select:disabled {
            background-color: #f8f9fa !important;
            color: #666 !important;
            border-color: #ddd !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }

        .cita-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .cita-date-time {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .date-badge {
            color: white;
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: center;
            min-width: 80px;
            box-shadow: var(--shadow-soft);
            position: relative;
        }

        /* COLORES DE BADGE PARA PRÓXIMAS CITAS */
        .date-badge.pendiente {
            background: var(--secondary-gradient) !important;
        }

        .date-badge.confirmada,
        .date-badge.confirmado {
            background: linear-gradient(135deg, #81c784, #66bb6a) !important;
        }

        .date-badge.en_proceso,
        .date-badge.en-proceso {
            background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
        }

        .date-badge .day {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .date-badge .month {
            display: block;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
            opacity: 0.9;
        }

        .date-badge .days-remaining {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .time-info {
            flex: 1;
        }

        .time-info .time {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .time-info .duration {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .vehicle-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
        }

        .vehicle-info i {
            color: #667eea;
        }

        /* Indicador de tiempo restante */
        .time-remaining {
            font-size: 0.85rem;
            color: #667eea;
            font-weight: 600;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* ESTILOS DE BADGE PARA PRÓXIMAS CITAS */
        .status-pendiente {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2) !important;
            color: #ef6c00 !important;
            border: 1px solid #ffcc80 !important;
        }

        .status-pendiente:hover {
            background: linear-gradient(135deg, #ffe0b2, #ffcc80) !important;
        }

        .status-confirmada,
        .status-confirmado {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc) !important;
            color: #0277bd !important;
            border: 1px solid #81d4fa !important;
        }

        .status-confirmada:hover,
        .status-confirmado:hover {
            background: linear-gradient(135deg, #b3e5fc, #81d4fa) !important;
        }

        .status-en_proceso,
        .status-en-proceso {
            background: linear-gradient(135deg, #f1e6ff, #e1bee7) !important;
            color: #6a1b9a !important;
            border: 1px solid #ce93d8 !important;
        }

        .status-en_proceso:hover,
        .status-en-proceso:hover {
            background: linear-gradient(135deg, #e1bee7, #ce93d8) !important;
        }

        .cita-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        /* COLORES DE BORDE PARA SECCIONES SEGÚN ESTADO */
        .cita-card.pendiente .detail-section {
            border-left-color: #4facfe !important;
        }

        .cita-card.confirmada .detail-section,
        .cita-card.confirmado .detail-section {
            border-left-color: #66bb6a !important;
        }

        .cita-card.en_proceso .detail-section,
        .cita-card.en-proceso .detail-section {
            border-left-color: #1b5e20 !important;
        }

        .detail-section {
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: var(--border-radius);
        }

        .detail-section h4 {
            margin: 0 0 15px 0;
            color: var(--text-primary);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* COLORES DE BADGE PARA TODOS LOS ESTADOS DE URGENCIA */
        .date-badge.urgent-soon {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            animation: pulseBadge 2s infinite;
        }

        .date-badge.urgent-close {
            background: linear-gradient(135deg, #fd7e14, #e55100) !important;
        }

        .date-badge.coming-soon {
            background: linear-gradient(135deg, #ffc107, #ff8f00) !important;
            color: #333 !important;
        }

        /* BORDES IZQUIERDOS PARA DETAIL-SECTION SEGÚN URGENCIA */
        .detail-section.urgent-soon {
            border-left-color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05) !important;
        }

        .detail-section.urgent-close {
            border-left-color: #fd7e14 !important;
            background-color: rgba(253, 126, 20, 0.05) !important;
        }

        .detail-section.coming-soon {
            border-left-color: #ffc107 !important;
            background-color: rgba(255, 193, 7, 0.05) !important;
        }

        /* Asegurar que las clases de urgencia tengan prioridad */
        .cita-card.confirmada .detail-section.urgent-soon,
        .cita-card.confirmado .detail-section.urgent-soon {
            border-left-color: #dc3545 !important;
        }

        .cita-card.confirmada .detail-section.urgent-close,
        .cita-card.confirmado .detail-section.urgent-close {
            border-left-color: #fd7e14 !important;
        }

        .cita-card.confirmada .detail-section.coming-soon,
        .cita-card.confirmado .detail-section.coming-soon {
            border-left-color: #ffc107 !important;
        }

        /* Mantener los colores base para estados normales */
        .detail-section.pendiente {
            border-left: 4px solid #4facfe !important;
        }

        .detail-section.confirmada,
        .detail-section.confirmado {
            border-left: 4px solid #66bb6a !important;
        }

        .detail-section.en_proceso,
        .detail-section.en-proceso {
            border-left: 4px solid #1b5e20 !important;
        }

        /* Animación de pulso para badges urgentes */
        @keyframes pulseBadge {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .service-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .service-item:last-child {
            border-bottom: none;
        }

        .service-name {
            font-weight: 600;
            flex: 1;
        }

        .service-price {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .cita-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
        }

        .empty-state i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: var(--border-radius);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        /* Estilos mejorados para la paginación */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: var(--border-radius);
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination a:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
            color: white;
        }

        .pagination .active span {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .pagination .disabled span {
            opacity: 0.6;
            cursor: not-allowed;
            background: rgba(255, 255, 255, 0.3);
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            margin: 3% auto;
            padding: 30px;
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-hover);
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            z-index: 10000;
            position: relative;
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
            z-index: 10001;
            position: relative;
        }

        .close-modal:hover {
            color: #667eea;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .service-card {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            background: white;
        }

        .service-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        .service-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        }

        .service-card input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .service-card h4 {
            margin: 0 0 5px 0;
            color: var(--text-primary);
        }

        .service-card p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .service-card .description {
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .cita-actions .btn {
            position: relative;
            z-index: 10;
            pointer-events: auto;
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

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .cita-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .cita-details {
                grid-template-columns: 1fr;
            }

            .back-button {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
                display: inline-block;
            }

            .view-nav {
                flex-direction: column;
                gap: 5px;
            }

            .counters-container {
                flex-direction: column;
                gap: 10px;
            }

            .counter-item {
                flex-direction: row;
                justify-content: space-between;
                min-width: auto;
                padding: 10px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .counter-item:last-child {
                border-bottom: none;
            }

            .fab-button {
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                font-size: 1.3rem;
            }

            .modal-content {
                margin: 5% auto;
                padding: 20px;
                max-width: 95%;
            }

            #serviciosContainer {
                grid-template-columns: 1fr !important;
            }

            /* Paginación responsiva */
            .pagination {
                gap: 4px;
            }

            .pagination a,
            .pagination span {
                min-width: 32px;
                height: 32px;
                padding: 0 8px;
                font-size: 0.9rem;
            }

            @media (max-width: 480px) {
                .cita-actions {
                    flex-direction: column;
                }

                .cita-actions .btn {
                    width: 100%;
                    margin-bottom: 8px;
                    justify-content: center;
                }

                .service-card {
                    flex-direction: column;
                    text-align: center;
                }

                .filters-grid {
                    grid-template-columns: 1fr;
                }
            }
        }
    </style>
</head>

<body>
    <a href="{{ route('cliente.dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Volver al Dashboard
    </a>

    <!-- Botón flotante para nueva cita -->
    <button class="fab-button" onclick="openCitaModal()" title="Agendar Nueva Cita">
        <i class="fas fa-plus"></i>
    </button>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-clock"></i> Mis Citas</h1>
            <p>Gestiona tus citas pendientes, confirmadas y en proceso</p>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <!-- Contadores específicos para próximas citas -->
            <div class="counters-container">
                <div class="counter-item counter-total">
                    <span class="counter-value" id="total-counter">
                        {{ $citas->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso'])->count() }}
                    </span>
                    <span class="counter-label">Total Citas</span>
                </div>
                <div class="counter-item counter-pendiente">
                    <span class="counter-value"
                        id="pendiente-counter">{{ $citas->where('estado', 'pendiente')->count() }}</span>
                    <span class="counter-label">Pendientes</span>
                </div>
                <div class="counter-item counter-confirmada">
                    <span class="counter-value"
                        id="confirmada-counter">{{ $citas->where('estado', 'confirmada')->count() }}</span>
                    <span class="counter-label">Confirmadas</span>
                </div>
                <div class="counter-item counter-en_proceso">
                    <span class="counter-value"
                        id="en-proceso-counter">{{ $citas->where('estado', 'en_proceso')->count() }}</span>
                    <span class="counter-label">En Proceso</span>
                </div>
            </div>

            <form id="filtrosForm">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="estado">
                            <i class="fas fa-filter"></i> Estado
                        </label>
                        <select name="estado" id="estado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                Pendiente</option>
                            <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>
                                Confirmada</option>
                            <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>
                                En Proceso</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="fecha_desde">
                            <i class="fas fa-calendar-day"></i> Desde
                        </label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>

                    <div class="filter-group">
                        <label for="fecha_hasta">
                            <i class="fas fa-calendar-day"></i> Hasta
                        </label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>

                    <div class="filter-group">
                        <label for="vehiculo_id">
                            <i class="fas fa-car"></i> Vehículo
                        </label>
                        <select name="vehiculo_id" id="vehiculo_id">
                            <option value="">Todos los vehículos</option>
                            @foreach (auth()->user()->vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}"
                                    {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                    @if ($vehiculo->placa)
                                        - {{ $vehiculo->placa }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <button type="button" class="btn btn-primary" onclick="limpiarFiltros()">
                            <i class="fas fa-times"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de citas -->
        <div id="citas-container">
            @if ($citas->count() > 0)
                <div class="citas-grid">
                    @foreach ($citas->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso']) as $cita)
                        @php
                            if (
                                !in_array($cita->estado, ['pendiente', 'confirmada', 'en_proceso']) &&
                                !request('estado')
                            ) {
                                continue;
                            }

                            $isFuture = $cita->fecha_hora > now();
                            $daysDiff = $isFuture ? floor(now()->diffInDays($cita->fecha_hora, false)) : null;
                            $hoursRemaining = $isFuture ? floor(now()->diffInHours($cita->fecha_hora, false)) : null;

                            $cardClass = $cita->estado;
                            $badgeClass = $cita->estado;
                            $detailClass = $cita->estado;

                            // LÓGICA DE URGENCIA SOLO PARA CITAS CONFIRMADAS
                            if ($cita->estado == 'confirmada' && $isFuture) {
                                if ($hoursRemaining <= 24) {
                                    $cardClass .= ' urgent-soon';
                                    $badgeClass .= ' urgent-soon';
                                    $detailClass .= ' urgent-soon';
                                } elseif ($daysDiff >= 1 && $daysDiff <= 2) {
                                    $cardClass .= ' urgent-close';
                                    $badgeClass .= ' urgent-close';
                                    $detailClass .= ' urgent-close';
                                } elseif ($daysDiff >= 3 && $daysDiff <= 5) {
                                    $cardClass .= ' coming-soon';
                                    $badgeClass .= ' coming-soon';
                                    $detailClass .= ' coming-soon';
                                }
                            }

                            $timeRemaining = '';
                            if ($isFuture) {
                                if ($daysDiff == 0) {
                                    $timeRemaining = $hoursRemaining . ' hora' . ($hoursRemaining != 1 ? 's' : '');
                                } else {
                                    $timeRemaining = $daysDiff . ' día' . ($daysDiff != 1 ? 's' : '');
                                }
                            }
                        @endphp


                        <div class="cita-card {{ $cardClass }}" data-cita-id="{{ $cita->id }}">
                            <div class="cita-header">
                                <div class="cita-date-time">
                                    <!-- Aplicar la clase de badge corregida -->
                                    <div class="date-badge {{ $badgeClass }}">
                                        <span class="day">{{ $cita->fecha_hora->format('d') }}</span>
                                        <span class="month">{{ $cita->fecha_hora->format('M') }}</span>
                                        @if ($isFuture && $hoursRemaining <= 24 && $cita->estado == 'confirmada')
                                            <span class="days-remaining">!</span>
                                        @endif
                                    </div>
                                    <div class="time-info">
                                        <div class="time">{{ $cita->fecha_hora->format('h:i A') }}</div>
                                        <div class="duration">
                                            <i class="fas fa-clock"></i>
                                            Duración: {{ $cita->servicios->sum('duracion_min') }} min
                                        </div>
                                        @if ($timeRemaining)
                                            <div class="time-remaining">
                                                <i class="fas fa-hourglass-half"></i>
                                                En {{ $timeRemaining }}
                                            </div>
                                        @endif
                                        <div class="vehicle-info">
                                            <i class="fas fa-car"></i>
                                            {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}
                                            @if ($cita->vehiculo->placa)
                                                - {{ $cita->vehiculo->placa }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="status-badge status-{{ str_replace('_', '-', $cita->estado) }}">
                                    {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                </span>
                            </div>

                            <div class="cita-details">
                                <!-- Aplicar la clase de detalle corregida -->
                                <div class="detail-section {{ $detailClass }}">
                                    <h4><i class="fas fa-tools"></i> Servicios Programados</h4>
                                    <ul class="service-list">
                                        @foreach ($cita->servicios as $servicio)
                                            <li class="service-item">
                                                <span class="service-name">{{ $servicio->nombre }}</span>
                                                <span
                                                    class="service-price">${{ number_format($servicio->precio, 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div style="border-top: 2px solid #667eea; margin-top: 10px; padding-top: 10px;">
                                        <strong>Total a Pagar:
                                            ${{ number_format($cita->servicios->sum('precio'), 2) }}</strong>
                                    </div>
                                </div>

                                @if ($cita->observaciones)
                                    <!-- Aplicar la clase de detalle corregida -->
                                    <div class="detail-section {{ $detailClass }}">
                                        <h4><i class="fas fa-comment"></i> Observaciones</h4>
                                        <p>{{ $cita->observaciones }}</p>
                                    </div>
                                @endif

                                <!-- Información adicional según el estado -->
                                @if ($cita->estado == 'pendiente')
                                    <!-- Aplicar la clase de detalle corregida -->
                                    <div class="detail-section {{ $detailClass }}">
                                        <h4><i class="fas fa-info-circle"></i> Estado de la Cita</h4>
                                        <p>Tu cita está <strong>pendiente de confirmación</strong>. Recibirás una
                                            notificación cuando sea confirmada.</p>
                                    </div>
                                @elseif ($cita->estado == 'confirmada')
                                    <!-- Aplicar la clase de detalle corregida -->
                                    <div class="detail-section {{ $detailClass }}">
                                        <h4><i class="fas fa-check-circle"></i> Cita Confirmada</h4>
                                        <p>Tu cita ha sido <strong>confirmada</strong>. Por favor llega 10 minutos antes
                                            de la hora programada.</p>
                                        @if ($hoursRemaining <= 24)
                                            <p style="color: #dc3545; font-weight: 600;">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                ¡Cita próxima! Recuerda asistir puntualmente.
                                            </p>
                                        @endif
                                    </div>
                                @elseif ($cita->estado == 'en_proceso')
                                    <!-- Aplicar la clase de detalle corregida -->
                                    <div class="detail-section {{ $detailClass }}">
                                        <h4><i class="fas fa-cog"></i> En Proceso</h4>
                                        <p>Tu vehículo está siendo atendido. Te notificaremos cuando esté listo.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Acciones disponibles para citas pendientes y confirmadas -->
                            @if (in_array($cita->estado, ['pendiente', 'confirmada']))
                                <div class="cita-actions">
                                    <button class="btn btn-sm btn-warning" onclick="editCita({{ $cita->id }})">
                                        <i class="fas fa-edit"></i> Modificar
                                    </button>
                                    <button class="btn btn-sm btn-outline" onclick="cancelCita({{ $cita->id }})">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Paginación mejorada -->
                <div class="pagination-wrapper">
                    @if ($citas->hasPages())
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($citas->onFirstPage())
                                <li class="disabled" aria-disabled="true">
                                    <span><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $citas->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($citas->getUrlRange(1, $citas->lastPage()) as $page => $url)
                                @if ($page == $citas->currentPage())
                                    <li class="active" aria-current="page">
                                        <span>{{ $page }}</span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($citas->hasMorePages())
                                <li>
                                    <a href="{{ $citas->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="disabled" aria-disabled="true">
                                    <span><i class="fas fa-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-plus"></i>
                    <h3>No tienes citas programadas</h3>
                    <p>¿Necesitas agendar un servicio para tu vehículo?</p>
                    <button class="btn btn-primary" onclick="openCitaModal()">
                        <i class="fas fa-calendar-plus"></i> Agendar Nueva Cita
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para crear/editar cita -->
    <div id="createCitaModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeCitaModal()">&times;</span>
            <h2 style="color: #4facfe; margin-bottom: 20px;">
                <i class="fas fa-calendar-plus"></i> <span id="modalTitle">Nueva Cita</span>
            </h2>
            <!-- ALERTA DE RESTRICCIÓN DE 24H  -->
            <div id="restriccion24hAlert" class="restriccion-alerta" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <span>No puedes modificar fecha, hora o vehículo porque faltan menos de 24 horas para la cita. Solo
                    puedes cambiar servicios y observaciones.</span>
            </div>

            <form id="citaForm" method="POST" action="{{ route('cliente.citas.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!-- Campo oculto para ID de cita (solo en edición) -->
                <input type="hidden" id="form_cita_id" name="cita_id" value="">

                <!-- Selección de vehículo -->
                <div class="form-group">
                    <label for="modal_vehiculo_id">Vehículo: <span style="color: red;">*</span></label>
                    <select id="modal_vehiculo_id" name="vehiculo_id" required onchange="cargarServiciosPorTipo()">
                        <option value="">Seleccione un vehículo</option>
                        @foreach (auth()->user()->vehiculos as $vehiculo)
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración global de SweetAlert
        const swalWithBootstrapButtons = Swal.mixin({
            confirmButtonColor: '#4facfe',
            cancelButtonColor: '#6c757d',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline mr-2'
            },
            buttonsStyling: false
        });

        // Variables globales
        let horariosDisponibles = [];
        let todosServiciosDisponibles = [];
        let serviciosFiltrados = [];
        let diasNoLaborables = [];

        // Función para calcular horas restantes
        function calcularHorasRestantes(fechaHoraCita) {
            const ahora = new Date();
            const fechaCita = new Date(fechaHoraCita);
            return (fechaCita - ahora) / (1000 * 60 * 60); // Diferencia en horas
        }

        // Función para abrir modal de cita (MEJORADA)
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

                        // Ocultar alerta de restricción
                        document.getElementById('restriccion24hAlert').style.display = 'none';

                        // Habilitar todos los campos
                        document.getElementById('modal_vehiculo_id').disabled = false;
                        document.getElementById('fecha').disabled = false;
                        document.getElementById('hora').disabled = false;
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

                        // Establecer vehículo si se proporciona
                        if (vehiculoId) {
                            const vehiculoSelect = document.getElementById('modal_vehiculo_id');
                            if (vehiculoSelect) {
                                vehiculoSelect.value = vehiculoId;
                                await cargarServiciosPorTipo();
                            }
                        }

                        console.log('Modal abierto para CREAR nueva cita');
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

        // Función para cargar datos iniciales (MEJORADA)
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

        // Configuración del datepicker 
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

        // Función para formatear fecha bonita (ej: "Lunes, 25 de Junio")
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

            // Resetear selección
            document.getElementById('fecha').value = '';
            document.getElementById('hora').innerHTML = '<option value="">Seleccione una hora</option>';
        }

        // Función para cargar servicios según el tipo de vehículo seleccionado (MEJORADA)
        async function cargarServiciosPorTipo() {
            return new Promise(async (resolve, reject) => {
                try {
                    const vehiculoSelect = document.getElementById('modal_vehiculo_id');
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

        // Función renderServicios (MEJORADA)
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

        // Función para cancelar citas
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
                            credentials: 'same-origin'
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
                            let errorMsg = typeof error === 'string' ? error : (error.message ||
                                'Error al cancelar la cita');

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
            console.log('Editando cita ID:', citaId);

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
                setModalMode(true);

                await new Promise(resolve => setTimeout(resolve, 300));

                // 3. Configurar formulario
                const form = document.getElementById('citaForm');
                const vehiculoSelect = document.getElementById('modal_vehiculo_id');
                const fechaInput = document.getElementById('fecha');
                const horaSelect = document.getElementById('hora');
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


                const horasRestantes = calcularHorasRestantes(data.data.fecha_hora);
                if (horasRestantes < 24 && data.data.estado === 'confirmada') {
                    // Mostrar alerta
                    document.getElementById('restriccion24hAlert').style.display = 'block';

                    // ✅ BLOQUEO VISUAL SIN AFECTAR ENVÍO DE DATOS
                    fechaInput.readOnly = true;
                    horaSelect.readOnly = true;
                    vehiculoSelect.readOnly = true;

                    fechaInput.classList.add('campo-bloqueado');
                    horaSelect.classList.add('campo-bloqueado');
                    vehiculoSelect.classList.add('campo-bloqueado');

                } else {
                    document.getElementById('restriccion24hAlert').style.display = 'none';
                    fechaInput.readOnly = false;
                    horaSelect.readOnly = false;
                    vehiculoSelect.readOnly = false;

                    fechaInput.classList.remove('campo-bloqueado');
                    horaSelect.classList.remove('campo-bloqueado');
                    vehiculoSelect.classList.remove('campo-bloqueado');
                }

                // 5. Cargar servicios
                if (data.data.vehiculo_id) {
                    await cargarServiciosPorTipo();
                    await new Promise(resolve => setTimeout(resolve, 200));
                }

                // 6. Establecer fecha
                if (fechaInput && data.data.fecha) {
                    fechaInput.value = data.data.fecha;
                    const changeEvent = new Event('change');
                    fechaInput.dispatchEvent(changeEvent);
                    await new Promise(resolve => setTimeout(resolve, 800));
                }

                // 7. Configurar hora
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

        // FUNCIÓN para convertir día de JavaScript a formato backend
        function getBackendDayFromJSDay(jsDay) {
            // JavaScript: 0=Domingo, 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado
            // Backend: 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado, 7=Domingo

            if (jsDay === 0) {
                return 7; // Domingo
            }
            return jsDay; // Luenes=1, Martes=2, etc.
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

        // Manejar envío del formulario de citas (MEJORADO)
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

                        // Recargar la página para ver los cambios
                        location.reload();

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
                        <p>${errorMessage}</p>
                        ${errorDetails ? `<p style="color: #dc3545; margin-top: 10px;">${errorDetails}</p>` : ''}
                `;

                        if (showAvailableTimes && availableTimes.length > 0) {
                            errorHtml += `
                        <div style="margin-top: 15px;">
                            <p><strong>Horarios disponibles:</strong></p>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px;">
                                ${availableTimes.map(time => `<span class="badge badge-primary mr-1 mb-1">${time}</span>`).join('')}
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

        // Función para forzar creación de cita con tiempo extendido
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

                    // Recargar la página para ver los cambios
                    location.reload();
                } else {
                    // Verificar si hay un conflicto de horario específico
                    if (result.message && result.message.includes('conflicto')) {
                        // Mostrar horarios disponibles si vienen en la respuesta
                        let errorHtml = `
                            <div style="text-align: left;">
                                <p>${result.message}</p>
                        `;

                        if (result.horarios_disponibles && result.horarios_disponibles.length > 0) {
                            errorHtml += `
                                <div style="margin-top: 15px;">
                                    <p><strong>Horarios disponibles:</strong></p>
                                    <div style="max-height: 150px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px;">
                                        ${result.horarios_disponibles.map(time => `<span class="badge badge-primary mr-1 mb-1">${time}</span>`).join('')}
                                    </div>
                                </div>
                            `;
                        }

                        errorHtml += `</div>`;

                        throw new Error(errorHtml);
                    } else {
                        throw new Error(result.message || 'Error al forzar creación de cita');
                    }
                }
            } catch (error) {
                console.error('Error al forzar creación:', error);

                // Mostrar error con formato adecuado
                if (error.message.includes('<div')) {
                    // Es un error con HTML formateado
                    await swalWithBootstrapButtons.fire({
                        title: 'Error de conflicto',
                        html: error.message,
                        icon: 'error'
                    });
                } else {
                    // Es un error de texto plano
                    await swalWithBootstrapButtons.fire({
                        title: 'Error',
                        text: error.message || 'No se pudo completar la reserva',
                        icon: 'error'
                    });
                }
            }
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

        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-warning')) {
                const citaId = e.target.closest('.cita-card').dataset.citaId;
                editCita(citaId);
            }
            if (e.target.closest('.btn-outline')) {
                const citaId = e.target.closest('.cita-card').dataset.citaId;
                cancelCita(citaId);
            }
        });

        // Función para limpiar filtros
        function limpiarFiltros() {
            // Resetear el formulario
            document.getElementById('filtrosForm').reset();

            // Redirigir a la URL base sin parámetros
            window.location.href = '{{ route('cliente.citas') }}';
        }

        // Función para aplicar filtros automáticamente
        function aplicarFiltros() {
            // Mostrar overlay de carga
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(loadingOverlay);

            // Obtener valores actuales
            const estado = document.getElementById('estado').value;
            const fecha_desde = document.getElementById('fecha_desde').value;
            const fecha_hasta = document.getElementById('fecha_hasta').value;
            const vehiculo_id = document.getElementById('vehiculo_id').value;

            // Construir URL con parámetros
            let url = '{{ route('cliente.citas') }}?';

            if (estado) url += `estado=${estado}&`;
            if (fecha_desde) url += `fecha_desde=${fecha_desde}&`;
            if (fecha_hasta) url += `fecha_hasta=${fecha_hasta}&`;
            if (vehiculo_id) url += `vehiculo_id=${vehiculo_id}&`;

            // Eliminar el último & si existe
            url = url.replace(/&$/, '');

            // Recargar la página con los nuevos parámetros
            window.location.href = url;
        }

        // Configurar event listeners para los filtros
        document.addEventListener('DOMContentLoaded', function() {
            const filters = ['estado', 'vehiculo_id', 'fecha_desde', 'fecha_hasta'];

            filters.forEach(filterId => {
                const element = document.getElementById(filterId);
                if (element) {
                    element.addEventListener('change', aplicarFiltros);
                }
            });

            // Animación de entrada para las cards
            const citaCards = document.querySelectorAll('.cita-card');
            citaCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('createCitaModal');
            if (event.target == modal) {
                closeCitaModal();
            }
        }
    </script>
</body>

</html>
