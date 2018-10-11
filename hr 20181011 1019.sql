--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.2.76.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 11.10.2018 10:19:23
-- Версия сервера: 5.7.20
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE hr;

--
-- Описание для таблицы employees
--
DROP TABLE IF EXISTS employees;
CREATE TABLE employees (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(512) DEFAULT NULL COMMENT 'ФИО',
  deleted TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы migration
--
DROP TABLE IF EXISTS migration;
CREATE TABLE migration (
  version VARCHAR(180) NOT NULL,
  apply_time INT(11) DEFAULT NULL,
  PRIMARY KEY (version)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1365
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы rel_users_groups
--
DROP TABLE IF EXISTS rel_users_groups;
CREATE TABLE rel_users_groups (
  user_id INT(11) NOT NULL COMMENT 'Сотрудник',
  group_id INT(11) NOT NULL COMMENT 'Рабочая группа',
  user_role_id INT(11) DEFAULT NULL,
  UNIQUE INDEX user_id_group_id (user_id, group_id),
  INDEX user_id_user_role_id (user_id, user_role_id)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1260
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы sys_exceptions
--
DROP TABLE IF EXISTS sys_exceptions;
CREATE TABLE sys_exceptions (
  id INT(11) NOT NULL AUTO_INCREMENT,
  timestamp TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  user_id INT(11) DEFAULT NULL,
  code INT(11) DEFAULT NULL,
  file VARCHAR(255) DEFAULT NULL,
  line INT(11) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  trace TEXT DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы sys_groups
--
DROP TABLE IF EXISTS sys_groups;
CREATE TABLE sys_groups (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(512) DEFAULT NULL COMMENT 'Название',
  comment TEXT DEFAULT NULL COMMENT 'Описание',
  daddy INT(11) DEFAULT NULL COMMENT 'id создателя',
  create_date DATETIME NOT NULL COMMENT 'Дата создания',
  deleted TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id),
  INDEX author (daddy)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы sys_users
--
DROP TABLE IF EXISTS sys_users;
CREATE TABLE sys_users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL COMMENT 'Отображаемое имя пользователя',
  login VARCHAR(64) NOT NULL COMMENT 'Логин',
  password VARCHAR(255) NOT NULL COMMENT 'Хеш пароля',
  salt VARCHAR(255) NOT NULL COMMENT 'Unique random salt hash',
  email VARCHAR(255) NOT NULL COMMENT 'email',
  comment TEXT DEFAULT NULL COMMENT 'Служебный комментарий пользователя',
  create_date DATETIME NOT NULL COMMENT 'Дата регистрации',
  profile_image VARCHAR(255) DEFAULT NULL COMMENT 'Фото профиля',
  daddy INT(11) DEFAULT NULL COMMENT 'ID зарегистрировавшего/проверившего пользователя',
  deleted TINYINT(1) DEFAULT 0 COMMENT 'Флаг удаления',
  PRIMARY KEY (id),
  INDEX daddy (daddy),
  INDEX deleted (deleted),
  UNIQUE INDEX email (email),
  UNIQUE INDEX login (login),
  INDEX username (username)
)
ENGINE = INNODB
AUTO_INCREMENT = 11
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

-- 
-- Вывод данных для таблицы employees
--

-- Таблица hr.employees не содержит данных

-- 
-- Вывод данных для таблицы migration
--
INSERT INTO migration VALUES
('m000000_000000_base', 1538299847),
('m180930_092340_create_sys_exceptions', 1538299849),
('m180930_093408_sys_users', 1538302120),
('m181006_140806_employee', 1538836588),
('m181006_141340_workgroups', 1538836588),
('m181006_141856_rel_employees_workroups', 1538836680),
('m181006_142719_ref_employee_roles', 1538836698),
('m181006_143104_ref_employee_role_samples', 1538836698),
('m181006_151846_add_admin_profile', 1538839651),
('m181006_190403_users_profile_image', 1538853141),
('m181009_100149_okkam', 1539079603),
('m181009_115345_sys_group_author', 1539087244),
('m181009_123059_sys_group_create_date', 1539088519),
('m181010_101831_user_role_id_not_required', 1539166829);

-- 
-- Вывод данных для таблицы rel_users_groups
--
INSERT INTO rel_users_groups VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(2, 1, NULL),
(2, 3, NULL),
(2, 15, NULL),
(2, 2, 2),
(3, 2, 2),
(4, 2, 2),
(5, 1, 2),
(6, 1, 2),
(7, 1, 2),
(8, 3, 2),
(9, 3, 2),
(10, 3, 2);

-- 
-- Вывод данных для таблицы sys_exceptions
--

-- Таблица hr.sys_exceptions не содержит данных

-- 
-- Вывод данных для таблицы sys_groups
--
INSERT INTO sys_groups VALUES
(1, 'Пятничная тусня', 'Расширение сознания легальными методами', 1, '2018-10-09 15:35:25', 0),
(2, 'Братство ножа и топора', 'Несите ваши денежки', 1, '2018-10-09 15:38:24', 0),
(3, 'Разработчики', 'Code Coding Coders', 1, '2018-10-09 15:38:56', 0);

-- 
-- Вывод данных для таблицы sys_users
--
INSERT INTO sys_users VALUES
(1, 'admin', 'admin', '91c12d15ccdd587d4949cfaa56d2e2d10377caca', '7621147c8178b68c1a2bbd44ec50ecda43430e80', 'admin@POZITRONEBOOK', 'Погонщик гоблинов', '2018-10-06 18:27:28', NULL, NULL, 0),
(2, 'Артемий Лебедев', 'artemy', 'e1a28d4f71269e9a800ec2afdbcabc55c47df490', 'eea0d1ad05ac6ecc745858d22d7ee8291fdd28af', 'artemy@lebedev.ru', 'Ответственный за портвейн', '2018-10-09 11:52:31', NULL, 1, 0),
(3, 'Юрий Дудь', 'dud', 'f86d91ca739991eef317652e614493c84ce1a6e9', '16670b39970357209e98c322ee19595ca43b1eae', 'yuri@dud.ru', 'Человек, который ел одну картошку', '2018-10-09 12:03:00', NULL, 1, 0),
(4, 'Гомер Симпсон', 'homer', '717c36330477311557254797fe0e8a610de34adc', '749fd223df1eab47de7f85fec5d7f80ca6d1ea28', 'homer@simpson.ru', 'D''oh!', '2018-10-09 12:03:41', NULL, 1, 0),
(5, 'Пабло Эскобар', 'pablo', '9c5ac1d7f81b9414471ad79c7d62bdb5a188ca18', '6cec8e0c1126e602c1d352b72e05ae014a06cb7e', 'pablo@escobar.ru', 'Торговец чёрным деревом', '2018-10-09 12:04:13', NULL, 1, 0),
(6, 'Свинка Пеппа', 'peppa', '72470d4fc3cf986fecb3f4f1e8d54ee5e616b2c7', 'b15bfd574e5047d44b30947c579e4a82fae81487', 'peppa@pig.ru', 'Драм энд бейс энд энтропия', '2018-10-09 12:04:55', NULL, 1, 0),
(7, 'Малосольный огурчик', 'pickles', '5a451b024a2248f86022b6e1fdad5e453711dd1b', '5c5b723e3b04b205d8d010a695f410f9ec2f7dfb', 'pickles@rick.ru', 'Я превратил себя в огурчик!', '2018-10-09 12:05:52', '7.gif', 1, 0),
(8, 'Медведь Шатун', 'bear', '656a9874baad40ca3ac3c0ec3b245739a4d51e11', '08a718c8a6e25282fe26e78c17f3c5d23ddb410b', 'bear@rick.ru', 'Не думаю, что это хорошая идея', '2018-10-09 12:06:34', NULL, 1, 0),
(9, 'Генадий Викторович', 'genady', '5d4f9ee788900faacc85001720a0f6e4953fad36', '427bba9406e3ad01a13c6de09c3dd9e5383c3be4', 'genady@viktorovich.ru', 'Генадий Викторович Уотерсон', '2018-10-09 12:07:28', NULL, 1, 0),
(10, 'Александра Серова', 'alexandra', '3434df7425b734f7400d607073eee5650d05de39', '5e208a06fffe57b7fdc5a85629bfff9ab7f64fb2', 'alexandra@grey.ru', 'Скромная девушка Саша', '2018-10-09 12:08:32', NULL, 1, 0);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;