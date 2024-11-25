-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 24 2024 г., 14:19
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `chess_portal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `achievements`
--

CREATE TABLE `achievements` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `points` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `description`, `requirements`, `points`, `created_at`) VALUES
(1, 'Первая победа', 'Одержите первую победу в турнире', 'Победа в турнирной партии', 10, '2024-11-23 12:38:49'),
(2, 'Мастер дебютов', 'Успешно используйте 5 разных дебютов', '5 разных дебютов с положительным результатом', 20, '2024-11-23 12:38:49'),
(3, 'Тактический гений', 'Выполните 10 тактических комбинаций', '10 успешных тактических ходов', 30, '2024-11-23 12:38:49'),
(4, 'Стратег', 'Победите в 5 длинных партиях', '5 побед в партиях более 40 ходов', 40, '2024-11-23 12:38:49'),
(5, 'Гроссмейстер', 'Достигните рейтинга 2000', 'Рейтинг 2000 и выше', 100, '2024-11-23 12:38:49');

-- --------------------------------------------------------

--
-- Структура таблицы `game_analysis`
--

CREATE TABLE `game_analysis` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `tournament_id` int DEFAULT NULL,
  `game_date` datetime DEFAULT NULL,
  `opening_used` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tactical_moves_count` int DEFAULT NULL,
  `positional_score` decimal(5,2) DEFAULT NULL,
  `mistakes_count` int DEFAULT NULL,
  `critical_moments_score` decimal(4,2) DEFAULT NULL,
  `endgame_performance` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson_exercises`
--

CREATE TABLE `lesson_exercises` (
  `id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `solution` json NOT NULL,
  `hint` text COLLATE utf8mb4_unicode_ci,
  `order_number` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `lesson_exercises`
--

INSERT INTO `lesson_exercises` (`id`, `lesson_id`, `title`, `description`, `position`, `solution`, `hint`, `order_number`, `created_at`) VALUES
(2, 13, 'Ход конем', 'Найдите все возможные ходы конем из начальной позиции', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', '[\"b1a3\", \"b1c3\"]', 'Конь ходит буквой \"Г\" - на две клетки вперед и одну в сторону', 1, '2024-11-23 20:43:29'),
(3, 14, 'Двойной удар ферзем', 'Найдите двойной удар ферзем, атакующий две фигуры противника', '4k3/8/8/8/8/8/3Q4/4K3 w - - 0 1', '[\"d2e3\"]', 'Ищите позицию, где ферзь может атаковать несколько целей одновременно', 1, '2024-11-23 20:43:29'),
(4, 15, 'Защита короля', 'Определите лучший способ защиты короля в эндшпиле', '4k3/8/8/8/8/8/4P3/4K3 w - - 0 1', '[\"e1e2\"]', 'В эндшпиле король становится активной фигурой', 1, '2024-11-23 20:43:29');

-- --------------------------------------------------------

--
-- Структура таблицы `matches`
--

CREATE TABLE `matches` (
  `id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `player1_id` int NOT NULL,
  `player2_id` int NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `result` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `round` int DEFAULT NULL,
  `winner_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'view_dashboard', 'Просмотр панели управления', '2024-11-23 18:25:29'),
(2, 'edit_profile', 'Редактирование своего профиля', '2024-11-23 18:25:29'),
(3, 'manage_users', 'Управление пользователями', '2024-11-23 18:25:29'),
(4, 'view_users', 'Просмотр списка пользователей', '2024-11-23 18:25:29'),
(5, 'edit_users', 'Редактирование пользователей', '2024-11-23 18:25:29'),
(6, 'delete_users', 'Удаление пользователей', '2024-11-23 18:25:29'),
(7, 'manage_lessons', 'Управление уроками', '2024-11-23 18:25:29'),
(8, 'create_lessons', 'Создание уроков', '2024-11-23 18:25:29'),
(9, 'edit_lessons', 'Редактирование уроков', '2024-11-23 18:25:29'),
(10, 'delete_lessons', 'Удаление уроков', '2024-11-23 18:25:29'),
(11, 'view_lessons', 'Просмотр уроков', '2024-11-23 18:25:29'),
(12, 'manage_tournaments', 'Управление турнирами', '2024-11-23 18:25:29'),
(13, 'create_tournaments', 'Создание турниров', '2024-11-23 18:25:29'),
(14, 'edit_tournaments', 'Редактирование турниров', '2024-11-23 18:25:29'),
(15, 'delete_tournaments', 'Удаление турниров', '2024-11-23 18:25:29'),
(16, 'view_tournaments', 'Просмотр турниров', '2024-11-23 18:25:29'),
(17, 'manage_quests', 'Управление квестами', '2024-11-23 18:25:29'),
(18, 'create_quests', 'Создание квестов', '2024-11-23 18:25:29'),
(19, 'edit_quests', 'Редактирование квестов', '2024-11-23 18:25:29'),
(20, 'delete_quests', 'Удаление квестов', '2024-11-23 18:25:29'),
(21, 'view_quests', 'Просмотр квестов', '2024-11-23 18:25:29'),
(22, 'manage_trainings', 'Управление тренировками', '2024-11-23 18:25:29'),
(23, 'create_trainings', 'Создание тренировок', '2024-11-23 18:25:29'),
(24, 'edit_trainings', 'Редактирование тренировок', '2024-11-23 18:25:29'),
(25, 'delete_trainings', 'Удаление тренировок', '2024-11-23 18:25:29'),
(26, 'view_trainings', 'Просмотр тренировок', '2024-11-23 18:25:29');

-- --------------------------------------------------------

--
-- Структура таблицы `quests`
--

CREATE TABLE `quests` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `reward_type` enum('certificate','training','points') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reward_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `quests`
--

INSERT INTO `quests` (`id`, `name`, `description`, `requirements`, `reward_type`, `reward_value`, `created_at`) VALUES
(1, 'Пять турниров', 'Сыграйте в пяти турнирах', 'Участие в 5 турнирах', 'certificate', 'Сертификат участника', '2024-11-23 12:38:49'),
(2, 'Решение задач', 'Решайте задачи каждый день в течение недели', '50 задач в день', 'training', 'Индивидуальная тренировка', '2024-11-23 12:38:49'),
(3, 'Сеанс одновременной игры', 'Участвуйте в сеансе одновременной игры', 'Участие в сеансе', 'points', '100', '2024-11-23 12:38:49'),
(4, 'Битва с тренером', 'Вызовите тренера на партию', 'Сыграть партию с тренером', 'training', 'Анализ партии с гроссмейстером', '2024-11-23 12:38:49'),
(5, 'Обучающие видео', 'Посмотрите все обучающие видео', 'Просмотр всех видео', 'points', '50', '2024-11-23 12:38:49'),
(6, 'Научи друга', 'Научите друга играть в шахматы', 'Привести нового игрока', 'certificate', 'Сертификат наставника', '2024-11-23 12:38:49');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Администратор системы с полным доступом', '2024-11-23 18:25:29', '2024-11-23 18:25:29'),
(2, 'trainer', 'Тренер с возможностью создания и управления уроками', '2024-11-23 18:25:29', '2024-11-23 18:25:29'),
(3, 'user', 'Обычный пользователь системы', '2024-11-23 18:25:29', '2024-11-23 18:25:29');

-- --------------------------------------------------------

--
-- Структура таблицы `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 2),
(3, 2),
(1, 3),
(1, 4),
(2, 4),
(1, 5),
(1, 6),
(1, 7),
(2, 7),
(1, 8),
(2, 8),
(1, 9),
(2, 9),
(1, 10),
(2, 10),
(1, 11),
(2, 11),
(3, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(2, 16),
(3, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(2, 21),
(3, 21),
(1, 22),
(2, 22),
(1, 23),
(2, 23),
(1, 24),
(2, 24),
(1, 25),
(2, 25),
(1, 26),
(2, 26),
(3, 26);

-- --------------------------------------------------------

--
-- Структура таблицы `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('upcoming','active','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'upcoming',
  `max_participants` int DEFAULT NULL,
  `type` enum('round_robin','elimination','swiss') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'round_robin',
  `time_control` int NOT NULL DEFAULT '10',
  `current_participants` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `description`, `start_date`, `end_date`, `status`, `max_participants`, `type`, `time_control`, `current_participants`, `created_at`) VALUES
(1, 'Весенний турнир 2024', 'Традиционный весенний турнир для всех любителей шахмат', '2024-03-01 10:00:00', '2024-03-15 18:00:00', 'upcoming', 32, 'round_robin', 10, 0, '2024-11-23 19:34:48'),
(2, 'Блиц-турнир', 'Быстрые шахматы для опытных игроков', '2024-02-25 15:00:00', '2024-02-25 20:00:00', 'upcoming', 16, 'round_robin', 10, 0, '2024-11-23 19:34:48'),
(3, 'Детский турнир', 'Турнир для юных шахматистов до 14 лет', '2024-03-10 12:00:00', '2024-03-10 17:00:00', 'upcoming', 20, 'round_robin', 10, 0, '2024-11-23 19:34:48'),
(4, 'Гранд-мастер 2024', 'Престижный турнир с участием сильнейших игроков', '2024-04-01 10:00:00', '2024-04-10 18:00:00', 'upcoming', 8, 'round_robin', 10, 0, '2024-11-23 19:34:48'),
(5, 'Весенний турнир 2024', 'Традиционный весенний турнир для всех любителей шахмат', '2024-03-01 10:00:00', '2024-03-15 18:00:00', 'upcoming', 32, 'round_robin', 10, 0, '2024-11-23 19:47:04'),
(6, 'Блиц-турнир', 'Быстрые шахматы для опытных игроков', '2024-02-25 15:00:00', '2024-02-25 20:00:00', 'upcoming', 16, 'round_robin', 10, 0, '2024-11-23 19:47:04'),
(7, 'Детский турнир', 'Турнир для юных шахматистов до 14 лет', '2024-03-10 12:00:00', '2024-03-10 17:00:00', 'upcoming', 20, 'round_robin', 10, 0, '2024-11-23 19:47:04'),
(8, 'Гранд-мастер 2024', 'Престижный турнир с участием сильнейших игроков', '2024-04-01 10:00:00', '2024-04-10 18:00:00', 'upcoming', 8, 'round_robin', 10, 0, '2024-11-23 19:47:04'),
(9, 'Весенний турнир 2024', 'Традиционный весенний турнир для всех любителей шахмат', '2024-03-01 10:00:00', '2024-03-15 18:00:00', 'upcoming', 32, 'round_robin', 10, 0, '2024-11-23 20:30:22'),
(10, 'Блиц-турнир', 'Быстрые шахматы для опытных игроков', '2024-02-25 15:00:00', '2024-02-25 20:00:00', 'upcoming', 16, 'round_robin', 10, 0, '2024-11-23 20:30:22'),
(11, 'Детский турнир', 'Турнир для юных шахматистов до 14 лет', '2024-03-10 12:00:00', '2024-03-10 17:00:00', 'upcoming', 20, 'round_robin', 10, 0, '2024-11-23 20:30:22'),
(12, 'Гранд-мастер 2024', 'Престижный турнир с участием сильнейших игроков', '2024-04-01 10:00:00', '2024-04-10 18:00:00', 'upcoming', 8, 'round_robin', 10, 0, '2024-11-23 20:30:22'),
(13, 'Весенний турнир 2024', 'Традиционный весенний турнир для всех любителей шахмат', '2024-03-01 10:00:00', '2024-03-15 18:00:00', 'upcoming', 32, 'round_robin', 10, 0, '2024-11-23 20:33:18'),
(14, 'Блиц-турнир', 'Быстрые шахматы для опытных игроков', '2024-02-25 15:00:00', '2024-02-25 20:00:00', 'upcoming', 16, 'round_robin', 10, 0, '2024-11-23 20:33:18'),
(15, 'Детский турнир', 'Турнир для юных шахматистов до 14 лет', '2024-03-10 12:00:00', '2024-03-10 17:00:00', 'upcoming', 20, 'round_robin', 10, 0, '2024-11-23 20:33:18'),
(16, 'Гранд-мастер 2024', 'Престижный турнир с участием сильнейших игроков', '2024-04-01 10:00:00', '2024-04-10 18:00:00', 'upcoming', 8, 'round_robin', 10, 0, '2024-11-23 20:33:18'),
(17, 'Весенний турнир 2024', 'Традиционный весенний турнир для всех любителей шахмат', '2024-03-01 10:00:00', '2024-03-15 18:00:00', 'upcoming', 32, 'round_robin', 10, 0, '2024-11-23 20:36:31'),
(18, 'Блиц-турнир', 'Быстрые шахматы для опытных игроков', '2024-02-25 15:00:00', '2024-02-25 20:00:00', 'upcoming', 16, 'round_robin', 10, 0, '2024-11-23 20:36:31'),
(19, 'Детский турнир', 'Турнир для юных шахматистов до 14 лет', '2024-03-10 12:00:00', '2024-03-10 17:00:00', 'upcoming', 20, 'round_robin', 10, 0, '2024-11-23 20:36:31'),
(20, 'Гранд-мастер 2024', 'Престижный турнир с участием сильнейших игроков', '2024-04-01 10:00:00', '2024-04-10 18:00:00', 'upcoming', 8, 'round_robin', 10, 0, '2024-11-23 20:36:31');

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_participants`
--

CREATE TABLE `tournament_participants` (
  `id` int NOT NULL,
  `tournament_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tournament_participants`
--

INSERT INTO `tournament_participants` (`id`, `tournament_id`, `user_id`, `registration_date`) VALUES
(1, 2, 2, '2024-11-23 20:04:13'),
(2, 6, 2, '2024-11-23 20:07:01');

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_results`
--

CREATE TABLE `tournament_results` (
  `id` int NOT NULL,
  `tournament_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `opponent_id` int DEFAULT NULL,
  `result` enum('win','loss','draw') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` decimal(4,2) DEFAULT NULL,
  `move_count` int DEFAULT NULL,
  `average_move_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `training_materials`
--

CREATE TABLE `training_materials` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci,
  `difficulty_level` enum('beginner','intermediate','advanced') COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int DEFAULT '30',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `training_materials`
--

INSERT INTO `training_materials` (`id`, `title`, `short_description`, `content`, `difficulty_level`, `duration`, `created_at`) VALUES
(13, 'Основы шахмат', 'Знакомство с шахматной доской, фигурами и базовыми правилами игры', '<h2>Введение в шахматы</h2>\r\n<p>Шахматы - это древняя игра, которая развивает стратегическое мышление и логику.</p>\r\n<h3>Шахматная доска</h3>\r\n<ul>\r\n<li>64 клетки (8x8)</li>\r\n<li>Чередование белых и черных полей</li>\r\n<li>Правильное расположение доски: белое поле должно быть справа внизу</li>\r\n</ul>\r\n<h3>Шахматные фигуры</h3>\r\n<ul>\r\n<li>Король - самая важная фигура</li>\r\n<li>Ферзь - самая сильная фигура</li>\r\n<li>Ладья - ходит по горизонтали и вертикали</li>\r\n<li>Слон - ходит по диагонали</li>\r\n<li>Конь - ходит буквой \"Г\"</li>\r\n<li>Пешка - ходит вперед, бьет по диагонали</li>\r\n</ul>', 'beginner', 30, '2024-11-23 20:43:29'),
(14, 'Тактические приемы', 'Изучение основных тактических приемов в шахматах', '<h2>Базовые тактические приемы</h2>\r\n<p>Тактика - это краткосрочные комбинации ходов для получения материального или позиционного преимущества.</p>\r\n<h3>Основные тактические приемы</h3>\r\n<ul>\r\n<li>Вилка - атака одной фигурой двух или более фигур противника</li>\r\n<li>Связка - ограничение подвижности фигуры из-за угрозы более ценной фигуре</li>\r\n<li>Двойной удар - атака двух фигур противника одновременно</li>\r\n<li>Открытое нападение - атака, возникающая после ухода своей фигуры с линии атаки</li>\r\n</ul>', 'intermediate', 45, '2024-11-23 20:43:29'),
(15, 'Стратегия игры', 'Долгосрочное планирование и позиционная игра', '<h2>Основы шахматной стратегии</h2>\r\n<p>Стратегия в шахматах - это долгосрочное планирование, направленное на улучшение позиции.</p>\r\n<h3>Ключевые стратегические концепции</h3>\r\n<ul>\r\n<li>Контроль центра</li>\r\n<li>Развитие фигур</li>\r\n<li>Безопасность короля</li>\r\n<li>Пешечная структура</li>\r\n<li>Открытые линии</li>\r\n</ul>', 'advanced', 60, '2024-11-23 20:43:29');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `training_status` enum('self','club') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `sports_rank` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `competition_history` text COLLATE utf8mb4_unicode_ci,
  `training_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_sports` text COLLATE utf8mb4_unicode_ci,
  `role` enum('user','admin','trainer') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `school`, `grade`, `location`, `age`, `gender`, `training_status`, `coach_name`, `rating`, `sports_rank`, `competition_history`, `training_time`, `other_sports`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Alexnadr', 'alieksa.@mail.ru', 'a1031034303', 'asdasd', 'sdsd', 'dadcasd', 15, 'male', 'club', 'adsadsa', 5, '23 dfd', 'weqe ', 'ewqe ', 'qwe ', 'user', '2024-11-23 12:43:41', '2024-11-23 12:43:41'),
(2, 'Александр Сергеевич Гунари', 'alieksandr.gunari.06@mail.ru', '$2y$10$3WUUgLrAPvQuNKNEt45JOO4hhNAzefM2mRxtaWRqUMa88U3dyE2FK', 'asd', 'asd1', 'asdasd', 15, 'male', 'self', '', 123, '123', '123cqds', '12', '1234cf', 'user', '2024-11-23 12:44:43', '2024-11-23 12:44:43');

-- --------------------------------------------------------

--
-- Структура таблицы `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `achievement_id` int DEFAULT NULL,
  `achieved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `email_notifications` tinyint(1) DEFAULT '1',
  `tournament_notifications` tinyint(1) DEFAULT '1',
  `game_invites` tinyint(1) DEFAULT '1',
  `news_notifications` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `email_notifications`, `tournament_notifications`, `game_invites`, `news_notifications`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 1, 1, '2024-11-23 15:13:05', '2024-11-23 15:13:05');

-- --------------------------------------------------------

--
-- Структура таблицы `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `exercise_id` int NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_quests`
--

CREATE TABLE `user_quests` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `quest_id` int DEFAULT NULL,
  `progress` int DEFAULT '0',
  `completed` tinyint(1) DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `game_analysis`
--
ALTER TABLE `game_analysis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Индексы таблицы `lesson_exercises`
--
ALTER TABLE `lesson_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Индексы таблицы `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `player1_id` (`player1_id`),
  ADD KEY `player2_id` (`player2_id`),
  ADD KEY `winner_id` (`winner_id`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `quests`
--
ALTER TABLE `quests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Индексы таблицы `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tournament_participants`
--
ALTER TABLE `tournament_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_participant` (`tournament_id`,`user_id`);

--
-- Индексы таблицы `tournament_results`
--
ALTER TABLE `tournament_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `opponent_id` (`opponent_id`);

--
-- Индексы таблицы `training_materials`
--
ALTER TABLE `training_materials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `achievement_id` (`achievement_id`);

--
-- Индексы таблицы `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`user_id`);

--
-- Индексы таблицы `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_exercise` (`user_id`,`exercise_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Индексы таблицы `user_quests`
--
ALTER TABLE `user_quests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quest_id` (`quest_id`);

--
-- Индексы таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `game_analysis`
--
ALTER TABLE `game_analysis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lesson_exercises`
--
ALTER TABLE `lesson_exercises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `quests`
--
ALTER TABLE `quests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `tournament_participants`
--
ALTER TABLE `tournament_participants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `tournament_results`
--
ALTER TABLE `tournament_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `training_materials`
--
ALTER TABLE `training_materials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_quests`
--
ALTER TABLE `user_quests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `game_analysis`
--
ALTER TABLE `game_analysis`
  ADD CONSTRAINT `game_analysis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `game_analysis_ibfk_2` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`);

--
-- Ограничения внешнего ключа таблицы `lesson_exercises`
--
ALTER TABLE `lesson_exercises`
  ADD CONSTRAINT `lesson_exercises_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `training_materials` (`id`);

--
-- Ограничения внешнего ключа таблицы `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`player1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`player2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_4` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournament_participants`
--
ALTER TABLE `tournament_participants`
  ADD CONSTRAINT `tournament_participants_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`);

--
-- Ограничения внешнего ключа таблицы `tournament_results`
--
ALTER TABLE `tournament_results`
  ADD CONSTRAINT `tournament_results_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`),
  ADD CONSTRAINT `tournament_results_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tournament_results_ibfk_3` FOREIGN KEY (`opponent_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `lesson_exercises` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_quests`
--
ALTER TABLE `user_quests`
  ADD CONSTRAINT `user_quests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_quests_ibfk_2` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
