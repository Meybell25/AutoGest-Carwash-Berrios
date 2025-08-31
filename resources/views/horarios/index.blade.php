@extends('layouts.app')

@section('title', 'Horarios')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Horarios</h2>
        <a href="{{ route('admin.horarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Horario
        </a>
    </div>

    <div class="card-body">
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
                @foreach ($horarios as $horario)
                <tr>
                    <td>{{ \App\Models\Horario::NOMBRES_DIAS[$horario->dia_semana] }}</td>
                    <td>{{ $horario->hora_inicio_formateada }}</td>
                    <td>{{ $horario->hora_fin_formateada }}</td>
                    <td>
                        <span class="badge {{ $horario->activo ? 'bg-success' : 'bg-danger' }}">
                            {{ $horario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.horarios.edit', $horario->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este horario?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>
@endsection

