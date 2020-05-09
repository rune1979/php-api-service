# Create Testuser
CREATE USER 'db_user_to_change'@'localhost' IDENTIFIED BY 'pass_to_change';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON *.* TO 'db_user_to_change'@'localhost';
# Create DB
CREATE DATABASE IF NOT EXISTS `db_name_to_change` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_name_to_change`;

-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Vært: localhost:3306
-- Genereringstid: 25. 04 2020 kl. 10:42:42
-- Serverversion: 5.7.26-0ubuntu0.18.04.1
-- PHP-version: 7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_g2_chillerhot`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `access_relation`
--

CREATE TABLE `access_relation` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `zone_id` int(10) NOT NULL,
  `time_from` time(6) DEFAULT NULL,
  `time_to` time(6) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `alerts`
--

CREATE TABLE `alerts` (
  `id` int(10) NOT NULL,
  `alert_type_id` int(10) NOT NULL,
  `description` varchar(150) NOT NULL,
  `who` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `facility_id` int(10) DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `alert_type`
--

CREATE TABLE `alert_type` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `color` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `emp`
--

CREATE TABLE `emp` (
  `id` int(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(35) DEFAULT NULL,
  `cpr` varchar(255) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `rfid` varchar(255) DEFAULT NULL,
  `passd` varchar(250) DEFAULT NULL,
  `user` varchar(20) DEFAULT NULL,
  `passwd` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `facility`
--

CREATE TABLE `facility` (
  `id` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `country` text,
  `time_zone` varchar(50) DEFAULT NULL,
  `geo` varchar(50) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `hash` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `iot`
--

CREATE TABLE `iot` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `iot_type_id` int(10) NOT NULL,
  `img_url` varchar(100) DEFAULT NULL,
  `cur_val` varchar(50) DEFAULT NULL,
  `acceptable_values` varchar(25) DEFAULT NULL,
  `set_val` varchar(50) DEFAULT NULL,
  `set_val_once` varchar(50) DEFAULT NULL,
  `set_val_forced` varchar(50) DEFAULT NULL,
  `zone_id` int(10) NOT NULL,
  `facility_id` int(10) NOT NULL,
  `local_name` varchar(100) DEFAULT NULL,
  `alert_type` int(10) DEFAULT NULL,
  `max_alert` varchar(10) DEFAULT NULL,
  `min_alert` varchar(10) DEFAULT NULL,
  `equal_alert` varchar(10) DEFAULT NULL,
  `not_equal_alert` varchar(10) DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `iot_relation`
--

CREATE TABLE `iot_relation` (
  `id` int(11) NOT NULL,
  `if_this` int(11) NOT NULL,
  `operator` varchar(10) NOT NULL,
  `this_val` varchar(10) NOT NULL,
  `this_iot` int(6) NOT NULL,
  `set_val` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `iot_type`
--

CREATE TABLE `iot_type` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `locks`
--

CREATE TABLE `locks` (
  `id` int(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `zone` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `log`
--

CREATE TABLE `log` (
  `id` int(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` varchar(25) DEFAULT NULL,
  `lock_id` int(11) DEFAULT NULL,
  `alert` varchar(100) DEFAULT NULL,
  `temp` varchar(20) DEFAULT NULL,
  `moist` varchar(20) DEFAULT NULL,
  `action` varchar(250) DEFAULT NULL,
  `facility_id` int(10) DEFAULT NULL,
  `zone_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `navn` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `roles` (id, navn) VALUES ('1', 'Admin');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `scheduler`
--

CREATE TABLE `scheduler` (
  `id` int(10) NOT NULL,
  `time_temp_id` int(10) DEFAULT NULL,
  `iot_id` int(6) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `start_time` time NOT NULL,
  `to_time` time NOT NULL,
  `daysofweek` int(8) NOT NULL,
  `set_val` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `time_control`
--

CREATE TABLE `time_control` (
  `id` int(10) NOT NULL,
  `time_temp_id` int(10) NOT NULL,
  `iot_type_id` int(6) DEFAULT NULL,
  `iot_id` int(6) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `from_day` int(6) NOT NULL,
  `days` int(10) NOT NULL,
  `daysofweek` int(8) NOT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  `set_val` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `time_templates`
--

CREATE TABLE `time_templates` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `fac_id` int(11) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `zone_id` int(10) NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `types`
--

CREATE TABLE `types` (
  `id` int(6) NOT NULL,
  `type_cat` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `zone`
--

CREATE TABLE `zone` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `temp` varchar(8) DEFAULT NULL,
  `moist` varchar(8) DEFAULT NULL,
  `users` varchar(250) DEFAULT NULL,
  `facility_id` int(10) NOT NULL,
  `zone_type_id` int(10) DEFAULT NULL,
  `zone_content_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `zone_content`
--

CREATE TABLE `zone_content` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `latin` varchar(65) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `zone_type_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `zone_type`
--

CREATE TABLE `zone_type` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `change_later` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `access_relation`
--
ALTER TABLE `access_relation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks for tabel `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `alert_type`
--
ALTER TABLE `alert_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `emp`
--
ALTER TABLE `emp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- Indeks for tabel `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `id` (`id`);

--
-- Indeks for tabel `iot`
--
ALTER TABLE `iot`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `iot_relation`
--
ALTER TABLE `iot_relation`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `iot_type`
--
ALTER TABLE `iot_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `locks`
--
ALTER TABLE `locks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lock_name` (`name`),
  ADD KEY `zone` (`zone`);

--
-- Indeks for tabel `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `timestamp` (`time`);

--
-- Indeks for tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `navn` (`navn`),
  ADD KEY `id` (`id`);

--
-- Indeks for tabel `scheduler`
--
ALTER TABLE `scheduler`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `time_control`
--
ALTER TABLE `time_control`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `time_templates`
--
ALTER TABLE `time_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks for tabel `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`(30));

--
-- Indeks for tabel `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zone_name` (`name`),
  ADD KEY `userrelation` (`users`);

--
-- Indeks for tabel `zone_content`
--
ALTER TABLE `zone_content`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `zone_type`
--
ALTER TABLE `zone_type`
  ADD PRIMARY KEY (`id`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `access_relation`
--
ALTER TABLE `access_relation`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `alert_type`
--
ALTER TABLE `alert_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `emp`
--
ALTER TABLE `emp`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `facility`
--
ALTER TABLE `facility`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `iot`
--
ALTER TABLE `iot`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `iot_relation`
--
ALTER TABLE `iot_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `iot_type`
--
ALTER TABLE `iot_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `locks`
--
ALTER TABLE `locks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `log`
--
ALTER TABLE `log`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `scheduler`
--
ALTER TABLE `scheduler`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `time_control`
--
ALTER TABLE `time_control`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `time_templates`
--
ALTER TABLE `time_templates`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `types`
--
ALTER TABLE `types`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `zone`
--
ALTER TABLE `zone`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `zone_content`
--
ALTER TABLE `zone_content`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `zone_type`
--
ALTER TABLE `zone_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Begrænsninger for tabel `access_relation`
--
ALTER TABLE `access_relation`
  ADD CONSTRAINT `access_relation_ibfk_3` FOREIGN KEY (`zone_id`) REFERENCES `zone` (`id`);

--
-- Begrænsninger for tabel `emp`
--
ALTER TABLE `emp`
  ADD CONSTRAINT `emp_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;

--
-- Begrænsninger for tabel `locks`
--
ALTER TABLE `locks`
  ADD CONSTRAINT `locks_ibfk_1` FOREIGN KEY (`zone`) REFERENCES `zone` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
