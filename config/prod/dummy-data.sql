# ************************************************************
# Sequel Pro SQL dump
# Version 5438
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 8.0.12)
# Database: wave
# Generation Time: 2019-05-02 17:26:05 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table answer
# ------------------------------------------------------------

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;

INSERT INTO `answer` (`id`, `question_id`, `created_by`, `updated_by`, `answer`, `approved`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,3,1,'At the train station near the center. There are some chargers near the exit. But its very costly',1,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(2,1,4,1,'Somewhere',0,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(3,2,3,1,'At the train station near the center. There are some chargers near the exit. But its very costly',0,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(4,2,4,1,'Somewhere else',0,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table event
# ------------------------------------------------------------

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;

INSERT INTO `event` (`id`, `location_id`, `created_by`, `updated_by`, `name`, `description`, `start`, `end`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,1,1,'Begrüssung','Die Begrüssung im Theater','2019-06-14 10:00:00','2019-06-14 10:45:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(2,1,1,1,'Auto Show','Eine Auto Show aller Autos der WAVE','2019-06-14 11:00:00','2019-06-14 11:45:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(3,1,1,1,'Lunch',NULL,'2019-06-14 12:00:00','2019-06-14 13:00:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(4,1,1,1,'Abfahrt','Abfahrt aller Autos im 2 Minuten Rhytmus','2019-06-14 14:00:00','2019-06-14 14:30:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(5,2,1,1,'Begrüssung','Die Begrüssung im Dreispitz','2019-06-14 15:00:00','2019-06-14 15:45:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(6,2,1,1,'Führung','Die Führung durch das Dreispitzareal','2019-06-14 16:00:00','2019-06-14 16:45:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(7,2,1,1,'Abendessen',NULL,'2019-06-14 18:00:00','2019-06-14 19:00:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(8,2,1,1,'Abfahrt','Abfahrt aller Autos im 2 Minuten Rhytmus','2019-06-14 20:00:00','2019-06-14 20:30:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL);

/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ext_log_entries
# ------------------------------------------------------------



# Dump of table ext_translations
# ------------------------------------------------------------



# Dump of table group
# ------------------------------------------------------------

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;

INSERT INTO `group` (`id`, `wave_id`, `created_by`, `updated_by`, `name`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,1,1,'Gruppe 1','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(2,1,1,1,'Gruppe 2','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL);

/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hotel
# ------------------------------------------------------------

LOCK TABLES `hotel` WRITE;
/*!40000 ALTER TABLE `hotel` DISABLE KEYS */;

INSERT INTO `hotel` (`id`, `location_id`, `created_by`, `updated_by`, `name`, `breakfast_included`, `last_check_in`, `comment`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,3,1,1,'Jugi Riehen',1,NULL,'Es hat 10 Ladestationen am Bahnhof','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(2,4,1,1,'Trois Rois',1,NULL,'Ladestationen verfügbar','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `hotel` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jwt
# ------------------------------------------------------------



# Dump of table location
# ------------------------------------------------------------

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;

INSERT INTO `location` (`id`, `wave_id`, `created_by`, `updated_by`, `name`, `lat`, `lon`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,1,1,'Luzern','47.03892207982464','8.318022908459511','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(2,1,1,1,'Basel','47.529612457152716','7.583999021162867','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(3,1,1,1,'Riehen','47.548154349374464','7.68454967271316','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(4,1,1,1,'Trois Rois','47.560441018678915','7.5876688957214355','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lodging
# ------------------------------------------------------------

LOCK TABLES `lodging` WRITE;
/*!40000 ALTER TABLE `lodging` DISABLE KEYS */;

INSERT INTO `lodging` (`id`, `hotel_id`, `comment`)
VALUES
(1,1,NULL),
(2,2,'Bitte anständige Kleidung (Business Casual) anziehen');

/*!40000 ALTER TABLE `lodging` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lodging_user
# ------------------------------------------------------------

LOCK TABLES `lodging_user` WRITE;
/*!40000 ALTER TABLE `lodging_user` DISABLE KEYS */;

INSERT INTO `lodging_user` (`lodging_id`, `user_id`)
VALUES
(1,1),
(1,4),
(1,5),
(1,6),
(1,7),
(2,2),
(2,3);

/*!40000 ALTER TABLE `lodging_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table media
# ------------------------------------------------------------

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;

INSERT INTO `media` (`id`, `name`, `path`, `url`)
VALUES
(1,'5ccb27d89b5438.95809913.svg','/media/2019/05/02','/media/2019/05/02/5ccb27d89b5438.95809913.svg'),
(2,'5ccb27daf1d086.15435643.svg','/media/2019/05/02','/media/2019/05/02/5ccb27daf1d086.15435643.svg'),
(3,'5ccb27daf308e7.49991381.svg','/media/2019/05/02','/media/2019/05/02/5ccb27daf308e7.49991381.svg'),
(4,'5ccb27db000961.64584529.svg','/media/2019/05/02','/media/2019/05/02/5ccb27db000961.64584529.svg'),
(5,'5ccb27db013216.49021381.svg','/media/2019/05/02','/media/2019/05/02/5ccb27db013216.49021381.svg'),
(6,'5ccb27db025804.46693592.svg','/media/2019/05/02','/media/2019/05/02/5ccb27db025804.46693592.svg'),
(7,'5ccb27db037e86.21100378.svg','/media/2019/05/02','/media/2019/05/02/5ccb27db037e86.21100378.svg');

/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table question
# ------------------------------------------------------------

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;

INSERT INTO `question` (`id`, `group_id`, `user_id`, `created_by`, `updated_by`, `title`, `question`, `resolved`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,2,2,2,'Where to park in lucerne','I just arrived in lucerne. Where do i park?',1,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(2,1,2,2,2,'Where to park in basel','I just arrived in basel. Where do i park?',0,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table team
# ------------------------------------------------------------

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;

INSERT INTO `team` (`id`, `group_id`, `created_by`, `updated_by`, `name`, `start_number`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,1,1,'Renault',1,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(2,1,1,1,'Pilatus',2,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(3,2,1,1,'Jura Energie',3,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL);

/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table team_has_participations
# ------------------------------------------------------------

LOCK TABLES `team_has_participations` WRITE;
/*!40000 ALTER TABLE `team_has_participations` DISABLE KEYS */;

INSERT INTO `team_has_participations` (`team_participation_id`, `team_id`)
VALUES
(1,2),
(2,1),
(2,3);

/*!40000 ALTER TABLE `team_has_participations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table team_participation
# ------------------------------------------------------------

LOCK TABLES `team_participation` WRITE;
/*!40000 ALTER TABLE `team_participation` DISABLE KEYS */;

INSERT INTO `team_participation` (`id`, `location_id`, `created_by`, `updated_by`, `arrival`, `departure`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,2,1,1,'2019-06-14 15:15:00','2019-06-14 20:15:00','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(2,1,1,1,'2019-06-14 10:15:00','2019-06-14 14:15:00','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `team_participation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `media_id`, `team_id`, `created_by`, `updated_by`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `first_name`, `last_name`, `has_received_welcome_email`, `has_received_setup_app_email`, `must_reset_password`, `locale`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,NULL,1,NULL,'bjoern','bjoern','bjoern','bjoern',1,NULL,'$2y$13$422dD9/z8JvkYFfthr59Y.K11WcYKxcryTKlb6Pmuel6vt8i19.gW',NULL,NULL,'2019-05-02 17:24:40','a:2:{i:0;s:10:\"ROLE_ADMIN\";i:1;s:16:\"ROLE_SUPER_ADMIN\";}','Björn','Pfoster',1,0,1,'en_GB','2019-05-02 17:24:40','2019-05-02 17:24:42',NULL),
(2,3,1,1,1,'lorenz.camenisch','lorenz.camenisch','bjoern@pfoster.ch','bjoern@pfoster.ch',1,NULL,'$2y$13$ohUsmvNQNwpOowF7N8yW/OVr3sza2cPMXC96.ERWj9QRDpKHLo.B2',NULL,NULL,'2019-05-02 17:24:40','a:0:{}','Lorenz','Camenisch',0,0,1,'en_GB','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(3,2,1,1,1,'remo.camenisch','remo.camenisch','remo@example.com','remo@example.com',1,NULL,'$2y$13$uxyHUEexLJ6E..IDk.Z3y.mfKajow3lhR9Rz9rh7S.qxi2QxCpTFW',NULL,NULL,'2019-05-02 17:24:40','a:0:{}','Remo','Camenisch',1,0,1,'en_GB','2019-05-02 17:24:41','2019-05-02 17:24:41',NULL),
(4,4,2,1,1,'andy.alig','andy.alig','andy@example.com','andy@example.com',1,NULL,'$2y$13$6BDJV3kXZAtSRqk7j5y/u..0kyWEcC4NzGVuxhiUMfCU3B0Fdhfo6',NULL,NULL,'2019-05-02 17:24:40','a:0:{}','Andy','Alig',1,0,1,'en_GB','2019-05-02 17:24:41','2019-05-02 17:24:41',NULL),
(5,5,2,1,1,'edy.künzli','edy.künzli','edy@example.com','edy@example.com',1,NULL,'$2y$13$uLzRc5/Rm01eT2SIKEYBieGBbMIT3I4zvpsHIlbWRwS6uwCh24sjC',NULL,NULL,'2019-05-02 17:24:40','a:0:{}','Edy','Künzli',1,0,1,'en_GB','2019-05-02 17:24:41','2019-05-02 17:24:41',NULL),
(6,6,3,1,1,'jean.oppliger','jean.oppliger','jean@example.com','jean@example.com',1,NULL,'$2y$13$ffGz5xigIBsyGVaNN0gAa.WCARl9bXFqDhi/aVpXMdaeBjzLGLcai',NULL,NULL,'2019-05-02 17:24:40','a:0:{}','Jean','Oppliger',1,0,1,'en_GB','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(7,7,3,1,1,'esther.oppliger','esther.oppliger','esther@example.com','esther@example.com',1,NULL,'$2y$13$4XjVedew6MLrqxhv/SLvoeBw1k9.pY7dbtNmEnJuKWkY1/R/bCzKe',NULL,NULL,'2019-05-02 17:24:40','a:0:{}','Esther','Oppliger',1,0,1,'en_GB','2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_email
# ------------------------------------------------------------

LOCK TABLES `user_email` WRITE;
/*!40000 ALTER TABLE `user_email` DISABLE KEYS */;

INSERT INTO `user_email` (`id`, `user_id`, `created_by`, `updated_by`, `email`, `is_public`, `confirmed`, `confirmation_token`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,1,1,'bjoern',0,0,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(2,1,1,1,'bjoern',1,0,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(3,3,3,3,'remo1@cc.cc',1,1,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(4,3,3,3,'remo2@cc.cc',1,1,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(5,3,3,3,'remo3@cc.cc',1,1,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(6,3,3,3,'remo4@cc.cc',1,1,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(7,3,3,3,'remo5@cc.cc',1,1,NULL,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(8,2,2,2,'bjoern@pfoster.ch',0,0,NULL,'2019-05-02 17:24:41','2019-05-02 17:24:41',NULL),
(9,3,3,3,'remo@example.com',0,0,NULL,'2019-05-02 17:24:41','2019-05-02 17:24:41',NULL),
(10,4,4,4,'andy@example.com',0,0,NULL,'2019-05-02 17:24:41','2019-05-02 17:24:41',NULL),
(11,5,5,5,'edy@example.com',0,0,NULL,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(12,6,6,6,'jean@example.com',0,0,NULL,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL),
(13,7,7,7,'esther@example.com',0,0,NULL,'2019-05-02 17:24:42','2019-05-02 17:24:42',NULL);

/*!40000 ALTER TABLE `user_email` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_phonenumber
# ------------------------------------------------------------

LOCK TABLES `user_phonenumber` WRITE;
/*!40000 ALTER TABLE `user_phonenumber` DISABLE KEYS */;

INSERT INTO `user_phonenumber` (`id`, `user_id`, `created_by`, `updated_by`, `phonenumber`, `country_code`, `is_public`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,3,3,3,'761234567','+41',1,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(2,3,3,3,'762234567','+41',1,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(3,3,3,3,'763234567','+41',1,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(4,3,3,3,'764234567','+41',1,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL),
(5,3,3,3,'765234567','+41',1,'2019-05-02 17:24:40','2019-05-02 17:24:40',NULL);

/*!40000 ALTER TABLE `user_phonenumber` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wave
# ------------------------------------------------------------

LOCK TABLES `wave` WRITE;
/*!40000 ALTER TABLE `wave` DISABLE KEYS */;

INSERT INTO `wave` (`id`, `created_by`, `updated_by`, `name`, `country`, `start`, `end`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(1,1,1,'WAVE Switzerland','Switzerland','2019-06-14 10:00:00','2019-06-22 18:00:00','2019-05-02 17:24:40','2019-05-02 17:24:40',NULL);

/*!40000 ALTER TABLE `wave` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
