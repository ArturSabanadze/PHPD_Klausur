<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/Product/Product.php';

$productInstance = new Product();
$library = $_GET['library'] ?? 'books';
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

$product_comments = null;
if ($library === 'books') {
    $product_comments = 'books_comments';
} elseif ($library === 'movies') {
    $product_comments = 'movies_comments';
}

$books = [];
$movies = [];
$getProductById = null;
$currentProductComments = [];
$productTitles = [];
$flaggedComments = new Product()->getAllFlaggedComments($pdo);

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("Database connection is not initialized.");
}

try {
    $books = $productInstance->read($pdo, 'books');
    $movies = $productInstance->read($pdo, 'movies');
   
    $productTitles = new Product()->getAllProductTitlesByType($pdo, $library) ?? [];

    if ($library !== null && $productId > 0) {
        $getProductById = $productInstance->getById($pdo, $library, $productId);
        if ($product_comments) {
            $currentProductComments = $productInstance->getComments($pdo, $product_comments, $productId);
        }
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
