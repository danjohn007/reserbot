<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-building"></i> Gestionar Sucursales
    </h1>
    <p class="text-gray-600 mt-2">Administre las ubicaciones del sistema</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Sucursales Registradas</h2>
        <button onclick="window.alert('Función de agregar sucursal próximamente')" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Nueva Sucursal
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($sucursales as $sucursal): ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <?php echo htmlspecialchars($sucursal['nombre']); ?>
                    </h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded <?php echo $sucursal['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo $sucursal['activo'] ? 'Activa' : 'Inactiva'; ?>
                    </span>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <p>
                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                        <?php echo htmlspecialchars($sucursal['direccion'] ?? 'N/A'); ?>
                    </p>
                    <p>
                        <i class="fas fa-city text-blue-600"></i>
                        <?php echo htmlspecialchars($sucursal['ciudad'] . ', ' . $sucursal['estado']); ?>
                    </p>
                    <p>
                        <i class="fas fa-phone text-blue-600"></i>
                        <?php echo htmlspecialchars($sucursal['telefono'] ?? 'N/A'); ?>
                    </p>
                    <p>
                        <i class="fas fa-envelope text-blue-600"></i>
                        <?php echo htmlspecialchars($sucursal['email'] ?? 'N/A'); ?>
                    </p>
                    <p>
                        <i class="fas fa-clock text-blue-600"></i>
                        <?php echo date('H:i', strtotime($sucursal['horario_apertura'])); ?> - 
                        <?php echo date('H:i', strtotime($sucursal['horario_cierre'])); ?>
                    </p>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                    <button onclick="window.alert('Edición próximamente')" 
                            class="flex-1 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button onclick="window.alert('Función próximamente')" 
                            class="flex-1 bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-sm">
                        <i class="fas fa-eye"></i> Ver
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($sucursales)): ?>
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-building text-6xl mb-4"></i>
            <p class="text-lg">No hay sucursales registradas</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
