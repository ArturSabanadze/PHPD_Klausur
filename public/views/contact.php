<?php
if(!isset($_SESSION['username']))
    header('Location: index.php?page=login');
?>

<section class="container">
    <div class="content-terms-page">
        <h1>Contact Us</h1>

        <p>
            This is an educational project. You can use this form to send a message to the project maintainers.
            No real emails are sent unless you configure a backend mail service.
        </p>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Retrieve and sanitize session messages
        $success_message = isset($_SESSION['contact_success']) ? htmlspecialchars($_SESSION['contact_success']) : '';
        $error_message = isset($_SESSION['contact_error']) ? htmlspecialchars($_SESSION['contact_error']) : '';

        unset($_SESSION['contact_success'], $_SESSION['contact_error']);

        // Pre-fill user info if logged in
        $username = isset($_SESSION['username']) ? htmlspecialchars(trim($_SESSION['username'])) : '';
        $email = isset($_SESSION['email']) ? htmlspecialchars(trim($_SESSION['email'])) : '';
        ?>

        <?php if ($success_message): ?>
            <div class="msg-success"><?= $success_message ?></div>
        <?php elseif ($error_message): ?>
            <div class="msg-error"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="post" action="api_middleware/contact_node.php" class="contact-form">
            <!-- Pre-filled hidden fields if user is logged in -->
            <input type="hidden" name="username" value="<?= $username ?>">
            <input type="hidden" name="email" value="<?= $email ?>">

            <label>
                Subject
                <input type="text" name="subject" required placeholder="Subject of your message"
                       value="<?= isset($_SESSION['contact_subject']) ? htmlspecialchars(trim($_SESSION['contact_subject'])) : '' ?>">
            </label>

            <label>
                Message
                <textarea name="message" rows="6" required placeholder="Write your message here..."><?= isset($_SESSION['contact_message']) ? htmlspecialchars(trim($_SESSION['contact_message'])) : '' ?></textarea>
            </label>

            <div style="margin-top: 1rem;">
                <button type="submit" class="btn-primary">Send Message</button>
                <button type="reset" class="btn-secondary">Clear</button>
            </div>
        </form>
    </div>
</section>
