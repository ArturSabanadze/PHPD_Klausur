<?php


trait TraitProduct
{

    /**
     * Retrieves all product titles from a specific product table.
     *
     * @param PDO $pdo PDO database connection
     * @param string $product_table Table name (e.g., 'books', 'movies')
     * @return array List of product titles
     */
    public function getAllProductTitlesByType(PDO $pdo, string $product_table): array
    {
        $stmt = $pdo->prepare("SELECT title FROM $product_table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

}