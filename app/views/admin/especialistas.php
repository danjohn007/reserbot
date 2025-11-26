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
        <i class="fas fa-user-md"></i> Gestionar Especialistas
    </h1>
    <p class="text-gray-600 mt-2">Administre los profesionales del sistema</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Especialistas Registrados</h2>
        <button onclick="window.alert('Función de agregar especialista próximamente')" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Nuevo Especialista
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($especialistas as $especialista): ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-3xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <?php echo htmlspecialchars($especialista['nombre'] . ' ' . $especialista['apellido']); ?>
                                </h3>
                                <p class="text-sm text-gray-600">
                                    <?php echo htmlspecialchars($especialista['titulo'] ?? $especialista['especialidad']); ?>
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded <?php echo $especialista['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $especialista['activo'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                        
                        <div class="mt-4 space-y-2 text-sm text-gray-600">
                            <p>
                                <i class="fas fa-envelope text-blue-600"></i>
                                <?php echo htmlspecialchars($especialista['email']); ?>
                            </p>
                            <p>
                                <i class="fas fa-building text-blue-600"></i>
                                <?php echo htmlspecialchars($especialista['sucursal_nombre']); ?>
                            </p>
                            <p>
                                <i class="fas fa-star text-yellow-500"></i>
                                Calificación: <?php echo number_format($especialista['calificacion_promedio'], 1); ?> 
                                (<?php echo $especialista['total_calificaciones']; ?> reseñas)
                            </p>
                            <?php if ($especialista['cedula_profesional']): ?>
                                <p>
                                    <i class="fas fa-id-card text-blue-600"></i>
                                    Cédula: <?php echo htmlspecialchars($especialista['cedula_profesional']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($especialista['biografia']): ?>
                            <p class="mt-3 text-sm text-gray-700">
                                <?php echo htmlspecialchars(substr($especialista['biografia'], 0, 150)) . '...'; ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                            <button onclick="window.alert('Ver perfil próximamente')" 
                                    class="flex-1 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                                <i class="fas fa-eye"></i> Ver Perfil
                            </button>
                            <button onclick="window.alert('Edición próximamente')" 
                                    class="flex-1 bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-sm">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($especialistas)): ?>
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-user-md text-6xl mb-4"></i>
            <p class="text-lg">No hay especialistas registrados</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
