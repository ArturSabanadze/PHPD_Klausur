<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/Admin/Admin.php';

$adminInstance = new Admin();

$contactMessages = [];

try {
    $contactMessages = $adminInstance->getAllContactMessages($pdo);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
