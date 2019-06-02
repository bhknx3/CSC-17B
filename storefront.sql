CREATE DATABASE  IF NOT EXISTS `storefront` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `storefront`;
-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for Win32 (AMD64)
--
-- Host: 127.0.0.1    Database: storefront
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
-- Table structure for table `entity_order`
--

DROP TABLE IF EXISTS `entity_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_order` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_total` decimal(50,2) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_order`
--

LOCK TABLES `entity_order` WRITE;
/*!40000 ALTER TABLE `entity_order` DISABLE KEYS */;
INSERT INTO `entity_order` VALUES (1,'2019-05-31 23:04:16',254.91),(2,'2019-05-31 23:40:23',99.99);
/*!40000 ALTER TABLE `entity_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_order_item`
--

DROP TABLE IF EXISTS `entity_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_order_item` (
  `order_item_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_item_quantity` int(10) NOT NULL,
  `order_item_price` decimal(50,2) NOT NULL,
  PRIMARY KEY (`order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_order_item`
--

LOCK TABLES `entity_order_item` WRITE;
/*!40000 ALTER TABLE `entity_order_item` DISABLE KEYS */;
INSERT INTO `entity_order_item` VALUES (1,5,14.99),(2,1,29.99),(3,3,49.99),(4,1,99.99);
/*!40000 ALTER TABLE `entity_order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_product`
--

DROP TABLE IF EXISTS `entity_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_product` (
  `product_id` int(10) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `type` int(10) NOT NULL,
  `product_color` varchar(50) NOT NULL,
  `product_price` decimal(50,2) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `entity_product_ibfk_1` (`type`),
  CONSTRAINT `entity_product_ibfk_1` FOREIGN KEY (`type`) REFERENCES `enum_product_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_product`
--

LOCK TABLES `entity_product` WRITE;
/*!40000 ALTER TABLE `entity_product` DISABLE KEYS */;
INSERT INTO `entity_product` VALUES (1,'White Tee','100% cotton tee',1,'White',14.99,'white_t.jpg'),(2,'Red Tee','100% cotton tee',1,'Red',14.99,'red_t.jpg'),(3,'Blue Tee','100% cotton tee',1,'Blue',14.99,'blue_t.jpg'),(4,'Colorful Flannel','Check patterned multi-colored flannel shirt',2,'Multi',49.99,'color_flannel.jpg'),(5,'Leather Jacket','Full-grain leather jacket with minimalist detailing',3,'Brown',1999.99,'leather_jacket.jpg'),(6,'Jeans','Light-blue washed jeans for everyday attire',4,'Blue',49.99,'jeans.jpg'),(7,'Ripped Jeans','Dark-washed jeans with distressed detailing',4,'Black',59.99,'ripped_black_jeans.jpg'),(8,'Belt','A regular belt',7,'Black',29.99,'belt.jpg'),(9,'Pocket Jacket','Jacket with many pockets',3,'Green',99.99,'jacket.jpg'),(10,'Ugly Shoes','A pair of expensive shoes',6,'White/Green',1655.00,'shoe.jpg'),(11,'Necklace','18K white gold and diamonds pendant for your loved one...',7,'White',10200.00,'necklace.jpg');
/*!40000 ALTER TABLE `entity_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_product_variant`
--

DROP TABLE IF EXISTS `entity_product_variant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_product_variant` (
  `variant_id` int(10) NOT NULL AUTO_INCREMENT,
  `variant_size` int(10) DEFAULT NULL,
  `variant_quantity` int(10) DEFAULT NULL,
  PRIMARY KEY (`variant_id`),
  KEY `quantity` (`variant_quantity`),
  KEY `entity_product_variant_ibfk_1` (`variant_size`),
  CONSTRAINT `entity_product_variant_ibfk_1` FOREIGN KEY (`variant_size`) REFERENCES `enum_size` (`size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_product_variant`
--

LOCK TABLES `entity_product_variant` WRITE;
/*!40000 ALTER TABLE `entity_product_variant` DISABLE KEYS */;
INSERT INTO `entity_product_variant` VALUES (1,4,50),(2,5,50),(3,6,50),(4,7,50),(5,8,50),(6,4,50),(7,5,50),(8,6,50),(9,7,50),(10,8,50),(11,4,50),(12,5,50),(13,6,50),(14,7,50),(15,8,50),(16,5,25),(17,6,25),(18,7,25),(19,8,25),(20,5,10),(21,6,10),(22,7,10),(23,8,10),(24,11,50),(25,13,50),(26,15,50),(27,17,50),(28,19,50),(29,21,50),(30,23,50),(31,25,50),(32,11,20),(33,13,20),(34,15,20),(35,17,20),(36,19,20),(37,21,20),(38,23,20),(39,25,20),(40,1,500),(41,5,100),(42,6,100),(43,7,100),(44,8,100),(45,34,10),(46,36,10),(47,38,10),(48,40,10),(49,42,10),(50,1,5);
/*!40000 ALTER TABLE `entity_product_variant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_user`
--

DROP TABLE IF EXISTS `entity_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` int(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_user`
--

LOCK TABLES `entity_user` WRITE;
/*!40000 ALTER TABLE `entity_user` DISABLE KEYS */;
INSERT INTO `entity_user` VALUES (1,'admin@storefront.com','$2y$10$a8kCiGtJSx69l1CU/jpmHuRodonzdYExk9tryEQ8AhVJUBuQw/.mm',NULL,NULL,'2019-05-27 01:15:31',0),(2,'bkwon1@student.rccd.edu','$2y$10$ll5ddutzdFtVz9Z8YteD7u46d3MEEKUvhhr2CHdcdb6og6REVAa06','Ben','Kwon','2019-05-31 23:03:32',1),(3,'user1@email.com','$2y$10$9DT2iE2keATFdwHrOuJpDeJ/dpZKofNY0.PJY8jenYzy1zl42bH.e','George','Washington','2019-06-01 17:49:17',1),(4,'user2@email.com','$2y$10$nl3PbPiQYScXsEdpfoOM3.G2bL9jMfjjKxuNxQyluauq8YfIbdVqO','Thomas','Jefferson','2019-06-01 17:50:27',1),(5,'user3@email.com','$2y$10$U4u594PEfoGrVOLgw4s7oOc3l9WnVan8aHRubrdMLsTP6rdSsT2dm','Abraham','Lincoln','2019-06-01 17:51:21',1),(6,'user4@email.com','$2y$10$CIDbgltM9AGq2Dx5oxj26OkkYkN4uznXW.pA13.cfCFCHeVjfYUbi','Andrew','Jackson','2019-06-01 17:52:04',1),(7,'user5@email.com','$2y$10$6EmA/A8c307pCXeC0u4.kezkTdsAxvSY1vipEYDdp9U.E6dKI2CcW','Benjamin','Franklin','2019-06-01 17:52:16',1);
/*!40000 ALTER TABLE `entity_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enum_product_type`
--

DROP TABLE IF EXISTS `enum_product_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enum_product_type` (
  `id` int(10) NOT NULL,
  `product_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enum_product_type`
--

LOCK TABLES `enum_product_type` WRITE;
/*!40000 ALTER TABLE `enum_product_type` DISABLE KEYS */;
INSERT INTO `enum_product_type` VALUES (1,'T-Shirt'),(2,'Shirt'),(3,'Outerwear'),(4,'Denim'),(5,'Pants'),(6,'Shoes'),(7,'Accessories');
/*!40000 ALTER TABLE `enum_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enum_size`
--

DROP TABLE IF EXISTS `enum_size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enum_size` (
  `size_id` int(10) NOT NULL AUTO_INCREMENT,
  `size` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enum_size`
--

LOCK TABLES `enum_size` WRITE;
/*!40000 ALTER TABLE `enum_size` DISABLE KEYS */;
INSERT INTO `enum_size` VALUES (1,'O/S'),(2,'XXXS'),(3,'XXS'),(4,'XS'),(5,'S'),(6,'M'),(7,'L'),(8,'XL'),(9,'XXL'),(10,'XXXL'),(11,'28'),(12,'29'),(13,'30'),(14,'31'),(15,'32'),(16,'33'),(17,'34'),(18,'35'),(19,'36'),(20,'37'),(21,'38'),(22,'39'),(23,'40'),(24,'41'),(25,'42'),(26,'43'),(27,'44'),(28,'45'),(29,'46'),(30,'47'),(31,'48'),(32,'49'),(33,'50'),(34,'8'),(35,'8.5'),(36,'9'),(37,'9.5'),(38,'10'),(39,'10.5'),(40,'11'),(41,'11.5'),(42,'12');
/*!40000 ALTER TABLE `enum_size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_order_item_product_variant`
--

DROP TABLE IF EXISTS `xref_order_item_product_variant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_order_item_product_variant` (
  `order_item_product_variant_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_item_id` int(10) NOT NULL,
  `product_variant_id` int(10) NOT NULL,
  PRIMARY KEY (`order_item_product_variant_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `product_variation_id` (`product_variant_id`),
  CONSTRAINT `xref_order_item_product_variant_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `entity_order_item` (`order_item_id`),
  CONSTRAINT `xref_order_item_product_variant_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `entity_product_variant` (`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_order_item_product_variant`
--

LOCK TABLES `xref_order_item_product_variant` WRITE;
/*!40000 ALTER TABLE `xref_order_item_product_variant` DISABLE KEYS */;
INSERT INTO `xref_order_item_product_variant` VALUES (1,1,4),(2,2,40),(3,3,19),(4,4,44);
/*!40000 ALTER TABLE `xref_order_item_product_variant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_order_order_item`
--

DROP TABLE IF EXISTS `xref_order_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_order_order_item` (
  `order_order_item_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `order_item_id` int(10) NOT NULL,
  PRIMARY KEY (`order_order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `order_item_id` (`order_item_id`),
  CONSTRAINT `xref_order_order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `entity_order` (`order_id`),
  CONSTRAINT `xref_order_order_item_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `entity_order_item` (`order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_order_order_item`
--

LOCK TABLES `xref_order_order_item` WRITE;
/*!40000 ALTER TABLE `xref_order_order_item` DISABLE KEYS */;
INSERT INTO `xref_order_order_item` VALUES (1,1,1),(2,1,2),(3,1,3),(4,2,4);
/*!40000 ALTER TABLE `xref_order_order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_product_variant_product`
--

DROP TABLE IF EXISTS `xref_product_variant_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_product_variant_product` (
  `product_product_variant_id` int(10) NOT NULL AUTO_INCREMENT,
  `product_variant_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  PRIMARY KEY (`product_product_variant_id`),
  KEY `xref_product_product_variation_ibfk_1` (`product_id`),
  KEY `xref_product_product_variant_ibfk_2` (`product_variant_id`),
  CONSTRAINT `xref_product_variant_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `entity_product` (`product_id`),
  CONSTRAINT `xref_product_variant_product_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `entity_product_variant` (`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_product_variant_product`
--

LOCK TABLES `xref_product_variant_product` WRITE;
/*!40000 ALTER TABLE `xref_product_variant_product` DISABLE KEYS */;
INSERT INTO `xref_product_variant_product` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,2),(7,7,2),(8,8,2),(9,9,2),(10,10,2),(11,11,3),(12,12,3),(13,13,3),(14,14,3),(15,15,3),(16,16,4),(17,17,4),(18,18,4),(19,19,4),(20,20,5),(21,21,5),(22,22,5),(23,23,5),(24,24,6),(25,25,6),(26,26,6),(27,27,6),(28,28,6),(29,29,6),(30,30,6),(31,31,6),(32,32,7),(33,33,7),(34,34,7),(35,35,7),(36,36,7),(37,37,7),(38,38,7),(39,39,7),(40,40,8),(41,41,9),(42,42,9),(43,43,9),(44,44,9),(45,45,10),(46,46,10),(47,47,10),(48,48,10),(49,49,10),(50,50,11);
/*!40000 ALTER TABLE `xref_product_variant_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xref_user_order`
--

DROP TABLE IF EXISTS `xref_user_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xref_user_order` (
  `user_order_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  PRIMARY KEY (`user_order_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `xref_user_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `entity_user` (`user_id`),
  CONSTRAINT `xref_user_order_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `entity_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xref_user_order`
--

LOCK TABLES `xref_user_order` WRITE;
/*!40000 ALTER TABLE `xref_user_order` DISABLE KEYS */;
INSERT INTO `xref_user_order` VALUES (1,2,1),(2,2,2);
/*!40000 ALTER TABLE `xref_user_order` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-02  0:49:45
