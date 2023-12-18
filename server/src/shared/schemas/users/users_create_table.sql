use cybozu_seatmap;

CREATE TABLE IF NOT EXISTS `users`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `email`      varchar(250) NOT NULL,
    `password`   varchar(255) NOT NULL,
    `full_name`  varchar(250) NOT NULL,
    `role`       varchar(100)      DEFAULT 'user',
    `avatar`     text,
    `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 41
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci
