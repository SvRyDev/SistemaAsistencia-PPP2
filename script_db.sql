-- Crear base de datos y usarla
CREATE DATABASE IF NOT EXISTS db_asistencia_estudiantes;
USE db_asistencia_estudiantes;

-- Crear tabla estudiante
CREATE TABLE `estudiante` (
  `estudiante_id` INT PRIMARY KEY AUTO_INCREMENT,
  `codigo` VARCHAR(11),
  `nombre` VARCHAR(105),
  `apellidos` VARCHAR(255),
  `dni` CHAR(8),
  `grado` VARCHAR(50),
  `seccion` VARCHAR(50),
  `date_created` DATETIME NOT NULL
);

-- Crear tabla carnet_estudiante
CREATE TABLE `carnet_estudiante` (
  `carnet_id` INT PRIMARY KEY AUTO_INCREMENT,
  `estudiante_id` INT UNIQUE NOT NULL,
  `foto_path` VARCHAR(255) UNIQUE,
  `codigo_barras_path` VARCHAR(255) UNIQUE,
  FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`estudiante_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- Crear tabla dia_fecha
CREATE TABLE `dia_fecha` (
  `dia_fecha_id` INT PRIMARY KEY AUTO_INCREMENT,
  `fecha` DATE,
  `nombre` VARCHAR(20),
  `hora_entrada` TIME,
  `hora_salida` TIME
);

-- Crear tabla asistencia_estudiante
CREATE TABLE `asistencia_estudiante` (
  `asistencia_estudiante_id` INT PRIMARY KEY AUTO_INCREMENT,
  `estudiante_id` INT NOT NULL,
  `dia_fecha_id` INT,
  `hora_entrada` DATETIME,
  `hora_salida` DATETIME,
  `condicion` VARCHAR(20),
  `observacion` VARCHAR(250),
  FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`estudiante_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`dia_fecha_id`) REFERENCES `dia_fecha` (`dia_fecha_id`)
    ON DELETE SET NULL ON UPDATE CASCADE
);

-- Insertar estudiantes de prueba
INSERT INTO estudiante (`codigo`, `nombre`, `apellidos`, `dni`, `grado`, `seccion`, `date_created`) VALUES
('STU001', 'Ana', 'Martínez López', '12345678', '5°', 'A', NOW()),
('STU002', 'Luis', 'Ramírez Torres', '87654321', '5°', 'B', NOW()),
('STU003', 'María', 'González Díaz', '11223344', '6°', 'A', NOW()),
('STU004', 'Carlos', 'Pérez Rojas', '22334455', '6°', 'B', NOW()),
('STU005', 'Laura', 'Fernández Ruiz', '33445566', '5°', 'A', NOW());

-- Insertar carnets de prueba
INSERT INTO carnet_estudiante (`estudiante_id`, `foto_path`, `codigo_barras_path`) VALUES
(1, 'fotos/ana.jpg', 'barcodes/ana.png'),
(2, 'fotos/luis.jpg', 'barcodes/luis.png'),
(3, 'fotos/maria.jpg', 'barcodes/maria.png'),
(4, 'fotos/carlos.jpg', 'barcodes/carlos.png'),
(5, 'fotos/laura.jpg', 'barcodes/laura.png');

-- Insertar fechas de asistencia
INSERT INTO dia_fecha (`fecha`, `nombre`, `hora_entrada`, `hora_salida`) VALUES
('2025-05-29', 'Jueves', '08:00:00', '13:00:00'),
('2025-05-28', 'Miércoles', '08:00:00', '13:00:00'),
('2025-05-27', 'Martes', '08:00:00', '13:00:00'),
('2025-05-26', 'Lunes', '08:00:00', '13:00:00'),
('2025-05-23', 'Viernes', '08:00:00', '13:00:00');

-- Insertar asistencias de prueba
INSERT INTO asistencia_estudiante (`estudiante_id`, `dia_fecha_id`, `hora_entrada`, `hora_salida`, `condicion`, `observacion`) VALUES
(1, 1, '2025-05-29 08:01:00', '2025-05-29 13:00:00', 'Presente', ''),
(2, 1, '2025-05-29 08:10:00', '2025-05-29 13:05:00', 'Tarde', 'Llegó 10 min tarde'),
(3, 1, NULL, NULL, 'Ausente', 'Sin registrar'),
(4, 2, '2025-05-28 08:00:00', '2025-05-28 13:00:00', 'Presente', ''),
(5, 3, '2025-05-27 07:58:00', '2025-05-27 12:55:00', 'Presente', '');
