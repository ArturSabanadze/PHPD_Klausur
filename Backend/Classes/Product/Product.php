<?php

require_once __DIR__ . '/ProductRepository.php';
require_once __DIR__ . '/TraitProduct.php';

/**
 * Product Class
 * 
 * Data access layer for product entities.
 * Provides CRUD operations, fetching product titles, and comment management.
 * This class is meant to work with a PDO connection and multiple product tables.
 * 
 * @package Backend\Classes\Product
 * @author  Artur Sabanadze
 * @version 1.0.1
 */

class Product implements ProductRepository
{
    use TraitProduct;

    /* ---------- CREATE ---------- */

    /**
     * Creates a new product record.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table_name Table to insert into
     * @param array $columns Columns to insert
     * @param array $keyValues Associative array of column => value
     * @return void
     */
    public function create(PDO $pdo, string $product_table_name, array $columns, array $keyValues): void
    {
        $columnsSql = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $stmt = $pdo->prepare("
            INSERT IGNORE INTO $product_table_name ($columnsSql)
            VALUES ($placeholders)
        ");

        $stmt->execute($keyValues);
    }

    /* ---------- READ ---------- */

    /**
     * Retrieves all products from a product table.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table_name Table name
     * @return array Associative array of products
     */
    public function read(PDO $pdo, string $product_table_name): array
    {
        $stmt = $pdo->prepare("SELECT * FROM $product_table_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a single product by ID.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table_name Table name
     * @param int $id Product ID
     * @return array|false Product data or false if not found
     */
    public function getById(PDO $pdo, string $product_table_name, int|string $id): array|false
{
    $result = [];
    if (is_numeric($id)) {
    
    // First try by id
    $stmt = $pdo->prepare("SELECT * FROM {$product_table_name} WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else {
        // If not found, try by google_id
    $stmt = $pdo->prepare("SELECT * FROM {$product_table_name} WHERE google_id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } 
    return $result;
}


    /* ---------- UPDATE ---------- */

    /**
     * Updates a product record.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table_name Table name
     * @param int $id Product ID
     * @param array $columns Columns to update
     * @param array $keyValues Column => new value
     * @return void
     */
    public function update(PDO $pdo, string $product_table_name, int $id, array $columns, array $keyValues): void
    {
        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", $columns));
        $keyValues['id'] = $id;

        $stmt = $pdo->prepare("
            UPDATE $product_table_name
            SET $setClause
            WHERE id = :id
        ");

        $stmt->execute($keyValues);
    }

    /* ---------- DELETE ---------- */
    public function delete(PDO $pdo, string $product_table_name, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM $product_table_name WHERE id = ?");
        $stmt->execute([$id]);
    }   

    /* ---------- COMMENTS ---------- */

    /**
     * Retrieves comments for a product.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table_name Table name for comments
     * @param int $productId Product ID
     * @return array List of comments with usernames
     */
    public function getComments(PDO $pdo, string $product_table_name, int $productId): array
    {
        $stmt = $pdo->prepare("
            SELECT mc.*, u.username
            FROM $product_table_name mc
            JOIN users u ON mc.user_id = u.id
            WHERE product_id = ? AND mc.hidden = 0
            ORDER BY created_at DESC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    /**
     * Saves a comment for a product.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table_name Table name for comments
     * @param int $productId Product ID
     * @param int $userId User ID
     * @param string $comment Comment text
     * @param int|null $rating Optional rating
     * @return bool Success/failure
     */
    public function saveComment(PDO $pdo, string $product_table_name, int $productId, int $userId, string $comment, ?int $rating): bool
    {
        $stmt = $pdo->prepare("
            INSERT INTO $product_table_name (product_id, user_id, comment, rating)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$productId, $userId, $comment, $rating]);
    }

    public function getAllFlaggedComments(PDO $pdo): array
    {
        $stmt = $pdo->prepare("
            SELECT mc.id AS comment_id, 'movies' AS type, mc.product_id, mc.comment, mc.rating, mc.reported, mc.hidden, mc.created_at, u.username, m.title
            FROM movies_comments mc
            JOIN users u ON mc.user_id = u.id
            JOIN movies m ON mc.product_id = m.id
            WHERE mc.reported = 1

            UNION ALL

            SELECT bc.id AS comment_id, 'books' AS type, bc.product_id, bc.comment, bc.rating, bc.reported, bc.hidden, bc.created_at, u.username, b.title
            FROM books_comments bc
            JOIN users u ON bc.user_id = u.id
            JOIN books b ON bc.product_id = b.id
            WHERE bc.reported = 1

            ORDER BY comment_id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function unflagComment(PDO $pdo, string $product_table_name, int $commentId): bool
    {
        $stmt = $pdo->prepare("
            UPDATE " . $product_table_name . "_comments
            SET reported = 0
            WHERE id = ?
        ");
        return $stmt->execute([$commentId]);
    }
    public function hideComment(PDO $pdo, string $product_table_name, int $commentId): bool
    {
        $stmt = $pdo->prepare("
            UPDATE " . $product_table_name . "_comments
            SET hidden = 1
            WHERE id = ?
        ");
        return $stmt->execute([$commentId]);
    }

    public function flagComment(PDO $pdo, string $product_table_name, int $commentId): bool
    {
        $stmt = $pdo->prepare("
            UPDATE $product_table_name
            SET reported = 1
            WHERE id = ?
        ");
        return $stmt->execute([$commentId]);
    }

}
