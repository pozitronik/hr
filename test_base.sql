-- MySQL dump 10.13  Distrib 5.7.20, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: hr
-- ------------------------------------------------------
-- Server version	5.7.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) DEFAULT NULL COMMENT 'ФИО',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1538299847),('m180930_092340_create_sys_exceptions',1538299849),('m180930_093408_sys_users',1538302120),('m181006_140806_employee',1538836588),('m181006_141340_workgroups',1538836588),('m181006_141856_rel_employees_workroups',1538836680),('m181006_142719_ref_employee_roles',1538836698),('m181006_143104_ref_employee_role_samples',1538836698),('m181006_151846_add_admin_profile',1538839651),('m181006_190403_users_profile_image',1538853141),('m181009_100149_okkam',1539079603),('m181009_115345_sys_group_author',1539087244),('m181009_123059_sys_group_create_date',1539088519),('m181010_101831_user_role_id_not_required',1539166829),('m181011_081655_rel_groups_groups',1539246150),('m181016_071524_new_groups_fields',1539674340),('m181016_071941_ref_group_types',1539690605),('m181017_121419_ref_users_roles',1539780666),('m181017_122521_user_role_column',1539780667),('m181017_134444_ref_user_role_in_groups',1539784236),('m181017_135729_user_groups_roles_id',1539784817),('m181017_141725_rel_user_groups_roles',1539786350);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_group_types`
--

DROP TABLE IF EXISTS `ref_group_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ref_group_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_group_types`
--

LOCK TABLES `ref_group_types` WRITE;
/*!40000 ALTER TABLE `ref_group_types` DISABLE KEYS */;
INSERT INTO `ref_group_types` VALUES (1,'Change',0),(2,'Management&Support',0),(3,'Чаптер',0),(4,'RUN',0),(5,'Дивизион',0);
/*!40000 ALTER TABLE `ref_group_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_user_positions`
--

DROP TABLE IF EXISTS `ref_user_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ref_user_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_user_positions`
--

LOCK TABLES `ref_user_positions` WRITE;
/*!40000 ALTER TABLE `ref_user_positions` DISABLE KEYS */;
INSERT INTO `ref_user_positions` VALUES (1,'Главный инженер ',0),(2,'CJE',0),(3,'Customer Journey expert',0),(4,'Cтарший ИТ-инженер',0),(5,'Engineer dev',0),(6,'Автоматизатор тестирования',0),(7,'Аналитик',0),(8,'Архитектор сервисов',0),(9,'аутсорс',0),(10,'аутстафф - аналитик',0),(11,'Аутстафф - разработчик',0),(12,'Аутстафф - тестировщик',0),(13,'Бизнес-аналитик',0),(14,'Бухгалтер',0),(15,'Веб-аналитик',0),(16,'Ведущий администратор проектов',0),(17,'Ведущий ИТ-инженер',0),(18,'Главный ИТ-инженер',0),(19,'Исполнительный директор',0),(20,'Менеджер',0),(21,'Менеджер по работе с ключевыми партнерами',0),(22,'Разработчик',0),(23,'Разработчик Android',0),(24,'Разработчик IOS',0),(25,'Руководитель направления',0),(26,'Руководитель направления по развитию ИТ-систем',0),(27,'Руководитель проектов',0),(28,'Эксперт',0);
/*!40000 ALTER TABLE `ref_user_positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_user_roles`
--

DROP TABLE IF EXISTS `ref_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ref_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Название',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_user_roles`
--

LOCK TABLES `ref_user_roles` WRITE;
/*!40000 ALTER TABLE `ref_user_roles` DISABLE KEYS */;
INSERT INTO `ref_user_roles` VALUES (1,'Product owner',0),(2,'Leader',0);
/*!40000 ALTER TABLE `ref_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_groups_groups`
--

DROP TABLE IF EXISTS `rel_groups_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_groups_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT 'Вышестоящая группа',
  `child_id` int(11) NOT NULL COMMENT 'Нижестоящая группа',
  `relation` int(11) DEFAULT NULL COMMENT 'Тип связи',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_child_id_relation` (`parent_id`,`child_id`,`relation`),
  KEY `parent_id` (`parent_id`),
  KEY `child_id` (`child_id`),
  KEY `parent_id_child_id` (`parent_id`,`child_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_groups_groups`
--

LOCK TABLES `rel_groups_groups` WRITE;
/*!40000 ALTER TABLE `rel_groups_groups` DISABLE KEYS */;
INSERT INTO `rel_groups_groups` VALUES (1,1,3,NULL),(3,2,1,NULL),(4,3,4,NULL),(5,4,2,NULL),(6,4,5,NULL),(7,5,3,NULL),(8,13,6,NULL),(9,13,7,NULL),(10,13,8,NULL),(12,25,14,NULL),(13,25,15,NULL),(14,25,16,NULL),(15,25,17,NULL),(16,25,18,NULL),(17,25,19,NULL),(18,25,20,NULL),(19,25,21,NULL),(20,25,22,NULL),(21,25,23,NULL),(22,25,24,NULL),(23,26,13,NULL),(41,26,25,NULL);
/*!40000 ALTER TABLE `rel_groups_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_users_groups`
--

DROP TABLE IF EXISTS `rel_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Сотрудник',
  `group_id` int(11) NOT NULL COMMENT 'Рабочая группа',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_group_id` (`user_id`,`group_id`),
  KEY `user_id_user_role_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_users_groups`
--

LOCK TABLES `rel_users_groups` WRITE;
/*!40000 ALTER TABLE `rel_users_groups` DISABLE KEYS */;
INSERT INTO `rel_users_groups` VALUES (1,1,6),(2,2,6),(4,3,6),(5,4,6),(6,5,8),(7,6,8),(8,7,8),(9,8,8),(10,9,7),(11,10,7),(12,11,7),(13,12,7),(14,13,7),(15,14,7),(27,15,7),(16,16,7),(17,17,7),(18,18,7),(19,19,7),(20,20,13);
/*!40000 ALTER TABLE `rel_users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_users_groups_roles`
--

DROP TABLE IF EXISTS `rel_users_groups_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_users_groups_roles` (
  `user_group_id` int(11) NOT NULL COMMENT 'ID связки пользователь/группа',
  `role` int(11) NOT NULL COMMENT 'Роль',
  UNIQUE KEY `user_group_id_role` (`user_group_id`,`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_users_groups_roles`
--

LOCK TABLES `rel_users_groups_roles` WRITE;
/*!40000 ALTER TABLE `rel_users_groups_roles` DISABLE KEYS */;
INSERT INTO `rel_users_groups_roles` VALUES (1,1),(1,2),(6,2),(18,2),(20,2),(27,1);
/*!40000 ALTER TABLE `rel_users_groups_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_exceptions`
--

DROP TABLE IF EXISTS `sys_exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `message` text,
  `trace` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_exceptions`
--

LOCK TABLES `sys_exceptions` WRITE;
/*!40000 ALTER TABLE `sys_exceptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_exceptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_groups`
--

DROP TABLE IF EXISTS `sys_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) DEFAULT NULL COMMENT 'Название',
  `type` int(11) DEFAULT NULL COMMENT 'Тип группы',
  `comment` text COMMENT 'Описание',
  `daddy` int(11) DEFAULT NULL COMMENT 'id создателя',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `author` (`daddy`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_groups`
--

LOCK TABLES `sys_groups` WRITE;
/*!40000 ALTER TABLE `sys_groups` DISABLE KEYS */;
INSERT INTO `sys_groups` VALUES (1,'Пятничная тусня',1,'Расширение сознания легальными методами',1,'2018-10-09 15:35:25',1),(2,'Братство ножа и топора',NULL,'Несите ваши денежки',1,'2018-10-09 15:38:24',1),(3,'Разработчики',NULL,'Code Coding Coders',1,'2018-10-09 15:38:56',1),(4,'Мажоры',NULL,'$$$',1,'2018-10-11 15:25:13',1),(5,'Молельный дом',NULL,'Брат во Христе',1,'2018-10-11 15:27:45',1),(6,'Web-инструменты',1,'',1,'2018-10-16 15:52:44',0),(7,'Инструменты продаж',2,'',1,'2018-10-16 15:53:38',0),(8,'mShop (YOOM.SHOP)',1,'',1,'2018-10-16 16:00:28',0),(9,'ООО Рога и копыта',2,'',1,'2018-10-16 16:04:01',1),(10,'ООО Рога и копыта',2,'',1,'2018-10-16 16:04:10',1),(11,'Марципанчик',1,'',1,'2018-10-16 16:04:39',1),(12,'Ладушки',NULL,'',1,'2018-10-16 16:06:54',1),(13,'Портальные сервисы',3,'',1,'2018-10-16 17:37:04',0),(14,'Airlines',4,'',1,'2018-10-17 09:42:05',0),(15,'E-commerce basic products',1,'',1,'2018-10-17 09:42:12',0),(16,'mShop (YOOM.SHOP)',1,'',1,'2018-10-17 09:42:20',0),(17,'PAY\'s+54FZ',1,'',1,'2018-10-17 09:42:37',0),(18,'И-эквайринг 2.0 (связки)',4,'',1,'2018-10-17 09:42:44',0),(19,'Смарт POS (2 в 1)',1,'',1,'2018-10-17 09:42:53',0),(20,'Эквайринг. Альтернативные платежные системы (CUP/JCB/МИР)',1,'',1,'2018-10-17 09:43:04',0),(21,'Эквайринг. Инновационные способы оплаты (биометрия, QR и т.п.)',4,'',1,'2018-10-17 09:43:11',0),(22,'Эквайринг. Мобильные решения (mPOS)',1,'',1,'2018-10-17 09:43:20',0),(23,'Эквайринг. Продуктовые решения на POS (базовый продукт)',1,'',1,'2018-10-17 09:43:42',0),(24,'Эквайринг.UPOS.Вендинги',1,'',1,'2018-10-17 09:43:56',0),(25,'Acquiring_Product_(Offline&Online)',3,'',1,'2018-10-17 09:46:11',0),(26,'Розничный бизнес',5,'',1,'2018-10-17 09:57:35',0);
/*!40000 ALTER TABLE `sys_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_users`
--

DROP TABLE IF EXISTS `sys_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT 'Отображаемое имя пользователя',
  `login` varchar(64) NOT NULL COMMENT 'Логин',
  `password` varchar(255) NOT NULL COMMENT 'Хеш пароля',
  `salt` varchar(255) NOT NULL COMMENT 'Unique random salt hash',
  `email` varchar(255) NOT NULL COMMENT 'email',
  `comment` text COMMENT 'Служебный комментарий пользователя',
  `create_date` datetime NOT NULL COMMENT 'Дата регистрации',
  `profile_image` varchar(255) DEFAULT NULL COMMENT 'Фото профиля',
  `daddy` int(11) DEFAULT NULL COMMENT 'ID зарегистрировавшего/проверившего пользователя',
  `deleted` tinyint(1) DEFAULT '0' COMMENT 'Флаг удаления',
  `position` int(11) DEFAULT NULL COMMENT 'Должность/позиция',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`),
  KEY `username` (`username`),
  KEY `daddy` (`daddy`),
  KEY `deleted` (`deleted`),
  KEY `position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_users`
--

LOCK TABLES `sys_users` WRITE;
/*!40000 ALTER TABLE `sys_users` DISABLE KEYS */;
INSERT INTO `sys_users` VALUES (1,'Дубровский Павел Николаевич','admin','91c12d15ccdd587d4949cfaa56d2e2d10377caca','7621147c8178b68c1a2bbd44ec50ecda43430e80','admin@POZITRONEBOOK','Погонщик гоблинов','2018-10-06 18:27:28',NULL,NULL,0,25),(2,'Колобов Станислав Александрович','artemy','e1a28d4f71269e9a800ec2afdbcabc55c47df490','eea0d1ad05ac6ecc745858d22d7ee8291fdd28af','artemy@lebedev.ru','Ответственный за портвейн','2018-10-09 11:52:31',NULL,1,0,NULL),(3,'Моисеенко Евгений Александрович','dud','f86d91ca739991eef317652e614493c84ce1a6e9','16670b39970357209e98c322ee19595ca43b1eae','yuri@dud.ru','Человек, который ел одну картошку','2018-10-09 12:03:00',NULL,1,0,NULL),(4,'Суарес Иван Иванович','homer','717c36330477311557254797fe0e8a610de34adc','749fd223df1eab47de7f85fec5d7f80ca6d1ea28','homer@simpson.ru','D\'oh!','2018-10-09 12:03:41',NULL,1,0,NULL),(5,'Белоглазов Николай Вячеславович','pablo','9c5ac1d7f81b9414471ad79c7d62bdb5a188ca18','6cec8e0c1126e602c1d352b72e05ae014a06cb7e','pablo@escobar.ru','Торговец чёрным деревом','2018-10-09 12:04:13',NULL,1,0,NULL),(6,'Большаков Виталий Евгеньевич','peppa','72470d4fc3cf986fecb3f4f1e8d54ee5e616b2c7','b15bfd574e5047d44b30947c579e4a82fae81487','peppa@pig.ru','Драм энд бейс энд энтропия','2018-10-09 12:04:55',NULL,1,0,NULL),(7,'Караичев Сергей Александрович','pickles','5a451b024a2248f86022b6e1fdad5e453711dd1b','5c5b723e3b04b205d8d010a695f410f9ec2f7dfb','pickles@rick.ru','Я превратил себя в огурчик!','2018-10-09 12:05:52','7.gif',1,0,NULL),(8,'Печерских Александр Геннадьевич','bear','656a9874baad40ca3ac3c0ec3b245739a4d51e11','08a718c8a6e25282fe26e78c17f3c5d23ddb410b','bear@rick.ru','Не думаю, что это хорошая идея','2018-10-09 12:06:34',NULL,1,0,NULL),(9,'Ахмадеев Рустем Азатович','genady','5d4f9ee788900faacc85001720a0f6e4953fad36','427bba9406e3ad01a13c6de09c3dd9e5383c3be4','genady@viktorovich.ru','Генадий Викторович Уотерсон','2018-10-09 12:07:28',NULL,1,0,NULL),(10,'Гандыбин Илья Валерьевич','alexandra','3434df7425b734f7400d607073eee5650d05de39','5e208a06fffe57b7fdc5a85629bfff9ab7f64fb2','alexandra@grey.ru','Скромная девушка Саша','2018-10-09 12:08:32',NULL,1,0,NULL),(11,'Ермаченок Алексей Андреевич','ermachenok','123','83a36f3b9389bb4bd7e7c39cdf1a39cb9e8aa515','ermachenok@localhost','','2018-10-16 17:02:16',NULL,1,0,NULL),(12,'Заворотнев Александр Николаевич','zavorotnev','123','dd3c3c71e1ade0143bd202858addfca8dc5b1c8c','zavorotnev@localhost','','2018-10-16 17:02:57',NULL,1,0,NULL),(13,'Истомин Роман Сергеевич','istomin','123','1ed452b99b4cf87dbabd01446761a5953a32a0ce','istomin','','2018-10-16 17:03:17',NULL,1,0,NULL),(14,'Клемин Илья Вячеславович','klemin','123','8a781406adb9d68ad6f8a68ad68354c535e00168','klemin@localhost','','2018-10-16 17:03:39',NULL,1,0,NULL),(15,'Комарова Анна Валерьевна','komarova','123','0149e9dd007c158fc5ae8ef1ea8769d7795304af','komarova','','2018-10-16 17:04:10',NULL,1,0,NULL),(16,'Лапин Алексей Сергеевич','lapin','123','d5902efe7c2afea3bd97f9846e211b38caeb48d1','lapin@localhost','','2018-10-16 17:04:40',NULL,1,0,NULL),(17,'Половинкин Виктор Михайлович','polovinkin','123','78791d3677e89c3884f547647579c7c1bd94ddc8','polovinkin@localhost','','2018-10-16 17:05:12',NULL,1,0,NULL),(18,'Понкратенко Евгений Валентинович','ponkratenko','123','002bbd270071a08e16486deed0174c3ef77daf69','ponkratenko@localhost','','2018-10-16 17:05:58',NULL,1,0,NULL),(19,'Редькин Станислав Михайлович','redkin','123','6f06c3c74295494589de852b37ac09cc5181c2da','redkin@localhost','','2018-10-16 17:06:22',NULL,1,0,NULL),(20,'Морякин Дмитрий Евгеньевич','moryakin','123','762fc1fc3d50df17db489b4693c6eab5c313d2f0','moryakin@localhost','','2018-10-16 17:38:30',NULL,1,0,NULL);
/*!40000 ALTER TABLE `sys_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-18 19:11:44
