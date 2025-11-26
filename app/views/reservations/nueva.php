<?php
// Prevent direct access to this file
if (!defined('VIEWS_PATH')) {
    http_response_code(403);
    die('Forbidden: Direct access not allowed.');
}
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-calendar-plus"></i> Nueva Reservación
    </h1>
    <p class="text-gray-600 mt-2">Complete el formulario para agendar su cita</p>
</div>

<div class="bg-white rounded-lg shadow-md p-8">
    <form id="reservacionForm" action="<?php echo BASE_URL; ?>/reservacion/crear" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <!-- Step 1: Sucursal -->
        <div class="mb-6">
            <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-building"></i> Seleccione una Sucursal *
            </label>
            <select name="sucursal_id" id="sucursal_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Seleccione una sucursal --</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?php echo $sucursal['id']; ?>">
                        <?php echo htmlspecialchars($sucursal['nombre']); ?> - 
                        <?php echo htmlspecialchars($sucursal['ciudad']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Step 2: Categoria de Servicio -->
        <div class="mb-6">
            <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-list"></i> Categoría de Servicio *
            </label>
            <select id="categoria_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Seleccione una categoría --</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>">
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Step 3: Servicio -->
        <div class="mb-6">
            <label for="servicio_id" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-briefcase"></i> Servicio *
            </label>
            <select name="servicio_id" id="servicio_id" required disabled
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Primero seleccione una categoría --</option>
            </select>
            <div id="servicio_info" class="mt-2 hidden">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong>Duración:</strong> <span id="servicio_duracion"></span> minutos
                    </p>
                    <p class="text-sm text-gray-700">
                        <strong>Precio:</strong> $<span id="servicio_precio"></span>
                    </p>
                    <p class="text-sm text-gray-600 mt-2" id="servicio_descripcion"></p>
                </div>
            </div>
        </div>
        
        <!-- Step 4: Especialista -->
        <div class="mb-6">
            <label for="especialista_id" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user-md"></i> Especialista *
            </label>
            <select name="especialista_id" id="especialista_id" required disabled
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Primero seleccione un servicio --</option>
            </select>
        </div>
        
        <!-- Step 5: Fecha -->
        <div class="mb-6">
            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar"></i> Fecha *
            </label>
            <input type="date" id="fecha" required disabled
                   min="<?php echo date('Y-m-d'); ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <!-- Step 6: Hora -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-clock"></i> Hora Disponible *
            </label>
            <div id="horarios_container" class="hidden">
                <div id="horarios_disponibles" class="grid grid-cols-4 gap-3">
                    <!-- Horarios se llenarán dinámicamente -->
                </div>
                <input type="hidden" name="fecha_hora" id="fecha_hora">
            </div>
            <div id="horarios_loading" class="hidden text-center py-4">
                <div class="spinner mx-auto"></div>
                <p class="text-gray-600 mt-2">Cargando horarios disponibles...</p>
            </div>
            <div id="horarios_empty" class="hidden text-center py-4 text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                <p>No hay horarios disponibles para esta fecha</p>
            </div>
        </div>
        
        <!-- Notas -->
        <div class="mb-6">
            <label for="notas" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-sticky-note"></i> Notas (Opcional)
            </label>
            <textarea name="notas" id="notas" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Agregue cualquier información adicional..."></textarea>
        </div>
        
        <!-- Buttons -->
        <div class="flex justify-between">
            <a href="<?php echo BASE_URL; ?>/dashboard" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" id="submitBtn" disabled
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <i class="fas fa-check"></i> Confirmar Reservación
            </button>
        </div>
    </form>
</div>

<script>
let serviciosData = [];
let selectedServicio = null;

// Load all services
fetch(`${BASE_URL}/reservacion/getServicios`)
    .then(response => response.json())
    .then(data => {
        serviciosData = data.servicios;
    });

// Category change
document.getElementById('categoria_id').addEventListener('change', function() {
    const categoriaId = this.value;
    const servicioSelect = document.getElementById('servicio_id');
    
    servicioSelect.innerHTML = '<option value="">-- Seleccione un servicio --</option>';
    servicioSelect.disabled = !categoriaId;
    
    if (categoriaId) {
        const filteredServicios = serviciosData.filter(s => s.categoria_id == categoriaId);
        filteredServicios.forEach(servicio => {
            const option = document.createElement('option');
            option.value = servicio.id;
            option.textContent = servicio.nombre;
            option.dataset.duracion = servicio.duracion;
            option.dataset.precio = servicio.precio;
            option.dataset.descripcion = servicio.descripcion;
            servicioSelect.appendChild(option);
        });
    }
    
    document.getElementById('servicio_info').classList.add('hidden');
});

// Service change
document.getElementById('servicio_id').addEventListener('change', function() {
    const servicioId = this.value;
    
    if (servicioId) {
        const option = this.options[this.selectedIndex];
        selectedServicio = {
            id: servicioId,
            duracion: option.dataset.duracion,
            precio: option.dataset.precio,
            descripcion: option.dataset.descripcion
        };
        
        document.getElementById('servicio_duracion').textContent = selectedServicio.duracion;
        document.getElementById('servicio_precio').textContent = parseFloat(selectedServicio.precio).toFixed(2);
        document.getElementById('servicio_descripcion').textContent = selectedServicio.descripcion;
        document.getElementById('servicio_info').classList.remove('hidden');
        
        loadEspecialistas();
    } else {
        document.getElementById('servicio_info').classList.add('hidden');
        document.getElementById('especialista_id').disabled = true;
    }
});

// Load especialistas
function loadEspecialistas() {
    const servicioId = document.getElementById('servicio_id').value;
    const sucursalId = document.getElementById('sucursal_id').value;
    
    if (!servicioId || !sucursalId) return;
    
    fetch(`${BASE_URL}/reservacion/getEspecialistas?servicio_id=${servicioId}&sucursal_id=${sucursalId}`)
        .then(response => response.json())
        .then(data => {
            const especialistaSelect = document.getElementById('especialista_id');
            especialistaSelect.innerHTML = '<option value="">-- Seleccione un especialista --</option>';
            
            data.especialistas.forEach(esp => {
                const option = document.createElement('option');
                option.value = esp.id;
                option.textContent = `${esp.nombre} ${esp.apellido} - ${esp.especialidad}`;
                especialistaSelect.appendChild(option);
            });
            
            especialistaSelect.disabled = false;
        });
}

// Especialista change
document.getElementById('especialista_id').addEventListener('change', function() {
    document.getElementById('fecha').disabled = !this.value;
});

// Date change
document.getElementById('fecha').addEventListener('change', function() {
    loadHorariosDisponibles();
});

// Load available times
function loadHorariosDisponibles() {
    const especialistaId = document.getElementById('especialista_id').value;
    const fecha = document.getElementById('fecha').value;
    
    if (!especialistaId || !fecha || !selectedServicio) return;
    
    document.getElementById('horarios_container').classList.add('hidden');
    document.getElementById('horarios_empty').classList.add('hidden');
    document.getElementById('horarios_loading').classList.remove('hidden');
    
    fetch(`${BASE_URL}/reservacion/getHorariosDisponibles?especialista_id=${especialistaId}&fecha=${fecha}&duracion=${selectedServicio.duracion}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('horarios_loading').classList.add('hidden');
            
            const horariosDiv = document.getElementById('horarios_disponibles');
            horariosDiv.innerHTML = '';
            
            if (data.horarios && data.horarios.length > 0) {
                data.horarios.forEach(horario => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'horario-btn px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition';
                    button.textContent = horario.hora;
                    button.dataset.datetime = horario.datetime;
                    button.onclick = () => selectHorario(button, horario.datetime);
                    horariosDiv.appendChild(button);
                });
                document.getElementById('horarios_container').classList.remove('hidden');
            } else {
                document.getElementById('horarios_empty').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('horarios_loading').classList.add('hidden');
            document.getElementById('horarios_empty').classList.remove('hidden');
        });
}

// Select time
function selectHorario(button, datetime) {
    // Remove previous selection
    document.querySelectorAll('.horario-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('border-blue-600', 'text-blue-600');
    });
    
    // Mark as selected
    button.classList.remove('border-blue-600', 'text-blue-600');
    button.classList.add('bg-blue-600', 'text-white');
    
    document.getElementById('fecha_hora').value = datetime;
    document.getElementById('submitBtn').disabled = false;
}
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
