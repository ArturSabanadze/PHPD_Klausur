<?php

require_once __DIR__ . '/../functions/json_filter.php';
require_once __DIR__ . '/../functions/data_parser.php';
require_once __DIR__ . '/../functions/pagination.php';

$availableLibraries = ['No library', 'books', 'movies'];
if (isset($_GET['library']) && in_array($_GET['library'], $availableLibraries, true)) {
    $_SESSION['library'] = $_GET['library'];
}

if (!isset($_GET['library']) && isset($_SESSION['library'])) {
    header('Location: ?library=' . urlencode($_SESSION['library']));
    exit;
}

$selectedLibrary = $_SESSION['library'] ?? '';

/* Preserve query parameters */
$queryParams = $_GET;
unset($queryParams['page']);
$baseQuery = http_build_query($queryParams);
$baseQuery = $baseQuery ? $baseQuery . '&' : '';
?>

<section class="container">

<!-- Library Selector + Filter Toggle -->
<div class="filters-bar" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
    <!-- Library Selector -->
    <div class="library-selector-wrapper">
        <form method="GET" class="library-selector" style="display: inline-block; margin: 0;">
            <label for="library">Select Library:</label>
            <select name="library" id="library" onchange="this.form.submit()">
                <?php foreach ($availableLibraries as $lib): ?>
                    <option value="<?= htmlspecialchars($lib) ?>" <?= $selectedLibrary === $lib ? 'selected' : '' ?>>
                        <?= ucfirst($lib) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Filters Toggle Button -->
    <?php if ($selectedLibrary && $selectedLibrary !== 'No library'): ?>
        <button class="accordion-toggle" type="button" onclick="toggleFilters()">
            Filters
        </button>
    <?php endif; ?>
</div>

<div id="filtersAccordion" class="filters-accordion">

<?php if ($selectedLibrary && $selectedLibrary !== 'No library'): ?>

<?php
// Normalize GET parameters for filters
$search     = !empty($_GET['search']) ? $_GET['search'] : false;
$category   = !empty($_GET['category']) ? $_GET['category'] : false;
$best_rate  = isset($_GET['filter_by']) && $_GET['filter_by'] === 'best_rate';
$price_asc  = isset($_GET['filter_by']) && $_GET['filter_by'] === 'price_asc';
$price_desc = isset($_GET['filter_by']) && $_GET['filter_by'] === 'price_desc';

// Filter data per library (USING DATABASE)
$libraryData = [];
if ($selectedLibrary === 'books' && ($search || $category || $best_rate || $price_asc || $price_desc)) {
    $libraryData = filterBooks($search, $category, $best_rate, $price_asc, $price_desc);
} elseif ($selectedLibrary === 'movies' && ($search || $category || $best_rate || $price_asc || $price_desc)) {
    $libraryData = filterMovies($search, $category, $best_rate, $price_asc, $price_desc);
} 
// Load all data per library (FROM JSON)
elseif ($selectedLibrary === 'books') {
    $libraryData = loadBooksFromJson();
} elseif ($selectedLibrary === 'movies') {
    $libraryData = loadMoviesFromJson();
}

$pagination = paginate($libraryData);
?>

<!-- Filter Bar -->
<form method="GET" class="product-filter-bar">
    <input type="hidden" name="library" value="<?= htmlspecialchars($selectedLibrary) ?>">

    <?php if (!empty($search)): ?>
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
    <?php endif; ?>

    <div class="filters">
        <!-- Category Dropdown -->
        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="">-- All Categories --</option>
            <!-- Example: populate dynamically if needed -->
        </select>

        <!-- Sorting -->
        <label for="filter_by">Filter by:</label>
        <select name="filter_by" id="filter_by" onchange="this.form.submit()">
            <option value="">-- No Sorting --</option>
            <option value="best_rate" <?= $best_rate ? 'selected' : '' ?>>Rate</option>
            <option value="price_asc" <?= $price_asc ? 'selected' : '' ?>>Price Low → High</option>
            <option value="price_desc" <?= $price_desc ? 'selected' : '' ?>>Price High → Low</option>
        </select>

        <button type="submit" class="btn">Apply Filters</button>
        <button type="button" class="btn clear-filters-btn"
            onclick="window.location.href='?library=<?= htmlspecialchars($selectedLibrary) ?>'">
            Clear Filters
        </button>
    </div>
</form>
</div>

<!-- Products Grid -->
<?php if (empty($libraryData)): ?>
    <div class="card text-center">
        <p>No <?= htmlspecialchars($selectedLibrary) ?> available at the moment.</p>
    </div>
<?php else: ?>
    <div class="product-grid">
        <?php foreach ($pagination['items'] as $item): ?>
            <!-- Product Card -->
            <article class="card product-card">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($item['thumbnail'] ?? 'images/default-thumbnail.jpg') ?>"
                         alt="<?= htmlspecialchars($item['title'] ?? 'No title') ?>" loading="lazy">
                </div>
                <div class="product-content">
                    <h3 class="product-title"><?= htmlspecialchars($item['title'] ?? 'Untitled') ?></h3>
                    <?php if ($selectedLibrary === 'books'): ?>
                        <p class="product-author"><?= htmlspecialchars($item['author'] ?? 'Unknown') ?></p>
                        <div class="product-footer">
                            <span class="product-price">
                                Price: <?= isset($item['price']) ? '€' . number_format($item['price'], 2) : 'Free' ?>
                            </span>
                            <div class="product-footer">
                            <a href="<?= '?page=product&library=books&product_type=books&product_id=' . urlencode($item['id']) ?>" class="btn">View</a>
                        </div>
                        </div>
                    <?php elseif ($selectedLibrary === 'movies'): ?>
                        <p><strong>Year:</strong> <?= htmlspecialchars($item['year'] ?? 'N/A') ?></p>
                        <p><strong>Director:</strong> <?= htmlspecialchars($item['director'] ?? 'Unknown') ?></p>
                        <p><strong>Actors:</strong> <?= htmlspecialchars($item['actors'] ?? 'Unknown') ?></p>
                        <p><strong>Rating:</strong> <?= htmlspecialchars($item['rating'] ?? 'N/A') ?></p>
                        <div class="product-footer">
                            <a href="<?= '?page=product&library=movies&product_type=movies&product_id=' . urlencode($item['id']) ?>" class="btn">View</a>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['totalPages'] > 1): ?>
        <div class="pagination">
            <?php if ($pagination['currentPage'] > 1): ?>
                <a href="?<?= $baseQuery ?>page=<?= $pagination['currentPage'] - 1 ?>" class="page-btn">&laquo; Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                <a href="?<?= $baseQuery ?>page=<?= $i ?>" class="page-btn <?= $i === $pagination['currentPage'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                <a href="?<?= $baseQuery ?>page=<?= $pagination['currentPage'] + 1 ?>" class="page-btn">Next &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php endif; ?>
</section>
<?php if (empty($selectedLibrary) || $selectedLibrary === 'No library'): ?>
    <div class="card text-center msg-error" style="min-width: 300px; min-height: 100px;">
        <p >Please select a library to view available products.</p>
    </div>
<?php endif; ?>

<script>
function toggleFilters() {
    const accordion = document.getElementById('filtersAccordion');
    accordion.classList.toggle('active');
}
</script>
