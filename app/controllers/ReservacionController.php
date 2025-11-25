<?php
/**
 * ReservacionController - Manage reservations
 */

class ReservacionController extends BaseController {
    private $reservacionModel;
    private $servicioModel;
    private $sucursalModel;
    private $especialistaModel;
    
    public function __construct() {
        parent::__construct();
        $this->reservacionModel = new Reservacion();
        $this->servicioModel = new Servicio();
        $this->sucursalModel = new Sucursal();
        $this->especialistaModel = new Especialista();
    }
    
    /**
     * New reservation form
     */
    public function nueva() {
        $this->requireAuth();
        $this->requireRole(ROLE_CLIENTE);
        
        $data = [
            'title' => 'Nueva Reservación - ' . APP_NAME,
            'sucursales' => $this->sucursalModel->getAll(),
            'categorias' => $this->servicioModel->getCategorias(),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('reservations/nueva', $data);
    }
    
    /**
     * Get servicios by sucursal (AJAX)
     */
    public function getServicios() {
        $this->requireAuth();
        
        $sucursalId = $_GET['sucursal_id'] ?? null;
        
        if (!$sucursalId) {
            $this->json(['error' => 'Sucursal requerida'], 400);
        }
        
        $servicios = $this->servicioModel->getAll();
        $this->json(['servicios' => $servicios]);
    }
    
    /**
     * Get especialistas by servicio (AJAX)
     */
    public function getEspecialistas() {
        $this->requireAuth();
        
        $servicioId = $_GET['servicio_id'] ?? null;
        $sucursalId = $_GET['sucursal_id'] ?? null;
        
        if (!$servicioId) {
            $this->json(['error' => 'Servicio requerido'], 400);
        }
        
        $especialistas = $this->especialistaModel->getByServicio($servicioId, $sucursalId);
        $this->json(['especialistas' => $especialistas]);
    }
    
    /**
     * Get available times (AJAX)
     */
    public function getHorariosDisponibles() {
        $this->requireAuth();
        
        $especialistaId = $_GET['especialista_id'] ?? null;
        $fecha = $_GET['fecha'] ?? null;
        $duracion = $_GET['duracion'] ?? 30;
        
        if (!$especialistaId || !$fecha) {
            $this->json(['error' => 'Parámetros incompletos'], 400);
        }
        
        // Get horarios del especialista para ese día
        $diaSemana = date('w', strtotime($fecha));
        $horarios = $this->especialistaModel->getHorarios($especialistaId);
        
        $horariosDisponibles = [];
        
        foreach ($horarios as $horario) {
            if ($horario['dia_semana'] == $diaSemana) {
                // Generate time slots
                $inicio = strtotime($fecha . ' ' . $horario['hora_inicio']);
                $fin = strtotime($fecha . ' ' . $horario['hora_fin']);
                
                for ($time = $inicio; $time < $fin; $time += ($duracion * 60)) {
                    $timeStr = date('Y-m-d H:i:s', $time);
                    
                    // Check if time is available
                    if ($this->especialistaModel->isTimeAvailable($especialistaId, $timeStr, $duracion)) {
                        $horariosDisponibles[] = [
                            'hora' => date('H:i', $time),
                            'datetime' => $timeStr
                        ];
                    }
                }
            }
        }
        
        $this->json(['horarios' => $horariosDisponibles]);
    }
    
    /**
     * Create reservation
     */
    public function crear() {
        $this->requireAuth();
        $this->requireRole(ROLE_CLIENTE);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reservacion/nueva');
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            $this->setFlash('error', 'Token de seguridad inválido');
            $this->redirect('reservacion/nueva');
        }
        
        $sucursalId = $_POST['sucursal_id'] ?? null;
        $servicioId = $_POST['servicio_id'] ?? null;
        $especialistaId = $_POST['especialista_id'] ?? null;
        $fechaHora = $_POST['fecha_hora'] ?? null;
        $notas = trim($_POST['notas'] ?? '');
        
        // Validate
        $errors = [];
        
        if (!$sucursalId) $errors[] = 'Seleccione una sucursal';
        if (!$servicioId) $errors[] = 'Seleccione un servicio';
        if (!$especialistaId) $errors[] = 'Seleccione un especialista';
        if (!$fechaHora) $errors[] = 'Seleccione fecha y hora';
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('reservacion/nueva');
        }
        
        // Get servicio info
        $servicio = $this->servicioModel->findById($servicioId);
        
        if (!$servicio) {
            $this->setFlash('error', 'Servicio no encontrado');
            $this->redirect('reservacion/nueva');
        }
        
        // Check if time is still available
        if (!$this->especialistaModel->isTimeAvailable($especialistaId, $fechaHora, $servicio['duracion'])) {
            $this->setFlash('error', 'El horario seleccionado ya no está disponible');
            $this->redirect('reservacion/nueva');
        }
        
        // Create reservation
        try {
            $reservacionId = $this->reservacionModel->create([
                'cliente_id' => $_SESSION['user_id'],
                'especialista_id' => $especialistaId,
                'servicio_id' => $servicioId,
                'sucursal_id' => $sucursalId,
                'fecha_hora' => $fechaHora,
                'duracion' => $servicio['duracion'],
                'precio' => $servicio['precio'],
                'notas' => $notas
            ]);
            
            $this->logSecurityEvent('reservacion_creada', "Nueva reservación ID: $reservacionId", $_SESSION['user_id']);
            $this->setFlash('success', '¡Reservación creada exitosamente!');
            $this->redirect('reservacion/ver/' . $reservacionId);
            
        } catch (Exception $e) {
            error_log("Error creating reservation: " . $e->getMessage());
            $this->setFlash('error', 'Error al crear la reservación. Intente nuevamente.');
            $this->redirect('reservacion/nueva');
        }
    }
    
    /**
     * View reservation
     */
    public function ver($id = null) {
        $this->requireAuth();
        
        if (!$id) {
            $this->redirect('dashboard');
        }
        
        $reservacion = $this->reservacionModel->findById($id);
        
        if (!$reservacion) {
            $this->setFlash('error', 'Reservación no encontrada');
            $this->redirect('dashboard');
        }
        
        // Check permissions
        $canView = false;
        
        if ($_SESSION['rol_id'] == ROLE_CLIENTE && $reservacion['cliente_id'] == $_SESSION['user_id']) {
            $canView = true;
        } elseif ($_SESSION['rol_id'] == ROLE_ESPECIALISTA) {
            $especialista = $this->especialistaModel->findByUsuarioId($_SESSION['user_id']);
            if ($especialista && $reservacion['especialista_id'] == $especialista['id']) {
                $canView = true;
            }
        } elseif (in_array($_SESSION['rol_id'], [ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_RECEPCIONISTA])) {
            $canView = true;
        }
        
        if (!$canView) {
            $this->setFlash('error', 'No tiene permiso para ver esta reservación');
            $this->redirect('dashboard');
        }
        
        $data = [
            'title' => 'Reservación #' . $id . ' - ' . APP_NAME,
            'reservacion' => $reservacion,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('reservations/ver', $data);
    }
    
    /**
     * Cancel reservation
     */
    public function cancelar($id = null) {
        $this->requireAuth();
        
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            $this->json(['error' => 'Token de seguridad inválido'], 400);
        }
        
        $reservacion = $this->reservacionModel->findById($id);
        
        if (!$reservacion) {
            $this->json(['error' => 'Reservación no encontrada'], 404);
        }
        
        // Check permissions
        $canCancel = false;
        
        if ($_SESSION['rol_id'] == ROLE_CLIENTE && $reservacion['cliente_id'] == $_SESSION['user_id']) {
            $canCancel = true;
        } elseif (in_array($_SESSION['rol_id'], [ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_RECEPCIONISTA])) {
            $canCancel = true;
        }
        
        if (!$canCancel) {
            $this->json(['error' => 'No tiene permiso para cancelar esta reservación'], 403);
        }
        
        $motivo = $_POST['motivo'] ?? 'Cancelado por el cliente';
        
        try {
            $this->reservacionModel->cancel($id, $motivo);
            $this->logSecurityEvent('reservacion_cancelada', "Reservación cancelada ID: $id", $_SESSION['user_id']);
            $this->json(['success' => true, 'message' => 'Reservación cancelada']);
        } catch (Exception $e) {
            error_log("Error canceling reservation: " . $e->getMessage());
            $this->json(['error' => 'Error al cancelar la reservación'], 500);
        }
    }
    
    /**
     * Update reservation status
     */
    public function actualizarEstado($id = null) {
        $this->requireAnyRole([ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_ESPECIALISTA, ROLE_RECEPCIONISTA]);
        
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Solicitud inválida'], 400);
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            $this->json(['error' => 'Token de seguridad inválido'], 400);
        }
        
        $estado = $_POST['estado'] ?? null;
        $validEstados = [STATUS_PENDIENTE, STATUS_CONFIRMADA, STATUS_COMPLETADA, STATUS_CANCELADA];
        
        if (!in_array($estado, $validEstados)) {
            $this->json(['error' => 'Estado inválido'], 400);
        }
        
        try {
            $this->reservacionModel->update($id, ['estado' => $estado]);
            $this->logSecurityEvent('reservacion_actualizada', "Estado actualizado a $estado para reservación ID: $id", $_SESSION['user_id']);
            $this->json(['success' => true, 'message' => 'Estado actualizado']);
        } catch (Exception $e) {
            error_log("Error updating reservation: " . $e->getMessage());
            $this->json(['error' => 'Error al actualizar'], 500);
        }
    }
}
