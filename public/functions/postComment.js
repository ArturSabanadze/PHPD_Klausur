function postComment(product_type, product_id, user_id, comment, rating) {
    if (!comment || comment.trim().length === 0) {
        alert('Please write a comment before posting.');
        return;
    }

    fetch('api_middleware/node_expose.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_type,
            product_id,
            user_id,
            comment: comment.trim(),
            rating
        })
    })
    .then(res => {
        if (!res.ok) throw new Error('Request failed');
        return res.json();
    })
    .then(data => {
        if (data.status !== 'success') {
            throw new Error(data.message || 'Failed to post comment');
        }
        window.location.reload();
    })
    .catch(err => {
        console.error(err);
        alert('Something went wrong while posting your comment.');
    });
}

function reportComment(commentId, productType) {
    fetch('api_middleware/comment_report.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            comment_id: commentId,
            product_type: productType
        })
    })
    .then(res => {
        if (!res.ok) throw new Error('Request failed');
        return res.json();
    })
    .then(data => {
        if (data.status !== 'success') {
            throw new Error(data.message || 'Failed to report comment');
        }
        alert('Comment reported successfully.');
    })
    .catch(err => {
        console.error(err);
        alert('Something went wrong while reporting the comment.');
    });
}


document.addEventListener('DOMContentLoaded', () => {

    // Existing post comment listener stays as-is
    const postBtn = document.getElementById('postCommentBtn');
    if (postBtn) {
        postBtn.addEventListener('click', () => {
            const productType = postBtn.dataset.productType;
            const productId = parseInt(postBtn.dataset.productId, 10);
            const userId = postBtn.dataset.userId;

            const commentEl = document.querySelector('textarea[name="comment"]');
            const ratingEl = document.querySelector('select[name="rating"]');

            postComment(
                productType,
                productId,
                userId,
                commentEl.value,
                ratingEl.value ? parseInt(ratingEl.value, 10) : null
            );
        });
    }

    // report comment listeners
    document.querySelectorAll('.btn-report-comment').forEach(button => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;
            const productType = button.dataset.productType;

            if (!confirm('Report this comment as inappropriate?')) return;

            reportComment(commentId, productType);
        });
    });
});
