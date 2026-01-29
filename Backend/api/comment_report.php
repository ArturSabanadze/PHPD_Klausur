<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/Product/Product.php';

$data = json_decode(file_get_contents('php://input'), true);

if (
    !$data ||
    empty($data['comment_id']) ||
    empty($data['product_type'])
) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in']);
    exit;
}

// Determine table
switch ($data['product_type']) {
    case 'books':
        $table = 'books_comments';
        break;
    case 'movies':
        $table = 'movies_comments';
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid product type']);
        exit;
}

// Flag comment
$product = new Product();
$result = $product->flagComment(
    $pdo,
    $table,
    (int)$data['comment_id']
);

echo json_encode([
    'status'  => $result ? 'success' : 'error',
    'message' => $result ? 'Comment reported successfully.' : 'Failed to report comment.'
]);
exit;
