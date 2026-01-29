<h2>Contact Messages</h2>

<?php
require_once __DIR__ . '/../../Backend/api/contact_messages_api.php'; // Provides $contactMessages as associative array

if (empty($contactMessages)) {
    echo "<p>No messages found.</p>";
} else {
    ?>
    <section class="messages-list">
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse; font-size: 0.8rem;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User_ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contactMessages as $msg): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['id']) ?></td>
                        <td><?= htmlspecialchars($msg['user_id']) ?></td>
                        <td><?= htmlspecialchars($msg['username']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= htmlspecialchars($msg['subject']) ?></td>
                        <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                        <td><?= htmlspecialchars($msg['status']) ?></td>
                        <td><?= htmlspecialchars($msg['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php
}
?>
