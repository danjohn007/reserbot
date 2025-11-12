<?php
/**
 * DashboardController - Role-based dashboards
 */

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $rolId = $_SESSION['rol_id'];
        
        // Redirect to role-specific dashboard
        switch ($rolId) {
            case ROLE_SUPERADMIN:
            case ROLE_ADMIN:
                $this->admin();
                break;
            case ROLE_ESPECIALISTA:
                $this->especialista();
                break;
            case ROLE_RECEPCIONISTA:
                $this->recepcionista();
                break;
            case ROLE_CLIENTE:
            default:
                $this->cliente();
                break;
        }
    }
    
    /**
     * Admin dashboard
     */
    private function admin() {
        $reservacionModel = new Reservacion();
        $especialistaModel = new Especialista();
        $sucursalModel = new Sucursal();
        $usuarioModel = new Usuario();
        
        // Get statistics
        $filters = [];
        
        // If admin (not superadmin), filter by their sucursales
        if ($_SESSION['rol_id'] == ROLE_ADMIN) {
            // Get admin's sucursales
            $sucursales = $sucursalModel->getByAdmin($_SESSION['user_id']);
            if (!empty($sucursales)) {
                $filters['sucursal_id'] = $sucursales[0]['id']; // For simplicity, use first sucursal
            }
        }
        
        $stats = $reservacionModel->getStatistics($filters);
        
        // Get recent reservaciones
        $recentReservaciones = $reservacionModel->getAll(array_merge($filters, ['limit' => 10]));
        
        $data = [
            'title' => 'Panel de Administración - ' . APP_NAME,
            'stats' => $stats,
            'reservaciones' => $recentReservaciones,
            'especialistas' => $especialistaModel->getAll(),
            'sucursales' => $sucursalModel->getAll(false)
        ];
        
        $this->view('dashboard/admin', $data);
    }
    
    /**
     * Especialista dashboard
     */
    private function especialista() {
        $reservacionModel = new Reservacion();
        $especialistaModel = new Especialista();
        
        // Get especialista info
        $especialista = $especialistaModel->findByUsuarioId($_SESSION['user_id']);
        
        if (!$especialista) {
            $this->setFlash('error', 'Perfil de especialista no encontrado');
            $this->redirect('');
        }
        
        // Get today's reservaciones
        $today = date('Y-m-d');
        $hoyReservaciones = $reservacionModel->getAll([
            'especialista_id' => $especialista['id'],
            'fecha_desde' => $today . ' 00:00:00',
            'fecha_hasta' => $today . ' 23:59:59'
        ]);
        
        // Get upcoming reservaciones
        $proximasReservaciones = $reservacionModel->getAll([
            'especialista_id' => $especialista['id'],
            'fecha_desde' => date('Y-m-d H:i:s'),
            'limit' => 10
        ]);
        
        // Get statistics
        $stats = $reservacionModel->getStatistics([
            'especialista_id' => $especialista['id']
        ]);
        
        $data = [
            'title' => 'Mi Dashboard - ' . APP_NAME,
            'especialista' => $especialista,
            'hoy_reservaciones' => $hoyReservaciones,
            'proximas_reservaciones' => $proximasReservaciones,
            'stats' => $stats
        ];
        
        $this->view('dashboard/especialista', $data);
    }
    
    /**
     * Cliente dashboard
     */
    private function cliente() {
        $reservacionModel = new Reservacion();
        
        // Get user's reservaciones
        $misReservaciones = $reservacionModel->getAll([
            'cliente_id' => $_SESSION['user_id'],
            'limit' => 20
        ]);
        
        // Get upcoming reservaciones
        $proximasReservaciones = $reservacionModel->getUpcoming($_SESSION['user_id']);
        
        $data = [
            'title' => 'Mi Dashboard - ' . APP_NAME,
            'reservaciones' => $misReservaciones,
            'proximas' => $proximasReservaciones
        ];
        
        $this->view('dashboard/cliente', $data);
    }
    
    /**
     * Recepcionista dashboard
     */
    private function recepcionista() {
        $reservacionModel = new Reservacion();
        
        // Get today's reservaciones
        $today = date('Y-m-d');
        $hoyReservaciones = $reservacionModel->getAll([
            'fecha_desde' => $today . ' 00:00:00',
            'fecha_hasta' => $today . ' 23:59:59'
        ]);
        
        // Get pending reservaciones
        $pendientes = $reservacionModel->getAll([
            'estado' => STATUS_PENDIENTE,
            'limit' => 20
        ]);
        
        $data = [
            'title' => 'Recepción - ' . APP_NAME,
            'hoy_reservaciones' => $hoyReservaciones,
            'pendientes' => $pendientes
        ];
        
        $this->view('dashboard/recepcionista', $data);
    }
}
