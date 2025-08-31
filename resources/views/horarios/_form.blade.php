@php($dias = \App\Models\Horario::NOMBRES_DIAS)

<div class="mb-3">
    <label for="dia_semana" class="form-label">Día de la Semana:</label>
    <select name="dia_semana" id="dia_semana" class="form-select" required>
        <option value="">Seleccione el día</option>
        @foreach ($dias as $key => $dia)
            <option value="{{ $key }}" {{ (old('dia_semana', $horario->dia_semana ?? '') == $key) ? 'selected' : '' }}>
                {{ $dia }}
            </option>
        @endforeach
    </select>
    @error('dia_semana')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
    </div>

<div class="mb-3">
    <label for="hora_inicio" class="form-label">Hora de Inicio:</label>
    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control"
           value="{{ old('hora_inicio', $horario->hora_inicio_formateada ?? '') }}" required>
    @error('hora_inicio')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="hora_fin" class="form-label">Hora de Fin:</label>
    <input type="time" name="hora_fin" id="hora_fin" class="form-control"
           value="{{ old('hora_fin', $horario->hora_fin_formateada ?? '') }}" required>
    @error('hora_fin')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="activo" class="form-label">Estado:</label>
    <select name="activo" id="activo" class="form-select" required>
        <option value="1" {{ (old('activo', isset($horario) ? (int)$horario->activo : 1) == 1) ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ (old('activo', isset($horario) ? (int)$horario->activo : 1) == 0) ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('activo')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar</button>
    </div>

