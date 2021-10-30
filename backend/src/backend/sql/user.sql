CREATE TABLE IF NOT EXISTS `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `uuid` CHAR(36) UNIQUE NOT NULL DEFAULT uuid_v4(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `dob` int NOT NULL,
  `join_date` int NOT NULL,
  `permissions` int NOT NULL
);