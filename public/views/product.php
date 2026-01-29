<?php

require_once __DIR__ . '/../../Backend/api/products_api.php';
// Ensure we have a valid product to display
if (!$getProductById || empty($getProductById)) {
    echo "<p style=\"text-align: center; margin-top: 2rem; font-size: 1.25rem; color: red;\">Product not found.</p>";
    return;
}

$library = $_GET['library'] ?? '';
?>

<section class="product-single">
    <div class="card product-card product-single-card">

        <!-- Thumbnail -->
        <div class="product-image">
            <img src="<?= htmlspecialchars($getProductById['thumbnail'] ?? 'images/default-thumbnail.jpg') ?>"
                 alt="<?= htmlspecialchars($getProductById['title'] ?? 'No title') ?>">
        </div>

        <div class="product-content product-single-content">
            <h1 class="product-title">
                <?= htmlspecialchars($getProductById['title'] ?? 'Untitled') ?>
            </h1>

            <?php if ($library === 'movies'): ?>

                <!-- MOVIE FIELDS -->
                <p><strong>Genre:</strong> <?= htmlspecialchars($getProductById['genre'] ?? 'Unknown') ?></p>
                <p><strong>Year:</strong> <?= htmlspecialchars($getProductById['year'] ?? 'N/A') ?></p>
                <p><strong>Duration:</strong> <?= htmlspecialchars($getProductById['runtime'] ?? 'Unknown') ?></p>
                <p><strong>Director:</strong> <?= htmlspecialchars($getProductById['director'] ?? 'Unknown') ?></p>
                <p><strong>Actors:</strong> <?= htmlspecialchars($getProductById['actors'] ?? 'Unknown') ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($getProductById['plot'] ?? 'Unknown') ?></p>
                <p><strong>Rating:</strong> <?= htmlspecialchars($getProductById['rating'] ?? 'N/A') ?></p>

            <?php elseif ($library === 'books'): ?>

                <!-- BOOK FIELDS -->
                <p><strong>Author:</strong> <?= htmlspecialchars($getProductById['author'] ?? 'Unknown') ?></p>
                <p><strong>Publisher:</strong> <?= htmlspecialchars($getProductById['publisher'] ?? 'Unknown') ?></p>
                <p><strong>Pages:</strong> <?= htmlspecialchars($getProductById['page_count'] ?? 'N/A') ?></p>
                <p><strong>Published Date:</strong> <?= htmlspecialchars($getProductById['published_date'] ?? 'N/A') ?></p>
                <p><strong>Publisher:</strong> <?= htmlspecialchars($getProductById['publisher'] ?? 'N/A') ?></p>
                <p><strong>Language:</strong> <?= htmlspecialchars($getProductById['language'] ?? 'Unknown') ?></p>
                <p><strong>Price:</strong> <?= htmlspecialchars($getProductById['price'] ?? 'Free') ?></p>
                

            <?php endif; ?>

            <!-- Shared Description -->
            <p class="product-description">
                <?= htmlspecialchars($getProductById['plot'] ?? $getProductById['description'] ?? 'No description available.') ?>
            </p>

            <!-- Actions -->
            <div class="product-actions">
                <a href="<?= $library === 'movies'
                    ? 'movies.php?library=movies'
                    : 'books.php?library=books' ?>"
                   class="btn btn-secondary">
                    Back to <?= ucfirst($library) ?>
                </a>

                <?php if ($library === 'movies' && !empty($getProductById['preview_link'])): ?>
                    <a href="<?= htmlspecialchars($getProductById['preview_link']) ?>"
                       class="btn btn-primary"
                       target="_blank">
                        Watch Trailer
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<section>
    <?php include __DIR__ . '/../components/comments.php'; ?>
</section>

