CREATE TABLE IF NOT EXISTS `users`(
    `user_id` INT not null UNIQUE AUTO_INCREMENT,
    `username` VARCHAR(255) not null UNIQUE CHECK (LENGTH(`username`) >= 6),
    `email` VARCHAR(255) not null UNIQUE,
    `password` VARCHAR(255) not null check(LENGTH(`password`) >= 6),
    `twoFA` BOOLEAN not null DEFAULT '0',
    `attempts` INT(1) not null DEFAULT '3' check(`attempts` BETWEEN 0 AND 3),
    `last_attempt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`user_id`)
);
