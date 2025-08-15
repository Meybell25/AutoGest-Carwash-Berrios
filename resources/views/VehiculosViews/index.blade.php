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