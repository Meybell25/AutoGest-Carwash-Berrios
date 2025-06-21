@extends('layouts.app')

@section('title', 'Editar Vehiculo')

@section('content')
    <div class="mb-3">
        <a href="{{ route('vehiculos.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Vehículo</h5>
        </div>
        <div class="card-body">
            <form id="vehiculoEditForm" method="POST" action="{{ route('vehiculos.update', $vehiculo) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca', $vehiculo->marca) }}">
                </div>
                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $vehiculo->modelo) }}">
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control">
                        <option value="">Seleccione un tipo</option>
                        @foreach(App\Models\Vehiculo::getTipos() as $key => $label)
                            <option value="{{ $key }}" {{ old('tipo', $vehiculo->tipo) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" name="color" id="color" class="form-control" value="{{ old('color', $vehiculo->color) }}">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $vehiculo->descripcion) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="placa" class="form-label">Placa</label>
                    <input type="text" name="placa" id="placa" class="form-control" value="{{ old('placa', $vehiculo->placa) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('vehiculoEditForm')?.addEventListener('submit', async function(e){
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const resp = await fetch(this.action, {
                method: 'POST',
                headers: {'X-Requested-With':'XMLHttpRequest'},
                body: formData
            });
            const data = await resp.json();
            if(!resp.ok) throw new Error(data.message || 'Error');

            localStorage.setItem('vehiculoActualizado', Date.now());
            swalWithBootstrapButtons.fire({
                title: '¡Éxito!',
                text: 'Vehículo actualizado correctamente',
                icon: 'success'
            }).then(()=> window.location.href = '{{ route('vehiculos.index') }}');
        } catch(error){
            swalWithBootstrapButtons.fire({
                title: 'Error',
                text: error.message || 'Error al actualizar el vehículo',
                icon: 'error'
            });
        }
    });
</script>
@endpush