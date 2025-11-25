<?php
/**
 * AdminController - Administration panel
 */

class AdminController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        // Require admin or superadmin role
        $this->requireAnyRole([ROLE_SUPERADMIN, ROLE_ADMIN]);
    }
    
    /**
     * Admin panel home
     */
    public function index() {
        $this->redirect('dashboard');
    }
    
    /**
     * Manage sucursales
     */
    public function sucursales() {
        $sucursalModel = new Sucursal();
        
        $data = [
            'title' => 'Gestionar Sucursales - ' . APP_NAME,
            'sucursales' => $sucursalModel->getAll(false),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/sucursales', $data);
    }
    
    /**
     * Manage servicios
     */
    public function servicios() {
        $servicioModel = new Servicio();
        
        $data = [
            'title' => 'Gestionar Servicios - ' . APP_NAME,
            'servicios' => $servicioModel->getAll(false),
            'categorias' => $servicioModel->getCategorias(false),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/servicios', $data);
    }
    
    /**
     * Manage especialistas
     */
    public function especialistas() {
        $especialistaModel = new Especialista();
        $usuarioModel = new Usuario();
        $sucursalModel = new Sucursal();
        
        $data = [
            'title' => 'Gestionar Especialistas - ' . APP_NAME,
            'especialistas' => $especialistaModel->getAll(false),
            'usuarios_disponibles' => $usuarioModel->getAll(['rol_id' => ROLE_ESPECIALISTA]),
            'sucursales' => $sucursalModel->getAll(),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/especialistas', $data);
    }
    
    /**
     * Manage users
     */
    public function usuarios() {
        $this->requireRole(ROLE_SUPERADMIN);
        
        $usuarioModel = new Usuario();
        
        $data = [
            'title' => 'Gestionar Usuarios - ' . APP_NAME,
            'usuarios' => $usuarioModel->getAll(),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/usuarios', $data);
    }
    
    /**
     * System reports
     */
    public function reportes() {
        $reservacionModel = new Reservacion();
        
        // Get date range from query params or default to current month
        $fechaDesde = $_GET['fecha_desde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fecha_hasta'] ?? date('Y-m-t');
        
        $filters = [
            'fecha_desde' => $fechaDesde . ' 00:00:00',
            'fecha_hasta' => $fechaHasta . ' 23:59:59'
        ];
        
        // If admin (not superadmin), filter by their sucursales
        if ($_SESSION['rol_id'] == ROLE_ADMIN) {
            $sucursalModel = new Sucursal();
            $sucursales = $sucursalModel->getByAdmin($_SESSION['user_id']);
            if (!empty($sucursales)) {
                $filters['sucursal_id'] = $sucursales[0]['id'];
            }
        }
        
        $stats = $reservacionModel->getStatistics($filters);
        $reservaciones = $reservacionModel->getAll($filters);
        
        $data = [
            'title' => 'Reportes - ' . APP_NAME,
            'stats' => $stats,
            'reservaciones' => $reservaciones,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/reportes', $data);
    }
    
    /**
     * System configurations (Superadmin only)
     */
    public function configuraciones() {
        $this->requireRole(ROLE_SUPERADMIN);
        
        $sql = "SELECT * FROM configuraciones ORDER BY categoria, clave";
        $configuraciones = $this->getDb()->fetchAll($sql);
        
        // Group by category
        $grouped = [];
        foreach ($configuraciones as $config) {
            $categoria = $config['categoria'] ?? 'general';
            if (!isset($grouped[$categoria])) {
                $grouped[$categoria] = [];
            }
            $grouped[$categoria][] = $config;
        }
        
        $data = [
            'title' => 'Configuraciones del Sistema - ' . APP_NAME,
            'configuraciones' => $grouped,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/configuraciones', $data);
    }
    
    /**
     * Save configuration (AJAX)
     */
    public function guardarConfiguracion() {
        $this->requireRole(ROLE_SUPERADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            $this->json(['error' => 'Token de seguridad inválido'], 400);
        }
        
        $clave = $_POST['clave'] ?? null;
        $valor = $_POST['valor'] ?? '';
        
        if (!$clave) {
            $this->json(['error' => 'Clave requerida'], 400);
        }
        
        try {
            $sql = "UPDATE configuraciones SET valor = ? WHERE clave = ?";
            $this->getDb()->query($sql, [$valor, $clave]);
            
            $this->logSecurityEvent('config_actualizada', "Configuración actualizada: $clave", $_SESSION['user_id']);
            $this->json(['success' => true, 'message' => 'Configuración guardada']);
        } catch (Exception $e) {
            error_log("Error saving configuration: " . $e->getMessage());
            $this->json(['error' => 'Error al guardar la configuración'], 500);
        }
    }
    
    /**
     * Security logs
     */
    public function logs() {
        $this->requireRole(ROLE_SUPERADMIN);
        
        $page = $_GET['page'] ?? 1;
        $perPage = 50;
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT l.*, u.nombre, u.apellido, u.email 
                FROM logs_seguridad l
                LEFT JOIN usuarios u ON l.usuario_id = u.id
                ORDER BY l.fecha_hora DESC
                LIMIT ? OFFSET ?";
        
        $logs = $this->getDb()->fetchAll($sql, [$perPage, $offset]);
        
        // Get total count
        $totalResult = $this->getDb()->fetch("SELECT COUNT(*) as total FROM logs_seguridad");
        $total = $totalResult['total'];
        $totalPages = ceil($total / $perPage);
        
        $data = [
            'title' => 'Logs de Seguridad - ' . APP_NAME,
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];
        
        $this->view('admin/logs', $data);
    }
}
