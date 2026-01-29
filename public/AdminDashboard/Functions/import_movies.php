<?php
require_once __DIR__ . '/../../../Backend/DB_Connection/db_connection.php';
require_once __DIR__ . '/../../../Backend/Classes/Product/Product.php';


// Import movies from a JSON file (TMDB format) into the database
function importMoviesFromJson(): void
{
    global $pdo;
    
    // TMDB API
    $file = "../../data/movies_library.json";

    $response = json_decode(file_get_contents($file), true);

    if (!isset($response['movies']) || !is_array($response['movies'])) {
        return; // No movies returned
    }

    $movieModel = new Product();

    $columns = [
        'title', 'year', 'director', 'actors', 'rating', 'runtime',
        'genre', 'plot', 'thumbnail'
    ];
    
    foreach ($response['movies'] as $item) {
        $actors = isset($item['actors']) && is_array($item['actors']) ? implode(', ', $item['actors']) : null;
        $genre  = isset($item['genre']) && is_array($item['genre']) ? implode(', ', $item['genre']) : null;


        $keyValues = [
            'title'    => $item['title'] ?? 'Untitled',
            'year'     => $item['year'] ?? null,
            'director' => $item['director'] ?? null,
            'actors'   => $actors,
            'rating'   => $item['rating'] ?? null,
            'runtime'  => $item['runtime'] ?? null,
            'genre'    => $genre,
            'plot'     => $item['plot'] ?? null,
            'thumbnail'=> $item['thumbnail'] ?? null
        ];

        $movieModel->create($pdo, "movies", $columns, $keyValues);
        }
}
