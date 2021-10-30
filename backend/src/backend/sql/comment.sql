CREATE TABLE IF NOT EXISTS `comment` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `uuid` CHAR(36) UNIQUE NOT NULL DEFAULT uuid_v4(),
  `author_id` CHAR(36),
  `post_id` CHAR(36),
  `content` varchar(255) NOT NULL,
  `created_at` number NOT NULL,
  `edited_at` number DEFAULT NULL,
  FOREIGN KEY (`author_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
);