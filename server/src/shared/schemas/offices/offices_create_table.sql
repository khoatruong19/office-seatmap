CREATE TABLE IF NOT EXISTS `offices`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) NOT NULL,
    `visible`    tinyint(1)        DEFAULT '1',
    `blocks`     longtext,
    `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 27
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci