<?php
/**
 * BaseController - Base controller for all controllers
 * Provides common functionality
 */

class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Load a view
     */
    protected function view($view, $data = []) {
        extract($data);
        
        // Check if view file exists
        $viewFile = VIEWS_PATH . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: $view");
        }
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url) {
        $url = BASE_URL . '/' . ltrim($url, '/');
        header("Location: $url");
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Check if user has specific role
     */
    protected function hasRole($roleId) {
        return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == $roleId;
    }
    
    /**
     * Check if user has one of the specified roles
     */
    protected function hasAnyRole($roleIds) {
        return isset($_SESSION['rol_id']) && in_array($_SESSION['rol_id'], $roleIds);
    }
    
    /**
     * Require specific role
     */
    protected function requireRole($roleId) {
        $this->requireAuth();
        if (!$this->hasRole($roleId)) {
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Require one of the specified roles
     */
    protected function requireAnyRole($roleIds) {
        $this->requireAuth();
        if (!$this->hasAnyRole($roleIds)) {
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Get current user data
     */
    protected function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'nombre' => $_SESSION['nombre'] ?? '',
            'apellido' => $_SESSION['apellido'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'rol_id' => $_SESSION['rol_id'] ?? null
        ];
    }
    
    /**
     * Generate CSRF token
     */
    protected function generateCsrfToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken($token) {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && 
               hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Log security event
     */
    protected function logSecurityEvent($tipo, $descripcion, $usuarioId = null) {
        try {
            $sql = "INSERT INTO logs_seguridad (usuario_id, tipo, descripcion, ip, user_agent) 
                    VALUES (?, ?, ?, ?, ?)";
            $this->db->query($sql, [
                $usuarioId,
                $tipo,
                $descripcion,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
        } catch (Exception $e) {
            error_log("Error logging security event: " . $e->getMessage());
        }
    }
    
    /**
     * Get flash message
     */
    protected function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
}
