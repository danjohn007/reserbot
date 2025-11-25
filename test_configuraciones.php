<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Configuraciones - ReserBot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-cog text-blue-600"></i> Test del Módulo de Configuraciones
            </h1>
            
            <?php
            // Load configuration
            require_once __DIR__ . '/config/config.php';
            
            $tests = [];
            $allPassed = true;
            
            // Test 1: Database Connection
            $dbTest = false;
            $db = null;
            try {
                $db = Database::getInstance();
                $connection = $db->getConnection();
                $dbTest = $connection !== null;
            } catch (Exception $e) {
                $dbTest = false;
            }
            $tests[] = [
                'name' => 'Conexión a Base de Datos',
                'status' => $dbTest,
                'message' => $dbTest ? 'Conectado exitosamente' : 'Error de conexión'
            ];
            $allPassed = $allPassed && $dbTest;
            
            // Test 2: Check if configuraciones table exists
            $tableTest = false;
            if ($dbTest) {
                try {
                    $result = $db->fetch("SHOW TABLES LIKE 'configuraciones'");
                    $tableTest = !empty($result);
                } catch (Exception $e) {
                    $tableTest = false;
                }
            }
            $tests[] = [
                'name' => 'Tabla configuraciones',
                'status' => $tableTest,
                'message' => $tableTest ? 'Tabla existe en la base de datos' : 'Tabla no encontrada'
            ];
            $allPassed = $allPassed && $tableTest;
            
            // Test 3: Count configurations
            $configCount = 0;
            $configCountTest = false;
            if ($tableTest) {
                try {
                    $result = $db->fetch("SELECT COUNT(*) as count FROM configuraciones");
                    $configCount = $result['count'];
                    $configCountTest = $configCount > 0;
                } catch (Exception $e) {
                    $configCountTest = false;
                }
            }
            $tests[] = [
                'name' => 'Configuraciones en Base de Datos',
                'status' => $configCountTest,
                'message' => $configCountTest ? "Se encontraron $configCount configuraciones" : 'No hay configuraciones'
            ];
            $allPassed = $allPassed && $configCountTest;
            
            // Test 4: Load Configuracion Model
            $modelTest = false;
            $configModel = null;
            try {
                require_once MODELS_PATH . 'Configuracion.php';
                $configModel = new Configuracion();
                $modelTest = $configModel !== null;
            } catch (Exception $e) {
                $modelTest = false;
            }
            $tests[] = [
                'name' => 'Modelo Configuracion',
                'status' => $modelTest,
                'message' => $modelTest ? 'Modelo cargado correctamente' : 'Error al cargar el modelo'
            ];
            $allPassed = $allPassed && $modelTest;
            
            // Test 5: Read a configuration value
            $readTest = false;
            $siteName = '';
            if ($modelTest) {
                try {
                    $siteName = $configModel->get('site_name', 'ReserBot');
                    $readTest = !empty($siteName);
                } catch (Exception $e) {
                    $readTest = false;
                }
            }
            $tests[] = [
                'name' => 'Leer Configuración',
                'status' => $readTest,
                'message' => $readTest ? "Nombre del sitio: $siteName" : 'Error al leer configuración'
            ];
            $allPassed = $allPassed && $readTest;
            
            // Test 6: Get all configurations
            $allConfigsTest = false;
            $allConfigs = [];
            if ($modelTest) {
                try {
                    $allConfigs = $configModel->getAll();
                    $allConfigsTest = !empty($allConfigs);
                } catch (Exception $e) {
                    $allConfigsTest = false;
                }
            }
            $tests[] = [
                'name' => 'Obtener Todas las Configuraciones',
                'status' => $allConfigsTest,
                'message' => $allConfigsTest ? 'Configuraciones cargadas: ' . count($allConfigs) : 'Error al cargar configuraciones'
            ];
            $allPassed = $allPassed && $allConfigsTest;
            
            // Test 7: Check AdminController exists
            $adminControllerTest = file_exists(CONTROLLERS_PATH . 'AdminController.php');
            $tests[] = [
                'name' => 'AdminController',
                'status' => $adminControllerTest,
                'message' => $adminControllerTest ? 'Controlador existe' : 'Controlador no encontrado'
            ];
            $allPassed = $allPassed && $adminControllerTest;
            
            // Test 8: Check configuraciones view exists
            $configViewTest = file_exists(VIEWS_PATH . 'admin/configuraciones.php');
            $tests[] = [
                'name' => 'Vista de Configuraciones',
                'status' => $configViewTest,
                'message' => $configViewTest ? 'Vista existe' : 'Vista no encontrada'
            ];
            $allPassed = $allPassed && $configViewTest;
            
            // Display results
            echo "<div class='mb-6'>";
            foreach ($tests as $test) {
                $icon = $test['status'] ? '<i class="fas fa-check-circle text-green-600"></i>' : '<i class="fas fa-times-circle text-red-600"></i>';
                $bgColor = $test['status'] ? 'bg-green-50' : 'bg-red-50';
                echo "<div class='mb-4 p-4 rounded-lg $bgColor'>";
                echo "<div class='flex items-start'>";
                echo "<div class='text-2xl mr-3'>$icon</div>";
                echo "<div class='flex-1'>";
                echo "<h3 class='font-semibold text-gray-800'>{$test['name']}</h3>";
                echo "<p class='text-sm text-gray-600'>{$test['message']}</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            
            // Display configuration list
            if ($allConfigsTest && !empty($allConfigs)) {
                echo "<div class='bg-blue-50 rounded-lg p-6 mb-6'>";
                echo "<h2 class='text-xl font-bold text-gray-800 mb-4'>";
                echo "<i class='fas fa-list text-blue-600'></i> Configuraciones Disponibles";
                echo "</h2>";
                
                // Group by category
                $grouped = [];
                foreach ($allConfigs as $config) {
                    $categoria = $config['categoria'] ?? 'general';
                    if (!isset($grouped[$categoria])) {
                        $grouped[$categoria] = [];
                    }
                    $grouped[$categoria][] = $config;
                }
                
                echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-6'>";
                
                $categoryNames = [
                    'general' => ['nombre' => 'General', 'icon' => 'fa-home', 'color' => 'blue'],
                    'email' => ['nombre' => 'Email / SMTP', 'icon' => 'fa-envelope', 'color' => 'green'],
                    'whatsapp' => ['nombre' => 'WhatsApp', 'icon' => 'fa-whatsapp', 'color' => 'emerald'],
                    'api' => ['nombre' => 'APIs', 'icon' => 'fa-plug', 'color' => 'purple'],
                    'colors' => ['nombre' => 'Colores', 'icon' => 'fa-palette', 'color' => 'pink']
                ];
                
                foreach ($grouped as $categoria => $configs) {
                    $catInfo = $categoryNames[$categoria] ?? ['nombre' => ucfirst($categoria), 'icon' => 'fa-cog', 'color' => 'gray'];
                    echo "<div class='bg-white rounded-lg p-4 shadow'>";
                    echo "<h3 class='font-semibold text-{$catInfo['color']}-600 mb-3'>";
                    echo "<i class='fas {$catInfo['icon']}'></i> {$catInfo['nombre']}";
                    echo " <span class='text-xs text-gray-500'>(" . count($configs) . ")</span>";
                    echo "</h3>";
                    echo "<ul class='space-y-2 text-sm'>";
                    foreach ($configs as $config) {
                        $valor = $config['valor'] ?: '<span class="text-gray-400">No configurado</span>';
                        if (strlen($valor) > 50) {
                            $valor = substr($valor, 0, 50) . '...';
                        }
                        echo "<li class='border-b border-gray-100 pb-2'>";
                        echo "<div class='font-medium text-gray-700'>" . htmlspecialchars($config['descripcion'] ?? $config['clave']) . "</div>";
                        echo "<div class='text-gray-600'>" . $valor . "</div>";
                        echo "</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                
                echo "</div>";
                echo "</div>";
            }
            ?>
            
            <div class="mt-8 p-6 rounded-lg <?php echo $allPassed ? 'bg-green-100' : 'bg-yellow-100'; ?>">
                <?php if ($allPassed): ?>
                    <h2 class="text-2xl font-bold text-green-800 mb-2">
                        <i class="fas fa-thumbs-up"></i> ¡Módulo de Configuraciones Funcionando!
                    </h2>
                    <p class="text-green-700 mb-4">
                        El módulo de configuraciones está correctamente instalado y funcionando. Puede acceder al panel de configuraciones desde el área de administración.
                    </p>
                    <div class="space-x-4">
                        <a href="<?php echo BASE_URL; ?>" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
                            <i class="fas fa-home"></i> Ir al Inicio
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/login" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                    </div>
                    <div class="mt-4 p-4 bg-green-50 rounded">
                        <p class="text-sm text-green-800">
                            <strong>Nota:</strong> Para acceder al módulo de configuraciones, inicie sesión como Superadministrador y vaya a:
                            <br>
                            <code class="bg-green-200 px-2 py-1 rounded">Dashboard → Configuraciones</code>
                        </p>
                    </div>
                <?php else: ?>
                    <h2 class="text-2xl font-bold text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle"></i> Algunos tests fallaron
                    </h2>
                    <p class="text-yellow-700">
                        Por favor, revise los errores arriba y corrija los problemas antes de continuar.
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-info-circle"></i> Información del Sistema
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Versión:</span>
                        <span class="font-semibold"><?php echo APP_VERSION; ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">PHP:</span>
                        <span class="font-semibold"><?php echo phpversion(); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Base de Datos:</span>
                        <span class="font-semibold"><?php echo DB_NAME; ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Entorno:</span>
                        <span class="font-semibold"><?php echo APP_ENV; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>ReserBot - Sistema de Reservaciones y Citas Profesionales</p>
                <p class="mt-2">
                    <a href="<?php echo BASE_URL; ?>/test_connection.php" class="text-blue-600 hover:underline">
                        <i class="fas fa-vial"></i> Ver Test de Conexión
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
