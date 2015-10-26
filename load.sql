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

CREATE TABLE `analysis_options_types` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL
) COMMENT='' ENGINE='InnoDB'; -- 0.464 s

CREATE TABLE `analysis_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  FOREIGN KEY (`type`) REFERENCES `analysis_options_types` (`id`) ON DELETE RESTRICT
) COMMENT='' ENGINE='InnoDB'; -- 0.378 s

CREATE TABLE `analysis` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `result` decimal(10,2) NOT NULL,
  `option` int(10) unsigned NOT NULL,
  `paper` int(11) NOT NULL,
  `year` year NULL,
  `notes` text NOT NULL,
  FOREIGN KEY (`option`) REFERENCES `analysis_options` (`id`),
  FOREIGN KEY (`paper`) REFERENCES `papers` (`id`)
) COMMENT='' ENGINE='InnoDB'; -- 0.283 s

INSERT INTO `analysis_options_types` (`name`)
VALUES ('Indicators'); -- 0.147 s

INSERT INTO `analysis_options_types` (`name`)
VALUES ('Data source comments'); -- 0.140 s

INSERT INTO `analysis_options` (`id`, `type`, `name`) VALUES
(1,	1,	'DMI per capita'),
(2,	1,	'TMI per capita'),
(3,	1,	'TMR per capita'),
(4,	1,	'DPO per capita'),
(5,	1,	'TMO per capita'),
(6,	1,	'DMC per capita'),
(7,	1,	'TMC per capita'),
(8,	1,	'PTB per capita'),
(9,	1,	'NAS per capita'),
(10,	2,	'Domestic extraction of fossil fuels'),
(11,	2,	'Domestic extraction of metal ores'),
(12,	2,	'Domestic extraction of non-metallic minerals'),
(13,	2,	'Domestic extraction of biomass – agriculture'),
(14,	2,	'Domestic extraction of biomass – forestry'),
(15,	2,	'Domestic extraction of biomass – grazing'),
(16,	2,	'Domestic extraction of biomass – hunting'),
(17,	2,	'Domestic extraction of biomass – fisheries'),
(18,	2,	'Imports'),
(19,	2,	'Exports');

ALTER TABLE `regional`
RENAME TO `case_studies`,
COMMENT=''; -- 0.643 s


CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

CREATE TABLE `users_permissions` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user` int(11) NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  FOREIGN KEY (`user`) REFERENCES `users` (`user_id`),
  FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) COMMENT='' ENGINE='InnoDB'; -- 1.010 s

ALTER TABLE `users_permissions`
DROP FOREIGN KEY `users_permissions_ibfk_1`,
ADD FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.507 s

ALTER TABLE `users_permissions`
DROP FOREIGN KEY `users_permissions_ibfk_2`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 1.354 s

ALTER TABLE `mfa_dataset`
CHANGE `research_project` `research_project` int(10) unsigned NULL AFTER `id`,
COMMENT=''; -- 0.385 s

ALTER TABLE `mfa_data`
DROP FOREIGN KEY `mfa_data_ibfk_4`; -- 0.045 s

ALTER TABLE `mfa_data`
CHANGE `source_id` `source_id` int unsigned NULL AFTER `source_link`,
COMMENT=''; -- 0.445 s

ALTER TABLE `mfa_data`
ADD FOREIGN KEY (`source_id`) REFERENCES `mfa_sources` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE; -- 1.656 s

ALTER TABLE `mfa_special_flags`
ADD `dataset` int(10) unsigned NULL,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`),
COMMENT=''; -- 0.645 s

ALTER TABLE `mfa_special_flags`
DROP FOREIGN KEY `mfa_special_flags_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 1.159 s

CREATE TABLE `mfa_notes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `dataset` int(10) unsigned NOT NULL,
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL,
  `subject` varchar(255) NOT NULL,
  `details` text NOT NULL,
  FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`),
  FOREIGN KEY (`user`) REFERENCES `users` (`user_id`)
) COMMENT=''; -- 0.370 s


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
  CONSTRAINT `mfa_indicators_ibfk_4` FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_indicators_ibfk_1` FOREIGN KEY (`type`) REFERENCES `mfa_indicators_types` (`id`),
  CONSTRAINT `mfa_indicators_ibfk_2` FOREIGN KEY (`more_information`) REFERENCES `papers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `mfa_indicators_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `mfa_indicators_types` (`id`, `name`) VALUES
(1,	'Input indicators'),
(2,	'Consumption indicators'),
(3,	'Output indicators');

INSERT INTO `mfa_indicators` (`id`, `type`, `name`, `abbreviation`, `description`, `more_information`, `dataset`) VALUES
(1,	1,	'Direct Material Input',	'DMI',	'Measures the direct input of materials for use into the economy, i.e. all materials which are of economic value and are used in production and consumption activities; DMI equals domestic (used) extraction plus imports. DMI is not additive across countries. For example, for EU totals of DMI the intra-EU foreign trade flows must be netted out from the DMIs of Member States.',	147,	NULL),
(2,	1,	'Total Material Input',	'TMI',	'includes, in addition to DMI, also unused domestic extraction, i.e. materials that are moved by economic activities but that do not serve as input for production or consumption activities (mining overburden, etc.). Unused domestic extraction is sometimes termed ‘domestic hidden flows’. TMI is not additive across countries.',	147,	NULL),
(3,	1,	'Total Material Requirement',	'TMR',	'Includes, in addition to TMI, the (indirect) material flows that are associated to imports but that take place in other countries. It measures the total ‘material base’ of an economy. Adding indirect flows converts imports into their ‘primary resource extraction equivalent’.\r\n\r\nTMR is not additive across countries. For example, for EU totals of TMR the intra-EU trade and the indirect flows associated to intra-EU trade must be netted out from the TMRs of Member States.',	147,	NULL),
(4,	1,	'Domestic Total Material Requirement',	'',	'Includes domestic used and unused extraction, i.e. the total of material flows originating from the national territory. Domestic TMR equals TMI less imports. Domestic TMR is additive across countries.',	147,	NULL),
(5,	2,	'Domestic material consumption',	'DMC',	'Measures the total amount of material directly used in an economy (i.e. excluding indirect flows). DMC is defined in the same way as other key physical indictors such as gross inland energy consumption. DMC equals DMI minus exports.',	147,	NULL),
(6,	2,	'Total material consumption',	'TMC',	'Measures the total material use associated with domestic production and consumption activities, including indirect flows imported (see TMR) but less exports and associated indirect flows of exports. TMC equals TMR minus exports and their indirect flows.',	147,	NULL),
(7,	2,	'Net Additions to Stock',	'NAS',	'Measures the ‘physical growth of the economy’, i.e. the quantity (weight) of new construction materials used in buildings and other infrastructure, and materials incorporated into new durable goods such as cars, industrial machinery, and household appliances. Materials are added to the economy’s stock each year (gross additions), and old materials are removed from stock as buildings are demolished, and durable goods disposed of (removals). These decommissioned materials, if not recycled, are accounted for in DPO (see below).',	147,	NULL),
(8,	2,	'Physical Trade Balance',	'PTB',	'Measures the physical trade surplus or deficit of an economy. PTB equals imports minus exports. Physical trade balances may also be defined for indirect flows associated to Imports and Exports.',	147,	NULL),
(9,	3,	'Domestic Processed Output',	'DPO',	'the total weight of materials, extracted from the domestic environment or imported, which have been used in the domestic economy, before flowing to the environment. These flows occur at the processing, manufacturing, use, and final disposal stages of the production-consumption chain. Included in DPO are emissions to air, industrial and household wastes deposited in landfills, material loads in wastewater and materials dispersed into the environment as a result of product use (dissipative flows). Recycled material flows in the economy (e.g. of metals, paper, glass) are not included in DPO. An uncertain fraction of some dissipative flows (manure, fertiliser) is ‘recycled’ by plant growth, but no attempt is made to estimate this fraction and subtract it from DPO.',	147,	NULL),
(10,	3,	'Total Domestic Output',	'TDO',	'The sum of DPO, and disposal of unused extraction. This indicator represents the total quantity of material outputs to the  environment caused by economic activity.',	147,	NULL),
(11,	3,	'Direct Material Output',	'DMO',	'The sum of DPO, and exports. This indicator represents the total quantity of material leaving the economy after use either towards the environment or towards the rest of the world. DMO is not additive across countries.',	147,	NULL),
(12,	3,	'Total material output',	'TMO',	'Measures the total of material that leaves the economy. TMO equals\r\nTDO plus exports. TMO is not additive across countries.',	147,	NULL);

CREATE TABLE `mfa_indicators_formula` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator` tinyint(3) unsigned NOT NULL,
  `type` enum('add','subtract') COLLATE utf8_unicode_ci NOT NULL,
  `mfa_group` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mfa_group` (`mfa_group`),
  KEY `indicator` (`indicator`),
  CONSTRAINT `mfa_indicators_formula_ibfk_4` FOREIGN KEY (`indicator`) REFERENCES `mfa_indicators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mfa_indicators_formula_ibfk_3` FOREIGN KEY (`mfa_group`) REFERENCES `mfa_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `mfa_indicators_formula` (`id`, `indicator`, `type`, `mfa_group`) VALUES
(1,	1,	'add',	1),
(2,	1,	'add',	2),
(3,	5,	'add',	1),
(4,	5,	'add',	2),
(5,	5,	'subtract',	3),
(6,	8,	'add',	2),
(7,	8,	'subtract',	3),
(8,	9,	'add',	4),
(9,	11,	'add',	4),
(10,	11,	'add',	3);

// Cascading... could be consider to be implemented

ALTER TABLE `dqi_sections`
DROP FOREIGN KEY `dqi_sections_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.533 s

ALTER TABLE `mfa_activities`
DROP FOREIGN KEY `mfa_activities_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.458 s

ALTER TABLE `mfa_activities_log`
DROP FOREIGN KEY `mfa_activities_log_ibfk_3`,
ADD FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.553 s

ALTER TABLE `mfa_activities_log`
DROP FOREIGN KEY `mfa_activities_log_ibfk_1`,
ADD FOREIGN KEY (`activity`) REFERENCES `mfa_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.601 s

ALTER TABLE `mfa_activities_log`
DROP FOREIGN KEY `mfa_activities_log_ibfk_4`,
ADD FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.444 s

ALTER TABLE `mfa_leads`
DROP FOREIGN KEY `mfa_leads_ibfk_2`,
ADD FOREIGN KEY (`from_source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.423 s

ALTER TABLE `mfa_contacts_types`
DROP FOREIGN KEY `mfa_contacts_types_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.413 s

ALTER TABLE `mfa_files`
DROP FOREIGN KEY `mfa_files_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.550 s

ALTER TABLE `mfa_materials_notes`
DROP FOREIGN KEY `mfa_materials_notes_ibfk_1`,
ADD FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 1.210 s

ALTER TABLE `mfa_notes`
DROP FOREIGN KEY `mfa_notes_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.471 s

ALTER TABLE `mfa_notes`
DROP FOREIGN KEY `mfa_notes_ibfk_2`,
ADD FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.502 s

ALTER TABLE `mfa_scales`
DROP FOREIGN KEY `mfa_scales_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.386 s

ALTER TABLE `mfa_sources`
DROP FOREIGN KEY `mfa_sources_ibfk_1`,
ADD FOREIGN KEY (`type`) REFERENCES `mfa_sources_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.606 s

ALTER TABLE `mfa_sources`
DROP FOREIGN KEY `mfa_sources_ibfk_2`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.583 s

ALTER TABLE `mfa_sources`
DROP FOREIGN KEY `mfa_sources_ibfk_3`,
ADD FOREIGN KEY (`status`) REFERENCES `mfa_status_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.765 s

ALTER TABLE `mfa_sources_types`
DROP FOREIGN KEY `mfa_sources_types_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.323 s

ALTER TABLE `mfa_sources_flags`
DROP FOREIGN KEY `mfa_sources_flags_ibfk_2`,
ADD FOREIGN KEY (`flag`) REFERENCES `mfa_special_flags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 1.264 s

ALTER TABLE `papers`
CHANGE `abstract_status` `abstract_status` enum('pending','author_approved','journal_approved','open_access','not_approved','toc_only') COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'pending' AFTER `abstract`,
COMMENT=''; -- 1.530 s

INSERT INTO `mfa_status_options` (`status`)
VALUES ('On hold'); -- 0.058 s

ALTER TABLE `mfa_dataset`
ADD `banner_text` varchar(255) NOT NULL,
COMMENT=''; -- 1.509 s

-- Import from here onward

ALTER TABLE `mfa_scales`
ADD `standard_multiplier` decimal(3,2) unsigned NOT NULL DEFAULT '1',
COMMENT=''; -- 0.599 s

ALTER TABLE `mfa_dataset`
CHANGE `multiscale` `multiscale` tinyint(1) unsigned NOT NULL COMMENT 'Indicates whether or not to include several scales' AFTER `time_log`,
ADD `multiscale_multiplier` tinyint(1) unsigned NOT NULL COMMENT 'If several scales are used, this indicates if a multiplier system should be used (otherwise this is a comparison between scales instead)' AFTER `multiscale`,
COMMENT=''; -- 0.586 s

ALTER TABLE `mfa_dataset`
CHANGE `multiscale_multiplier` `multiscale_as_proxy` tinyint(1) unsigned NOT NULL COMMENT 'If several scales are used, this indicates if a multiplier system should be used (otherwise this is a comparison between scales instead)' AFTER `multiscale`,
COMMENT=''; -- 0.511 s

ALTER TABLE `mfa_data`
ADD `multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1',
COMMENT=''; -- 0.530 s

ALTER TABLE `mfa_scales`
CHANGE `standard_multiplier` `standard_multiplier` decimal(5,2) unsigned NOT NULL DEFAULT '1.00' AFTER `name`,
COMMENT=''; -- 0.479 s

ALTER TABLE `mfa_scales`
CHANGE `standard_multiplier` `standard_multiplier` decimal(5,4) unsigned NOT NULL DEFAULT '1.00' AFTER `name`,
COMMENT=''; -- 0.810 s

ALTER TABLE `mfa_dataset`
ADD `multiple_values` enum('calculate_average','do_not_allow','select_best') COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'calculate_average',
COMMENT=''; -- 1.399 s

ALTER TABLE `mfa_data`
ADD `include_in_totals` tinyint(1) unsigned NOT NULL DEFAULT '1',
COMMENT=''; -- 0.525 s


CREATE TABLE `mfa_transportation_modes` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(50) NOT NULL
) COMMENT='' ENGINE='InnoDB'; -- 0.365 s


INSERT INTO `mfa_transportation_modes` (`id`, `name`) VALUES
(1,	'Bicycle'),
(2,	'Car'),
(3,	'Bus'),
(4,	'Train'),
(5,	'Taxi'),
(6,	'Walking'),
(7,	'Other');

CREATE TABLE `mfa_transportation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `transportation_mode` smallint(5) unsigned NOT NULL,
  `route_url` varchar(1200) COLLATE utf8_unicode_ci NOT NULL,
  `distance` int(11) NOT NULL COMMENT 'in km',
  `notes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `transportation_mode` (`transportation_mode`),
  CONSTRAINT `mfa_transportation_ibfk_1` FOREIGN KEY (`activity`) REFERENCES `mfa_activities_log` (`id`),
  CONSTRAINT `mfa_transportation_ibfk_2` FOREIGN KEY (`transportation_mode`) REFERENCES `mfa_transportation_modes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `mfa_transportation`
ADD `cost` decimal(7,2) unsigned NOT NULL,
ADD `from` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `cost`,
ADD `to` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `from`,
COMMENT=''; -- 0.736 s

ALTER TABLE `mfa_transportation`
CHANGE `from` `from_destination` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `cost`,
CHANGE `to` `to_destination` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `from_destination`,
COMMENT=''; -- 0.697 s

ALTER TABLE `mfa_transportation`
CHANGE `distance` `distance` decimal(7,2) NOT NULL COMMENT 'in km' AFTER `route_url`,
COMMENT=''; -- 0.821 s

ALTER TABLE `mfa_transportation`
DROP `route_url`,
COMMENT=''; -- 0.876 s

INSERT INTO `mfa_dataset` (`research_project`, `name`, `year_start`, `year_end`, `access`, `decimal_precision`, `measurement`, `contact_management`, `dqi`, `time_log`, `multiscale`, `multiscale_as_proxy`, `type`, `banner_text`, `multiple_values`)
VALUES (NULL, 'Iceland', '1962', '2008', 2, '0', 'metric tons', '0', '0', '0', '0', '0', '1', '', 2); -- 0.328 s

INSERT INTO `mfa_groups` (`section`, `name`, `dataset`)
VALUES ('A', 'Domestic Extraction', '12'); -- 0.166 s

INSERT INTO `mfa_groups` (`section`, `name`, `dataset`)
VALUES ('B', 'Imports', '12'); -- 0.151 s

INSERT INTO `mfa_groups` (`section`, `name`, `dataset`)
VALUES ('D', 'Exports', '12'); -- 0.171 s

CREATE TABLE `mfa_material_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `source` int(10) unsigned NULL,
  `contact` int(10) unsigned NULL,
  `material` mediumint(8) unsigned NULL,
  `group` mediumint(8) unsigned NULL,
  FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`),
  FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`),
  FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`),
  FOREIGN KEY (`group`) REFERENCES `mfa_groups` (`id`)
) COMMENT='' ENGINE='InnoDB'; -- 0.741 s

ALTER TABLE `mfa_material_links`
CHANGE `group` `mfa_group` mediumint(8) unsigned NULL AFTER `material`,
COMMENT=''; -- 0.536 s

ALTER TABLE `mfa_material_links`
DROP FOREIGN KEY `mfa_material_links_ibfk_1`,
ADD FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.530 s

ALTER TABLE `mfa_material_links`
DROP FOREIGN KEY `mfa_material_links_ibfk_2`,
ADD FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 1.489 s

ALTER TABLE `mfa_material_links`
DROP FOREIGN KEY `mfa_material_links_ibfk_3`,
ADD FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 1.228 s

ALTER TABLE `mfa_material_links`
DROP FOREIGN KEY `mfa_material_links_ibfk_4`,
ADD FOREIGN KEY (`mfa_group`) REFERENCES `mfa_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.496 s

ALTER TABLE `mfa_contacts`
ADD `belongs_to` int unsigned NULL COMMENT 'This indicates if this contact is part of (e.g. employee of or sub division of) another contact' AFTER `employer`,
COMMENT=''; -- 1.006 s

ALTER TABLE `mfa_contacts`
ADD FOREIGN KEY (`belongs_to`) REFERENCES `mfa_contacts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE; -- 0.775 s

ALTER TABLE `mfa_sources`
ADD `belongs_to` int(10) unsigned NULL AFTER `created`,
ADD FOREIGN KEY (`belongs_to`) REFERENCES `mfa_contacts` (`id`),
COMMENT=''; -- 1.002 s

ALTER TABLE `mfa_dataset`
ADD `resource_management` tinyint(1) unsigned NOT NULL COMMENT 'Elaborate options to track asociated resources and material flows' AFTER `contact_management`,
COMMENT=''; -- 0.588 s

ALTER TABLE `mfa_materials_notes`
ADD `user` int(11) NOT NULL,
ADD `source` int(10) unsigned NULL AFTER `user`,
ADD `contact` int(10) unsigned NULL AFTER `source`,
ADD FOREIGN KEY (`user`) REFERENCES `users` (`user_id`),
ADD FOREIGN KEY (`source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE,
ADD FOREIGN KEY (`contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE,
COMMENT=''; -- 0.908 s

ALTER TABLE `mfa_materials_notes`
DROP FOREIGN KEY `mfa_materials_notes_ibfk_1`,
ADD FOREIGN KEY (`material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.602 s

-- Industry sectors created

CREATE TABLE `mfa_industries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `indicator_weight` tinyint(1) unsigned NULL,
  `indicator_value` tinyint(1) unsigned NULL,
  `indicator_environment` tinyint(1) unsigned NULL,
  `indicator_companies` tinyint(1) unsigned NULL,
  `indicator_illegality` tinyint(1) unsigned NULL,
  `description_companies` text NULL,
  `description_illegality` text NULL,
  `description_associations` tinyint(1) unsigned NULL,
  `description_general` text NULL,
  FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) COMMENT='' ENGINE='InnoDB'; -- 0.381 s

CREATE TABLE `mfa_industries_sectors` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL
) COMMENT='' ENGINE='InnoDB'; -- 0.304 s

INSERT INTO `mfa_industries_sectors` (`id`, `name`) VALUES
(1,	'Few large companies dominate'),
(2,	'Medium-sized companies dominate'),
(3,	'Small-size companies dominate'),
(4,	'Companies exist in all sizes; no particular company size dominates');

ALTER TABLE `mfa_industries`
ADD `sector` tinyint(3) unsigned NULL,
ADD FOREIGN KEY (`sector`) REFERENCES `mfa_industries_sectors` (`id`),
COMMENT=''; -- 0.638 s

ALTER TABLE `mfa_industries`
CHANGE `description_associations` `description_associations` text NULL AFTER `description_illegality`,
COMMENT=''; -- 1.169 s

ALTER TABLE `mfa_contacts`
ADD `industry` int(10) unsigned NULL AFTER `belongs_to`,
ADD FOREIGN KEY (`industry`) REFERENCES `mfa_industries` (`id`),
COMMENT=''; -- 1.198 s

ALTER TABLE `mfa_contacts`
ADD `url` varchar(255) NOT NULL AFTER `type`,
COMMENT=''; -- 1.677 s

ALTER TABLE `mfa_dataset`
ADD `source_paper` int(11) NULL AFTER `research_project`,
ADD FOREIGN KEY (`source_paper`) REFERENCES `papers` (`id`) ON DELETE RESTRICT,
COMMENT=''; -- 1.685 s

CREATE TABLE `mfa_industries_labels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` enum('value','mass') NOT NULL,
  `score` tinyint(1) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) COMMENT=''; -- 0.980 s

ALTER TABLE `mfa_industries_labels`
DROP FOREIGN KEY `mfa_industries_labels_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.463 s

CREATE TABLE `mfa_industries_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` enum('mass','value') NOT NULL,
  `flow` enum('extraction','import','export') NOT NULL,
  `industry` int(10) unsigned NOT NULL,
  `score` tinyint(1) unsigned NOT NULL,
  FOREIGN KEY (`industry`) REFERENCES `mfa_industries` (`id`) ON DELETE CASCADE
) COMMENT=''; -- 0.467 s

ALTER TABLE `mfa_industries_scores`
DROP FOREIGN KEY `mfa_industries_scores_ibfk_1`,
ADD FOREIGN KEY (`industry`) REFERENCES `mfa_industries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.417 s

ALTER TABLE `mfa_industries_scores`
CHANGE `flow` `flow` enum('extraction','import','export','output') COLLATE 'utf8_unicode_ci' NOT NULL AFTER `type`,
COMMENT=''; -- 0.429 s

CREATE TABLE `mfa_sankey` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `dataset` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT=''; -- 0.546 s

CREATE TABLE `mfa_sankey_nodes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `sankey` int(10) unsigned NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `to_name` varchar(255) NOT NULL,
  `weight` int unsigned NOT NULL,
  FOREIGN KEY (`sankey`) REFERENCES `mfa_sankey` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT=''; -- 0.412 s

CREATE TABLE `mfa_population` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `year` year NOT NULL,
  `dataset` int(10) unsigned NOT NULL,
  `population` int unsigned NOT NULL,
  FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`)
) COMMENT='' ENGINE='InnoDB'; -- 0.575 s

ALTER TABLE `mfa_population`
DROP FOREIGN KEY `mfa_population_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.583 s

INSERT INTO `analysis_options_types` (`name`)
VALUES ('Per-capita flows'); -- 0.117 s

ALTER TABLE `mfa_indicators_formula`
ADD `mfa_material` mediumint(8) unsigned NULL,
ADD FOREIGN KEY (`mfa_material`) REFERENCES `mfa_materials` (`id`) ON DELETE CASCADE,
COMMENT=''; -- 0.446 s

INSERT INTO `tags` (`tag`, `parent`, `description`)
VALUES ('Thesis', '1', ''); -- 0.059 s

CREATE TABLE `users_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user` int(11) NOT NULL,
  `privilege` enum('admin') NOT NULL DEFAULT 'admin',
  FOREIGN KEY (`user`) REFERENCES `users` (`user_id`)
) COMMENT=''; -- 0.301 s

ALTER TABLE `users_admin`
DROP FOREIGN KEY `users_admin_ibfk_1`,
ADD FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.370 s

ALTER TABLE `users_admin`
ADD INDEX `privilege` (`privilege`); -- 0.202 s

ALTER TABLE `analysis_options`
ADD `measure` varchar(255) COLLATE 'utf8_unicode_ci' NULL,
COMMENT=''; -- 0.319 s

ALTER TABLE `users_admin`
COMMENT='' ENGINE='InnoDB'; -- 0.469 s

ALTER TABLE `users_admin`
ADD FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.503 s

ALTER TABLE `analysis`
CHANGE `option` `analysis_option` int(10) unsigned NOT NULL AFTER `id`,
COMMENT=''; -- 0.664 s

ALTER TABLE `analysis`
CHANGE `result` `result` decimal(15,2) NULL AFTER `case_study`,
COMMENT=''; -- 0.490 s

ALTER TABLE `research`
ADD `deleted_on` datetime NULL,
COMMENT=''; -- 0.264 s

ALTER TABLE `dqi_sections`
DROP FOREIGN KEY `dqi_sections_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.357 s

ALTER TABLE `mfa_scales`
DROP FOREIGN KEY `mfa_scales_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.659 s

ALTER TABLE `mfa_sources`
DROP FOREIGN KEY `mfa_sources_ibfk_2`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.758 s

ALTER TABLE `mfa_contacts`
DROP FOREIGN KEY `mfa_contacts_ibfk_7`,
ADD FOREIGN KEY (`belongs_to`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.991 s

ALTER TABLE `mfa_sources`
DROP FOREIGN KEY `mfa_sources_ibfk_4`,
ADD FOREIGN KEY (`belongs_to`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.703 s

ALTER TABLE `mfa_activities`
DROP FOREIGN KEY `mfa_activities_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.429 s

ALTER TABLE `mfa_leads`
DROP FOREIGN KEY `mfa_leads_ibfk_2`,
ADD FOREIGN KEY (`from_source`) REFERENCES `mfa_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.548 s

ALTER TABLE `mfa_leads`
DROP FOREIGN KEY `mfa_leads_ibfk_1`,
ADD FOREIGN KEY (`from_contact`) REFERENCES `mfa_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.592 s

ALTER TABLE `mfa_files`
DROP FOREIGN KEY `mfa_files_ibfk_1`,
ADD FOREIGN KEY (`dataset`) REFERENCES `mfa_dataset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.557 s

ALTER TABLE `mfa_dqi`
DROP FOREIGN KEY `mfa_dqi_ibfk_1`,
ADD FOREIGN KEY (`classification`) REFERENCES `dqi_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; -- 0.439 s
