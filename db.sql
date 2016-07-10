-- MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `cv`;
CREATE TABLE `cv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `uploaded` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cv_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cv` (`id`, `user_id`, `uploaded`, `last_edited`) VALUES
(1,	3,	'2016-07-08 17:49:05',	'2016-07-08 17:49:05');

DROP TABLE IF EXISTS `education`;
CREATE TABLE `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cv_id` int(10) unsigned NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date DEFAULT NULL,
  `facility` varchar(150) NOT NULL,
  `skills` varchar(250) DEFAULT NULL,
  `qualification` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_id` (`cv_id`),
  CONSTRAINT `education_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `education` (`id`, `cv_id`, `date_from`, `date_to`, `facility`, `skills`, `qualification`) VALUES
(1,	1,	'1988-01-01',	'1992-01-01',	'Vocation college A.S. Popov',	'Electro-technician',	'High school');

DROP TABLE IF EXISTS `employment`;
CREATE TABLE `employment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cv_id` int(10) unsigned NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date DEFAULT NULL,
  `employer` varchar(150) NOT NULL,
  `position` varchar(150) NOT NULL,
  `activity` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_id` (`cv_id`),
  CONSTRAINT `employment_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `employment` (`id`, `cv_id`, `date_from`, `date_to`, `employer`, `position`, `activity`) VALUES
(1,	1,	'2012-01-01',	NULL,	'Opencode Systems Ltd.',	'R&D Engineer',	'Developing tools for processing large amounts of data and statistics.'),
(2,	1,	'2008-01-01',	'2012-01-01',	'Sexwell EOOD',	'CTO',	'\n				Managing the IT department\n				Creating and managing the company web sites\n				Creating and developing the intra-company CRM/ERP system\n			'),
(3,	1,	'2007-01-01',	'2008-01-01',	'StangaOne',	'PHP & MySQL developer',	'Working on various company projects'),
(4,	1,	'2004-01-01',	'2007-01-01',	'Self-employed',	'WEB developer',	'Creating and developing web sites for a vast range of clients'),
(5,	1,	'2003-01-01',	'2004-01-01',	'Balkan restaurant, Quality Inn Horizon Hotel, Dubai, U.A.E.',	'Musician',	'Musician'),
(6,	1,	'2001-01-01',	'2003-01-01',	'Supporting Victims of Crimes and Combating Corruption Foundation',	'Project coordinator',	''),
(7,	1,	'1993-01-01',	'2001-01-01',	'Self-employed',	'Musician, tone producer',	'\n				Establishment of a music production and arrangement demo-studio;\n				Performing at clubs with a number of bands\n			'),
(8,	1,	'1990-01-01',	'1993-01-01',	'Club 113+, Sofia',	'Tone producer',	'');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `address` varchar(250) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `skype` varchar(50) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `driving_license` tinyint(1) NOT NULL DEFAULT '0',
  `nationality` varchar(50) DEFAULT NULL,
  `languages` text,
  `skills` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `name`, `birthday`, `address`, `phone`, `email`, `skype`, `linkedin`, `driving_license`, `nationality`, `languages`, `skills`) VALUES
(3,	'Daniel Sabev Denev',	'1974-10-13',	'Patriarh Evtimii blvd., Sofia, Bulgaria',	'+359 878 510 454',	'dusty@gbg.bg',	'dusty_raven',	'https://bg.linkedin.com/in/danieldenev',	1,	'Bulgarian',	'English, Russian',	'PHP, MySQL, Firebird, SQLite, HTML, CSS, JavaScript, Apache, Nginx, SVN, GIT, SEO, UX, DOS, Linux, Windows, Office, Photoshop, CorelDraw');

-- 2016-07-10 19:01:13
