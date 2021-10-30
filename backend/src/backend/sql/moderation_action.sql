CREATE TABLE IF NOT EXISTS `moderation_action` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `uuid` CHAR(36) UNIQUE NOT NULL DEFAULT uuid_v4(),
  `moderator` CHAR(36),
  `type` varchar(255),
  FOREIGN KEY (`moderator`) REFERENCES `user` (`id`),
  FOREIGN KEY (`type`) REFERENCES `moderation_type` (`type`)
);