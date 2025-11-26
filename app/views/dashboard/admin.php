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
        <i class="fas fa-cog"></i> Panel de Administración
    </h1>
    <p class="text-gray-600 mt-2">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3 text-white">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Reservaciones</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['total'] ?? 0; ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3 text-white">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Pendientes</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['pendientes'] ?? 0; ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3 text-white">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Completadas</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['completadas'] ?? 0; ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3 text-white">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Ingresos</p>
                <p class="text-2xl font-semibold text-gray-900">$<?php echo number_format($stats['ingresos_total'] ?? 0, 2); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <a href="<?php echo BASE_URL; ?>/admin/sucursales" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition text-center">
        <i class="fas fa-building text-4xl text-blue-600 mb-3"></i>
        <h3 class="text-lg font-semibold text-gray-800">Gestionar Sucursales</h3>
        <p class="text-sm text-gray-600 mt-2">Administrar ubicaciones</p>
    </a>
    
    <a href="<?php echo BASE_URL; ?>/admin/especialistas" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition text-center">
        <i class="fas fa-user-md text-4xl text-green-600 mb-3"></i>
        <h3 class="text-lg font-semibold text-gray-800">Gestionar Especialistas</h3>
        <p class="text-sm text-gray-600 mt-2">Administrar profesionales</p>
    </a>
    
    <a href="<?php echo BASE_URL; ?>/admin/servicios" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition text-center">
        <i class="fas fa-briefcase text-4xl text-purple-600 mb-3"></i>
        <h3 class="text-lg font-semibold text-gray-800">Gestionar Servicios</h3>
        <p class="text-sm text-gray-600 mt-2">Catálogo de servicios</p>
    </a>
</div>

<!-- Recent Reservations -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-list"></i> Reservaciones Recientes
    </h2>
    
    <?php if (!empty($reservaciones)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($reservaciones as $reservacion): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                #<?php echo $reservacion['id']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($reservacion['cliente_nombre'] . ' ' . $reservacion['cliente_apellido']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($reservacion['especialista_nombre'] . ' ' . $reservacion['especialista_apellido']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('d/m/Y H:i', strtotime($reservacion['fecha_hora'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'confirmada' => 'bg-blue-100 text-blue-800',
                                    'completada' => 'bg-green-100 text-green-800',
                                    'cancelada' => 'bg-red-100 text-red-800'
                                ];
                                $color = $statusColors[$reservacion['estado']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
                                    <?php echo ucfirst($reservacion['estado']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo BASE_URL; ?>/reservacion/ver/<?php echo $reservacion['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-5xl mb-3"></i>
            <p>No hay reservaciones aún</p>
        </div>
    <?php endif; ?>
</div>

<!-- System Info (Superadmin only) -->
<?php if ($_SESSION['rol_id'] == ROLE_SUPERADMIN): ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-3">
            <i class="fas fa-building"></i> Sucursales
        </h3>
        <p class="text-3xl font-bold text-blue-600">
            <?php echo count($sucursales ?? []); ?>
        </p>
        <a href="<?php echo BASE_URL; ?>/admin/sucursales" class="text-sm text-blue-600 hover:underline mt-2 inline-block">
            Ver todas →
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-3">
            <i class="fas fa-user-md"></i> Especialistas
        </h3>
        <p class="text-3xl font-bold text-green-600">
            <?php echo count($especialistas ?? []); ?>
        </p>
        <a href="<?php echo BASE_URL; ?>/admin/especialistas" class="text-sm text-green-600 hover:underline mt-2 inline-block">
            Ver todos →
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-3">
            <i class="fas fa-cog"></i> Configuraciones
        </h3>
        <p class="text-sm text-gray-600 mb-3">
            Personalizar el sistema
        </p>
        <a href="<?php echo BASE_URL; ?>/admin/configuraciones" class="text-sm text-purple-600 hover:underline inline-block">
            Ir a configuraciones →
        </a>
    </div>
</div>
<?php endif; ?>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
