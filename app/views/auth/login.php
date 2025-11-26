<?php
// Prevent direct access to this file
if (!defined('VIEWS_PATH')) {
    http_response_code(403);
    die('Forbidden: Direct access not allowed.');
}
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="text-center text-6xl text-blue-600 mb-4">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Iniciar Sesión
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                O
                <a href="<?php echo BASE_URL; ?>/auth/register" class="font-medium text-blue-600 hover:text-blue-500">
                    crea una cuenta nueva
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="<?php echo BASE_URL; ?>/auth/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="usuario@ejemplo.com">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock"></i> Contraseña
                    </label>
                    <input id="password" name="password" type="password" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </div>
        </form>
        
        <!-- Credenciales de Prueba -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-info-circle"></i> Credenciales de Prueba
            </h3>
            <div class="text-xs text-gray-600 space-y-2">
                <div>
                    <strong>Superadmin:</strong> admin@reserbot.com / ReserBot2024
                </div>
                <div>
                    <strong>Admin Centro:</strong> admin.centro@reserbot.com / ReserBot2024
                </div>
                <div>
                    <strong>Especialista:</strong> ana.lopez@reserbot.com / ReserBot2024
                </div>
                <div>
                    <strong>Cliente:</strong> juan.perez@email.com / ReserBot2024
                </div>
                <div>
                    <strong>Recepcionista:</strong> sofia.torres@reserbot.com / ReserBot2024
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
