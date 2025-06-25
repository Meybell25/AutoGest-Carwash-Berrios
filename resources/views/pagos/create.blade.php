@extends('layouts.app')

@section('title', 'Registrar Nuevo Pago')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Registrar Nuevo Pago</h2>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('pagos.store') }}">
            @csrf

            <div class="mb-3">
                <label for="cita_id" class="form-label">Cita:</label>
                <select name="cita_id" id="cita_id" class="form-select" required>
                    <option value="">Seleccione la cita</option>
                    @foreach ($citas as $cita)
                        <option value="{{ $cita->id }}">{{ $cita->id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="monto" class="form-label">Monto:</label>
                <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="metodo" class="form-label">Método de Pago:</label>
                <select name="metodo" id="metodo" class="form-select" required onchange="mostrarCamposMetodo()">
                    <option value="">Seleccione método</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="pasarela">Pasarela</option>
                </select>
            </div>

            <!-- Campos específicos para efectivo -->
            <div id="campos-efectivo" style="display: none;">
                <div class="mb-3">
                    <label for="monto_recibido" class="form-label">Monto Recibido:</label>
                    <input type="number" step="0.01" name="monto_recibido" id="monto_recibido" class="form-control">
                </div>
            </div>

            <!-- Campos específicos para transferencia o pasarela -->
            <div id="campos-referencia" style="display: none;">
                <div class="mb-3">
                    <label for="referencia" class="form-label">Referencia:</label>
                    <input type="text" name="referencia" id="referencia" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('pagos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Pago</button>
            </div>
        </form>
    </div>
</div>

<script>
    function mostrarCamposMetodo() {
        const metodo = document.getElementById('metodo').value;
        document.getElementById('campos-efectivo').style.display = (metodo === 'efectivo') ? 'block' : 'none';
        document.getElementById('campos-referencia').style.display = (metodo === 'transferencia' || metodo === 'pasarela') ? 'block' : 'none';
    }
</script>
@endsection
