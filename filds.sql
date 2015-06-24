-- phpMyAdmin SQL Dump
-- version 4.0.10.2
-- http://www.phpmyadmin.net
--
-- Хост: phototim.mysql.ukraine.com.ua
-- Время создания: Июн 24 2015 г., 11:40
-- Версия сервера: 5.1.72-cll-lve
-- Версия PHP: 5.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `phototim_wp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `filds`
--

CREATE TABLE IF NOT EXISTS `filds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET cp1251 NOT NULL,
  `label` varchar(100) CHARACTER SET cp1251 NOT NULL,
  `priority` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `filds`
--

INSERT INTO `filds` (`id`, `key`, `label`, `priority`) VALUES
(12, 'first_name', 'Имя', 1),
(13, 'user_login', 'Логин', 2),
(14, 'user_pass', 'Пароль', 3),
(26, 'user_email', 'Email', 4),
(16, 'name_komp', 'Имя компании', 5),
(17, 'telephone', 'Номер телефона', 6),
(18, 'inn', 'ИНН', 7),
(19, 'lic', 'Лицевой счет', 8),
(20, 'nomdogovor', 'Номер договора', 9),
(21, 'date_dog', 'Дата договора', 10),
(22, 'adres', 'Адрес', 11),
(23, 'date_rozd', 'Дата рождения', 12);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
