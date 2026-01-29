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



// Seite bestimmen
$page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Admin-Dashboard</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>

<body>
    <header class="admin-header">
        <div>
            <h1>Admin-Dashboard</h1>
        </div>
        <div>
            <nav class="admin-nav">
                <a href="admin_dashboard.php?page=home">Home</a>
                <div class="nav-dropdown">
                    <a href="admin_dashboard.php?page=library">Library</a>
                    <div class="dropdown-menu">
                        <a href="admin_dashboard.php?page=books">Books</a>
                        <a href="admin_dashboard.php?page=movies">Movies</a>
                        <a href="admin_dashboard.php?page=music">Music</a>
                    </div>
                </div>
                <a href="admin_dashboard.php?page=users">Users</a>
                <a href="admin_dashboard.php?page=contact_messages">Contact Messages</a>
                <a href="admin_dashboard.php?page=flagged_comments">Flagged Comments</a>
            </nav>
        </div>
        <div>
            <nav class="right-menu">
                <a href="admin_dashboard.php?page=login">Login</a>
                <a href="admin_dashboard.php?page=logout">Logout</a>
            </nav>
        </div>
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
            case 'music':
                require __DIR__ . '/music.php';
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
                <section>
                    <h2>Welcome to the Admin Dashboard</h2>
                    <label for="admin-action" style="margin-top: 1rem; display: inline-block;">
                     Library data import. <strong style="color: red;">Use once</strong>, reimporting will skip all new data with the same Titles:
                      </label>  

                        <select id="admin-action" onchange="triggerImport(this.value)">
                            <option value="">-- Please choose --</option>
                            <option value="books">Books</option>
                            <option value="movies">Movies</option>
                        </select>

                        <p id="import-status"></p>
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