<h2>Product Management - Movies</h2>

<?php
require_once __DIR__ . '/../../Backend/api/products_api.php'; // Provides $movies as associative array

if (empty($movies)) {
    echo "<p>No movies found. <a href=\"admin_dashboard.php\">import library</a></p>";
} else {
    ?>
    <section class="movies-list">
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Director</th>
                    <th>Actors</th>
                    <th>Rating</th>
                    <th>Runtime</th>
                    <th>Genre</th>
                    <th>Plot</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?= htmlspecialchars($movie['id']) ?></td>
                        <td>
                            <?php if (!empty($movie['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($movie['thumbnail']) ?>" alt="Thumbnail" style="width:90px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($movie['title']) ?></td>
                        <td><?= htmlspecialchars($movie['year'] ?? '') ?></td>
                        <td><?= htmlspecialchars($movie['director'] ?? '') ?></td>
                        <td><?= htmlspecialchars($movie['actors'] ?? '') ?></td>
                        <td><?= htmlspecialchars($movie['rating'] ?? '') ?></td>
                        <td><?= htmlspecialchars($movie['runtime'] ?? '') ?></td>
                        <td><?= htmlspecialchars($movie['genre'] ?? '') ?></td>
                        <td><div class="td-clamp"><?= htmlspecialchars($movie['plot'] ?? '') ?></div></td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php
}
?>
