CREATE DATABASE IF NOT EXISTS `problem`;
DROP TABLE IF EXISTS `problem`.`ai_analysis_logs`;
CREATE TABLE `problem`.`ai_analysis_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `image_path` varchar(255) DEFAULT NULL,
    `is_success` boolean DEFAULT NULL,
    -- API仕様に合わせて型を変更
    -- 元仕様 `success` varchar(255) DEFAULT NULL,
    `message` varchar(255) DEFAULT NULL,
    `class` int(11) DEFAULT NULL,
    `confidence` decimal(5, 4) DEFAULT NULL,
    `request_timestamp` int(10) unsigned DEFAULT NULL,
    `response_timestamp` int(10) unsigned DEFAULT NULL,
    `created_at` timestamp default current_timestamp,
    `updated_at` timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- testing
CREATE DATABASE IF NOT EXISTS `problem_test`;
DROP TABLE IF EXISTS `problem_test`.`ai_analysis_logs`;
CREATE TABLE `problem_test`.`ai_analysis_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `image_path` varchar(255) DEFAULT NULL,
    `is_success` boolean DEFAULT NULL,
    -- API仕様に合わせて型を変更
    -- 元仕様 `success` varchar(255) DEFAULT NULL,
    `message` varchar(255) DEFAULT NULL,
    `class` int(11) DEFAULT NULL,
    `confidence` decimal(5, 4) DEFAULT NULL,
    `request_timestamp` int(10) unsigned DEFAULT NULL,
    `response_timestamp` int(10) unsigned DEFAULT NULL,
    `created_at` timestamp default current_timestamp,
    `updated_at` timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
GRANT ALL ON *.* TO 'user' @'%';