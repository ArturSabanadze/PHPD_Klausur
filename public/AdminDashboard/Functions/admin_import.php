<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../Backend/DB_Connection/db_connection.php';  
require_once __DIR__ . '/import_books.php';
require_once __DIR__ . '/import_movies.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

$type = $_POST['type'] ?? '';

switch ($type) {
    case 'books_json':
        importBooksFromJson();
        echo 'Books imported successfully from JSON.';
        break;

    case 'movies_json':
        importMoviesFromJson();
        echo 'Movies imported successfully from JSON.';
        break;

    case 'books_xml':
        importBooksFromXml();
        echo 'Books imported successfully from XML.';
        break;
    case 'movies_xml':
        importMoviesFromXml();
        echo 'Movies imported successfully from XML.';
        break;

    default:
        http_response_code(400);
        echo 'Invalid import type.';
}
