<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../Backend/api/products_api.php';

$user_id = $_SESSION['user_id'] ?? '';
$username = $_SESSION['username'] ?? '';
$comments = $currentProductComments ?? [];
$productType = $_GET['product_type'] ?? '';
$productId = $_GET['product_id'] ?? 0;
?>
<script src="/functions/postComment.js"></script>

<section class="product-comments card" style="max-height: none;">
    <h2>Comments</h2>

    <!-- Comment Form -->
<form method="POST" class="comment-form" onsubmit="return false;">
    <input type="hidden" name="product_type" value="<?= htmlspecialchars($productType) ?>">
    <input type="hidden" name="product_id" value="<?= (int)$productId ?>">
    <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
    
    <textarea name="comment" placeholder="Write your comment..." required></textarea>

    <label>
        Rating:
        <select name="rating">
            <option value=""></option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?>/10</option>
            <?php endfor; ?>
        </select>
    </label>

    <?php if (!empty($username)): ?>
    <button type="button"
            class="btn btn-primary"
            id="postCommentBtn"
            data-product-type="<?= htmlspecialchars($productType) ?>"
            data-product-id="<?= (int)$productId ?>"
            data-user-id="<?= htmlspecialchars($user_id) ?>">
        Post Comment
    </button>
<?php else: ?>
    <p class="login-warning">
        <em>Login to leave a comment</em>
    </p>
<?php endif; ?>

</form>


    <!-- Existing Comments -->
    <?php if (empty($comments)): ?>
        <p>No comments yet. Be the first!</p>
    <?php else: ?>
        <ul class="comment-list">
            <?php foreach ($comments as $c): ?>
                <li class="comment-item">
                    <strong><?= htmlspecialchars($c['username']) ?></strong>
                    <?php if ($c['rating']): ?>
                        <span class="rating">(<?= $c['rating'] ?>/10)</span>
                    <?php else: ?>
                        <span class="rating">(No rating)</span>
                    <?php endif; ?>
                    <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
                    <small><?= htmlspecialchars($c['created_at']) ?></small>
                    <?php if (!empty($username)): ?>
                        <button class="btn-report-comment"
                                data-comment-id="<?= (int)$c['id'] ?>"
                                data-product-type="<?= htmlspecialchars($productType) ?>">
                            Report
                        </button>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
