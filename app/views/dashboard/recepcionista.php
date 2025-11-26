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
        <i class="fas fa-headset"></i> Panel de Recepción
    </h1>
    <p class="text-gray-600 mt-2">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></p>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">
            <i class="fas fa-calendar-day"></i> Citas de Hoy
        </h2>
        <p class="text-4xl font-bold"><?php echo count($hoy_reservaciones ?? []); ?></p>
    </div>
    
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">
            <i class="fas fa-clock"></i> Pendientes
        </h2>
        <p class="text-4xl font-bold"><?php echo count($pendientes ?? []); ?></p>
    </div>
</div>

<!-- Citas de Hoy -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-calendar-check"></i> Citas Programadas para Hoy
    </h2>
    
    <?php if (!empty($hoy_reservaciones)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($hoy_reservaciones as $reservacion): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                <?php echo date('H:i', strtotime($reservacion['fecha_hora'])); ?>
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
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <?php if ($reservacion['estado'] == 'pendiente'): ?>
                                    <button onclick="updateStatus(<?php echo $reservacion['id']; ?>, 'confirmada')"
                                            class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-check"></i> Confirmar
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-calendar-times text-5xl mb-3"></i>
            <p>No hay citas programadas para hoy</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pendientes -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-clock"></i> Reservaciones Pendientes
    </h2>
    
    <?php if (!empty($pendientes)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($pendientes as $reservacion): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                #<?php echo $reservacion['id']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('d/m/Y H:i', strtotime($reservacion['fecha_hora'])); ?>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo BASE_URL; ?>/reservacion/ver/<?php echo $reservacion['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <button onclick="updateStatus(<?php echo $reservacion['id']; ?>, 'confirmada')"
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check"></i> Confirmar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-check-circle text-5xl mb-3"></i>
            <p>No hay reservaciones pendientes</p>
        </div>
    <?php endif; ?>
</div>

<script>
function updateStatus(reservacionId, nuevoEstado) {
    if (!confirm('¿Está seguro de confirmar esta reservación?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('csrf_token', '<?php echo isset($csrf_token) ? $csrf_token : ''; ?>');
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
