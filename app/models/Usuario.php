<?php
/**
 * Usuario Model
 */

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Create new user
     */
    public function create($data) {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, telefono, rol_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['telefono'] ?? null,
            $data['rol_id'] ?? ROLE_CLIENTE
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Update last connection
     */
    public function updateLastConnection($userId) {
        $sql = "UPDATE usuarios SET ultima_conexion = NOW() WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    /**
     * Increment login attempts
     */
    public function incrementLoginAttempts($userId) {
        $sql = "UPDATE usuarios SET intentos_login = intentos_login + 1 WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    /**
     * Reset login attempts
     */
    public function resetLoginAttempts($userId) {
        $sql = "UPDATE usuarios SET intentos_login = 0, bloqueado_hasta = NULL WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    /**
     * Block user account
     */
    public function blockAccount($userId, $minutes = 15) {
        $sql = "UPDATE usuarios SET bloqueado_hasta = DATE_ADD(NOW(), INTERVAL ? MINUTE) WHERE id = ?";
        $this->db->query($sql, [$minutes, $userId]);
    }
    
    /**
     * Check if account is blocked
     */
    public function isBlocked($userId) {
        $sql = "SELECT bloqueado_hasta FROM usuarios WHERE id = ?";
        $user = $this->db->fetch($sql, [$userId]);
        
        if ($user && $user['bloqueado_hasta']) {
            return strtotime($user['bloqueado_hasta']) > time();
        }
        
        return false;
    }
    
    /**
     * Get all users
     */
    public function getAll($filters = []) {
        $sql = "SELECT * FROM usuarios WHERE 1=1";
        $params = [];
        
        if (isset($filters['rol_id'])) {
            $sql .= " AND rol_id = ?";
            $params[] = $filters['rol_id'];
        }
        
        if (isset($filters['activo'])) {
            $sql .= " AND activo = ?";
            $params[] = $filters['activo'];
        }
        
        $sql .= " ORDER BY fecha_registro DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['nombre'])) {
            $fields[] = "nombre = ?";
            $params[] = $data['nombre'];
        }
        
        if (isset($data['apellido'])) {
            $fields[] = "apellido = ?";
            $params[] = $data['apellido'];
        }
        
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }
        
        if (isset($data['telefono'])) {
            $fields[] = "telefono = ?";
            $params[] = $data['telefono'];
        }
        
        if (isset($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['activo'])) {
            $fields[] = "activo = ?";
            $params[] = $data['activo'];
        }
        
        $params[] = $id;
        
        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->db->query($sql, $params);
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
