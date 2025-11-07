-- Cleaned SQL (MariaDB 11.x / MySQL 8.x compatible)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /!40101 SET NAMES utf8mb4 */;

-- =========================================================
-- Database: `exammaker_db`
-- Collation normalized to utf8mb4_unicode_ci
-- =========================================================

-- ---------------------------------------------------------
-- users (created first so FKs can reference it)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`            varchar(36)  NOT NULL,
  `username`      varchar(50)  NOT NULL,
  `email`         varchar(100) NOT NULL,
  `password_hash` text         NOT NULL,
  `name`          varchar(100) NOT NULL,
  `activated`     tinyint(1)   NOT NULL DEFAULT 0,
  `profile_picture` longtext DEFAULT NULL,
  `google_sub`    varchar(64)  DEFAULT NULL,
  `google_picture_url` text    DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_username` (`username`),
  UNIQUE KEY `uniq_users_email`    (`email`),
  UNIQUE KEY `uniq_users_google_sub` (`google_sub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- exams (references users.id)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams` (
  `id`                  bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`             varchar(36) NOT NULL,
  `title`               varchar(255) NOT NULL,
  `description`         text DEFAULT NULL,
  `exam_type`           varchar(255) NOT NULL,
  `number_of_questions` int(11) NOT NULL,
  `sets_of_exam`        int(11) NOT NULL,
  `learning_material`   varchar(255) DEFAULT NULL,
  `status`              varchar(255) NOT NULL DEFAULT 'generated',
  `created_at`          timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_exams_user_id` (`user_id`),
  CONSTRAINT `fk_exams_user`
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- exam_bodies (one body per exam; keep UNIQUE(exam_id), remove dup indexes)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `exam_bodies`;
CREATE TABLE `exam_bodies` (
  `id`           bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id`      bigint(20) UNSIGNED NOT NULL,
  `storage_mode` enum('db','file') NOT NULL DEFAULT 'db',
  `body_html`    mediumtext DEFAULT NULL,
  `created_at`   timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file_path`    varchar(1024) DEFAULT NULL,
  `body_format`  varchar(16) DEFAULT NULL,
  `body_bytes`   int(10) UNSIGNED DEFAULT NULL,
  `sha256`       char(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_exam_bodies_exam` (`exam_id`),
  CONSTRAINT `fk_exam_bodies_exam`
    FOREIGN KEY (`exam_id`) REFERENCES `exams`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- cms_classes
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `cms_classes`;
CREATE TABLE `cms_classes` (
  `id`           int(11) NOT NULL AUTO_INCREMENT,
  `user_id`      varchar(64) NOT NULL,
  `name`         varchar(255) DEFAULT NULL,
  `section`      varchar(100) DEFAULT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `exam_title`   varchar(255) DEFAULT NULL,
  `header_text`  varchar(500) DEFAULT NULL,
  `color`        varchar(16) DEFAULT NULL,
  `logo_path`    varchar(500) DEFAULT NULL,
  `created_at`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cms_classes_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- cms_records (collation normalized; kept set_start default 1)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `cms_records`;
CREATE TABLE `cms_records` (
  `id`            int(11) NOT NULL AUTO_INCREMENT,
  `user_id`       varchar(64) NOT NULL,
  `header_text`   varchar(255) NOT NULL,
  `teacher_name`  varchar(255) NOT NULL,
  `exam_title`    varchar(255) NOT NULL,
  `default_date`  date DEFAULT NULL,
  `default_page`  varchar(20) DEFAULT NULL,
  `set_start`     int(11) DEFAULT 1,
  `logo_path`     varchar(255) DEFAULT NULL,
  `created_at`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cms_records_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- cms_students (references cms_classes.id)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `cms_students`;
CREATE TABLE `cms_students` (
  `id`           int(11) NOT NULL AUTO_INCREMENT,
  `class_id`     int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `section`      varchar(100) DEFAULT NULL,
  `grade`        varchar(50) DEFAULT NULL,
  `score`        varchar(50) DEFAULT NULL,
  `date`         varchar(32) DEFAULT NULL,
  `page_no`      varchar(16) DEFAULT NULL,
  `set_no`       varchar(16) DEFAULT NULL,
  `exam_title`   varchar(255) DEFAULT NULL,
  `created_at`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cms_students_class_id` (`class_id`),
  CONSTRAINT `fk_cms_students_class`
    FOREIGN KEY (`class_id`) REFERENCES `cms_classes`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- otp_table (add surrogate PK for cleanliness)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `otp_table`;
CREATE TABLE `otp_table` (
  `id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      varchar(100) NOT NULL,
  `otp`        varchar(10)  NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_otp_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
