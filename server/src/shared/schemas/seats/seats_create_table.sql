CREATE TABLE IF NOT EXISTS `seats`
(
    `id`         int         NOT NULL AUTO_INCREMENT,
    `label`      varchar(20) NOT NULL,
    `position`   int         NOT NULL,
    `available`  tinyint(1)       DEFAULT '1',
    `office_id`  int         NOT NULL,
    `user_id`    int              DEFAULT NULL,
    `created_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `office_id` (`office_id`),
    CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `seats_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 27
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci