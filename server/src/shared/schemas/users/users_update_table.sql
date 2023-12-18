use cybozu_seatmap;

SET
    foreign_key_checks = 0;

CREATE TABLE `update_users`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `email`      varchar(250) NOT NULL,
    `password`   varchar(255) NOT NULL,
    `full_name`  varchar(250) NOT NULL,
    `role`       varchar(100)      DEFAULT 'user',
    `avatar`     text,
    `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `test_column` varchar(250)  NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 41
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

INSERT INTO `update_users` (id, email, password, full_name, role, avatar, created_at, updated_at, test_column)
SELECT id, email, password, full_name, role, avatar, created_at, updated_at, NULL as test_column
FROM `users`;

DROP TABLE `users`;

RENAME
    TABLE `update_users` TO `users`;

SET
    foreign_key_checks = 1;