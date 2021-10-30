CREATE TABLE `token` (
  `token` varchar(255),
  `user_id` CHAR(36),
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);
