<?php
$page = $_GET['page'] ?? 'home'; // default page

$allowedPages = [
    'home' => 'views/home.php',
    'product' => 'views/product.php',
    'login' => 'views/login.php',
    'register' => 'views/register.php',
    'logout' => 'views/logout.php',
    'terms' => 'views/terms.php',
    'imprint' => 'views/imprint.php',
    'contact' => 'views/contact.php'
];
if (array_key_exists($page, $allowedPages)) {

    include $allowedPages[$page];

} else {
    include 'views/home.php';
}