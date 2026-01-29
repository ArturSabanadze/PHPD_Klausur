<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Backend/api/registration_api.php';

$email = $email ?? '';
$username = $username ?? '';

$error = $_SESSION['register_error'] ?? '';
$success_message = $_SESSION['register_success_message'] ?? '';
$success = $_SESSION['register_success'] ?? false;
?>

<div class="login-container">
    <?php if ($error): ?>
        <div class="msg-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="msg-success">
            Registration successful! You can now <a href="index.php?page=login">login</a>.
        </div>
    <?php else: ?>
        <div class="login-card">
            <form method="post" action="" class="login-form">
                <input type="hidden" name="action" value="register">

                <label>Username
                    <input type="text" name="username" minlength="3" maxlength="32" required
                           placeholder="Choose a username"
                           value="<?= htmlspecialchars($username) ?>" autocomplete="username">
                </label>

                <label>Email
                    <input type="email" name="email" required placeholder="Your email"
                           value="<?= htmlspecialchars($email) ?>" autocomplete="email">
                </label>

                <label>Password
                    <input type="password" name="password" required placeholder="Choose a password"
                           autocomplete="new-password">
                </label>

                <button type="submit" class="login-btn-main">Register</button>

                <hr>

                <p>Already have an account?</p>

                <button type="button" class="login-btn-secondary"
                        onclick="window.location.href='index.php?page=login'">Login</button>
            </form>
        </div>
    <?php endif; ?>
</div>
