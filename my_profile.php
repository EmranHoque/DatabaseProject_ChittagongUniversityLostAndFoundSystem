<?php
require 'includes/db.php';
session_start();
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];


$sql_user = "SELECT * FROM user WHERE user_id = :user_id";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute(['user_id' => $user_id]);
$user = $stmt_user->fetch();

if (!$user) {
    die("User not found.");
}


$sql_posts = "SELECT p.*, c.category_name 
              FROM post p
              JOIN category c ON p.category_id = c.category_id
              WHERE p.user_id = :user_id
              ORDER BY p.created_at DESC";
$stmt_posts = $pdo->prepare($sql_posts);
$stmt_posts->execute(['user_id' => $user_id]);
$posts = $stmt_posts->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-200">
    <!-- My Profile Section -->
    <div class="max-w-7xl mx-auto py-12 px-4 space-y-12">
        <!-- Profile Section -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6">My Profile</h1>
            <div class="space-y-4">
                <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone_number']) ?></p>
            </div>
            <div class="mt-6 flex justify-end">
                <a href="edit_profile.php" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Edit Profile</a>
            </div>
        </div>

        <!-- My Posts Section -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-semibold text-gray-900">My Posts</h2>
            <?php if (count($posts) > 0): ?>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md relative">
                            <!-- Post Type -->
                            <div 
                                class="absolute top-0 left-0 rounded-tr-lg rounded-bl-lg px-3 py-1 text-xs font-bold text-white 
                                       <?= $post['post_type'] == 'Lost' ? 'bg-red-500' : 'bg-green-500' ?>">
                                <?= htmlspecialchars($post['post_type']) ?>
                            </div>

                            <!-- Post Details -->
                            <div class="mt-4">
                                <p class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($post['title']) ?></p>
                                <p class="text-sm text-gray-500 mt-2">
                                    <i class="fas fa-layer-group"></i> <?= htmlspecialchars($post['category_name']) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($post['date_reported']) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-tag"></i> Status: <?= htmlspecialchars($post['item_status']) ?>
                                </p>
                            </div>

                            <!-- Post edit/view option -->
                            <div class="flex justify-between items-center mt-4">
                                <a href="post_details.php?post_id=<?= $post['post_id'] ?>" class="text-purple-600 hover:text-purple-800 text-sm">View Details</a>
                                <a href="edit_post.php?post_id=<?= $post['post_id'] ?>" class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</a>
                                <a href="delete_post.php?post_id=<?= $post['post_id'] ?>" 
                                   class="text-red-600 hover:text-red-800 text-sm" 
                                   onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center mt-8">You have not created any posts yet.</p>
            <?php endif; ?>
        </div>
    </div>

    
</body>
</html>

<?php include 'templates/footer.php'; ?>
