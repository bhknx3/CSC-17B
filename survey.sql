CREATE DATABASE  IF NOT EXISTS `survey` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `survey`;
-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for Win32 (AMD64)
--
-- Host: 127.0.0.1    Database: survey
-- ------------------------------------------------------
-- Server version	10.1.37-MariaDB

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
-- Table structure for table `entity_answer`
--

DROP TABLE IF EXISTS `entity_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_answer` (
  `answer_id` int(10) NOT NULL AUTO_INCREMENT,
  `answer` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`answer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_answer`
--

LOCK TABLES `entity_answer` WRITE;
/*!40000 ALTER TABLE `entity_answer` DISABLE KEYS */;
INSERT INTO `entity_answer` VALUES (1,'Beef'),(2,'Chicken'),(3,'Pork'),(4,'Chicken'),(5,'Other'),(6,'Ranch'),(7,'Italian'),(8,'Balsamic Vinegar'),(9,'Honey Mustard'),(10,'Other'),(11,'Coke'),(12,'Sprite'),(13,'Root beer'),(14,'Other'),(15,'Pie'),(16,'Cake'),(17,'Pastry'),(18,'Ice cream'),(19,'Other'),(20,'12:00 PM'),(21,'1:00 PM'),(22,'2:00 PM'),(23,'3:00 PM'),(24,'4:00 PM');
/*!40000 ALTER TABLE `entity_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_question`
--

DROP TABLE IF EXISTS `entity_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_question` (
  `question_id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_question`
--

LOCK TABLES `entity_question` WRITE;
/*!40000 ALTER TABLE `entity_question` DISABLE KEYS */;
INSERT INTO `entity_question` VALUES (1,'What main meat do you prefer?'),(2,'Favorite salad dressing?'),(3,'Favorite soda?'),(4,'Favorite dessert?'),(5,'Best time?');
/*!40000 ALTER TABLE `entity_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_survey`
--

DROP TABLE IF EXISTS `entity_survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_survey` (
  `survey_id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `unique_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_survey`
--

LOCK TABLES `entity_survey` WRITE;
/*!40000 ALTER TABLE `entity_survey` DISABLE KEYS */;
INSERT INTO `entity_survey` VALUES (1,'Food ideas for next week!','Need a tally on what kinds of food should be served at our next gathering','eff35e6cfeebaa8fc3150bf1d04086b8'),(2,'What time should we meet?','To determine a time to meet up for the club meeting','5c1e89f8ce77e755d949f02b33291846');
/*!40000 ALTER TABLE `entity_survey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_user`
--

DROP TABLE IF EXISTS `entity_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_user`
--

LOCK TABLES `entity_user` WRITE;
/*!40000 ALTER TABLE `entity_user` DISABLE KEYS */;
INSERT INTO `entity_user` VALUES (1,'admin','$2y$10$EGebORNkDBlvfK3y05k3auJ4hqR77ij9xO7/tm1dDT1JMvEmIrT12',0,'2019-05-20 00:27:00'),(2,'user1','$2y$10$OoAXWIIFJv8qAMrjWSUVoOZs3xGUkI2RIuOJBJhdfBzb2muoVhEQW',1,'2019-06-01 20:36:04'),(3,'user2','$2y$10$fE8E0d9ybqlCErwFkNIN5uINifqZytKOBR1f943Wqm2DBL/xPF6Km',1,'2019-06-01 20:36:16'),(4,'user3','$2y$10$9Wc.HgcAyogxXMlnX.SlQ.1HvXvu0vZDSTxjLEtjpf29nx4x2zb0G',1,'2019-06-01 20:36:22'),(5,'user4','$2y$10$qmluyEjTKztyDa9DWQvfE.TsU3eKiDorN2.K48OL4zsD477CF8MWu',1,'2019-06-01 20:36:26'),(6,'user5','$2y$10$2TmQcGCvIaazr7AQe5KZyO8ZZ4CAVzy8w2FVDkbxWZwdH1ejgxLuK',1,'2019-06-01 20:36:30'),(7,'auser1','$2y$10$KO7jteqO1BC1nnUUjaRwzONmQPzuHPmg8cUbxY9kCGZz9pXp7YcZG',1,'2019-06-01 20:37:06');
/*!40000 ALTER TABLE `entity_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_answer_user`
--

DROP TABLE IF EXISTS `xref_answer_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_answer_user` (
  `answer_user_id` int(10) NOT NULL AUTO_INCREMENT,
  `answer_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`answer_user_id`),
  KEY `user_id` (`user_id`),
  KEY `answer_id` (`answer_id`),
  CONSTRAINT `xref_answer_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `entity_user` (`user_id`),
  CONSTRAINT `xref_answer_user_ibfk_2` FOREIGN KEY (`answer_id`) REFERENCES `entity_answer` (`answer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_answer_user`
--

LOCK TABLES `xref_answer_user` WRITE;
/*!40000 ALTER TABLE `xref_answer_user` DISABLE KEYS */;
INSERT INTO `xref_answer_user` VALUES (1,1,2),(2,6,2),(3,11,2),(4,18,2),(5,1,3),(6,7,3),(7,11,3),(8,18,3);
/*!40000 ALTER TABLE `xref_answer_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_question_answer`
--

DROP TABLE IF EXISTS `xref_question_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_question_answer` (
  `question_answer_id` int(10) NOT NULL AUTO_INCREMENT,
  `question_id` int(10) DEFAULT NULL,
  `answer_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`question_answer_id`),
  KEY `question_id` (`question_id`),
  KEY `answer_id` (`answer_id`),
  CONSTRAINT `xref_question_answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `entity_question` (`question_id`),
  CONSTRAINT `xref_question_answer_ibfk_2` FOREIGN KEY (`answer_id`) REFERENCES `entity_answer` (`answer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_question_answer`
--

LOCK TABLES `xref_question_answer` WRITE;
/*!40000 ALTER TABLE `xref_question_answer` DISABLE KEYS */;
INSERT INTO `xref_question_answer` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,2,6),(7,2,7),(8,2,8),(9,2,9),(10,2,10),(11,3,11),(12,3,12),(13,3,13),(14,3,14),(15,4,15),(16,4,16),(17,4,17),(18,4,18),(19,4,19),(20,5,20),(21,5,21),(22,5,22),(23,5,23),(24,5,24);
/*!40000 ALTER TABLE `xref_question_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_survey_question`
--

DROP TABLE IF EXISTS `xref_survey_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_survey_question` (
  `survey_question_id` int(10) NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) DEFAULT NULL,
  `question_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`survey_question_id`),
  KEY `survey_id` (`survey_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `xref_survey_question_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `entity_survey` (`survey_id`),
  CONSTRAINT `xref_survey_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `entity_question` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_survey_question`
--

LOCK TABLES `xref_survey_question` WRITE;
/*!40000 ALTER TABLE `xref_survey_question` DISABLE KEYS */;
INSERT INTO `xref_survey_question` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,2,5);
/*!40000 ALTER TABLE `xref_survey_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_user_survey`
--

DROP TABLE IF EXISTS `xref_user_survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_user_survey` (
  `user_survey_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `survey_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_survey_id`),
  KEY `survey_id` (`survey_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `xref_user_survey_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `entity_survey` (`survey_id`),
  CONSTRAINT `xref_user_survey_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `entity_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_user_survey`
--

LOCK TABLES `xref_user_survey` WRITE;
/*!40000 ALTER TABLE `xref_user_survey` DISABLE KEYS */;
INSERT INTO `xref_user_survey` VALUES (1,1,1),(2,2,1),(3,1,2),(4,3,1);
/*!40000 ALTER TABLE `xref_user_survey` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-02  0:49:53
