<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../DB_Connection/db_connection.php';
require_once __DIR__ . '/../Classes/Product/Product.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['product_type'], $data['product_id'], $data['comment'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$userID = $_SESSION['user_id'] ?? '';
if (!$userID) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to post a comment']);
    exit;
}

$product_table = '';
if ($data['product_type'] === 'books') $product_table = 'books_comments';
elseif ($data['product_type'] === 'movies') $product_table = 'movies_comments';
else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product type']);
    exit;
}

// Save comment
$commentInstance = new Product();
$result = $commentInstance->saveComment(
    $pdo,
    $product_table,
    (int)$data['product_id'],
    $userID,
    trim($data['comment']),
    $data['rating'] ?? null
);

echo json_encode([
    'status' => $result ? 'success' : 'error',
    'message' => $result ? 'Comment posted successfully.' : 'Failed to post comment.'
]);
exit;
