-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: db_pagina_web
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria_comunicados`
--

DROP TABLE IF EXISTS `categoria_comunicados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_comunicados` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(45) NOT NULL,
  `color_etiqueta` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_comunicados`
--

LOCK TABLES `categoria_comunicados` WRITE;
/*!40000 ALTER TABLE `categoria_comunicados` DISABLE KEYS */;
INSERT INTO `categoria_comunicados` VALUES (1,'Informativo',NULL),(2,'Urgente',NULL),(3,'Eventual',NULL);
/*!40000 ALTER TABLE `categoria_comunicados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias_documentos`
--

DROP TABLE IF EXISTS `categorias_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias_documentos` (
  `id_categoria_doc` int NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(45) NOT NULL,
  PRIMARY KEY (`id_categoria_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias_documentos`
--

LOCK TABLES `categorias_documentos` WRITE;
/*!40000 ALTER TABLE `categorias_documentos` DISABLE KEYS */;
INSERT INTO `categorias_documentos` VALUES (1,'Pre-inscripcion'),(2,'Inscripcion');
/*!40000 ALTER TABLE `categorias_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias_eventos`
--

DROP TABLE IF EXISTS `categorias_eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias_eventos` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(45) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias_eventos`
--

LOCK TABLES `categorias_eventos` WRITE;
/*!40000 ALTER TABLE `categorias_eventos` DISABLE KEYS */;
INSERT INTO `categorias_eventos` VALUES (1,'Cultural'),(2,'Academica'),(3,'Comunidad'),(4,'Deportiva'),(5,'Administrativo');
/*!40000 ALTER TABLE `categorias_eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunicados`
--

DROP TABLE IF EXISTS `comunicados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comunicados` (
  `id_comunicado` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL,
  `id_usuario_creador` int NOT NULL,
  `id_seccion` int NOT NULL,
  `id_categoria` int NOT NULL,
  PRIMARY KEY (`id_comunicado`),
  KEY `fk_comunicados_usuarios_idx` (`id_usuario_creador`),
  KEY `fk_comunicados_secciones_paginas1_idx` (`id_seccion`),
  KEY `fk_comunicados_categoria_comunicados1_idx` (`id_categoria`),
  CONSTRAINT `fk_comunicados_categoria_comunicados1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_comunicados` (`id_categoria`),
  CONSTRAINT `fk_comunicados_secciones_paginas1` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_paginas` (`id_seccion`),
  CONSTRAINT `fk_comunicados_usuarios` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunicados`
--

LOCK TABLES `comunicados` WRITE;
/*!40000 ALTER TABLE `comunicados` DISABLE KEYS */;
INSERT INTO `comunicados` VALUES (8,'Pierde el miedo y haz que tu voz se escuche!','En la U.E.P. José Diego María Rivera, creemos que el liderazgo comienza con la confianza de expresar tus ideas. Por eso, abrimos las inscripciones para nuestro Curso de Oratoria y Liderazgo.\r\n¿Qué aprenderán nuestros alumnos?\r\n✅ Técnicas profesionales para hablar en público.\r\n✅ Herramientas de liderazgo efectivo.\r\n✅ Cómo dominar el miedo escénico.\r\n✅ Dicción y proyección de la voz.\r\n✅ ¡Muchos ejercicios prácticos y divertidos!\r\n¡Es el momento de fortalecer su crecimiento y seguridad! ?\r\n? Ubicación: Urb. El Caujaro, Lote IJ, Av. 49 G-01 / San Francisco.\r\n? Más información al WhatsApp: 0412-6486575','2026-04-08 00:30:11','2026-05-16','uploads/comunicados/1775622611_pierde el miedo.jpeg',1,3,3),(12,'Talleres y Cursos ','¡El momento de fortalecer tus conocimientos es ahora! ?\r\n\r\n​En la U.E.P. José Diego María Rivera, abrimos nuestras puertas para que desarrolles nuevas habilidades con nuestros Talleres de Formación Académica y Cursos Especializados.\r\n​? ¿Qué ofrecemos?\r\n\r\n​Refuerzo Académico: Matemática, Física, Química y Redacción de Informes.\r\n​Cursos de Crecimiento: Oratoria y Liderazgo, Inglés (Kids y Teens) y Automaquillaje.\r\n​✅ ¡Inscripciones abiertas! No dejes pasar la oportunidad de potenciar tu futuro.\r\n​? Contáctanos: 0412-6486575\r\n? Ubicación: Urb. El Caujaro, Lote 1J, Av. 49 G-01, San Francisco.','2026-04-18 19:46:43','2026-01-21','uploads/comunicados/1776556003_documento 2.jpeg',1,3,1),(13,'Abrimos Inscripciones','Tenemos buenas noticias! ✨\r\n\r\nPara el nuevo año escolar 2025-2026, abrimos inscripciones para los consentidos de nuestra casa de estudio. ¡Contáctanos ya! ✍?*\r\n\r\n✅ Grupos reducidos.\r\n✅ Atención personalizada.\r\n✅ Salas individuales: 3 años, 4 años y 5 años.\r\n✅ Clases de Danza, Karate, Música, Dibujo y Arte.\r\n\r\nTurnos disponibles:\r\n☀️ Mañana\r\n?️ Tarde\r\n¡Educar en valores es nuestro Compromiso! ?\r\n? 0412-6486575\r\n? Urb. El Caujaro, Lote 1J, Av. 49 G-01 / San Francisco.','2026-04-18 19:48:39','2026-02-25','uploads/comunicados/1776556119_imagen 3.jpeg',1,3,1),(14,'Ciencia y Tecnologia','En la U.E.P. José Diego María Rivera, nos enorgullece presentar nuestra mención en Educación Media General: Ciencias y Tecnología. Un programa diseñado para que nuestros estudiantes no solo aprendan, sino que transformen el mundo a través del conocimiento técnico y científico.\r\n\r\n​? Certifícate como Bachiller en Ciencias y Tecnología. Brindamos las herramientas necesarias para un camino universitario exitoso en las carreras más demandadas del futuro.\r\n​✨ ¡Tu éxito comienza aquí!\r\n​? Información al: 0412-6486575\r\n? Ubicación: Urb. El Caujaro, San Francisco.','2026-04-18 19:49:47','2026-04-01','uploads/comunicados/1776556187_imagen 5.jpeg',1,3,1);
/*!40000 ALTER TABLE `comunicados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos_descargables`
--

DROP TABLE IF EXISTS `documentos_descargables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentos_descargables` (
  `id_documentos` int NOT NULL AUTO_INCREMENT,
  `titulo_documento` varchar(100) NOT NULL,
  `url_archivo` varchar(255) NOT NULL,
  `fecha_subida` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` text,
  `id_categoria_doc` int NOT NULL,
  `id_seccion` int NOT NULL,
  `id_usuario_creador` int NOT NULL,
  PRIMARY KEY (`id_documentos`),
  KEY `fk_Documentos_Descargables_categorias_documentos1_idx` (`id_categoria_doc`),
  KEY `fk_Documentos_Descargables_secciones_paginas1_idx` (`id_seccion`),
  KEY `fk_Documentos_Descargables_usuarios1_idx` (`id_usuario_creador`),
  CONSTRAINT `fk_Documentos_Descargables_categorias_documentos1` FOREIGN KEY (`id_categoria_doc`) REFERENCES `categorias_documentos` (`id_categoria_doc`),
  CONSTRAINT `fk_Documentos_Descargables_secciones_paginas1` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_paginas` (`id_seccion`),
  CONSTRAINT `fk_Documentos_Descargables_usuarios1` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos_descargables`
--

LOCK TABLES `documentos_descargables` WRITE;
/*!40000 ALTER TABLE `documentos_descargables` DISABLE KEYS */;
INSERT INTO `documentos_descargables` VALUES (2,'FICHA DE INSCRIPCIÓN MEDIA GENERAL','archivos_docs/1776719687_MEDIA GENERAL FICHAS DE INSCRIPCION U.E.P. DIEGO RIVERA.pdf','2026-04-20 00:00:00','Planilla de inscripción para los estudiantes de media general. ',2,5,1),(3,'FICHA DE INSCRIPCIÓN PRIMARIA','archivos_docs/1776719761_PRIMARIA FICHA DE INSCRIPCION U.E.P. DIEGO RIVERA.pdf','2026-04-20 00:00:00','Planilla de inscripción de los estudiantes de primaria',1,5,1);
/*!40000 ALTER TABLE `documentos_descargables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_evento`
--

DROP TABLE IF EXISTS `estado_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_evento` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(45) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_evento`
--

LOCK TABLES `estado_evento` WRITE;
/*!40000 ALTER TABLE `estado_evento` DISABLE KEYS */;
INSERT INTO `estado_evento` VALUES (1,'Programado'),(2,'Cancelado'),(3,'Pospuesto'),(4,'Finalizado');
/*!40000 ALTER TABLE `estado_evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eventos` (
  `id_evento` int NOT NULL AUTO_INCREMENT,
  `titulo_evento` varchar(45) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date NOT NULL,
  `descripcion_evento` text NOT NULL,
  `id_estado` int NOT NULL,
  `id_usuario_creador` int NOT NULL,
  `id_ubicacion` int NOT NULL,
  `id_categoria` int NOT NULL,
  `id_seccion` int NOT NULL,
  PRIMARY KEY (`id_evento`),
  KEY `fk_eventos_categorias_eventos1_idx` (`id_categoria`),
  KEY `fk_eventos_estado_evento1_idx` (`id_estado`),
  KEY `fk_eventos_usuarios1_idx` (`id_usuario_creador`),
  KEY `fk_eventos_ubicacion_eventos1_idx` (`id_ubicacion`),
  KEY `fk_eventos_secciones_paginas1_idx` (`id_seccion`),
  CONSTRAINT `fk_eventos_categorias_eventos1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_eventos` (`id_categoria`),
  CONSTRAINT `fk_eventos_estado_evento1` FOREIGN KEY (`id_estado`) REFERENCES `estado_evento` (`id_estado`),
  CONSTRAINT `fk_eventos_secciones_paginas1` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_paginas` (`id_seccion`),
  CONSTRAINT `fk_eventos_ubicacion_eventos1` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicacion_eventos` (`id_ubicacion`),
  CONSTRAINT `fk_eventos_usuarios1` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (4,'Dí­a de la Zulianidad','2026-01-30','2026-01-30','El pasado 30 de enero, los pasillos de nuestra institución se vistieron de gala y tradición para conmemorar el Día de la Zulianidad. En una jornada cargada de orgullo y color, estudiantes y docentes nos unimos para rendir homenaje a la herencia cultural, histórica y artística que define al pueblo zuliano.   \r\n\r\n\r\n\"Ser zuliano no es solo haber nacido en esta tierra, es llevar el calor del sol en el pecho y la alegría de la gaita en la voz.\"    \r\n\r\n\r\nAgradecemos a todos los participantes por su creatividad y esfuerzo en las carteleras, maquetas y muestras culturales que hicieron de este día un evento inolvidable.',3,1,2,1,4),(5,'Dia de las Madres','2025-05-10','2025-05-10','En el Liceo José Diego María Rivera, las aulas se llenaron de una luz especial para rendir tributo a quienes son el pilar fundamental de nuestros hogares y el primer ejemplo de nuestros estudiantes: las madres. Con un emotivo acto cultural, celebramos la vida, la entrega y la sabiduría de esas mujeres que día a día impulsan el futuro de nuestra nación.\r\n\r\nEntendemos que detrás de cada buen estudiante, hay una madre que motiva, que guía y que cree en sus sueños. Celebrarlas no es solo una tradición, es un acto de justicia para quienes educan con el amor como bandera.\r\n\r\n\r\n\"El brazo de una madre es la mayor zona de seguridad para un hijo y su voz, el mejor consejo.\"\r\n\r\n\r\nDesde la dirección y el cuerpo docente, extendemos nuestro más sincero respeto y admiración a todas las madres de la familia Riverista. Gracias por confiar en nosotros para la formación de sus hijos y por ser su motor incansable.',4,1,2,3,4),(6,'RETO DE LAS CIENCIAS','2026-03-16','2026-04-10','¡El futuro de la ciencia se construye hoy en nuestras aulas! ✨?️\r\n\r\nEn la U.E.P José Diego María Rivera, creemos en el poder de la investigación y la creatividad de nuestros jóvenes. Por eso, te invitamos formalmente a presenciar la Fase Institucional del VII Reto Estudiantil de las Ciencias.\r\n\r\nSerá un día lleno de descubrimientos, donde los estudiantes compartirán sus ideas y soluciones innovadoras. ¡Tu presencia es el mejor incentivo para ellos!',2,1,2,2,4),(7,'YA NOS GRADUAMOS','2025-05-25','2025-05-06','Hay instantes que merecen ser eternos, y la Promo XVIII del Liceo José Diego María Rivera ha comenzado a inmortalizar los suyos. En una jornada llena de risas, abrazos y ese sentimiento agridulce que da la despedida, se llevó a cabo la sesión fotográfica oficial de nuestra próxima cohorte de bachilleres.\r\n\r\nEl día 27 de Junio del 2025, ver a nuestros futuros graduandos posar con entusiasmo nos llena de satisfacción. La Promo XVIII no solo deja una huella en los registros del liceo, sino también en el corazón de sus profesores y representantes, quienes han sido testigos de su crecimiento.\r\n\r\n\r\n\"Una fotografía es el botón de pausa de la vida. Hoy pausamos el tiempo para recordar siempre que aquí fuimos felices.\"',4,1,2,2,4),(8,'CURSO DE ORATORIA Y LIDERAZGO','2026-04-16','2026-05-16','El poder de la palabra y la capacidad de guiar con el ejemplo fueron los protagonistas en el reciente Curso de Oratoria y Liderazgo celebrado en nuestra institución. En el Liceo José Diego María Rivera, entendemos que formar líderes integrales va más allá de los libros de texto; se trata de dotar a nuestros jóvenes de las herramientas necesarias para influir positivamente en su entorno.\r\n\r\nEl evento culminó con una emotiva ceremonia de entrega de certificados, donde se reconoció formalmente el esfuerzo, la constancia y la evolución de cada estudiante. Este diploma no es solo un documento, sino el testimonio de una nueva habilidad adquirida que les abrirá puertas en su futuro académico y profesional.\r\n\r\n\r\n\"Hablar bien es una habilidad, pero liderar con la palabra es un propósito.\"\r\n\r\n\r\nFelicitamos a los nuevos certificados por dar este paso hacia la excelencia. En el Liceo José Diego María Rivera, seguimos apostando por una educación que empodere la voz de nuestra juventud.',1,1,2,2,4),(9,'Encuentro y saberes','2025-06-02','2025-07-02','En nuestra casa de estudio la teoría se transformó en práctica durante el reciente Encuentro de Saberes y Haceres. Este evento fue la vitrina perfecta para que los estudiantes de los distintos Grupos de Creación, Recreación y Producción (GCRP) demostraran las habilidades desarrolladas durante el periodo escolar.\r\n\r\nLa actividad no solo fue una exposición de resultados, sino un espacio de intercambio. Estudiantes y docentes compartieron sus experiencias, procesos y aprendizajes, reafirmando que el liceo es un centro de formación para la vida y el trabajo productivo.\r\n\r\n\r\n\"Educar la mente sin educar el corazón no es educación en absoluto, y educar sin manos a la obra es dejar el conocimiento a medias.\"\r\n\r\n\r\nFelicitamos a cada estudiante por su dedicación y a los docentes guías por impulsar estas áreas que permiten descubrir vocaciones y fortalecer la autogestión.',4,1,2,1,4);
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galeria`
--

DROP TABLE IF EXISTS `galeria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galeria` (
  `id_galeria` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `ruta_imagen` varchar(250) NOT NULL,
  `id_seccion` int NOT NULL,
  PRIMARY KEY (`id_galeria`),
  KEY `fk_galeria_seccion_idx` (`id_seccion`),
  CONSTRAINT `fk_galeria_seccion` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_paginas` (`id_seccion`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galeria`
--

LOCK TABLES `galeria` WRITE;
/*!40000 ALTER TABLE `galeria` DISABLE KEYS */;
INSERT INTO `galeria` VALUES (3,'Exposición de las efemérides de noviembre','img_galeria/Exposición de las efemérides de noviembre.jpeg',2),(4,'Regreso a clases 2025-2026','img_galeria/Regreso a clases 2025-2026.jpeg',2),(5,'Elección de la reina Dieguista 30 de enero 2026','img_galeria/Elección de la reina Dieguista 30 de enero 2026.jpeg',2),(6,'Fin de año escolar julio 2025','img_galeria/Fin de año escolar julio 2025.jpeg',2),(7,'Arte en las manos ','img_galeria/arte.jpeg',2),(8,'Aniversario Dieguista','img_galeria/imagen.jpeg',2),(9,'Observación y Experimento de Quimica','img_galeria/WhatsApp Image 2026-04-05 at 3.28.17 PM.jpeg',2),(10,'Nuestros Mejores Promedios','img_galeria/imagen.jpeg',2);
/*!40000 ALTER TABLE `galeria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_logins`
--

DROP TABLE IF EXISTS `historial_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_logins` (
  `id_logins` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_logins`),
  KEY `fk_logins_usuarios_idx` (`id_usuario`),
  CONSTRAINT `fk_logins_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_logins`
--

LOCK TABLES `historial_logins` WRITE;
/*!40000 ALTER TABLE `historial_logins` DISABLE KEYS */;
INSERT INTO `historial_logins` VALUES (1,1,'2026-04-12 14:17:32'),(3,1,'2026-04-12 14:20:53'),(4,1,'2026-04-12 14:45:21'),(9,1,'2026-04-14 18:55:03'),(10,1,'2026-04-15 14:32:04'),(11,1,'2026-04-15 18:13:49'),(12,1,'2026-04-16 17:19:52'),(15,1,'2026-04-16 19:20:00'),(17,1,'2026-04-16 19:21:20'),(20,1,'2026-04-16 20:22:20'),(25,1,'2026-04-16 21:07:26'),(28,1,'2026-04-16 21:34:15'),(29,1,'2026-04-16 22:04:49'),(30,1,'2026-04-16 22:51:28'),(31,1,'2026-04-16 23:03:16'),(32,1,'2026-04-17 07:23:16'),(33,9,'2026-04-17 07:25:34'),(34,9,'2026-04-17 07:27:11'),(35,9,'2026-04-17 07:27:30'),(36,1,'2026-04-17 07:29:06'),(38,1,'2026-04-17 07:30:53'),(39,1,'2026-04-17 07:42:50'),(40,1,'2026-04-17 17:31:00'),(41,9,'2026-04-18 07:32:50'),(42,1,'2026-04-18 07:33:47'),(44,1,'2026-04-18 10:14:20'),(46,1,'2026-04-18 13:50:19'),(47,1,'2026-04-18 16:49:59'),(48,1,'2026-04-18 17:35:09'),(49,1,'2026-04-18 18:11:59'),(50,1,'2026-04-18 19:35:16'),(51,1,'2026-04-18 20:45:38'),(52,9,'2026-04-18 23:48:09'),(53,1,'2026-04-19 23:48:58'),(55,1,'2026-04-20 09:21:49'),(56,1,'2026-04-20 15:29:09'),(57,1,'2026-04-20 16:23:23'),(59,1,'2026-04-20 16:57:55'),(61,1,'2026-04-20 17:01:55'),(62,1,'2026-04-21 18:46:29'),(66,1,'2026-04-21 19:18:31'),(68,1,'2026-04-21 19:29:36'),(69,1,'2026-04-21 19:34:38'),(70,1,'2026-04-21 19:37:34'),(71,1,'2026-04-21 19:41:43'),(73,1,'2026-04-21 19:44:45');
/*!40000 ALTER TABLE `historial_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multimedia_eventos`
--

DROP TABLE IF EXISTS `multimedia_eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `multimedia_eventos` (
  `id_multimedia` int NOT NULL AUTO_INCREMENT,
  `ruta_archivo` varchar(255) NOT NULL,
  `tipo_archivo` varchar(45) NOT NULL,
  `titulo_archivo` varchar(100) NOT NULL,
  `orden_visualizacion` int DEFAULT NULL,
  `id_evento` int NOT NULL,
  PRIMARY KEY (`id_multimedia`),
  KEY `fk_multimedia_eventos_idx` (`id_evento`),
  CONSTRAINT `fk_multimedia_eventos` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multimedia_eventos`
--

LOCK TABLES `multimedia_eventos` WRITE;
/*!40000 ALTER TABLE `multimedia_eventos` DISABLE KEYS */;
INSERT INTO `multimedia_eventos` VALUES (7,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.32 PM (1).jpeg','image','WhatsApp Image 2026-04-05 at 1.59.32 PM (1).jpeg',NULL,4),(8,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.32 PM.jpeg','image','WhatsApp Image 2026-04-05 at 1.59.32 PM.jpeg',NULL,4),(9,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.33 PM (1).jpeg','image','WhatsApp Image 2026-04-05 at 1.59.33 PM (1).jpeg',NULL,4),(10,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.33 PM.jpeg','image','WhatsApp Image 2026-04-05 at 1.59.33 PM.jpeg',NULL,4),(11,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.34 PM (1).jpeg','image','WhatsApp Image 2026-04-05 at 1.59.34 PM (1).jpeg',NULL,4),(12,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.34 PM.jpeg','image','WhatsApp Image 2026-04-05 at 1.59.34 PM.jpeg',NULL,4),(13,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.38 PM (1).jpeg','image','WhatsApp Image 2026-04-05 at 1.59.38 PM (1).jpeg',NULL,4),(14,'img_eventos/1775615433_WhatsApp Image 2026-04-05 at 1.59.38 PM.jpeg','image','WhatsApp Image 2026-04-05 at 1.59.38 PM.jpeg',NULL,4),(15,'img_eventos/1775696345_WhatsApp Image 2026-04-05 at 3.15.10 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.15.10 PM.jpeg',NULL,5),(16,'img_eventos/1775696345_WhatsApp Image 2026-04-05 at 3.15.19 PM (1).jpeg','image','WhatsApp Image 2026-04-05 at 3.15.19 PM (1).jpeg',NULL,5),(17,'img_eventos/1775696345_WhatsApp Image 2026-04-05 at 3.15.19 PM (2).jpeg','image','WhatsApp Image 2026-04-05 at 3.15.19 PM (2).jpeg',NULL,5),(18,'img_eventos/1775696345_WhatsApp Image 2026-04-05 at 3.15.19 PM (3).jpeg','image','WhatsApp Image 2026-04-05 at 3.15.19 PM (3).jpeg',NULL,5),(19,'img_eventos/1775696345_WhatsApp Image 2026-04-05 at 3.15.19 PM (4).jpeg','image','WhatsApp Image 2026-04-05 at 3.15.19 PM (4).jpeg',NULL,5),(20,'img_eventos/1775696345_WhatsApp Image 2026-04-05 at 3.15.19 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.15.19 PM.jpeg',NULL,5),(21,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.33.45 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.33.45 PM.jpeg',NULL,6),(22,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.33.57 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.33.57 PM.jpeg',NULL,6),(23,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.34.08 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.34.08 PM.jpeg',NULL,6),(24,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.34.19 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.34.19 PM.jpeg',NULL,6),(25,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.34.29 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.34.29 PM.jpeg',NULL,6),(26,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.34.45 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.34.45 PM.jpeg',NULL,6),(27,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.34.56 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.34.56 PM.jpeg',NULL,6),(28,'img_eventos/1775696927_WhatsApp Image 2026-04-05 at 3.35.05 PM.jpeg','image','WhatsApp Image 2026-04-05 at 3.35.05 PM.jpeg',NULL,6),(29,'img_eventos/1776557418_3.jpeg','image','3.jpeg',NULL,7),(30,'img_eventos/1776557565_WhatsApp Image 2026-04-05 at 2.11.01 PM.jpeg','image','WhatsApp Image 2026-04-05 at 2.11.01 PM.jpeg',NULL,8),(31,'img_eventos/1776557706_WhatsApp Image 2026-04-05 at 2.04.59 PM.jpeg','image','WhatsApp Image 2026-04-05 at 2.04.59 PM.jpeg',NULL,9);
/*!40000 ALTER TABLE `multimedia_eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfiles`
--

DROP TABLE IF EXISTS `perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfiles` (
  `id_perfiles` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `nombre_usuario` varchar(45) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `apellido_usuario` varchar(45) NOT NULL,
  PRIMARY KEY (`id_perfiles`),
  KEY `fk_perfil_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_perfil_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfiles`
--

LOCK TABLES `perfiles` WRITE;
/*!40000 ALTER TABLE `perfiles` DISABLE KEYS */;
INSERT INTO `perfiles` VALUES (1,1,'luis','123456789','robles'),(2,3,'luis','123456789','robles'),(3,4,'luis','123456789','robles'),(4,5,'yole','123456789','mavares'),(8,9,'Arturo','04246446850','Trau');
/*!40000 ALTER TABLE `perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id_permiso` int NOT NULL AUTO_INCREMENT,
  `nombre_permiso` varchar(100) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `nombre_permiso` (`nombre_permiso`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'gestionar_usuarios','Ver lista de usuarios, cambiar roles y eliminar perfiles'),(2,'administrar_secciones','Crear, editar y eliminar secciones del menú'),(3,'publicar_contenido','Subir comunicados, fotos, eventos y documentos'),(4,'editar_contenido','Modificar publicaciones existentes'),(5,'eliminar_contenido','Borrar publicaciones del sistema'),(6,'ver_panel_admin','Permiso básico para entrar al dashboard');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos_roles`
--

DROP TABLE IF EXISTS `permisos_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos_roles` (
  `id_rol` int NOT NULL,
  `id_permiso` int NOT NULL,
  PRIMARY KEY (`id_rol`,`id_permiso`),
  KEY `id_permiso` (`id_permiso`),
  CONSTRAINT `permisos_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE,
  CONSTRAINT `permisos_roles_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos_roles`
--

LOCK TABLES `permisos_roles` WRITE;
/*!40000 ALTER TABLE `permisos_roles` DISABLE KEYS */;
INSERT INTO `permisos_roles` VALUES (1,1),(5,1),(1,2),(1,3),(1,4),(2,4),(3,4),(1,5),(2,5),(1,6),(2,6),(3,6),(5,6);
/*!40000 ALTER TABLE `permisos_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recuperacion_password`
--

DROP TABLE IF EXISTS `recuperacion_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recuperacion_password` (
  `id_recuperacion` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiracion` datetime NOT NULL,
  `usados` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_recuperacion`),
  UNIQUE KEY `token_UNIQUE` (`token`),
  KEY `fk_recuperacion_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_recuperacion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recuperacion_password`
--

LOCK TABLES `recuperacion_password` WRITE;
/*!40000 ALTER TABLE `recuperacion_password` DISABLE KEYS */;
INSERT INTO `recuperacion_password` VALUES (4,9,'4fc6b57f7c1bbb753d6cd13496c841a1','2026-04-20 16:53:58',0);
/*!40000 ALTER TABLE `recuperacion_password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(20) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre_rol_UNIQUE` (`nombre_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'administrador'),(3,'Editor'),(2,'moderador'),(5,'Secretaria'),(4,'Visitante');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secciones_paginas`
--

DROP TABLE IF EXISTS `secciones_paginas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secciones_paginas` (
  `id_seccion` int NOT NULL AUTO_INCREMENT,
  `nombre_seccion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_seccion`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secciones_paginas`
--

LOCK TABLES `secciones_paginas` WRITE;
/*!40000 ALTER TABLE `secciones_paginas` DISABLE KEYS */;
INSERT INTO `secciones_paginas` VALUES (2,'Galeria'),(3,'Comunicados'),(4,'Eventos'),(5,'Documentos');
/*!40000 ALTER TABLE `secciones_paginas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesiones_activas`
--

DROP TABLE IF EXISTS `sesiones_activas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones_activas` (
  `id_sesion_sql` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token_sesion` varchar(255) NOT NULL,
  `ip_acceso` varchar(45) DEFAULT NULL,
  `ultima_actividad` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sesion_sql`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `sesiones_activas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones_activas`
--

LOCK TABLES `sesiones_activas` WRITE;
/*!40000 ALTER TABLE `sesiones_activas` DISABLE KEYS */;
INSERT INTO `sesiones_activas` VALUES (52,9,'45c16077bd5e48a4945161668a82cc8814fb6c478e4e8f14d97822978b249737','::1','2026-04-19 03:48:09'),(73,1,'58439a0e73c9dfe7c20db63ac8605963cc24d28797241ceaee3e7a4c1ff6f012','::1','2026-04-21 23:44:51');
/*!40000 ALTER TABLE `sesiones_activas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicacion_eventos`
--

DROP TABLE IF EXISTS `ubicacion_eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ubicacion_eventos` (
  `id_ubicacion` int NOT NULL AUTO_INCREMENT,
  `nombre_lugar` varchar(45) NOT NULL,
  PRIMARY KEY (`id_ubicacion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicacion_eventos`
--

LOCK TABLES `ubicacion_eventos` WRITE;
/*!40000 ALTER TABLE `ubicacion_eventos` DISABLE KEYS */;
INSERT INTO `ubicacion_eventos` VALUES (1,'Direccion'),(2,'Cede Ints. Diego Rivera');
/*!40000 ALTER TABLE `ubicacion_eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_roles`
--

DROP TABLE IF EXISTS `usuario_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_roles` (
  `id_usuario_rol` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_rol` int NOT NULL,
  PRIMARY KEY (`id_usuario_rol`),
  UNIQUE KEY `idx_usuario_rol_unico` (`id_usuario`,`id_rol`),
  KEY `fk_usuario_roles_usuario_idx` (`id_usuario`),
  KEY `fk_usuario_roles_roles_idx` (`id_rol`),
  CONSTRAINT `fk_usuario_roles_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_roles_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_roles`
--

LOCK TABLES `usuario_roles` WRITE;
/*!40000 ALTER TABLE `usuario_roles` DISABLE KEYS */;
INSERT INTO `usuario_roles` VALUES (1,1,1),(4,9,2);
/*!40000 ALTER TABLE `usuario_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario_login` varchar(50) NOT NULL,
  `contraseña` varchar(150) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `token_verificacion` varchar(200) DEFAULT NULL,
  `estado_verificado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo_UNIQUE` (`correo`),
  UNIQUE KEY `usuario_login_UNIQUE` (`usuario_login`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'arturito','99adc231b045331e514a516b4b7680f588e3823213abe901738bc3ad67b2f6fcb3c64efb93d18002588d3ccc1a49efbae1ce20cb43df36b38651f11fa75678e8','prueba@correo.com',NULL,1),(3,'arti','99adc231b045331e514a516b4b7680f588e3823213abe901738bc3ad67b2f6fcb3c64efb93d18002588d3ccc1a49efbae1ce20cb43df36b38651f11fa75678e8','prueba1@correo.com',NULL,0),(4,'pipipi','99adc231b045331e514a516b4b7680f588e3823213abe901738bc3ad67b2f6fcb3c64efb93d18002588d3ccc1a49efbae1ce20cb43df36b38651f11fa75678e8','prueba2@correo.com',NULL,0),(5,'yoli','99adc231b045331e514a516b4b7680f588e3823213abe901738bc3ad67b2f6fcb3c64efb93d18002588d3ccc1a49efbae1ce20cb43df36b38651f11fa75678e8','prueba13@correo.com',NULL,0),(9,'vergil','$2y$10$WIWSQAoV2wPZybYyNzW7yOeE55GtUiKdkl0m9n4Y4JAv//fjde68a','luisarturor649@gmail.com',NULL,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-21 20:30:26
