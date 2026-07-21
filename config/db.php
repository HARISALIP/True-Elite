<?php
// config/db.php
$host = 'localhost';
$dbname = 'true_elite_erp';
$username = 'root';
$password = ''; // Update if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Return standard JSON error for AJAX requests if DB fails
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
    // Otherwise standard error
    die("Database connection failed: " . $e->getMessage() . ". Please ensure MySQL is running, the database 'true_elite_erp' exists, and credentials are correct in config/db.php.");
}
