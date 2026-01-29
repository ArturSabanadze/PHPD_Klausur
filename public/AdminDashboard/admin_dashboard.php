<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Imports
require_once __DIR__ . '/../../Backend/DB_Connection/db_connection.php';
require_once __DIR__ . '/Functions/import_books.php';
require_once __DIR__ . '/Functions/import_movies.php';

$page = $_GET['page'] ?? 'home';
$product_to_load = '';

// Nur Admin-Benutzer zulassen, außer auf der Login-Seite
if ($page !== 'login' && (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    $_SESSION['login_error'] = 'You don’t have Admin rights.';
    header('Location: admin_dashboard.php?page=login');
    exit();
}

if ($page === 'login' && !empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin_dashboard.php?page=home');
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/admin.css">
     <link rel="stylesheet" href="../../styles/global.css">
</head>

<body>

<header class="admin-header">
    <div class="admin-header-left">
        <h1 class="admin-title">Admin Dashboard</h1>
    </div>

    <nav class="admin-nav">
        <a href="admin_dashboard.php?page=home">Home</a>

        <div class="nav-dropdown">
            <a href="admin_dashboard.php?page=library">Library</a>
            <div class="dropdown-menu">
                <a href="admin_dashboard.php?page=books">Books</a>
                <a href="admin_dashboard.php?page=movies">Movies</a>
            </div>
        </div>

        <a href="admin_dashboard.php?page=users">Users</a>
        <a href="admin_dashboard.php?page=contact_messages">Contact Messages</a>
        <a href="admin_dashboard.php?page=flagged_comments">Flagged Comments</a>
    </nav>

    <nav class="right-menu">
        <a href="admin_dashboard.php?page=login">Login</a>
        <a href="admin_dashboard.php?page=logout">Logout</a>
    </nav>
</header>

<main class="admin-main">
<?php
switch ($page) {
    case 'books':
        require __DIR__ . '/books.php';
        break;

    case 'movies':
        require __DIR__ . '/movies.php';
        break;

    case 'users':
        require __DIR__ . '/users.php';
        break;

    case 'contact_messages':
        require __DIR__ . '/contact_messages.php';
        break;

    case 'login':
        require __DIR__ . '/admin_login.php';
        break;

    case 'logout':
        require __DIR__ . '/admin_logout.php';
        break;

    case 'flagged_comments':
        require __DIR__ . '/flagged_comments.php';
        break;

    default:
        ?>
        <section class="admin-card">
            <h2>Welcome to the Admin Dashboard</h2>

            <label for="admin-action" class="admin-label">
                Library data import.
                <strong class="admin-warning">
                    Use once – reimporting will skip all new data with the same titles.
                </strong>
            </label>

            <select id="admin-action" class="admin-select" onchange="triggerImport(this.value)">
                <option value="">-- Please choose --</option>
                <option value="books_json">Books from JSON</option>
                <option value="movies_json">Movies from JSON</option>
                <option value="books_xml">Books from XML</option>
                <option value="movies_xml">Movies from XML</option>
            </select>

            <p id="import-status" class="admin-status"></p>
        </section>
        <?php
        break;
}
?>
</main>

<script>
function triggerImport(type) {
    if (!type) return;

    const status = document.getElementById('import-status');
    status.textContent = 'Importing...';

    fetch('Functions/admin_import.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `type=${encodeURIComponent(type)}`
    })
    .then(res => res.text())
    .then(msg => status.textContent = msg)
    .catch(() => status.textContent = 'Import failed.');
}
</script>

</body>
</html>
