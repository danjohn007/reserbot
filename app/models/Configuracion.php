<?php
/**
 * Configuracion Model
 */

class Configuracion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get configuration value
     */
    public function get($clave, $default = null) {
        $sql = "SELECT valor FROM configuraciones WHERE clave = ? LIMIT 1";
        $result = $this->db->fetch($sql, [$clave]);
        
        return $result ? $result['valor'] : $default;
    }
    
    /**
     * Set configuration value
     */
    public function set($clave, $valor) {
        $sql = "UPDATE configuraciones SET valor = ? WHERE clave = ?";
        return $this->db->query($sql, [$valor, $clave]);
    }
    
    /**
     * Get all configurations
     */
    public function getAll() {
        $sql = "SELECT * FROM configuraciones ORDER BY categoria, clave";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get configurations by category
     */
    public function getByCategoria($categoria) {
        $sql = "SELECT * FROM configuraciones WHERE categoria = ? ORDER BY clave";
        return $this->db->fetchAll($sql, [$categoria]);
    }
    
    /**
     * Create new configuration
     */
    public function create($data) {
        $sql = "INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion) 
                VALUES (?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $data['clave'],
            $data['valor'] ?? '',
            $data['tipo'] ?? 'string',
            $data['categoria'] ?? 'general',
            $data['descripcion'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Delete configuration
     */
    public function delete($clave) {
        $sql = "DELETE FROM configuraciones WHERE clave = ?";
        return $this->db->query($sql, [$clave]);
    }
}
