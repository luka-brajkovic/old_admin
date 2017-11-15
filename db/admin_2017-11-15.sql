# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.1.25-MariaDB)
# Database: admin
# Generation Time: 2017-11-15 08:20:52 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table _content_banners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_banners`;

CREATE TABLE `_content_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `slika` longtext,
  `link_ka` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_banners_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_blog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_blog`;

CREATE TABLE `_content_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `datum_objave` datetime DEFAULT NULL,
  `uvod` longtext,
  `text` longtext,
  `slika` longtext,
  `galerija` longtext,
  `tagovi_vesti` longtext,
  `title_seo` longtext,
  `desc_seo` longtext,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_brand
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_brand`;

CREATE TABLE `_content_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `opis` longtext,
  `logo` longtext,
  `naslovna` varchar(255) DEFAULT NULL,
  `logo_grey` longtext,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  KEY `resource_id_2` (`resource_id`),
  KEY `url` (`url`),
  CONSTRAINT `_content_brand_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_cities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_cities`;

CREATE TABLE `_content_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `postanski_broj` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  KEY `resource_id_2` (`resource_id`),
  CONSTRAINT `_content_cities_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_comments`;

CREATE TABLE `_content_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pitanje` longtext,
  `odgovor` longtext,
  `vreme_odgovora` datetime NOT NULL,
  `proizvod` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  KEY `resource_id_2` (`resource_id`),
  KEY `system_date` (`system_date`),
  KEY `proizvod` (`proizvod`),
  CONSTRAINT `_content_comments_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_faq
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_faq`;

CREATE TABLE `_content_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_faq_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_html_blocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_html_blocks`;

CREATE TABLE `_content_html_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `text` longtext,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_html_blocks_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `_content_html_blocks` WRITE;
/*!40000 ALTER TABLE `_content_html_blocks` DISABLE KEYS */;

INSERT INTO `_content_html_blocks` (`id`, `resource_id`, `title`, `url`, `ordering`, `num_views`, `status`, `system_date`, `lang`, `text`)
VALUES
	(1,1,'Right side registration page','right-side-registration-page',2,0,1,'2017-11-06 09:06:10','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a vulputate ligula. Vestibulum tristique sapien ante, sed condimentum quam porttitor in. Aenean ac mauris pulvinar, aliquam dui eget, venenatis enim. Fusce luctus diam est, quis semper odio mattis vitae. Integer ut augue nisl. Ut ut dui eget sem pretium maximus vel sed velit. Nulla vitae lectus eget dui placerat rhoncus id sit amet risus. Nulla pretium commodo tortor in ullamcorper.</p>'),
	(2,2,'Right side sign up','right-side-sign-up',1,0,1,'2017-11-06 09:10:32','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a vulputate ligula. Vestibulum tristique sapien ante, sed condimentum quam porttitor in. Aenean ac mauris pulvinar, aliquam dui eget, venenatis enim. Fusce luctus diam est, quis semper odio mattis vitae. Integer ut augue nisl. Ut ut dui eget sem pretium maximus vel sed velit. Nulla vitae lectus eget dui placerat rhoncus id sit amet risus. Nulla pretium commodo tortor in ullamcorper.</p>'),
	(3,3,'Text on pop up on page does&#39;t exist','text-on-pop-up-on-page-doest-exist',4,0,1,'2017-11-06 09:12:30','1','<h1>Gre&scaron;ka</h1>\r\n<p>Po&scaron;tovani, stranica koju ste tražili vi&scaron;e nije dostupana.</p>\r\n<p>Izvinjavamo se zbog neprijatnosti!</p>'),
	(6,8,'Text on pop up when not sign up on newsletter','text-on-pop-up-when-not-sign-up-on-newsletter',7,0,1,'2017-11-10 13:47:04','1','<h1>Neuspe&scaron;na prijava</h1>\r\n<p>NISTE uspeli da se registrujete na na&scaron;u newsletter listu. Molimo Vas da proverite email koji ste uneli.</p>'),
	(7,9,'Text on pop up when is sign up on newsletter','text-on-pop-up-when-is-sign-up-on-newsletter',8,0,1,'2017-11-10 13:48:31','1','<h1>Uspe&scaron;na prijava</h1>\r\n<p>Uspe&scaron;no ste se prijavili na na&scaron;u newsletter listu. Hvala Vam na interesovanju!</p>'),
	(8,10,'Text in mail when user sign up on newsletter','text-in-mail-when-user-sign-up-on-newsletter',9,0,1,'2017-11-10 14:05:45','1','<p>Hvala Vam &scaron;to ste se prijavili na na&scaron;u newsletter listu. Uskoro u Va&scaron;em sandučetu očekujte najnovije aktuelnosti i popuste sa na&scaron;eg sajta.</p>\r\n<p>Srdačan pozdrav od tima sajta Sky computer center!</p>'),
	(9,11,'Account page right side','account-page-right-side',3,0,1,'2017-11-10 14:06:50','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a vulputate ligula. Vestibulum tristique sapien ante, sed condimentum quam porttitor in. Aenean ac mauris pulvinar, aliquam dui eget, venenatis enim. Fusce luctus diam est, quis semper odio mattis vitae. Integer ut augue nisl. Ut ut dui eget sem pretium maximus vel sed velit. Nulla vitae lectus eget dui placerat rhoncus id sit amet risus. Nulla pretium commodo tortor in ullamcorper.</p>'),
	(10,14,'Poruka NIJE posalta','poruka-nije-posalta',10,0,1,'2017-11-12 20:45:21','1','<p>Po&scaron;tovani, Va&scaron;a poruka NIJE poslata, molimo Vas proverite unete podatke!</p>'),
	(11,15,'Poruka je uspešno poslata','poruka-je-uspesno-poslata',11,0,1,'2017-11-12 20:46:38','1','<p>Po&scaron;tovani, Va&scaron;a poruka je uspe&scaron;no poslata, uskoro očekujte na&scaron; odgovor!</p>'),
	(12,16,'General text for all products','general-text-for-all-products',12,0,1,'2017-11-12 20:50:12','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>');

/*!40000 ALTER TABLE `_content_html_blocks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table _content_newsletter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_newsletter`;

CREATE TABLE `_content_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_newsletter_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_newsletter_slanje
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_newsletter_slanje`;

CREATE TABLE `_content_newsletter_slanje` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `text` longtext,
  `link_text` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `link2_text` varchar(255) DEFAULT NULL,
  `link2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_newsletter_slanje_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `_content_newsletter_slanje` WRITE;
/*!40000 ALTER TABLE `_content_newsletter_slanje` DISABLE KEYS */;

INSERT INTO `_content_newsletter_slanje` (`id`, `resource_id`, `title`, `url`, `ordering`, `num_views`, `status`, `system_date`, `lang`, `text`, `link_text`, `link`, `link2_text`, `link2`)
VALUES
	(1,27,'Newsletter 1','newsletter-1',2,0,0,'2017-11-14 12:05:53','1','','','','','');

/*!40000 ALTER TABLE `_content_newsletter_slanje` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table _content_pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_pages`;

CREATE TABLE `_content_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `text` longtext,
  `title_seo` longtext,
  `desc_seo` longtext,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_pages_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `_content_pages` WRITE;
/*!40000 ALTER TABLE `_content_pages` DISABLE KEYS */;

INSERT INTO `_content_pages` (`id`, `resource_id`, `title`, `url`, `ordering`, `num_views`, `status`, `system_date`, `lang`, `text`, `title_seo`, `desc_seo`)
VALUES
	(1,17,'Uslovi koriščenja','uslovi-koriscenja',2,0,1,'2017-11-12 21:06:26','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>\r\n<p>Nullam dui mi, faucibus vitae lectus non, commodo blandit arcu. Vestibulum suscipit massa sed urna hendrerit, eget eleifend tortor finibus. Aenean at rutrum augue. Ut dictum ligula a sem fermentum sollicitudin. Sed eu nibh eget mauris fermentum tristique non nec est. Praesent rutrum tincidunt ante, eu finibus nunc finibus id. Sed laoreet lorem fringilla scelerisque volutpat. Donec at justo nisl. Integer placerat lorem cursus lacus feugiat maximus vel ut est. Fusce auctor dapibus fermentum. Proin a facilisis ante, sed suscipit orci. Pellentesque eu mauris non arcu consectetur imperdiet.</p>\r\n<p>Mauris nulla lorem, iaculis ac porttitor non, porttitor vel turpis. Proin quis ultrices risus, eu placerat neque. Praesent ac metus vel libero viverra tincidunt. Phasellus blandit libero quis eleifend fringilla. Donec id accumsan massa. Praesent lobortis commodo sem, nec tempor leo pellentesque id. Fusce id tempor nulla. Praesent bibendum dictum felis quis porttitor. Maecenas eu felis justo.</p>\r\n<p>Nam ut orci nec ex consequat consectetur eu eu lectus. Nam eleifend purus sit amet orci posuere imperdiet in nec tellus. Ut lacinia, lorem a lacinia ornare, est arcu dapibus elit, eu dapibus mauris quam in ipsum. Mauris nec odio elementum, laoreet lorem vel, porttitor urna. Morbi et hendrerit est. Vivamus enim odio, feugiat quis fermentum eget, dignissim et ex. Etiam nisl arcu, finibus sed lorem quis, tempus aliquet libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis nisl turpis, pretium ac odio et, pulvinar pharetra lorem. Maecenas at est at nunc vestibulum iaculis et at erat. Donec suscipit, nisi ullamcorper lacinia feugiat, tortor erat sagittis sapien, venenatis tincidunt neque sem id enim. Phasellus sed nunc libero. Phasellus ac turpis nibh.</p>','',''),
	(2,18,'Zaštita potrošača','zastita-potrosaca',3,0,1,'2017-11-12 21:07:53','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>\r\n<p>Nullam dui mi, faucibus vitae lectus non, commodo blandit arcu. Vestibulum suscipit massa sed urna hendrerit, eget eleifend tortor finibus. Aenean at rutrum augue. Ut dictum ligula a sem fermentum sollicitudin. Sed eu nibh eget mauris fermentum tristique non nec est. Praesent rutrum tincidunt ante, eu finibus nunc finibus id. Sed laoreet lorem fringilla scelerisque volutpat. Donec at justo nisl. Integer placerat lorem cursus lacus feugiat maximus vel ut est. Fusce auctor dapibus fermentum. Proin a facilisis ante, sed suscipit orci. Pellentesque eu mauris non arcu consectetur imperdiet.</p>\r\n<p>Mauris nulla lorem, iaculis ac porttitor non, porttitor vel turpis. Proin quis ultrices risus, eu placerat neque. Praesent ac metus vel libero viverra tincidunt. Phasellus blandit libero quis eleifend fringilla. Donec id accumsan massa. Praesent lobortis commodo sem, nec tempor leo pellentesque id. Fusce id tempor nulla. Praesent bibendum dictum felis quis porttitor. Maecenas eu felis justo.</p>\r\n<p>Nam ut orci nec ex consequat consectetur eu eu lectus. Nam eleifend purus sit amet orci posuere imperdiet in nec tellus. Ut lacinia, lorem a lacinia ornare, est arcu dapibus elit, eu dapibus mauris quam in ipsum. Mauris nec odio elementum, laoreet lorem vel, porttitor urna. Morbi et hendrerit est. Vivamus enim odio, feugiat quis fermentum eget, dignissim et ex. Etiam nisl arcu, finibus sed lorem quis, tempus aliquet libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis nisl turpis, pretium ac odio et, pulvinar pharetra lorem. Maecenas at est at nunc vestibulum iaculis et at erat. Donec suscipit, nisi ullamcorper lacinia feugiat, tortor erat sagittis sapien, venenatis tincidunt neque sem id enim. Phasellus sed nunc libero. Phasellus ac turpis nibh.</p>','',''),
	(3,19,'Politika privatnosti','politika-privatnosti',4,0,1,'2017-11-12 21:08:08','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>\r\n<p>Nullam dui mi, faucibus vitae lectus non, commodo blandit arcu. Vestibulum suscipit massa sed urna hendrerit, eget eleifend tortor finibus. Aenean at rutrum augue. Ut dictum ligula a sem fermentum sollicitudin. Sed eu nibh eget mauris fermentum tristique non nec est. Praesent rutrum tincidunt ante, eu finibus nunc finibus id. Sed laoreet lorem fringilla scelerisque volutpat. Donec at justo nisl. Integer placerat lorem cursus lacus feugiat maximus vel ut est. Fusce auctor dapibus fermentum. Proin a facilisis ante, sed suscipit orci. Pellentesque eu mauris non arcu consectetur imperdiet.</p>\r\n<p>Mauris nulla lorem, iaculis ac porttitor non, porttitor vel turpis. Proin quis ultrices risus, eu placerat neque. Praesent ac metus vel libero viverra tincidunt. Phasellus blandit libero quis eleifend fringilla. Donec id accumsan massa. Praesent lobortis commodo sem, nec tempor leo pellentesque id. Fusce id tempor nulla. Praesent bibendum dictum felis quis porttitor. Maecenas eu felis justo.</p>\r\n<p>Nam ut orci nec ex consequat consectetur eu eu lectus. Nam eleifend purus sit amet orci posuere imperdiet in nec tellus. Ut lacinia, lorem a lacinia ornare, est arcu dapibus elit, eu dapibus mauris quam in ipsum. Mauris nec odio elementum, laoreet lorem vel, porttitor urna. Morbi et hendrerit est. Vivamus enim odio, feugiat quis fermentum eget, dignissim et ex. Etiam nisl arcu, finibus sed lorem quis, tempus aliquet libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis nisl turpis, pretium ac odio et, pulvinar pharetra lorem. Maecenas at est at nunc vestibulum iaculis et at erat. Donec suscipit, nisi ullamcorper lacinia feugiat, tortor erat sagittis sapien, venenatis tincidunt neque sem id enim. Phasellus sed nunc libero. Phasellus ac turpis nibh.</p>','',''),
	(4,20,'O nama','o-nama',5,21,1,'2017-11-12 21:08:53','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>\r\n<p>Nullam dui mi, faucibus vitae lectus non, commodo blandit arcu. Vestibulum suscipit massa sed urna hendrerit, eget eleifend tortor finibus. Aenean at rutrum augue. Ut dictum ligula a sem fermentum sollicitudin. Sed eu nibh eget mauris fermentum tristique non nec est. Praesent rutrum tincidunt ante, eu finibus nunc finibus id. Sed laoreet lorem fringilla scelerisque volutpat. Donec at justo nisl. Integer placerat lorem cursus lacus feugiat maximus vel ut est. Fusce auctor dapibus fermentum. Proin a facilisis ante, sed suscipit orci. Pellentesque eu mauris non arcu consectetur imperdiet.</p>\r\n<p>Mauris nulla lorem, iaculis ac porttitor non, porttitor vel turpis. Proin quis ultrices risus, eu placerat neque. Praesent ac metus vel libero viverra tincidunt. Phasellus blandit libero quis eleifend fringilla. Donec id accumsan massa. Praesent lobortis commodo sem, nec tempor leo pellentesque id. Fusce id tempor nulla. Praesent bibendum dictum felis quis porttitor. Maecenas eu felis justo.</p>\r\n<p>Nam ut orci nec ex consequat consectetur eu eu lectus. Nam eleifend purus sit amet orci posuere imperdiet in nec tellus. Ut lacinia, lorem a lacinia ornare, est arcu dapibus elit, eu dapibus mauris quam in ipsum. Mauris nec odio elementum, laoreet lorem vel, porttitor urna. Morbi et hendrerit est. Vivamus enim odio, feugiat quis fermentum eget, dignissim et ex. Etiam nisl arcu, finibus sed lorem quis, tempus aliquet libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis nisl turpis, pretium ac odio et, pulvinar pharetra lorem. Maecenas at est at nunc vestibulum iaculis et at erat. Donec suscipit, nisi ullamcorper lacinia feugiat, tortor erat sagittis sapien, venenatis tincidunt neque sem id enim. Phasellus sed nunc libero. Phasellus ac turpis nibh.</p>','',''),
	(5,21,'Načini plaćanja','nacini-placanja',6,0,1,'2017-11-12 21:09:42','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>\r\n<p>Nullam dui mi, faucibus vitae lectus non, commodo blandit arcu. Vestibulum suscipit massa sed urna hendrerit, eget eleifend tortor finibus. Aenean at rutrum augue. Ut dictum ligula a sem fermentum sollicitudin. Sed eu nibh eget mauris fermentum tristique non nec est. Praesent rutrum tincidunt ante, eu finibus nunc finibus id. Sed laoreet lorem fringilla scelerisque volutpat. Donec at justo nisl. Integer placerat lorem cursus lacus feugiat maximus vel ut est. Fusce auctor dapibus fermentum. Proin a facilisis ante, sed suscipit orci. Pellentesque eu mauris non arcu consectetur imperdiet.</p>\r\n<p>Mauris nulla lorem, iaculis ac porttitor non, porttitor vel turpis. Proin quis ultrices risus, eu placerat neque. Praesent ac metus vel libero viverra tincidunt. Phasellus blandit libero quis eleifend fringilla. Donec id accumsan massa. Praesent lobortis commodo sem, nec tempor leo pellentesque id. Fusce id tempor nulla. Praesent bibendum dictum felis quis porttitor. Maecenas eu felis justo.</p>\r\n<p>Nam ut orci nec ex consequat consectetur eu eu lectus. Nam eleifend purus sit amet orci posuere imperdiet in nec tellus. Ut lacinia, lorem a lacinia ornare, est arcu dapibus elit, eu dapibus mauris quam in ipsum. Mauris nec odio elementum, laoreet lorem vel, porttitor urna. Morbi et hendrerit est. Vivamus enim odio, feugiat quis fermentum eget, dignissim et ex. Etiam nisl arcu, finibus sed lorem quis, tempus aliquet libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis nisl turpis, pretium ac odio et, pulvinar pharetra lorem. Maecenas at est at nunc vestibulum iaculis et at erat. Donec suscipit, nisi ullamcorper lacinia feugiat, tortor erat sagittis sapien, venenatis tincidunt neque sem id enim. Phasellus sed nunc libero. Phasellus ac turpis nibh.</p>','',''),
	(7,25,'Naručivanje telefonom ili e-mailom','narucivanje-telefonom-ili-e-mailom',7,0,1,'2017-11-13 08:04:51','1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mollis dolor id mauris cursus, vitae placerat urna bibendum. Sed maximus ligula eu aliquet dignissim. Vivamus sollicitudin quam eget ultrices tristique. Ut vehicula rutrum lectus. Sed non tempor magna. Nunc ante sapien, dapibus vitae cursus id, molestie quis nunc. Cras nibh nisi, efficitur ac odio quis, vehicula tincidunt est. In vitae ex ipsum.</p>\r\n<p>Proin vel placerat nisl, malesuada iaculis diam. Suspendisse eleifend ante libero, nec bibendum est iaculis ut. Pellentesque quis urna gravida, venenatis neque sit amet, luctus est. Aliquam tempor tellus id justo aliquam, ac interdum turpis aliquet. Quisque eu facilisis nisl. Nam varius turpis id dui laoreet porta. Praesent id odio neque.</p>\r\n<p>Nullam dui mi, faucibus vitae lectus non, commodo blandit arcu. Vestibulum suscipit massa sed urna hendrerit, eget eleifend tortor finibus. Aenean at rutrum augue. Ut dictum ligula a sem fermentum sollicitudin. Sed eu nibh eget mauris fermentum tristique non nec est. Praesent rutrum tincidunt ante, eu finibus nunc finibus id. Sed laoreet lorem fringilla scelerisque volutpat. Donec at justo nisl. Integer placerat lorem cursus lacus feugiat maximus vel ut est. Fusce auctor dapibus fermentum. Proin a facilisis ante, sed suscipit orci. Pellentesque eu mauris non arcu consectetur imperdiet.</p>\r\n<p>Mauris nulla lorem, iaculis ac porttitor non, porttitor vel turpis. Proin quis ultrices risus, eu placerat neque. Praesent ac metus vel libero viverra tincidunt. Phasellus blandit libero quis eleifend fringilla. Donec id accumsan massa. Praesent lobortis commodo sem, nec tempor leo pellentesque id. Fusce id tempor nulla. Praesent bibendum dictum felis quis porttitor. Maecenas eu felis justo.</p>\r\n<p>Nam ut orci nec ex consequat consectetur eu eu lectus. Nam eleifend purus sit amet orci posuere imperdiet in nec tellus. Ut lacinia, lorem a lacinia ornare, est arcu dapibus elit, eu dapibus mauris quam in ipsum. Mauris nec odio elementum, laoreet lorem vel, porttitor urna. Morbi et hendrerit est. Vivamus enim odio, feugiat quis fermentum eget, dignissim et ex. Etiam nisl arcu, finibus sed lorem quis, tempus aliquet libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis nisl turpis, pretium ac odio et, pulvinar pharetra lorem. Maecenas at est at nunc vestibulum iaculis et at erat. Donec suscipit, nisi ullamcorper lacinia feugiat, tortor erat sagittis sapien, venenatis tincidunt neque sem id enim. Phasellus sed nunc libero. Phasellus ac turpis nibh.</p>','','');

/*!40000 ALTER TABLE `_content_pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table _content_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_products`;

CREATE TABLE `_content_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `nabavljac` varchar(255) NOT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `technical_description` longtext,
  `marketing_description` longtext NOT NULL,
  `product_image` longtext,
  `price` decimal(11,2) DEFAULT NULL,
  `old_price` decimal(11,2) DEFAULT NULL,
  `najprodavanije` varchar(255) DEFAULT NULL,
  `preporuka` varchar(255) DEFAULT NULL,
  `slika_1` longtext,
  `slika_2` longtext,
  `slika_3` longtext,
  `slika_4` longtext,
  `slika_5` longtext,
  `akcija` varchar(255) DEFAULT NULL,
  `master_price` decimal(11,2) DEFAULT NULL,
  `slika_6` longtext,
  `slika_7` longtext,
  `slika_8` longtext,
  `slika_9` longtext,
  `slika_10` longtext,
  `master_status` varchar(255) DEFAULT NULL,
  `gratis_id` varchar(255) DEFAULT NULL,
  `gratis_id_2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  KEY `product_code` (`product_code`),
  KEY `brand` (`brand`),
  KEY `price` (`price`),
  KEY `title` (`title`),
  KEY `url` (`url`),
  KEY `resource_id_2` (`resource_id`),
  KEY `title_2` (`title`),
  KEY `preporuka` (`preporuka`),
  KEY `price_2` (`price`),
  KEY `old_price` (`old_price`),
  KEY `brand_2` (`brand`),
  KEY `product_code_2` (`product_code`),
  KEY `gratis_id` (`gratis_id`),
  KEY `master_status` (`master_status`),
  KEY `master_price` (`master_price`),
  KEY `url_2` (`url`),
  CONSTRAINT `_content_products_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `_content_products` WRITE;
/*!40000 ALTER TABLE `_content_products` DISABLE KEYS */;

INSERT INTO `_content_products` (`id`, `resource_id`, `title`, `url`, `ordering`, `num_views`, `status`, `system_date`, `updated`, `lang`, `product_code`, `brand`, `nabavljac`, `warranty`, `technical_description`, `marketing_description`, `product_image`, `price`, `old_price`, `najprodavanije`, `preporuka`, `slika_1`, `slika_2`, `slika_3`, `slika_4`, `slika_5`, `akcija`, `master_price`, `slika_6`, `slika_7`, `slika_8`, `slika_9`, `slika_10`, `master_status`, `gratis_id`, `gratis_id_2`)
VALUES
	(1,5,'Test product','test-product',2,0,0,'2017-11-08 13:01:14','0000-00-00 00:00:00','1','','','','','','','',0.00,0.00,'','','','','','','','',0.00,'','','','','','','','');

/*!40000 ALTER TABLE `_content_products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table _content_slider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_slider`;

CREATE TABLE `_content_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `slika` longtext,
  `link_ka` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`),
  CONSTRAINT `_content_slider_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table _content_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `_content_users`;

CREATE TABLE `_content_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `system_date` datetime NOT NULL,
  `lang` varchar(255) NOT NULL,
  `ime` varchar(255) DEFAULT NULL,
  `prezime` varchar(255) DEFAULT NULL,
  `e-mail` varchar(255) DEFAULT NULL,
  `mobilni_telefon` varchar(255) DEFAULT NULL,
  `fiksni_telefon` varchar(255) DEFAULT NULL,
  `ulica_i_broj` varchar(255) DEFAULT NULL,
  `sprat` varchar(255) DEFAULT NULL,
  `broj_stana` varchar(255) DEFAULT NULL,
  `grad` varchar(255) DEFAULT NULL,
  `postanski_broj` varchar(255) DEFAULT NULL,
  `naselje` varchar(255) DEFAULT NULL,
  `lozinka` varchar(255) DEFAULT NULL,
  `newsletter` varchar(255) DEFAULT NULL,
  `poslat_email` date NOT NULL,
  `tip_kupca` varchar(255) DEFAULT NULL,
  `naziv_firme` varchar(255) DEFAULT NULL,
  `pib` varchar(255) DEFAULT NULL,
  `dostava_ulica_i_broj` varchar(255) DEFAULT NULL,
  `dostava_postanski_broj` varchar(255) DEFAULT NULL,
  `dostava_grad` varchar(255) DEFAULT NULL,
  `dostava_fiksni_telefon` varchar(255) DEFAULT NULL,
  `dostava_naselje` varchar(255) DEFAULT NULL,
  `dostava_mobilni_telefon` varchar(255) DEFAULT NULL,
  `facebook_id` bigint(20) unsigned NOT NULL,
  `fb_user` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table administrators
# ------------------------------------------------------------

DROP TABLE IF EXISTS `administrators`;

CREATE TABLE `administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) NOT NULL COMMENT '1 - admin (all privilegies ), 2 - moderator (only content)',
  `username` varchar(255) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role`),
  CONSTRAINT `administrators_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `administrators` WRITE;
/*!40000 ALTER TABLE `administrators` DISABLE KEYS */;

INSERT INTO `administrators` (`id`, `role`, `username`, `password`, `fullname`, `email`)
VALUES
	(2,1,'admin','f8148a81ce2a4b1dec518b4710a0acc9','Admin','office@webdizajnsrbija.com');

/*!40000 ALTER TABLE `administrators` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `level` int(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` int(4) NOT NULL COMMENT '1 - active , 2 - inactive',
  `ordering` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `title_seo` longtext,
  `desc_seo` longtext,
  `keys_seo` longtext,
  `procenat_dodavanje_na_cenu` varchar(255) DEFAULT NULL,
  `do_50000` varchar(255) DEFAULT NULL,
  `do_70000` varchar(255) DEFAULT NULL,
  `do_85000` varchar(255) DEFAULT NULL,
  `do_100000` varchar(255) DEFAULT NULL,
  `do_120000` varchar(255) DEFAULT NULL,
  `do_150000` varchar(255) DEFAULT NULL,
  `do_170000` varchar(255) DEFAULT NULL,
  `do_200000` varchar(255) DEFAULT NULL,
  `od_200001` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_type_id` (`content_type_id`),
  KEY `resource_id` (`resource_id`),
  KEY `lang` (`lang`),
  KEY `ordering` (`ordering`),
  KEY `ordering_2` (`ordering`),
  KEY `status` (`status`),
  KEY `url` (`url`),
  KEY `parent_id` (`parent_id`),
  KEY `resource_id_2` (`resource_id`),
  KEY `title` (`title`),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `categories_ibfk_3` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `categories_ibfk_4` FOREIGN KEY (`lang`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table categories_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories_content`;

CREATE TABLE `categories_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_resource_id` int(11) NOT NULL,
  `content_resource_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_resource_id` (`category_resource_id`),
  KEY `content_resource_id` (`content_resource_id`),
  CONSTRAINT `categories_content_ibfk_1` FOREIGN KEY (`category_resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `categories_content_ibfk_2` FOREIGN KEY (`content_resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table categories_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories_fields`;

CREATE TABLE `categories_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `default_value` longtext NOT NULL,
  `show_in_list` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1 - global field for all categories, 0 - field for specific content type',
  PRIMARY KEY (`id`),
  KEY `content_type_id` (`content_type_id`),
  CONSTRAINT `categories_fields_ibfk_1` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table content_type_dimensions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `content_type_dimensions`;

CREATE TABLE `content_type_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `crop_resize` tinyint(4) NOT NULL COMMENT '1 - crop, 2 - resize',
  `width` varchar(255) CHARACTER SET utf8 NOT NULL,
  `height` varchar(255) CHARACTER SET utf8 NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `crop_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_type_id` (`content_type_id`),
  CONSTRAINT `content_type_dimensions_ibfk_1` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `content_type_dimensions` WRITE;
/*!40000 ALTER TABLE `content_type_dimensions` DISABLE KEYS */;

INSERT INTO `content_type_dimensions` (`id`, `title`, `url`, `crop_resize`, `width`, `height`, `content_type_id`, `crop_type`)
VALUES
	(2,'885x417','885x417',1,'885','417',67,1),
	(3,'640x512','640x512',1,'640','512',72,1),
	(4,'382x306','382x306',1,'382','306',72,1),
	(9,'300x300','300x300',1,'300','300',80,0),
	(10,'640x640','640x640',1,'400','400',80,1),
	(11,'GT 350','gt-350',1,'350','350',80,0),
	(12,'GB 640','gb-640',2,'640','640',80,0),
	(13,'630x354','630x354',1,'630','354',72,0),
	(14,'71x57','71x57',1,'71','57',72,1),
	(15,'630x354','630x354',1,'630','354',73,3),
	(16,'140x60','140x60',1,'140','60',73,3),
	(17,'80x34','80x34',1,'80','34',73,3),
	(18,'210x168','210x168',1,'210','168',72,0);

/*!40000 ALTER TABLE `content_type_dimensions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table content_type_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `content_type_fields`;

CREATE TABLE `content_type_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `field_type` varchar(255) NOT NULL COMMENT ' - text  - textarea  - wysiwyg  - select  - radio  - checkbox  - datapicker  - file  - select_table',
  `default_value` longtext NOT NULL,
  `show_in_list` tinyint(4) NOT NULL COMMENT '0 - do not show in list, 1 - show in list',
  `searchable` tinyint(4) NOT NULL COMMENT '0 - not searchable, 1 - searchable',
  `all_languages` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_type_id` (`content_type_id`),
  CONSTRAINT `content_type_fields_ibfk_1` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `content_type_fields` WRITE;
/*!40000 ALTER TABLE `content_type_fields` DISABLE KEYS */;

INSERT INTO `content_type_fields` (`id`, `title`, `column_name`, `content_type_id`, `field_type`, `default_value`, `show_in_list`, `searchable`, `all_languages`, `ordering`)
VALUES
	(357,'Text','text',66,'wysiwyg','',0,0,0,0),
	(358,'Slika','slika',67,'image','',1,1,0,0),
	(360,'Link_ka','link_ka',67,'text','',1,0,0,0),
	(361,'Text','text',68,'wysiwyg','',0,0,0,0),
	(362,'Title SEO','title_seo',68,'textarea','',0,0,0,0),
	(363,'Desc SEO','desc_seo',68,'textarea','',0,0,0,0),
	(365,'Ime','ime',69,'text','',0,0,0,1),
	(366,'Prezime','prezime',69,'text','',0,0,0,2),
	(367,'E-mail','e-mail',69,'text','',1,1,0,3),
	(368,'Mobilni telefon','mobilni_telefon',69,'text','',1,1,0,5),
	(369,'Fiksni telefon','fiksni_telefon',69,'text','',0,0,0,6),
	(370,'Ulica i broj','ulica_i_broj',69,'text','',0,0,0,7),
	(371,'Sprat','sprat',69,'text','',0,0,0,8),
	(372,'Broj stana','broj_stana',69,'text','',0,0,0,9),
	(373,'Grad','grad',69,'select_table','_content_gradovi,resource_id,title',0,0,0,10),
	(374,'Postanski broj','postanski_broj',69,'text','',0,0,0,11),
	(375,'Naselje','naselje',69,'text','',0,0,0,12),
	(376,'Lozinka','lozinka',69,'text','',0,0,0,4),
	(377,'Newsletter','newsletter',69,'select','Da,Ne',0,0,0,13),
	(378,'Postanski broj','postanski_broj',70,'text','',1,1,0,0),
	(379,'Slika','slika',71,'file','',0,0,0,0),
	(380,'Link ka','link_ka',71,'text','',0,0,0,0),
	(381,'Product Code','product_code',72,'text','',0,1,0,2),
	(383,'Brand','brand',72,'select_table','_content_brend,resource_id,title',0,1,0,3),
	(386,'Warranty','warranty',72,'text','',0,0,0,6),
	(391,'Technical Description','technical_description',72,'textarea','',0,0,0,5),
	(393,'Product Image','product_image',72,'image','',1,0,0,7),
	(396,'Marketing description','marketing_description',72,'wysiwyg','',0,0,0,4),
	(397,'Price','price',72,'text','',1,1,0,19),
	(398,'Old Price','old_price',72,'text','',0,0,0,20),
	(399,'Najprodavanije','najprodavanije',72,'select','Ne,Da',1,1,0,24),
	(401,'Preporuka','preporuka',72,'select','Ne,Da',1,1,0,25),
	(402,'Opis','opis',73,'wysiwyg','',0,0,0,1),
	(403,'Logo','logo',73,'image','',0,0,0,2),
	(406,'Title SEO','title_seo',72,'textarea','',0,0,0,26),
	(407,'Desc SEO','desc_seo',72,'textarea','',0,0,0,27),
	(409,'Slika 1','slika_1',72,'image','',0,0,0,8),
	(410,'Slika 2','slika_2',72,'image','',0,0,0,9),
	(411,'Slika 3','slika_3',72,'image','',0,0,0,10),
	(412,'Slika 4','slika_4',72,'image','',0,0,0,11),
	(413,'Slika 5','slika_5',72,'image','',0,0,0,12),
	(423,'Akcija','akcija',72,'select','Ne,Da',0,0,0,23),
	(427,'Text','text',79,'wysiwyg','',0,0,0,0),
	(428,'Link text','link_text',79,'text','',0,0,0,0),
	(429,'Link','link',79,'text','',0,0,0,0),
	(430,'Link2 text','link2_text',79,'text','',0,0,0,0),
	(431,'Link2','link2',79,'text','',0,0,0,0),
	(433,'Datum objave','datum_objave',80,'datepicker','',0,0,0,0),
	(434,'Uvod','uvod',80,'textarea','',0,0,0,0),
	(435,'Text','text',80,'wysiwyg','',0,0,0,0),
	(436,'Slika','slika',80,'image','',0,0,0,0),
	(437,'Galerija','galerija',80,'gallery','',0,0,0,0),
	(438,'Tagovi vesti','tagovi_vesti',80,'textarea','',0,0,0,0),
	(439,'Title SEO','title_seo',80,'textarea','',0,0,0,0),
	(440,'Desc SEO','desc_seo',80,'textarea','',0,0,0,0),
	(446,'Master price','master_price',72,'text','',0,0,0,18),
	(447,'Slika 6','slika_6',72,'image','',0,0,0,13),
	(448,'Slika 7','slika_7',72,'image','',0,0,0,14),
	(449,'Slika 8','slika_8',72,'image','',0,0,0,15),
	(450,'Slika 9','slika_9',72,'image','',0,0,0,16),
	(451,'Slika 10','slika_10',72,'image','',0,0,0,17),
	(452,'Master status','master_status',72,'select','Default,Active',0,0,0,1),
	(453,'Naslovna','naslovna',73,'select','Ne,Da',0,0,0,4),
	(454,'Gratis ID','gratis_id',72,'text','',0,0,0,21),
	(455,'Email','email',81,'text','',0,0,0,0),
	(456,'Pitanje','pitanje',81,'textarea','',0,0,0,0),
	(457,'Odgovor','odgovor',81,'wysiwyg','',0,0,0,0),
	(458,'Proizvod','proizvod',81,'select_table','_content_proizvodi,resource_id,title',0,0,0,0),
	(459,'Gratis ID 2','gratis_id_2',72,'text','',0,0,0,22);

/*!40000 ALTER TABLE `content_type_fields` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table content_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `content_types`;

CREATE TABLE `content_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comments` tinyint(4) NOT NULL COMMENT '0 - disable, 1 - enable',
  `category_type` tinyint(4) NOT NULL COMMENT '0 - no category, 1 - single category, 2 - multicategory',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `content_types` WRITE;
/*!40000 ALTER TABLE `content_types` DISABLE KEYS */;

INSERT INTO `content_types` (`id`, `title`, `ordering`, `table_name`, `url`, `comments`, `category_type`)
VALUES
	(66,'HTML Blocks',9,'_content_html_blocks','html-blocks',0,0),
	(67,'Slider',8,'_content_slider','slider',0,0),
	(68,'Pages',3,'_content_pages','pages',0,0),
	(69,'Users',5,'_content_users','users',0,0),
	(70,'Cities',6,'_content_cities','cities',0,0),
	(71,'Banners',10,'_content_banners','banners',0,0),
	(72,'Products',1,'_content_products','products',0,1),
	(73,'Brand',2,'_content_brand','brand',0,0),
	(76,'Newsletter',11,'_content_newsletter','newsletter',0,0),
	(79,'Newsletter slanje',12,'_content_newsletter_slanje','newsletter-slanje',0,0),
	(80,'Blog',4,'_content_blog','blog',0,0),
	(81,'Comments',7,'_content_comments','comments',0,0),
	(82,'FAQ',10,'_content_faq','faq',0,0);

/*!40000 ALTER TABLE `content_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dimensions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dimensions`;

CREATE TABLE `dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `crop_resize` tinyint(4) NOT NULL COMMENT '1 - crop, 2 - resize',
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `dimensions` WRITE;
/*!40000 ALTER TABLE `dimensions` DISABLE KEYS */;

INSERT INTO `dimensions` (`id`, `title`, `url`, `crop_resize`, `width`, `height`, `table_name`)
VALUES
	(1,'156x156','156x156',1,156,156,'albums'),
	(2,'640','640',2,640,0,'albums'),
	(3,'222x100','222x100',1,222,100,'categories'),
	(4,'480x325','480x325',1,480,325,'categories');

/*!40000 ALTER TABLE `dimensions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fields`;

CREATE TABLE `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `default_value` longtext NOT NULL,
  `show_in_list` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `fields` WRITE;
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;

INSERT INTO `fields` (`id`, `title`, `column_name`, `table_name`, `field_type`, `default_value`, `show_in_list`)
VALUES
	(4,'Title seo','title_seo','categories','textarea','',0),
	(5,'Desc seo','desc_seo','categories','textarea','',0);

/*!40000 ALTER TABLE `fields` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table filter_headers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `filter_headers`;

CREATE TABLE `filter_headers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) NOT NULL,
  `lang` int(2) NOT NULL DEFAULT '1',
  `cat_resource_id` int(11) NOT NULL,
  `show` int(1) NOT NULL COMMENT '1 - checkbox, 2-selectbox',
  `ordering` int(3) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table filter_joins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `filter_joins`;

CREATE TABLE `filter_joins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` int(2) NOT NULL DEFAULT '1',
  `product_rid` int(11) NOT NULL,
  `fv_id` longtext NOT NULL,
  `fh_id` int(11) NOT NULL,
  `cat_rid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fh_id` (`fh_id`),
  KEY `product_rid` (`product_rid`),
  KEY `fh_id_2` (`fh_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table filter_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `filter_values`;

CREATE TABLE `filter_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` int(2) NOT NULL DEFAULT '1',
  `ordering` int(1) NOT NULL,
  `fh_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fh_id` (`fh_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table korpa
# ------------------------------------------------------------

DROP TABLE IF EXISTS `korpa`;

CREATE TABLE `korpa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `system_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prezime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `adresa` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `grad` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `napomena` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nacin_placanja` int(11) NOT NULL DEFAULT '1',
  `naselje` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `num_views` int(11) NOT NULL,
  `datum_poslata` datetime NOT NULL,
  `admin_zaposleni` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `broj_fakture` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table languages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `is_default` tinyint(4) NOT NULL COMMENT '1 - default, 0 - not default',
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;

INSERT INTO `languages` (`id`, `title`, `code`, `is_default`, `ordering`)
VALUES
	(1,'Srpski','sr',1,0);

/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table list_zelja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `list_zelja`;

CREATE TABLE `list_zelja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_rid` int(11) NOT NULL,
  `user_rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table menu_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu_items`;

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1 - link, 2 - content, 3 - cateogry',
  `parent_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL COMMENT 'if type = 1',
  `resource_id` int(11) NOT NULL COMMENT 'if type is 2 or 3',
  `ordering` int(11) NOT NULL,
  `open_type` tinyint(4) NOT NULL COMMENT '1 - same window, 2 - new window',
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;

INSERT INTO `menu_items` (`id`, `menu_id`, `type`, `parent_id`, `title`, `url`, `resource_id`, `ordering`, `open_type`)
VALUES
	(1,1,1,0,'Naslovna','/',0,1,1),
	(2,1,1,0,'O nama','/strana/o-nama',0,2,1),
	(3,1,1,0,'Robne marke','/robne-marke',0,2,1),
	(4,1,1,0,'Aktuelnosti','/aktuelnosti',0,2,1),
	(5,1,1,0,'Kontakt','/kontakt',0,2,1),
	(6,2,1,0,'Prijava','/prijava',0,1,1),
	(7,2,1,0,'Registracija','/registracija',0,2,1),
	(8,2,1,0,'Korpa','/korpa',0,2,1),
	(9,3,1,0,'Uslovi koriščenja','/strana/uslovi-koriscenja',0,2,1),
	(10,3,1,0,'Politika privatnosti','/strana/politika-privatnosti',0,3,1),
	(11,3,1,0,'Načini plaćanja','/strana/nacini-placanja',0,4,1),
	(12,3,1,0,'Akcije','/proizvodi-na-akciji',0,0,1),
	(13,3,1,0,'Aktuelnosti','/aktuelnosti',0,1,1),
	(14,3,1,0,'Naručivanje telefonom ili e-mailom','/strana/narucivanje-telefonom-ili-e-mailom',0,5,1),
	(15,2,1,0,'FAQ','/faq',0,2,1);

/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;

INSERT INTO `menus` (`id`, `resource_id`, `title`, `url`, `lang_id`)
VALUES
	(1,13,'Menu','menu',1),
	(2,23,'Korisnicki nalog','korisnicki-nalog',1),
	(3,24,'Kupovina','kupovina',1);

/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table proizvodi_korpe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `proizvodi_korpe`;

CREATE TABLE `proizvodi_korpe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_rid` int(11) NOT NULL,
  `gratis` int(11) NOT NULL,
  `gratis2` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cena` varchar(50) NOT NULL,
  `kolicina` int(11) NOT NULL,
  `korpa_rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `lang` int(1) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `value` int(1) NOT NULL,
  `date` date NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table resources
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resources`;

CREATE TABLE `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;

INSERT INTO `resources` (`id`, `table_name`)
VALUES
	(1,'_content_html_blocks'),
	(2,'_content_html_blocks'),
	(3,'_content_html_blocks'),
	(5,'_content_proizvodi'),
	(8,'_content_html_blocks'),
	(9,'_content_html_blocks'),
	(10,'_content_html_blocks'),
	(11,'_content_html_blocks'),
	(13,'menus'),
	(14,'_content_html_blocks'),
	(15,'_content_html_blocks'),
	(16,'_content_html_blocks'),
	(17,'_content_pages'),
	(18,'_content_pages'),
	(19,'_content_pages'),
	(20,'_content_pages'),
	(21,'_content_pages'),
	(23,'menus'),
	(24,'menus'),
	(25,'_content_pages'),
	(27,'_content_newsletter_slanje');

/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `admin_access` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `title`, `admin_access`)
VALUES
	(1,'Administrator',1),
	(2,'Moderator',2);

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_host` varchar(100) NOT NULL,
  `site_title` longtext NOT NULL,
  `site_footer` longtext NOT NULL,
  `site_description` longtext NOT NULL,
  `site_email` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `site_account` varchar(255) NOT NULL,
  `site_firm` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `site_phone` varchar(100) NOT NULL,
  `site_phone_2` varchar(100) NOT NULL,
  `site_facebook` varchar(100) NOT NULL,
  `site_facebook_app_id` varchar(40) NOT NULL,
  `site_twitter` varchar(100) NOT NULL,
  `site_twitter_username` varchar(100) NOT NULL,
  `site_google_plus` varchar(100) NOT NULL,
  `site_instagram` varchar(100) NOT NULL,
  `site_pinterest` varchar(100) NOT NULL,
  `site_youtube` varchar(100) NOT NULL,
  `site_vimeo` varchar(100) NOT NULL,
  `site_address` varchar(255) NOT NULL,
  `site_city` varchar(100) NOT NULL,
  `site_country` varchar(100) NOT NULL,
  `site_zip` int(20) NOT NULL,
  `site_linkedin` varchar(100) NOT NULL,
  `site_koordinate` varchar(100) NOT NULL,
  `site_api_key` varchar(255) NOT NULL,
  `site_embed` varchar(300) NOT NULL,
  `online_shop` int(11) NOT NULL,
  `site_analytic` varchar(15) NOT NULL,
  `site_verification` varchar(100) NOT NULL,
  `site_outgoing_server` varchar(100) NOT NULL,
  `site_smtp_port` int(5) DEFAULT NULL,
  `site_username` varchar(100) NOT NULL,
  `site_password` varchar(100) NOT NULL,
  `site_working_time_1` varchar(100) NOT NULL,
  `site_working_time_2` varchar(100) NOT NULL,
  `site_working_time_3` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `site_host`, `site_title`, `site_footer`, `site_description`, `site_email`, `lang_id`, `site_account`, `site_firm`, `site_phone`, `site_phone_2`, `site_facebook`, `site_facebook_app_id`, `site_twitter`, `site_twitter_username`, `site_google_plus`, `site_instagram`, `site_pinterest`, `site_youtube`, `site_vimeo`, `site_address`, `site_city`, `site_country`, `site_zip`, `site_linkedin`, `site_koordinate`, `site_api_key`, `site_embed`, `online_shop`, `site_analytic`, `site_verification`, `site_outgoing_server`, `site_smtp_port`, `site_username`, `site_password`, `site_working_time_1`, `site_working_time_2`, `site_working_time_3`)
VALUES
	(1,'http://admin.local/','WEB SITE TITLE','SITE FOOTER','SITE DESC','info@wds.in.rs',1,'','SITE NAME','+381 11 1234567','+381 11 7654321','#','','#','@sitetwitterusername','#','','','','','Neznanog junaka bb','Beograd','Srbija',11000,'','44.802504, 20.466429','','',1,'','','mail.wds.in.rs',465,'info@wds.in.rs','123info456','09:00 - 17:00','09:00 - 13:00','Ne radi');

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
