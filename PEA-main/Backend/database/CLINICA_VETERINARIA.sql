-- =========================================
-- BASE DE DATOS: CLÍNICA VETERINARIA
-- =========================================
-- Creado: Diciembre 2024
-- Sistema: Gestión de citas veterinarias
-- Base de datos: clinica_veterinaria
-- =========================================

-- Crear Base de Datos
CREATE DATABASE IF NOT EXISTS clinica_veterinaria 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE clinica_veterinaria;

-- =========================================
-- TABLA: USUARIOS
-- =========================================
CREATE TABLE IF NOT EXISTS users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  rol VARCHAR(50) DEFAULT 'cliente',
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_users_email (email),
  INDEX idx_users_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLA: MASCOTAS
-- =========================================
CREATE TABLE IF NOT EXISTS mascotas (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  especie VARCHAR(100) NOT NULL,
  raza VARCHAR(100),
  edad INT,
  peso DECIMAL(8,2),
  descripcion TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_mascotas_user_id (user_id),
  INDEX idx_mascotas_especie (especie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLA: SERVICIOS
-- =========================================
CREATE TABLE IF NOT EXISTS servicios (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  tipo ENUM('consulta','vacuna','bano','cirugia','odontologia','radiografia','analisis','otros') NOT NULL DEFAULT 'consulta',
  duracion INT NOT NULL COMMENT 'Duración en minutos',
  imagen VARCHAR(255),
  activo BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_servicios_slug (slug),
  INDEX idx_servicios_tipo (tipo),
  INDEX idx_servicios_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLA: VETERINARIOS
-- =========================================
CREATE TABLE IF NOT EXISTS veterinarios (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  telefono VARCHAR(20),
  especialidad VARCHAR(255),
  licencia VARCHAR(255) UNIQUE NOT NULL,
  activo BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_veterinarios_email (email),
  INDEX idx_veterinarios_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLA: HORARIOS VETERINARIOS
-- =========================================
CREATE TABLE IF NOT EXISTS horarios_veterinarios (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  veterinario_id BIGINT NOT NULL,
  dia_semana INT NOT NULL COMMENT '0=Domingo, 1=Lunes, ..., 6=Sábado',
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  es_activo BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (veterinario_id) REFERENCES veterinarios(id) ON DELETE CASCADE,
  INDEX idx_horarios_veterinario_id (veterinario_id),
  INDEX idx_horarios_dia_semana (dia_semana)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLA: CITAS
-- =========================================
CREATE TABLE IF NOT EXISTS citas (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  mascota_id BIGINT NOT NULL,
  servicio_id BIGINT NOT NULL,
  veterinario_id BIGINT,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  estado ENUM('pendiente','confirmada','rechazada','completada','cancelada') DEFAULT 'pendiente',
  observaciones TEXT,
  razon_rechazo TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (mascota_id) REFERENCES mascotas(id) ON DELETE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE RESTRICT,
  FOREIGN KEY (veterinario_id) REFERENCES veterinarios(id) ON DELETE SET NULL,
  
  INDEX idx_citas_user_id (user_id),
  INDEX idx_citas_mascota_id (mascota_id),
  INDEX idx_citas_servicio_id (servicio_id),
  INDEX idx_citas_veterinario_id (veterinario_id),
  INDEX idx_citas_fecha (fecha),
  INDEX idx_citas_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLA: PERSONAL ACCESS TOKENS
-- =========================================
CREATE TABLE IF NOT EXISTS personal_access_tokens (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  tokenable_type VARCHAR(255) NOT NULL,
  tokenable_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  token VARCHAR(80) UNIQUE NOT NULL,
  abilities TEXT,
  last_used_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_personal_access_tokens_tokenable (tokenable_type, tokenable_id),
  INDEX idx_personal_access_tokens_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- INSERTAR DATOS DE PRUEBA (OPCIONAL)
-- =========================================

-- Usuarios
INSERT INTO users (name, email, password, rol) VALUES
('Cliente Prueba', 'cliente@ejemplo.com', '$2y$12$X.uW6HX3u.tQ7.j9X.uW6HX3u.tQ7.j9X.uW6HX3u.tQ7.j9X.uW6HX3', 'cliente'),
('Admin Prueba', 'admin@ejemplo.com', '$2y$12$X.uW6HX3u.tQ7.j9X.uW6HX3u.tQ7.j9X.uW6HX3u.tQ7.j9X.uW6HX3', 'admin');

-- Servicios
INSERT INTO servicios (nombre, slug, descripcion, precio, tipo, duracion) VALUES
('Consulta General', 'consulta-general', 'Revisión completa de tu mascota', 50000, 'consulta', 30),
('Vacuna Anual', 'vacuna-anual', 'Vacunación anual completa', 80000, 'vacuna', 20),
('Baño y Aseo', 'bano-aseo', 'Baño, secado y cepillado', 60000, 'bano', 60),
('Cirugía Menor', 'cirugia-menor', 'Cirugía menor con anestesia', 200000, 'cirugia', 120),
('Limpieza Dental', 'limpieza-dental', 'Limpieza y desinfección dental', 150000, 'odontologia', 45),
('Radiografía', 'radiografia', 'Radiografía digital', 100000, 'radiografia', 30),
('Análisis Clínicos', 'analisis-clinicos', 'Sangre, orina y heces', 120000, 'analisis', 45),
('Desparasitación', 'desparasitacion', 'Tratamiento de parásitos internos y externos', 40000, 'otros', 20);

-- Veterinarios
INSERT INTO veterinarios (nombre, email, telefono, especialidad, licencia) VALUES
('Dra. María García', 'maria@clinica.com', '300-123-4567', 'Medicina General', 'MV-001-BOG'),
('Dr. Carlos López', 'carlos@clinica.com', '301-234-5678', 'Cirugía', 'MV-002-BOG'),
('Dra. Laura Rodríguez', 'laura@clinica.com', '302-345-6789', 'Dermatología', 'MV-003-BOG'),
('Dr. Juan Martínez', 'juan@clinica.com', '303-456-7890', 'Odontología', 'MV-004-BOG');

-- Horarios Veterinarios
INSERT INTO horarios_veterinarios (veterinario_id, dia_semana, hora_inicio, hora_fin, es_activo) VALUES
-- Dra. María (Lunes a Viernes, 8am-6pm)
(1, 1, '08:00:00', '18:00:00', TRUE),
(1, 2, '08:00:00', '18:00:00', TRUE),
(1, 3, '08:00:00', '18:00:00', TRUE),
(1, 4, '08:00:00', '18:00:00', TRUE),
(1, 5, '08:00:00', '18:00:00', TRUE),
(1, 6, '10:00:00', '14:00:00', TRUE),
-- Dr. Carlos (Lunes a Viernes, 9am-5pm)
(2, 1, '09:00:00', '17:00:00', TRUE),
(2, 2, '09:00:00', '17:00:00', TRUE),
(2, 3, '09:00:00', '17:00:00', TRUE),
(2, 4, '09:00:00', '17:00:00', TRUE),
(2, 5, '09:00:00', '17:00:00', TRUE),
-- Dra. Laura (Martes a Sábado, 10am-6pm)
(3, 2, '10:00:00', '18:00:00', TRUE),
(3, 3, '10:00:00', '18:00:00', TRUE),
(3, 4, '10:00:00', '18:00:00', TRUE),
(3, 5, '10:00:00', '18:00:00', TRUE),
(3, 6, '10:00:00', '18:00:00', TRUE),
-- Dr. Juan (Lunes, Miércoles, Viernes, 8am-4pm)
(4, 1, '08:00:00', '16:00:00', TRUE),
(4, 3, '08:00:00', '16:00:00', TRUE),
(4, 5, '08:00:00', '16:00:00', TRUE);

-- =========================================
-- VISTAS ÚTILES
-- =========================================

-- Vista: Citas con información completa
CREATE OR REPLACE VIEW v_citas_completas AS
SELECT 
  c.id,
  c.fecha,
  c.hora,
  c.estado,
  u.name AS cliente,
  u.email AS cliente_email,
  m.nombre AS mascota,
  m.especie,
  m.raza,
  s.nombre AS servicio,
  s.precio,
  s.tipo,
  v.nombre AS veterinario,
  v.especialidad
FROM citas c
JOIN users u ON c.user_id = u.id
JOIN mascotas m ON c.mascota_id = m.id
JOIN servicios s ON c.servicio_id = s.id
LEFT JOIN veterinarios v ON c.veterinario_id = v.id
ORDER BY c.fecha DESC, c.hora DESC;

-- Vista: Disponibilidad de veterinarios
CREATE OR REPLACE VIEW v_disponibilidad_veterinarios AS
SELECT 
  v.id,
  v.nombre,
  v.especialidad,
  v.email,
  h.dia_semana,
  h.hora_inicio,
  h.hora_fin,
  (SELECT COUNT(*) FROM citas WHERE veterinario_id = v.id AND estado = 'confirmada') AS citas_confirmadas
FROM veterinarios v
JOIN horarios_veterinarios h ON v.id = h.veterinario_id
WHERE v.activo = TRUE AND h.es_activo = TRUE
ORDER BY v.nombre, h.dia_semana, h.hora_inicio;

-- Vista: Próximas citas por cliente
CREATE OR REPLACE VIEW v_proximas_citas AS
SELECT 
  c.id,
  c.fecha,
  c.hora,
  u.name AS cliente,
  m.nombre AS mascota,
  s.nombre AS servicio,
  v.nombre AS veterinario,
  c.estado
FROM citas c
JOIN users u ON c.user_id = u.id
JOIN mascotas m ON c.mascota_id = m.id
JOIN servicios s ON c.servicio_id = s.id
LEFT JOIN veterinarios v ON c.veterinario_id = v.id
WHERE c.fecha >= CURDATE() AND c.estado IN ('pendiente', 'confirmada')
ORDER BY c.fecha ASC, c.hora ASC;

-- =========================================
-- ESTADÍSTICAS Y TRIGGERS
-- =========================================

-- Trigger: Validar que la hora de fin sea posterior a la hora de inicio
DELIMITER //
CREATE TRIGGER tr_validar_horarios_veterinarios 
BEFORE INSERT ON horarios_veterinarios
FOR EACH ROW
BEGIN
  IF NEW.hora_fin <= NEW.hora_inicio THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'La hora de fin debe ser posterior a la hora de inicio';
  END IF;
END//
DELIMITER ;

-- Trigger: Validar que la fecha de la cita no sea en el pasado
DELIMITER //
CREATE TRIGGER tr_validar_citas_fecha
BEFORE INSERT ON citas
FOR EACH ROW
BEGIN
  IF NEW.fecha < CURDATE() THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'No se pueden agendar citas en el pasado';
  END IF;
END//
DELIMITER ;

-- =========================================
-- PROCEDIMIENTOS ALMACENADOS
-- =========================================

-- Procedimiento: Obtener disponibilidad de veterinarios
DELIMITER //
CREATE PROCEDURE sp_obtener_disponibilidad(
  IN p_fecha DATE,
  IN p_servicio_id BIGINT
)
BEGIN
  SELECT 
    v.id,
    v.nombre,
    v.especialidad,
    h.hora_inicio,
    h.hora_fin,
    (SELECT COUNT(*) FROM citas 
     WHERE veterinario_id = v.id 
     AND fecha = p_fecha 
     AND estado = 'confirmada') AS citas_agendadas
  FROM veterinarios v
  JOIN horarios_veterinarios h ON v.id = h.veterinario_id
  WHERE v.activo = TRUE 
    AND h.es_activo = TRUE
    AND h.dia_semana = DAYOFWEEK(p_fecha) - 1
  ORDER BY v.nombre, h.hora_inicio;
END//
DELIMITER ;

-- Procedimiento: Cancelar cita y liberar veterinario
DELIMITER //
CREATE PROCEDURE sp_cancelar_cita(
  IN p_cita_id BIGINT,
  IN p_razon VARCHAR(255)
)
BEGIN
  UPDATE citas 
  SET estado = 'cancelada',
      razon_rechazo = p_razon,
      updated_at = CURRENT_TIMESTAMP
  WHERE id = p_cita_id;
END//
DELIMITER ;

-- =========================================
-- ÍNDICES ADICIONALES
-- =========================================

-- Índices para búsquedas frecuentes
CREATE INDEX idx_citas_fecha_estado ON citas(fecha, estado);
CREATE INDEX idx_horarios_activos ON horarios_veterinarios(veterinario_id, es_activo);
CREATE INDEX idx_servicios_precio ON servicios(precio);
CREATE INDEX idx_mascotas_nombre ON mascotas(nombre);

-- =========================================
-- FIN DEL SCRIPT
-- =========================================
-- Base de datos lista para usar
-- Fecha: Diciembre 2024
-- Version: 1.0
-- =========================================
