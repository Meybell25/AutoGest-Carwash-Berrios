<!-- Contenedor para Gestión de Gastos -->
<div class="card">
    <div class="card-header">
        <h2>
            <div class="card-header-icon icon-container">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            Gestión de Gastos
        </h2>
    </div>
    <div class="card-body">
        <div class="card-header-actions" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h3 style="color: var(--text-primary); margin: 0; max-width: 70%;">Registro de gastos operativos</h3>
            <button class="btn btn-primary" onclick="mostrarModalGasto()">
                <i class="fas fa-plus"></i> Registrar Gasto
            </button>
        </div>

        <div class="search-filter-container">
            <div class="search-box">
                <input type="text" id="buscarGasto" placeholder="Buscar gastos..." class="form-control" oninput="filtrarGastos()">
            </div>
            <div class="filter-select">
                <select id="filtroTipoGasto" class="form-control" onchange="filtrarGastos()">
                    <option value="">Todos los tipos</option>
                    <option value="stock">Stock</option>
                    <option value="sueldos">Sueldos</option>
                    <option value="personal">Personal</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table" id="tablaGastos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Detalle</th>
                        <th>Monto</th>
                        <th>Registrado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaGastos">
                    <!-- Los gastos se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Gastos -->
<div id="gastoModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <span class="close-modal" onclick="closeModal('gastoModal')">&times;</span>
        <h2 id="gastoModalTitle">
            <i class="fas fa-money-bill-wave"></i> Registrar Gasto
        </h2>
        <form id="gastoForm">
            <div class="form-grid">
                <div class="form-group">
                    <label for="gastoTipo">Tipo:</label>
                    <select id="gastoTipo" name="tipo" class="form-control" required>
                        <option value="">Seleccione tipo</option>
                        <option value="stock">Stock</option>
                        <option value="sueldos">Sueldos</option>
                        <option value="personal">Personal</option>
                        <option value="mantenimiento">Mantenimiento</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gastoMonto">Monto ($):</label>
                    <input type="number" step="0.01" id="gastoMonto" name="monto" required class="form-control" placeholder="0.00">
                </div>
            </div>

            <div class="form-group">
                <label for="gastoDetalle">Detalle:</label>
                <textarea id="gastoDetalle" name="detalle" rows="3" required class="form-control" placeholder="Descripción del gasto..."></textarea>
            </div>

            <div class="form-group">
                <label for="gastoFecha">Fecha:</label>
                <input type="date" id="gastoFecha" name="fecha" class="form-control" value="{{ date('Y-m-d') }}">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-save"></i> Registrar Gasto
            </button>
        </form>
    </div>
</div>

<script>

// Datos iniciales (simulando base de datos)
let gastos = JSON.parse(localStorage.getItem('gastos')) || [
    {
        id: 1,
        fecha: '2025-06-15',
        tipo: 'stock',
        detalle: 'Compra de shampoo y ceras',
        monto: 125.50,
        registradoPor: 'Admin'
    },
    {
        id: 2,
        fecha: '2025-06-10',
        tipo: 'mantenimiento',
        detalle: 'Reparación de equipo de lavado',
        monto: 320.00,
        registradoPor: 'Admin'
    }
];

// Mostrar modal de gastos
function mostrarModalGasto() {
    document.getElementById('gastoForm').reset();
    document.getElementById('gastoFecha').value = new Date().toISOString().split('T')[0];
    document.getElementById('gastoModal').style.display = 'flex';
}

// Cargar gastos en la tabla
function cargarGastos(gastosAMostrar = gastos) {
    const cuerpoTabla = document.getElementById('cuerpoTablaGastos');
    cuerpoTabla.innerHTML = '';

    if (gastosAMostrar.length === 0) {
        cuerpoTabla.innerHTML = `
            <tr>
                <td colspan="6" class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>No se encontraron gastos</p>
                </td>
            </tr>
        `;
        return;
    }

    gastosAMostrar.forEach(gasto => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${formatearFecha(gasto.fecha)}</td>
            <td>${capitalizeFirstLetter(gasto.tipo)}</td>
            <td>${gasto.detalle}</td>
            <td>$${gasto.monto.toFixed(2)}</td>
            <td>${gasto.registradoPor}</td>
            <td>
                <div class="table-actions">
                    <button class="table-btn btn-edit" onclick="editarGasto(${gasto.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="table-btn btn-delete" onclick="eliminarGasto(${gasto.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        cuerpoTabla.appendChild(fila);
    });
}

// Formatear fecha
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES');
}

// Capitalizar primera letra
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Filtrar gastos
function filtrarGastos() {
    const textoBusqueda = document.getElementById('buscarGasto').value.toLowerCase();
    const tipoSeleccionado = document.getElementById('filtroTipoGasto').value;

    const gastosFiltrados = gastos.filter(gasto => {
        const coincideTexto = gasto.detalle.toLowerCase().includes(textoBusqueda) || 
                            gasto.registradoPor.toLowerCase().includes(textoBusqueda);
        const coincideTipo = tipoSeleccionado === '' || gasto.tipo === tipoSeleccionado;
        
        return coincideTexto && coincideTipo;
    });

    cargarGastos(gastosFiltrados);
}

// Registrar nuevo gasto
document.getElementById('gastoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nuevoGasto = {
        id: gastos.length > 0 ? Math.max(...gastos.map(g => g.id)) + 1 : 1,
        fecha: document.getElementById('gastoFecha').value,
        tipo: document.getElementById('gastoTipo').value,
        detalle: document.getElementById('gastoDetalle').value,
        monto: parseFloat(document.getElementById('gastoMonto').value),
        registradoPor: 'Admin' // En un sistema real sería el usuario logueado
    };

    gastos.push(nuevoGasto);
    localStorage.setItem('gastos', JSON.stringify(gastos));
    
    Toast.fire({
        icon: 'success',
        title: 'Gasto registrado correctamente'
    });
    
    closeModal('gastoModal');
    cargarGastos();
    document.getElementById('gastoForm').reset();
});

// Editar gasto
function editarGasto(id) {
    const gasto = gastos.find(g => g.id === id);
    if (!gasto) return;

    document.getElementById('gastoModalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Gasto';
    document.getElementById('gastoFecha').value = gasto.fecha;
    document.getElementById('gastoTipo').value = gasto.tipo;
    document.getElementById('gastoDetalle').value = gasto.detalle;
    document.getElementById('gastoMonto').value = gasto.monto;
    
    // Cambiar el comportamiento del formulario para edición
    const form = document.getElementById('gastoForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        
        gasto.fecha = document.getElementById('gastoFecha').value;
        gasto.tipo = document.getElementById('gastoTipo').value;
        gasto.detalle = document.getElementById('gastoDetalle').value;
        gasto.monto = parseFloat(document.getElementById('gastoMonto').value);
        
        localStorage.setItem('gastos', JSON.stringify(gastos));
        
        Toast.fire({
            icon: 'success',
            title: 'Gasto actualizado correctamente'
        });
        
        closeModal('gastoModal');
        cargarGastos();
        form.reset();
        form.onsubmit = handleSubmit; // Restaurar el manejador original
    };
    
    document.getElementById('gastoModal').style.display = 'flex';
}

// Eliminar gasto
function eliminarGasto(id) {
    Swal.fire({
        title: '¿Eliminar este gasto?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            gastos = gastos.filter(gasto => gasto.id !== id);
            localStorage.setItem('gastos', JSON.stringify(gastos));
            cargarGastos();
            
            Toast.fire({
                icon: 'success',
                title: 'Gasto eliminado correctamente'
            });
        }
    });
}

// Configuración de Toast (SweetAlert2)
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Cargar gastos al iniciar
document.addEventListener('DOMContentLoaded', function() {
    cargarGastos();
});

</script>

