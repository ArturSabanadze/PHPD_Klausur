<?php

function loadBooksFromJson(): array
{
    $filePath = __DIR__ . '/../data/books_programming.json';
    
    if (!file_exists($filePath)) {
        return [];
    }

    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    if (!isset($data['items']) || !is_array($data['items'])) {
        return [];
    }

    $books = [];

    foreach ($data['items'] as $item) {
        $volumeInfo = $item['volumeInfo'] ?? [];
        $saleInfo   = $item['saleInfo'] ?? [];

        $books[] = [
            'id'        => $item['id'] ?? null,
            'title'     => $volumeInfo['title'] ?? 'Untitled',
            'author'    => isset($volumeInfo['authors'])
                            ? implode(', ', $volumeInfo['authors'])
                            : 'Unknown',
            'category'  => $volumeInfo['categories'][0] ?? 'Uncategorized',
            'thumbnail' => $volumeInfo['imageLinks']['thumbnail']
                            ?? $volumeInfo['imageLinks']['smallThumbnail']
                            ?? 'images/default-thumbnail.jpg',
            'price'     => $saleInfo['retailPrice']['amount'] ?? null,
            'currency'  => $saleInfo['retailPrice']['currencyCode'] ?? null,
            'rating'    => $volumeInfo['averageRating'] ?? null,
            'pageCount' => $volumeInfo['pageCount'] ?? null,
            'published' => $volumeInfo['publishedDate'] ?? null,
            'preview'   => $volumeInfo['previewLink'] ?? null,
        ];
    }

    return $books;
}

function loadMoviesFromJson(): array
{
    $filePath = __DIR__ . '/../data/movies_library.json';

    if (!file_exists($filePath)) {
        return [];
    }

    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    if (!isset($data['movies']) || !is_array($data['movies'])) {
        return [];
    }

    $movies = [];

    foreach ($data['movies'] as $movie) {
        $movies[] = [
            'id'        => $movie['id'] ?? null,
            'title'     => $movie['title'] ?? 'Untitled',
            'year'      => $movie['year'] ?? null,
            'director'  => $movie['director'] ?? 'Unknown',
            'actors'    => isset($movie['actors'])
                            ? implode(', ', $movie['actors'])
                            : 'Unknown',
            'genre'     => isset($movie['genre'])
                            ? implode(', ', $movie['genre'])
                            : 'Uncategorized',
            'runtime'   => $movie['runtime'] ?? null,
            'rating'    => $movie['rating'] ?? null,
            'plot'      => $movie['plot'] ?? null,
            'thumbnail' => $movie['thumbnail'] ?? 'images/default-thumbnail.jpg',
        ];
    }

    return $movies;
}

function loadBooksFromXml(): array
{
    $filePath = __DIR__ . '/../data/books_programming.xml';
    if (!file_exists($filePath)) {
        return [];
    }

    $xml = simplexml_load_file($filePath, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (!$xml || !isset($xml->book)) {
        return [];
    }

    $books = [];

    foreach ($xml->book as $item) {
        $books[] = [
            'id'        => (string)($item->id ?? null),
            'title'     => (string)($item->title ?? 'Untitled'),
            'author'    => isset($item->authors) ? implode(', ', array_map('strval', (array)$item->authors->author)) : 'Unknown',
            'category'  => (string)($item->category ?? 'Uncategorized'),
            'thumbnail' => (string)($item->thumbnail ?? 'images/default-thumbnail.jpg'),
            'price'     => isset($item->price) ? floatval($item->price) : null,
            'currency'  => (string)($item->currency ?? null),
            'rating'    => isset($item->rating) ? floatval($item->rating) : null,
            'pageCount' => isset($item->pageCount) ? intval($item->pageCount) : null,
            'published' => (string)($item->published ?? null),
            'preview'   => (string)($item->preview ?? null),
        ];
    }

    return $books;
}

function loadMoviesFromXml(): array
{
    $filePath = __DIR__ . '/../data/movies_library.xml';
    if (!file_exists($filePath)) {
        return [];
    }

    $xml = simplexml_load_file($filePath, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (!$xml || !isset($xml->movie)) {
        return [];
    }

    $movies = [];

    foreach ($xml->movie as $movie) {
        $movies[] = [
            'id'        => (string)($movie->id ?? null),
            'title'     => (string)($movie->title ?? 'Untitled'),
            'year'      => (string)($movie->year ?? null),
            'director'  => (string)($movie->director ?? 'Unknown'),
            'actors'    => isset($movie->actors) ? implode(', ', array_map('strval', (array)$movie->actors->actor)) : 'Unknown',
            'genre'     => isset($movie->genre) ? implode(', ', array_map('strval', (array)$movie->genre->item)) : 'Uncategorized',
            'runtime'   => (string)($movie->runtime ?? null),
            'rating'    => isset($movie->rating) ? floatval($movie->rating) : null,
            'plot'      => (string)($movie->plot ?? null),
            'thumbnail' => (string)($movie->thumbnail ?? 'images/default-thumbnail.jpg'),
        ];
    }

    return $movies;
}
