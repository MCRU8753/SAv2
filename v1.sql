SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `v1` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci;
USE `v1`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text COLLATE utf8_slovenian_ci NOT NULL,
  `password` text COLLATE utf8_slovenian_ci NOT NULL,
  `email` text COLLATE utf8_slovenian_ci NOT NULL,
  `firstname` text COLLATE utf8_slovenian_ci NOT NULL,
  `lastname` text COLLATE utf8_slovenian_ci NOT NULL,
  `address` text COLLATE utf8_slovenian_ci NULL,
  `post` text COLLATE utf8_slovenian_ci NULL,
  `phone` text COLLATE utf8_slovenian_ci NULL,
  `admin` boolean COLLATE utf8_slovenian_ci NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_slovenian_ci NOT NULL,
  `description` text COLLATE utf8_slovenian_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

DROP TABLE IF EXISTS `ad_images`;
CREATE TABLE IF NOT EXISTS `ad_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) NOT NULL,
  `image` longblob NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ad_id`) REFERENCES `ads`(`id`) ON DELETE CASCADE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

DROP TABLE IF EXISTS `ads_users`;
CREATE TABLE IF NOT EXISTS `ads_users` (
  `ad_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`ad_id`, `user_id`),
  FOREIGN KEY (`ad_id`) REFERENCES `ads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

DROP TABLE IF EXISTS `ad_categories`;
CREATE TABLE IF NOT EXISTS `ad_categories` (
  `ad_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`ad_id`, `category_id`),
  FOREIGN KEY (`ad_id`) REFERENCES `ads`(`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8_slovenian_ci NOT NULL,
  `country` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ad_id`) REFERENCES `ads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;


INSERT INTO categories VALUES
(NULL, 'Avto-moto'),
(NULL, 'Telefonija'),
(NULL, 'Rekreacija'),
(NULL, 'Dom'),
(NULL, 'Računalništvo');

COMMIT;