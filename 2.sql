-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: localhost    Database: test_samson
-- ------------------------------------------------------
-- Server version	5.7.36-log

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
-- Table structure for table `a_category`
--

DROP TABLE IF EXISTS `a_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `a_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `parent_for_category_idx` (`parent_id`),
  CONSTRAINT `parent_for_category` FOREIGN KEY (`parent_id`) REFERENCES `a_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `a_category`
--

LOCK TABLES `a_category` WRITE;
/*!40000 ALTER TABLE `a_category` DISABLE KEYS */;
INSERT INTO `a_category` VALUES (78,'','Ð‘ÑƒÐ¼Ð°Ð³Ð°',NULL),(79,'','ÐŸÑ€Ð¸Ð½Ñ‚ÐµÑ€Ñ‹',NULL),(80,'','ÐœÐ¤Ð£',NULL);
/*!40000 ALTER TABLE `a_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `a_category_alias`
--

DROP TABLE IF EXISTS `a_category_alias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `a_category_alias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_category_multi` (`product_id`,`category_id`),
  KEY `product_category_cid_idx` (`category_id`),
  CONSTRAINT `product_category_cid` FOREIGN KEY (`category_id`) REFERENCES `a_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_category_pid` FOREIGN KEY (`product_id`) REFERENCES `a_product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `a_category_alias`
--

LOCK TABLES `a_category_alias` WRITE;
/*!40000 ALTER TABLE `a_category_alias` DISABLE KEYS */;
INSERT INTO `a_category_alias` VALUES (25,220,78),(26,221,78),(27,222,79),(28,222,80),(29,223,79),(30,223,80);
/*!40000 ALTER TABLE `a_category_alias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `a_price`
--

DROP TABLE IF EXISTS `a_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `a_price` (
  `product_id` int(11) NOT NULL,
  `price_type` varchar(45) DEFAULT NULL,
  `price` float DEFAULT NULL,
  UNIQUE KEY `price_unique` (`product_id`,`price_type`),
  CONSTRAINT `price_product_id` FOREIGN KEY (`product_id`) REFERENCES `a_product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `a_price`
--

LOCK TABLES `a_price` WRITE;
/*!40000 ALTER TABLE `a_price` DISABLE KEYS */;
INSERT INTO `a_price` VALUES (220,'Ð‘Ð°Ð·Ð¾Ð²Ð°Ñ',11.5),(220,'ÐœÐ¾ÑÐºÐ²Ð°',12.5),(221,'Ð‘Ð°Ð·Ð¾Ð²Ð°Ñ',18.5),(221,'ÐœÐ¾ÑÐºÐ²Ð°',22.5),(222,'Ð‘Ð°Ð·Ð¾Ð²Ð°Ñ',3010),(222,'ÐœÐ¾ÑÐºÐ²Ð°',3500),(223,'Ð‘Ð°Ð·Ð¾Ð²Ð°Ñ',3310),(223,'ÐœÐ¾ÑÐºÐ²Ð°',2999);
/*!40000 ALTER TABLE `a_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `a_product`
--

DROP TABLE IF EXISTS `a_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `a_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `a_product`
--

LOCK TABLES `a_product` WRITE;
/*!40000 ALTER TABLE `a_product` DISABLE KEYS */;
INSERT INTO `a_product` VALUES (220,'201','Ð‘ÑƒÐ¼Ð°Ð³Ð° Ð4'),(221,'202','Ð‘ÑƒÐ¼Ð°Ð³Ð° Ð3'),(222,'302','ÐŸÑ€Ð¸Ð½Ñ‚ÐµÑ€ Canon'),(223,'305','ÐŸÑ€Ð¸Ð½Ñ‚ÐµÑ€ HP');
/*!40000 ALTER TABLE `a_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `a_property`
--

DROP TABLE IF EXISTS `a_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `a_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `property` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_parent_idx` (`parent_id`),
  CONSTRAINT `property_parent` FOREIGN KEY (`parent_id`) REFERENCES `a_property` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `a_property`
--

LOCK TABLES `a_property` WRITE;
/*!40000 ALTER TABLE `a_property` DISABLE KEYS */;
INSERT INTO `a_property` VALUES (1,204,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','100',NULL),(2,1,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','150',NULL),(3,2,'Ð•Ð´Ð˜Ð·Ð¼','%',2),(4,205,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','90',NULL),(5,4,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','100',NULL),(6,5,'Ð•Ð´Ð˜Ð·Ð¼','%',5),(7,206,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A4',NULL),(8,7,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(9,8,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(10,207,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(11,10,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(12,208,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','100',NULL),(13,12,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','150',NULL),(14,13,'Ð•Ð´Ð˜Ð·Ð¼','%',13),(15,209,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','90',NULL),(16,15,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','100',NULL),(17,16,'Ð•Ð´Ð˜Ð·Ð¼','%',16),(18,210,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A4',NULL),(19,18,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(20,19,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(21,211,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(22,21,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(23,212,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','100',NULL),(24,23,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','150',NULL),(25,24,'Ð•Ð´Ð˜Ð·Ð¼','%',24),(26,213,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','90',NULL),(27,26,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','100',NULL),(28,27,'Ð•Ð´Ð˜Ð·Ð¼','%',27),(29,214,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A4',NULL),(30,29,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(31,30,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(32,215,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(33,32,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(34,220,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','100',NULL),(35,220,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','150',NULL),(36,220,'Ð•Ð´Ð˜Ð·Ð¼','%',35),(37,221,'ÐŸÐ»Ð¾Ñ‚Ð½Ð¾ÑÑ‚ÑŒ','90',NULL),(38,221,'Ð‘ÐµÐ»Ð¸Ð·Ð½Ð°','100',NULL),(39,221,'Ð•Ð´Ð˜Ð·Ð¼','%',38),(40,222,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A4',NULL),(41,222,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(42,222,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL),(43,223,'Ð¤Ð¾Ñ€Ð¼Ð°Ñ‚','A3',NULL),(44,223,'Ð¢Ð¸Ð¿','Ð›Ð°Ð·ÐµÑ€Ð½Ñ‹Ð¹',NULL);
/*!40000 ALTER TABLE `a_property` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-23 22:28:11
