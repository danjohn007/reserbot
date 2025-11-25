<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="text-center text-6xl text-blue-600 mb-4">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                O
                <a href="<?php echo BASE_URL; ?>/auth/login" class="font-medium text-blue-600 hover:text-blue-500">
                    inicia sesión si ya tienes cuenta
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="<?php echo BASE_URL; ?>/auth/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="rounded-md shadow-sm space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input id="nombre" name="nombre" type="text" required
                               class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Juan">
                    </div>
                    
                    <div>
                        <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">
                            Apellido
                        </label>
                        <input id="apellido" name="apellido" type="text" required
                               class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Pérez">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <input id="email" name="email" type="email" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="usuario@ejemplo.com">
                </div>
                
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-phone"></i> Teléfono
                    </label>
                    <input id="telefono" name="telefono" type="tel"
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="442-123-4567">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock"></i> Contraseña
                    </label>
                    <input id="password" name="password" type="password" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                </div>
                
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock"></i> Confirmar Contraseña
                    </label>
                    <input id="password_confirm" name="password_confirm" type="password" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Cuenta
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
