<?php
/**
 * Servicio Model
 */

class Servicio {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all servicios
     */
    public function getAll($activeOnly = true) {
        $sql = "SELECT s.*, c.nombre as categoria_nombre 
                FROM servicios s
                LEFT JOIN categorias_servicio c ON s.categoria_id = c.id";
        if ($activeOnly) {
            $sql .= " WHERE s.activo = 1";
        }
        $sql .= " ORDER BY c.orden, s.nombre";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Find servicio by ID
     */
    public function findById($id) {
        $sql = "SELECT s.*, c.nombre as categoria_nombre 
                FROM servicios s
                LEFT JOIN categorias_servicio c ON s.categoria_id = c.id
                WHERE s.id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Get servicios by categoria
     */
    public function getByCategoria($categoriaId) {
        $sql = "SELECT * FROM servicios WHERE categoria_id = ? AND activo = 1 ORDER BY nombre";
        return $this->db->fetchAll($sql, [$categoriaId]);
    }
    
    /**
     * Get servicios by especialista
     */
    public function getByEspecialista($especialistaId) {
        $sql = "SELECT s.*, c.nombre as categoria_nombre 
                FROM servicios s
                LEFT JOIN categorias_servicio c ON s.categoria_id = c.id
                INNER JOIN especialista_servicios es ON s.id = es.servicio_id
                WHERE es.especialista_id = ? AND s.activo = 1
                ORDER BY s.nombre";
        return $this->db->fetchAll($sql, [$especialistaId]);
    }
    
    /**
     * Get all categorias
     */
    public function getCategorias($activeOnly = true) {
        $sql = "SELECT * FROM categorias_servicio";
        if ($activeOnly) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY orden, nombre";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Create new servicio
     */
    public function create($data) {
        $sql = "INSERT INTO servicios (categoria_id, nombre, descripcion, duracion, precio) 
                VALUES (?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $data['categoria_id'],
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['duracion'],
            $data['precio']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update servicio
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['categoria_id', 'nombre', 'descripcion', 'duracion', 'precio', 'activo'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        $params[] = $id;
        
        $sql = "UPDATE servicios SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->db->query($sql, $params);
    }
    
    /**
     * Delete servicio
     */
    public function delete($id) {
        $sql = "DELETE FROM servicios WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
