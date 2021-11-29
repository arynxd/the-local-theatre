CREATE TABLE IF NOT EXISTS `user`
(
    `id` char
(
    36
) PRIMARY KEY,
    `firstName` mediumtext NOT NULL,
    `lastName` mediumtext NOT NULL,
    `username` mediumtext NOT NULL,
    `dob` bigint NOT NULL,
    `joinDate` bigint NOT NULL,
    `permissions` int NOT NULL
    );

CREATE TABLE IF NOT EXISTS `moderation_action`
(
    `id` char
(
    36
) PRIMARY KEY,
    `moderator` char
(
    36
),
    `type` varchar
(
    50
)
    );

CREATE TABLE IF NOT EXISTS `moderation_type`
(
    `type` varchar
(
    50
) PRIMARY KEY
    );

CREATE TABLE IF NOT EXISTS `post`
(
    `id` char
(
    36
) PRIMARY KEY,
    `content` mediumtext NOT NULL,
    `title` mediumtext NOT NULL,
    `authorId` char
(
    36
),
    `createdAt` bigint NOT NULL,
    `editedAt` bigint DEFAULT null
    );

CREATE TABLE IF NOT EXISTS `credential`
(
    `userId` char
(
    36
) PRIMARY KEY,
    `email` mediumtext NOT NULL UNIQUE,
    `password` mediumtext NOT NULL,
    `token` char
(
    64
)
    );

CREATE TABLE IF NOT EXISTS `comment`
(
    `id` char
(
    36
) PRIMARY KEY,
    `authorId` char
(
    36
),
    `postId` char
(
    36
),
    `content` mediumtext NOT NULL,
    `createdAt` bigint NOT NULL,
    `editedAt` bigint DEFAULT null
    );

CREATE TABLE IF NOT EXISTS `user_prefs`
(
    `userId` char
(
    36
) PRIMARY KEY,
    `theme` enum
(
    'light',
    'dark'
) NOT NULL
    );

ALTER TABLE `moderation_action`
    ADD FOREIGN KEY (`moderator`) REFERENCES `user` (`id`);

ALTER TABLE `moderation_action`
    ADD FOREIGN KEY (`type`) REFERENCES `moderation_type` (`type`);

ALTER TABLE `post`
    ADD FOREIGN KEY (`authorId`) REFERENCES `user` (`id`);

ALTER TABLE `credential`
    ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `comment`
    ADD FOREIGN KEY (`authorId`) REFERENCES `user` (`id`);

ALTER TABLE `comment`
    ADD FOREIGN KEY (`postId`) REFERENCES `post` (`id`);

ALTER TABLE `user_prefs`
    ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);
