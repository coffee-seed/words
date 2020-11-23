CREATE DATABASE `words_db`;
ALTER DATABASE `words_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE USER `words_user`@`localhost` IDENTIFIED BY "pass123";
GRANT ALL PRIVILEGES ON `words`.* to `words_user`@`localhost`;
use words_db;

CREATE TABLE `users`(
	`id` 		INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `email`		VARCHAR(255) UNIQUE,
    `password`	VARCHAR(255)
);

CREATE TABLE `cookies`(
	`id` 		INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`session` 	VARCHAR(64) UNIQUE,
    `token`		VARCHAR(64),
	`uid`	INT UNSIGNED NOT NULL
);
CREATE TABLE `words`(
	`id` 		INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`uid` 		INT UNSIGNED NOT NULL,
    `image`		VARCHAR(255),
	`sound`		VARCHAR(255)
);