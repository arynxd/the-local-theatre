CREATE TABLE IF NOT EXISTS `user` (
  `id` char(36) PRIMARY KEY,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `dob` bigint NOT NULL,
  `joinDate` bigint NOT NULL,
  `permissions` int NOT NULL
);

CREATE TABLE IF NOT EXISTS `moderation_action` (
  `id` char(36) PRIMARY KEY,
  `moderator` char(36),
  `type` varchar(255)
);

CREATE TABLE IF NOT EXISTS `moderation_type` (
  `type` varchar(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS `post` (
  `id` char(36) PRIMARY KEY,
  `content` varchar(255) NOT NULL,
  `authorId` char(36),
  `createdAt` bigint NOT NULL,
  `editedAt` bigint DEFAULT null
);

CREATE TABLE IF NOT EXISTS `credential` (
  `userId` char(36) PRIMARY KEY,
  `email` varchar(64) NOT NULL UNIQUE,
  `password` varchar(124) NOT NULL,
  `token` char(64)
);

CREATE TABLE IF NOT EXISTS `comment` (
  `id` char(36) PRIMARY KEY,
  `authorId` char(36),
  `postId` char(36),
  `content` varchar(255) NOT NULL,
  `createdAt` bigint NOT NULL,
  `editedAt` bigint DEFAULT null
);

CREATE TABLE IF NOT EXISTS `user_prefs` (
  `userId` char(36) PRIMARY KEY,
  `theme` enum ('light', 'dark') NOT NULL
);

ALTER TABLE `moderation_action` ADD FOREIGN KEY (`moderator`) REFERENCES `user` (`id`);

ALTER TABLE `moderation_action` ADD FOREIGN KEY (`type`) REFERENCES `moderation_type` (`type`);

ALTER TABLE `post` ADD FOREIGN KEY (`authorId`) REFERENCES `user` (`id`);

ALTER TABLE `credential` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `comment` ADD FOREIGN KEY (`authorId`) REFERENCES `user` (`id`);

ALTER TABLE `comment` ADD FOREIGN KEY (`postId`) REFERENCES `post` (`id`);

ALTER TABLE `user_prefs` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);
