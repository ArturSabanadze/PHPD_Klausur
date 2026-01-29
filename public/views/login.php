<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF-Token generieren, falls nicht vorhanden
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../../Backend/api/authentication_api.php';

$error = $_SESSION['login_error'] ?? '';
$success_message = $_SESSION['login_success'] ?? '';

unset($_SESSION['login_error'], $_SESSION['login_success']);
$success = !empty($success_message);

?>

<div class="login-container">
    <div class="logout-card">
    <?php if ($error): ?>
        <div class="msg-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="msg-success"><?= htmlspecialchars($success_message) ?></div>
    <?php else: ?>
        <form method="post" class="login-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="action" value="login">

            <label class="form-label">
                Username
                <input type="text" name="username" class="form-input" required>
            </label>

            <label class="form-label">
                Password
                <input type="password" name="password" class="form-input" required>
            </label>

            <button type="submit" class="login-btn-main">Login</button>

            <hr class="form-divider">

            <button type="button" class="login-btn-secondary"
                onclick="window.location.href='index.php?page=register'">Register</button>
        </form>
    <?php endif; ?>
    </div>
</div>
