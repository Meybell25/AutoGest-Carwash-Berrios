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

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(45deg, #4facfe 0%, #1be9f4 100%);
        --success-gradient: linear-gradient(45deg, #3dd26e 0%, #35ebc9 100%);
        --warning-gradient: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        --info-gradient: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.2);
        --text-primary: #333;
        --text-secondary: #666;
        --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
        --border-radius: 0.75rem;
        --border-radius-lg: 1rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(180deg, #bbadfd, #5b21b6, #452383);
        min-height: 100vh;
        color: var(--text-primary);
        line-height: 1.6;
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }

    /* Partículas flotantes de fondo */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(250, 112, 154, 0.05) 0%, transparent 50%);
        z-index: -1;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        33% {
            transform: translate(30px, -30px) rotate(120deg);
        }
        66% {
            transform: translate(-20px, 20px) rotate(240deg);
        }
    }
</style>