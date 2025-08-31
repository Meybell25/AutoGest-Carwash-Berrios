@extends('layouts.app')

@section('title', 'Bitácora')

@section('styles')
<style>
    /* ======================
       ESTILOS DE BITÁCORA 
       ====================== */
    .bitacora-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 25px;
        position: relative;
    }

    /* Header de bitácora */
    .bitacora-header {
        background: var(--bg-light);
        backdrop-filter: var(--blur);
        padding: 30px 35px;
        border-radius: 24px;
        margin-bottom: 35px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bitacora-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
        animation: shimmer 3s ease-in-out infinite;
    }

    .bitacora-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .bitacora-title h1 {
        margin: 0;
        font-size: 1.8rem;
        background: var(--secondary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Tarjeta de bitácora */
    .bitacora-card {
        background: var(--bg-light);
        backdrop-filter: var(--blur);
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        margin-bottom: 35px;
    }

    .bitacora-card::before {
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

    .bitacora-card:hover::before {
        opacity: 1;
    }

    .bitacora-card-header {
        padding: 25px 30px 0;
        border-bottom: 2px solid var(--border-primary);
        margin-bottom: 25px;
    }

    .bitacora-card-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    .bitacora-card-body {
        padding: 0 30px 30px;
    }

    /* Filtros */
    .search-filter-container {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-button {
        height: fit-content;
        margin-bottom: 8px;
    }

    /* Tabla de bitácora */
    .bitacora-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        background: var(--bg-surface);
    }

    .bitacora-table th {
        background: var(--light);
        padding: 18px 15px;
        text-align: left;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .bitacora-table td {
        padding: 18px 15px;
        border-bottom: 1px solid var(--border-primary);
        background: var(--bg-surface);
    }

    .bitacora-table tr:hover td {
        background: rgba(39, 174, 96, 0.03);
    }

    /* Estado vacío */
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

    /* Paginación */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
    }

    .page-link {
        padding: 10px 15px;
        border: 2px solid var(--border-primary);
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
    }

    /* Responsive */
    @media (max-width: 768px) {
        .bitacora-header {
            flex-direction: column;
            text-align: center;
            gap: 20px;
            padding: 20px;
        }
        
        .search-filter-container {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .bitacora-table {
            display: block;
            overflow-x: auto;
        }
        
        .bitacora-table th,
        .bitacora-table td {
            padding: 12px 8px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<div class="bitacora-container">
    <!-- Header -->
    <div class="bitacora-header">
        <div class="bitacora-title">
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
    <div class="bitacora-card">
        <div class="bitacora-card-header">
            <h2>
                <i class="fas fa-clipboard-list"></i>
                Registro de todas las actividades realizadas en el sistema
            </h2>
        </div>
        <div class="bitacora-card-body">
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
                <table class="bitacora-table">
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

            <!-- Paginación -->
            <div class="pagination">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection