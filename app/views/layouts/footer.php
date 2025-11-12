    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-calendar-check"></i> <?php echo APP_NAME; ?>
                    </h3>
                    <p class="text-gray-400">
                        Sistema profesional de gestión de reservaciones y citas.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?php echo BASE_URL; ?>" class="hover:text-white">Inicio</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/auth/login" class="hover:text-white">Iniciar Sesión</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/auth/register" class="hover:text-white">Registrarse</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope"></i> info@reserbot.com</li>
                        <li><i class="fas fa-phone"></i> 442-212-3456</li>
                        <li><i class="fas fa-map-marker-alt"></i> Querétaro, México</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos los derechos reservados.</p>
                <p class="text-sm mt-2">Versión <?php echo APP_VERSION; ?></p>
            </div>
        </div>
    </footer>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo BASE_URL; ?>/public/js/main.js"></script>
</body>
</html>
