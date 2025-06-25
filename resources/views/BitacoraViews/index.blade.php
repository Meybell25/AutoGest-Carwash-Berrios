@extends('layouts.app')

@section('title', 'Bitácora')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-arrow-left me-2"></i> Volver al Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Registros de Bitácora</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <label for="usuario_id" class="form-label">Usuario</label>
                <select name="usuario_id" id="usuario_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($usuarios as $id => $nombre)
                        <option value="{{ $id }}" @selected(request('usuario_id') == $id)>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Desde</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Hasta</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->fecha->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $log->usuario->nombre ?? 'N/A' }}</td>
                            <td>{{ $log->accion }}</td>
                            <td>{{ $log->ip }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Sin registros</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    </div>
</div>
@endsection