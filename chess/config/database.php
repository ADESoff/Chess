<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'chess_portal');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Проверка подключения
    echo "<!-- Database connection successful -->\n";
} catch(PDOException $e) {
    // Выводим ошибку в HTML-комментарий для отладки
    echo "<!-- Database connection failed: " . $e->getMessage() . " -->\n";
    die("Connection failed: " . $e->getMessage());
}
?>
