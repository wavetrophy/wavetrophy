# ************************************************************
# Sequel Pro SQL dump
# Version 5438
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 8.0.12)
# Database: wave
# Generation Time: 2019-04-02 07:49:08 +0000
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



# Dump of table event
# ------------------------------------------------------------

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;

INSERT INTO `event` (`id`, `location_id`, `name`, `description`, `start`, `end`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(25,13,'Begrüssung','Die Begrüssung im Theater','2019-06-14 10:00:00','2019-06-14 10:45:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(26,13,'Auto Show','Eine Auto Show aller Autos der WAVE','2019-06-14 11:00:00','2019-06-14 11:45:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(27,13,'Lunch',NULL,'2019-06-14 12:00:00','2019-06-14 13:00:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(28,13,'Abfahrt','Abfahrt aller Autos im 2 Minuten Rhytmus','2019-06-14 14:00:00','2019-06-14 14:30:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(29,14,'Begrüssung','Die Begrüssung im Dreispitz','2019-06-14 15:00:00','2019-06-14 15:45:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(30,14,'Führung','Die Führung durch das Dreispitzareal','2019-06-14 16:00:00','2019-06-14 16:45:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(31,14,'Abendessen',NULL,'2019-06-14 18:00:00','2019-06-14 19:00:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(32,14,'Abfahrt','Abfahrt aller Autos im 2 Minuten Rhytmus','2019-06-14 20:00:00','2019-06-14 20:30:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL);

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

INSERT INTO `group` (`id`, `wave_id`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(7,4,'Gruppe 1','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(8,4,'Gruppe 2','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL);

/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hotel
# ------------------------------------------------------------

LOCK TABLES `hotel` WRITE;
/*!40000 ALTER TABLE `hotel` DISABLE KEYS */;

INSERT INTO `hotel` (`id`, `location_id`, `name`, `breakfast_included`, `last_check_in`, `comment`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(7,15,'Jugi Riehen',1,NULL,'Es hat 10 Ladestationen am Bahnhof','2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL),
	(8,16,'Trois Rois',1,NULL,'Ladestationen verfügbar','2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL);

/*!40000 ALTER TABLE `hotel` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jwt
# ------------------------------------------------------------



# Dump of table location
# ------------------------------------------------------------

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;

INSERT INTO `location` (`id`, `wave_id`, `name`, `lat`, `lon`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(13,4,'Luzern','47.03892207982464','8.318022908459511','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(14,4,'Basel','47.529612457152716','7.583999021162867','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(15,4,'Riehen','47.548154349374464','7.68454967271316','2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL),
	(16,4,'Trois Rois','47.560441018678915','7.5876688957214355','2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL);

/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lodging
# ------------------------------------------------------------

LOCK TABLES `lodging` WRITE;
/*!40000 ALTER TABLE `lodging` DISABLE KEYS */;

INSERT INTO `lodging` (`id`, `hotel_id`, `comment`)
VALUES
	(7,7,NULL),
	(8,8,'Bitte anständige Kleidung (Business Casual) anziehen');

/*!40000 ALTER TABLE `lodging` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lodging_user
# ------------------------------------------------------------

LOCK TABLES `lodging_user` WRITE;
/*!40000 ALTER TABLE `lodging_user` DISABLE KEYS */;

INSERT INTO `lodging_user` (`lodging_id`, `user_id`)
VALUES
	(7,15),
	(7,18),
	(7,19),
	(7,20),
	(7,21),
	(8,16),
	(8,17);

/*!40000 ALTER TABLE `lodging_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table media
# ------------------------------------------------------------



# Dump of table migration_versions
# ------------------------------------------------------------

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;

INSERT INTO `migration_versions` (`version`, `executed_at`)
VALUES
	('20190303113230','2019-04-02 07:35:47'),
	('20190306235802','2019-04-02 07:35:47'),
	('20190311123710','2019-04-02 07:35:47'),
	('20190314210052','2019-04-02 07:35:48'),
	('20190320125551','2019-04-02 07:35:48'),
	('20190320231407','2019-04-02 07:35:48'),
	('20190327155455','2019-04-02 07:35:48'),
	('20190327163549','2019-04-02 07:35:48'),
	('20190402073556','2019-04-02 07:41:04'),
	('20190402074156','2019-04-02 07:41:57'),
	('20190402074203','2019-04-02 07:42:05');

/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table question
# ------------------------------------------------------------



# Dump of table team
# ------------------------------------------------------------

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;

INSERT INTO `team` (`id`, `group_id`, `name`, `start_number`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(10,7,'Renault',1,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(11,7,'Pilatus',2,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL),
	(12,8,'Jura Energie',3,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL);

/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table team_has_participations
# ------------------------------------------------------------

LOCK TABLES `team_has_participations` WRITE;
/*!40000 ALTER TABLE `team_has_participations` DISABLE KEYS */;

INSERT INTO `team_has_participations` (`team_participation_id`, `team_id`)
VALUES
	(7,11),
	(8,10),
	(8,12);

/*!40000 ALTER TABLE `team_has_participations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table team_participation
# ------------------------------------------------------------

LOCK TABLES `team_participation` WRITE;
/*!40000 ALTER TABLE `team_participation` DISABLE KEYS */;

INSERT INTO `team_participation` (`id`, `location_id`, `arrival`, `departure`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(7,14,'2019-06-14 15:15:00','2019-06-14 20:15:00','2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL),
	(8,13,'2019-06-14 10:15:00','2019-06-14 14:15:00','2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL);

/*!40000 ALTER TABLE `team_participation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `team_id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `first_name`, `last_name`, `has_received_welcome_email`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `has_received_setup_app_email`, `must_reset_password`, `media_id`)
VALUES
	(15,NULL,'bjoern','bjoern','bjoern','bjoern',1,NULL,'$2y$13$DfRhBK.igpPBYBjOFnqk.O.63dKzigzX8yuIbX.5hFiZee5i7Fb6m',NULL,NULL,'2019-04-02 07:42:05','a:2:{i:0;s:10:\"ROLE_ADMIN\";i:1;s:16:\"ROLE_SUPER_ADMIN\";}','Björn','Pfoster',1,'2019-04-02 07:42:05','anonymous','2019-04-02 07:42:05','anonymous',NULL,0,1,NULL),
	(16,10,'bjoern@pfoster.ch','bjoern@pfoster.ch','bjoern@pfoster.ch','bjoern@pfoster.ch',1,NULL,'$2y$13$9Ago6HtdaP7ZrtgQVJwXj.ARNV3dfBmfYXtI7so9WQ57YAfyWQOYG',NULL,NULL,'2019-04-02 07:42:06','a:0:{}','Lorenz','Camenisch',0,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL,0,1,NULL),
	(17,10,'remo@example.com','remo@example.com','remo@example.com','remo@example.com',1,NULL,'$2y$13$K4kbjrmgr8g0NHJXGnIqzeGWcgAbh.NxeT7EMT7iH9IkA/L6Nyvoy',NULL,NULL,'2019-04-02 07:42:06','a:0:{}','Remo','Camenisch',1,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL,0,1,NULL),
	(18,11,'andy@example.com','andy@example.com','andy@example.com','andy@example.com',1,NULL,'$2y$13$qtOSkolFizQSklbRMS/X0OvMg4/llhGgFzqDi0B8zWYnQKvbQBnnu',NULL,NULL,'2019-04-02 07:42:06','a:0:{}','Andy','Alig',1,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL,0,1,NULL),
	(19,11,'edy@example.com','edy@example.com','edy@example.com','edy@example.com',1,NULL,'$2y$13$Kwklr5jAANFsxm7v1gT6MeLByVONMHZyOcu.s1ySHCOdlhGzVyTKa',NULL,NULL,'2019-04-02 07:42:06','a:0:{}','Edy','Künzli',1,'2019-04-02 07:42:07','anonymous','2019-04-02 07:42:07','anonymous',NULL,0,1,NULL),
	(20,12,'jean@example.com','jean@example.com','jean@example.com','jean@example.com',1,NULL,'$2y$13$.AMK/khYARAep0yt0XrBxOXISzOJl/KLr9jwkyLucI0eH2gGnmdy.',NULL,NULL,'2019-04-02 07:42:06','a:0:{}','Jean','Oppliger',1,'2019-04-02 07:42:07','anonymous','2019-04-02 07:42:07','anonymous',NULL,0,1,NULL),
	(21,12,'esther@example.com','esther@example.com','esther@example.com','esther@example.com',1,NULL,'$2y$13$BxGgguiT66ZxBFbk2ZLS9OiPu7LTBVT/5r4uqYrfnYHatu.ctyVyS',NULL,NULL,'2019-04-02 07:42:06','a:0:{}','Esther','Oppliger',1,'2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL,0,1,NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_email
# ------------------------------------------------------------

LOCK TABLES `user_email` WRITE;
/*!40000 ALTER TABLE `user_email` DISABLE KEYS */;

INSERT INTO `user_email` (`id`, `user_id`, `email`, `is_public`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `confirmed`, `confirmation_token`)
VALUES
	(15,15,'bjoern',1,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL,0,NULL),
	(16,16,'bjoern@pfoster.ch',0,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL,0,NULL),
	(17,17,'remo@example.com',0,'2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL,0,NULL),
	(18,18,'andy@example.com',0,'2019-04-02 07:42:07','anonymous','2019-04-02 07:42:07','anonymous',NULL,0,NULL),
	(19,19,'edy@example.com',0,'2019-04-02 07:42:07','anonymous','2019-04-02 07:42:07','anonymous',NULL,0,NULL),
	(20,20,'jean@example.com',0,'2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL,0,NULL),
	(21,21,'esther@example.com',0,'2019-04-02 07:42:08','anonymous','2019-04-02 07:42:08','anonymous',NULL,0,NULL);

/*!40000 ALTER TABLE `user_email` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_phonenumber
# ------------------------------------------------------------



# Dump of table wave
# ------------------------------------------------------------

LOCK TABLES `wave` WRITE;
/*!40000 ALTER TABLE `wave` DISABLE KEYS */;

INSERT INTO `wave` (`id`, `name`, `country`, `start`, `end`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`)
VALUES
	(4,'WAVE Switzerland','Switzerland','2019-06-14 10:00:00','2019-06-22 18:00:00','2019-04-02 07:42:06','anonymous','2019-04-02 07:42:06','anonymous',NULL);

/*!40000 ALTER TABLE `wave` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
