<?php
// Prevent direct access to this file
if (!defined('VIEWS_PATH')) {
    http_response_code(403);
    die('Forbidden: Direct access not allowed.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? APP_NAME; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    
    <script>
        // Make BASE_URL available to JavaScript
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold text-blue-600">
                            <i class="fas fa-calendar-check"></i> <?php echo APP_NAME; ?>
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Logged in navigation -->
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo BASE_URL; ?>/dashboard" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            
                            <?php if ($_SESSION['rol_id'] == ROLE_CLIENTE): ?>
                                <a href="<?php echo BASE_URL; ?>/reservacion/nueva" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                    <i class="fas fa-plus"></i> Nueva Reservación
                                </a>
                            <?php endif; ?>
                            
                            <div class="relative group">
                                <button class="flex items-center text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-user-circle text-2xl"></i>
                                    <span class="ml-2"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                </button>
                                
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block z-10">
                                    <a href="<?php echo BASE_URL; ?>/perfil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user"></i> Mi Perfil
                                    </a>
                                    <?php if (in_array($_SESSION['rol_id'], [ROLE_SUPERADMIN, ROLE_ADMIN])): ?>
                                        <a href="<?php echo BASE_URL; ?>/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog"></i> Administración
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Guest navigation -->
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo BASE_URL; ?>/auth/login" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                            <a href="<?php echo BASE_URL; ?>/auth/register" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php
    $successMsg = null;
    $errorMsg = null;
    
    if (isset($_SESSION['flash']['success'])) {
        $successMsg = $_SESSION['flash']['success'];
        unset($_SESSION['flash']['success']);
    }
    
    if (isset($_SESSION['flash']['error'])) {
        $errorMsg = $_SESSION['flash']['error'];
        unset($_SESSION['flash']['error']);
    }
    ?>
    
    <?php if ($successMsg): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?php echo $successMsg; ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($errorMsg): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo $errorMsg; ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
