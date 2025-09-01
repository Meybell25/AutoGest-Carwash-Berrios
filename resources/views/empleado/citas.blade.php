@extends('layouts.app')

@section('title', 'Agenda de Citas')

@section('content')
<div class="container">
    <h1 class="mb-4">Agenda de Citas</h1>
    <form method="GET" action="{{ route('empleado.citas') }}" class="mb-3 d-flex align-items-end gap-2">
        <div>
            <label for="filtro" class="form-label">Filtro</label>
            <select name="filtro" id="filtro" class="form-select" onchange="this.form.submit()">
                <option value="hoy" {{ $filtro == 'hoy' ? 'selected' : '' }}>Hoy</option>
                <option value="manana" {{ $filtro == 'manana' ? 'selected' : '' }}>Mañana</option>
                <option value="pasado" {{ $filtro == 'pasado' ? 'selected' : '' }}>Pasado mañana</option>
                <option value="fecha" {{ $filtro == 'fecha' ? 'selected' : '' }}>Otra fecha</option>
            </select>
        </div>
        <div>
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" min="{{ now()->toDateString() }}" value="{{ $fecha->toDateString() }}" {{ $filtro == 'fecha' ? '' : 'disabled' }} onchange="this.form.submit()">
        </div>
    </form>

    @forelse ($citas as $cita)
        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>{{ $cita->usuario?->nombre ?? 'Cliente' }}</strong>
                    <div class="text-muted">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</div>
                </div>
                <div>
                    <span class="badge bg-primary">Pendiente</span>
                </div>
            </div>
            <div>
                {{ $cita->vehiculo?->marca }} {{ $cita->vehiculo?->modelo }}
            </div>
        </div>
    @empty
        <p>No hay citas pendientes para esta fecha.</p>
    @endforelse
</div>
@endsection