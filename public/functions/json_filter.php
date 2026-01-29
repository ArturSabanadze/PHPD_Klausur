<?php

 function filterBooks($search = false, $category = false, $best_rate = false, $price_asc = false, $price_desc = false): array
    {
        require_once __DIR__ . '/../../Backend/api/products_api.php'; // Provide $product data = all products from DB

        //fuzzy search by title and / or author
        if ($search) {
        $search = strtolower(trim($search));
        $words  = preg_split('/\s+/', $search);

        $books = array_values(array_filter($books, function ($book) use ($words) {
            $title  = strtolower($book['title'] ?? '');
            $author = strtolower($book['author'] ?? '');

            foreach ($words as $word) {
                if ($word === '') continue;

                // Search field is Case-insensitive and allow partial key word match 45%
                if (stripos($title, $word) !== false || stripos($author, $word) !== false) {
                    return true;
                }

                // Fuzzy typo tolerance
                similar_text($title, $word, $tp);
                similar_text($author, $word, $ap);

                if ($tp > 90 || $ap > 90) {
                    return true;
                }
            }

            return false;
        }));
    }


        // Filter by category name: string match 100%
        if ($category) {
            $books = array_filter($books, function ($book) use ($category) {
                return stripos($book['category'] ?? '', $category) !== false;

                });
        }

        // Sorting all product rates and return biggest rates first
        if ($best_rate) {
            usort($books, function ($a, $b) {
                return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);

            });
        }
        // Sorting all product prices in ascending order
        if ($price_asc) {
    usort($books, function ($a, $b) {
        $priceA = floatval($a['price'] ?? 0);
        $priceB = floatval($b['price'] ?? 0);
        return $priceA <=> $priceB;
    });
    }

    // Sorting all product prices in descending order
       if ($price_desc) {
           usort($books, function ($a, $b) {
               $priceA = floatval($a['price'] ?? 0);
               $priceB = floatval($b['price'] ?? 0);
               return $priceB <=> $priceA;
           });
       }
               // If no search criteraia provided, return all books
               return $books ?? [];
} 

function filterMovies($search = false, $category = false, $best_rate = false, $price_asc = false, $price_desc = false): array
    {
        require_once __DIR__ . '/../../Backend/api/products_api.php'; // Provide $product data = all products from DB

        //fuzzy search by title and / or author
        if ($search) {
        $search = strtolower(trim($search));
        $words  = preg_split('/\s+/', $search);

        $movies = array_values(array_filter($movies, function ($movie) use ($words) {
            $title  = strtolower($movie['title'] ?? '');
            $director = strtolower($movie['director'] ?? '');

            foreach ($words as $word) {
                if ($word === '') continue;

                // Search field is Case-insensitive and allow partial key word match 45%
                if (stripos($title, $word) !== false || stripos($director, $word) !== false) {
                    return true;
                }

                // Fuzzy typo tolerance
                similar_text($title, $word, $tp);
                similar_text($director, $word, $ap);

                if ($tp > 55 || $ap > 55) {
                    return true;
                }
            }

            return false;
        }));
    }


        // Filter by category name: string match 100%
        if ($category) {
            $movies = array_filter($movies, function ($movie) use ($category) {
                return stripos($movie['genre'] ?? '', $category) !== false;

                });
        }

        // Sorting all product rates and return biggest rates first
        if ($best_rate) {
            usort($movies, function ($a, $b) {
                return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);

            });
        }
        // Sorting all product prices in ascending order
        if ($price_asc) {
    usort($movies, function ($a, $b) {
        $priceA = floatval($a['price'] ?? 0);
        $priceB = floatval($b['price'] ?? 0);
        return $priceA <=> $priceB;
    });
    }

    // Sorting all product prices in descending order
       if ($price_desc) {
           usort($movies, function ($a, $b) {
               $priceA = floatval($a['price'] ?? 0);
               $priceB = floatval($b['price'] ?? 0);
               return $priceB <=> $priceA;
           });
       }
               // If no search criteraia provided, return all movies
               return $movies ?? [];
}
