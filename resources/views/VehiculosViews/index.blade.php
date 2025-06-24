@extends('layouts.app')

@section('title', 'Vehiculos')

@section('content')
    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Vehículos</h5>
            <a href="{{ route('vehiculos.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Agregar nuevo
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table mb-0" id="vehiculosIndexTable">
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Color</th>
                        <th>Descripción</th>
                        <th>Fecha de Registro</th>
                        <th>Placa</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td data-label="Marca">{{ $vehiculo->marca }}</td>
                            <td data-label="Modelo">{{ $vehiculo->modelo }}</td>
                            <td data-label="Tipo">{{ $vehiculo->tipo_formatted }}</td>
                            <td data-label="Color">{{ $vehiculo->color }}</td>
                            <td data-label="Descripción">{{ $vehiculo->descripcion }}</td>
                            <td data-label="Fecha de Registro">{{ optional($vehiculo->fecha_registro)->format('d/m/Y') }}</td>
                            <td data-label="Placa">{{ $vehiculo->placa }}</td>
                            <td data-label="Acciones" class="text-end">
                                @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'cliente' && $vehiculo->usuario_id === auth()->id()))
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                   <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline-block vehiculo-delete-form" onsubmit="return confirm('¿Eliminar este vehículo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            #vehiculosIndexTable {
                min-width: 100%;
            }

            #vehiculosIndexTable thead {
                display: none;
            }

            #vehiculosIndexTable tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #eee;
                border-radius: 8px;
                padding: 10px;
            }

            #vehiculosIndexTable td {
                padding-left: 45%;
                position: relative;
                min-height: 40px;
                display: flex;
                align-items: center;
                word-break: break-word;
                white-space: normal;
                border: none;
            }

            #vehiculosIndexTable td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 40%;
                padding-right: 10px;
                font-weight: 600;
                color: #2e7d32;
            }
        }

        @media (max-width: 480px) {
            #vehiculosIndexTable td {
                padding-left: 40%;
            }

            #vehiculosIndexTable td:before {
                width: 35%;
            }
        }
    </style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function initResponsiveTables() {
            document.querySelectorAll('.table-responsive').forEach(tableContainer => {
                if (window.innerWidth < 768) {
                    tableContainer.classList.add('force-responsive');
                }
            });
        }

        window.addEventListener('resize', initResponsiveTables);
        initResponsiveTables();
        
        document.querySelectorAll('.vehiculo-delete-form').forEach(form => {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (!confirm('¿Eliminar este vehículo?')) return;
                const formData = new FormData(this);
                try {
                    const resp = await fetch(this.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const data = await resp.json();
                    if (!resp.ok) throw new Error(data.message || 'Error');

                    localStorage.setItem('vehiculoActualizado', Date.now());
                    this.closest('tr').remove();
                    swalWithBootstrapButtons.fire({
                        title: '¡Éxito!',
                        text: 'Vehículo eliminado correctamente',
                        icon: 'success'
                    });
                } catch (error) {
                    swalWithBootstrapButtons.fire({
                        title: 'Error',
                        text: error.message || 'Error al eliminar el vehículo',
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
@endpush