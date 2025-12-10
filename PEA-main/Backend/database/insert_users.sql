-- Limpiar usuarios anteriores
DELETE FROM users;

-- Insertar usuarios de prueba con contraseña hasheada
-- Contraseña: password (hasheada con bcrypt)
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO users (id, name, email, password, rol, created_at, updated_at) VALUES
(1, 'Cliente Test', 'cliente@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), NOW()),
(2, 'Admin Test', 'admin@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW());
