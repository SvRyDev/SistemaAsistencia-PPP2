-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: db_asistencia_estudiantes
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asistencia_estudiante`
--

DROP TABLE IF EXISTS `asistencia_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia_estudiante` (
  `asistencia_estudiante_id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `dia_fecha_id` int(11) DEFAULT NULL,
  `hora_entrada` datetime DEFAULT NULL,
  `hora_salida` datetime DEFAULT NULL,
  `condicion` varchar(20) DEFAULT NULL,
  `observacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`asistencia_estudiante_id`),
  KEY `estudiante_id` (`estudiante_id`),
  KEY `dia_fecha_id` (`dia_fecha_id`),
  CONSTRAINT `asistencia_estudiante_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`estudiante_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistencia_estudiante_ibfk_2` FOREIGN KEY (`dia_fecha_id`) REFERENCES `dia_asistencia` (`dia_fecha_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia_estudiante`
--

LOCK TABLES `asistencia_estudiante` WRITE;
/*!40000 ALTER TABLE `asistencia_estudiante` DISABLE KEYS */;
INSERT INTO `asistencia_estudiante` VALUES (1,1,1,'2025-05-29 08:01:00','2025-05-29 13:00:00','Presente',''),(2,2,1,'2025-05-29 08:10:00','2025-05-29 13:05:00','Tarde','Llegó 10 min tarde'),(3,3,1,NULL,NULL,'Ausente','Sin registrar'),(4,4,2,'2025-05-28 08:00:00','2025-05-28 13:00:00','Presente',''),(5,5,3,'2025-05-27 07:58:00','2025-05-27 12:55:00','Presente','');
/*!40000 ALTER TABLE `asistencia_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carnet_estudiante`
--

DROP TABLE IF EXISTS `carnet_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `carnet_estudiante` VALUES (1,1,'fotos/ana.jpg','barcodes/ana.png'),(2,2,'fotos/luis.jpg','barcodes/luis.png'),(3,3,'fotos/maria.jpg','barcodes/maria.png'),(4,4,'fotos/carlos.jpg','barcodes/carlos.png'),(5,5,'fotos/laura.jpg','barcodes/laura.png');
/*!40000 ALTER TABLE `carnet_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dia_asistencia`
--

DROP TABLE IF EXISTS `dia_asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dia_asistencia`
--

LOCK TABLES `dia_asistencia` WRITE;
/*!40000 ALTER TABLE `dia_asistencia` DISABLE KEYS */;
INSERT INTO `dia_asistencia` VALUES (1,'2025-05-29','Jueves','08:00:00','13:00:00',NULL,NULL,NULL),(2,'2025-05-28','Miércoles','08:00:00','13:00:00',NULL,NULL,NULL),(3,'2025-05-27','Martes','08:00:00','13:00:00',NULL,NULL,NULL),(4,'2025-05-26','Lunes','08:00:00','13:00:00',NULL,NULL,NULL),(5,'2025-05-23','Viernes','08:00:00','13:00:00',NULL,NULL,NULL);
/*!40000 ALTER TABLE `dia_asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiante` (
  `estudiante_id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(11) DEFAULT NULL,
  `nombres` varchar(105) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `dni` char(8) DEFAULT NULL,
  `grado` varchar(50) DEFAULT NULL,
  `seccion` varchar(50) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`estudiante_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiante`
--

LOCK TABLES `estudiante` WRITE;
/*!40000 ALTER TABLE `estudiante` DISABLE KEYS */;
INSERT INTO `estudiante` VALUES (1,'STU001','Ana','Martínez López','12345678','5°','A','2025-05-29 09:52:00'),(2,'STU002','Luis','Ramírez Torres','87654321','5°','B','2025-05-29 09:52:00'),(3,'STU003','María','González Díaz','11223344','6°','A','2025-05-29 09:52:00'),(4,'STU004','Carlos','Pérez Rojas','22334455','6°','B','2025-05-29 09:52:00'),(5,'STU005','Laura','Fernández Ruiz','33445566','5°','A','2025-05-29 09:52:00'),(6,'SL12345678C','ARTURO','SARAVIA REYES','12312312','PRIMERO','A','0000-00-00 00:00:00'),(7,'SL87654321C','JAVIER MATIAS','VILCAPUMA WARACA','32132132','SEGUNDO','B','0000-00-00 00:00:00'),(8,'SL11112222C','JOHANA MILENA','BELLA TINTO','98767285','TERCERO','A','0000-00-00 00:00:00'),(9,'SL77778888C','MARIO VERGAS','SUMARI PINO','85473612','CUARTO','C','0000-00-00 00:00:00'),(10,'sl99887766c','ARTURO','SARAVIA REYES','12312312','PRIMERO','A','0000-00-00 00:00:00'),(11,'sl44556677d','JAVIER MATIAS','VILCAPUMA WARACA','32132132','SEGUNDO','B','0000-00-00 00:00:00'),(12,'sl11223344b','JOHANA MILENA','BELLA TINTO','98767285','TERCERO','A','0000-00-00 00:00:00'),(13,NULL,'MARIO VERGAS','SUMARI PINO','85473612','CUARTO','C','0000-00-00 00:00:00'),(14,NULL,'ARTURO','SARAVIA REYES','12312312','PRIMERO','A','0000-00-00 00:00:00'),(15,NULL,'JAVIER MATIAS','VILCAPUMA WARACA','32132132','SEGUNDO','B','0000-00-00 00:00:00'),(16,NULL,'JOHANA MILENA','BELLA TINTO','98767285','TERCERO','A','0000-00-00 00:00:00'),(17,NULL,'MARIO VERGAS','SUMARI PINO','85473612','CUARTO','C','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_config`
--

DROP TABLE IF EXISTS `system_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `system_config` VALUES (1,'2025','2025-03-01','2025-12-20','08:15:00','2025-06-10 19:46:09');
/*!40000 ALTER TABLE `system_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_asistencia_estudiantes'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-10 21:36:52
