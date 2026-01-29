<h2>User Management</h2>

<?php
require_once __DIR__ . '/../../Backend/api/admin_api.php'; // Provides $allUsers as associative array

if (empty($allUsers)) {
    echo "<p>No users found.</p>";
} else {
    ?>
    <section class="users-list">
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Deleted At</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['status'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['created_at'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['updated_at'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['deleted_at'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['last_login_at'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php
}
?>
