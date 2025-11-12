-- ReserBot - Sistema de Reservaciones y Citas Profesionales
-- Database Schema with Sample Data for Querétaro, Mexico

CREATE DATABASE IF NOT EXISTS reserbot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reserbot;

-- Table: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    rol_id INT NOT NULL DEFAULT 4 COMMENT '1=Superadmin, 2=Admin, 3=Especialista, 4=Cliente, 5=Recepcionista',
    activo BOOLEAN DEFAULT TRUE,
    intentos_login INT DEFAULT 0,
    bloqueado_hasta DATETIME NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_conexion DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_rol (rol_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: sucursales
CREATE TABLE sucursales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    direccion TEXT,
    ciudad VARCHAR(100) DEFAULT 'Querétaro',
    estado VARCHAR(100) DEFAULT 'Querétaro',
    codigo_postal VARCHAR(10),
    telefono VARCHAR(20),
    email VARCHAR(150),
    horario_apertura TIME DEFAULT '08:00:00',
    horario_cierre TIME DEFAULT '20:00:00',
    activo BOOLEAN DEFAULT TRUE,
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: categorias_servicio
CREATE TABLE categorias_servicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50),
    activo BOOLEAN DEFAULT TRUE,
    orden INT DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: servicios
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    duracion INT NOT NULL COMMENT 'Duración en minutos',
    precio DECIMAL(10, 2) NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_servicio(id) ON DELETE CASCADE,
    INDEX idx_categoria (categoria_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: especialistas
CREATE TABLE especialistas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    especialidad VARCHAR(150),
    biografia TEXT,
    titulo VARCHAR(200),
    cedula_profesional VARCHAR(50),
    foto VARCHAR(255),
    calificacion_promedio DECIMAL(3, 2) DEFAULT 0.00,
    total_calificaciones INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_sucursal (sucursal_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: especialista_servicios
CREATE TABLE especialista_servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    especialista_id INT NOT NULL,
    servicio_id INT NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_especialista_servicio (especialista_id, servicio_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: horarios_especialista
CREATE TABLE horarios_especialista (
    id INT AUTO_INCREMENT PRIMARY KEY,
    especialista_id INT NOT NULL,
    dia_semana INT NOT NULL COMMENT '0=Domingo, 1=Lunes, ..., 6=Sábado',
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE,
    INDEX idx_especialista (especialista_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: bloqueos_horario
CREATE TABLE bloqueos_horario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    especialista_id INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    motivo VARCHAR(255),
    tipo VARCHAR(50) DEFAULT 'vacaciones' COMMENT 'vacaciones, personal, enfermedad, otro',
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE,
    INDEX idx_especialista_fecha (especialista_id, fecha_inicio, fecha_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: reservaciones
CREATE TABLE reservaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    especialista_id INT NOT NULL,
    servicio_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    duracion INT NOT NULL COMMENT 'Duración en minutos',
    estado VARCHAR(50) DEFAULT 'pendiente' COMMENT 'pendiente, confirmada, completada, cancelada',
    notas TEXT,
    motivo_cancelacion TEXT,
    precio DECIMAL(10, 2),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id),
    INDEX idx_especialista (especialista_id),
    INDEX idx_fecha (fecha_hora),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: calificaciones
CREATE TABLE calificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservacion_id INT NOT NULL,
    cliente_id INT NOT NULL,
    especialista_id INT NOT NULL,
    servicio_id INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservacion_id) REFERENCES reservaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reservacion (reservacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: dias_festivos
CREATE TABLE dias_festivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    UNIQUE KEY unique_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: configuraciones
CREATE TABLE configuraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    tipo VARCHAR(50) DEFAULT 'string' COMMENT 'string, number, boolean, json',
    categoria VARCHAR(50) COMMENT 'general, email, whatsapp, api, colors',
    descripcion TEXT,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: logs_seguridad
CREATE TABLE logs_seguridad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    tipo VARCHAR(50) NOT NULL COMMENT 'login, logout, failed_login, registro, cambio_password, etc',
    descripcion TEXT,
    ip VARCHAR(45),
    user_agent TEXT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_tipo (tipo),
    INDEX idx_fecha (fecha_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: admin_sucursales (relación admin-sucursal)
CREATE TABLE admin_sucursales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE CASCADE,
    UNIQUE KEY unique_admin_sucursal (usuario_id, sucursal_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================

-- Insert usuarios (password: ReserBot2024 for all)
INSERT INTO usuarios (nombre, apellido, email, password, telefono, rol_id) VALUES
('Super', 'Admin', 'admin@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234567', 1),
('Carlos', 'García', 'admin.centro@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234568', 2),
('María', 'Torres', 'admin.juriquilla@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234569', 2),
('Ana', 'López', 'ana.lopez@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234570', 3),
('Roberto', 'Hernández', 'roberto.hernandez@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234571', 3),
('Patricia', 'Ramírez', 'patricia.ramirez@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234572', 3),
('Fernando', 'Silva', 'fernando.silva@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234573', 3),
('Juan', 'Pérez', 'juan.perez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234574', 4),
('Laura', 'Sánchez', 'laura.sanchez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234575', 4),
('Sofía', 'Torres', 'sofia.torres@reserbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4421234576', 5);

-- Insert sucursales (Querétaro locations)
INSERT INTO sucursales (nombre, direccion, ciudad, estado, codigo_postal, telefono, email, latitud, longitud) VALUES
('Centro Histórico', 'Calle 5 de Mayo #38, Centro Histórico', 'Querétaro', 'Querétaro', '76000', '4422123456', 'centro@reserbot.com', 20.5888, -100.3899),
('Juriquilla', 'Blvd. Juriquilla #1000, Juriquilla', 'Querétaro', 'Querétaro', '76230', '4422123457', 'juriquilla@reserbot.com', 20.6444, -100.4466),
('Corregidora', 'Av. Paseo de la República #100, El Pueblito', 'Corregidora', 'Querétaro', '76900', '4422123458', 'corregidora@reserbot.com', 20.5333, -100.4444);

-- Insert categorias_servicio
INSERT INTO categorias_servicio (nombre, descripcion, icono, orden) VALUES
('Medicina General', 'Consultas y atención médica general', 'fa-user-md', 1),
('Odontología', 'Servicios dentales y de salud bucal', 'fa-tooth', 2),
('Servicios Legales', 'Asesoría y consultas legales', 'fa-balance-scale', 3),
('Contabilidad', 'Servicios contables y fiscales', 'fa-calculator', 4),
('Psicología', 'Terapia y consultas psicológicas', 'fa-brain', 5),
('Nutrición', 'Planes alimenticios y asesoría nutricional', 'fa-apple-alt', 6);

-- Insert servicios
INSERT INTO servicios (categoria_id, nombre, descripcion, duracion, precio) VALUES
(1, 'Consulta Médica General', 'Revisión general de salud y diagnóstico', 30, 500.00),
(1, 'Consulta de Seguimiento', 'Consulta de control y seguimiento', 20, 350.00),
(2, 'Limpieza Dental', 'Limpieza profunda y revisión bucal', 45, 600.00),
(2, 'Extracción Dental', 'Extracción de pieza dental', 30, 800.00),
(3, 'Consulta Legal', 'Asesoría legal general', 60, 1000.00),
(3, 'Revisión de Contratos', 'Análisis y revisión de documentos legales', 45, 1200.00),
(4, 'Declaración de Impuestos', 'Preparación y presentación de declaraciones', 60, 1500.00),
(4, 'Consultoría Fiscal', 'Asesoría en temas fiscales', 45, 900.00),
(5, 'Terapia Individual', 'Sesión de terapia psicológica', 50, 700.00),
(6, 'Plan Nutricional', 'Diseño de plan alimenticio personalizado', 40, 800.00);

-- Insert especialistas
INSERT INTO especialistas (usuario_id, sucursal_id, especialidad, biografia, titulo, cedula_profesional, calificacion_promedio, total_calificaciones) VALUES
(4, 1, 'Medicina General', 'Médico general con 10 años de experiencia en atención primaria', 'Doctora en Medicina', '1234567', 4.8, 45),
(5, 1, 'Odontología', 'Especialista en odontología general y estética dental', 'Doctor en Odontología', '2345678', 4.9, 52),
(6, 2, 'Derecho Civil', 'Abogada especializada en derecho civil y familiar', 'Licenciada en Derecho', '3456789', 4.7, 38),
(7, 2, 'Contaduría Pública', 'Contador público certificado especializado en impuestos', 'Maestro en Contaduría', '4567890', 4.6, 30);

-- Insert especialista_servicios
INSERT INTO especialista_servicios (especialista_id, servicio_id) VALUES
(1, 1), (1, 2),
(2, 3), (2, 4),
(3, 5), (3, 6),
(4, 7), (4, 8);

-- Insert horarios_especialista
-- Dra. Ana López (Especialista 1) - Lunes a Viernes 9:00-17:00
INSERT INTO horarios_especialista (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(1, 1, '09:00:00', '17:00:00'),
(1, 2, '09:00:00', '17:00:00'),
(1, 3, '09:00:00', '17:00:00'),
(1, 4, '09:00:00', '17:00:00'),
(1, 5, '09:00:00', '17:00:00');

-- Dr. Roberto Hernández (Especialista 2) - Lunes a Sábado 10:00-18:00
INSERT INTO horarios_especialista (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(2, 1, '10:00:00', '18:00:00'),
(2, 2, '10:00:00', '18:00:00'),
(2, 3, '10:00:00', '18:00:00'),
(2, 4, '10:00:00', '18:00:00'),
(2, 5, '10:00:00', '18:00:00'),
(2, 6, '10:00:00', '14:00:00');

-- Lic. Patricia Ramírez (Especialista 3) - Martes a Sábado 11:00-19:00
INSERT INTO horarios_especialista (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(3, 2, '11:00:00', '19:00:00'),
(3, 3, '11:00:00', '19:00:00'),
(3, 4, '11:00:00', '19:00:00'),
(3, 5, '11:00:00', '19:00:00'),
(3, 6, '11:00:00', '15:00:00');

-- Mtro. Fernando Silva (Especialista 4) - Lunes a Viernes 8:00-16:00
INSERT INTO horarios_especialista (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(4, 1, '08:00:00', '16:00:00'),
(4, 2, '08:00:00', '16:00:00'),
(4, 3, '08:00:00', '16:00:00'),
(4, 4, '08:00:00', '16:00:00'),
(4, 5, '08:00:00', '16:00:00');

-- Insert dias_festivos (México 2024-2025)
INSERT INTO dias_festivos (fecha, nombre, descripcion) VALUES
('2024-01-01', 'Año Nuevo', 'Celebración de año nuevo'),
('2024-02-05', 'Día de la Constitución', 'Conmemoración de la Constitución Mexicana'),
('2024-03-18', 'Natalicio de Benito Juárez', 'Aniversario del nacimiento de Benito Juárez'),
('2024-05-01', 'Día del Trabajo', 'Día Internacional del Trabajo'),
('2024-09-16', 'Día de la Independencia', 'Independencia de México'),
('2024-11-18', 'Día de la Revolución Mexicana', 'Aniversario de la Revolución Mexicana'),
('2024-12-25', 'Navidad', 'Celebración de Navidad'),
('2025-01-01', 'Año Nuevo', 'Celebración de año nuevo'),
('2025-02-03', 'Día de la Constitución', 'Conmemoración de la Constitución Mexicana'),
('2025-03-17', 'Natalicio de Benito Juárez', 'Aniversario del nacimiento de Benito Juárez'),
('2025-05-01', 'Día del Trabajo', 'Día Internacional del Trabajo'),
('2025-09-16', 'Día de la Independencia', 'Independencia de México'),
('2025-11-17', 'Día de la Revolución Mexicana', 'Aniversario de la Revolución Mexicana'),
('2025-12-25', 'Navidad', 'Celebración de Navidad');

-- Insert admin_sucursales
INSERT INTO admin_sucursales (usuario_id, sucursal_id) VALUES
(2, 1), -- Admin Centro
(3, 2); -- Admin Juriquilla

-- Insert configuraciones
INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion) VALUES
('site_name', 'ReserBot', 'string', 'general', 'Nombre del sitio'),
('site_logo', '', 'string', 'general', 'URL del logo del sitio'),
('site_email', 'info@reserbot.com', 'string', 'general', 'Email de contacto del sitio'),
('site_phone', '442-212-3456', 'string', 'general', 'Teléfono de contacto'),
('site_address', 'Querétaro, México', 'string', 'general', 'Dirección del sitio'),
('site_description', 'Sistema profesional de reservaciones y citas', 'string', 'general', 'Descripción del sitio'),
('primary_color', '#3B82F6', 'string', 'colors', 'Color primario'),
('secondary_color', '#10B981', 'string', 'colors', 'Color secundario'),
('smtp_host', '', 'string', 'email', 'Servidor SMTP'),
('smtp_port', '587', 'number', 'email', 'Puerto SMTP'),
('smtp_user', '', 'string', 'email', 'Usuario SMTP'),
('smtp_password', '', 'string', 'email', 'Contraseña SMTP'),
('whatsapp_number', '', 'string', 'whatsapp', 'Número de WhatsApp'),
('whatsapp_enabled', 'false', 'boolean', 'whatsapp', 'WhatsApp habilitado'),
('paypal_client_id', '', 'string', 'api', 'PayPal Client ID'),
('paypal_secret', '', 'string', 'api', 'PayPal Secret'),
('qr_api_key', '', 'string', 'api', 'API Key para generación de QR'),
('hikvision_api_url', '', 'string', 'api', 'URL API HikVision'),
('shelly_relay_api_key', '', 'string', 'api', 'API Key Shelly Relay');

-- Insert sample reservaciones
INSERT INTO reservaciones (cliente_id, especialista_id, servicio_id, sucursal_id, fecha_hora, duracion, estado, precio) VALUES
(8, 1, 1, 1, '2024-11-15 10:00:00', 30, 'confirmada', 500.00),
(8, 2, 3, 1, '2024-11-16 14:00:00', 45, 'pendiente', 600.00),
(9, 3, 5, 2, '2024-11-17 11:00:00', 60, 'confirmada', 1000.00),
(9, 4, 7, 2, '2024-11-18 09:00:00', 60, 'pendiente', 1500.00);

-- Insert sample calificaciones
INSERT INTO calificaciones (reservacion_id, cliente_id, especialista_id, servicio_id, calificacion, comentario) VALUES
(1, 8, 1, 1, 5, 'Excelente atención, muy profesional y amable.');
