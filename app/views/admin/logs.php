<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-shield-alt"></i> Logs de Seguridad
    </h1>
    <p class="text-gray-600 mt-2">Auditoría completa de acciones en el sistema</p>
</div>

<!-- Summary -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-600">Total de Eventos Registrados</p>
            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total); ?></p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Página <?php echo $page; ?> de <?php echo $totalPages; ?></p>
        </div>
    </div>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Registro de Eventos</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($logs as $log): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo date('d/m/Y H:i:s', strtotime($log['fecha_hora'])); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $typeColors = [
                                'login' => 'bg-green-100 text-green-800',
                                'logout' => 'bg-blue-100 text-blue-800',
                                'failed_login' => 'bg-red-100 text-red-800',
                                'registro' => 'bg-purple-100 text-purple-800',
                                'account_blocked' => 'bg-red-100 text-red-800',
                                'reservacion_creada' => 'bg-green-100 text-green-800',
                                'reservacion_cancelada' => 'bg-yellow-100 text-yellow-800',
                                'reservacion_actualizada' => 'bg-blue-100 text-blue-800',
                                'config_actualizada' => 'bg-purple-100 text-purple-800'
                            ];
                            $color = $typeColors[$log['tipo']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
                                <?php echo htmlspecialchars($log['tipo']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php if ($log['usuario_id']): ?>
                                <div>
                                    <div class="font-medium">
                                        <?php echo htmlspecialchars($log['nombre'] . ' ' . $log['apellido']); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars($log['email']); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400">Sistema</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <?php echo htmlspecialchars($log['descripcion']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($log['ip'] ?? 'N/A'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-6 flex justify-center">
            <nav class="flex space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" 
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php endif; ?>
                
                <span class="px-4 py-2 bg-gray-200 rounded">
                    Página <?php echo $page; ?> de <?php echo $totalPages; ?>
                </span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" 
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
