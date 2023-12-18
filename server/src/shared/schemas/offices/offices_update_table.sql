use cybozu_seatmap;

SET
    foreign_key_checks = 0;

CREATE TABLE `update_offices`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) NOT NULL,
    `visible`    tinyint(1)        DEFAULT '1',
    `blocks`     longtext,
    `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `test_column` varchar(250)  NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 27
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

INSERT INTO `update_offices` (id, name, visible, blocks, created_at, updated_at, test_column)
SELECT id, name, visible, blocks, created_at, updated_at, NULL as test_column
FROM `offices`;

DROP TABLE `offices`;

RENAME
    TABLE `update_offices` TO `offices`;

SET
    foreign_key_checks = 1;