-- MySQL dump 10.13  Distrib 8.0.20, for Win64 (x86_64)
--
-- Host: localhost    Database: omruilservice
-- ------------------------------------------------------
-- Server version	8.0.20

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
-- Table structure for table `events`
--

CREATE DATABASE IF NOT EXISTS `omruilservice` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `omruilservice`;

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `eventID` int NOT NULL AUTO_INCREMENT,
  `eventName` varchar(45) DEFAULT NULL,
  `standaardPrijsTicket` int NOT NULL,
  `startTime` varchar(20) DEFAULT NULL,
  `endTime` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `locatie` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`eventID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Tomorrowland',200,'25-07-2020','26-07-2020','Een festival in Boom','Boom');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sellers` (
  `sellerID` int NOT NULL AUTO_INCREMENT,
  `sellerName` varchar(20) DEFAULT NULL,
  `sellerMail` varchar(40) DEFAULT NULL,
  `sellerPassword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sellerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sellers`
--

LOCK TABLES `sellers` WRITE;
/*!40000 ALTER TABLE `sellers` DISABLE KEYS */;
/*!40000 ALTER TABLE `sellers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `ticketID` int NOT NULL AUTO_INCREMENT,
  `ticketName` varchar(50) DEFAULT NULL,
  `ticketPrice` int DEFAULT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `events_eventID` int NOT NULL,
  `soort` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ticketID`),
  KEY `fk_tickets_events1_idx` (`events_eventID`),
  CONSTRAINT `fk_tickets_events1` FOREIGN KEY (`events_eventID`) REFERENCES `events` (`eventID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,'dagticket Tomorrowland',200,'Corona',1,'dagticket'),(3,'combiticket Tomorrowland',250,'Corona',1,'combiticket'),(4,'campingticket Tomorrowland',250,'Corona',1,'camping');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets_has_sellers`
--

DROP TABLE IF EXISTS `tickets_has_sellers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets_has_sellers` (
  `tickets_ticketID` int NOT NULL,
  `sellers_sellerID` int NOT NULL,
  PRIMARY KEY (`tickets_ticketID`,`sellers_sellerID`),
  KEY `fk_tickets_has_sellers_sellers1_idx` (`sellers_sellerID`),
  KEY `fk_tickets_has_sellers_tickets1_idx` (`tickets_ticketID`),
  CONSTRAINT `fk_tickets_has_sellers_sellers1` FOREIGN KEY (`sellers_sellerID`) REFERENCES `sellers` (`sellerID`),
  CONSTRAINT `fk_tickets_has_sellers_tickets1` FOREIGN KEY (`tickets_ticketID`) REFERENCES `tickets` (`ticketID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets_has_sellers`
--

LOCK TABLES `tickets_has_sellers` WRITE;
/*!40000 ALTER TABLE `tickets_has_sellers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tickets_has_sellers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-29 17:11:12
