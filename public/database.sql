-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8mb3 ;
-- -----------------------------------------------------
-- Schema solicitud_final
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema solicitud_final
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `solicitud_final` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
-- USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`cargo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`cargo` (
  `idcargo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_cargo` VARCHAR(45) NOT NULL,
  `descripcion_cargo` VARCHAR(45) NOT NULL,
  `fecha_registro` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`idcargo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `solicitud_final`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`usuarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `apellido` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `cargo` VARCHAR(150) NULL DEFAULT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_email` (`email` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`dependencias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`dependencias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `codigo` VARCHAR(50) NULL DEFAULT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`estados_solicitud`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`estados_solicitud` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255) NULL DEFAULT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_estado_nombre` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`solicitudes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`solicitudes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_radicado` VARCHAR(30) NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `dependencia_id` INT UNSIGNED NOT NULL,
  `estado_id` INT UNSIGNED NOT NULL,
  `fecha_solicitud` DATE NOT NULL,
  `justificacion` TEXT NULL DEFAULT NULL,
  `valor_total` DECIMAL(18,2) NOT NULL DEFAULT '0.00',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_radicado` (`numero_radicado` ASC) VISIBLE,
  INDEX `usuario_id` (`usuario_id` ASC) VISIBLE,
  INDEX `dependencia_id` (`dependencia_id` ASC) VISIBLE,
  INDEX `estado_id` (`estado_id` ASC) VISIBLE,
  CONSTRAINT `solicitudes_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `solicitud_final`.`usuarios` (`id`),
  CONSTRAINT `solicitudes_ibfk_2`
    FOREIGN KEY (`dependencia_id`)
    REFERENCES `solicitud_final`.`dependencias` (`id`),
  CONSTRAINT `solicitudes_ibfk_3`
    FOREIGN KEY (`estado_id`)
    REFERENCES `solicitud_final`.`estados_solicitud` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `mydb`.`items_solicitud_refrigerio_almuerzo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`items_solicitud_refrigerio_almuerzo` (
  `iditems_solicitud_refrigerio_almuerzo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `dia` ENUM('1', '2', '3', '4', '5', '6') NOT NULL,
  `hora` VARCHAR(15) NOT NULL,
  `cantidad` INT NOT NULL,
  `alimentos` VARCHAR(100) NULL DEFAULT NULL,
  `bebidas` VARCHAR(100) NULL DEFAULT NULL,
  `tipo_solicitud` ENUM('Refrigerio', 'Desayuno', 'Almuerzo', 'Cena') NOT NULL,
  `requiere_mesero` ENUM('Si', 'No') NOT NULL,
  `lugar_entrega` VARCHAR(100) NOT NULL,
  `id_solicitud` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`iditems_solicitud_refrigerio_almuerzo`),
  INDEX `id_solicitud_idx` (`id_solicitud` ASC) VISIBLE,
  CONSTRAINT `id_solicitud`
    FOREIGN KEY (`id_solicitud`)
    REFERENCES `solicitud_final`.`solicitudes` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;

USE `solicitud_final` ;

-- -----------------------------------------------------
-- Table `solicitud_final`.`archivos_adjuntos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`archivos_adjuntos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `solicitud_id` INT UNSIGNED NOT NULL,
  `nombre_archivo` VARCHAR(255) NOT NULL,
  `ruta_archivo` VARCHAR(500) NOT NULL,
  `tipo_mime` VARCHAR(100) NULL DEFAULT NULL,
  `tamano_bytes` INT UNSIGNED NULL DEFAULT NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `solicitud_id` (`solicitud_id` ASC) VISIBLE,
  CONSTRAINT `archivos_adjuntos_ibfk_1`
    FOREIGN KEY (`solicitud_id`)
    REFERENCES `solicitud_final`.`solicitudes` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`centros_costo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`centros_costo` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `codigo` INT UNSIGNED NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_cc_codigo` (`codigo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`fondos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`fondos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(500) NOT NULL,
  `codigo` VARCHAR(20) NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_ff_codigo` (`codigo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`fondos_funcion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`fondos_funcion` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(500) NOT NULL,
  `codigo` VARCHAR(20) NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_ff_codigo` (`codigo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`funcion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`funcion` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(500) NOT NULL,
  `codigo` VARCHAR(20) NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_ff_codigo` (`codigo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`historial_estados`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`historial_estados` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `solicitud_id` INT UNSIGNED NOT NULL,
  `estado_anterior_id` INT UNSIGNED NULL DEFAULT NULL,
  `estado_nuevo_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `observacion` TEXT NULL DEFAULT NULL,
  `notificado` TINYINT(1) NOT NULL DEFAULT '0',
  `fecha_notificado` TIMESTAMP NULL DEFAULT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `solicitud_id` (`solicitud_id` ASC) VISIBLE,
  INDEX `estado_anterior_id` (`estado_anterior_id` ASC) VISIBLE,
  INDEX `estado_nuevo_id` (`estado_nuevo_id` ASC) VISIBLE,
  INDEX `usuario_id` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `historial_estados_ibfk_1`
    FOREIGN KEY (`solicitud_id`)
    REFERENCES `solicitud_final`.`solicitudes` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `historial_estados_ibfk_2`
    FOREIGN KEY (`estado_anterior_id`)
    REFERENCES `solicitud_final`.`estados_solicitud` (`id`),
  CONSTRAINT `historial_estados_ibfk_3`
    FOREIGN KEY (`estado_nuevo_id`)
    REFERENCES `solicitud_final`.`estados_solicitud` (`id`),
  CONSTRAINT `historial_estados_ibfk_4`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `solicitud_final`.`usuarios` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`rubros`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`rubros` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `codigo` VARCHAR(20) NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_rubro_codigo` (`codigo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`items_solicitud`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`items_solicitud` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `solicitud_id` INT UNSIGNED NOT NULL,
  `descripcion_servicio` TEXT NOT NULL,
  `cantidad` INT UNSIGNED NOT NULL DEFAULT '1',
  `precio_unitario` DECIMAL(18,2) NOT NULL DEFAULT '0.00',
  `valor_total` DECIMAL(18,2) NOT NULL DEFAULT '0.00',
  `centro_costo_id` INT UNSIGNED NOT NULL,
  `rubro_id` INT UNSIGNED NOT NULL,
  `fondo_funcion_id` INT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `solicitud_id` (`solicitud_id` ASC) VISIBLE,
  INDEX `centro_costo_id` (`centro_costo_id` ASC) VISIBLE,
  INDEX `rubro_id` (`rubro_id` ASC) VISIBLE,
  INDEX `fondo_funcion_id` (`fondo_funcion_id` ASC) VISIBLE,
  CONSTRAINT `items_solicitud_ibfk_1`
    FOREIGN KEY (`solicitud_id`)
    REFERENCES `solicitud_final`.`solicitudes` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `items_solicitud_ibfk_2`
    FOREIGN KEY (`centro_costo_id`)
    REFERENCES `solicitud_final`.`centros_costo` (`id`),
  CONSTRAINT `items_solicitud_ibfk_3`
    FOREIGN KEY (`rubro_id`)
    REFERENCES `solicitud_final`.`rubros` (`id`),
  CONSTRAINT `items_solicitud_ibfk_4`
    FOREIGN KEY (`fondo_funcion_id`)
    REFERENCES `solicitud_final`.`fondos_funcion` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`revision`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`revision` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `solicitud_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `nivel` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `decision` ENUM('aprobada', 'rechazada', 'devuelta') NOT NULL,
  `observacion` TEXT NULL DEFAULT NULL,
  `fecha` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `solicitud_id` (`solicitud_id` ASC) VISIBLE,
  INDEX `usuario_id` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `revision_ibfk_1`
    FOREIGN KEY (`solicitud_id`)
    REFERENCES `solicitud_final`.`solicitudes` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `revision_ibfk_2`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `solicitud_final`.`usuarios` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255) NULL DEFAULT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_rol_nombre` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`usuario_dependencia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`usuario_dependencia` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `dependencia_id` INT UNSIGNED NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `usuario_id` (`usuario_id` ASC) VISIBLE,
  INDEX `dependencia_id` (`dependencia_id` ASC) VISIBLE,
  CONSTRAINT `usuario_dependencia_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `solicitud_final`.`usuarios` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `usuario_dependencia_ibfk_2`
    FOREIGN KEY (`dependencia_id`)
    REFERENCES `solicitud_final`.`dependencias` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `solicitud_final`.`usuario_rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitud_final`.`usuario_rol` (
  `usuario_id` INT UNSIGNED NOT NULL,
  `rol_id` INT UNSIGNED NOT NULL,
  `asignado_desde` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_id`, `rol_id`),
  INDEX `rol_id` (`rol_id` ASC) VISIBLE,
  CONSTRAINT `usuario_rol_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `solicitud_final`.`usuarios` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `usuario_rol_ibfk_2`
    FOREIGN KEY (`rol_id`)
    REFERENCES `solicitud_final`.`roles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- Estados
INSERT INTO `estados_solicitud` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Pendiente',   'Solicitud recibida, pendiente de revisión'),
(2, 'En revisión', 'Solicitud en proceso de evaluación'),
(3, 'Aprobada',    'Solicitud aprobada por el área correspondiente'),
(4, 'Rechazada',   'Solicitud rechazada con observaciones');

-- Roles
INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Solicitante',   'Puede crear y ver sus propias solicitudes'),
(3, 'Revisor',       'Puede revisar y aprobar solicitudes');

-- Dependencias
INSERT INTO `dependencias` (`id`, `nombre`, `codigo`) VALUES
(1, 'Rectoría',                      'REC-001'),
(2, 'Vicerrectoría Académica',       'VRA-002'),
(3, 'Bienestar Universitario',       'BU-003'),
(4, 'Facultad de Ingeniería',        'FI-004'),
(5, 'Facultad de Ciencias Sociales', 'FCS-005'),
(6, 'Dirección Financiera',          'DF-006'),
(7, 'Recursos Humanos',              'RH-007');

-- Usuarios (contraseña de todos: cecar2024)
INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password_hash`, `cargo`) VALUES
(1, 'Alexis', 'Mendoza',  'alexis.mendoza@cecar.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Estudiante'),
(2, 'María',  'González', 'maria.gonzalez@cecar.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Docente'),
(3, 'Admin',  'Sistema',  'admin@cecar.edu.co',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador');

-- Solicitudes de prueba
INSERT INTO `solicitudes` (`numero_radicado`, `usuario_id`, `dependencia_id`, `estado_id`, `fecha_solicitud`, `justificacion`, `valor_total`) VALUES
('RAD-20260501-A1B2C3', 1, 3, 1, '2026-05-01', 'Refrigerio para evento de bienvenida.', 150000.00),
('RAD-20260502-D4E5F6', 2, 4, 2, '2026-05-02', 'Almuerzo para reunión de docentes.',    320000.00),
('RAD-20260503-G7H8I9', 3, 2, 3, '2026-05-03', 'Desayuno para taller de formación.',    180000.00);