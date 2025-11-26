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
        <i class="fas fa-briefcase"></i> Gestionar Servicios
    </h1>
    <p class="text-gray-600 mt-2">Administre el catálogo de servicios</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Servicios Registrados</h2>
        <button onclick="window.alert('Función de agregar servicio próximamente')" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($servicios as $servicio): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $servicio['id']; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="font-medium"><?php echo htmlspecialchars($servicio['nombre']); ?></div>
                            <div class="text-gray-500 text-xs"><?php echo htmlspecialchars($servicio['descripcion'] ?? ''); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo htmlspecialchars($servicio['categoria_nombre']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $servicio['duracion']; ?> min
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            $<?php echo number_format($servicio['precio'], 2); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $servicio['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $servicio['activo'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="window.alert('Edición próximamente')" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (empty($servicios)): ?>
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-briefcase text-6xl mb-4"></i>
            <p class="text-lg">No hay servicios registrados</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
