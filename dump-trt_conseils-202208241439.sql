-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: trt_conseils
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.24-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `candidature`
--

DROP TABLE IF EXISTS `candidature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_approved` tinyint(1) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `job_offer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E33BD3B891BD8781` (`candidate_id`),
  KEY `IDX_E33BD3B83481D195` (`job_offer_id`),
  CONSTRAINT `FK_E33BD3B83481D195` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offer` (`id`),
  CONSTRAINT `FK_E33BD3B891BD8781` FOREIGN KEY (`candidate_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidature`
--

LOCK TABLES `candidature` WRITE;
/*!40000 ALTER TABLE `candidature` DISABLE KEYS */;
INSERT INTO `candidature` VALUES (1,1,1,12),(2,1,7,10),(3,1,7,13),(4,1,7,12),(5,1,7,8),(6,0,1,10),(7,0,1,11),(9,1,1,8),(10,0,1,13);
/*!40000 ALTER TABLE `candidature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20220816075000','2022-08-16 09:50:53',353),('DoctrineMigrations\\Version20220817101850','2022-08-17 12:19:09',563),('DoctrineMigrations\\Version20220817121941','2022-08-17 14:19:54',89),('DoctrineMigrations\\Version20220817124545','2022-08-17 14:45:50',130),('DoctrineMigrations\\Version20220817131500','2022-08-17 15:15:04',62),('DoctrineMigrations\\Version20220817145610','2022-08-17 16:56:13',210),('DoctrineMigrations\\Version20220823143036','2022-08-23 16:30:57',191),('DoctrineMigrations\\Version20220823143414','2022-08-23 16:34:18',194);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_offer`
--

DROP TABLE IF EXISTS `job_offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` double NOT NULL,
  `working_hours` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recruiter_id` int(11) NOT NULL,
  `is_approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_288A3A4E156BE243` (`recruiter_id`),
  CONSTRAINT `FK_288A3A4E156BE243` FOREIGN KEY (`recruiter_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_offer`
--

LOCK TABLES `job_offer` WRITE;
/*!40000 ALTER TABLE `job_offer` DISABLE KEYS */;
INSERT INTO `job_offer` VALUES (8,'Barman','L\'Océan Vert\r\nRue d\'ici\r\n62000 Arras','Besoin d\'un barman spécialisé dans les cocktails pour travail soir et nuit.\r\nDébutant accepté',1600,'35h',3,1),(10,'Cuisinier','Restaurant Mezzaluna\r\nRue de labas\r\n62000 Arras','Nous recherchons un cuisinier spécialisé dans les plats vegan.\r\nPoste soir et weekend',1700,'35h',4,1),(11,'Serveur en salle','Couleur Café\r\nPlace des héros\r\n62000 Arras','Nous recherchons un serveur en salle dynamique et souriant.\r\nExpérience de 3 ans minimum demandée.',1450,'35h',3,1),(12,'Gérant restaurant','Bio\'Tiful Restaurant\r\nBlvd de Strasbourg\r\n62000 Arras','Besoin d\'un gérant en restauration rapidement.\r\nEncadrement d\'une équipe 15 personnes.\r\nExpérience de 5ans minimum\r\nPoste à pourvoir dès que possible.\r\nCDI',2200,'39h',4,1),(13,'test','dfbvsddvsdf','dfvdsfvsdvfddsf',1234,'35h',3,1),(15,'test2','fhkuykuykuykyfuykjf,hjf,fj\r\nsvsrgrdtgrt','dfghhsqdjfnjkvnsef  unjkncjkn n n nuinijsoqùjioV A DFDQVQ     DSQFQSD',1245,'35h',3,1);
/*!40000 ALTER TABLE `job_offer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_offer_user`
--

DROP TABLE IF EXISTS `job_offer_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_offer_user` (
  `job_offer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`job_offer_id`,`user_id`),
  KEY `IDX_191C68433481D195` (`job_offer_id`),
  KEY `IDX_191C6843A76ED395` (`user_id`),
  CONSTRAINT `FK_191C68433481D195` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offer` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_191C6843A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_offer_user`
--

LOCK TABLES `job_offer_user` WRITE;
/*!40000 ALTER TABLE `job_offer_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_offer_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'mathieubouthors@hotmail.com','[\"ROLE_CANDIDATE\"]','$2y$13$i.auBLlUMslY/sz44uIp/ug/8/oENe.BTcojrPXuGwqoOkUYiNvMC','Mathieu','Bouthors','Serveur','652ab1f73253f625183301333d552c1b.pdf',NULL,NULL,1),(2,'john.doe@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$qytWsvmJUnmnHXEvNyzmke0cfwduLINZeJLWt/4CbtQIyWzUUT9vm','John','Doe',NULL,NULL,NULL,NULL,1),(3,'mathieu.bouthors.mb@gmail.com','[\"ROLE_RECRUITER\"]','$2y$13$ddUKeJS9pN1HXmPiKongN.IXgKYNxpBnjIe6LkFY2fxHgUnEdU5ci',NULL,NULL,NULL,NULL,'Ma societe','4 rue de la-bas 62000 Arras',1),(4,'mathieu.bouthors@gmail.com','[\"ROLE_RECRUITER\"]','$2y$13$QI0WwZ9dV80v72Hir2anWejl1q.6ZuXupD27NzdDDmNf4eFA.lXL.',NULL,NULL,NULL,NULL,'societe','qvaerqrverfeqrfer',1),(5,'mathieubouthor@gmail.com','[\"ROLE_RECRUITER\"]','$2y$13$/2J0VflPjAXjvXnBKquRh.ClUKp3V9bKuvhhMBG/ANmg8dPj3feZK',NULL,NULL,NULL,NULL,'societe','qvaerqrverfeqrfer',1),(6,'mathibouthors@hotmail.com','[\"ROLE_CANDIDATE\"]','$2y$13$k.7SVc2a0Pu9hpkETTmEkeKKNV10V7v4LNU4G4YpfAolQFt4COPIO','mat','may','azertyuiop','C:\\xampp\\tmp\\phpA190.tmp',NULL,NULL,1),(7,'mathbouthors@hotmail.com','[\"ROLE_CANDIDATE\"]','$2y$13$WL7Rc2PKET8nsWFoTErPxuZ/KnYugxqCKrHurNSS0Wl5dWm7MepNy','njnjn','bjhkl',',kl,,k','C:\\xampp\\tmp\\phpB1B5.tmp',NULL,NULL,1),(8,'m@b.com','[\"ROLE_CANDIDATE\"]','$2y$13$vC7BV9jRAgZNHOzzj/XfUOgRY2jWTBkpF6UAVSteE94aBgwKf4nYK','MAt','Boo','gfhvh;nj','C:\\xampp\\tmp\\php4B37.tmp',NULL,NULL,1),(9,'a@b.com','[\"ROLE_RECRUITER\"]','$2y$13$FgYqn7pdY1keXGGr6D6YQuoQBANmLfvUxnsGJgOvqq8Ysc03V.tCK',NULL,NULL,NULL,NULL,'une société','sdfgdgvds',0),(10,'mors@hotmail.com','[\"ROLE_CANDIDATE\"]','$2y$13$3G1H4ncixZJo3qUtAyku5OMzpRillDZpaWudL5waGU3F4DInkg9Iu','mat','boo','fgjgbjhk','C:\\xampp\\tmp\\php40B7.tmp',NULL,NULL,1),(11,'mrs@hotmail.com','[\"ROLE_CANDIDATE\"]','$2y$13$yeewVYMbGPmLc6usUCD5a.pmlO2MHJKj768IOcx5esnyTOblD/Jn.','mat','boo','fgjgbjh','f77b45a7a44b17f2e5bf1ee57cd006d7.pdf',NULL,NULL,0),(13,'consult2@hotmail.com','[\"ROLE_CONSULTANT\"]','$2y$13$R3QStlWsBGZRLInsDfW21e.JONMCud/bYxFH..NeP1nTxsbBh9Tcu','Lui','azert',NULL,NULL,NULL,NULL,1),(14,'consult1@gmail.com','[\"ROLE_CONSULTANT\"]','$2y$13$xmzAG9.e452T4GnyJUPqZOLXh4goXwp3MovYyLEJfBqrqctH/nHDG','Ant','Boo',NULL,NULL,NULL,NULL,1),(15,'consult3@gmail.comm','[\"ROLE_CONSULTANT\"]','$2y$13$6se3qfP4TtMqprnqwm8NB.pD4lYIq6aMO3IsBhUWai8TEMbWGluQG','Lui','Nom',NULL,NULL,NULL,NULL,1),(27,'test1@test.com','[\"ROLE_CONSULTANT\"]','$2y$13$N.qIpxmC7PD0eNYlPDgZJeiLz2WxMpMRe9oESC.mh5Wbt/82JGfYi','test1','test1',NULL,NULL,NULL,NULL,1),(28,'test@test.com','[\"ROLE_CANDIDATE\"]','$2y$13$BrY1hznUzDaLWkkqXJaRxeN8f/zsnR6BrNhlF8geoKSXo5zUQsjFS','test','test','test','dd94c2363b7a477861bc4de931e4a068.pdf',NULL,NULL,0),(29,'test2@test.com','[\"ROLE_RECRUITER\"]','$2y$13$KAgQtsD4f9WtbUU2ocJ9leAlm46ZfkB31yQUijRvdx.8roqjSZhAu',NULL,NULL,NULL,NULL,'nkmjnk',',klù',0),(30,'test3@test.com','[\"ROLE_CANDIDATE\"]','$2y$13$mVDWgWOoXw2AEZdJ3nZlau1QZ.Y8uWabODGddwJ9WnvYPuCOG7YXi','bhjkbhljknkl','kl,k!,',',lmk,;L.M','05c84c491e8f8f3282707655f8395958.pdf',NULL,NULL,0),(31,'test4@test.com','[\"ROLE_CANDIDATE\"]','$2y$13$2SE7YYtYwsg//a62CervU.UMdByiRiM2xS7mJywZv25PpRmJu9p5S','fjhgfg','ghfjgh','bgfg','35426f4415b2df4db0aaf673808e06ed.pdf',NULL,NULL,0),(38,'bouthors.mathieu@gmail.com','[\"ROLE_CONSULTANT\"]','$2y$13$GtXGTo5KmZGVSCjHp3RcwuCDRGzaaA9LnDi6HmpWj3cRpjutyDyMq','Mathieu','Boo',NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'trt_conseils'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-24 14:39:25
