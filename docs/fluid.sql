-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny1


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema fluid
--

CREATE DATABASE IF NOT EXISTS fluid;
USE fluid;

--
-- Definition of table `datasources`
--

DROP TABLE IF EXISTS `datasources`;
CREATE TABLE `datasources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `settings` text collate utf8_unicode_ci NOT NULL,
  `read_only` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `datasources_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `lkp_datasources_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `datasources`
--

/*!40000 ALTER TABLE `datasources` DISABLE KEYS */;
INSERT INTO `datasources` VALUES  (1,'false',2,'a:2:{s:4:\"rows\";s:218:\"[{\\\"type\\\":null,\\\"value\\\":null},{\\\"type\\\":null,\\\"value\\\":null},{\\\"type\\\":null,\\\"value\\\":null},{\\\"type\\\":null,\\\"value\\\":null},{\\\"type\\\":null,\\\"value\\\":null},{\\\"type\\\":null,\\\"value\\\":null},{\\\"type\\\":null,\\\"value\\\":null}]\";s:9:\"tableName\";s:5:\"users\";}',0);
/*!40000 ALTER TABLE `datasources` ENABLE KEYS */;


--
-- Definition of table `lkp_datasources_types`
--

DROP TABLE IF EXISTS `lkp_datasources_types`;
CREATE TABLE `lkp_datasources_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lkp_datasources_types`
--

/*!40000 ALTER TABLE `lkp_datasources_types` DISABLE KEYS */;
INSERT INTO `lkp_datasources_types` VALUES  (1,'File'),
 (5,'Json'),
 (4,'MutableObject'),
 (2,'Table'),
 (3,'Xml');
/*!40000 ALTER TABLE `lkp_datasources_types` ENABLE KEYS */;


--
-- Definition of table `lkp_datatypes`
--

DROP TABLE IF EXISTS `lkp_datatypes`;
CREATE TABLE `lkp_datatypes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `editor` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lkp_datatypes`
--

/*!40000 ALTER TABLE `lkp_datatypes` DISABLE KEYS */;
INSERT INTO `lkp_datatypes` VALUES  (1,'text','YAHOO.widget.DataTable.formatText'),
 (2,'string','YAHOO.widget.DataTable.formatTextarea'),
 (3,'integer','YAHOO.widget.DataTable.formatNumber'),
 (4,'float','YAHOO.widget.DataTable.formatNumber'),
 (5,'binary','YAHOO.widget.DataTable.formatTextarea'),
 (6,'date','YAHOO.widget.DataTable.formatDate'),
 (8,'boolean','YAHOO.widget.DataTable.formatCheckbox');
/*!40000 ALTER TABLE `lkp_datatypes` ENABLE KEYS */;


--
-- Definition of table `lkp_groups`
--

DROP TABLE IF EXISTS `lkp_groups`;
CREATE TABLE `lkp_groups` (
  `id` int(10) unsigned NOT NULL,
  `father_id` int(10) unsigned default NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `father_id` (`father_id`),
  CONSTRAINT `lkp_groups_ibfk_1` FOREIGN KEY (`father_id`) REFERENCES `lkp_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lkp_groups`
--

/*!40000 ALTER TABLE `lkp_groups` DISABLE KEYS */;
INSERT INTO `lkp_groups` VALUES  (1,2,'Root'),
 (2,3,'Admin'),
 (3,4,'Power user'),
 (4,NULL,'Guest');
/*!40000 ALTER TABLE `lkp_groups` ENABLE KEYS */;


--
-- Definition of table `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE `models` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `source` enum('file','database','webservice') character set utf8 collate utf8_bin NOT NULL COMMENT 'Where the data are stored',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `models`
--

/*!40000 ALTER TABLE `models` DISABLE KEYS */;
INSERT INTO `models` VALUES  (1,'group',0x6461746162617365),
 (2,'user',0x6461746162617365);
/*!40000 ALTER TABLE `models` ENABLE KEYS */;


--
-- Definition of table `models_properties`
--

DROP TABLE IF EXISTS `models_properties`;
CREATE TABLE `models_properties` (
  `model_id` int(10) unsigned NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `is_required` tinyint(1) default NULL,
  PRIMARY KEY  (`model_id`,`name`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `models_properties_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `lkp_datatypes` (`id`),
  CONSTRAINT `models_properties_ibfk_2` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `models_properties`
--

/*!40000 ALTER TABLE `models_properties` DISABLE KEYS */;
INSERT INTO `models_properties` VALUES  (1,'name',2,1),
 (2,'email',2,1),
 (2,'password',2,1),
 (2,'username',2,1);
/*!40000 ALTER TABLE `models_properties` ENABLE KEYS */;


--
-- Definition of table `objects`
--

DROP TABLE IF EXISTS `objects`;
CREATE TABLE `objects` (
  `id` bigint(20) unsigned NOT NULL auto_increment COMMENT 'guid of the object',
  `model_id` int(10) unsigned NOT NULL COMMENT 'This help us define some data useful for this particular model',
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `model_id` (`model_id`),
  CONSTRAINT `objects_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `objects`
--

/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
INSERT INTO `objects` VALUES  (1,2,'0000-00-00 00:00:00'),
 (2,2,'2009-01-11 14:54:14'),
 (3,2,'2009-01-11 14:56:19');
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;


--
-- Definition of table `objects_properties`
--

DROP TABLE IF EXISTS `objects_properties`;
CREATE TABLE `objects_properties` (
  `object_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `value` blob NOT NULL,
  PRIMARY KEY  (`object_id`,`name`),
  KEY `type_id` (`type_id`),
  KEY `value_string` (`value`(255)),
  CONSTRAINT `objects_properties_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `objects` (`id`),
  CONSTRAINT `objects_properties_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `lkp_datatypes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `objects_properties`
--

/*!40000 ALTER TABLE `objects_properties` DISABLE KEYS */;
INSERT INTO `objects_properties` VALUES  (1,'email',2,0x666162696F2E7472657A7A6940637431392E6974),
 (1,'password',2,0x35626161363165346339623933663366303638323235306236636638333331623765653638666438),
 (1,'username',2,0x77657A7A79),
 (2,'email',2,0x666162696F2E7472657A7A6940676D61696C2E636F6D),
 (2,'password',2,0x787878),
 (3,'email',2,0x666162696F2E7472657A7A6940637431392E6974);
/*!40000 ALTER TABLE `objects_properties` ENABLE KEYS */;


--
-- Definition of table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `zone_id` int(10) unsigned NOT NULL,
  `father_id` int(10) unsigned default NULL,
  `url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `template` varchar(255) collate utf8_unicode_ci NOT NULL,
  `layout` varchar(255) collate utf8_unicode_ci NOT NULL,
  `custom_css` text collate utf8_unicode_ci NOT NULL,
  `custom_js` text collate utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `theme` varchar(255) collate utf8_unicode_ci NOT NULL,
  `is_default` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `zone_id` (`zone_id`),
  KEY `father_id` (`father_id`),
  KEY `is_default` (`is_default`),
  CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`father_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES  (1,1,NULL,'/default/home','HomePage','default','three_columns','','','2009-05-16 15:55:08','default',1);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;


--
-- Definition of table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `id` int(11) NOT NULL default '0',
  `path` varchar(255) default NULL,
  `configuration` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugins`
--

/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;


--
-- Definition of table `portlet_instances`
--

DROP TABLE IF EXISTS `portlet_instances`;
CREATE TABLE `portlet_instances` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `page_id` int(10) unsigned NOT NULL,
  `container_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `portlet_title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `portlet_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `identifier` varchar(255) collate utf8_unicode_ci NOT NULL COMMENT 'the portlet identifier (DOM id), it ensure that a portlet has always the same ID even if the page is reloaded',
  `settings` text collate utf8_unicode_ci,
  `order` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `portlet_instances_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `portlet_instances`
--

/*!40000 ALTER TABLE `portlet_instances` DISABLE KEYS */;
INSERT INTO `portlet_instances` VALUES  (23,1,'column1','hello world','hello_world','portlet_10015056',NULL,0),
 (25,1,'column3','hello world','hello_world','portlet_10015106',NULL,1),
 (53,1,'column1','hello world','hello_world','portlet_10617280',NULL,1),
 (62,1,'column2','hello world','hello_world','portlet_11320956',NULL,0),
 (63,1,'column1','static text','static_text','portlet_11705303','a:1:{s:5:\"value\";s:111:\"<a href=\\\'#\\\' onclick=\\\'F.use([\\\"simpleeditor\\\", \\\"imagecropper\\\"], function(){alert(\\\"test\\\")})\\\'>Click me</a>\";}',2),
 (64,1,'column1','login','login','portlet_11731689',NULL,0),
 (65,1,'column2','rich text','rich_text','portlet_11830591','a:1:{s:5:\"value\";s:53:\"asd<strong>asdas</strong>d as<br><br>asdasdasdadsadsa\";}',1),
 (66,1,'column1','hello world','hello_world','portlet_13533477',NULL,0);
/*!40000 ALTER TABLE `portlet_instances` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` varchar(255) collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `surname` varchar(255) collate utf8_unicode_ci NOT NULL,
  `nick` varchar(255) collate utf8_unicode_ci NOT NULL,
  `organization` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES  (1,'fabio.trezzi@ct19.it','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','Fabio','Trezzi','wezzy','artBits snc');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


--
-- Definition of table `users2groups`
--

DROP TABLE IF EXISTS `users2groups`;
CREATE TABLE `users2groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `users2groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users2groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `lkp_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users2groups`
--

/*!40000 ALTER TABLE `users2groups` DISABLE KEYS */;
INSERT INTO `users2groups` VALUES  (1,1);
/*!40000 ALTER TABLE `users2groups` ENABLE KEYS */;


--
-- Definition of table `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE `zones` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `zones`
--

/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` VALUES  (1,'default');
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
