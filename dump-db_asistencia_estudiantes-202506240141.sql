/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_asistencia_estudiantes
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `asistencia_estudiante`
--

DROP TABLE IF EXISTS `asistencia_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia_estudiante` (
  `asistencia_estudiante_id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `dia_fecha_id` int(11) DEFAULT NULL,
  `hora_entrada` datetime DEFAULT NULL,
  `hora_salida` datetime DEFAULT NULL,
  `estado_asistencia_id` int(11) DEFAULT NULL,
  `observacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`asistencia_estudiante_id`),
  KEY `estudiante_id` (`estudiante_id`),
  KEY `dia_fecha_id` (`dia_fecha_id`),
  KEY `fk_asistencia_estudiante_estado` (`estado_asistencia_id`),
  CONSTRAINT `asistencia_estudiante_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`estudiante_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistencia_estudiante_ibfk_2` FOREIGN KEY (`dia_fecha_id`) REFERENCES `dia_asistencia` (`dia_fecha_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_asistencia_estudiante_estado` FOREIGN KEY (`estado_asistencia_id`) REFERENCES `estados_asistencia` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia_estudiante`
--

LOCK TABLES `asistencia_estudiante` WRITE;
/*!40000 ALTER TABLE `asistencia_estudiante` DISABLE KEYS */;
INSERT INTO `asistencia_estudiante` VALUES
(1,1,3,'2025-05-29 08:01:00','2025-05-29 13:00:00',1,''),
(2,1,2,'2025-05-29 08:10:00','2025-05-29 13:05:00',2,'Llegó 10 min tarde'),
(3,1,1,'2025-05-29 08:10:00','2025-05-29 08:10:00',1,'Sin registrar'),
(4,4,2,'2025-05-28 08:00:00','2025-05-28 13:00:00',1,''),
(5,5,3,'2025-05-27 07:58:00','2025-05-27 12:55:00',1,''),
(6,1,4,'2025-05-27 07:58:00','2025-05-27 12:55:00',1,NULL),
(7,1,5,'2025-05-27 07:58:00','2025-05-27 12:55:00',1,NULL),
(8,1,6,'2025-05-27 07:58:00','2025-05-27 07:58:00',1,NULL),
(9,1,7,'2025-05-27 07:58:00','2025-05-27 07:58:00',1,NULL);
/*!40000 ALTER TABLE `asistencia_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carnet_estudiante`
--

DROP TABLE IF EXISTS `carnet_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carnet_estudiante` (
  `carnet_id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `codigo_barras_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`carnet_id`),
  UNIQUE KEY `estudiante_id` (`estudiante_id`),
  UNIQUE KEY `foto_path` (`foto_path`),
  UNIQUE KEY `codigo_barras_path` (`codigo_barras_path`),
  CONSTRAINT `carnet_estudiante_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`estudiante_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carnet_estudiante`
--

LOCK TABLES `carnet_estudiante` WRITE;
/*!40000 ALTER TABLE `carnet_estudiante` DISABLE KEYS */;
INSERT INTO `carnet_estudiante` VALUES
(1,1,'fotos/ana.jpg','barcodes/ana.png'),
(2,2,'fotos/luis.jpg','barcodes/luis.png'),
(3,3,'fotos/maria.jpg','barcodes/maria.png'),
(4,4,'fotos/carlos.jpg','barcodes/carlos.png'),
(5,5,'fotos/laura.jpg','barcodes/laura.png');
/*!40000 ALTER TABLE `carnet_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dia_asistencia`
--

DROP TABLE IF EXISTS `dia_asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dia_asistencia` (
  `dia_fecha_id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `total_asistidos` int(11) DEFAULT NULL,
  `total_justificados` int(11) DEFAULT NULL,
  `total_tardanza` int(11) DEFAULT NULL,
  PRIMARY KEY (`dia_fecha_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dia_asistencia`
--

LOCK TABLES `dia_asistencia` WRITE;
/*!40000 ALTER TABLE `dia_asistencia` DISABLE KEYS */;
INSERT INTO `dia_asistencia` VALUES
(1,'2025-05-12','Jueves','08:00:00','13:00:00',NULL,NULL,NULL),
(2,'2025-05-13','Miércoles','08:00:00','13:00:00',NULL,NULL,NULL),
(3,'2025-05-14','Martes','08:00:00','13:00:00',NULL,NULL,NULL),
(4,'2025-05-15','Lunes','08:00:00','13:00:00',NULL,NULL,NULL),
(5,'2025-08-02','Viernes','08:00:00','13:00:00',NULL,NULL,NULL),
(6,'2025-08-02','Lunes','08:00:00','13:00:00',NULL,NULL,NULL),
(7,'2025-08-03','Martes','08:00:00','13:00:00',NULL,NULL,NULL);
/*!40000 ALTER TABLE `dia_asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados_asistencia`
--

DROP TABLE IF EXISTS `estados_asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_asistencia` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(20) NOT NULL,
  `abreviatura` varchar(5) NOT NULL,
  `color_hex` varchar(7) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados_asistencia`
--

LOCK TABLES `estados_asistencia` WRITE;
/*!40000 ALTER TABLE `estados_asistencia` DISABLE KEYS */;
INSERT INTO `estados_asistencia` VALUES
(1,'Presente','P','#28a745'),
(2,'Tarde','T','#ffc107'),
(3,'Falta','F','#dc3545'),
(4,'Justificado','J','#17a2b8');
/*!40000 ALTER TABLE `estados_asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiante` (
  `estudiante_id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(11) DEFAULT NULL,
  `nombres` varchar(105) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `dni` char(8) DEFAULT NULL,
  `grado_id` int(11) DEFAULT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`estudiante_id`),
  KEY `fk_estudiante_grado` (`grado_id`),
  KEY `fk_estudiante_seccion` (`seccion_id`),
  CONSTRAINT `fk_estudiante_grado` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id_grado`),
  CONSTRAINT `fk_estudiante_seccion` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id_seccion`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiante`
--

LOCK TABLES `estudiante` WRITE;
/*!40000 ALTER TABLE `estudiante` DISABLE KEYS */;
INSERT INTO `estudiante` VALUES
(1,'STU001','Ana','Martínez López','12345678',1,1,'2025-05-29 09:52:00'),
(2,'STU002','Luis','Ramírez Torres','87654321',1,1,'2025-05-29 09:52:00'),
(3,'STU003','María','González Díaz','11223344',1,1,'2025-05-29 09:52:00'),
(4,'STU004','Carlos','Pérez Rojas','22334455',1,1,'2025-05-29 09:52:00'),
(5,'STU005','Laura','Fernández Ruiz','33445566',1,1,'2025-05-29 09:52:00'),
(6,'SL12345678C','ARTURO','SARAVIA REYES','12312312',1,1,'2025-05-29 09:52:00'),
(7,'SL87654321C','JAVIER MATIAS','VILCAPUMA WARACA','32132132',1,1,'2025-05-29 09:52:00'),
(8,'SL11112222C','JOHANA MILENA','BELLA TINTO','98767285',1,1,'2025-05-29 09:52:00'),
(9,'SL77778888C','MARIO VERGAS','SUMARI PINO','85473612',1,1,'2025-05-29 09:52:00'),
(10,'sl99887766c','ARTURO','SARAVIA REYES','12312312',1,1,'2025-05-29 09:52:00'),
(11,'sl44556677d','JAVIER MATIAS','VILCAPUMA WARACA','32132132',1,1,'2025-05-29 09:52:00'),
(12,'sl11223344b','JOHANA MILENA','BELLA TINTO','98767285',1,1,'2025-05-29 09:52:00'),
(13,NULL,'MARIO VERGAS','SUMARI PINO','85473612',1,1,'2025-05-29 09:52:00'),
(14,NULL,'ARTURO','SARAVIA REYES','12312312',1,1,'2025-05-29 09:52:00'),
(15,NULL,'JAVIER MATIAS','VILCAPUMA WARACA','32132132',1,1,'2025-05-29 09:52:00'),
(16,NULL,'JOHANA MILENA','BELLA TINTO','98767285',1,1,'2025-05-29 09:52:00'),
(17,NULL,'MARIO VERGAS','SUMARI PINO','85473612',1,1,'2025-05-29 09:52:00'),
(18,NULL,'AWDAW','ASD D','12354231',1,1,'2025-05-29 09:52:00'),
(19,NULL,'DWFG','DWFG','62524362',1,1,'2025-05-29 09:52:00'),
(20,NULL,'GERGERG','DWFEG  E','63457232',1,1,'2025-05-29 09:52:00'),
(21,NULL,'AWDAW','ASD D','12354231',1,1,'2025-05-29 09:52:00'),
(22,NULL,'DWFG','DWFG','62524362',1,1,'2025-05-29 09:52:00'),
(23,NULL,'GERGERG','DWFEG  E','63457232',1,2,'2025-05-29 09:52:00');
/*!40000 ALTER TABLE `estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grados`
--

DROP TABLE IF EXISTS `grados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grados` (
  `id_grado` int(11) NOT NULL AUTO_INCREMENT,
  `orden_num` varchar(5) NOT NULL,
  `nombre_completo` varchar(20) NOT NULL,
  PRIMARY KEY (`id_grado`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grados`
--

LOCK TABLES `grados` WRITE;
/*!40000 ALTER TABLE `grados` DISABLE KEYS */;
INSERT INTO `grados` VALUES
(1,'1°','PRIMERO'),
(2,'2°','SEGUNDO'),
(3,'3°','TERCERO'),
(4,'4°','CUARTO'),
(5,'5°','QUINTO');
/*!40000 ALTER TABLE `grados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secciones`
--

DROP TABLE IF EXISTS `secciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `secciones` (
  `id_seccion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_seccion` varchar(10) NOT NULL,
  PRIMARY KEY (`id_seccion`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secciones`
--

LOCK TABLES `secciones` WRITE;
/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
INSERT INTO `secciones` VALUES
(1,'A'),
(2,'B'),
(3,'C'),
(4,'D'),
(5,'E');
/*!40000 ALTER TABLE `secciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_config`
--

DROP TABLE IF EXISTS `system_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_config` (
  `id` int(11) NOT NULL,
  `academic_year` varchar(9) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `punctual_time` time NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_config`
--

LOCK TABLES `system_config` WRITE;
/*!40000 ALTER TABLE `system_config` DISABLE KEYS */;
INSERT INTO `system_config` VALUES
(1,'2025','2025-03-01','2025-12-20','08:15:00','2025-06-10 19:46:09');
/*!40000 ALTER TABLE `system_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vista_estudiantes`
--

DROP TABLE IF EXISTS `vista_estudiantes`;
/*!50001 DROP VIEW IF EXISTS `vista_estudiantes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vista_estudiantes` AS SELECT
 1 AS `estudiante_id`,
  1 AS `codigo`,
  1 AS `nombres`,
  1 AS `apellidos`,
  1 AS `dni`,
  1 AS `id_grado`,
  1 AS `grado`,
  1 AS `grado_nombre`,
  1 AS `id_seccion`,
  1 AS `seccion`,
  1 AS `date_created` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'db_asistencia_estudiantes'
--

--
-- Final view structure for view `vista_estudiantes`
--

/*!50001 DROP VIEW IF EXISTS `vista_estudiantes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_estudiantes` AS select `e`.`estudiante_id` AS `estudiante_id`,`e`.`codigo` AS `codigo`,`e`.`nombres` AS `nombres`,`e`.`apellidos` AS `apellidos`,`e`.`dni` AS `dni`,`g`.`id_grado` AS `id_grado`,`g`.`orden_num` AS `grado`,`g`.`nombre_completo` AS `grado_nombre`,`s`.`id_seccion` AS `id_seccion`,`s`.`nombre_seccion` AS `seccion`,`e`.`date_created` AS `date_created` from ((`estudiante` `e` left join `grados` `g` on(`e`.`grado_id` = `g`.`id_grado`)) left join `secciones` `s` on(`e`.`seccion_id` = `s`.`id_seccion`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-06-24  1:41:18
