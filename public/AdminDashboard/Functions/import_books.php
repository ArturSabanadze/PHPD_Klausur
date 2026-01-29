<?php
require_once __DIR__ . '/../../../Backend/DB_Connection/db_connection.php';
require_once __DIR__ . '/../../../Backend/Classes/Product/Product.php';


// Import books from a JSON file (Google Books API format) into the database
function importBooksFromJson(): void
{
    global $pdo;
    
    // Google Books API
    $file = "../../data/books_programming.json";

    $response = json_decode(file_get_contents($file), true);

    if (!isset($response['items']) || !is_array($response['items'])) {
        return; // No books returned
    }

    $bookModel = new Product();

    $columns = [
        'google_id', 'title', 'subtitle', 'author', 'description', 'thumbnail', 'preview_link',
        'publisher', 'published_date', 'language', 'page_count', 'category',
        'price', 'currency', 'saleability'
    ];
    
    foreach ($response['items'] as $item) {
        $info = $item['volumeInfo'] ?? [];
        $sale = $item['saleInfo'] ?? [];

        $keyValues = [
            'google_id'      => $item['id'] ?? null,
            'title'          => $info['title'] ?? 'Untitled',
            'subtitle'       => $info['subtitle'] ?? null,
            'author'         => $info['authors'][0] ?? null,
            'description'    => $info['description'] ?? null,
            'thumbnail'      => $info['imageLinks']['thumbnail'] ?? null,
            'preview_link'    => $info['previewLink'] ?? null,
            'publisher'      => $info['publisher'] ?? null,
            'published_date' => $info['publishedDate'] ?? null,
            'language'       => $info['language'] ?? null,
            'page_count'     => $info['pageCount'] ?? null,
            'category'       => $info['categories'][0] ?? null,
            'price'          => $sale['retailPrice']['amount'] ?? null,
            'currency'       => $sale['retailPrice']['currencyCode'] ?? null,
            'saleability'    => $sale['saleability'] ?? null
        ];

        $bookModel->create($pdo, "books", $columns, $keyValues);
    }
}
