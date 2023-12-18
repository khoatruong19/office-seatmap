use cybozu_seatmap;

SET
    foreign_key_checks = 0;


ALTER TABLE `seats` DROP FOREIGN KEY `seats_ibfk_1`;
ALTER TABLE `seats` DROP FOREIGN KEY `seats_ibfk_2`;

CREATE TABLE `update_seats`
(
    `id`         int         NOT NULL AUTO_INCREMENT,
    `label`      varchar(20) NOT NULL,
    `position`   int         NOT NULL,
    `available`  tinyint(1)       DEFAULT '1',
    `office_id`  int         NOT NULL,
    `user_id`    int              DEFAULT NULL,
    `created_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `test_column` varchar(250)  NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `office_id` (`office_id`),
    CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `seats_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 27
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

INSERT INTO `update_seats` (id, label, position, available, office_id, user_id, created_at, updated_at, test_column)
SELECT id, label, position, available, office_id, user_id, created_at, updated_at, NULL as test_column
FROM `seats`;

DROP TABLE `seats`;

RENAME
    TABLE `update_seats` TO `seats`;

SET
    foreign_key_checks = 1;