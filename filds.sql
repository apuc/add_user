-- phpMyAdmin SQL Dump
-- version 4.0.10.2
-- http://www.phpmyadmin.net
--
-- Хост: phototim.mysql.ukraine.com.ua
-- Время создания: Июн 30 2015 г., 13:38
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
  `publication` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `filds`
--

INSERT INTO `filds` (`id`, `key`, `label`, `priority`, `publication`) VALUES
(12, 'first_name', 'Имя', 1, 1),
(13, 'user_login', 'Логин', 2, 0),
(14, 'user_pass', 'Пароль', 3, 0),
(26, 'user_email', 'Email', 4, 0),
(16, 'name_komp', 'Имя компании', 5, 0),
(17, 'telephone', 'Номер телефона', 6, 0),
(18, 'inn', 'ИНН', 7, 0),
(19, 'lic', 'Лицевой счет', 8, 0),
(20, 'nomdogovor', 'Номер договора', 9, 1),
(21, 'date_dog', 'Дата договора', 10, 0),
(22, 'adres', 'Адрес', 11, 0),
(23, 'date_rozd', 'Дата рождения', 12, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
