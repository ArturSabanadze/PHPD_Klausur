<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

?>

<div class="logout-wrapper">
    <div class="logout-card">
        <h1 class="logout-title">You have been logged out</h1>
        <p class="logout-sub">
            Your session has ended.
            You can return to the homepage or log in again.
        </p>

        <div class="logout-actions">
            <a class="lgo-btn btn-primary" href="admin_dashboard.php?page=home">Return to Home</a>
            <a class="lgo-btn btn-outline" href="admin_dashboard.php?page=login">Log in</a>
        </div>
    </div>
</div>