
CREATE TABLE `cv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `uploaded` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cv_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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

