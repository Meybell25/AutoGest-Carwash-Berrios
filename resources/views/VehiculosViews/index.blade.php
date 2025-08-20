@extends('layouts.app')

@section('title', 'Vehículos')

@section('content')
<div class="container py-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestión de Vehículos</h1>
            <p class="mb-0 text-muted">Administra todos los vehículos registrados en el sistema</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Vehículo
            </a>
        </div>
    </div>

    <hr class="my-4">

    <!-- Contenido principal -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h2 class="h5 mb-4">Lista de Vehículos</h2>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>Color</th>
                            <th>Descripción</th>
                            <th>Fecha Registro</th>
                            <th>Placa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td>{{ $vehiculo->marca }}</td>
                            <td>{{ $vehiculo->modelo }}</td>
                            <td><span class="badge bg-primary">{{ $vehiculo->tipo_formatted }}</span></td>
                            <td>{{ $vehiculo->color }}</td>
                            <td>{{ $vehiculo->descripcion }}</td>
                            <td>{{ optional($vehiculo->fecha_registro)->format('d/m/Y') }}</td>
                            <td>{{ $vehiculo->placa }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'cliente' && $vehiculo->usuario_id === auth()->id()))
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('¿Eliminar este vehículo?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-car fa-2x mb-3"></i>
                                    <h5 class="mb-1">No hay vehículos registrados</h5>
                                    <p class="mb-0">Agrega un nuevo vehículo para comenzar</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para los botones de acción */
    .btn-edit {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
    }
    
    .btn-edit:hover, .btn-delete:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>
@endsection

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
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        33% {
            transform: translate(30px, -30px) rotate(120deg);
        }
        66% {
            transform: translate(-20px, 20px) rotate(240deg);
        }
    }
</style>