<h2>Flagged Comments</h2>

<?php
require_once __DIR__ . '/../../Backend/api/products_api.php'; // Provides $flaggedComments as associative array

if (empty($flaggedComments)) {
    echo "<p>No flagged comments found.</p>";
} else {
    ?>
    <section class="users-list">
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse; font-size: 0.8rem;">
            <thead>
                <tr>
                    
                    <th>Username</th>
                    <th>Media Type</th>
                    <th>Media ID</th>
                    <th>Title</th>
                    <th>Comment</th>
                    <th>Given Rating</th>
                    <th>Reported</th>
                    <th>Hidden</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flaggedComments as $comment): ?>
                    <tr>
                        
                        <td><?= htmlspecialchars($comment['username']) ?></td>
                        <td><?= htmlspecialchars($comment['type']) ?></td>
                        <td><?= htmlspecialchars($comment['product_id']) ?></td>
                        <td><?= htmlspecialchars($comment['title']) ?></td>
                        <td><?= htmlspecialchars($comment['comment']) ?></td>
                        <td><?= htmlspecialchars($comment['rating'] ?? '') ?></td>
                        <td><?= htmlspecialchars(($comment['reported'] ?? 'True') ? 'True' : 'False') ?></td>
                        <td><?= htmlspecialchars(($comment['hidden'] ?? 'True') ? 'True' : 'False') ?></td>
                        <td><?= htmlspecialchars($comment['created_at'] ?? '') ?></td>
                        <td>
                            <button 
                                class="btn-hide"
                                data-type="<?= htmlspecialchars($comment['type']) ?>"
                                data-id="<?= htmlspecialchars($comment['comment_id']) ?>"
                                onclick="hideComment(this)"
                            >
                                Hide Comment
                            </button>
                            <button 
                                class="btn-unflag"
                                data-type="<?= htmlspecialchars($comment['type']) ?>"
                                data-id="<?= htmlspecialchars($comment['comment_id']) ?>"
                                onclick="unflagComment(this)"
                            >
                                Unflag
                            </button>

                            <button 
                                class="btn-ban"
                                data-user="<?= htmlspecialchars($comment['username']) ?>"
                                onclick="banUser(this)"
                            >
                                Ban User
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <script>
        function hideComment(button) {
            const type = button.dataset.type;
            const id = button.dataset.id;

            if (!confirm('Hide this comment?')) return;

            fetch('/AdminDashboard/Functions/admin_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=hide_comment&type=${type}&id=${id}`
            })
            .then(res => res.text())
            .then(msg => {
                alert(msg);
                button.closest('tr').remove();
            })
            .catch(() => alert('Failed to hide comment'));
        }

        function unflagComment(button) {
            const type = button.dataset.type;
            const id = button.dataset.id;

            if (!confirm('Unflag this comment?')) return;

            fetch('/AdminDashboard/Functions/admin_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=unflag_comment&type=${type}&id=${id}`
            })
            .then(res => res.text())
            .then(msg => {
                alert(msg);
                button.closest('tr').remove();
            })
            .catch(() => alert('Failed to unflag comment'));
        }

        function banUser(button) {
            const username = button.dataset.user;

            if (!confirm(`Ban user ${username}?`)) return;

            fetch('/AdminDashboard/Functions/admin_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=ban_user&username=${encodeURIComponent(username)}`
            })
            .then(res => res.text())
            .then(msg => { alert(msg); })
            .catch(() => alert('Failed to ban user'));
        }
</script>

<?php
}
?>
