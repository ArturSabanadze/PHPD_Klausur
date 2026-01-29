<?php
// Make sure session cookie is available for all paths
session_set_cookie_params([
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
header('Content-Type: application/json');

echo json_encode([
    'username' => $_SESSION['username'] ?? null
]);
