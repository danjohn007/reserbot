<?php
// Prevent direct access to this file
if (!defined('VIEWS_PATH')) {
    http_response_code(403);
    die('Forbidden: Direct access not allowed.');
}
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-cog"></i> Configuraciones del Sistema
    </h1>
    <p class="text-gray-600 mt-2">Personalice el sistema según sus necesidades</p>
</div>

<div class="grid grid-cols-1 gap-6">
    <?php
    $categoryIcons = [
        'general' => 'fa-home',
        'email' => 'fa-envelope',
        'whatsapp' => 'fa-whatsapp',
        'api' => 'fa-plug',
        'colors' => 'fa-palette'
    ];
    
    $categoryNames = [
        'general' => 'General',
        'email' => 'Email / SMTP',
        'whatsapp' => 'WhatsApp',
        'api' => 'Integraciones API',
        'colors' => 'Colores del Sistema'
    ];
    
    foreach ($configuraciones as $categoria => $configs):
    ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-6 flex items-center">
                <i class="fas <?php echo $categoryIcons[$categoria] ?? 'fa-cog'; ?> text-blue-600 mr-2"></i>
                <?php echo $categoryNames[$categoria] ?? ucfirst($categoria); ?>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($configs as $config): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo htmlspecialchars($config['descripcion'] ?? $config['clave']); ?>
                        </label>
                        
                        <?php if ($config['tipo'] == 'boolean'): ?>
                            <select name="<?php echo $config['clave']; ?>" 
                                    class="config-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                                    data-clave="<?php echo $config['clave']; ?>">
                                <option value="true" <?php echo $config['valor'] == 'true' ? 'selected' : ''; ?>>Sí</option>
                                <option value="false" <?php echo $config['valor'] == 'false' ? 'selected' : ''; ?>>No</option>
                            </select>
                        <?php elseif ($config['tipo'] == 'number'): ?>
                            <input type="number" 
                                   name="<?php echo $config['clave']; ?>"
                                   value="<?php echo htmlspecialchars($config['valor']); ?>"
                                   class="config-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                                   data-clave="<?php echo $config['clave']; ?>">
                        <?php else: ?>
                            <input type="text" 
                                   name="<?php echo $config['clave']; ?>"
                                   value="<?php echo htmlspecialchars($config['valor']); ?>"
                                   class="config-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                                   data-clave="<?php echo $config['clave']; ?>"
                                   placeholder="<?php echo htmlspecialchars($config['clave']); ?>">
                        <?php endif; ?>
                        
                        <p class="text-xs text-gray-500 mt-1">
                            Clave: <?php echo $config['clave']; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-600">
            <i class="fas fa-info-circle text-blue-600"></i>
            Los cambios se guardan automáticamente al modificar cada campo
        </p>
        <button onclick="location.reload()" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-sync-alt"></i> Recargar Página
        </button>
    </div>
</div>

<script>
// Auto-save on change
document.querySelectorAll('.config-input').forEach(input => {
    input.addEventListener('change', function() {
        const clave = this.dataset.clave;
        const valor = this.value;
        
        // Show loading indicator
        this.style.borderColor = '#FFA500';
        
        const formData = new FormData();
        formData.append('csrf_token', '<?php echo $csrf_token; ?>');
        formData.append('clave', clave);
        formData.append('valor', valor);
        
        fetch(`${BASE_URL}/admin/guardarConfiguracion`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success - green border
                this.style.borderColor = '#10B981';
                setTimeout(() => {
                    this.style.borderColor = '';
                }, 1000);
            } else {
                // Error - red border
                this.style.borderColor = '#EF4444';
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.style.borderColor = '#EF4444';
            alert('Error al guardar la configuración');
        });
    });
});
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
