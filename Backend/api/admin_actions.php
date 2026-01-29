<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo ('Unauthorized');
    exit();
}

require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/User/User.php';
require_once __DIR__ . '/../Classes/Product/Product.php';

$userInstance = new User();
$product = new Product();
$action = $_POST['action'] ?? '';


if (!isset($pdo) || !$pdo instanceof PDO) {
    echo ("Database connection is not initialized.");
    exit();
}

switch ($action) {
    case 'hide_comment':
        $type = $_POST['type'] ?? '';
        $id   = (int)($_POST['id'] ?? 0);

        if (!$type || !$id) {
            http_response_code(400);
            echo 'Invalid parameters';
            exit;
        }

        $product->hideComment($pdo, $type, $id);
        echo 'Comment hidden successfully.';
        exit();

    case 'ban_user':
        $username = $_POST['username'] ?? '';
        if (!$username) {
            http_response_code(400);
            echo 'Invalid parameters';
            exit;
        }
        
        $userInstance->banUser($pdo, $username);
        echo 'User banned successfully.';
        exit();

    default:
        http_response_code(400);
        echo 'Invalid action.';
        exit();
}
