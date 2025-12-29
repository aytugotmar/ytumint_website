-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: ytumint
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.22.04.2

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
-- Table structure for table `about_us`
--

DROP TABLE IF EXISTS `about_us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `about_us` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_us`
--

LOCK TABLES `about_us` WRITE;
/*!40000 ALTER TABLE `about_us` DISABLE KEYS */;
INSERT INTO `about_us` VALUES (1,'MİNT (Multidisipliner İnovasyon Teknolojileri Kulübü); Yıldız Teknik Üniversitesi çatısı altında, YTÜ Blockchain, Yıldız Siber ve HSD YTÜ (Huawei Student Developers) topluluklarının bilgi birikimi, üretim kültürü ve merak enerjisinin kesişiminde konumlanan; bünyesinde teknik proje ve yarışma takımı DEPTRON’u barındıran çok disiplinli bir öğrenci oluşumudur.\r\n\r\n\r\n MİNT, teknolojiyi yalnızca tüketilen bir araç olarak değil; tartışılan, üretilen ve dönüştürülen bir alan olarak ele alır. Eğitimler, atölyeler, projeler ve etkinliklerle öğrencilerin teknik yetkinliklerini geliştirirken; analitik düşünme, problem çözme ve disiplinler arası bakış açılarını da güçlendirmeyi hedefler.\r\n\r\n\r\n MİNT\'te mesele yalnızca ne öğrendiğin değil, nasıl düşündüğündür. Çünkü yenilikçi fikirler çoğu zaman tek bir disiplinin içinde değil, disiplinlerin kesiştiği alanlarda filizlenir. MİNT ve DEPTRON, bu alanları bilinçli olarak açar ve üretime dönüştürür.','uploads/about_1766931230.jpg','2025-12-28 21:38:41');
/*!40000 ALTER TABLE `about_us` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'superadmin','$2y$10$4r6TD9GhDHP5.1/WkGD51OU5RMdmokOBmXg7jP1/JSnljRnN.IYpC','admin@ytumint.org','2025-12-28 12:35:21'),(2,'nisanurkocak','$2y$10$UXSifpYRqnC/QA3ot9CPrO/O3by8ZDLkq7vwysj16vQyK7UNJ6B3q','nisanurkocak@ytumint.org','2025-12-28 21:10:14'),(3,'sudenazakkaya','$2y$10$3EFTN6YquMFV2QLPX6HeTeajFK1I92X1NBxapr839jrkqQVAVvX46','sudenazakkaya@ytumint.org','2025-12-28 21:11:14'),(4,'aytugotmar','$2y$10$o3i7L6tvwEhL6PQ5RDH.1ujZZ.cY5KYSUVWWManSDsol1J4PlG8SC','aytugotmar@ytumint.org','2025-12-28 21:11:45'),(5,'zeynepcemre','$2y$10$4zObV.M.cMbhGXchsbeGMeyMwdB.IFd9ZIdRB0/PLViocfz2qxoPu','zeynepcemre@ytumint.org','2025-12-28 21:14:29'),(6,'zehraozmen','$2y$10$VnLkNltVLeILAoSzdMTOEeE9AwVqFqrYqC3QMN2asE3J64F.UAxk.','zehraozmen@ytumint.org','2025-12-28 21:18:23');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communities`
--

DROP TABLE IF EXISTS `communities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `communities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communities`
--

LOCK TABLES `communities` WRITE;
/*!40000 ALTER TABLE `communities` DISABLE KEYS */;
INSERT INTO `communities` VALUES (5,'Blockchain','','uploads/community_1766930213_6102.png','',0,1,'2025-12-28 12:54:19','2025-12-28 13:57:14'),(6,'Depthron','','uploads/community_1766930229_8843.png','',2,1,'2025-12-28 13:57:09','2025-12-28 20:40:07'),(7,'HSD (Huawei Student Developers)','Teknoloji tutkusunu paylaşan üniversite öğrencilerine yönelik Huawei tarafından desteklenen küresel bir programdır. Türkiye genelinde teknoloji ve inovasyon eğitimleri. Cloud, AI, DevOps ve daha fazlası.\r\n\r\n- Teknoloji, yazılım, liderlik ve kariyer konularında eğitimler\r\n- Liderlik, topluluk önünde konuşma ve organizasyon deneyimi\r\n- Yazılım alanında uygulamalı bilgi ve deneyim kazanma','uploads/community_1766930249_9623.png','',3,1,'2025-12-28 13:57:29','2025-12-28 20:40:01'),(8,'YTU Siber','','uploads/community_1766930262_1601.png','',4,1,'2025-12-28 13:57:42','2025-12-28 18:15:15'),(9,'DEEPMINT','YTU MINT bünyesinde 2025 yılında faaliyete başlayan ve temel html css eğitimleri düzenleyip kulüp web sitesini tasarlayan topluluğumuz.','uploads/community_1766945767_4101.png','',1,1,'2025-12-28 18:16:07','2025-12-29 07:55:23');
/*!40000 ALTER TABLE `communities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (2,'Aytuğ Otmar','aytugotmar@ytumint.org','İlk Mesaj','Bu mesajı deneme olarak atıyorum. Siteden formu doldurup gönderilen mesajları bu şekilde okuyabilirsiniz. Hayırlı olsun şimdiden.',1,'2025-12-28 22:02:36');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (6,'MULTİDİJİTALLEŞME','&nbsp&nbsp&nbsp MİNT olarak düzenlediğimiz Multidijitalleşme zirvesi; finans, lojistik ve müzik gibi farklı sektörlerdeki dijital dönüşümün güçlü bir yansıması oldu.\r\n<br><br>\r\n&nbsp&nbsp&nbsp Teknolojiyi sadece bir araç değil, bir yaşam biçimi olarak görüyor; üniversite öğrencilerinin çoklu dijital kimlikleriyle geleceğe yön vermelerini amaçlıyoruz. \r\n<br><br>\r\n&nbsp&nbsp&nbsp  Başta Yıldız Teknik Üniversitesi olmak üzere, farklı üniversitelerin çeşitli disiplinlerinden gelen öğrencilerin ilgiyle takip ettiği oturumlarda; yapay zekâ, bulut altyapısı, üretim ve lojistik teknolojileri ile dijital içerik ve medya dünyasının dönüşümünü ele aldık. Bu geniş katılımlı ve çok yönlü yaklaşım, etkinliği klasik bir zirve olmanın ötesine taşıyarak MİNT’in teknoloji kültürünü besleyen, disiplinlerin birbirine temas ettiği kolektif bir düşünme alanına dönüştürdü.\r\n<br><br>\r\n&nbsp&nbsp&nbsp  Dijitalleşmenin her sektörün kendi dinamikleri içinde hızla şekillenen ve bugünün çalışma düzenini belirleyen temel unsur haline geldiğini net bir şekilde ortaya koyduğumuz bir etkinlik oldu.','2025-12-03','10:15:00','uploads/event_1766956974_6740.jpg',0,1,'2025-12-28 21:22:54','2025-12-28 21:36:39');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_general_ci,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','YTU MINT','2025-12-28 11:52:18'),(2,'youtube_video_id','YTZCSfBWRbo','2025-12-28 16:59:47'),(4,'twitter_url','https://x.com/ytumint','2025-12-28 14:01:34'),(5,'instagram_url','https://www.instagram.com/ytumint/','2025-12-28 14:01:34'),(6,'linkedin_url','https://www.linkedin.com/in/ytu-mint/?originalSubdomain=tr','2025-12-28 14:01:34'),(7,'tiktok_url','https://www.tiktok.com/@ytumint','2025-12-28 14:14:41'),(8,'hero_title','YTU MINT\'e Hoş Geldiniz','2025-12-28 11:52:18'),(9,'hero_subtitle','Teknoloji ve İnovasyonda Öncü Topluluk','2025-12-28 11:39:07'),(10,'hero_button_text','Keşfet','2025-12-28 11:39:07'),(14,'site_title','YTU MINT','2025-12-28 14:01:34'),(15,'site_slogan','Multidisipliner İnovasyon Teknolojileri Kulübü','2025-12-28 14:01:34'),(16,'site_description','ytu, mint, ytumint, deepmint, blockchain, siber, depthron, kulüp, öğrenci,','2025-12-28 14:01:34'),(21,'youtube_url','https://www.youtube.com/@mintkulubu','2025-12-28 14:01:34'),(22,'site_logo','uploads/logo_1766930494_mint_logo_transparent.png','2025-12-28 14:01:34'),(23,'site_favicon','uploads/favicon_1766930494_mint_logo_transparent.png','2025-12-28 14:01:34');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `member_type` enum('board','community','active') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `community_id` int DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `community_id` (`community_id`),
  CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,'Sude Naz Akkaya','Başkan','uploads/team/1766933580_sude-naz-akkaya.jpg','sudnaz24@gmail.com','https://www.linkedin.com/in/sudenazakkaya/','board',NULL,0,1,'2025-12-28 14:53:00','2025-12-28 20:01:16'),(2,'Nisanur Koçak','Başkan Yardımcısı','uploads/team/team_1766944847_2114.jpg','nisanurkocak0000@gmail.com','https://www.linkedin.com/in/nisanur-ko%C3%A7ak-9b0328285/','board',NULL,1,1,'2025-12-28 17:23:06','2025-12-28 20:07:12'),(3,'Recep Doğan','Sayman','uploads/team/team_1766945029_4474.jpg','recepdogan2003@gmail.com','https://www.linkedin.com/in/recepdo%C4%9Fan/','board',NULL,2,1,'2025-12-28 18:03:49','2025-12-28 20:08:03'),(4,'Zehra Nur Özmen','Sekreter','uploads/team/team_1766945054_4745.jpg','zehraaozmen1@gmail.com','https://www.linkedin.com/in/zehranurozmen/','board',NULL,3,1,'2025-12-28 18:04:14','2025-12-28 20:09:10'),(5,'Zeynep Cemre Çebitürk','Yönetim Kurulu Üyesi','uploads/team/team_1766945086_7982.jpg','zcemrecebiturk@gmail.com','https://www.linkedin.com/in/zeynep-cemre-%C3%A7ebit%C3%BCrk-939479316/','board',NULL,4,1,'2025-12-28 18:04:46','2025-12-28 20:09:57'),(6,'İsmail Sami Başaraner','Yönetim Kurulu Üyesi','uploads/team/team_1766945108_4212.jpg','basaraner1629@gmail.com','https://www.linkedin.com/in/ismail-sami-ba%C5%9Faraner-a7b616173/','board',NULL,5,1,'2025-12-28 18:05:08','2025-12-28 20:08:50'),(7,'Aytuğ Otmar','Ekip Lideri','uploads/team/team_1766946285_9593.png','otmaraytug@gmail.com','https://www.linkedin.com/in/aytugotmar/','community',9,0,1,'2025-12-28 18:24:45','2025-12-28 18:24:45'),(8,'İpek Argun','Ekip Üyesi','uploads/team/team_1766955540_1092.jpeg','ipekargunargun@gmail.com','https://www.linkedin.com/in/ipek-argun-3a525a280?utm_source=share_via&utm_content=profile&utm_medium=member_android','community',9,1,1,'2025-12-28 20:59:00','2025-12-28 20:59:00'),(9,'Batuhan Yılmaz','Ekip Üyesi','uploads/team/team_1766955581_8374.jpeg','batuhanyilmaz2814@outlook.com','https://www.linkedin.com/in/batuhan-y%C4%B1lmaz-5a4027324?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app','community',9,2,1,'2025-12-28 20:59:41','2025-12-28 20:59:41'),(10,'Ceren Şengüler','Ekip Üyesi','uploads/team/team_1766994791_6778.jpeg','','https://www.linkedin.com/in/ceren-%C5%9Feng%C3%BCler-7239b832a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app','community',9,3,1,'2025-12-29 07:53:11','2025-12-29 07:53:11'),(11,'Mehmet Ali Sertöz','Ekip Üyesi','uploads/team/team_1767043816_8970.jpeg','','https://www.linkedin.com/in/mehmet-ali-sert%C3%B6z-a405592b3?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app','community',9,4,1,'2025-12-29 21:30:16','2025-12-29 21:30:16');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-30  0:39:31
