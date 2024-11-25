<?php
session_start();

// Define base path constants
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');

// Include required files
require_once CONFIG_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';

$page_title = 'Главная';
$current_page = 'home';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Шахматный портал - Главная</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/tournaments.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <nav class="main-nav">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="Шахматный портал">
                <span>Шахматный портал</span>
            </a>
            <div class="nav-menu">
                <a href="index.php" <?php echo $current_page === 'home' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-home"></i> Главная
                </a>
                <a href="tournaments.php" <?php echo $current_page === 'tournaments' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-trophy"></i> Турниры
                </a>
                <a href="my_tournaments.php" <?php echo $current_page === 'my_tournaments' ? 'class = "active"' : ''; ?>">
                     <i class="fas fa-chess"></i> Мои турниры
                </a>
                <a href="players.php" <?php echo $current_page === 'players' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-users"></i> Игроки
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="training.php" <?php echo $current_page === 'training' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-graduation-cap"></i> Обучение
                </a>
                    <a href="dashboard.php" <?php echo $current_page === 'dashboard' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-chess-board"></i> Личный кабинет
                    </a>
                    <a href="logout.php" class="nav-menu-right">
                        <i class="fas fa-sign-out-alt"></i> Выход
                    </a>
                <?php else: ?>
                    <div class="nav-menu-right">
                        <a href="login.php" <?php echo $current_page === 'login' ? 'class="active"' : ''; ?>>
                            <i class="fas fa-sign-in-alt"></i> Вход
                        </a>
                        <a href="register.php" <?php echo $current_page === 'register' ? 'class="active"' : ''; ?>>
                            <i class="fas fa-user-plus"></i> Регистрация
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container">
        <h1 class="page-title">
            <i class="fas fa-chess"></i>
            Добро пожаловать в шахматный портал
        </h1>

        <div class="content-section">
            <div class="info-cards">
                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Турниры</h3>
                    <p>Участвуйте в турнирах различного уровня и развивайте свои навыки</p>
                    <a href="tournaments.php" class="btn btn-primary">Перейти к турнирам</a>
                </div>

                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Сообщество</h3>
                    <p>Присоединяйтесь к сообществу шахматистов и находите новых соперников</p>
                    <a href="players.php" class="btn btn-primary">Найти игроков</a>
                </div>

                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-chess-board"></i>
                    </div>
                    <h3>Тренировки</h3>
                    <p>Улучшайте свою игру с помощью наших тренировочных материалов</p>
                    <a href="training.php" class="btn btn-primary">Начать тренировку</a>
                </div>
            </div>
        </div>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="cta-section">
                <h2>Присоединяйтесь к нам</h2>
                <p>Создайте аккаунт, чтобы участвовать в турнирах и отслеживать свой прогресс</p>
                <div class="cta-buttons">
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Регистрация
                    </a>
                    <a href="login.php" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Вход
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
