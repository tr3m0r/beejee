-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 26 2024 г., 19:32
-- Версия сервера: 5.7.35-38
-- Версия PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tr3m0r_todo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `todo_tasks`
--

CREATE TABLE IF NOT EXISTS `todo_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `task_description` text NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `edited` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `todo_tasks`
--

INSERT INTO `todo_tasks` (`id`, `user_name`, `user_email`, `task_description`, `completed`, `edited`) VALUES
(1, 'Николай Егоров', 'egorovnv@beegee.org', 'Код-ревью списка задач', 0, 0),
(2, 'Степан Елизаров', 'stepanev@beegee.org', 'Доработать UI', 1, 0),
(3, 'Федосей Смирнов', 'smirnoff@beegee.org', 'Проверить таски в трелло', 0, 0),
(4, 'Иммануил Филимонов', 'ceo@beejee.org', 'Встреча со стратегическими партнёрами по шахматам', 0, 0),
(5, 'Фёдор Спиваков', 'spivak@beejee.org', 'Составить отчёт', 0, 0),
(6, 'Фёкла Степанова', 'stepanovafm@beejee.org', 'Созвониться с заказчиками', 0, 0),
(7, 'Дарина Авзалова', 'avzalovado@beejee.org', 'Провести брейншторминг с командой', 0, 1),
(8, 'Снежана Юдина', 'yudinasm@beejee.org', 'Организовать созвон', 0, 0),
(9, 'Светлана Смирнова', 'smirnovasn@beejee.ru', 'Отправить корреспонденцию заказными письмами', 0, 0),
(10, 'Василиса Иларионова', 'vasya@beejee.org', 'Созвон с командой в 9 вечера', 1, 1),
(11, 'Елизавета Яшина', 'yashinaliza@beejee.org', 'Доработать презентацию продукта', 0, 1),
(12, 'Михаил Ярушин', 'yarushinmz@beejee.ru', 'Встреча с подрядчиком в 9:16', 1, 0),
(13, 'Марина Фурсеева', 'furseeva@beejee.org', 'Описание задачи', 0, 0),
(14, 'Святослав Свидригайлов', 'svyatsvyatsvyat@beejee.org', 'Текст новой задачи для Святослава', 0, 0),
(15, 'Юнона Клячева', 'klyachevayn@beejee.org', 'Здесь задача для Юноны', 0, 0),
(16, 'Леонид Илларионов', 'illarionovlv@beejee.org', '&lt;script&gt;alert(&#039;test&#039;)&lt;/script&gt;', 0, 1),
(17, 'Тест', 'alert@beejee.org', '&lt;script&gt;alert(&#039;test&#039;)&lt;/script&gt;', 0, 1),
(18, 'Деонис Матвеев', 'matveev@beejee.org', 'Проверить почту', 0, 0),
(19, 'Елизавета Пригорелова', 'progorelovaes@beejee.org', 'Написать статью о проведении конференции', 0, 0),
(20, 'Александра Семёнова', 'semyonovaaa@beejee.org', 'Провести собеседование', 0, 0),
(21, 'Елизар Смирнов', 'smirnoffez@beejee.org', 'Дать обратную связь', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
