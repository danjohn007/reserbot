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
        <i class="fas fa-user-md"></i> Panel de Especialista
    </h1>
    <p class="text-gray-600 mt-2">
        <?php echo htmlspecialchars($especialista['titulo'] ?? ''); ?> 
        <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?>
    </p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3 text-white">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Citas</p>
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
                <i class="fas fa-star text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Calificación</p>
                <p class="text-2xl font-semibold text-gray-900">
                    <?php echo number_format($especialista['calificacion_promedio'] ?? 0, 1); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Citas de Hoy -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-calendar-day"></i> Citas de Hoy
    </h2>
    
    <?php if (!empty($hoy_reservaciones)): ?>
        <div class="space-y-4">
            <?php foreach ($hoy_reservaciones as $reservacion): ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <?php
                                $statusColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'confirmada' => 'bg-blue-100 text-blue-800',
                                    'completada' => 'bg-green-100 text-green-800',
                                    'cancelada' => 'bg-red-100 text-red-800'
                                ];
                                $color = $statusColors[$reservacion['estado']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="<?php echo $color; ?> text-xs font-semibold px-2 py-1 rounded">
                                    <?php echo ucfirst($reservacion['estado']); ?>
                                </span>
                                <span class="ml-2 text-sm text-gray-500">
                                    #<?php echo $reservacion['id']; ?>
                                </span>
                            </div>
                            <h3 class="font-semibold text-lg text-gray-800">
                                <i class="fas fa-clock"></i>
                                <?php echo date('H:i', strtotime($reservacion['fecha_hora'])); ?> - 
                                <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
                            </h3>
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($reservacion['cliente_nombre'] . ' ' . $reservacion['cliente_apellido']); ?>
                            </p>
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-phone"></i>
                                <?php echo htmlspecialchars($reservacion['cliente_email']); ?>
                            </p>
                            <?php if ($reservacion['notas']): ?>
                                <p class="text-gray-600 text-sm mt-2">
                                    <i class="fas fa-sticky-note"></i>
                                    <?php echo htmlspecialchars($reservacion['notas']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4 space-y-2">
                            <a href="<?php echo BASE_URL; ?>/reservacion/ver/<?php echo $reservacion['id']; ?>" 
                               class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm text-center">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <?php if ($reservacion['estado'] == 'pendiente'): ?>
                                <button onclick="updateStatus(<?php echo $reservacion['id']; ?>, 'confirmada')"
                                        class="block w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                                    <i class="fas fa-check"></i> Confirmar
                                </button>
                            <?php elseif ($reservacion['estado'] == 'confirmada'): ?>
                                <button onclick="updateStatus(<?php echo $reservacion['id']; ?>, 'completada')"
                                        class="block w-full bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-sm">
                                    <i class="fas fa-check-double"></i> Completar
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-calendar-times text-5xl mb-3"></i>
            <p>No tienes citas programadas para hoy</p>
        </div>
    <?php endif; ?>
</div>

<!-- Próximas Citas -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-calendar-alt"></i> Próximas Citas
    </h2>
    
    <?php if (!empty($proximas_reservaciones)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($proximas_reservaciones as $reservacion): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('d/m/Y H:i', strtotime($reservacion['fecha_hora'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($reservacion['cliente_nombre'] . ' ' . $reservacion['cliente_apellido']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
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
            <i class="fas fa-calendar-times text-5xl mb-3"></i>
            <p>No tienes próximas citas programadas</p>
        </div>
    <?php endif; ?>
</div>

<script>
function updateStatus(reservacionId, nuevoEstado) {
    if (!confirm('¿Está seguro de actualizar el estado de esta reservación?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('csrf_token', '<?php echo $this->generateCsrfToken(); ?>');
    formData.append('estado', nuevoEstado);
    
    fetch(`${BASE_URL}/reservacion/actualizarEstado/${reservacionId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado');
    });
}
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
