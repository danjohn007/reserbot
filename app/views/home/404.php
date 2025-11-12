<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="text-center py-12">
    <div class="text-9xl text-gray-300 mb-4">
        <i class="fas fa-exclamation-triangle"></i>
    </div>
    <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
    <h2 class="text-2xl text-gray-600 mb-8">Página no encontrada</h2>
    <p class="text-gray-500 mb-8">
        Lo sentimos, la página que buscas no existe.
    </p>
    <a href="<?php echo BASE_URL; ?>" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 inline-block">
        <i class="fas fa-home"></i> Volver al Inicio
    </a>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
