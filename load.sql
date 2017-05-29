-- MySQL dump 10.16  Distrib 10.1.9-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: mfa
-- ------------------------------------------------------
-- Server version	10.1.9-MariaDB

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
-- Table structure for table `analysis`
--

DROP TABLE IF EXISTS `analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `analysis_option` int(10) unsigned NOT NULL,
  `case_study` int(10) unsigned NOT NULL,
  `result` decimal(15,2) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `option` (`analysis_option`),
  KEY `case` (`case_study`),
  CONSTRAINT `analysis_ibfk_1` FOREIGN KEY (`analysis_option`) REFERENCES `analysis_options` (`id`),
  CONSTRAINT `analysis_ibfk_2` FOREIGN KEY (`case_study`) REFERENCES `case_studies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analysis_options`
--

DROP TABLE IF EXISTS `analysis_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysis_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `measure` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  CONSTRAINT `analysis_options_ibfk_1` FOREIGN KEY (`type`) REFERENCES `analysis_options_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analysis_options_types`
--

DROP TABLE IF EXISTS `analysis_options_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysis_options_types` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `case_studies`
--

DROP TABLE IF EXISTS `case_studies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_studies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paper` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paper` (`paper`),
  CONSTRAINT `case_studies_ibfk_1` FOREIGN KEY (`paper`) REFERENCES `papers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dqi_classifications`
--

DROP TABLE IF EXISTS `dqi_classifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dqi_classifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` int(10) unsigned NOT NULL,
  `score` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section` (`section`),
  CONSTRAINT `dqi_classifications_ibfk_2` FOREIGN KEY (`section`) REFERENCES `dqi_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dqi_sections`
--

DROP TABLE IF EXISTS `dqi_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dqi_sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `dqi_sections_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keywords` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1083 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `keywords_papers`
--

DROP TABLE IF EXISTS `keywords_papers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keywords_papers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` smallint(5) unsigned NOT NULL,
  `paper` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paper` (`paper`),
  KEY `keyword` (`keyword`),
  CONSTRAINT `keywords_papers_ibfk_1` FOREIGN KEY (`keyword`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keywords_papers_ibfk_2` FOREIGN KEY (`paper`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2147 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_activities`
--

DROP TABLE IF EXISTS `mfa_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_activities_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_activities_log`
--

DROP TABLE IF EXISTS `mfa_activities_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_activities_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `time` smallint(5) unsigned NOT NULL,
  `source` int(10) unsigned DEFAULT NULL,
  `contact` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `contact` (`contact`),
  KEY `source` (`source`),
  CONSTRAINT `mfa_activities_log_ibfk_5` FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_activities_log_ibfk_6` FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_activities_log_ibfk_7` FOREIGN KEY (`activity`) REFERENCES `mfa_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2813 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`paulmysql`@`localhost`*/ /*!50003 TRIGGER `mfa_activities_log_bu` BEFORE UPDATE ON `mfa_activities_log` FOR EACH ROW
BEGIN
      SET NEW.time = (UNIX_TIMESTAMP(NEW.end)-UNIX_TIMESTAMP(NEW.start))/60;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `mfa_contacts`
--

DROP TABLE IF EXISTS `mfa_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(3) unsigned DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `organization` tinyint(1) unsigned NOT NULL COMMENT 'Whether or not this contact is an organization (if false, this is a person)',
  `works_for_referral_organization` tinyint(1) unsigned NOT NULL COMMENT 'Whether or not this contact (person) works for the referral organization (rather than just a contact of the organization)',
  `employer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Name of the employer; this clearly only applies to people, not to organizations',
  `belongs_to` int(10) unsigned DEFAULT NULL COMMENT 'This indicates if this contact is part of (e.g. employee of or sub division of) another contact',
  `industry` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `specialty` int(10) unsigned NOT NULL,
  `nature` tinyint(3) unsigned NOT NULL,
  `success` tinyint(3) unsigned NOT NULL,
  `assist` tinyint(1) unsigned NOT NULL,
  `folder` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `dataset` (`dataset`),
  KEY `employer` (`employer`),
  KEY `status` (`status`),
  KEY `belongs_to` (`belongs_to`),
  KEY `industry` (`industry`),
  CONSTRAINT `mfa_contacts_ibfk_4` FOREIGN KEY (`type`) REFERENCES `mfa_contacts_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_contacts_ibfk_5` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_contacts_ibfk_6` FOREIGN KEY (`status`) REFERENCES `mfa_status_options` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mfa_contacts_ibfk_8` FOREIGN KEY (`industry`) REFERENCES `mfa_industries` (`id`),
  CONSTRAINT `mfa_contacts_ibfk_9` FOREIGN KEY (`belongs_to`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=795 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_contacts_flags`
--

DROP TABLE IF EXISTS `mfa_contacts_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_contacts_flags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact` int(10) unsigned NOT NULL,
  `flag` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `flag` (`flag`),
  KEY `contact` (`contact`),
  CONSTRAINT `mfa_contacts_flags_ibfk_3` FOREIGN KEY (`flag`) REFERENCES `mfa_special_flags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_contacts_flags_ibfk_4` FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=343 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_contacts_types`
--

DROP TABLE IF EXISTS `mfa_contacts_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_contacts_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_contacts_types_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_data`
--

DROP TABLE IF EXISTS `mfa_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `material` mediumint(8) unsigned NOT NULL,
  `year` year(4) NOT NULL,
  `data` decimal(12,4) unsigned NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_id` int(10) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scale` int(10) unsigned DEFAULT NULL,
  `multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1.0000',
  `include_in_totals` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `material` (`material`),
  KEY `scale` (`scale`),
  KEY `source_id` (`source_id`),
  CONSTRAINT `mfa_data_ibfk_2` FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_data_ibfk_5` FOREIGN KEY (`source_id`) REFERENCES `mfa_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `mfa_data_ibfk_6` FOREIGN KEY (`scale`) REFERENCES `mfa_scales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18426 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_dataset`
--

DROP TABLE IF EXISTS `mfa_dataset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_dataset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `research_project` int(10) unsigned DEFAULT NULL,
  `source_paper` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year_start` year(4) NOT NULL,
  `year_end` year(4) NOT NULL,
  `access` enum('public','private','link_only') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'private',
  `decimal_precision` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `measurement` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'kg',
  `contact_management` tinyint(1) unsigned NOT NULL,
  `resource_management` tinyint(1) unsigned NOT NULL COMMENT 'Elaborate options to track asociated resources and material flows',
  `dqi` tinyint(1) unsigned NOT NULL,
  `time_log` tinyint(1) unsigned NOT NULL,
  `multiscale` tinyint(1) unsigned NOT NULL COMMENT 'Indicates whether or not to include several scales',
  `multiscale_as_proxy` tinyint(1) unsigned NOT NULL COMMENT 'If several scales are used, this indicates if a multiplier system should be used (otherwise this is a comparison between scales instead)',
  `type` tinyint(3) unsigned NOT NULL,
  `banner_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `multiple_values` enum('calculate_average','do_not_allow','select_best') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'calculate_average',
  PRIMARY KEY (`id`),
  KEY `research_project` (`research_project`),
  KEY `access` (`access`),
  KEY `type` (`type`),
  KEY `source_paper` (`source_paper`),
  CONSTRAINT `mfa_dataset_ibfk_2` FOREIGN KEY (`research_project`) REFERENCES `research` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_dataset_ibfk_3` FOREIGN KEY (`type`) REFERENCES `mfa_dataset_types` (`id`),
  CONSTRAINT `mfa_dataset_ibfk_4` FOREIGN KEY (`source_paper`) REFERENCES `papers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_dataset_types`
--

DROP TABLE IF EXISTS `mfa_dataset_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_dataset_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_dqi`
--

DROP TABLE IF EXISTS `mfa_dqi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_dqi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` int(10) unsigned NOT NULL,
  `classification` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classification` (`classification`),
  KEY `data` (`data`),
  CONSTRAINT `mfa_dqi_ibfk_3` FOREIGN KEY (`data`) REFERENCES `mfa_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_dqi_ibfk_4` FOREIGN KEY (`classification`) REFERENCES `dqi_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9017 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_files`
--

DROP TABLE IF EXISTS `mfa_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `source` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  KEY `source` (`source`),
  CONSTRAINT `mfa_files_ibfk_2` FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_files_ibfk_3` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=390 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_groups`
--

DROP TABLE IF EXISTS `mfa_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_groups_ibfk_4` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_indicators`
--

DROP TABLE IF EXISTS `mfa_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_indicators` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `more_information` int(11) DEFAULT NULL,
  `dataset` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `more_information` (`more_information`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_indicators_ibfk_1` FOREIGN KEY (`type`) REFERENCES `mfa_indicators_types` (`id`),
  CONSTRAINT `mfa_indicators_ibfk_2` FOREIGN KEY (`more_information`) REFERENCES `papers` (`id`),
  CONSTRAINT `mfa_indicators_ibfk_4` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_indicators_formula`
--

DROP TABLE IF EXISTS `mfa_indicators_formula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_indicators_formula` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator` tinyint(3) unsigned NOT NULL,
  `type` enum('add','subtract') COLLATE utf8_unicode_ci NOT NULL,
  `mfa_group` mediumint(8) unsigned NOT NULL,
  `mfa_material` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mfa_group` (`mfa_group`),
  KEY `indicator` (`indicator`),
  KEY `mfa_material` (`mfa_material`),
  CONSTRAINT `mfa_indicators_formula_ibfk_3` FOREIGN KEY (`mfa_group`) REFERENCES `mfa_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_indicators_formula_ibfk_4` FOREIGN KEY (`indicator`) REFERENCES `mfa_indicators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_indicators_formula_ibfk_5` FOREIGN KEY (`mfa_material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_indicators_types`
--

DROP TABLE IF EXISTS `mfa_indicators_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_indicators_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_industries`
--

DROP TABLE IF EXISTS `mfa_industries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_industries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `indicator_weight` tinyint(1) unsigned DEFAULT NULL,
  `indicator_value` tinyint(1) unsigned DEFAULT NULL,
  `indicator_environment` tinyint(1) unsigned DEFAULT NULL,
  `indicator_companies` tinyint(1) unsigned DEFAULT NULL,
  `indicator_illegality` tinyint(1) unsigned DEFAULT NULL,
  `description_companies` text COLLATE utf8_unicode_ci,
  `description_illegality` text COLLATE utf8_unicode_ci,
  `description_associations` text COLLATE utf8_unicode_ci,
  `description_general` text COLLATE utf8_unicode_ci,
  `sector` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  KEY `sector` (`sector`),
  CONSTRAINT `mfa_industries_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`),
  CONSTRAINT `mfa_industries_ibfk_2` FOREIGN KEY (`sector`) REFERENCES `mfa_industries_sectors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_industries_labels`
--

DROP TABLE IF EXISTS `mfa_industries_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_industries_labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('value','mass') COLLATE utf8_unicode_ci NOT NULL,
  `score` tinyint(1) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_industries_labels_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_industries_scores`
--

DROP TABLE IF EXISTS `mfa_industries_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_industries_scores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('mass','value') COLLATE utf8_unicode_ci NOT NULL,
  `flow` enum('extraction','import','export','output') COLLATE utf8_unicode_ci NOT NULL,
  `industry` int(10) unsigned NOT NULL,
  `score` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `industry` (`industry`),
  CONSTRAINT `mfa_industries_scores_ibfk_2` FOREIGN KEY (`industry`) REFERENCES `mfa_industries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_industries_sectors`
--

DROP TABLE IF EXISTS `mfa_industries_sectors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_industries_sectors` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_leads`
--

DROP TABLE IF EXISTS `mfa_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_leads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_contact` int(10) unsigned DEFAULT NULL,
  `from_source` int(10) unsigned DEFAULT NULL,
  `to_contact` int(10) unsigned DEFAULT NULL,
  `to_source` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `from_contact` (`from_contact`),
  KEY `from_source` (`from_source`),
  KEY `to_source` (`to_source`),
  KEY `to_contact` (`to_contact`),
  CONSTRAINT `mfa_leads_ibfk_5` FOREIGN KEY (`to_source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_leads_ibfk_6` FOREIGN KEY (`to_contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_leads_ibfk_7` FOREIGN KEY (`from_source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_leads_ibfk_8` FOREIGN KEY (`from_contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_material_links`
--

DROP TABLE IF EXISTS `mfa_material_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_material_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source` int(10) unsigned DEFAULT NULL,
  `contact` int(10) unsigned DEFAULT NULL,
  `material` mediumint(8) unsigned DEFAULT NULL,
  `mfa_group` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `contact` (`contact`),
  KEY `material` (`material`),
  KEY `mfa_group` (`mfa_group`),
  CONSTRAINT `mfa_material_links_ibfk_5` FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_material_links_ibfk_6` FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_material_links_ibfk_7` FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_material_links_ibfk_8` FOREIGN KEY (`mfa_group`) REFERENCES `mfa_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=531 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_materials`
--

DROP TABLE IF EXISTS `mfa_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_materials` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mfa_group` mediumint(8) unsigned NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`mfa_group`),
  CONSTRAINT `mfa_materials_ibfk_2` FOREIGN KEY (`mfa_group`) REFERENCES `mfa_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12052 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_materials_notes`
--

DROP TABLE IF EXISTS `mfa_materials_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_materials_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `user` int(11) NOT NULL,
  `source` int(10) unsigned DEFAULT NULL,
  `contact` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material` (`material`),
  KEY `user` (`user`),
  KEY `source` (`source`),
  KEY `contact` (`contact`),
  CONSTRAINT `mfa_materials_notes_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`user_id`),
  CONSTRAINT `mfa_materials_notes_ibfk_3` FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mfa_materials_notes_ibfk_4` FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mfa_materials_notes_ibfk_5` FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_notes`
--

DROP TABLE IF EXISTS `mfa_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  KEY `user` (`user`),
  CONSTRAINT `mfa_notes_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`),
  CONSTRAINT `mfa_notes_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_population`
--

DROP TABLE IF EXISTS `mfa_population`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_population` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  `population` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_population_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_sankey`
--

DROP TABLE IF EXISTS `mfa_sankey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_sankey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_sankey_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_sankey_nodes`
--

DROP TABLE IF EXISTS `mfa_sankey_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_sankey_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sankey` int(10) unsigned NOT NULL,
  `from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sankey` (`sankey`),
  CONSTRAINT `mfa_sankey_nodes_ibfk_1` FOREIGN KEY (`sankey`) REFERENCES `mfa_sankey` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_scales`
--

DROP TABLE IF EXISTS `mfa_scales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_scales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `standard_multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1.0000',
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_scales_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_sources`
--

DROP TABLE IF EXISTS `mfa_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_sources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `type` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `belongs_to` int(10) unsigned DEFAULT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `specialty` int(10) unsigned NOT NULL,
  `assist` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `dataset` (`dataset`),
  KEY `status` (`status`),
  KEY `belongs_to` (`belongs_to`),
  CONSTRAINT `mfa_sources_ibfk_1` FOREIGN KEY (`type`) REFERENCES `mfa_sources_types` (`id`),
  CONSTRAINT `mfa_sources_ibfk_3` FOREIGN KEY (`status`) REFERENCES `mfa_status_options` (`id`),
  CONSTRAINT `mfa_sources_ibfk_5` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_sources_ibfk_6` FOREIGN KEY (`belongs_to`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=318 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_sources_flags`
--

DROP TABLE IF EXISTS `mfa_sources_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_sources_flags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source` int(10) unsigned NOT NULL,
  `flag` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `flag` (`flag`),
  KEY `source` (`source`),
  CONSTRAINT `mfa_sources_flags_ibfk_2` FOREIGN KEY (`flag`) REFERENCES `mfa_special_flags` (`id`),
  CONSTRAINT `mfa_sources_flags_ibfk_3` FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_sources_types`
--

DROP TABLE IF EXISTS `mfa_sources_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_sources_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_sources_types_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_special_flags`
--

DROP TABLE IF EXISTS `mfa_special_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_special_flags` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_special_flags_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_status_options`
--

DROP TABLE IF EXISTS `mfa_status_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_status_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_transportation`
--

DROP TABLE IF EXISTS `mfa_transportation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_transportation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `transportation_mode` smallint(5) unsigned NOT NULL,
  `distance` decimal(7,2) NOT NULL COMMENT 'in km',
  `notes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `cost` decimal(7,2) unsigned NOT NULL,
  `from_destination` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to_destination` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `transportation_mode` (`transportation_mode`),
  CONSTRAINT `mfa_transportation_ibfk_1` FOREIGN KEY (`activity`) REFERENCES `mfa_activities_log` (`id`),
  CONSTRAINT `mfa_transportation_ibfk_2` FOREIGN KEY (`transportation_mode`) REFERENCES `mfa_transportation_modes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mfa_transportation_modes`
--

DROP TABLE IF EXISTS `mfa_transportation_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mfa_transportation_modes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `papers`
--

DROP TABLE IF EXISTS `papers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `volume` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `issue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pages` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `length` smallint(6) NOT NULL,
  `year` year(4) NOT NULL,
  `doi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abstract` text COLLATE utf8_unicode_ci NOT NULL,
  `abstract_status` enum('pending','author_approved','journal_approved','open_access','not_approved','toc_only') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `editor_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(600) COLLATE utf8_unicode_ci NOT NULL,
  `source` smallint(6) NOT NULL,
  `status` enum('active','pending','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `open_access` tinyint(1) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `status` (`status`),
  CONSTRAINT `papers_ibfk_1` FOREIGN KEY (`source`) REFERENCES `sources` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `research`
--

DROP TABLE IF EXISTS `research`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `research` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `researcher` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supervisor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `institution` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('ongoing','finished','cancelled','paused') COLLATE utf8_unicode_ci NOT NULL,
  `target_finishing_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` smallint(5) unsigned NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `parent` (`parent`),
  CONSTRAINT `tags_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `tags_parents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags_papers`
--

DROP TABLE IF EXISTS `tags_papers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags_papers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tag` smallint(5) unsigned NOT NULL,
  `paper` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paper` (`paper`),
  KEY `tag` (`tag`),
  CONSTRAINT `papertag` FOREIGN KEY (`paper`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tagtag` FOREIGN KEY (`tag`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1423 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags_parents`
--

DROP TABLE IF EXISTS `tags_parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags_parents` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_admin`
--

DROP TABLE IF EXISTS `users_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `privilege` enum('admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`),
  KEY `privilege` (`privilege`),
  KEY `user` (`user`),
  CONSTRAINT `users_admin_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_permissions`
--

DROP TABLE IF EXISTS `users_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `users_permissions_ibfk_3` FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_permissions_ibfk_4` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wastewater`
--

DROP TABLE IF EXISTS `wastewater`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wastewater` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `material` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `measure` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(10,4) unsigned NOT NULL,
  `raw_value` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `year` tinyint(1) unsigned NOT NULL,
  `plant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=610 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wastewater_plants`
--

DROP TABLE IF EXISTS `wastewater_plants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wastewater_plants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `flow` decimal(10,2) unsigned NOT NULL,
  `flow_year_1` decimal(10,2) unsigned NOT NULL,
  `flow_year_2` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wastewater_samples`
--

DROP TABLE IF EXISTS `wastewater_samples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wastewater_samples` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `material` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `measure` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(10,4) unsigned NOT NULL,
  `raw_value` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `plant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('RAW','FE') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33817 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-15 13:07:05

ALTER TABLE `tags`
ADD `gps` varchar(200) COLLATE 'utf8_unicode_ci' NULL;

UPDATE tags SET gps = '2.3488, 48.8534' WHERE id = 9;
UPDATE tags SET gps = '-9.1333, 38.7167' WHERE id = 20;
UPDATE tags SET gps = '120.5954, 31.3041' WHERE id = 23;
UPDATE tags SET gps = '121.5319, 25.0478' WHERE id = 41;
UPDATE tags SET gps = '-100.1607, 40.9295' WHERE id = 45;
UPDATE tags SET gps = '10.7461, 59.9127' WHERE id = 49;
UPDATE tags SET gps = '-61.6333, 10.2333' WHERE id = 61;
UPDATE tags SET gps = '77.2315, 28.6519' WHERE id = 62;
UPDATE tags SET gps = '-74.0060, 40.7143' WHERE id = 64;
UPDATE tags SET gps = '15.6216, 58.4109' WHERE id = 92;
UPDATE tags SET gps = '116.3972, 39.9075' WHERE id = 95;
UPDATE tags SET gps = '114.1577, 22.2855' WHERE id = 98;
UPDATE tags SET gps = '-99.1277, 19.4285' WHERE id = 99;
UPDATE tags SET gps = '-62.9000,-7.2333' WHERE id = 100;
UPDATE tags SET gps = '-43.2075,-22.9028' WHERE id = 101;
UPDATE tags SET gps = '-58.3772,-34.6131' WHERE id = 102;
UPDATE tags SET gps = '-1.0827, 53.9576' WHERE id = 103;
UPDATE tags SET gps = '16.3721, 48.2085' WHERE id = 104;
UPDATE tags SET gps = '18.4232,-33.9258' WHERE id = 108;
UPDATE tags SET gps = '10.0153, 53.5753' WHERE id = 111;
UPDATE tags SET gps = '103.8501, 1.2897' WHERE id = 112;
UPDATE tags SET gps = '18.0649, 59.3326' WHERE id = 113;
UPDATE tags SET gps = '-79.4163, 43.7001' WHERE id = 115;
UPDATE tags SET gps = '-2.9779, 53.4106' WHERE id = 120;
UPDATE tags SET gps = '-74.0817, 4.6097' WHERE id = 121;
UPDATE tags SET gps = '121.4581, 31.2222' WHERE id = 122;
UPDATE tags SET gps = '126.9784, 37.5660' WHERE id = 123;
UPDATE tags SET gps = '35.9450, 31.9552' WHERE id = 125;
UPDATE tags SET gps = '120.9822, 14.6042' WHERE id = 127;
UPDATE tags SET gps = '117.1767, 39.1422' WHERE id = 133;
UPDATE tags SET gps = '-90.0751, 29.9547' WHERE id = 134;
UPDATE tags SET gps = '77.5937, 12.9719' WHERE id = 137;
UPDATE tags SET gps = '100.5014, 13.7540' WHERE id = 138;
UPDATE tags SET gps = '-118.2437, 34.0522' WHERE id = 139;
UPDATE tags SET gps = '117.1006, 31.3108' WHERE id = 140;
UPDATE tags SET gps = '139.6917, 35.6895' WHERE id = 141;
UPDATE tags SET gps = '37.6156, 55.7522' WHERE id = 142;
UPDATE tags SET gps = '113.2500, 23.1167' WHERE id = 143;
UPDATE tags SET gps = '137.2667, 35.9500' WHERE id = 144;
UPDATE tags SET gps = '56.2940, 35.3174' WHERE id = 145;
UPDATE tags SET gps = '-0.1257, 51.5085' WHERE id = 146;
UPDATE tags SET gps = '106.8451,-6.2146' WHERE id = 148;
UPDATE tags SET gps = '28.9497, 41.0138' WHERE id = 149;
UPDATE tags SET gps = '114.0683, 22.5455' WHERE id = 150;
UPDATE tags SET gps = '3.3947, 6.4541' WHERE id = 151;
UPDATE tags SET gps = '90.4074, 23.7104' WHERE id = 152;
UPDATE tags SET gps = '67.0822, 24.9056' WHERE id = 153;
UPDATE tags SET gps = '31.2497, 30.0626' WHERE id = 154;
UPDATE tags SET gps = '88.3630, 22.5626' WHERE id = 155;
UPDATE tags SET gps = '39.4934, 13.6937' WHERE id = 166;
UPDATE tags SET gps = '106.6296, 10.8230' WHERE id = 170;
UPDATE tags SET gps = '-80.1937, 25.7743' WHERE id = 173;
UPDATE tags SET gps = '16.1826, 58.5942' WHERE id = 179;
UPDATE tags SET gps = '4.3488, 50.8505' WHERE id = 180;
UPDATE tags SET gps = '144.9633,-37.8140' WHERE id = 184;
UPDATE tags SET gps = '151.2073,-33.8678' WHERE id = 187;
UPDATE tags SET gps = '-123.1193, 49.2497' WHERE id = 188;
UPDATE tags SET gps = '-114.0853, 51.0501' WHERE id = 189;
UPDATE tags SET gps = '2.1590, 41.3888' WHERE id = 190;
UPDATE tags SET gps = '-3.7026, 40.4165' WHERE id = 191;
UPDATE tags SET gps = '79.8478, 6.9319' WHERE id = 193;
UPDATE tags SET gps = '-88.3054, 41.8875' WHERE id = 194;
UPDATE tags SET gps = '4.4792, 51.9225' WHERE id = 195;
UPDATE tags SET gps = '-4.2576, 55.8651' WHERE id = 197;
UPDATE tags SET gps = '-97.7431, 30.2672' WHERE id = 198;
UPDATE tags SET gps = '-104.9847, 39.7392' WHERE id = 199;
UPDATE tags SET gps = '-122.6762, 45.5234' WHERE id = 200;
UPDATE tags SET gps = '-122.3321, 47.6062' WHERE id = 201;
UPDATE tags SET gps = '-81.7960, 26.1423' WHERE id = 202;
UPDATE tags SET gps = '85.3206, 27.7017' WHERE id = 204;
UPDATE tags SET gps = '-8.6110, 41.1496' WHERE id = 205;
UPDATE tags SET gps = '14.5051, 46.0511' WHERE id = 207;
UPDATE tags SET gps = '23.7162, 37.9794' WHERE id = 208;
UPDATE tags SET gps = '10.5333, 49.6833' WHERE id = 210;
UPDATE tags SET gps = '9.1770, 48.7823' WHERE id = 211;
UPDATE tags SET gps = '14.4208, 50.0880' WHERE id = 212;
UPDATE tags SET gps = '106.5528, 29.5628' WHERE id = 215;
UPDATE tags SET gps = '100.4517, 38.9342' WHERE id = 216;

CREATE TABLE `people` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `affiliation` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `profile` text NOT NULL,
  `research_interests` text NOT NULL,
  `url` varchar(200) NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

CREATE TABLE `people_papers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `paper` int(11) NOT NULL,
  `people` int(10) unsigned NOT NULL,
  FOREIGN KEY (`paper`) REFERENCES `papers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`people`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `people`
ADD `active` tinyint(1) unsigned NOT NULL DEFAULT '1';

ALTER TABLE `people`
ADD `city` varchar(100) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `affiliation`,
ADD `country` varchar(100) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `city`;

CREATE TABLE `people_access` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `people` int(10) unsigned NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `date` timestamp NOT NULL,
  `details` text NOT NULL,
  FOREIGN KEY (`people`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

CREATE TABLE `blog_authors` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(200) NOT NULL,
  `profile` text NOT NULL,
  `url` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

CREATE TABLE `blog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

CREATE TABLE `blog_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `blog` int(10) unsigned NOT NULL,
  `paper` int(11) NOT NULL,
  FOREIGN KEY (`blog`) REFERENCES `blog` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`paper`) REFERENCES `papers` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `blog_authors`
CHANGE `profile` `profile` longtext COLLATE 'utf8_unicode_ci' NOT NULL AFTER `name`;

ALTER TABLE `blog`
CHANGE `content` `content` longtext COLLATE 'utf8_unicode_ci' NOT NULL AFTER `title`;

CREATE TABLE `blog_authors_pivot` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `blog` int(10) unsigned NOT NULL,
  `author` int(10) unsigned NOT NULL,
  FOREIGN KEY (`blog`) REFERENCES `blog` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`author`) REFERENCES `blog_authors` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `papers`
CHANGE `status` `status` enum('active','pending','deleted','pending_data') COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'active' AFTER `source`;

ALTER TABLE `papers`
CHANGE `source` `source` smallint(6) NULL AFTER `keywords`;

CREATE TABLE `mails` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `subject` varchar(200) NOT NULL,
  `content` text NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

CREATE TABLE `people_mails` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `people` int(10) unsigned NOT NULL,
  `mail` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL,
  `address` varchar(255) NOT NULL,
  `content` text NOT NULL,
  FOREIGN KEY (`people`) REFERENCES `people` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`mail`) REFERENCES `mails` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `people_mails`
ADD `sent_by` int(11) NOT NULL,
ADD FOREIGN KEY (`sent_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

CREATE TABLE `people_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `url` varchar(500) NOT NULL,
  `date` timestamp NOT NULL,
  `ip` varchar(200) NOT NULL,
  `action` varchar(200) NOT NULL,
  `info` text NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `people_log`
ADD `people` int(10) unsigned NOT NULL,
ADD FOREIGN KEY (`people`) REFERENCES `people` (`id`) ON DELETE CASCADE;

ALTER TABLE `people_log`
DROP FOREIGN KEY `people_log_ibfk_1`,
ADD FOREIGN KEY (`people`) REFERENCES `people_access` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

INSERT INTO `mails` (`subject`, `content`)
VALUES ('Personal e-mail reminder', 'Content varies per e-mail... entered directly by the sender in the e-mail client. Not sent automatically.');

ALTER TABLE `mfa_dataset`
ADD `description` text COLLATE 'utf8_unicode_ci' NOT NULL AFTER `banner_text`;

ALTER TABLE `papers`
ADD `title_native` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `title`,
ADD `abstract_native` text COLLATE 'utf8_unicode_ci' NULL AFTER `abstract`,
ADD `language` enum('English','Spanish','Chinese','French','German','Other') NULL DEFAULT 'English';

CREATE TABLE `datavisualizations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `paper` int(11) NULL,
  `source_details` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `contributor` varchar(255) NOT NULL,
  FOREIGN KEY (`paper`) REFERENCES `papers` (`id`)
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `datavisualizations`
ADD `date` date NOT NULL;

ALTER TABLE `datavisualizations`
ADD `year` year NULL;

ALTER TABLE `datavisualizations`
CHANGE `source_details` `source_details` text COLLATE 'utf8_unicode_ci' NOT NULL AFTER `paper`;

INSERT INTO `mails` (`subject`, `content`)
VALUES ('Urban metabolism data visualization published', 'Dear NAME,\r\n\r\nOn the Metabolism of Cities website we recently published an urban metabolism data visualization from one of your publications. This is the image that we published:\r\n\r\nDATA_VIZ_LINK\r\n\r\nThis image comes from the following publication:\r\n\r\nPUBLICATION_LINK\r\n\r\nWe just wanted to let you know about this and let you know that from October-December 2016 we are running a data visualization project. In this period we will be publishing new data visualizations every day, we will be publishing blog posts and organize other activities around data visualization. We would love your involvement in this! Anything from a comment on your own data visualization or the writing of a short guest blog post would be greatly appreciated. We also welcome your other ideas to contribute. Interested! Mail us at info@metabolismofcities.org! \r\n\r\n------------------------------------------------------\r\n\r\n*ABOUT OUR WEBSITE* \r\n\r\n\r\nThe Metabolism of Cities website is an open source platform for urban metabolism. Through our website, our team strives to encourage collaboration between urban metabolism stakeholders (academia, urban administrations, NGOs, local inhabitants, etc), to host and create open data, to gather information on existing publications and to enable a global conversation in the field of urban metabolism in order to advance this field.\r\n\r\n*Urban metabolism publications*\r\n\r\nOver the past two years, we have created a collection of publications related to (urban) metabolism. We have tagged each publication and made it very easy to filter through this list. We now have 369 publications but there are many more out there.\r\n\r\nWe have identified you as the author of a total of PUBLICATION_TOTAL publication(s). These are the ones we have in our database:\r\n\r\nPUBLICATION_LIST\r\n\r\nCould you review the publications and edit any incorrect information or add any missing publications? You can also edit your own profile. This is all a matter of clicking one link (no registration required; this link is unique to you):\r\n\r\nDASHBOARD_LINK\r\n\r\n*Urban metabolism data*\r\n\r\nWe are developing a single database with data from urban metabolism studies from all over the world. So far we have identified 37 studies that have published data, and we have logged 220 different data points from these studies. But we need your help! Have you undertaken urban metabolism studies? Can you help review data from your city?\r\n\r\nUPLOAD_STUDIES\r\nREVIEW_DATA\r\n\r\n*Metabolism of Cities Stakeholder Initiative*\r\n\r\nWe aim to bring together people who operate in the field of urban metabolism. Would you like to participate in future discussions, contribute to our forums, or help shape ideas around research and data? Register now to become part of the Stakeholders Initiative! We will add you to our mailing list and keep you informed of upcoming ideas and developments as our network grows.\r\n\r\nJOIN_STAKEHOLDERS\r\nREAD_MORE_STAKEHOLDERS\r\n\r\nIf you have any questions or comments, please don\'t hesitate to let us know at [mailto:info@metabolismofcities.org info@metabolismofcities.org].\r\n\r\nSincerely,\r\n\r\n*The Metabolism of Cities Team*\r\n\r\nAristide Athanassiadis\r\nGabriela Fernandez\r\nPaul Hoekman\r\nRachel Spiegel');

UPDATE `mails` SET
`id` = '3',
`subject` = 'Urban metabolism data visualization published',
`content` = 'Dear NAME,\r\n\r\nOn the Metabolism of Cities website we recently published an urban metabolism data visualization from one of your publications. This is the image that we published:\r\n\r\nDATA_VIZ_LINK\r\n\r\nThis image comes from the following publication:\r\n\r\nPUBLICATION_LINK\r\n\r\nWe just wanted to let you know about this and let you know that from October-December 2016 we are running a [http://metabolismofcities.org/datavisualization data visualization project]. In this period we will be publishing [http://metabolismofcities.org/datavisualization/examples new data visualizations every day], we will be publishing [http://metabolismofcities.org/blog blog posts] and organize other activities around data visualization. We would love your involvement in this! Anything from a comment on your own data visualization or the writing of a short guest blog post would be greatly appreciated. We also welcome your other ideas to contribute. Interested? Mail us at info@metabolismofcities.org! \r\n\r\n------------------------------------------------------\r\n\r\n*ABOUT OUR WEBSITE* \r\n\r\n\r\nThe Metabolism of Cities website is an open source platform for urban metabolism. Through our website, our team strives to encourage collaboration between urban metabolism stakeholders (academia, urban administrations, NGOs, local inhabitants, etc), to host and create open data, to gather information on existing publications and to enable a global conversation in the field of urban metabolism in order to advance this field.\r\n\r\n*Urban metabolism publications*\r\n\r\nOver the past two years, we have created a collection of publications related to (urban) metabolism. We have tagged each publication and made it very easy to filter through this list. We now have 369 publications but there are many more out there.\r\n\r\nWe have identified you as the author of a total of PUBLICATION_TOTAL publication(s). These are the ones we have in our database:\r\n\r\nPUBLICATION_LIST\r\n\r\nCould you review the publications and edit any incorrect information or add any missing publications? You can also edit your own profile. This is all a matter of clicking one link (no registration required; this link is unique to you):\r\n\r\nDASHBOARD_LINK\r\n\r\n*Urban metabolism data*\r\n\r\nWe are developing a single database with data from urban metabolism studies from all over the world. So far we have identified 37 studies that have published data, and we have logged 220 different data points from these studies. But we need your help! Have you undertaken urban metabolism studies? Can you help review data from your city?\r\n\r\nUPLOAD_STUDIES\r\nREVIEW_DATA\r\n\r\n*Metabolism of Cities Stakeholder Initiative*\r\n\r\nWe aim to bring together people who operate in the field of urban metabolism. Would you like to participate in future discussions, contribute to our forums, or help shape ideas around research and data? Register now to become part of the Stakeholders Initiative! We will add you to our mailing list and keep you informed of upcoming ideas and developments as our network grows.\r\n\r\nJOIN_STAKEHOLDERS\r\n\r\nIf you have any questions or comments, please don\'t hesitate to let us know at [mailto:info@metabolismofcities.org info@metabolismofcities.org].\r\n\r\nSincerely,\r\n\r\n*The Metabolism of Cities Team*\r\n\r\nAristide Athanassiadis\r\nGabriela Fernandez\r\nPaul Hoekman\r\nRachel Spiegel'
WHERE `id` = '3';

ALTER TABLE `people_mails`
CHANGE `sent_by` `sent_by` int(11) NULL AFTER `content`;

CREATE TABLE `videos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

CREATE TABLE `votes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `datavisualization` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL,
  `ip` varchar(200) NOT NULL,
  `browser` varchar(300) NOT NULL,
  `info` text NOT NULL,
  FOREIGN KEY (`datavisualization`) REFERENCES `datavisualizations` (`id`)
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `votes`
ADD `comments` text COLLATE 'utf8_unicode_ci' NOT NULL,
ADD `name` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `comments`;

ALTER TABLE `votes`
ADD `email` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL;

ALTER TABLE `papers`
CHANGE `year` `year` smallint(4) unsigned NOT NULL AFTER `length`;

INSERT INTO `mails` (`subject`, `content`)
SELECT 'Invitation to join the Metabolism of Cities open source platform', 'Dear NAME,\r\n\r\nThe Metabolism of Cities website is an open source platform for urban metabolism. Through our website, our team strives to encourage collaboration between urban metabolism stakeholders (academia, urban administrations, NGOs, local inhabitants, etc), to host and create open data, to gather information on existing publications and to enable a global conversation in the field of urban metabolism in order to advance this field.\r\n\r\nWith these goals in mind, we are engaging in several activities for which we like to ask for your support.\r\n\r\n*Urban metabolism publications*\r\n\r\nOver the past year and a half, we have created a collection of publications related to (urban) metabolism. We have tagged each publication and made it very easy to filter through this list. We now have GRAND_TOTAL_PUBLICATIONS publications but there are many more out there.\r\n\r\nWe have identified you as the author of a total of PUBLICATION_TOTAL publication(s). These are the ones we have in our database:\r\n\r\nPUBLICATION_LIST\r\n\r\nCould you review the publications and edit any incorrect information or add any missing publications? You can also edit your own profile. This is all a matter of clicking one link (no registration required; this link is unique to you):\r\n\r\nDASHBOARD_LINK\r\n\r\n*Urban metabolism data*\r\n\r\nWe are developing a single database with data from urban metabolism studies from all over the world. So far we have identified 37 studies that have published data, and we have logged 220 different data points from these studies. But we need your help! Have you undertaken urban metabolism studies? Can you help review data from your city?\r\n\r\nUPLOAD_STUDIES\r\nREVIEW_DATA\r\n\r\n*Metabolism of Cities Stakeholder Initiative*\r\n\r\nWe aim to bring together people who operate in the field of urban metabolism. Would you like to participate in future discussions, contribute to our forums, or help shape ideas around research and data? Register now to become part of the Stakeholders Initiative! We will add you to our mailing list and keep you informed of upcoming ideas and developments as our network grows.\r\n\r\nJOIN_STAKEHOLDERS\r\n\r\nIf you have any questions or comments, please don\'t hesitate to let us know at [mailto:info@metabolismofcities.org info@metabolismofcities.org].\r\n\r\nSincerely,\r\n\r\n*The Metabolism of Cities Team*\r\n\r\nAristide Athanassiadis\r\nGabriela Fernandez\r\nPaul Hoekman\r\nRachel Spiegel'
FROM `mails`
WHERE ((`id` = '1'));

CREATE TABLE `wishlist_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `wishlist_types` (`id`, `name`) VALUES
(1,	'Features'),
(2,	'Content-related'),
(3,	'Urban data mining project'),
(4,	'Quality Control'),
(5,	'Other');

CREATE TABLE `wishlist` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `description` text NOT NULL,
  `status` enum('open','finished','removed') NOT NULL DEFAULT 'open',
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `type` int(10) unsigned NOT NULL,
  `assigned_to` int(11) NULL,
  FOREIGN KEY (`type`) REFERENCES `wishlist_types` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';


ALTER TABLE `wishlist`
ADD `parent_item` int(10) unsigned NULL,
COMMENT=''; -- 0.022 s

ALTER TABLE `wishlist`
ADD FOREIGN KEY (`parent_item`) REFERENCES `wishlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

ALTER TABLE `wishlist`
ADD `last_update` datetime NULL ;

CREATE TABLE `ontology` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `parent_id` int unsigned NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';


ALTER TABLE `ontology`
ADD INDEX `parent_id` (`parent_id`);

ALTER TABLE `ontology`
ADD FOREIGN KEY (`parent_id`) REFERENCES `ontology` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

ALTER TABLE `ontology`
CHANGE `parent_id` `parent_id` int(10) unsigned NULL AFTER `name`;

CREATE TABLE `mtu_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` smallint(5) unsigned NOT NULL,
  `mtu_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mtu_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `city` (`city`),
  CONSTRAINT `mtu_list_ibfk_1` FOREIGN KEY (`city`) REFERENCES `tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `mtu_analysis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `analysis_option` int(10) unsigned NOT NULL,
  `mtu` int(10) unsigned NOT NULL,
  `result` decimal(15,2) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `url` text COLLATE utf8_unicode_ci,
  `source_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mtu_option` (`analysis_option`),
  KEY `mtu` (`mtu`),
  CONSTRAINT `mtu_analysis_ibfk_1` FOREIGN KEY (`mtu`) REFERENCES `mtu_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mtu_ibfk_1` FOREIGN KEY (`analysis_option`) REFERENCES `analysis_options` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `videos`
ADD `site` enum('youtube','vimeo') COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'youtube';

CREATE TABLE `tags_research` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tag` smallint(5) unsigned NOT NULL,
  `research` int(10) unsigned NOT NULL,
  FOREIGN KEY (`tag`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`research`) REFERENCES `research` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';


CREATE TABLE `questionnaire` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` timestamp NOT NULL,
  `ip` varchar(255) NOT NULL,
  `browser` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `affiliation` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `do_not_share` tinyint(1) unsigned NOT NULL,
  `work_field` tinyint unsigned NOT NULL,
  `work` varchar(100) NOT NULL,
  `work_other` text NOT NULL,
  `areas` varchar(100) NOT NULL,
  `areas_other` text NOT NULL,
  `regions` varchar(100) NOT NULL,
  `regions_other` text NOT NULL,
  `scales` varchar(100) NOT NULL,
  `scales_other` text NOT NULL,
  `materials` tinyint(1) unsigned NOT NULL,
  `materials_details` text NOT NULL,
  `primary_data` tinyint(1) unsigned NOT NULL,
  `data_type` text NOT NULL,
  `data_details` text NOT NULL,
  `software` text NOT NULL,
  `literature` text NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';
