CREATE TABLE IF NOT EXISTS `post` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `uuid` CHAR(36) UNIQUE NOT NULL DEFAULT uuid_v4(),
  `content` varchar(255) NOT NULL,
  `author_id` CHAR(36),
  `created_at` number NOT NULL,
  `edited_at` number DEFAULT NULL,
  FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
);