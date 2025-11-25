<?php
/**
 * Sucursal Model
 */

class Sucursal {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all sucursales
     */
    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM sucursales";
        if ($activeOnly) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY nombre";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Find sucursal by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM sucursales WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Create new sucursal
     */
    public function create($data) {
        $sql = "INSERT INTO sucursales (nombre, direccion, ciudad, estado, codigo_postal, 
                telefono, email, horario_apertura, horario_cierre, latitud, longitud) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $data['nombre'],
            $data['direccion'] ?? null,
            $data['ciudad'] ?? 'Querétaro',
            $data['estado'] ?? 'Querétaro',
            $data['codigo_postal'] ?? null,
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['horario_apertura'] ?? '08:00:00',
            $data['horario_cierre'] ?? '20:00:00',
            $data['latitud'] ?? null,
            $data['longitud'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update sucursal
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['nombre', 'direccion', 'ciudad', 'estado', 'codigo_postal', 
                          'telefono', 'email', 'horario_apertura', 'horario_cierre', 
                          'latitud', 'longitud', 'activo'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        $params[] = $id;
        
        $sql = "UPDATE sucursales SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->db->query($sql, $params);
    }
    
    /**
     * Delete sucursal
     */
    public function delete($id) {
        $sql = "DELETE FROM sucursales WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    /**
     * Get sucursales by admin
     */
    public function getByAdmin($adminId) {
        $sql = "SELECT s.* FROM sucursales s
                INNER JOIN admin_sucursales as ON as.sucursal_id = s.id
                WHERE as.usuario_id = ? AND s.activo = 1
                ORDER BY s.nombre";
        return $this->db->fetchAll($sql, [$adminId]);
    }
}
