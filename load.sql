-- Adminer 4.1.0-dev MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `dqi_classifications`;
CREATE TABLE `dqi_classifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` int(10) unsigned NOT NULL,
  `score` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section` (`section`),
  CONSTRAINT `dqi_classifications_ibfk_2` FOREIGN KEY (`section`) REFERENCES `dqi_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dqi_sections`;
CREATE TABLE `dqi_sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `dqi_sections_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `keywords_papers`;
CREATE TABLE `keywords_papers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` smallint(5) unsigned NOT NULL,
  `paper` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paper` (`paper`),
  KEY `keyword` (`keyword`),
  CONSTRAINT `keywords_papers_ibfk_1` FOREIGN KEY (`keyword`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keywords_papers_ibfk_2` FOREIGN KEY (`paper`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_activities`;
CREATE TABLE `mfa_activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_activities_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_activities_log`;
CREATE TABLE `mfa_activities_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `time` smallint(5) unsigned NOT NULL,
  `source` smallint(6) DEFAULT NULL,
  `contact` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `source` (`source`),
  KEY `contact` (`contact`),
  CONSTRAINT `mfa_activities_log_ibfk_1` FOREIGN KEY (`activity`) REFERENCES `mfa_activities` (`id`),
  CONSTRAINT `mfa_activities_log_ibfk_2` FOREIGN KEY (`source`) REFERENCES `sources` (`id`),
  CONSTRAINT `mfa_activities_log_ibfk_3` FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DELIMITER ;;

CREATE TRIGGER `mfa_activities_log_bu` BEFORE UPDATE ON `mfa_activities_log` FOR EACH ROW
BEGIN
      SET NEW.time = (UNIX_TIMESTAMP(NEW.end)-UNIX_TIMESTAMP(NEW.start))/60;
END;;

DELIMITER ;

DROP TABLE IF EXISTS `mfa_contacts`;
CREATE TABLE `mfa_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(3) unsigned DEFAULT NULL,
  `organization` tinyint(1) unsigned NOT NULL COMMENT 'Whether or not this contact is an organization (if false, this is a person)',
  `employer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Name of the employer; this clearly only applies to people, not to organizations',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pending` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `dataset` (`dataset`),
  KEY `employer` (`employer`),
  CONSTRAINT `mfa_contacts_ibfk_4` FOREIGN KEY (`type`) REFERENCES `mfa_contacts_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_contacts_ibfk_5` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_contacts_types`;
CREATE TABLE `mfa_contacts_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_contacts_types_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_data`;
CREATE TABLE `mfa_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `material` mediumint(8) unsigned NOT NULL,
  `year` year(4) NOT NULL,
  `data` decimal(12,4) unsigned NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_id` smallint(6) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scale` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material` (`material`),
  KEY `scale` (`scale`),
  KEY `source_id` (`source_id`),
  CONSTRAINT `mfa_data_ibfk_2` FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_data_ibfk_3` FOREIGN KEY (`scale`) REFERENCES `mfa_scales` (`id`),
  CONSTRAINT `mfa_data_ibfk_4` FOREIGN KEY (`source_id`) REFERENCES `sources` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_dataset`;
CREATE TABLE `mfa_dataset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `research_project` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year_start` year(4) NOT NULL,
  `year_end` year(4) NOT NULL,
  `access` enum('public','private','link_only') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'private',
  `decimal_precision` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `measurement` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'kg',
  `contact_management` tinyint(1) unsigned NOT NULL,
  `dqi` tinyint(1) unsigned NOT NULL,
  `time_log` tinyint(1) unsigned NOT NULL,
  `multiscale` tinyint(1) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `research_project` (`research_project`),
  KEY `access` (`access`),
  KEY `type` (`type`),
  CONSTRAINT `mfa_dataset_ibfk_2` FOREIGN KEY (`research_project`) REFERENCES `research` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_dataset_ibfk_3` FOREIGN KEY (`type`) REFERENCES `mfa_dataset_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_dataset_types`;
CREATE TABLE `mfa_dataset_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_dqi`;
CREATE TABLE `mfa_dqi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` int(10) unsigned NOT NULL,
  `classification` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classification` (`classification`),
  KEY `data` (`data`),
  CONSTRAINT `mfa_dqi_ibfk_1` FOREIGN KEY (`classification`) REFERENCES `dqi_classifications` (`id`),
  CONSTRAINT `mfa_dqi_ibfk_3` FOREIGN KEY (`data`) REFERENCES `mfa_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_files`;
CREATE TABLE `mfa_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `source` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_files_ibfk_2` FOREIGN KEY (`id`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_files_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_groups`;
CREATE TABLE `mfa_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataset` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_groups_ibfk_4` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_leads`;
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
  CONSTRAINT `mfa_leads_ibfk_1` FOREIGN KEY (`from_contact`) REFERENCES `mfa_contacts` (`id`),
  CONSTRAINT `mfa_leads_ibfk_2` FOREIGN KEY (`from_source`) REFERENCES `mfa_sources` (`id`),
  CONSTRAINT `mfa_leads_ibfk_5` FOREIGN KEY (`to_source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_leads_ibfk_6` FOREIGN KEY (`to_contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_materials`;
CREATE TABLE `mfa_materials` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mfa_group` mediumint(8) unsigned NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`mfa_group`),
  CONSTRAINT `mfa_materials_ibfk_2` FOREIGN KEY (`mfa_group`) REFERENCES `mfa_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_materials_notes`;
CREATE TABLE `mfa_materials_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `material` (`material`),
  CONSTRAINT `mfa_materials_notes_ibfk_1` FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_scales`;
CREATE TABLE `mfa_scales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_scales_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_sources`;
CREATE TABLE `mfa_sources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `type` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `pending` tinyint(1) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_sources_ibfk_1` FOREIGN KEY (`type`) REFERENCES `mfa_sources_types` (`id`),
  CONSTRAINT `mfa_sources_ibfk_2` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `mfa_sources_types`;
CREATE TABLE `mfa_sources_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dataset` (`dataset`),
  CONSTRAINT `mfa_sources_types_ibfk_1` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `papers`;
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
  `abstract_status` enum('pending','author_approved','journal_approved','open_access','not_approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `editor_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(600) COLLATE utf8_unicode_ci NOT NULL,
  `source` smallint(6) NOT NULL,
  `status` enum('active','pending','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `open_access` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `status` (`status`),
  CONSTRAINT `papers_ibfk_1` FOREIGN KEY (`source`) REFERENCES `sources` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `research`;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `sources`;
CREATE TABLE `sources` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` smallint(5) unsigned NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `parent` (`parent`),
  CONSTRAINT `tags_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `tags_parents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `tags_papers`;
CREATE TABLE `tags_papers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tag` smallint(5) unsigned NOT NULL,
  `paper` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paper` (`paper`),
  KEY `tag` (`tag`),
  CONSTRAINT `papertag` FOREIGN KEY (`paper`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tagtag` FOREIGN KEY (`tag`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `tags_parents`;
CREATE TABLE `tags_parents` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2014-08-31 13:41:28

ALTER TABLE `mfa_activities_log`
DROP FOREIGN KEY `mfa_activities_log_ibfk_2`; -- 0.001 s

ALTER TABLE `mfa_activities_log`
CHANGE `source` `source` int unsigned NULL AFTER `time`,
COMMENT=''; -- 0.531 s

ALTER TABLE `mfa_activities_log`
ADD FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; -- 0.497 s

ALTER TABLE `mfa_contacts`
ADD `part_of_referral_organization` tinyint(1) unsigned NOT NULL COMMENT 'Whether or not this contact (person) is part of the referral organization (rather than just a contact of the organization)' AFTER `organization`,
COMMENT=''; -- 0.752 s

ALTER TABLE `mfa_contacts`
CHANGE `part_of_referral_organization` `works_for_referral_organization` tinyint(1) unsigned NOT NULL COMMENT 'Whether or not this contact (person) works for the referral organization (rather than just a contact of the organization)' AFTER `organization`,
COMMENT=''; -- 0.491 s

ALTER TABLE `mfa_files`
DROP FOREIGN KEY `mfa_files_ibfk_2`; -- 0.111 s

ALTER TABLE `mfa_files`
CHANGE `source` `source` int unsigned NOT NULL AFTER `uploaded`,
COMMENT=''; -- 0.671 s

ALTER TABLE `mfa_files`
ADD FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.509 s

ALTER TABLE `mfa_contacts`
ADD `status` enum('pending','processed','awaiting_response','discarded') NOT NULL DEFAULT 'pending',
COMMENT=''; -- 0.924 s

ALTER TABLE `mfa_sources`
ADD `status` enum('pending','processed','awaiting_response','discarded') NOT NULL DEFAULT 'pending',
COMMENT=''; -- 0.924 s

UPDATE mfa_contacts SET status = 'processed' WHERE pending = 0;
UPDATE mfa_sources SET status = 'processed' WHERE pending = 0;

ALTER TABLE `mfa_contacts`
DROP `pending`,
COMMENT=''; -- 0.390 s

ALTER TABLE `mfa_sources`
DROP `pending`,
COMMENT=''; -- 0.390 s

CREATE TABLE `mfa_status_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status` varchar(255) NOT NULL
) COMMENT='' ENGINE='InnoDB'; -- 0.342 s

INSERT INTO `mfa_status_options` (`status`)
VALUES ('Pending'); -- 0.247 s

INSERT INTO `mfa_status_options` (`status`)
VALUES ('Processed'); -- 0.109 s

INSERT INTO `mfa_status_options` (`status`)
VALUES ('Awaiting response'); -- 0.128 s

INSERT INTO `mfa_status_options` (`status`)
VALUES ('Meeting scheduled'); -- 0.111 s

INSERT INTO `mfa_status_options` (`status`)
VALUES ('Discarded'); -- 0.122 s

ALTER TABLE `mfa_contacts`
CHANGE `status` `status` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'pending' AFTER `created`,
COMMENT=''; -- 0.928 s

ALTER TABLE `mfa_sources`
CHANGE `status` `status` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'pending' AFTER `created`,
COMMENT=''; -- 0.928 s

UPDATE mfa_contacts SET status = 1 WHERE status = 'pending';
UPDATE mfa_sources SET status = 1 WHERE status = 'pending';

UPDATE mfa_contacts SET status = 2 WHERE status = 'processed';
UPDATE mfa_sources SET status = 2 WHERE status = 'processed';

ALTER TABLE `mfa_contacts`
CHANGE `status` `status` int unsigned NOT NULL DEFAULT '1' AFTER `created`,
COMMENT=''; -- 0.426 s

ALTER TABLE `mfa_sources`
CHANGE `status` `status` int unsigned NOT NULL DEFAULT '1' AFTER `created`,
COMMENT=''; -- 0.426 s

ALTER TABLE `mfa_sources`
ADD INDEX `status` (`status`); -- 0.279 s

ALTER TABLE `mfa_contacts`
ADD INDEX `status` (`status`); -- 0.279 s

ALTER TABLE `mfa_sources`
ADD FOREIGN KEY (`status`) REFERENCES `mfa_status_options` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE; -- 0.427 s

ALTER TABLE `mfa_contacts`
ADD FOREIGN KEY (`status`) REFERENCES `mfa_status_options` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE; -- 0.427 s

ALTER TABLE `mfa_sources_flags`
DROP FOREIGN KEY `mfa_sources_flags_ibfk_1`; -- 0.271 s

ALTER TABLE `mfa_sources_flags`
CHANGE `source` `source` int unsigned NOT NULL AFTER `id`,
COMMENT=''; -- 0.629 s

ALTER TABLE `mfa_sources_flags`
ADD FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.467 s

ALTER TABLE `mfa_contacts_flags`
DROP FOREIGN KEY `mfa_contacts_flags_ibfk_1`,
ADD FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.405 s

CREATE TABLE `regional` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `paper` int(11) NOT NULL,
  FOREIGN KEY (`paper`) REFERENCES `papers` (`id`)
) COMMENT='' ENGINE='InnoDB'; -- 0.435 s

ALTER TABLE `mfa_contacts`
CHANGE `created` `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `employer`,
COMMENT=''; -- 0.693 s
