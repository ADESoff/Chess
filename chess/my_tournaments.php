<?php
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$current_page = 'my_tournaments';

try {
    // Получаем турниры, в которых участвует пользователь
    $query = "SELECT t.*, 
              COUNT(DISTINCT p.user_id) as participants_count,
              1 as is_participant,
              tp.registration_date
              FROM tournaments t 
              JOIN tournament_participants tp ON t.id = tp.tournament_id
              LEFT JOIN tournament_participants p ON t.id = p.tournament_id 
              WHERE tp.user_id = :user_id
              GROUP BY t.id
              ORDER BY t.start_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои турниры - Шахматный портал</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/tournaments.css">
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
        <div class="page-header">
            <h1>Мои турниры</h1>
            <p>Здесь отображаются все турниры, в которых вы участвуете.</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif (empty($tournaments)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Вы пока не участвуете ни в одном турнире. 
                <a href="tournaments.php" class="alert-link">Просмотреть доступные турниры</a>
            </div>
        <?php else: ?>
            <div class="tournament-grid">
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-card">
                        <div class="tournament-info">
                            <div class="tournament-header">
                                <h3 class="tournament-name"><?php echo htmlspecialchars($tournament['name']); ?></h3>
                                <div class="tournament-status <?php echo htmlspecialchars($tournament['status']); ?>">
                                    <?php echo getTournamentStatus($tournament['status']); ?>
                                </div>
                            </div>
                            <p class="tournament-description"><?php echo htmlspecialchars($tournament['description']); ?></p>
                            <div class="tournament-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><?php echo formatDateTime($tournament['start_date']); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <span>Участники: <?php echo (int)$tournament['participants_count']; ?> / <?php echo (int)$tournament['max_participants']; ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Регистрация: <?php echo formatDateTime($tournament['registration_date']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="tournament-actions">
                            <?php if ($tournament['status'] === 'ongoing'): ?>
                                <a href="play.php?tournament_id=<?php echo (int)$tournament['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-chess"></i> Играть
                                </a>
                            <?php endif; ?>
                            <a href="tournament_details.php?id=<?php echo (int)$tournament['id']; ?>" class="btn btn-secondary">
                                <i class="fas fa-info-circle"></i> Подробнее
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
