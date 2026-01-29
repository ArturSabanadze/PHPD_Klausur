<?php

trait TraitBook
{

public function importBooksFromGoogle(): void
{
    $url = "https://www.googleapis.com/books/v1/volumes?q=flowers+inauthor:keyes&key=AIzaSyA1ooRY220IOtLUERJns3gY7p79tn7j2Bw";

    $response = json_decode(file_get_contents($url), true);

    if (!isset($response['items'])) {
        return;
    }

    $bookModel = new Book();

    foreach ($response['items'] as $item) {
        $info = $item['volumeInfo'];
        $sale = $item['saleInfo'] ?? [];

        $bookModel->create([
            'google_id'      => $item['id'],
            'title'          => $info['title'] ?? 'Untitled',
            'subtitle'       => $info['subtitle'] ?? null,
            'author'         => $info['authors'][0] ?? null,
            'description'    => $info['description'] ?? null,
            'thumbnail'      => $info['imageLinks']['thumbnail'] ?? null,
            'publisher'      => $info['publisher'] ?? null,
            'published_date' => $info['publishedDate'] ?? null,
            'language'       => $info['language'] ?? null,
            'page_count'     => $info['pageCount'] ?? null,
            'category'       => $info['categories'][0] ?? null,
            'price'          => $sale['retailPrice']['amount'] ?? null,
            'currency'       => $sale['retailPrice']['currencyCode'] ?? null,
            'saleability'    => $sale['saleability'] ?? null
        ]);
    }
}

}