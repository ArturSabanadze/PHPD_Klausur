<h2>Product Management - Books</h2>

<?php
require_once __DIR__ . '/../../Backend/api/products_api.php'; // Provides $books as associative array

if (empty($books)) {
    echo "<p>No books found.</p>";
} else {
    ?>
    <section class="books-list">
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse; font-size: 0.8rem;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Thumbnail</th>
                    <th>Link</th>
                    <th>Publisher</th>
                    <th>Published Date</th>
                    <th>Lang.</th>
                    <th>Page Count</th>
                    <th>Category</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['id']) ?></td>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author'] ?? '') ?></td>
                        <td><?= htmlspecialchars($book['description'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($book['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($book['thumbnail']) ?>" alt="Thumbnail" style="width:90px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($book['preview_link'])): ?>
                                <a href="<?= htmlspecialchars($book['preview_link']) ?>" target="_blank">Preview</a>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($book['publisher'] ?? '') ?></td>
                        <td><?= htmlspecialchars($book['published_date'] ?? '') ?></td>
                        <td><?= htmlspecialchars($book['language'] ?? '') ?></td>
                        <td><?= htmlspecialchars($book['page_count'] ?? '') ?></td>
                        <td><?= htmlspecialchars($book['category'] ?? '') ?></td>
                        <td><?= htmlspecialchars(($book['price'] ?? '') . ($book['currency'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php
}
?>
