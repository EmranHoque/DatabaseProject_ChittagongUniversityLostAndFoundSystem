<?php
require 'includes/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  
    exit;
}

include 'templates/header.php';

if (!isset($_GET['post_id'])) {
    header('Location: posts.php');
    exit;
}

$post_id = $_GET['post_id'];

// Fetch post details
$sql = "SELECT p.*, c.category_name, u.name AS user_name, u.phone_number AS contact_info 
        FROM post p
        JOIN category c ON p.category_id = c.category_id
        JOIN user u ON p.user_id = u.user_id
        WHERE p.post_id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}

// Fetch comments
$sql = "SELECT com.*, u.name AS commenter_name 
        FROM comments com
        JOIN user u ON com.user_id = u.user_id
        WHERE com.post_id = :post_id
        ORDER BY com.comment_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);
$comments = $stmt->fetchAll();

// ... (rest of your code remains unchanged)
?>


$editing_comment_id = null;

// Handle edit comment request
if (isset($_POST['edit_comment_request']) && isset($_POST['comment_id'])) {
    $editing_comment_id = $_POST['comment_id'];
}

// Delete comment
if (isset($_POST['delete_comment']) && isset($_POST['comment_id']) && isset($_SESSION['user_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM comments WHERE comment_id = :comment_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['comment_id' => $comment_id, 'user_id' => $user_id]);
    header("Location: post_details.php?post_id=$post_id");
    exit;
}

// Edit comment
if (isset($_POST['edit_comment']) && isset($_POST['comment_id']) && isset($_SESSION['user_id'])) {
    $comment_id = $_POST['comment_id'];
    $new_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE comments SET comment_text = :comment_text WHERE comment_id = :comment_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['comment_text' => $new_text, 'comment_id' => $comment_id, 'user_id' => $user_id]);
    header("Location: post_details.php?post_id=$post_id");
    exit;
}

// Delete post
if (isset($_POST['delete_post']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM post WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
    header("Location: posts.php");
    exit;
}

// Add a new comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text']) && isset($_SESSION['user_id'])) {
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO comments (post_id, user_id, comment_date, comment_text)
            VALUES (:post_id, :user_id, NOW(), :comment_text)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id, 'comment_text' => $comment_text]);
        header("Location: post_details.php?post_id=$post_id");
        exit;
    } catch (PDOException $e) {
        $error = "Error adding comment: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-gray-200">
    
    <!-- Post Details Section -->
    <div class="max-w-5xl mx-auto py-12 px-4">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="text-sm text-gray-500 italic">Posted on: <?= htmlspecialchars($post['date_reported']) ?></p>

            <!-- Post Details -->
            <div class="text-gray-700 space-y-4 mt-6">
                <p><strong>Post Type:</strong> <?= htmlspecialchars($post['post_type']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($post['category_name']) ?></p>
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($post['item_description'])) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($post['location_reported']) ?></p>
                <p><strong>Contact Info:</strong> <?= htmlspecialchars($post['contact_info']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($post['item_status']) ?></p>
            </div>

<!-- Post Actions -->
<div class="mt-6 flex justify-end space-x-4">
    <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
        <a href="edit_post.php?post_id=<?= $post_id ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Edit Post</a>
        <form action="post_details.php?post_id=<?= $post_id ?>" method="POST" onsubmit="return confirm('Delete post?');">
            <button type="submit" name="delete_post" class="bg-red-600 text-white px-4 py-2 rounded-lg">Delete Post</button>
        </form>
    <?php endif; ?>
</div>



            <!-- Comments Section -->
            <div class="mt-10">
                <h2 class="text-2xl font-bold text-gray-900">Comments</h2>
                <?php foreach ($comments as $comment): ?>
                    <div class="bg-gray-100 p-4 rounded-lg mt-4">
                        <p><strong><?= htmlspecialchars($comment['commenter_name']) ?>:</strong></p>

                        <!-- Show and edit comment -->
                        <?php if ($editing_comment_id == $comment['comment_id']): ?>
                            <!-- Edit form -->
                            <form action="post_details.php?post_id=<?= $post_id ?>" method="POST">
                                <textarea name="comment_text" class="w-full border p-2 rounded-lg"
                                    required><?= htmlspecialchars($comment['comment_text']) ?></textarea>
                                <div class="flex space-x-2 mt-2">
                                    <button type="submit" name="edit_comment"
                                        class="bg-blue-600 text-white px-3 py-1 rounded-lg">Update</button>
                                    <button type="button"
                                        onclick="window.location.href='post_details.php?post_id=<?= $post_id ?>'"
                                        class="bg-gray-500 text-white px-3 py-1 rounded-lg">Cancel</button>
                                </div>
                                <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                            </form>
                        <?php else: ?>
                            <!-- if not user comment -->
                            <p><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($comment['comment_date']) ?></p>
                        <?php endif; ?>


                        <?php if ($_SESSION['user_id'] == $comment['user_id'] && !$editing_comment_id): ?>
                            <div class="mt-2 flex space-x-2">
                                <!-- Edit  -->
                                <form action="post_details.php?post_id=<?= $post_id ?>" method="POST">
                                    <button type="submit" name="edit_comment_request"
                                        class="bg-blue-600 text-white px-2 py-1 rounded-lg">Edit</button>
                                    <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                </form>
                                <!-- Delete  -->
                                <form action="post_details.php?post_id=<?= $post_id ?>" method="POST"
                                    onsubmit="return confirm('Delete comment?');">
                                    <button type="submit" name="delete_comment"
                                        class="bg-red-600 text-white px-2 py-1 rounded-lg">Delete</button>
                                    <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Write Comment -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="post_details.php?post_id=<?= $post_id ?>" method="POST" class="mt-6">
                        <textarea name="comment_text" class="w-full p-4 border rounded-lg" placeholder="Write a comment..."
                            required></textarea>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 mt-2 rounded-lg">Post
                            Comment</button>
                    </form>
                <?php endif; ?>
            </div>

        </div>
    </div>
</body>

</html>

<?php include 'templates/footer.php'; ?>
