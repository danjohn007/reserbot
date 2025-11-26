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
        <i class="fas fa-tachometer-alt"></i> Mi Dashboard
    </h1>
    <p class="text-gray-600 mt-2">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></p>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-bolt"></i> Acciones Rápidas
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="<?php echo BASE_URL; ?>/reservacion/nueva" 
           class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 text-center transition">
            <i class="fas fa-plus-circle text-3xl mb-2"></i>
            <div class="font-semibold">Nueva Reservación</div>
        </a>
        <a href="#" 
           class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 text-center transition">
            <i class="fas fa-history text-3xl mb-2"></i>
            <div class="font-semibold">Mi Historial</div>
        </a>
    </div>
</div>

<!-- Próximas Citas -->
<?php if (!empty($proximas)): ?>
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-calendar-alt"></i> Próximas Citas
    </h2>
    <div class="space-y-4">
        <?php foreach ($proximas as $reservacion): ?>
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                <?php echo ucfirst($reservacion['estado']); ?>
                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                #<?php echo $reservacion['id']; ?>
                            </span>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800">
                            <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
                        </h3>
                        <p class="text-gray-600 text-sm">
                            <i class="fas fa-user-md"></i>
                            <?php echo htmlspecialchars($reservacion['especialista_nombre'] . ' ' . $reservacion['especialista_apellido']); ?>
                        </p>
                        <p class="text-gray-600 text-sm">
                            <i class="fas fa-calendar"></i>
                            <?php echo date('d/m/Y', strtotime($reservacion['fecha_hora'])); ?>
                        </p>
                        <p class="text-gray-600 text-sm">
                            <i class="fas fa-clock"></i>
                            <?php echo date('H:i', strtotime($reservacion['fecha_hora'])); ?>
                        </p>
                        <p class="text-gray-600 text-sm">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($reservacion['sucursal_nombre']); ?>
                        </p>
                    </div>
                    <div class="ml-4">
                        <a href="<?php echo BASE_URL; ?>/reservacion/ver/<?php echo $reservacion['id']; ?>" 
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
    <div class="text-gray-400 text-6xl mb-4">
        <i class="fas fa-calendar-times"></i>
    </div>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes citas próximas</h3>
    <p class="text-gray-600 mb-4">Agenda tu primera cita ahora</p>
    <a href="<?php echo BASE_URL; ?>/reservacion/nueva" 
       class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 inline-block">
        <i class="fas fa-plus"></i> Nueva Reservación
    </a>
</div>
<?php endif; ?>

<!-- Todas las Reservaciones -->
<?php if (!empty($reservaciones)): ?>
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-list"></i> Todas Mis Reservaciones
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Servicio
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Especialista
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha/Hora
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($reservaciones as $reservacion): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            #<?php echo $reservacion['id']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo htmlspecialchars($reservacion['especialista_nombre'] . ' ' . $reservacion['especialista_apellido']); ?>
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
</div>
<?php endif; ?>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
