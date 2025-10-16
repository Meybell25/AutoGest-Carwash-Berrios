@extends('layouts.app')

@section('title', 'Horarios')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Horarios</h2>
        <a href="{{ route('admin.horarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Horario
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($horarios as $horario)
                <tr>
                    <td>{{ \App\Http\Controllers\HorarioController::DIAS_SEMANA[$horario->dia_semana] ?? $horario->dia_semana }}</td>
                    <td>{{ $horario->hora_inicio?->format('H:i') }}</td>
                    <td>{{ $horario->hora_fin?->format('H:i') }}</td>
                    <td>
                        <span class="badge {{ $horario->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $horario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.horarios.edit', $horario) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.horarios.destroy', $horario) }}" method="POST" onsubmit="return confirm('¿Eliminar este horario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Sin horarios aún</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>
@endsection

