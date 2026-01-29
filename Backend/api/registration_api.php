<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Redirect if logged in
if (isset($_SESSION['token'])) {
    $_SESSION['flash_message'] = 'You are logged in. Please logout before registering.';
    header('Location: index.php');
    exit;
}
// Include necessary files
require_once __DIR__ . '/../DB_connection/db_connection.php';
require_once __DIR__ . '/../Classes/User/User.php'; // User class
// Validation patterns
$namePattern = '/^[a-zA-Z\s-]+$/';
$usernamePattern = '/^[a-zA-Z0-9\s-]+$/';
// Initialize session messages
$_SESSION['register_error'] = '';
$_SESSION['register_success_message'] = '';
$_SESSION['register_success'] = false;
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
    //users table fields and his object
    $user_data = [
        'username' => trim($_POST['username'] ?? ''),
        'plain_password' => $_POST['password'] ?? '',
        'email' => trim($_POST['email'] ?? '')
    ];
    // POST Validation
    if ($user_data['username'] === '') {
        $_SESSION['register_error'] = 'Name are required.';
        return;
    } elseif (!preg_match($usernamePattern, $user_data['username'])) {
        $_SESSION['register_error'] = 'Username contains invalid characters.';
        return;
    } elseif (!filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Invalid email format.';
        return;
    }
     else {

        try {
            // Create instances of user, address, and profile
            $user = new User($user_data);

            // Check if user exists
            if ($user->exist($pdo)) {
                $_SESSION['register_error'] = 'Username or email already exists.';
                return;
            } else {
                // Save to DB
                $user->save($pdo);
                // set success message
                $_SESSION['register_success_message'] = 'Registration successful! You can now log in.';
                $_SESSION['register_success'] = true;
                // Clear form
                $user_data = [];
            }
        } catch (PDOException $e) {
            $_SESSION['register_error'] = 'Database error. Please try again later.' . $e->getMessage();
        }
    }
}
