<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - ReserBot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-vial text-blue-600"></i> Test de Instalación ReserBot
            </h1>
            
            <?php
            // Load configuration
            require_once __DIR__ . '/config/config.php';
            
            $tests = [];
            $allPassed = true;
            
            // Test 1: PHP Version
            $phpVersion = phpversion();
            $phpTest = version_compare($phpVersion, '7.4.0', '>=');
            $tests[] = [
                'name' => 'Versión de PHP',
                'status' => $phpTest,
                'message' => $phpTest ? "PHP $phpVersion (✓ Compatible)" : "PHP $phpVersion (✗ Se requiere PHP 7.4 o superior)"
            ];
            $allPassed = $allPassed && $phpTest;
            
            // Test 2: Base URL Detection
            $baseUrlTest = !empty(BASE_URL);
            $tests[] = [
                'name' => 'Detección de URL Base',
                'status' => $baseUrlTest,
                'message' => $baseUrlTest ? BASE_URL : 'No se pudo detectar la URL base'
            ];
            $allPassed = $allPassed && $baseUrlTest;
            
            // Test 3: Directory Structure
            $requiredDirs = ['app/controllers', 'app/models', 'app/views', 'config', 'public', 'logs'];
            $dirsExist = true;
            $missingDirs = [];
            foreach ($requiredDirs as $dir) {
                if (!is_dir(BASE_PATH . '/' . $dir)) {
                    $dirsExist = false;
                    $missingDirs[] = $dir;
                }
            }
            $tests[] = [
                'name' => 'Estructura de Directorios',
                'status' => $dirsExist,
                'message' => $dirsExist ? 'Todos los directorios existen' : 'Faltan directorios: ' . implode(', ', $missingDirs)
            ];
            $allPassed = $allPassed && $dirsExist;
            
            // Test 4: Logs Directory Writable
            $logsWritable = is_writable(LOGS_PATH);
            $tests[] = [
                'name' => 'Directorio logs escribible',
                'status' => $logsWritable,
                'message' => $logsWritable ? 'El directorio logs tiene permisos de escritura' : 'El directorio logs no tiene permisos de escritura'
            ];
            $allPassed = $allPassed && $logsWritable;
            
            // Test 5: PDO Extension
            $pdoAvailable = extension_loaded('pdo') && extension_loaded('pdo_mysql');
            $tests[] = [
                'name' => 'Extensión PDO MySQL',
                'status' => $pdoAvailable,
                'message' => $pdoAvailable ? 'PDO MySQL está disponible' : 'PDO MySQL no está disponible'
            ];
            $allPassed = $allPassed && $pdoAvailable;
            
            // Test 6: Database Connection
            $dbTest = false;
            $dbMessage = '';
            try {
                $db = Database::getInstance();
                $connection = $db->getConnection();
                $dbTest = $connection !== null;
                $dbMessage = $dbTest ? 'Conexión exitosa a la base de datos' : 'No se pudo conectar a la base de datos';
                
                // Try to fetch database name
                if ($dbTest) {
                    $stmt = $connection->query("SELECT DATABASE() as db_name");
                    $result = $stmt->fetch();
                    $dbMessage .= " (Base de datos: {$result['db_name']})";
                }
            } catch (Exception $e) {
                $dbTest = false;
                $dbMessage = 'Error: ' . $e->getMessage();
            }
            $tests[] = [
                'name' => 'Conexión a Base de Datos',
                'status' => $dbTest,
                'message' => $dbMessage
            ];
            $allPassed = $allPassed && $dbTest;
            
            // Test 7: Check if tables exist
            $tablesTest = false;
            $tablesMessage = '';
            if ($dbTest) {
                try {
                    $db = Database::getInstance();
                    $tables = $db->fetchAll("SHOW TABLES");
                    $tablesTest = count($tables) > 0;
                    $tablesMessage = $tablesTest ? 
                        'Se encontraron ' . count($tables) . ' tablas en la base de datos' : 
                        'No se encontraron tablas. Por favor, ejecute el script database.sql';
                } catch (Exception $e) {
                    $tablesTest = false;
                    $tablesMessage = 'Error al verificar tablas: ' . $e->getMessage();
                }
            } else {
                $tablesMessage = 'No se puede verificar (sin conexión a DB)';
            }
            $tests[] = [
                'name' => 'Tablas de Base de Datos',
                'status' => $tablesTest,
                'message' => $tablesMessage
            ];
            $allPassed = $allPassed && $tablesTest;
            
            // Display results
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
            ?>
            
            <div class="mt-8 p-6 rounded-lg <?php echo $allPassed ? 'bg-green-100' : 'bg-yellow-100'; ?>">
                <?php if ($allPassed): ?>
                    <h2 class="text-2xl font-bold text-green-800 mb-2">
                        <i class="fas fa-thumbs-up"></i> ¡Todo está listo!
                    </h2>
                    <p class="text-green-700 mb-4">
                        La instalación de ReserBot se completó exitosamente. Puede comenzar a usar el sistema.
                    </p>
                    <a href="<?php echo BASE_URL; ?>" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
                        <i class="fas fa-home"></i> Ir a la Página Principal
                    </a>
                <?php else: ?>
                    <h2 class="text-2xl font-bold text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle"></i> Atención requerida
                    </h2>
                    <p class="text-yellow-700">
                        Algunos tests fallaron. Por favor, revise los errores arriba y corrija los problemas antes de continuar.
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>ReserBot v<?php echo APP_VERSION; ?> - Sistema de Reservaciones y Citas</p>
            </div>
        </div>
    </div>
</body>
</html>
