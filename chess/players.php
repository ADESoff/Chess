<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Получение списка игроков с их рейтингом
$query = "SELECT u.id, u.username, u.rating, u.games_played, 
          (SELECT COUNT(*) FROM tournament_participants tp 
           JOIN tournaments t ON tp.tournament_id = t.id 
           WHERE tp.user_id = u.id AND t.status = 'completed') as tournaments_played
          FROM users u 
          ORDER BY u.rating DESC";
try {
    $stmt = $pdo->query($query);
    $players = $stmt->fetchAll();
} catch(PDOException $e) {
    $players = [];
}

$current_page = 'players';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Игроки - Шахматный портал</title>
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
            <i class="fas fa-users"></i>
            Игроки
        </h1>

        <div class="players-section">
            <div class="players-grid">
                <?php foreach ($players as $player): ?>
                    <div class="player-card">
                        <div class="player-avatar">
                            <i class="fas fa-chess-pawn"></i>
                        </div>
                        <div class="player-info">
                            <h3 class="player-name"><?php echo htmlspecialchars($player['username']); ?></h3>
                            <div class="player-stats">
                                <div class="stat">
                                    <i class="fas fa-star"></i>
                                    <span>Рейтинг: <?php echo $player['rating']; ?></span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-chess"></i>
                                    <span>Игр: <?php echo $player['games_played']; ?></span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-trophy"></i>
                                    <span>Турниров: <?php echo $player['tournaments_played']; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $player['id']): ?>
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-gamepad"></i> Вызвать на игру
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
