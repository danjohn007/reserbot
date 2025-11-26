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
        <i class="fas fa-calendar-check"></i> Detalles de Reservación #<?php echo $reservacion['id']; ?>
    </h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">
                        <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
                    </h2>
                    <?php
                    $statusColors = [
                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                        'confirmada' => 'bg-blue-100 text-blue-800',
                        'completada' => 'bg-green-100 text-green-800',
                        'cancelada' => 'bg-red-100 text-red-800'
                    ];
                    $color = $statusColors[$reservacion['estado']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded-full <?php echo $color; ?>">
                        <?php echo ucfirst($reservacion['estado']); ?>
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">
                        $<?php echo number_format($reservacion['precio'], 2); ?>
                    </p>
                    <p class="text-sm text-gray-500">
                        <?php echo $reservacion['duracion']; ?> minutos
                    </p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">
                        <i class="fas fa-calendar-alt text-blue-600"></i> Fecha y Hora
                    </h3>
                    <p class="text-gray-800 font-medium">
                        <?php echo date('l, d \d\e F \d\e Y', strtotime($reservacion['fecha_hora'])); ?>
                    </p>
                    <p class="text-gray-800 font-medium">
                        <?php echo date('H:i', strtotime($reservacion['fecha_hora'])); ?>
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">
                        <i class="fas fa-map-marker-alt text-blue-600"></i> Sucursal
                    </h3>
                    <p class="text-gray-800"><?php echo htmlspecialchars($reservacion['sucursal_nombre']); ?></p>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($reservacion['sucursal_direccion']); ?></p>
                </div>
            </div>
            
            <?php if ($reservacion['notas']): ?>
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-blue-600"></i> Notas
                    </h3>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($reservacion['notas'])); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($reservacion['motivo_cancelacion']): ?>
                <div class="mt-6 p-4 bg-red-50 rounded-lg">
                    <h3 class="font-semibold text-red-700 mb-2">
                        <i class="fas fa-times-circle"></i> Motivo de Cancelación
                    </h3>
                    <p class="text-red-700"><?php echo htmlspecialchars($reservacion['motivo_cancelacion']); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Cliente Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-user text-blue-600"></i> Cliente
            </h3>
            <p class="text-gray-800 font-medium">
                <?php echo htmlspecialchars($reservacion['cliente_nombre'] . ' ' . $reservacion['cliente_apellido']); ?>
            </p>
            <p class="text-sm text-gray-600">
                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($reservacion['cliente_email']); ?>
            </p>
            <?php if ($reservacion['cliente_telefono']): ?>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($reservacion['cliente_telefono']); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Especialista Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-md text-blue-600"></i> Especialista
            </h3>
            <p class="text-gray-800 font-medium">
                <?php echo htmlspecialchars($reservacion['especialista_nombre'] . ' ' . $reservacion['especialista_apellido']); ?>
            </p>
            <p class="text-sm text-gray-600">
                <?php echo htmlspecialchars($reservacion['especialidad']); ?>
            </p>
        </div>
        
        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-cog"></i> Acciones
            </h3>
            
            <?php if ($reservacion['estado'] == 'pendiente' || $reservacion['estado'] == 'confirmada'): ?>
                <?php if ($_SESSION['rol_id'] == ROLE_CLIENTE && $reservacion['cliente_id'] == $_SESSION['user_id']): ?>
                    <button onclick="cancelarReservacion()" 
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 mb-2">
                        <i class="fas fa-times-circle"></i> Cancelar Reservación
                    </button>
                <?php endif; ?>
            <?php endif; ?>
            
            <a href="<?php echo BASE_URL; ?>/dashboard" 
               class="block w-full text-center bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<script>
function cancelarReservacion() {
    const motivo = prompt('Por favor, indique el motivo de la cancelación (opcional):');
    
    if (motivo === null) return; // User clicked cancel
    
    if (!confirm('¿Está seguro de que desea cancelar esta reservación?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('csrf_token', '<?php echo $csrf_token; ?>');
    formData.append('motivo', motivo);
    
    fetch(`${BASE_URL}/reservacion/cancelar/<?php echo $reservacion['id']; ?>`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reservación cancelada exitosamente');
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cancelar la reservación');
    });
}
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
