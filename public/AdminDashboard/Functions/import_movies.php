<?php
require_once __DIR__ . '/../../../Backend/DB_Connection/db_connection.php';
require_once __DIR__ . '/../../../Backend/Classes/Product/Product.php';


function importMoviesFromJson(): void
{
    global $pdo;

    $file = "../../data/movies_library.json";

    if (!file_exists($file)) return;

    $response = json_decode(file_get_contents($file), true);

    if (!isset($response['movies']) || !is_array($response['movies'])) return;

    $movieModel = new Product();

    $columns = [
        'title', 'year', 'director', 'actors', 'rating', 'runtime',
        'genre', 'plot', 'thumbnail'
    ];

    foreach ($response['movies'] as $item) {
        $actors = isset($item['actors']) && is_array($item['actors']) ? implode(', ', $item['actors']) : null;
        $genre  = isset($item['genre']) && is_array($item['genre']) ? implode(', ', $item['genre']) : null;

        $keyValues = [
            'title'     => $item['title'] ?? 'Untitled',
            'year'      => $item['year'] ?? null,
            'director'  => $item['director'] ?? null,
            'actors'    => $actors,
            'rating'    => $item['rating'] ?? null,
            'runtime'   => $item['runtime'] ?? null,
            'genre'     => $genre,
            'plot'      => $item['plot'] ?? null,
            'thumbnail' => $item['thumbnail'] ?? null
        ];

        $movieModel->create($pdo, "movies", $columns, $keyValues);
    }
}


function importMoviesFromXml(): void
{
    global $pdo;

    $file = "../../data/movies_library.xml";

    if (!file_exists($file)) return;

    $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (!$xml || !isset($xml->movie)) return;

    $movieModel = new Product();

    $columns = [
        'title', 'year', 'director', 'actors', 'rating', 'runtime',
        'genre', 'plot', 'thumbnail'
    ];

    foreach ($xml->movie as $item) {
        $actors = isset($item->actors) ? array_map('strval', (array)$item->actors->actor) : [];
        $genre  = isset($item->genre)  ? array_map('strval', (array)$item->genre->item) : [];

        $keyValues = [
            'title'     => (string)($item->title ?? 'Untitled'),
            'year'      => isset($item->year) ? intval($item->year) : null,
            'director'  => (string)($item->director ?? null),
            'actors'    => !empty($actors) ? implode(', ', $actors) : null,
            'rating'    => isset($item->rating) ? floatval($item->rating) : null,
            'runtime'   => isset($item->runtime) ? intval($item->runtime) : null,
            'genre'     => !empty($genre) ? implode(', ', $genre) : null,
            'plot'      => (string)($item->plot ?? null),
            'thumbnail' => (string)($item->thumbnail ?? null)
        ];

        $movieModel->create($pdo, "movies", $columns, $keyValues);
    }
}
