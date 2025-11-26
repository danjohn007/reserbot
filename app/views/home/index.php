<?php
// Prevent direct access to this file
if (!defined('VIEWS_PATH')) {
    http_response_code(403);
    die('Forbidden: Direct access not allowed.');
}
require_once VIEWS_PATH . 'layouts/header.php';
?>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-xl p-12 mb-12">
    <div class="text-center">
        <h1 class="text-5xl font-bold mb-4">
            <i class="fas fa-calendar-check"></i> Bienvenido a <?php echo APP_NAME; ?>
        </h1>
        <p class="text-xl mb-8">
            Sistema profesional de gestión de reservaciones y citas
        </p>
        <div class="space-x-4">
            <a href="<?php echo BASE_URL; ?>/auth/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                <i class="fas fa-user-plus"></i> Registrarse Ahora
            </a>
            <a href="<?php echo BASE_URL; ?>/auth/login" class="bg-blue-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-400 inline-block">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
        </div>
    </div>
</div>

<!-- Features -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-blue-600 text-5xl mb-4">
            <i class="fas fa-clock"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">Disponibilidad en Tiempo Real</h3>
        <p class="text-gray-600">
            Consulta horarios disponibles al instante y agenda tu cita en segundos
        </p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-green-600 text-5xl mb-4">
            <i class="fas fa-user-md"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">Especialistas Certificados</h3>
        <p class="text-gray-600">
            Profesionales altamente calificados en diversas especialidades
        </p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-purple-600 text-5xl mb-4">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2">Fácil y Rápido</h3>
        <p class="text-gray-600">
            Gestiona tus citas desde cualquier dispositivo, en cualquier momento
        </p>
    </div>
</div>

<!-- Sucursales -->
<?php if (!empty($sucursales)): ?>
<div class="bg-white rounded-lg shadow-md p-8 mb-12">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
        <i class="fas fa-map-marker-alt"></i> Nuestras Sucursales
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($sucursales as $sucursal): ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">
                    <?php echo htmlspecialchars($sucursal['nombre']); ?>
                </h3>
                <p class="text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo htmlspecialchars($sucursal['direccion'] ?? ''); ?>
                </p>
                <p class="text-gray-600 mb-2">
                    <i class="fas fa-phone"></i>
                    <?php echo htmlspecialchars($sucursal['telefono'] ?? 'N/A'); ?>
                </p>
                <p class="text-gray-600">
                    <i class="fas fa-clock"></i>
                    <?php echo date('H:i', strtotime($sucursal['horario_apertura'])); ?> - 
                    <?php echo date('H:i', strtotime($sucursal['horario_cierre'])); ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Categorías de Servicios -->
<?php if (!empty($categorias)): ?>
<div class="bg-white rounded-lg shadow-md p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
        <i class="fas fa-list"></i> Nuestros Servicios
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($categorias as $categoria): ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition text-center">
                <div class="text-4xl text-blue-600 mb-3">
                    <i class="fas <?php echo htmlspecialchars($categoria['icono'] ?? 'fa-briefcase'); ?>"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </h3>
                <p class="text-gray-600 text-sm">
                    <?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg shadow-xl p-12 mt-12 text-center">
    <h2 class="text-3xl font-bold mb-4">¿Listo para comenzar?</h2>
    <p class="text-xl mb-6">
        Únete a cientos de clientes satisfechos y agenda tu próxima cita hoy
    </p>
    <a href="<?php echo BASE_URL; ?>/auth/register" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
        <i class="fas fa-rocket"></i> Crear Cuenta Gratis
    </a>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
