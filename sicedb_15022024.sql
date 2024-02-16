-- MySQL dump 10.13  Distrib 8.2.0, for macos13.5 (arm64)
--
-- Host: localhost    Database: sicedb
-- ------------------------------------------------------
-- Server version	8.2.0

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
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `areas_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,'Artes Manuales y Labores','2024-02-16 05:50:54','2024-02-16 05:50:54'),(2,'Corte y Confección','2024-02-16 05:51:08','2024-02-16 05:51:08');
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendarios`
--

DROP TABLE IF EXISTS `calendarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fec_ini` date NOT NULL,
  `fec_fin` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendarios_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendarios`
--

LOCK TABLES `calendarios` WRITE;
/*!40000 ALTER TABLE `calendarios` DISABLE KEYS */;
INSERT INTO `calendarios` VALUES (1,'1-2024','2024-02-01','2024-02-29','2024-02-16 05:57:48','2024-02-16 05:57:48');
/*!40000 ALTER TABLE `calendarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenios`
--

DROP TABLE IF EXISTS `convenios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `convenios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calendario_id` bigint unsigned NOT NULL,
  `fec_ini` date NOT NULL,
  `fec_fin` date NOT NULL,
  `descuento` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `convenios_calendario_id_nombre_unique` (`calendario_id`,`nombre`),
  CONSTRAINT `convenios_calendario_id_foreign` FOREIGN KEY (`calendario_id`) REFERENCES `calendarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenios`
--

LOCK TABLES `convenios` WRITE;
/*!40000 ALTER TABLE `convenios` DISABLE KEYS */;
INSERT INTO `convenios` VALUES (1,'Sura',1,'2024-02-01','2024-02-29',10,'2024-02-16 06:03:39','2024-02-16 06:03:39');
/*!40000 ALTER TABLE `convenios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso_calendario`
--

DROP TABLE IF EXISTS `curso_calendario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `curso_calendario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `calendario_id` bigint unsigned NOT NULL,
  `curso_id` bigint unsigned NOT NULL,
  `modalidad` enum('Presencial','Virtual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Presencial',
  `costo` double(8,2) NOT NULL DEFAULT '0.00',
  `cupo` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `curso_calendario_index` (`calendario_id`,`curso_id`,`modalidad`),
  KEY `curso_calendario_curso_id_foreign` (`curso_id`),
  CONSTRAINT `curso_calendario_calendario_id_foreign` FOREIGN KEY (`calendario_id`) REFERENCES `calendarios` (`id`),
  CONSTRAINT `curso_calendario_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso_calendario`
--

LOCK TABLES `curso_calendario` WRITE;
/*!40000 ALTER TABLE `curso_calendario` DISABLE KEYS */;
INSERT INTO `curso_calendario` VALUES (1,1,3,'Presencial',290000.00,0,'2024-02-16 05:58:28','2024-02-16 05:58:28'),(2,1,4,'Presencial',290000.00,0,'2024-02-16 05:58:41','2024-02-16 05:58:41'),(3,1,1,'Presencial',329000.00,0,'2024-02-16 05:59:18','2024-02-16 05:59:18'),(4,1,2,'Presencial',329000.00,0,'2024-02-16 05:59:32','2024-02-16 05:59:32');
/*!40000 ALTER TABLE `curso_calendario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_id` bigint unsigned NOT NULL,
  `tipo_curso` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cursos_nombre_area_id_unique` (`nombre`,`area_id`),
  KEY `cursos_area_id_foreign` (`area_id`),
  CONSTRAINT `cursos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,'Clínica del vestido',2,'Regular','2024-02-16 05:51:26','2024-02-16 05:51:26'),(2,'Sastrería Masculina',2,'Regular','2024-02-16 05:51:40','2024-02-16 05:51:40'),(3,'Cajas decorativas y empaques',1,'Regular','2024-02-16 05:52:14','2024-02-16 05:52:14'),(4,'Decoración en madera',1,'Regular','2024-02-16 05:52:30','2024-02-16 05:52:30'),(5,'Corte y confección de Blusas',2,'Regular','2024-02-16 06:00:33','2024-02-16 06:00:33');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dias_festivos`
--

DROP TABLE IF EXISTS `dias_festivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dias_festivos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anio` int NOT NULL,
  `fechas` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dias_festivos_anio_unique` (`anio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dias_festivos`
--

LOCK TABLES `dias_festivos` WRITE;
/*!40000 ALTER TABLE `dias_festivos` DISABLE KEYS */;
INSERT INTO `dias_festivos` VALUES (1,2024,'2024-01-01,2024-01-08,2024-03-25,2024-03-28,2024-03-29,2024-05-01,2024-05-13,2024-06-03,2024-06-10,2024-07-01,2024-07-20,2024-08-07,2024-08-19,2024-10-14,2024-11-04,2024-11-11,2024-12-08,2024-12-25','2024-02-16 05:49:22','2024-02-16 05:49:22');
/*!40000 ALTER TABLE `dias_festivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formulario_inscripcion`
--

DROP TABLE IF EXISTS `formulario_inscripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formulario_inscripcion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grupo_id` bigint unsigned NOT NULL,
  `participante_id` bigint unsigned NOT NULL,
  `convenio_id` bigint unsigned DEFAULT NULL,
  `voucher` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_formulario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('Pendiente de pago','Pagado','Anulado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente de pago',
  `costo_curso` decimal(8,2) NOT NULL DEFAULT '0.00',
  `valor_descuento` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_a_pagar` decimal(8,2) NOT NULL DEFAULT '0.00',
  `medio_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pagoBanco',
  `fecha_max_legalizacion` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formulario_inscripcion_voucher_unique` (`voucher`),
  UNIQUE KEY `formulario_inscripcion_numero_formulario_unique` (`numero_formulario`),
  KEY `formulario_inscripcion_grupo_id_foreign` (`grupo_id`),
  KEY `formulario_inscripcion_participante_id_foreign` (`participante_id`),
  KEY `formulario_inscripcion_convenio_id_foreign` (`convenio_id`),
  CONSTRAINT `formulario_inscripcion_convenio_id_foreign` FOREIGN KEY (`convenio_id`) REFERENCES `convenios` (`id`),
  CONSTRAINT `formulario_inscripcion_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  CONSTRAINT `formulario_inscripcion_participante_id_foreign` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formulario_inscripcion`
--

LOCK TABLES `formulario_inscripcion` WRITE;
/*!40000 ALTER TABLE `formulario_inscripcion` DISABLE KEYS */;
/*!40000 ALTER TABLE `formulario_inscripcion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `salon_id` bigint unsigned NOT NULL,
  `orientador_id` bigint unsigned NOT NULL,
  `dia` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Lunes',
  `jornada` enum('Mañana','Tarde','Noche') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mañana',
  `curso_calendario_id` bigint unsigned NOT NULL,
  `calendario_id` bigint unsigned NOT NULL,
  `cupos` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupos_index_unique` (`curso_calendario_id`,`calendario_id`,`salon_id`,`orientador_id`,`dia`,`jornada`),
  UNIQUE KEY `orientador_ocupado_index_unique` (`calendario_id`,`orientador_id`,`dia`,`jornada`),
  KEY `grupos_salon_id_foreign` (`salon_id`),
  KEY `grupos_orientador_id_foreign` (`orientador_id`),
  CONSTRAINT `grupos_curso_calendario_id_foreign` FOREIGN KEY (`curso_calendario_id`) REFERENCES `curso_calendario` (`id`),
  CONSTRAINT `grupos_orientador_id_foreign` FOREIGN KEY (`orientador_id`) REFERENCES `orientadores` (`id`),
  CONSTRAINT `grupos_salon_id_foreign` FOREIGN KEY (`salon_id`) REFERENCES `salones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (1,1,3,'Lunes','Tarde',3,1,10,'2024-02-16 06:01:23','2024-02-16 06:01:23'),(2,1,2,'Viernes','Mañana',4,1,10,'2024-02-16 06:01:53','2024-02-16 06:01:53'),(3,3,1,'Miércoles','Mañana',1,1,10,'2024-02-16 06:02:30','2024-02-16 06:02:30'),(4,4,1,'Miércoles','Tarde',2,1,10,'2024-02-16 06:03:10','2024-02-16 06:03:10');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2023_09_22_020202_create_areas_table',1),(6,'2023_09_27_221345_create_cursos_table',1),(7,'2023_09_28_020314_create_salones_table',1),(8,'2023_09_28_040839_create_orientadores_table',1),(9,'2023_09_28_041216_create_orientador_areas_table',1),(10,'2023_09_30_221016_create_calendarios_table',1),(11,'2023_10_01_200253_create_grupos_table',1),(12,'2023_10_17_155920_create_tipo_salones_table',1),(13,'2023_10_17_175854_add_tipo_salon_id_to_salones',1),(14,'2023_10_18_115411_add_fec_nac_nivel_educativo_to_colaboradores',1),(15,'2023_10_19_101200_create_curso_calendario_table',1),(16,'2023_10_24_073946_add_tipo_curso_to_cursos',1),(17,'2023_10_24_081430_add_rango_salarial_to_orientadores',1),(18,'2023_10_27_115039_add_curso_calendario_id_to_grupos',1),(19,'2023_10_27_172507_create_convenios_table',1),(20,'2023_10_30_084522_create_participantes_table',1),(21,'2023_10_30_090949_create_formulario_inscripcion_table',1),(22,'2023_11_01_015423_add_campos_contacto_emergencia_to_participantes',1),(23,'2023_11_08_151036_add_index_to_orientador_areas',1),(24,'2023_11_11_104606_add_cupo_to_grupos',1),(25,'2023_11_11_152248_add_estado_to_formulario_inscripcion',1),(26,'2023_11_15_023143_add_medio_de_pago_to_formulario_inscripcion',1),(27,'2023_11_30_020533_create_dias_festivos_table',1),(28,'2023_11_30_033527_add_fecha_max_legalizacion_to_formulario_inscripcion',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orientador_areas`
--

DROP TABLE IF EXISTS `orientador_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orientador_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `orientador_id` bigint unsigned NOT NULL,
  `area_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orientador_area` (`orientador_id`,`area_id`),
  KEY `orientador_areas_area_id_foreign` (`area_id`),
  CONSTRAINT `orientador_areas_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `orientador_areas_orientador_id_foreign` FOREIGN KEY (`orientador_id`) REFERENCES `orientadores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orientador_areas`
--

LOCK TABLES `orientador_areas` WRITE;
/*!40000 ALTER TABLE `orientador_areas` DISABLE KEYS */;
INSERT INTO `orientador_areas` VALUES (1,1,1,NULL,NULL),(2,1,2,NULL,NULL),(3,2,2,NULL,NULL),(4,3,2,NULL,NULL);
/*!40000 ALTER TABLE `orientador_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orientadores`
--

DROP TABLE IF EXISTS `orientadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orientadores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('CC','TI','CE','PP') COLLATE utf8mb4_unicode_ci NOT NULL,
  `documento` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_institucional` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_personal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eps` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `fec_nacimiento` date DEFAULT NULL,
  `nivel_estudio` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rango_salarial` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orientadores_tipo_documento_documento_unique` (`tipo_documento`,`documento`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orientadores`
--

LOCK TABLES `orientadores` WRITE;
/*!40000 ALTER TABLE `orientadores` DISABLE KEYS */;
INSERT INTO `orientadores` VALUES (1,'Lilian Bedud Riojas Gonzales','CC','10101010','','lilian.riojas@hotmail.com','Calle 87 # 88 - 66','EPS SANITAS',1,'','1986-09-05','Profesional','Profesional','2024-02-16 05:55:12','2024-02-16 05:55:12'),(2,'José Luis Gonzalez Mejía','CC','10101020','','jolgonme@gmail.com','Carrera 7 # 28 - 66','SALUD TOTAL EPS S.A.',1,'','1985-08-28','Especialización','Profesional','2024-02-16 05:56:16','2024-02-16 05:56:16'),(3,'Bienvenido De Ávila Chamorro','CC','10101030','','bienvenido.avila@gmail.com','Diagonal 80B # 12 - 23','FAMISANAR',1,'','1965-04-14','Técnico','Técnico Profesional','2024-02-16 05:57:34','2024-02-16 05:57:34');
/*!40000 ALTER TABLE `orientadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participantes`
--

DROP TABLE IF EXISTS `participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `primer_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `tipo_documento` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documento` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_expedicion` date DEFAULT NULL,
  `sexo` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'M',
  `estado_civil` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eps` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contacto_emergencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_emergencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participantes_tipo_documento_documento_unique` (`tipo_documento`,`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participantes`
--

LOCK TABLES `participantes` WRITE;
/*!40000 ALTER TABLE `participantes` DISABLE KEYS */;
/*!40000 ALTER TABLE `participantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salones`
--

DROP TABLE IF EXISTS `salones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacidad` int NOT NULL DEFAULT '0',
  `esta_disponible` tinyint(1) NOT NULL DEFAULT '1',
  `hoja_vida` text COLLATE utf8mb4_unicode_ci,
  `tipo_salon_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salones_nombre_unique` (`nombre`),
  KEY `salones_tipo_salon_id_foreign` (`tipo_salon_id`),
  CONSTRAINT `salones_tipo_salon_id_foreign` FOREIGN KEY (`tipo_salon_id`) REFERENCES `tipo_salones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salones`
--

LOCK TABLES `salones` WRITE;
/*!40000 ALTER TABLE `salones` DISABLE KEYS */;
INSERT INTO `salones` VALUES (1,'100',10,1,NULL,1,'2024-02-16 05:53:34','2024-02-16 05:53:34'),(2,'110',10,1,NULL,1,'2024-02-16 05:53:43','2024-02-16 05:53:43'),(3,'200',10,1,NULL,2,'2024-02-16 05:54:08','2024-02-16 05:54:08'),(4,'210',10,1,NULL,2,'2024-02-16 05:54:17','2024-02-16 05:54:17');
/*!40000 ALTER TABLE `salones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_salones`
--

DROP TABLE IF EXISTS `tipo_salones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_salones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipo_salones_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_salones`
--

LOCK TABLES `tipo_salones` WRITE;
/*!40000 ALTER TABLE `tipo_salones` DISABLE KEYS */;
INSERT INTO `tipo_salones` VALUES (1,'Corte y confección','2024-02-16 05:52:57','2024-02-16 05:53:21'),(2,'Manuales y labores','2024-02-16 05:53:14','2024-02-16 05:53:14');
/*!40000 ALTER TABLE `tipo_salones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-15 20:05:28
