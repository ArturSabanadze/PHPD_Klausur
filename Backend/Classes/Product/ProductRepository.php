<?php


interface ProductRepository
{
    public function create(PDO $pdo, string $product_table_name, array $columns, array $keyValues): void;
    public function read(PDO $pdo, string $product_table_name): array;
    public function update(PDO $pdo, string $product_table_name, int $id, array $columns, array $keyValues): void;
    public function delete(PDO $pdo, string $product_table_name, int $id): void;
}