<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-chart-bar"></i> Reportes y Estadísticas
    </h1>
    <p class="text-gray-600 mt-2">Analice el rendimiento del sistema</p>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>/admin/reportes" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar"></i> Desde
            </label>
            <input type="date" name="fecha_desde" id="fecha_desde" 
                   value="<?php echo $fecha_desde; ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        
        <div class="flex-1 min-w-[200px]">
            <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar"></i> Hasta
            </label>
            <input type="date" name="fecha_hasta" id="fecha_hasta" 
                   value="<?php echo $fecha_hasta; ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        
        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </div>
        
        <div>
            <button type="button" onclick="window.print()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </form>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3 text-white">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total</p>
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

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Distribución por Estado</h3>
        <div class="space-y-3">
            <?php
            $total = $stats['total'] ?? 1;
            $estados = [
                ['nombre' => 'Pendientes', 'valor' => $stats['pendientes'] ?? 0, 'color' => 'bg-yellow-500'],
                ['nombre' => 'Confirmadas', 'valor' => $stats['confirmadas'] ?? 0, 'color' => 'bg-blue-500'],
                ['nombre' => 'Completadas', 'valor' => $stats['completadas'] ?? 0, 'color' => 'bg-green-500'],
                ['nombre' => 'Canceladas', 'valor' => $stats['canceladas'] ?? 0, 'color' => 'bg-red-500']
            ];
            
            foreach ($estados as $estado):
                $porcentaje = $total > 0 ? ($estado['valor'] / $total) * 100 : 0;
            ?>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-700"><?php echo $estado['nombre']; ?></span>
                        <span class="text-sm text-gray-700"><?php echo $estado['valor']; ?> (<?php echo number_format($porcentaje, 1); ?>%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="<?php echo $estado['color']; ?> h-2.5 rounded-full" 
                             style="width: <?php echo $porcentaje; ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Resumen Financiero</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-green-700">$<?php echo number_format($stats['ingresos_total'] ?? 0, 2); ?></p>
                </div>
                <i class="fas fa-money-bill-wave text-4xl text-green-600"></i>
            </div>
            
            <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">Ingreso Promedio</p>
                    <p class="text-2xl font-bold text-blue-700">
                        $<?php echo number_format(($stats['completadas'] ?? 0) > 0 ? ($stats['ingresos_total'] ?? 0) / $stats['completadas'] : 0, 2); ?>
                    </p>
                </div>
                <i class="fas fa-chart-line text-4xl text-blue-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Detailed List -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Listado Detallado</h2>
    
    <?php if (!empty($reservaciones)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($reservaciones as $reservacion): ?>
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
                                <?php echo htmlspecialchars($reservacion['servicio_nombre']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($reservacion['especialista_nombre'] . ' ' . $reservacion['especialista_apellido']); ?>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $<?php echo number_format($reservacion['precio'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p class="text-lg">No hay datos para el período seleccionado</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
