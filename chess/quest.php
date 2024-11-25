<?php
session_start();

// Define base path constants
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');

// Include required files
require_once CONFIG_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Квесты и достижения';
$current_page = 'quest';

// Получение достижений пользователя
$user_id = $_SESSION['user_id'];
$achievements = [
    [
        'id' => 1,
        'title' => 'Первая победа',
        'description' => 'Выиграйте свой первый турнирный матч',
        'icon' => 'trophy',
        'progress' => 1,
        'total' => 1,
        'completed' => true
    ],
    [
        'id' => 2,
        'title' => 'Серия побед',
        'description' => 'Выиграйте 5 матчей подряд',
        'icon' => 'fire',
        'progress' => 3,
        'total' => 5,
        'completed' => false
    ],
    [
        'id' => 3,
        'title' => 'Турнирный боец',
        'description' => 'Примите участие в 10 турнирах',
        'icon' => 'medal',
        'progress' => 7,
        'total' => 10,
        'completed' => false
    ],
    [
        'id' => 4,
        'title' => 'Мастер тактики',
        'description' => 'Решите 50 тактических задач',
        'icon' => 'chess-knight',
        'progress' => 35,
        'total' => 50,
        'completed' => false
    ]
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Шахматный портал</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .achievements-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .achievement-item {
            background: #fff;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid #e0e0e0;
            position: relative;
        }
        .achievement-item.completed {
            border-color: #28a745;
        }
        .achievement-icon {
            font-size: 1.5rem;
            color: #2a5298;
            width: 40px;
            text-align: center;
        }
        .achievement-info {
            flex: 1;
        }
        .achievement-info h4 {
            margin: 0 0 0.5rem;
            color: #333;
        }
        .achievement-info p {
            margin: 0 0 1rem;
            color: #666;
        }
        .progress {
            height: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        .progress-bar {
            background: #2a5298;
            height: 100%;
            border-radius: 4px;
        }
        .progress-text {
            font-size: 0.9rem;
            color: #666;
            text-align: right;
            display: block;
        }
        .achievement-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #28a745;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
        .stats-card .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            padding: 1rem 0;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2a5298;
            display: block;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .stats-card .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                <a href="players.php" <?php echo $current_page === 'players' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-users"></i> Игроки
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" <?php echo $current_page === 'dashboard' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-chess-board"></i> Личный кабинет
                    </a>
                    <a href="logout.php" class="nav-link">
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

    <main class="container py-4">
        <div class="dashboard-header">
            <h1><?php echo $page_title; ?></h1>
        </div>

        <div class="dashboard-grid">
            <!-- Статистика -->
            <div class="dashboard-card stats-card">
                <h3><i class="fas fa-trophy"></i> Общая статистика</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-value">7/15</span>
                        <span class="stat-label">Достижения</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">1,250</span>
                        <span class="stat-label">Очки опыта</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">5</span>
                        <span class="stat-label">Уровень</span>
                    </div>
                </div>
            </div>

            <!-- Достижения -->
            <div class="dashboard-card achievements-card">
                <h3><i class="fas fa-medal"></i> Достижения</h3>
                <div class="achievements-list">
                    <?php foreach ($achievements as $achievement): ?>
                        <div class="achievement-item <?php echo $achievement['completed'] ? 'completed' : ''; ?>">
                            <div class="achievement-icon">
                                <i class="fas fa-<?php echo $achievement['icon']; ?>"></i>
                            </div>
                            <div class="achievement-info">
                                <h4><?php echo $achievement['title']; ?></h4>
                                <p><?php echo $achievement['description']; ?></p>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?php echo ($achievement['progress'] / $achievement['total']) * 100; ?>%"></div>
                                </div>
                                <span class="progress-text"><?php echo $achievement['progress']; ?>/<?php echo $achievement['total']; ?></span>
                            </div>
                            <?php if ($achievement['completed']): ?>
                                <div class="achievement-status">
                                    <i class="fas fa-check"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
