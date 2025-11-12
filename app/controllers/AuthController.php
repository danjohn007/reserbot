<?php
/**
 * AuthController - Authentication (login, register, logout)
 */

class AuthController extends BaseController {
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $data = [
                'title' => 'Iniciar Sesión - ' . APP_NAME,
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->view('auth/login', $data);
        }
    }
    
    /**
     * Process login
     */
    private function processLogin() {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            $this->setFlash('error', 'Token de seguridad inválido');
            $this->redirect('auth/login');
        }
        
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        
        // Validate inputs
        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Por favor, complete todos los campos');
            $this->redirect('auth/login');
        }
        
        // Find user
        $user = $this->usuarioModel->findByEmail($email);
        
        if (!$user) {
            $this->logSecurityEvent('failed_login', "Intento de login con email inexistente: $email");
            $this->setFlash('error', 'Credenciales inválidas');
            $this->redirect('auth/login');
        }
        
        // Check if account is blocked
        if ($this->usuarioModel->isBlocked($user['id'])) {
            $this->logSecurityEvent('failed_login', "Intento de login en cuenta bloqueada", $user['id']);
            $this->setFlash('error', 'Su cuenta está temporalmente bloqueada. Intente más tarde.');
            $this->redirect('auth/login');
        }
        
        // Verify password
        if (!$this->usuarioModel->verifyPassword($password, $user['password'])) {
            // Increment login attempts
            $this->usuarioModel->incrementLoginAttempts($user['id']);
            
            // Check if should block account
            if ($user['intentos_login'] + 1 >= MAX_LOGIN_ATTEMPTS) {
                $this->usuarioModel->blockAccount($user['id'], LOGIN_TIMEOUT / 60);
                $this->logSecurityEvent('account_blocked', "Cuenta bloqueada por múltiples intentos fallidos", $user['id']);
                $this->setFlash('error', 'Cuenta bloqueada por múltiples intentos fallidos');
            } else {
                $this->logSecurityEvent('failed_login', "Contraseña incorrecta", $user['id']);
                $remaining = MAX_LOGIN_ATTEMPTS - ($user['intentos_login'] + 1);
                $this->setFlash('error', "Credenciales inválidas. Intentos restantes: $remaining");
            }
            
            $this->redirect('auth/login');
        }
        
        // Check if user is active
        if (!$user['activo']) {
            $this->logSecurityEvent('failed_login', "Intento de login en cuenta inactiva", $user['id']);
            $this->setFlash('error', 'Su cuenta está inactiva. Contacte al administrador.');
            $this->redirect('auth/login');
        }
        
        // Successful login
        $this->usuarioModel->resetLoginAttempts($user['id']);
        $this->usuarioModel->updateLastConnection($user['id']);
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['apellido'] = $user['apellido'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['rol_id'] = $user['rol_id'];
        
        $this->logSecurityEvent('login', "Login exitoso", $user['id']);
        
        // Redirect
        $redirectUrl = $_SESSION['redirect_after_login'] ?? 'dashboard';
        unset($_SESSION['redirect_after_login']);
        $this->redirect($redirectUrl);
    }
    
    /**
     * Register page
     */
    public function register() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
        } else {
            $data = [
                'title' => 'Registro - ' . APP_NAME,
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->view('auth/register', $data);
        }
    }
    
    /**
     * Process registration
     */
    private function processRegister() {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            $this->setFlash('error', 'Token de seguridad inválido');
            $this->redirect('auth/register');
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        // Validate inputs
        $errors = [];
        
        if (empty($nombre)) $errors[] = 'El nombre es requerido';
        if (empty($apellido)) $errors[] = 'El apellido es requerido';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        if (empty($password) || strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres';
        }
        if ($password !== $password_confirm) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('auth/register');
        }
        
        // Check if email already exists
        if ($this->usuarioModel->findByEmail($email)) {
            $this->setFlash('error', 'Este email ya está registrado');
            $this->redirect('auth/register');
        }
        
        // Create user
        try {
            $userId = $this->usuarioModel->create([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'password' => $password,
                'rol_id' => ROLE_CLIENTE
            ]);
            
            $this->logSecurityEvent('registro', "Nuevo usuario registrado: $email", $userId);
            $this->setFlash('success', 'Registro exitoso. Por favor, inicie sesión.');
            $this->redirect('auth/login');
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $this->setFlash('error', 'Error al registrar. Intente nuevamente.');
            $this->redirect('auth/register');
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        if ($this->isLoggedIn()) {
            $this->logSecurityEvent('logout', "Logout", $_SESSION['user_id']);
        }
        
        session_destroy();
        $this->redirect('');
    }
}
