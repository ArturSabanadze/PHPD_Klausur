<?php
require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/Product/Product.php';

header('Content-Type: application/json');

$library = $_GET['library'] ?? 'books';
$allowed = ['books', 'movies'];
$library = in_array($library, $allowed, true) ? $library : 'books';

$product = new Product();
$titles = $product->getAllProductTitlesByType($pdo, $library);

echo json_encode([
    'titles' => $titles
]);
exit;
