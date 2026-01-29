<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../Backend/DB_Connection/db_connection.php';
require_once __DIR__ . '/../../Backend/Classes/Product/Product.php';

$library = $_GET['library'] ?? 'books';
$allowedLibraries = ['books', 'movies'];

if (!in_array($library, $allowedLibraries, true)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid library type'
    ]);
    exit;
}

if (!isset($pdo) || !$pdo instanceof PDO) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed'
    ]);
    exit;
}

try {
    $product = new Product();
    $titles = $product->getAllProductTitlesByType($pdo, $library);

    echo json_encode([
        'titles' => $titles
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error'
    ]);
}
