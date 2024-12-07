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

$statsSql = "
    SELECT 
        SUM(CASE WHEN post_type = 'Lost' THEN 1 ELSE 0 END) AS total_lost,
        SUM(CASE WHEN post_type = 'Found' THEN 1 ELSE 0 END) AS total_found,
        SUM(CASE WHEN item_status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
        SUM(CASE WHEN item_status = 'Pending' THEN 1 ELSE 0 END) AS unresolved
    FROM post
    WHERE user_id = :user_id";
$stmtStats = $pdo->prepare($statsSql);
$stmtStats->execute(['user_id' => $user_id]);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

$sql_posts = "
    SELECT post.*, category.category_name 
    FROM post 
    JOIN category ON post.category_id = category.category_id 
    WHERE post.user_id = :user_id";
$stmt_posts = $pdo->prepare($sql_posts);
$stmt_posts->execute(['user_id' => $user_id]);
$posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="max-w-7xl mx-auto py-12 px-4 space-y-12">
        <!-- Profile and Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">My Profile</h1>
                <div class="space-y-4">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone_number']) ?></p>
                </div>
                <div class="mt-20 flex justify-end">
                    <a href="edit_profile.php" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:opacity-90 transition duration-300">Edit Profile</a>
                </div>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-3xl font-semibold text-gray-900 mb-6">Analytics Board</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-yellow-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Total Lost</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['total_lost']) ?></p>
                    </div>
                    <div class="bg-blue-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Total Found</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['total_found']) ?></p>
                    </div>
                    <div class="bg-green-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Resolved</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['resolved']) ?></p>
                    </div>
                    <div class="bg-red-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Pending</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['unresolved']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Posts Section -->
        <div class="bg-gray p-8">
            <h2 class="text-3xl font-semibold text-gray-900">My Posts</h2>
            <?php if (count($posts) > 0): ?>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md relative">
                            <div 
                                class="absolute top-0 left-0 rounded-tr-lg rounded-bl-lg px-3 py-1 text-xs font-bold text-white 
                                       <?= $post['post_type'] == 'Lost' ? 'bg-red-500' : 'bg-green-500' ?>">
                                <?= htmlspecialchars($post['post_type']) ?>
                            </div>

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
