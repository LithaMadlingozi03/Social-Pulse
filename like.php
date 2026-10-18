
<?php

require_once __DIR__ . '/app.php';

require_login();
verify_csrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('feed.php');
}

$me = current_user();
$post_id = (int) ($_POST['post_id'] ?? 0);

if ($post_id > 0) {

    // Check whether the current user has already liked this post.
    // The likes table has no id column, so select 1 instead.
    $stmt = $conn->prepare(
        'SELECT 1 FROM likes WHERE post_id = ? AND user_id = ?'
    );

    $stmt->bind_param('ii', $post_id, $me['id']);
    $stmt->execute();

    $already_liked = $stmt->get_result()->fetch_assoc();

    if ($already_liked) {

        // Remove the existing like.
        $stmt = $conn->prepare(
            'DELETE FROM likes WHERE post_id = ? AND user_id = ?'
        );

        $stmt->bind_param('ii', $post_id, $me['id']);
        $stmt->execute();

    } else {

        // Add a new like.
        $stmt = $conn->prepare(
            'INSERT IGNORE INTO likes (post_id, user_id) VALUES (?, ?)'
        );

        $stmt->bind_param('ii', $post_id, $me['id']);
        $stmt->execute();
    }
}

// Return to the page where the user clicked Like.
$back = $_SERVER['HTTP_REFERER'] ?? 'feed.php';

if (
    parse_url($back, PHP_URL_HOST)
    && parse_url($back, PHP_URL_HOST) !== ($_SERVER['HTTP_HOST'] ?? '')
) {
    $back = 'feed.php';
}

redirect($back);
