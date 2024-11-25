<?php
require_once 'config/database.php';

try {
    // Add missing columns if they don't exist
    $pdo->exec("ALTER TABLE tournaments 
                ADD COLUMN IF NOT EXISTS prize_pool DECIMAL(10,2) DEFAULT 0.00 AFTER status,
                ADD COLUMN IF NOT EXISTS max_participants INT DEFAULT 0 AFTER prize_pool");
    
    echo "Table structure updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
