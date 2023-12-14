CREATE TABLE IF NOT EXISTS seats
(
    `id`       INT PRIMARY KEY AUTO_INCREMENT,
    `label`      VARCHAR(20) NOT NULL,
    `position`   INT NOT NULL,
    `available` BOOL DEFAULT true,
    `office_id` INT NOT NULL,
    `user_id`  INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES users(`id`)
                                                     ON UPDATE NO ACTION
                                                     ON DELETE NO ACTION,
    FOREIGN KEY (`office_id`) REFERENCES offices(`id`)
                                                     ON UPDATE NO ACTION
                                                     ON DELETE CASCADE
);