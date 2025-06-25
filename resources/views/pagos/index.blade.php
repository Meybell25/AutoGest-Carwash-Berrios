@extends('layouts.app')

@section('title', 'Listado de Pagos')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Pagos Registrados</h2>
        <a href="{{ route('pagos.create') }}" class="btn btn-primary float-end">
            <i class="fas fa-plus"></i> Nuevo Pago
        </a>
    </div>

    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Cita</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Fecha de Pago</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $pago->cita->id ?? 'N/A' }}</td>
                    <td>${{ number_format($pago->monto, 2) }}</td>
                    <td>{{ ucfirst($pago->metodo) }}</td>
                    <td>
                        <span class="badge 
                            @if($pago->estado === \App\Models\Pago::ESTADO_COMPLETADO) bg-success
                            @elseif($pago->estado === \App\Models\Pago::ESTADO_PENDIENTE) bg-warning
                            @elseif($pago->estado === \App\Models\Pago::ESTADO_FALLIDO) bg-danger
                            @elseif($pago->estado === \App\Models\Pago::ESTADO_REEMBOLSADO) bg-secondary
                            @endif">
                            {{ ucfirst($pago->estado) }}
                        </span>
                    </td>
                    <td>{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('pagos.edit', $pago->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este pago?')">
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
