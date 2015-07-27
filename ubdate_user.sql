-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 27 2015 г., 14:16
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.4.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `testword`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ubdate_user`
--

CREATE TABLE IF NOT EXISTS `ubdate_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_add` int(11) NOT NULL,
  `ub_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `ubdate_user`
--

INSERT INTO `ubdate_user` (`id`, `dt_add`, `ub_user`) VALUES
(1, 2015, 3),
(2, 0, 3),
(3, 1437992770, 3),
(4, 1437994266, 3),
(5, 1437994323, 3),
(6, 1437994384, 3),
(7, 1437994443, 3),
(8, 1437994504, 3),
(9, 1437994563, 3),
(10, 1437994624, 3),
(11, 1437994683, 3),
(12, 1437994744, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
