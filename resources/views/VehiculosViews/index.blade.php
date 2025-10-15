<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Vehículos - AutoGest Carwash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #4facfe;
            --primary-dark: #00f2fe;
            --secondary: #667eea;
            --success: #3dd26e;
            --warning: #ff9800;
            --danger: #c62828;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px 32px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-soft);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 4px;
        }

        .header-title p {
            color: #666;
            font-size: 0.95rem;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Botones profesionales */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-success {
            background: linear-gradient(135deg, #3dd26e 0%, #35ebc9 100%);
            color: white;
        }

        .btn-sm {
            padding: 8px 14px;
            font-size: 0.875rem;
        }

        /* Card principal */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 0;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 28px;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 28px;
        }

        /* Grid de vehículos */
        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .vehicle-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .vehicle-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        }

        .vehicle-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }

        .vehicle-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .vehicle-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .vehicle-info h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .vehicle-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .vehicle-details {
            margin: 16px 0;
            display: grid;
            gap: 10px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .detail-item i {
            color: var(--primary);
            width: 20px;
        }

        .detail-item strong {
            color: #333;
            min-width: 80px;
        }

        .vehicle-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .vehicle-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #eee;
        }

        .btn-edit {
            flex: 1;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            justify-content: center;
        }

        .btn-delete {
            flex: 1;
            background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
            color: white;
            justify-content: center;
        }

        /* Estado vacío */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1100;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-modal {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.3rem;
            transition: var(--transition);
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: var(--transition);
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .modal-footer {
            padding: 20px 28px;
            background: #f8f9fa;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1><i class="fas fa-car"></i> Mis Vehículos</h1>
                <p>Administra y gestiona tus vehículos registrados</p>
            </div>
            <div class="header-actions">
                <button onclick="openCreateModal()" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i>
                    Agregar Vehículo
                </button>
            </div>
        </div>

        <!-- Card principal -->
        <div class="card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    Vehículos Registrados
                </h2>
                <span style="color: #666; font-size: 0.95rem;">
                    Total: <strong>{{ $vehiculos->count() }}</strong>
                </span>
            </div>
            <div class="card-body">
                @if($vehiculos->count() > 0)
                    <div class="vehicles-grid">
                        @foreach($vehiculos as $vehiculo)
                        <div class="vehicle-card">
                            <div class="vehicle-header">
                                <div class="vehicle-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <span class="vehicle-badge">{{ $vehiculo->tipo_formatted }}</span>
                            </div>

                            <div class="vehicle-info">
                                <h3>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h3>
                                <p><i class="fas fa-hashtag"></i> {{ $vehiculo->placa }}</p>
                            </div>

                            <div class="vehicle-details">
                                <div class="detail-item">
                                    <i class="fas fa-palette"></i>
                                    <strong>Color:</strong>
                                    <span>{{ $vehiculo->color }}</span>
                                </div>
                                @if($vehiculo->descripcion)
                                <div class="detail-item">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Descripción:</strong>
                                    <span>{{ Str::limit($vehiculo->descripcion, 40) }}</span>
                                </div>
                                @endif
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <strong>Registro:</strong>
                                    <span>{{ optional($vehiculo->fecha_registro)->format('d/m/Y') ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'cliente' && $vehiculo->usuario_id === auth()->id()))
                            <div class="vehicle-actions">
                                <button onclick="openEditModal({{ $vehiculo->id }})" class="btn btn-sm btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </button>
                                <button onclick="deleteVehiculo({{ $vehiculo->id }})" class="btn btn-sm btn-delete">
                                    <i class="fas fa-trash-alt"></i>
                                    Eliminar
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-car"></i>
                        <h3>No hay vehículos registrados</h3>
                        <p>Agrega tu primer vehículo para comenzar</p>
                        <button onclick="openCreateModal()" class="btn btn-success" style="margin-top: 20px;">
                            <i class="fas fa-plus-circle"></i>
                            Agregar Vehículo
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar Vehículo -->
    <div id="vehiculoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-car"></i>
                    <span id="modalTitle">Agregar Vehículo</span>
                </h2>
                <button class="close-modal" onclick="closeVehiculoModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="vehiculoForm" method="POST">
                @csrf
                <input type="hidden" id="vehiculoId" name="vehiculo_id">
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="marca"><i class="fas fa-tag"></i> Marca</label>
                        <input type="text" id="marca" name="marca" required placeholder="Ej: Toyota, Honda, Ford">
                    </div>

                    <div class="form-group">
                        <label for="modelo"><i class="fas fa-car-side"></i> Modelo</label>
                        <input type="text" id="modelo" name="modelo" required placeholder="Ej: Corolla, Civic, Mustang">
                    </div>

                    <div class="form-group">
                        <label for="tipo"><i class="fas fa-list"></i> Tipo</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="sedan">Sedán</option>
                            <option value="pickup">Pickup</option>
                            <option value="camion">Camión</option>
                            <option value="moto">Motocicleta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="color"><i class="fas fa-palette"></i> Color</label>
                        <input type="text" id="color" name="color" required placeholder="Ej: Rojo, Azul, Negro">
                    </div>

                    <div class="form-group">
                        <label for="placa"><i class="fas fa-hashtag"></i> Placa</label>
                        <input type="text" id="placa" name="placa" required placeholder="Ej: ABC-123">
                    </div>

                    <div class="form-group">
                        <label for="descripcion"><i class="fas fa-align-left"></i> Descripción (Opcional)</label>
                        <textarea id="descripcion" name="descripcion" rows="3" placeholder="Detalles adicionales del vehículo..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeVehiculoModal()" class="btn btn-outline">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        <span id="submitText">Guardar Vehículo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Configuración CSRF para todas las peticiones AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Abrir modal para crear
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Agregar Vehículo';
            document.getElementById('submitText').textContent = 'Guardar Vehículo';
            document.getElementById('vehiculoForm').action = "{{ route('vehiculos.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('vehiculoId').value = '';
            document.getElementById('vehiculoForm').reset();
            document.getElementById('vehiculoModal').style.display = 'flex';
        }

        // Abrir modal para editar
        async function openEditModal(id) {
            try {
                const response = await fetch(`/vehiculos/${id}/edit`);
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Extraer datos del formulario de edición
                const marca = doc.querySelector('input[name="marca"]')?.value || '';
                const modelo = doc.querySelector('input[name="modelo"]')?.value || '';
                const tipo = doc.querySelector('select[name="tipo"]')?.value || '';
                const color = doc.querySelector('input[name="color"]')?.value || '';
                const placa = doc.querySelector('input[name="placa"]')?.value || '';
                const descripcion = doc.querySelector('textarea[name="descripcion"]')?.value || '';

                // Llenar el formulario
                document.getElementById('modalTitle').textContent = 'Editar Vehículo';
                document.getElementById('submitText').textContent = 'Actualizar Vehículo';
                document.getElementById('vehiculoForm').action = `/vehiculos/${id}`;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('vehiculoId').value = id;
                document.getElementById('marca').value = marca;
                document.getElementById('modelo').value = modelo;
                document.getElementById('tipo').value = tipo;
                document.getElementById('color').value = color;
                document.getElementById('placa').value = placa;
                document.getElementById('descripcion').value = descripcion;

                document.getElementById('vehiculoModal').style.display = 'flex';
            } catch (error) {
                console.error('Error al cargar datos del vehículo:', error);
                Swal.fire('Error', 'No se pudo cargar la información del vehículo', 'error');
            }
        }

        // Cerrar modal
        function closeVehiculoModal() {
            document.getElementById('vehiculoModal').style.display = 'none';
        }

        // Manejar envío del formulario
        document.getElementById('vehiculoForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const action = this.action;
            const method = document.getElementById('formMethod').value;

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: method === 'POST' ? 'Vehículo agregado correctamente' : 'Vehículo actualizado correctamente',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al procesar la solicitud');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', error.message || 'Ocurrió un error al procesar la solicitud', 'error');
            }
        });

        // Eliminar vehículo
        async function deleteVehiculo(id) {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c62828',
                cancelButtonColor: '#666',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/vehiculos/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'El vehículo ha sido eliminado',
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error('Error al eliminar el vehículo');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar el vehículo', 'error');
                }
            }
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('vehiculoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVehiculoModal();
            }
        });
    </script>
</body>
</html>
