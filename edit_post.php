<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['post_id'])) {
    header('Location: my_profile.php');
    exit;
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['user_id'];


$sql = "SELECT p.*, u.phone_number 
        FROM post p 
        INNER JOIN user u ON p.user_id = u.user_id 
        WHERE p.post_id = :post_id AND p.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found or you do not have permission to edit this post.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $date_reported = $_POST['date_reported'];
    $item_description = $_POST['item_description'];
    $post_type = $_POST['post_type'];
    $item_status = $_POST['item_status'];
    $category_id = $_POST['category_id'];

   
    $sql = "UPDATE post 
            SET title = :title, location_reported = :location, date_reported = :date_reported, 
                item_description = :item_description, post_type = :post_type, 
                item_status = :item_status, category_id = :category_id 
            WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            'title' => $title,
            'location' => $location,
            'date_reported' => $date_reported,
            'item_description' => $item_description,
            'post_type' => $post_type,
            'item_status' => $item_status,
            'category_id' => $category_id,
            'post_id' => $post_id,
            'user_id' => $user_id
        ]);
        header('Location: my_profile.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error updating post: " . $e->getMessage();
    }
}


$sql = "SELECT * FROM category";
$categories = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white custom-shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-purple-600 text-xl font-bold gradient-text">Chittagong University Lost & Found</span>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">Home</a>
                    <a href="posts.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">Browse Posts</a>
                    <a href="my_profile.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">My Profile</a>
                    <a href="logout.php" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Section -->
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="flex justify-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Post</h1>
        </div>
        <form action="edit_post.php?post_id=<?= htmlspecialchars($post_id) ?>" method="POST" class="bg-white p-8 rounded-lg shadow-md">
            <!-- Post Type -->
            <div class="mb-6">
                <label for="post_type" class="block text-sm font-medium text-gray-700">Post Type</label>
                <select id="post_type" name="post_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="Lost" <?= $post['post_type'] == 'Lost' ? 'selected' : '' ?>>Lost</option>
                    <option value="Found" <?= $post['post_type'] == 'Found' ? 'selected' : '' ?>>Found</option>
                </select>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required placeholder="Enter a brief title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Location -->
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700">Location Reported</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($post['location_reported']) ?>" required placeholder="Enter the location" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="item_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="item_description" name="item_description" rows="4" required placeholder="Provide details about the item" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"><?= htmlspecialchars($post['item_description']) ?></textarea>
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id'] ?>" <?= $post['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date of Incident -->
            <div class="mb-6">
                <label for="date_reported" class="block text-sm font-medium text-gray-700">Date of Incident</label>
                <input type="date" id="date_reported" name="date_reported" value="<?= htmlspecialchars($post['date_reported']) ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="item_status" class="block text-sm font-medium text-gray-700">Item Status</label>
                <select id="item_status" name="item_status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="Pending" <?= $post['item_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Resolved" <?= $post['item_status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="mb-6">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Contact Info</label>
                <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($post['phone_number']) ?>" readonly class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 sm:text-sm cursor-not-allowed">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Save Changes</button>
            </div>
        </form>
    </div>

</body>
</html>



<?php include 'templates/footer.php'; ?>
