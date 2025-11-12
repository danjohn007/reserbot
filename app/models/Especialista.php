<?php
/**
 * Especialista Model
 */

class Especialista {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all especialistas
     */
    public function getAll($activeOnly = true) {
        $sql = "SELECT e.*, u.nombre, u.apellido, u.email, s.nombre as sucursal_nombre
                FROM especialistas e
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN sucursales s ON e.sucursal_id = s.id";
        if ($activeOnly) {
            $sql .= " WHERE e.activo = 1 AND u.activo = 1";
        }
        $sql .= " ORDER BY u.nombre, u.apellido";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Find especialista by ID
     */
    public function findById($id) {
        $sql = "SELECT e.*, u.nombre, u.apellido, u.email, u.telefono, s.nombre as sucursal_nombre
                FROM especialistas e
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN sucursales s ON e.sucursal_id = s.id
                WHERE e.id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Find especialista by usuario_id
     */
    public function findByUsuarioId($usuarioId) {
        $sql = "SELECT e.*, u.nombre, u.apellido, u.email, s.nombre as sucursal_nombre
                FROM especialistas e
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN sucursales s ON e.sucursal_id = s.id
                WHERE e.usuario_id = ? LIMIT 1";
        return $this->db->fetch($sql, [$usuarioId]);
    }
    
    /**
     * Get especialistas by sucursal
     */
    public function getBySucursal($sucursalId, $activeOnly = true) {
        $sql = "SELECT e.*, u.nombre, u.apellido, u.email
                FROM especialistas e
                INNER JOIN usuarios u ON e.usuario_id = u.id
                WHERE e.sucursal_id = ?";
        if ($activeOnly) {
            $sql .= " AND e.activo = 1 AND u.activo = 1";
        }
        $sql .= " ORDER BY u.nombre, u.apellido";
        return $this->db->fetchAll($sql, [$sucursalId]);
    }
    
    /**
     * Get especialistas by servicio
     */
    public function getByServicio($servicioId, $sucursalId = null) {
        $sql = "SELECT DISTINCT e.*, u.nombre, u.apellido, u.email, s.nombre as sucursal_nombre
                FROM especialistas e
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN sucursales s ON e.sucursal_id = s.id
                INNER JOIN especialista_servicios es ON e.id = es.especialista_id
                WHERE es.servicio_id = ? AND e.activo = 1 AND u.activo = 1";
        
        $params = [$servicioId];
        
        if ($sucursalId) {
            $sql .= " AND e.sucursal_id = ?";
            $params[] = $sucursalId;
        }
        
        $sql .= " ORDER BY e.calificacion_promedio DESC, u.nombre, u.apellido";
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get horarios
     */
    public function getHorarios($especialistaId) {
        $sql = "SELECT * FROM horarios_especialista 
                WHERE especialista_id = ? AND activo = 1 
                ORDER BY dia_semana, hora_inicio";
        return $this->db->fetchAll($sql, [$especialistaId]);
    }
    
    /**
     * Check if time is available
     */
    public function isTimeAvailable($especialistaId, $fecha, $duracion) {
        // Check horarios
        $diaSemana = date('w', strtotime($fecha));
        $hora = date('H:i:s', strtotime($fecha));
        
        $sql = "SELECT COUNT(*) as count FROM horarios_especialista 
                WHERE especialista_id = ? AND dia_semana = ? 
                AND hora_inicio <= ? AND hora_fin >= ? AND activo = 1";
        
        $result = $this->db->fetch($sql, [$especialistaId, $diaSemana, $hora, $hora]);
        
        if ($result['count'] == 0) {
            return false;
        }
        
        // Check bloqueos
        $sql = "SELECT COUNT(*) as count FROM bloqueos_horario 
                WHERE especialista_id = ? 
                AND fecha_inicio <= ? AND fecha_fin >= ?";
        
        $result = $this->db->fetch($sql, [$especialistaId, $fecha, $fecha]);
        
        if ($result['count'] > 0) {
            return false;
        }
        
        // Check existing reservaciones
        $fechaFin = date('Y-m-d H:i:s', strtotime($fecha) + ($duracion * 60));
        
        $sql = "SELECT COUNT(*) as count FROM reservaciones 
                WHERE especialista_id = ? 
                AND estado != 'cancelada'
                AND (
                    (fecha_hora <= ? AND DATE_ADD(fecha_hora, INTERVAL duracion MINUTE) > ?)
                    OR (fecha_hora < ? AND DATE_ADD(fecha_hora, INTERVAL duracion MINUTE) >= ?)
                )";
        
        $result = $this->db->fetch($sql, [$especialistaId, $fecha, $fecha, $fechaFin, $fechaFin]);
        
        return $result['count'] == 0;
    }
    
    /**
     * Create especialista
     */
    public function create($data) {
        $sql = "INSERT INTO especialistas (usuario_id, sucursal_id, especialidad, biografia, 
                titulo, cedula_profesional) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $data['usuario_id'],
            $data['sucursal_id'],
            $data['especialidad'] ?? null,
            $data['biografia'] ?? null,
            $data['titulo'] ?? null,
            $data['cedula_profesional'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update calificacion promedio
     */
    public function updateCalificacion($especialistaId) {
        $sql = "UPDATE especialistas e
                SET calificacion_promedio = (
                    SELECT AVG(calificacion) FROM calificaciones WHERE especialista_id = e.id
                ),
                total_calificaciones = (
                    SELECT COUNT(*) FROM calificaciones WHERE especialista_id = e.id
                )
                WHERE e.id = ?";
        
        return $this->db->query($sql, [$especialistaId]);
    }
}
