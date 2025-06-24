@extends('layouts.app')

@section('title', 'Editar Horario')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Editar Horario</h2>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('horarios.update', $horario->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="dia_semana" class="form-label">Día de la Semana:</label>
                <select name="dia_semana" id="dia_semana" class="form-select" required>
                    @foreach (\App\Models\Horario::NOMBRES_DIAS as $key => $dia)
                        <option value="{{ $key }}" {{ $horario->dia_semana == $key ? 'selected' : '' }}>
                            {{ $dia }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora de Inicio:</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control"
                    value="{{ $horario->hora_inicio_formateada }}" required>
            </div>

            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora de Fin:</label>
                <input type="time" name="hora_fin" id="hora_fin" class="form-control"
                    value="{{ $horario->hora_fin_formateada }}" required>
            </div>

            <div class="mb-3">
                <label for="activo" class="form-label">Estado:</label>
                <select name="activo" id="activo" class="form-select" required>
                    <option value="1" {{ $horario->activo ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !$horario->activo ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('horarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
