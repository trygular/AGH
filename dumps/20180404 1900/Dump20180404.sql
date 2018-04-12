-- MySQL dump 10.13  Distrib 5.7.12, for Win32 (AMD64)
--
-- Host: 192.168.1.24    Database: CRNEWS
-- ------------------------------------------------------
-- Server version	5.1.73

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
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `mobile` int(10) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `main`
--

DROP TABLE IF EXISTS `main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(15) NOT NULL,
  `headline` varchar(100) NOT NULL DEFAULT '',
  `news` varchar(500) NOT NULL,
  `file1` varchar(200) DEFAULT NULL,
  `file2` varchar(200) DEFAULT NULL,
  `file3` varchar(200) DEFAULT NULL,
  `file4` varchar(200) DEFAULT NULL,
  `file5` varchar(200) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `user` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `main`
--

LOCK TABLES `main` WRITE;
/*!40000 ALTER TABLE `main` DISABLE KEYS */;
INSERT INTO `main` VALUES (1,'Post News','News Heading 001','News Discription\r\nFor news heading 001..',NULL,NULL,NULL,NULL,NULL,0,''),(2,'Post Image News','Im venketesh','Im venketesh','uploads/IMG-20180330-WA0000.jpg','','','','',0,''),(3,'Post News','News Heading 001','News Discription\r\nFor news heading 001..',NULL,NULL,NULL,NULL,NULL,0,''),(4,'Post Video News','Headline vid test 001','Description vid test 001','uploads/VID_20180327_170314.mp4','uploads/VID_20180327_170040.mp4','','','',0,''),(67,'Post Image News','Headline img test 001','Description for img test 001..','uploads/IMG_20180326_133042.jpg','uploads/IMG_20180326_133044.jpg','','','',0,''),(68,'Post News','123456','Hi test msg',NULL,NULL,NULL,NULL,NULL,0,''),(69,'Post Image News','Hhhhhh','Hhhhh','uploads/IMG-20180331-WA0005.jpg','','','','',0,''),(70,'Post Video News','Hhhhh','Vvvvvv','uploads/VID-20180320-WA0010.mp4','','','','',0,''),(71,'Post News','','',NULL,NULL,NULL,NULL,NULL,0,''),(72,'Post News','test q 1','test q 1',NULL,NULL,NULL,NULL,NULL,0,'3'),(73,'Post Image News','test q 2','test q 2','uploads/IMG_20180326_133042.jpg','','','','',0,'3'),(74,'Post Video News','test q 3','test q 23','uploads/VID_20180327_170040.mp4','','','','',0,'3'),(75,'Post News','news test 1','news t 1',NULL,NULL,NULL,NULL,NULL,0,'1'),(76,'Post News','Todays headlines 001','Headlines at tbdnet.',NULL,NULL,NULL,NULL,NULL,0,'4');
/*!40000 ALTER TABLE `main` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `main_type`
--

DROP TABLE IF EXISTS `main_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `main_type`
--

LOCK TABLES `main_type` WRITE;
/*!40000 ALTER TABLE `main_type` DISABLE KEYS */;
INSERT INTO `main_type` VALUES (1,'News'),(2,'Image News'),(3,'Video News');
/*!40000 ALTER TABLE `main_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp`
--

DROP TABLE IF EXISTS `otp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(10) NOT NULL,
  `message` varchar(100) DEFAULT NULL,
  `sender` varchar(15) DEFAULT NULL,
  `route` varchar(10) DEFAULT NULL,
  `unicode` varchar(15) DEFAULT NULL,
  `otp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp`
--

LOCK TABLES `otp` WRITE;
/*!40000 ALTER TABLE `otp` DISABLE KEYS */;
INSERT INTO `otp` VALUES (1,'7795727219','545013+is+your+OTP+for+registeration+at+Tarun+Bharat+NEWSCR+app.','TARUNB','4','0',545013),(2,'7899506716','519216+is+your+OTP+for+registeration+at+Tarun+Bharat+NEWSCR+app.','TARUNB','4','0',519216),(3,'8951070356','697044+is+your+OTP+for+registeration+at+Tarun+Bharat+NEWSCR+app.','TARUNB','4','0',697044);
/*!40000 ALTER TABLE `otp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL,
  `access` int(10) unsigned DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('s0ier0n2lv99v29f9d67c36vm3',1522845820,''),('jeq05ln138lo2ba9ujtp8n9hk1',1522846875,'');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-04 19:13:19
