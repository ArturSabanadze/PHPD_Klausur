<?php
// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

?>

<style>
.logout-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    background-color: #1e1e1e;
}
.logout-card {
    text-align: center;
    color: #e7e7e7;
}
</style>

<div class="logout-wrapper">
    <div class="logout-card">
        <h1 class="logout-title">You have been logged out</h1>
        <p class="logout-sub">
            Thank you for visiting — your session has ended.
            You can return to the homepage or log in again.
        </p>

        <div class="logout-actions">
            <a class="lgo-btn btn-primary" href="index.php?page=home">Return to Home</a>
            <a class="lgo-btn btn-outline" href="index.php?page=login">Log in</a>
        </div>
    </div>
</div>