<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Unauthorized');
}

require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/User/User.php';

$user_data = [
        'username' => trim($_POST['username'] ?? ''),
        'plain_password' => $_POST['password'] ?? '',
        'email' => trim($_POST['email'] ?? '')
    ];
$userInstance = new User($user_data);

$allUsers = [];


if (!isset($pdo) || !$pdo instanceof PDO) {
    die("Database connection is not initialized.");
}

try {

    $allUsers = $userInstance->getAllUsers($pdo);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}