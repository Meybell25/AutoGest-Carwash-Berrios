@extends('layouts.app')

@section('title', 'Bitácora')

@section('styles')
<style>
    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #3498db, #27ae60, #f39c12);
        background-attachment: fixed;
        min-height: 100vh;
        color: #2c3e50;
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
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 25px;
        position: relative;
    }

    /* Estilos de tarjeta */
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 35px;
    }

    .card-header {
        padding: 25px 30px 0;
        border-bottom: 2px solid rgba(39, 174, 96, 0.2);
        margin-bottom: 25px;
    }

    .card-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .card-body {
        padding: 0 30px 30px;
    }

    /* Botón Volver */
    .btn-primary {
        background: linear-gradient(135deg, #00695c 0%, #0277bd 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #004d40 0%, #01579b 100%);
        transform: translateY(-2px);
    }

    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-title .icon-container {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .header-title h1 {
        margin: 0;
        font-size: 1.8rem;
        background: linear-gradient(135deg, #00695c 0%, #0277bd 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.98);
    }

    .admin-table th {
        background: #eceff1;
        padding: 18px 15px;
        text-align: left;
        font-weight: 700;
        color: #2c3e50;
        font-size: 0.9rem;
    }

    .admin-table td {
        padding: 18px 15px;
        border-bottom: 1px solid rgba(39, 174, 96, 0.2);
    }

    .admin-table tr:hover td {
        background: rgba(39, 174, 96, 0.03);
    }

    /* Filtros - Ajuste para alinear botón */
    .search-filter-container {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-button {
        height: fit-content;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 18px;
        border: 2px solid rgba(39, 174, 96, 0.2);
        border-radius: 12px;
        font-size: 15px;
        background: rgba(255, 255, 255, 0.98);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        outline: none;
    }

    /* Paginación */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
    }

    .page-link {
        padding: 10px 15px;
        border: 2px solid rgba(39, 174, 96, 0.2);
        border-radius: 10px;
        color: #2e7d32;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .page-link:hover, .page-link.active {
        background: #2e7d32;
        color: white;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #2e7d32;
        margin-bottom: 20px;
    }

    /* Iconos */
    .icon-container {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00695c 0%, #0277bd 100%);
        color: white;
        font-size: 1.3rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="header">
        <div class="header-title">
            <div class="icon-container">
                <i class="fas fa-book"></i>
            </div>
            <h1>Bitácora del Sistema</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Card principal -->
    <div class="card">
        <div class="card-header">
            
            <h2>
                <i class="fas fa-clipboard-list"></i>
                Registro de todas las actividades realizadas en el sistema
            </h2>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="search-filter-container">
                <div class="filter-group">
                    <label for="usuario_id" class="form-label">Usuario</label>
                    <select name="usuario_id" id="usuario_id" class="form-control">
                        <option value="">Todos los usuarios</option>
                        @foreach($usuarios as $id => $nombre)
                            <option value="{{ $id }}" @selected(request('usuario_id') == $id)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="fecha_inicio" class="form-label">Desde</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                
                <div class="filter-group">
                    <label for="fecha_fin" class="form-label">Hasta</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
                
                <div class="filter-group filter-button">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>

            <!-- Tabla con IP -->
            <div style="overflow-x: auto; margin-top: 20px;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Dirección IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->fecha->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->usuario->nombre ?? 'Sistema' }}</td>
                                <td>{{ $log->accion }}</td>
                                <td>{{ $log->ip }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i class="fas fa-info-circle"></i>
                                    <h3>No se encontraron registros</h3>
                                    <p>No hay actividades registradas en el sistema</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación  -->
            <div class="pagination">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection