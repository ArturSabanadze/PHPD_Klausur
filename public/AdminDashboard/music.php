<h2>Product Management -
    <?= ucfirst($_GET['type'] ?? 'Physical') ?> Products
</h2>
<!-- Include the product CRUD action handler -->
<?php
if (!isset($_GET['type'])) {
    $_GET['type'] = 'Physical';
}
include_once __DIR__ . '/action_handlers/product_crud.php';
?>