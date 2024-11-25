<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Проверяем, есть ли ID урока
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: training.php");
    exit();
}

$lesson_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Получаем информацию об уроке
$query = "SELECT * FROM training_materials WHERE id = ?";
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$lesson_id]);
    $lesson = $stmt->fetch();
    
    if (!$lesson) {
        header("Location: training.php");
        exit();
    }
} catch(PDOException $e) {
    header("Location: training.php");
    exit();
}

// Получаем задачи для урока и прогресс пользователя
$query = "SELECT e.*, COALESCE(p.completed, 0) as completed 
          FROM lesson_exercises e 
          LEFT JOIN user_progress p ON e.id = p.exercise_id AND p.user_id = ? 
          WHERE e.lesson_id = ? 
          ORDER BY e.order_number ASC";
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $lesson_id]);
    $exercises = $stmt->fetchAll();
} catch(PDOException $e) {
    $exercises = [];
}

// Обработка AJAX запроса на сохранение прогресса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_progress') {
    header('Content-Type: application/json');
    
    if (!isset($_POST['exercise_id']) || !isset($_POST['completed'])) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }
    
    try {
        // Проверяем существует ли запись
        $query = "SELECT id FROM user_progress WHERE user_id = ? AND exercise_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id, $_POST['exercise_id']]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            // Обновляем существующую запись
            $query = "UPDATE user_progress SET completed = ?, completed_at = NOW() 
                     WHERE user_id = ? AND exercise_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$_POST['completed'], $user_id, $_POST['exercise_id']]);
        } else {
            // Создаем новую запись
            $query = "INSERT INTO user_progress (user_id, exercise_id, completed, completed_at) 
                     VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user_id, $_POST['exercise_id'], $_POST['completed']]);
        }
        
        echo json_encode(['success' => true]);
        exit;
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }
}

$current_page = 'training';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lesson['title']); ?> - Шахматный портал</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chessboard-js/1.0.0/chessboard-1.0.0.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.3/chess.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chessboard-js/1.0.0/chessboard-1.0.0.min.js"></script>
    <style>
    /* Добавляем стили для предзагрузки изображений */
    .chess-pieces-preload {
        position: absolute;
        left: -9999px;
        top: -9999px;
    }
    </style>
    <script>
    $(document).ready(function() {
        let board = null;
        let game = new Chess();
        let currentExercise = 0;

        // Получаем упражнения из PHP
        const exercises = <?php echo json_encode($exercises); ?>;

        // Функция сохранения прогресса
        function saveProgress(exerciseId, completed) {
            return $.ajax({
                url: 'lesson.php?id=<?php echo $lesson_id; ?>',
                method: 'POST',
                data: {
                    action: 'save_progress',
                    exercise_id: exerciseId,
                    completed: completed
                }
            });
        }

        function onDragStart(source, piece, position, orientation) {
            if (game.game_over()) return false;
            if ((game.turn() === 'w' && piece.search(/^b/) !== -1) ||
                (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
                return false;
            }
            return true;
        }

        function onDrop(source, target) {
            try {
                const move = game.move({
                    from: source,
                    to: target,
                    promotion: 'q'
                });

                if (move === null) return 'snapback';

                const exercise = exercises[currentExercise];
                if (exercise.solution === move.san) {
                    // Сохраняем прогресс при правильном ходе
                    saveProgress(exercise.id, 1).then(response => {
                        if (response.success) {
                            $('.exercise-status')
                                .removeClass('error')
                                .addClass('success')
                                .html('<i class="fas fa-check-circle"></i> Правильно! Задание выполнено.');
                            
                            // Обновляем статус в массиве упражнений
                            exercises[currentExercise].completed = 1;
                            
                            // Показываем кнопку следующего упражнения
                            $('.next-exercise').show();
                            
                            // Обновляем прогресс в интерфейсе
                            updateProgress();
                        }
                    });
                } else {
                    $('.exercise-status')
                        .removeClass('success')
                        .addClass('error')
                        .html('<i class="fas fa-times-circle"></i> Попробуйте другой ход.');
                    game.undo();
                    return 'snapback';
                }
            } catch (e) {
                console.error('Error making move:', e);
                return 'snapback';
            }
        }

        // Функция обновления прогресса на странице
        function updateProgress() {
            const completedExercises = exercises.filter(ex => ex.completed).length;
            const totalExercises = exercises.length;
            const progressPercent = Math.round((completedExercises / totalExercises) * 100);
            
            $('.progress-bar').css('width', progressPercent + '%');
            $('.progress-text').text(`Выполнено ${completedExercises} из ${totalExercises} заданий`);
            
            // Если все задания выполнены
            if (completedExercises === totalExercises) {
                $('.completion-message').show();
            }
        }

        function loadExercise(index) {
            if (index >= exercises.length) {
                $('.exercise-container').hide();
                $('.completion-message').show();
                return;
            }

            const exercise = exercises[index];
            currentExercise = index;
            
            game = new Chess();
            if (exercise.position) {
                game.load(exercise.position);
            }

            $('.exercise-title').text(exercise.title);
            $('.exercise-description').text(exercise.description);
            $('.hint-text').text(exercise.hint);
            $('.exercise-hint').hide();
            $('.exercise-status').removeClass('success error').empty();
            $('.next-exercise').toggle(exercise.completed === 1);
            $('.current-exercise').text(index + 1);
            $('.total-exercises').text(exercises.length);

            if (board) {
                board.position(exercise.position || 'start');
            }

            // Показываем статус выполнения
            if (exercise.completed) {
                $('.exercise-status')
                    .removeClass('error')
                    .addClass('success')
                    .html('<i class="fas fa-check-circle"></i> Задание уже выполнено');
            }
        }

        // Инициализация
        const config = {
            draggable: true,
            position: 'start',
            pieceTheme: 'https://chessboardjs.com/img/chesspieces/wikipedia/{piece}.png',
            onDragStart: onDragStart,
            onDrop: onDrop,
            onSnapEnd: () => board.position(game.fen())
        };

        board = Chessboard('board', config);
        $(window).resize(() => board.resize());

        // Обработчики кнопок
        $('.show-hint').click(() => $('.exercise-hint').slideToggle());
        $('.reset-exercise').click(() => loadExercise(currentExercise));
        $('.next-exercise').click(() => loadExercise(currentExercise + 1));
        $('.flip-board').click(() => board.flip());
        
        $('.tab-button').click(function() {
            const tab = $(this).data('tab');
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').hide();
            $(`#${tab}-content`).show();
            if (tab === 'practice' && board) {
                board.resize();
            }
        });

        // Загружаем первое упражнение и обновляем прогресс
        loadExercise(0);
        updateProgress();
    });
    </script>
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
    <main class="container">
        <div class="breadcrumbs">
            <a href="training.php">Тренировки</a>
            <i class="fas fa-chevron-right"></i>
            <span><?php echo htmlspecialchars($lesson['title']); ?></span>
        </div>

        <div class="lesson-content">
            <div class="lesson-tabs">
                <button class="tab-button active" data-tab="theory">Теория</button>
                <button class="tab-button" data-tab="practice">Практика</button>
            </div>
            
            <div class="tab-content" id="theory-content">
                <h1 class="lesson-title"><?php echo htmlspecialchars($lesson['title']); ?></h1>
                <div class="lesson-text">
                    <?php echo $lesson['content']; ?>
                </div>
                <button class="btn btn-primary start-practice">Начать практику</button>
            </div>
            
            <div class="tab-content" id="practice-content" style="display: none;">
                <div class="practice-container">
                    <div class="practice-header">
                        <h2>Практическое задание</h2>
                        <p class="practice-description">Выполните упражнения, чтобы закрепить изученный материал.</p>
                    </div>
                    
                    <div class="exercise-container">
                        <div class="board-container">
                            <div id="board" class="chess-board"></div>
                            <div class="board-controls">
                                <button class="btn btn-secondary flip-board">
                                    <i class="fas fa-sync-alt"></i> Перевернуть доску
                                </button>
                            </div>
                        </div>
                        <div class="exercise-info">
                            <div class="progress-indicator">
                                Упражнение <span class="current-exercise">1</span> из <span class="total-exercises"><?php echo count($exercises); ?></span>
                            </div>
                            <h3 class="exercise-title"></h3>
                            <p class="exercise-description"></p>
                            <div class="exercise-hint" style="display: none;">
                                <i class="fas fa-lightbulb"></i>
                                <span class="hint-text"></span>
                            </div>
                            <div class="exercise-controls">
                                <button class="btn btn-secondary show-hint">
                                    <i class="fas fa-lightbulb"></i> Показать подсказку
                                </button>
                                <button class="btn btn-secondary reset-exercise">
                                    <i class="fas fa-undo"></i> Начать заново
                                </button>
                                <button class="btn btn-primary next-exercise" style="display: none;">
                                    <i class="fas fa-arrow-right"></i> Следующее упражнение
                                </button>
                            </div>
                            <div class="exercise-status"></div>
                            <div class="completion-message" style="display: none;">
                                <h3>Поздравляем!</h3>
                                <p>Вы успешно завершили все упражнения этого урока.</p>
                                <button class="btn btn-primary return-to-theory">
                                    <i class="fas fa-book"></i> Вернуться к теории
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
