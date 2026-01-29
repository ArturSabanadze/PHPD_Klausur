<?php
if (!session_start()) {
    session_start();
}

require_once __DIR__ . '/../../Backend/DB_Connection/db_connection.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=contact');
    exit;
}

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['contact_error'] = 'You must be logged in to send a message.';
    header('Location: index.php?page=contact');
    exit;
}

// Collect & sanitize input
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Store old values in session (for repopulating form on error)
$_SESSION['contact_subject'] = $subject;
$_SESSION['contact_message'] = $message;

// Basic validation
if ($subject === '' || $message === '') {
    $_SESSION['contact_error'] = 'Subject and message are required.';
    header('Location: index.php?page=contact');
    exit;
}

if (strlen($subject) > 255) {
    $_SESSION['contact_error'] = 'Subject cannot exceed 255 characters.';
    header('Location: ../index.php?page=contact');
    exit;
}

try {

    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (user_id, subject, message)
        VALUES (:user_id, :subject, :message)
    ");

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':subject' => $subject,
        ':message' => $message,
    ]);

    // Clear old form data
    unset($_SESSION['contact_subject'], $_SESSION['contact_message']);

    $_SESSION['contact_success'] = 'Your message has been sent successfully.';

} catch (PDOException $e) {
    $_SESSION['contact_error'] = 'Something went wrong. Please try again later.';
    // Optional: log error
    // error_log($e->getMessage());
}

header('Location: ../index.php?page=contact');
exit;
