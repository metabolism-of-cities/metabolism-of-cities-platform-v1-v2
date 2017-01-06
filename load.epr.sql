ALTER TABLE `papers`
CHANGE `abstract` `abstract` text COLLATE 'utf8_unicode_ci' NULL AFTER `doi`;

ALTER TABLE `papers`
CHANGE `author` `author` text COLLATE 'utf8_unicode_ci' NULL AFTER `title_native`,
CHANGE `volume` `volume` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `author`,
CHANGE `issue` `issue` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `volume`,
CHANGE `pages` `pages` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `issue`,
CHANGE `doi` `doi` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `year`,
CHANGE `editor_comments` `editor_comments` text COLLATE 'utf8_unicode_ci' NULL AFTER `abstract_status`,
CHANGE `link` `link` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `editor_comments`,
CHANGE `keywords` `keywords` varchar(600) COLLATE 'utf8_unicode_ci' NULL AFTER `link`;

INSERT INTO `tags_parents` (`name`, `parent_order`)
VALUES ('Unclassified', '');

INSERT INTO `tags_parents` (`name`, `parent_order`)
VALUES ('Countries and Regions', '');

INSERT INTO `tags_parents` (`name`, `parent_order`)
VALUES ('WEEE', '');

INSERT INTO `tags_parents` (`name`, `parent_order`)
VALUES ('End-of-life', '');

INSERT INTO `tags_parents` (`name`, `parent_order`)
VALUES ('Life cycle', '');

INSERT INTO `tags_parents` (`name`, `parent_order`)
VALUES ('Environment', '');

ALTER TABLE `tags`
CHANGE `description` `description` text COLLATE 'utf8_unicode_ci' NULL AFTER `parent`;

delete from people;
delete from tags;
delete from tags_papers;
delete from people_papers;
delete from papers;

ALTER TABLE people AUTO_INCREMENT=1;
ALTER TABLE tags AUTO_INCREMENT=1;
ALTER TABLE tags_papers AUTO_INCREMENT=1;
ALTER TABLE people_papers AUTO_INCREMENT=1;
ALTER TABLE papers AUTO_INCREMENT=1;

ALTER TABLE `sources` ADD INDEX `name` (`name`);
