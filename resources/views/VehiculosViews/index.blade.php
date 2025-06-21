@extends('layouts.app')

@section('title', 'Vehiculos')

@section('content')
    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Vehículos</h5>
            <a href="{{ route('vehiculos.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Agregar nuevo
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Color</th>
                        <th>Descripción</th>
                        <th>Fecha de Registro</th>
                        <th>Placa</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td>{{ $vehiculo->marca }}</td>
                            <td>{{ $vehiculo->modelo }}</td>
                            <td>{{ $vehiculo->tipo_formatted }}</td>
                            <td>{{ $vehiculo->color }}</td>
                            <td>{{ $vehiculo->descripcion }}</td>
                            <td>{{ optional($vehiculo->fecha_registro)->format('d/m/Y') }}</td>
                            <td>{{ $vehiculo->placa }}</td>
                            <td class="text-end">
                                @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'cliente' && $vehiculo->usuario_id === auth()->id()))
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este vehículo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                             <td colspan="7" class="text-center">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection