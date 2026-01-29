<?php
$username = $_SESSION['username'] ?? '';
$library = $_GET['library'] ?? 'books'; // default
?>

<!-- product titles fetcher -->
<script src="/functions/get_product_titles.js"></script>

<div class="navbarContainerWrapper">
    <navbar class="main-navbar">

        <!-- Logo -->
        <div class="logo">
            <a href="index.php?page=home">
                <img src="images/logo1.png" alt="Logo">
            </a>
        </div>

        <!-- Search -->
        <div class="nav-filter-search-wrapper">
            <form method="GET" id="navSearchForm">

                <?php
                // Preserve current GET params except search
                foreach ($_GET as $key => $value) {
                    if ($key === 'search') continue;
                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                }
                ?>

                <input
                    type="text"
                    name="search"
                    class="nav-filter-search"
                    placeholder="Search products by title or author..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    autocomplete="off"
                />

                <button type="submit" style="display:none;"></button>

                <!-- Suggestions -->
                <div class="search-suggestions"></div>
            </form>
        </div>

        <!-- Right buttons -->
        <div class="navbar-btn-right">
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="?page=login" class="login-btn">Sign in</a>
            <?php else: ?>
                <a href="?page=logout" class="login-btn">Logout</a>
            <?php endif; ?>

            <a href="#" class="cart-btn"> <!-- User Panel removed -->
                <?= htmlspecialchars($username ? "Hallo $username" : '') ?>
            </a>
        </div>

    </navbar>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.nav-filter-search');
    const suggestionsBox = document.querySelector('.search-suggestions');
    const library = '<?= htmlspecialchars($library) ?>';

    let titles = [];

    // Fetch titles ONCE
    returnProductTitlesByType(library).then(result => {
        titles = result;
    });

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();
        suggestionsBox.innerHTML = '';

        if (!query || !titles.length) {
            suggestionsBox.style.display = 'none';
            return;
        }

        const matches = titles.filter(title =>
            title.toLowerCase().includes(query)
        );

        if (!matches.length) {
            suggestionsBox.style.display = 'none';
            return;
        }

        matches.forEach(title => {
            const item = document.createElement('div');
            item.textContent = title;
            item.classList.add('search-suggestion-item');

            item.addEventListener('click', () => {
                searchInput.value = title;
                suggestionsBox.style.display = 'none';
                document.getElementById('navSearchForm').submit();
            });

            suggestionsBox.appendChild(item);
        });

        suggestionsBox.style.display = 'block';
    });

    document.addEventListener('click', e => {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
});
</script>
