<?php
/**
 * Reservacion Model
 */

class Reservacion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all reservaciones
     */
    public function getAll($filters = []) {
        $sql = "SELECT r.*, 
                u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email,
                e.especialidad, ue.nombre as especialista_nombre, ue.apellido as especialista_apellido,
                s.nombre as servicio_nombre, s.duracion as servicio_duracion,
                su.nombre as sucursal_nombre
                FROM reservaciones r
                INNER JOIN usuarios u ON r.cliente_id = u.id
                INNER JOIN especialistas e ON r.especialista_id = e.id
                INNER JOIN usuarios ue ON e.usuario_id = ue.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                INNER JOIN sucursales su ON r.sucursal_id = su.id
                WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['cliente_id'])) {
            $sql .= " AND r.cliente_id = ?";
            $params[] = $filters['cliente_id'];
        }
        
        if (isset($filters['especialista_id'])) {
            $sql .= " AND r.especialista_id = ?";
            $params[] = $filters['especialista_id'];
        }
        
        if (isset($filters['sucursal_id'])) {
            $sql .= " AND r.sucursal_id = ?";
            $params[] = $filters['sucursal_id'];
        }
        
        if (isset($filters['estado'])) {
            $sql .= " AND r.estado = ?";
            $params[] = $filters['estado'];
        }
        
        if (isset($filters['fecha_desde'])) {
            $sql .= " AND r.fecha_hora >= ?";
            $params[] = $filters['fecha_desde'];
        }
        
        if (isset($filters['fecha_hasta'])) {
            $sql .= " AND r.fecha_hora <= ?";
            $params[] = $filters['fecha_hasta'];
        }
        
        $sql .= " ORDER BY r.fecha_hora DESC";
        
        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . intval($filters['limit']);
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Find reservacion by ID
     */
    public function findById($id) {
        $sql = "SELECT r.*, 
                u.nombre as cliente_nombre, u.apellido as cliente_apellido, 
                u.email as cliente_email, u.telefono as cliente_telefono,
                e.especialidad, ue.nombre as especialista_nombre, ue.apellido as especialista_apellido,
                s.nombre as servicio_nombre, s.duracion as servicio_duracion, s.precio as servicio_precio,
                su.nombre as sucursal_nombre, su.direccion as sucursal_direccion
                FROM reservaciones r
                INNER JOIN usuarios u ON r.cliente_id = u.id
                INNER JOIN especialistas e ON r.especialista_id = e.id
                INNER JOIN usuarios ue ON e.usuario_id = ue.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                INNER JOIN sucursales su ON r.sucursal_id = su.id
                WHERE r.id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Create new reservacion
     */
    public function create($data) {
        $sql = "INSERT INTO reservaciones (cliente_id, especialista_id, servicio_id, sucursal_id, 
                fecha_hora, duracion, estado, notas, precio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $data['cliente_id'],
            $data['especialista_id'],
            $data['servicio_id'],
            $data['sucursal_id'],
            $data['fecha_hora'],
            $data['duracion'],
            $data['estado'] ?? STATUS_PENDIENTE,
            $data['notas'] ?? null,
            $data['precio']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update reservacion
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['estado', 'notas', 'motivo_cancelacion', 'fecha_hora', 'especialista_id'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        $params[] = $id;
        
        $sql = "UPDATE reservaciones SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->db->query($sql, $params);
    }
    
    /**
     * Cancel reservacion
     */
    public function cancel($id, $motivo = null) {
        $sql = "UPDATE reservaciones SET estado = 'cancelada', motivo_cancelacion = ? WHERE id = ?";
        return $this->db->query($sql, [$motivo, $id]);
    }
    
    /**
     * Get upcoming reservaciones
     */
    public function getUpcoming($clienteId, $limit = 10) {
        $sql = "SELECT r.*, 
                e.especialidad, ue.nombre as especialista_nombre, ue.apellido as especialista_apellido,
                s.nombre as servicio_nombre,
                su.nombre as sucursal_nombre
                FROM reservaciones r
                INNER JOIN especialistas e ON r.especialista_id = e.id
                INNER JOIN usuarios ue ON e.usuario_id = ue.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                INNER JOIN sucursales su ON r.sucursal_id = su.id
                WHERE r.cliente_id = ? 
                AND r.fecha_hora >= NOW()
                AND r.estado != 'cancelada'
                ORDER BY r.fecha_hora ASC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$clienteId, $limit]);
    }
    
    /**
     * Get statistics
     */
    public function getStatistics($filters = []) {
        $where = "WHERE 1=1";
        $params = [];
        
        if (isset($filters['sucursal_id'])) {
            $where .= " AND sucursal_id = ?";
            $params[] = $filters['sucursal_id'];
        }
        
        if (isset($filters['especialista_id'])) {
            $where .= " AND especialista_id = ?";
            $params[] = $filters['especialista_id'];
        }
        
        if (isset($filters['fecha_desde'])) {
            $where .= " AND fecha_hora >= ?";
            $params[] = $filters['fecha_desde'];
        }
        
        if (isset($filters['fecha_hasta'])) {
            $where .= " AND fecha_hora <= ?";
            $params[] = $filters['fecha_hasta'];
        }
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'confirmada' THEN 1 ELSE 0 END) as confirmadas,
                SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as completadas,
                SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) as canceladas,
                SUM(CASE WHEN estado = 'completada' THEN precio ELSE 0 END) as ingresos_total
                FROM reservaciones $where";
        
        return $this->db->fetch($sql, $params);
    }
}
