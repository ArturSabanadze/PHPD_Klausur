<?php

// Prevent redefinition
if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo'] instanceof PDO) {

    $host   = $env['DB_HOST'] ?? 'localhost';
    $dbname = $env['DB_NAME'] ?? 'media_library';
    $user   = $env['DB_USER'] ?? 'root';
    $pass   = $env['DB_PASS'] ?? '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        $GLOBALS['pdo'] = new PDO(
            $dsn,
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        die("DB-Verbindung fehlgeschlagen: " . $e->getMessage());
    }
}

// Always expose $pdo
$pdo = $GLOBALS['pdo'];
