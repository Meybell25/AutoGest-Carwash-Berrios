@extends('layouts.app')

@section('title', 'Nuevo Horario')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Nuevo Horario</h2>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('horarios.store') }}">
            @csrf

            <div class="mb-3">
                <label for="dia_semana" class="form-label">Día de la Semana:</label>
                <select name="dia_semana" id="dia_semana" class="form-select" required>
                    <option value="">Seleccione el día</option>
                    @foreach (\App\Models\Horario::NOMBRES_DIAS as $key => $dia)
                        <option value="{{ $key }}" {{ old('dia_semana') == $key ? 'selected' : '' }}>
                            {{ $dia }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora de Inicio:</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ old('hora_inicio') }}" required>
            </div>

            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora de Fin:</label>
                <input type="time" name="hora_fin" id="hora_fin" class="form-control" value="{{ old('hora_fin') }}" required>
            </div>

            <div class="mb-3">
                <label for="activo" class="form-label">Estado:</label>
                <select name="activo" id="activo" class="form-select" required>
                    <option value="1" {{ old('activo') == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo') === "0" ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('horarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
