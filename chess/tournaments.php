<?php
session_start();

// Define base path constants
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');

// Include required files
require_once CONFIG_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';

$current_page = 'tournaments';

try {
    // Получение списка турниров
    $query = "SELECT t.*, 
              COUNT(DISTINCT p.user_id) as participants_count,
              (SELECT COUNT(*) FROM tournament_participants WHERE tournament_id = t.id AND user_id = :user_id) as is_participant
              FROM tournaments t 
              LEFT JOIN tournament_participants p ON t.id = p.tournament_id 
              GROUP BY t.id 
              ORDER BY 
                CASE t.status 
                    WHEN 'upcoming' THEN 1 
                    WHEN 'ongoing' THEN 2 
                    WHEN 'completed' THEN 3 
                END,
                t.start_date ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $_SESSION['user_id'] ?? 0]);
    $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Отладочная информация
    echo "<!-- Debug info: -->\n";
    echo "<!-- Number of tournaments: " . count($tournaments) . " -->\n";
    if (empty($tournaments)) {
        // Проверим наличие таблицы tournaments
        $tables = $pdo->query("SHOW TABLES LIKE 'tournaments'")->fetchAll();
        echo "<!-- Tables like 'tournaments': " . count($tables) . " -->\n";
        
        if (!empty($tables)) {
            // Проверим количество записей в таблице
            $count = $pdo->query("SELECT COUNT(*) as count FROM tournaments")->fetch();
            echo "<!-- Total records in tournaments: " . $count['count'] . " -->\n";
        }
    }
    
} catch (PDOException $e) {
    echo "<!-- Database error: " . htmlspecialchars($e->getMessage()) . " -->\n";
    $tournaments = [];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Турниры - Шахматный портал</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap">
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
            <h1><i class="fas fa-trophy"></i> Турниры</h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="create-tournament.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Создать турнир
                </a>
            <?php endif; ?>
        </div>

        <div class="tournaments-filters">
            <button class="btn btn-filter active" data-filter="all">Все турниры</button>
            <button class="btn btn-filter" data-filter="upcoming">Предстоящие</button>
            <button class="btn btn-filter" data-filter="ongoing">Текущие</button>
            <button class="btn btn-filter" data-filter="completed">Завершенные</button>
        </div>

        <?php if (empty($tournaments)): ?>
            <div class="no-tournaments">
                <i class="fas fa-info-circle"></i>
                <p>На данный момент нет доступных турниров.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <p>Вы можете создать новый турнир, нажав кнопку "Создать турнир".</p>
                <?php endif; ?>
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
                            </div>
                        </div>
                        <div class="tournament-actions">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($tournament['is_participant']): ?>
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check"></i> Вы участвуете
                                    </button>
                                <?php elseif ($tournament['status'] === 'upcoming' && $tournament['participants_count'] < $tournament['max_participants']): ?>
                                    <a href="join_tournament.php?id=<?php echo (int)$tournament['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Участвовать
                                    </a>
                                <?php endif; ?>
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

    <?php include INCLUDES_PATH . '/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.btn-filter');
            const tournamentCards = document.querySelectorAll('.tournament-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.dataset.filter;
                    
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    // Filter tournaments
                    tournamentCards.forEach(card => {
                        if (filter === 'all' || card.querySelector('.tournament-status').classList.contains(filter)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
