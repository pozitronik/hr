-- MySQL dump 10.13  Distrib 8.0.24, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: hr
-- ------------------------------------------------------
-- Server version	8.0.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `import_beeline`
--

DROP TABLE IF EXISTS `import_beeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_tn` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `business_block` varchar(255) DEFAULT NULL,
  `functional_block` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `ceo_level` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `position_name` varchar(255) DEFAULT NULL,
  `administrative_boss_name` varchar(255) DEFAULT NULL,
  `administrative_boss_position_name` varchar(255) DEFAULT NULL,
  `functional_boss_name` varchar(255) DEFAULT NULL,
  `functional_boss_position_name` varchar(255) DEFAULT NULL,
  `affiliation` varchar(255) DEFAULT NULL,
  `position_profile_number` varchar(255) DEFAULT NULL,
  `is_boss` varchar(255) DEFAULT NULL,
  `company_code` varchar(255) DEFAULT NULL,
  `cbo` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `commentary` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=1538 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_boss`
--

DROP TABLE IF EXISTS `import_beeline_boss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_boss` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `level` int DEFAULT NULL,
  `hr_user_id` int DEFAULT NULL,
  `domain` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_branch`
--

DROP TABLE IF EXISTS `import_beeline_branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_branch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_business_block`
--

DROP TABLE IF EXISTS `import_beeline_business_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_business_block` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_decomposed`
--

DROP TABLE IF EXISTS `import_beeline_decomposed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_decomposed` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `business_block_id` int DEFAULT NULL,
  `functional_block_id` int DEFAULT NULL,
  `direction_id` int DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `group_id` int DEFAULT NULL,
  `domain` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1538 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_department`
--

DROP TABLE IF EXISTS `import_beeline_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_department` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_direction`
--

DROP TABLE IF EXISTS `import_beeline_direction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_direction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_functional_block`
--

DROP TABLE IF EXISTS `import_beeline_functional_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_functional_block` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_group`
--

DROP TABLE IF EXISTS `import_beeline_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_service`
--

DROP TABLE IF EXISTS `import_beeline_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_beeline_users`
--

DROP TABLE IF EXISTS `import_beeline_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_beeline_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_tn` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `level` int DEFAULT NULL,
  `domain` int DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `affiliation` varchar(255) DEFAULT NULL,
  `position_profile_number` varchar(255) DEFAULT NULL,
  `is_boss` tinyint(1) DEFAULT '0',
  `company_code` varchar(255) DEFAULT NULL,
  `cbo` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hr_user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_competency_attributes`
--

DROP TABLE IF EXISTS `import_competency_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_competency_attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Название атрибута',
  `hr_attribute_id` int DEFAULT NULL COMMENT 'id в системе',
  `domain` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_competency_fields`
--

DROP TABLE IF EXISTS `import_competency_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_competency_fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'Ключ к атрибуту',
  `name` varchar(255) NOT NULL,
  `domain` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_competency_rel_users_fields`
--

DROP TABLE IF EXISTS `import_competency_rel_users_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_competency_rel_users_fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'Ключ к пользователю',
  `field_id` int NOT NULL COMMENT 'Ключ к полю атрибута',
  `value` text COMMENT 'Значение поля в сыром виде',
  `domain` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`),
  KEY `field_id` (`field_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_competency_users`
--

DROP TABLE IF EXISTS `import_competency_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_competency_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Имя сотрудника',
  `hr_user_id` int DEFAULT NULL COMMENT 'id в системе',
  `domain` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos`
--

DROP TABLE IF EXISTS `import_fos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num` varchar(255) DEFAULT NULL COMMENT '№ п/п',
  `sd_id` varchar(255) DEFAULT NULL COMMENT 'ШД ID',
  `position_name` varchar(255) DEFAULT NULL COMMENT 'Должность',
  `user_tn` varchar(255) DEFAULT NULL COMMENT 'ТН',
  `user_name` varchar(255) DEFAULT NULL COMMENT 'Ф.И.О. сотрудника',
  `birthday` varchar(255) DEFAULT NULL COMMENT 'Дата рождения',
  `functional_block` varchar(255) DEFAULT NULL COMMENT 'Функциональный блок',
  `division_level_1` varchar(255) DEFAULT NULL COMMENT 'Подразделение 1 уровня',
  `division_level_2` varchar(255) DEFAULT NULL COMMENT 'Подразделение 2 уровня',
  `division_level_3` varchar(255) DEFAULT NULL COMMENT 'Подразделение 3 уровня',
  `division_level_4` varchar(255) DEFAULT NULL COMMENT 'Подразделение 4 уровня',
  `division_level_5` varchar(255) DEFAULT NULL COMMENT 'Подразделение 5 уровня',
  `remote_flag` varchar(255) DEFAULT NULL COMMENT 'Признак УРМ',
  `town` varchar(255) DEFAULT NULL COMMENT 'Населенный пункт',
  `functional_block_tribe` varchar(255) DEFAULT NULL COMMENT 'Функциональный блок трайба',
  `tribe_id` varchar(255) DEFAULT NULL COMMENT 'Трайб ID',
  `tribe_code` varchar(255) DEFAULT NULL COMMENT 'Код трайба',
  `tribe_name` varchar(255) DEFAULT NULL COMMENT 'Трайб',
  `tribe_leader_tn` varchar(255) DEFAULT NULL COMMENT 'Лидер трайба ТН',
  `tribe_leader_name` varchar(255) DEFAULT NULL COMMENT 'Лидер трайба',
  `tribe_leader_it_tn` varchar(255) DEFAULT NULL COMMENT 'IT-лидер трайба ТН',
  `tribe_leader_it_name` varchar(255) DEFAULT NULL COMMENT 'IT-лидер трайба',
  `cluster_product_id` varchar(255) DEFAULT NULL COMMENT 'Кластер/Продукт ID',
  `cluster_product_code` varchar(255) DEFAULT NULL COMMENT 'Код кластера/продукта',
  `cluster_product_name` varchar(255) DEFAULT NULL COMMENT 'Кластер/Продукт',
  `cluster_product_leader_tn` varchar(255) DEFAULT NULL COMMENT 'Лидер кластера/продукта ТН',
  `cluster_product_leader_name` varchar(255) DEFAULT NULL COMMENT 'Лидер кластера/продукта',
  `cluster_product_leader_it_tn` varchar(255) DEFAULT NULL COMMENT 'IT-лидер кластера/продукта ТН',
  `cluster_product_leader_it_name` varchar(255) DEFAULT NULL COMMENT 'IT-лидер кластера/продукта',
  `command_id` varchar(255) DEFAULT NULL COMMENT 'Команда ID',
  `command_code` varchar(255) DEFAULT NULL COMMENT 'Код команды',
  `command_name` varchar(255) DEFAULT NULL COMMENT 'Команда',
  `command_type` varchar(255) DEFAULT NULL COMMENT 'Тип команды',
  `owner_tn` varchar(255) DEFAULT NULL COMMENT 'ТН владельца продукта',
  `owner_name` varchar(255) DEFAULT NULL COMMENT 'Владелец продукта',
  `command_position_id` varchar(255) DEFAULT NULL COMMENT 'Позиция в команде ID',
  `command_position_code` varchar(255) DEFAULT NULL COMMENT 'Код позиции в команде',
  `command_position_name` varchar(255) DEFAULT NULL COMMENT 'Позиция в команде',
  `expert_area` varchar(255) DEFAULT NULL COMMENT 'Область экспертизы',
  `combined_role` varchar(255) DEFAULT NULL COMMENT 'Совмещаемая роль',
  `chapter_id` varchar(255) DEFAULT NULL COMMENT 'Чаптер ID',
  `chapter_code` varchar(255) DEFAULT NULL COMMENT 'Код чаптера',
  `chapter_name` varchar(255) DEFAULT NULL COMMENT 'Чаптер',
  `chapter_leader_tn` varchar(255) DEFAULT NULL COMMENT 'Лидер чаптера ТН',
  `chapter_leader_name` varchar(255) DEFAULT NULL COMMENT 'Лидер чаптера',
  `chapter_couch_tn` varchar(255) DEFAULT NULL COMMENT 'Agile-коуч ТН',
  `chapter_couch_name` varchar(255) DEFAULT NULL COMMENT 'Agile-коуч',
  `email_sigma` varchar(255) DEFAULT NULL COMMENT 'Адрес электронной почты (sigma)',
  `email_alpha` varchar(255) DEFAULT NULL COMMENT 'Адрес электронной почты (внутренний',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка очереди импорта',
  PRIMARY KEY (`id`),
  KEY `birthday` (`birthday`),
  KEY `chapter_couch_id` (`chapter_couch_tn`),
  KEY `chapter_id` (`chapter_id`),
  KEY `chapter_leader_id` (`chapter_leader_tn`),
  KEY `cluster_product_id` (`cluster_product_id`),
  KEY `cluster_product_leader_id` (`cluster_product_leader_tn`),
  KEY `cluster_product_leader_it_name` (`cluster_product_leader_it_name`),
  KEY `cluster_product_leader_it_tn` (`cluster_product_leader_it_tn`),
  KEY `combined_role` (`combined_role`),
  KEY `command_id` (`command_id`),
  KEY `command_position_id` (`command_position_id`),
  KEY `domain` (`domain`),
  KEY `expert_area` (`expert_area`),
  KEY `owner_tn` (`owner_tn`),
  KEY `sd_id` (`sd_id`),
  KEY `tribe_id` (`tribe_id`),
  KEY `tribe_leader_id` (`tribe_leader_tn`),
  KEY `tribe_leader_it_id` (`tribe_leader_it_tn`),
  KEY `user_id` (`user_tn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1509;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_chapter`
--

DROP TABLE IF EXISTS `import_fos_chapter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_chapter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chapter_id` int NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `leader_id` int DEFAULT NULL COMMENT 'key to chapter leader id',
  `couch_id` int DEFAULT NULL COMMENT 'key to couch id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapter_id` (`chapter_id`),
  KEY `couch_id` (`couch_id`),
  KEY `domain` (`domain`),
  KEY `leader_id` (`leader_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=390;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_chapter_couch`
--

DROP TABLE IF EXISTS `import_fos_chapter_couch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_chapter_couch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_chapter_leader`
--

DROP TABLE IF EXISTS `import_fos_chapter_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_chapter_leader` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=455;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_cluster_product`
--

DROP TABLE IF EXISTS `import_fos_cluster_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_cluster_product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cluster_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `leader_id` int DEFAULT NULL COMMENT 'key to cluster product leader id',
  `leader_it_id` int DEFAULT NULL COMMENT 'key to cluster product leader it id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cluster_id` (`cluster_id`),
  KEY `domain` (`domain`),
  KEY `leader_id` (`leader_id`),
  KEY `leader_it_id` (`leader_it_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1024;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_cluster_product_leader`
--

DROP TABLE IF EXISTS `import_fos_cluster_product_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_cluster_product_leader` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1170;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_cluster_product_leader_it`
--

DROP TABLE IF EXISTS `import_fos_cluster_product_leader_it`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_cluster_product_leader_it` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_command`
--

DROP TABLE IF EXISTS `import_fos_command`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_command` (
  `id` int NOT NULL AUTO_INCREMENT,
  `command_id` int NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `cluster_id` int DEFAULT NULL COMMENT 'key to cluster product id',
  `owner_id` int DEFAULT NULL COMMENT 'key to product owner id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `command_id` (`command_id`),
  KEY `cluster_id` (`cluster_id`),
  KEY `domain` (`domain`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=109;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_command_position`
--

DROP TABLE IF EXISTS `import_fos_command_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_command_position` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `position_id` (`position_id`),
  KEY `domain` (`domain`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=98;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_decomposed`
--

DROP TABLE IF EXISTS `import_fos_decomposed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_decomposed` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `functional_block_id` int DEFAULT NULL,
  `division_level_1_id` int DEFAULT NULL,
  `division_level_2_id` int DEFAULT NULL,
  `division_level_3_id` int DEFAULT NULL,
  `division_level_4_id` int DEFAULT NULL,
  `division_level_5_id` int DEFAULT NULL,
  `functional_block_tribe_id` int DEFAULT NULL,
  `tribe_id` int DEFAULT NULL,
  `cluster_product_id` int DEFAULT NULL,
  `command_id` int DEFAULT NULL,
  `command_position_id` int DEFAULT NULL,
  `chapter_id` int DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка очереди импорта',
  PRIMARY KEY (`id`),
  KEY `chapter_id` (`chapter_id`),
  KEY `cluster_product_id` (`cluster_product_id`),
  KEY `command_id` (`command_id`),
  KEY `command_position_id` (`command_position_id`),
  KEY `division_level_1` (`division_level_1_id`),
  KEY `division_level_2` (`division_level_2_id`),
  KEY `division_level_3` (`division_level_3_id`),
  KEY `division_level_4` (`division_level_4_id`),
  KEY `division_level_5` (`division_level_5_id`),
  KEY `domain` (`domain`),
  KEY `functional_block` (`functional_block_id`),
  KEY `functional_block_tribe` (`functional_block_tribe_id`),
  KEY `position_id` (`position_id`),
  KEY `tribe_id` (`tribe_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=97;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_division_level1`
--

DROP TABLE IF EXISTS `import_fos_division_level1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_division_level1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_division_level2`
--

DROP TABLE IF EXISTS `import_fos_division_level2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_division_level2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=655;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_division_level3`
--

DROP TABLE IF EXISTS `import_fos_division_level3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_division_level3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_division_level4`
--

DROP TABLE IF EXISTS `import_fos_division_level4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_division_level4` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_division_level5`
--

DROP TABLE IF EXISTS `import_fos_division_level5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_division_level5` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_functional_block`
--

DROP TABLE IF EXISTS `import_fos_functional_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_functional_block` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_functional_block_tribe`
--

DROP TABLE IF EXISTS `import_fos_functional_block_tribe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_functional_block_tribe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_positions`
--

DROP TABLE IF EXISTS `import_fos_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=528;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_product_owner`
--

DROP TABLE IF EXISTS `import_fos_product_owner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_product_owner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=138;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_town`
--

DROP TABLE IF EXISTS `import_fos_town`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_town` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1024;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_tribe`
--

DROP TABLE IF EXISTS `import_fos_tribe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_tribe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tribe_id` int NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `leader_id` int DEFAULT NULL COMMENT 'key to tribe leader id',
  `leader_it_id` int DEFAULT NULL COMMENT 'key to tribe leader it id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_group_id` int DEFAULT NULL COMMENT 'id группы в рабочей базе (соответствие, установленное при импорте)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tribe_id` (`tribe_id`),
  KEY `domain` (`domain`),
  KEY `leader_id` (`leader_id`),
  KEY `leader_it_id` (`leader_it_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_tribe_leader`
--

DROP TABLE IF EXISTS `import_fos_tribe_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_tribe_leader` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_tribe_leader_it`
--

DROP TABLE IF EXISTS `import_fos_tribe_leader_it`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_tribe_leader_it` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'key to user id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_fos_users`
--

DROP TABLE IF EXISTS `import_fos_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_fos_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_tn` int NOT NULL,
  `sd_id` varchar(255) DEFAULT NULL COMMENT 'ШД ID',
  `name` varchar(255) DEFAULT NULL,
  `remote` tinyint(1) NOT NULL DEFAULT '0',
  `email_sigma` varchar(255) DEFAULT NULL,
  `email_alpha` varchar(255) DEFAULT NULL,
  `position_id` int DEFAULT NULL COMMENT 'key to position id',
  `town_id` int DEFAULT NULL COMMENT 'key to town id',
  `domain` int DEFAULT NULL COMMENT 'Служебная метка домена импорта',
  `hr_user_id` int DEFAULT NULL COMMENT 'id пользователя в рабочей базе (соответствие, установленное при импорте)',
  `birthday` varchar(255) DEFAULT NULL,
  `expert_area` varchar(255) DEFAULT NULL,
  `combined_role` varchar(255) DEFAULT NULL,
  `position_type` int DEFAULT NULL COMMENT 'Тип должности (определяем по ФБ)',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`),
  KEY `name` (`name`),
  KEY `position_type` (`position_type`),
  KEY `sd_id` (`sd_id`),
  KEY `user_tn` (`user_tn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=203;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_targets`
--

DROP TABLE IF EXISTS `import_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_targets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clusterName` varchar(255) DEFAULT NULL,
  `commandName` varchar(255) DEFAULT NULL,
  `commandCode` varchar(255) DEFAULT NULL,
  `subInit` varchar(255) DEFAULT NULL,
  `milestone` varchar(255) DEFAULT NULL,
  `target` text,
  `targetResult` varchar(255) DEFAULT NULL,
  `resultValue` varchar(255) DEFAULT NULL,
  `period` varchar(255) DEFAULT NULL,
  `isYear` varchar(255) DEFAULT NULL,
  `isLK` varchar(255) DEFAULT NULL,
  `isLT` varchar(255) DEFAULT NULL,
  `isCurator` varchar(255) DEFAULT NULL,
  `comment` text,
  `domain` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1127;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_targets_clusters`
--

DROP TABLE IF EXISTS `import_targets_clusters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_targets_clusters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cluster_name` varchar(255) NOT NULL,
  `domain` int NOT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=910;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_targets_commands`
--

DROP TABLE IF EXISTS `import_targets_commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_targets_commands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `command_name` varchar(255) NOT NULL,
  `command_id` varchar(255) NOT NULL,
  `domain` int NOT NULL,
  `hr_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=136;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_targets_milestones`
--

DROP TABLE IF EXISTS `import_targets_milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_targets_milestones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `initiative_id` int DEFAULT NULL,
  `milestone` varchar(255) NOT NULL,
  `domain` int NOT NULL,
  `hr_target_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=168;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_targets_subinitiatives`
--

DROP TABLE IF EXISTS `import_targets_subinitiatives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_targets_subinitiatives` (
  `id` int NOT NULL AUTO_INCREMENT,
  `initiative` varchar(255) NOT NULL,
  `domain` int NOT NULL,
  `hr_target_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=862;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_targets_targets`
--

DROP TABLE IF EXISTS `import_targets_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_targets_targets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `milestone_id` int DEFAULT NULL,
  `cluster_id` int DEFAULT NULL,
  `command_id` int DEFAULT NULL,
  `target` text NOT NULL,
  `domain` int NOT NULL,
  `result_id` int DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `period` varchar(255) DEFAULT NULL,
  `isYear` tinyint(1) NOT NULL DEFAULT '0',
  `isLK` tinyint(1) NOT NULL DEFAULT '0',
  `isLT` tinyint(1) NOT NULL DEFAULT '0',
  `isCurator` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text,
  `hr_target_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=282;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=128;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_attributes_types`
--

DROP TABLE IF EXISTS `ref_attributes_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_attributes_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_cluster_types`
--

DROP TABLE IF EXISTS `ref_cluster_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_cluster_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `level` int NOT NULL DEFAULT '0' COMMENT 'Уровень иерархии пространства',
  `color` varchar(255) DEFAULT NULL,
  `textcolor` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_group_relation_types`
--

DROP TABLE IF EXISTS `ref_group_relation_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_group_relation_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `color` varchar(255) DEFAULT NULL COMMENT 'Цветокод',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_group_types`
--

DROP TABLE IF EXISTS `ref_group_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_group_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(255) DEFAULT NULL COMMENT 'Цветокод',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1489;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_locations`
--

DROP TABLE IF EXISTS `ref_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_salary_grades`
--

DROP TABLE IF EXISTS `ref_salary_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_salary_grades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_salary_premium_group`
--

DROP TABLE IF EXISTS `ref_salary_premium_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_salary_premium_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `color` varchar(256) DEFAULT NULL COMMENT 'Цвет',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_targets_results`
--

DROP TABLE IF EXISTS `ref_targets_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_targets_results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(255) DEFAULT NULL COMMENT 'Цветокод',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет шрифта',
  PRIMARY KEY (`id`),
  KEY `color` (`color`),
  KEY `textcolor` (`textcolor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_targets_types`
--

DROP TABLE IF EXISTS `ref_targets_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_targets_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(255) DEFAULT NULL COMMENT 'Цветокод',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет шрифта',
  `parent` int DEFAULT NULL COMMENT 'id родительского типа цели, null если высший',
  PRIMARY KEY (`id`),
  KEY `color` (`color`),
  KEY `parent` (`parent`),
  KEY `textcolor` (`textcolor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_user_position_branches`
--

DROP TABLE IF EXISTS `ref_user_position_branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_user_position_branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `color` (`color`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_user_position_types`
--

DROP TABLE IF EXISTS `ref_user_position_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_user_position_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `color` (`color`),
  KEY `deleted` (`deleted`),
  KEY `textcolor` (`textcolor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_user_positions`
--

DROP TABLE IF EXISTS `ref_user_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_user_positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(255) DEFAULT NULL,
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `color` (`color`)
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=564;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_user_roles`
--

DROP TABLE IF EXISTS `ref_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_user_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `boss_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Флаг лидера группы',
  `color` varchar(255) DEFAULT NULL COMMENT 'Цветокод',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  `importance_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Дополнительный флаг важности роли',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `boss_flag` (`boss_flag`),
  KEY `color` (`color`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=2730;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_vacancy_recruiters`
--

DROP TABLE IF EXISTS `ref_vacancy_recruiters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_vacancy_recruiters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_vacancy_statuses`
--

DROP TABLE IF EXISTS `ref_vacancy_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_vacancy_statuses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `textcolor` varchar(255) DEFAULT NULL COMMENT 'Цвет текста',
  PRIMARY KEY (`id`),
  KEY `color` (`color`),
  KEY `textcolor` (`textcolor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_clusters_clusters`
--

DROP TABLE IF EXISTS `rel_clusters_clusters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_clusters_clusters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `child_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_child_id` (`parent_id`,`child_id`),
  KEY `child_id` (`child_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_clusters_seats`
--

DROP TABLE IF EXISTS `rel_clusters_seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_clusters_seats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cluster_id` int NOT NULL,
  `seat_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cluster_id_seat_id` (`cluster_id`,`seat_id`),
  KEY `cluster_id` (`cluster_id`),
  KEY `seat_id` (`seat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_grades_positions_rules`
--

DROP TABLE IF EXISTS `rel_grades_positions_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_grades_positions_rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grade_id` int NOT NULL,
  `position_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grade_id` (`grade_id`),
  KEY `grade_id_position_id` (`grade_id`,`position_id`),
  KEY `position_id` (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_groups_groups`
--

DROP TABLE IF EXISTS `rel_groups_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_groups_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL COMMENT 'Вышестоящая группа',
  `child_id` int NOT NULL COMMENT 'Нижестоящая группа',
  `relation` int DEFAULT NULL COMMENT 'Тип связи',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_child_id_relation` (`parent_id`,`child_id`,`relation`),
  KEY `child_id` (`child_id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_id_child_id` (`parent_id`,`child_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=42;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_privileges_dynamic_rights`
--

DROP TABLE IF EXISTS `rel_privileges_dynamic_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_privileges_dynamic_rights` (
  `id` int NOT NULL AUTO_INCREMENT,
  `privilege` int NOT NULL COMMENT 'id набора привилегий',
  `right` int NOT NULL COMMENT 'ID динамической привилегии',
  PRIMARY KEY (`id`),
  UNIQUE KEY `privilege_right` (`privilege`,`right`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_privileges_rights`
--

DROP TABLE IF EXISTS `rel_privileges_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_privileges_rights` (
  `id` int NOT NULL AUTO_INCREMENT,
  `privilege` int NOT NULL COMMENT 'id набора привилегий',
  `right` varchar(255) NOT NULL COMMENT 'Класс, предоставляющий право',
  PRIMARY KEY (`id`),
  UNIQUE KEY `privilege_right` (`privilege`,`right`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_ref_user_positions_branches`
--

DROP TABLE IF EXISTS `rel_ref_user_positions_branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_ref_user_positions_branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int NOT NULL,
  `position_branch_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `position_id_position_branch_id` (`position_id`,`position_branch_id`),
  KEY `position_branch_id` (`position_branch_id`),
  KEY `position_id` (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_ref_user_positions_types`
--

DROP TABLE IF EXISTS `rel_ref_user_positions_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_ref_user_positions_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int NOT NULL,
  `position_type_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `position_id_position_type_id` (`position_id`,`position_type_id`),
  KEY `position_id` (`position_id`),
  KEY `position_type_id` (`position_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_targets_groups`
--

DROP TABLE IF EXISTS `rel_targets_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_targets_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target_id` int DEFAULT NULL,
  `group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `target_id_group_id` (`target_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=59;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_targets_targets`
--

DROP TABLE IF EXISTS `rel_targets_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_targets_targets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL COMMENT 'Вышестоящая цель',
  `child_id` int NOT NULL COMMENT 'Нижестоящая цель',
  `relation` int DEFAULT NULL COMMENT 'Тип связи',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_child_id_relation` (`parent_id`,`child_id`,`relation`),
  KEY `child_id` (`child_id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_id_child_id` (`parent_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=55;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_targets_users`
--

DROP TABLE IF EXISTS `rel_targets_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_targets_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `target_id_user_id` (`target_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=2730;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_user_position_types`
--

DROP TABLE IF EXISTS `rel_user_position_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_user_position_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'Пользователь',
  `position_type_id` int NOT NULL COMMENT 'Тип должности',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_position_type_id` (`user_id`,`position_type_id`),
  KEY `position_type_id` (`position_type_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=67;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_users_attributes`
--

DROP TABLE IF EXISTS `rel_users_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_users_attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `attribute_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_attribute_id` (`user_id`,`attribute_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1152 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=45;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_users_attributes_types`
--

DROP TABLE IF EXISTS `rel_users_attributes_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_users_attributes_types` (
  `user_attribute_id` int NOT NULL,
  `type` int NOT NULL,
  UNIQUE KEY `user_attribute_id_type` (`user_attribute_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_users_groups`
--

DROP TABLE IF EXISTS `rel_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_users_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'Сотрудник',
  `group_id` int NOT NULL COMMENT 'Рабочая группа',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_group_id` (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_user_role_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6293 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=39;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_users_groups_roles`
--

DROP TABLE IF EXISTS `rel_users_groups_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_users_groups_roles` (
  `user_group_id` int NOT NULL COMMENT 'ID связки пользователь/группа',
  `role` int NOT NULL COMMENT 'Роль',
  UNIQUE KEY `user_group_id_role` (`user_group_id`,`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=63;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_users_privileges`
--

DROP TABLE IF EXISTS `rel_users_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_users_privileges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `privilege_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_privilege_id` (`user_id`,`privilege_id`),
  KEY `privilege_id` (`privilege_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_users_salary`
--

DROP TABLE IF EXISTS `rel_users_salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_users_salary` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `grade_id` int DEFAULT NULL,
  `premium_group_id` int DEFAULT NULL,
  `location_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `grade_id` (`grade_id`),
  KEY `location_id` (`location_id`),
  KEY `premium_group_id` (`premium_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_vacancy_group_roles`
--

DROP TABLE IF EXISTS `rel_vacancy_group_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_vacancy_group_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vacancy_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vacancy_id_role_id` (`vacancy_id`,`role_id`),
  KEY `role_id` (`role_id`),
  KEY `vacancy_id` (`vacancy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary_fork`
--

DROP TABLE IF EXISTS `salary_fork`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salary_fork` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int NOT NULL COMMENT 'Должность',
  `grade_id` int NOT NULL COMMENT 'Грейд',
  `premium_group_id` int DEFAULT NULL COMMENT 'Группа премирования',
  `location_id` int DEFAULT NULL COMMENT 'Локация',
  `min` float DEFAULT NULL COMMENT 'Минимальный оклад',
  `max` float DEFAULT NULL COMMENT 'Максимальный оклад',
  `currency` int DEFAULT NULL COMMENT 'Валюта',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `position_id_grade_id_premium_group_id_location_id` (`position_id`,`grade_id`,`premium_group_id`,`location_id`),
  KEY `grade_id` (`grade_id`),
  KEY `location_id` (`location_id`),
  KEY `position_id` (`position_id`),
  KEY `premium_group_id` (`premium_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes`
--

DROP TABLE IF EXISTS `sys_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Название компетенции',
  `category` int DEFAULT NULL COMMENT 'Категория',
  `daddy` int DEFAULT NULL COMMENT 'Создатель',
  `create_date` datetime DEFAULT NULL COMMENT 'Дата создания',
  `structure` json NOT NULL COMMENT 'Структура',
  `access` int NOT NULL DEFAULT '0' COMMENT 'Доступ',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Флаг удаления',
  PRIMARY KEY (`id`),
  KEY `access` (`access`),
  KEY `category` (`category`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=5461;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_boolean`
--

DROP TABLE IF EXISTS `sys_attributes_boolean`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_boolean` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` tinyint(1) DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=1152 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=64;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_date`
--

DROP TABLE IF EXISTS `sys_attributes_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_date` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` date DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_integer`
--

DROP TABLE IF EXISTS `sys_attributes_integer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_integer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` int DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_percent`
--

DROP TABLE IF EXISTS `sys_attributes_percent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_percent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` int DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_score`
--

DROP TABLE IF EXISTS `sys_attributes_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_score` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID атрибута',
  `property_id` int NOT NULL COMMENT 'ID свойства',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `self_score_value` int DEFAULT NULL COMMENT 'Оценка сотрудника (СО)',
  `self_score_comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий к самооценке',
  `tl_score_value` int DEFAULT NULL COMMENT 'Оценка тимлида (TL)',
  `tl_score_comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий к оценке тимлида',
  `al_score_value` int DEFAULT NULL COMMENT 'Оценка ареалида (AL)',
  `al_score_comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий к оценке ареалида',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `al_score_comment` (`al_score_comment`),
  KEY `al_score_value` (`al_score_value`),
  KEY `attribute_id` (`attribute_id`),
  KEY `property_id` (`property_id`),
  KEY `self_score_comment` (`self_score_comment`),
  KEY `self_score_value` (`self_score_value`),
  KEY `tl_score_comment` (`tl_score_comment`),
  KEY `tl_score_value` (`tl_score_value`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_string`
--

DROP TABLE IF EXISTS `sys_attributes_string`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_string` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` varchar(255) DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=6907 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=56;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_text`
--

DROP TABLE IF EXISTS `sys_attributes_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_text` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` text COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_attributes_time`
--

DROP TABLE IF EXISTS `sys_attributes_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_attributes_time` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL COMMENT 'ID компетенции',
  `property_id` int NOT NULL COMMENT 'ID поля',
  `user_id` int NOT NULL COMMENT 'ID пользователя',
  `value` time DEFAULT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_id_property_id_user_id` (`attribute_id`,`property_id`,`user_id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_clusters`
--

DROP TABLE IF EXISTS `sys_clusters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_clusters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL DEFAULT '0' COMMENT 'Тип кластера',
  `name` varchar(255) DEFAULT NULL COMMENT 'Необязательное имя',
  `daddy` int DEFAULT NULL COMMENT 'id создателя',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_exceptions`
--

DROP TABLE IF EXISTS `sys_exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_exceptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `code` int DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `line` int DEFAULT NULL,
  `message` text,
  `trace` text,
  `get` text COMMENT 'GET',
  `post` text COMMENT 'POST',
  `known` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Known error',
  PRIMARY KEY (`id`),
  KEY `known` (`known`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_groups`
--

DROP TABLE IF EXISTS `sys_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(512) DEFAULT NULL COMMENT 'Название',
  `type` int DEFAULT NULL COMMENT 'Тип группы',
  `comment` text COMMENT 'Описание',
  `daddy` int DEFAULT NULL COMMENT 'id создателя',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `logotype` varchar(255) DEFAULT NULL COMMENT 'Logotype image',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `author` (`daddy`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=178;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_log`
--

DROP TABLE IF EXISTS `sys_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int DEFAULT NULL,
  `model` varchar(64) DEFAULT NULL,
  `model_key` int DEFAULT NULL,
  `old_attributes` json DEFAULT NULL,
  `new_attributes` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`model`),
  KEY `model_key` (`model_key`),
  KEY `model_model_key` (`model`,`model_key`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=247;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_privileges`
--

DROP TABLE IF EXISTS `sys_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_privileges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Название набора прав',
  `daddy` int DEFAULT NULL COMMENT 'id создателя',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `deleted` tinyint(1) DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Привилегия применяется для всех пользователей в системе',
  PRIMARY KEY (`id`),
  KEY `default` (`default`),
  KEY `deleted` (`deleted`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_seats`
--

DROP TABLE IF EXISTS `sys_seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_seats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `daddy` int DEFAULT NULL COMMENT 'id создателя',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=1820;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_targets`
--

DROP TABLE IF EXISTS `sys_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_targets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL COMMENT 'id типа цели',
  `result_type` int DEFAULT NULL COMMENT 'id типа результата',
  `name` varchar(512) NOT NULL,
  `comment` text COMMENT 'Описание цели',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `daddy` int DEFAULT NULL COMMENT 'ID зарегистрировавшего/проверившего пользователя',
  `deleted` tinyint(1) DEFAULT '0' COMMENT 'Флаг удаления',
  PRIMARY KEY (`id`),
  KEY `daddy` (`daddy`),
  KEY `deleted` (`deleted`),
  KEY `result_type` (`result_type`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=403;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_targets_budgets`
--

DROP TABLE IF EXISTS `sys_targets_budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_targets_budgets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target` int NOT NULL COMMENT 'id цели',
  `comment` text COMMENT 'Описание бюджета',
  `value` int DEFAULT NULL COMMENT 'Значение бюджета в цифрах',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `marked_date` datetime NOT NULL COMMENT 'Дата, на которую изменение активно',
  `daddy` int DEFAULT NULL COMMENT 'ID зарегистрировавшего пользователя',
  PRIMARY KEY (`id`),
  KEY `daddy` (`daddy`),
  KEY `target` (`target`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_targets_periods`
--

DROP TABLE IF EXISTS `sys_targets_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_targets_periods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target_id` int NOT NULL,
  `q1` tinyint(1) NOT NULL DEFAULT '0',
  `q2` tinyint(1) NOT NULL DEFAULT '0',
  `q3` tinyint(1) NOT NULL DEFAULT '0',
  `q4` tinyint(1) NOT NULL DEFAULT '0',
  `is_year` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `target_id` (`target_id`),
  KEY `is_year` (`is_year`),
  KEY `q1` (`q1`),
  KEY `q2` (`q2`),
  KEY `q3` (`q3`),
  KEY `q4` (`q4`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=57;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_targets_results`
--

DROP TABLE IF EXISTS `sys_targets_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_targets_results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target` int NOT NULL COMMENT 'id цели',
  `comment` text COMMENT 'Описание результата',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `marked_date` datetime NOT NULL COMMENT 'Дата, на которую приходится результата',
  `daddy` int DEFAULT NULL COMMENT 'ID зарегистрировавшего пользователя',
  `status` int DEFAULT NULL COMMENT 'Тип статуса исполнения',
  PRIMARY KEY (`id`),
  KEY `daddy` (`daddy`),
  KEY `status` (`status`),
  KEY `target` (`target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_user_rights`
--

DROP TABLE IF EXISTS `sys_user_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_user_rights` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Название правила',
  `description` varchar(255) DEFAULT NULL COMMENT 'Описание правила',
  `rules` json NOT NULL COMMENT 'Набор разрешений правила',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_users`
--

DROP TABLE IF EXISTS `sys_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT 'Отображаемое имя пользователя',
  `login` varchar(64) NOT NULL COMMENT 'Логин',
  `password` varchar(255) NOT NULL COMMENT 'Хеш пароля',
  `salt` varchar(255) DEFAULT NULL COMMENT 'Unique random salt hash',
  `email` varchar(255) NOT NULL COMMENT 'email',
  `comment` text COMMENT 'Служебный комментарий пользователя',
  `create_date` datetime NOT NULL COMMENT 'Дата регистрации',
  `profile_image` varchar(255) DEFAULT NULL COMMENT 'Фото профиля',
  `daddy` int DEFAULT NULL COMMENT 'ID зарегистрировавшего/проверившего пользователя',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `position` int DEFAULT NULL COMMENT 'Должность/позиция',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`),
  KEY `daddy` (`daddy`),
  KEY `deleted` (`deleted`),
  KEY `position` (`position`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1153 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=226;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_users_identifiers`
--

DROP TABLE IF EXISTS `sys_users_identifiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_users_identifiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'id пользователя',
  `tn` varchar(255) DEFAULT NULL COMMENT 'Табельный номер',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `tn` (`tn`),
  UNIQUE KEY `user_id_tn` (`user_id`,`tn`)
) ENGINE=InnoDB AUTO_INCREMENT=1152 DEFAULT CHARSET=utf8mb3 AVG_ROW_LENGTH=64;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_users_options`
--

DROP TABLE IF EXISTS `sys_users_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_users_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL COMMENT 'System user id',
  `option` varchar(32) NOT NULL COMMENT 'Option name',
  `value` json DEFAULT NULL COMMENT 'Option value in JSON',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_option` (`user_id`,`option`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_vacancy`
--

DROP TABLE IF EXISTS `sys_vacancy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_vacancy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vacancy_id` int DEFAULT NULL COMMENT 'Внешний ID вакансии',
  `ticket_id` int DEFAULT NULL COMMENT 'ID заявки на подбор',
  `status` int DEFAULT '0' COMMENT 'Статус',
  `group` int NOT NULL COMMENT 'Группа',
  `name` varchar(255) DEFAULT NULL COMMENT 'Опциональное название вакансии',
  `location` int DEFAULT NULL COMMENT 'Локация',
  `recruiter` int DEFAULT NULL COMMENT 'Рекрутер',
  `employer` int DEFAULT NULL COMMENT 'Нанимающий руководитель',
  `position` int NOT NULL COMMENT 'Должность',
  `premium_group` int DEFAULT NULL COMMENT 'Группа премирования',
  `grade` int DEFAULT NULL COMMENT 'Грейд',
  `teamlead` int DEFAULT NULL COMMENT 'teamlead',
  `username` varchar(255) DEFAULT NULL,
  `create_date` datetime NOT NULL COMMENT 'Дата заведения вакансии',
  `close_date` datetime DEFAULT NULL COMMENT 'Дата закрытия вакансии',
  `estimated_close_date` datetime DEFAULT NULL COMMENT 'Дата ожидаемого закрытия вакансии',
  `daddy` int NOT NULL COMMENT 'Автор вакансии',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `close_date` (`close_date`),
  KEY `create_date` (`create_date`),
  KEY `daddy` (`daddy`),
  KEY `deleted` (`deleted`),
  KEY `employer` (`employer`),
  KEY `estimated_close_date` (`estimated_close_date`),
  KEY `grade` (`grade`),
  KEY `group` (`group`),
  KEY `location` (`location`),
  KEY `name` (`name`),
  KEY `position` (`position`),
  KEY `premium_group` (`premium_group`),
  KEY `recruiter` (`recruiter`),
  KEY `status` (`status`),
  KEY `teamlead` (`teamlead`),
  KEY `ticket_id` (`ticket_id`),
  KEY `username` (`username`),
  KEY `vacancy_id` (`vacancy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-02 17:34:03
