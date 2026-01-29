<?php

// Sicherstellen, dass die Sitzung gestartet ist. CRSF-Schutz prüfen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['login_error'] = 'Security validation failed. Please try again.';
        header("Location: index.php?page=login");
        exit;
    }
}

require_once __DIR__ . '/../DB_connection/db_connection.php';

// User Login POST-Anfragen bearbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Eingabefelder validieren
    if (!$username || !$password) {
        $_SESSION['login_error'] = 'Please enter both username and password.';
    }

    try {
        // Benutzer aus der Datenbank abrufen
        $stmt = $pdo->prepare("SELECT u.id, u.email, u.username, u.password_hash, u.status 
                               FROM users u
                               WHERE u.username = :username
                               LIMIT 1");
        // Benutzer anhand des Benutzernamens abrufen                       
        $stmt->execute([':username' => $username]);
        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Überprüfen, ob der Benutzer existiert und das Passwort korrekt ist und erstelle Session
        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                // Erfolgreicher Login = Session token und Benutzerdaten setzen
                session_regenerate_id(true);
                $token = bin2hex(random_bytes(16));
                $_SESSION['token'] = $token;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['status'] = $user['status'];
                $_SESSION['login_success'] = 'Login successful! Hello, ' . $user['username'] . '.';
                echo "<script>window.location.href = 'index.php?page=home';</script>";
            } else {
                // Ungültiges Passwort message
                $_SESSION['login_error'] = 'Invalid username or password.';
            }
        } else {
            $_SESSION['login_error'] = 'User does not exist.';
        }
    } catch (PDOException $e) {
        // Optional: Fehler für Admin-Logging
        $_SESSION['login_error'] = 'Database error. Please try again later.' . $e->getMessage();
    }

}

// Admin-Login POST-Anfragen bearbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'admin-login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Eingabefelder validieren
    if (!$username || !$password) {
        $_SESSION['login_error'] = 'Please enter both username and password.';
    }

    try {
        // Benutzer aus der Datenbank abrufen
        $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM admins WHERE username = :username AND role = 'admin' LIMIT 1");
        // Benutzer anhand des Benutzernamens abrufen                       
        $stmt->execute([':username' => $username]);
        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Überprüfen, ob der Benutzer existiert und das Passwort korrekt ist und erstelle Session
        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                // Erfolgreicher Login = Session token und Benutzerdaten setzen
                $token = bin2hex(random_bytes(16));
                $_SESSION['token'] = $token;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_success'] = 'Login successful! Hello, ' . $user['username'] . '.';
                header('Location: admin_dashboard.php?page=home');

            } else {
                // Ungültiges Passwort message
                $_SESSION['login_error'] = 'Invalid username or password.';
            }
        } else {
            $_SESSION['login_error'] = 'User does not exist.';
        }
    } catch (PDOException $e) {
        // Optional: Fehler für Admin-Logging
        $_SESSION['login_error'] = 'Database error. Please try again later.';
    }
}

