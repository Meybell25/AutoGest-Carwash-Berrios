@php($isEdit = isset($horario))
<div class="mb-3">
    <label for="dia_semana" class="form-label">Día de la semana</label>
    <select name="dia_semana" id="dia_semana" class="form-select @error('dia_semana') is-invalid @enderror" required>
        <option value="">Seleccione</option>
        @foreach($dias as $valor => $nombre)
            <option value="{{ $valor }}" {{ old('dia_semana', $horario->dia_semana ?? '') == $valor ? 'selected' : '' }}>{{ $nombre }}</option>
        @endforeach
    </select>
    @error('dia_semana')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="hora_inicio" class="form-label">Hora inicio</label>
    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control @error('hora_inicio') is-invalid @enderror" value="{{ old('hora_inicio', ($horario->hora_inicio ?? null)?->format('H:i')) }}" required>
    @error('hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="hora_fin" class="form-label">Hora fin</label>
    <input type="time" name="hora_fin" id="hora_fin" class="form-control @error('hora_fin') is-invalid @enderror" value="{{ old('hora_fin', ($horario->hora_fin ?? null)?->format('H:i')) }}" required>
    @error('hora_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', ($horario->activo ?? true)) ? 'checked' : '' }}>
    <label class="form-check-label" for="activo">Activo</label>
    @error('activo')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
</div>
