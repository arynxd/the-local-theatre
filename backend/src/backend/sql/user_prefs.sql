CREATE TABLE IF NOT EXISTS `user_prefs` (
  `user_id` CHAR(36) PRIMARY KEY,
  `theme` ENUM('dark', 'light') NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);