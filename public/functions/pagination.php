<?php
function paginate($items, $perPage = 4) {
    $totalItems = count($items);
    $totalPages = ceil($totalItems / $perPage);

    $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, min($currentPage, $totalPages));

    $offset = ($currentPage - 1) * $perPage;
    $pageItems = array_slice($items, $offset, $perPage);

    return [
        'items' => $pageItems,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage
    ];
}