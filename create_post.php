<?php
require 'includes/db.php';
session_start();
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $date_reported = $_POST['date_reported'];
    $item_description = $_POST['item_description'];
    $post_type = $_POST['post_type'];
    $item_status = 'Pending';
    $category_id = $_POST['category_id'];
    $user_id = $_SESSION['user_id'];
    
    $sql = "INSERT INTO post (title, location_reported, date_reported, item_description, post_type, item_status, category_id, user_id)
            VALUES (:title, :location, :date_reported, :item_description, :post_type, :item_status, :category_id, :user_id)";
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
            'user_id' => $user_id
        ]);
        header('Location: my_profile.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error creating post: " . $e->getMessage();
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
    <title>Create Post - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-200">
    <!-- Main Section -->
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="flex justify-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Create a Post</h1>
        </div>
        <form action="create_post.php" method="POST" class="bg-white p-8 rounded-lg shadow-md">
            <!-- Post Type -->
            <div class="mb-6">
                <label for="post_type" class="block text-sm font-medium text-gray-700">Post Type</label>
                <select id="post_type" name="post_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="Lost">Lost</option>
                    <option value="Found">Found</option>
                </select>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter a brief title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Location -->
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700">Location Reported</label>
                <input type="text" id="location" name="location" required placeholder="Enter the location" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="item_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="item_description" name="item_description" rows="4" required placeholder="Provide details about the item" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date of Incident -->
            <div class="mb-6">
                <label for="date_reported" class="block text-sm font-medium text-gray-700">Date of Incident</label>
                <input type="date" id="date_reported" name="date_reported" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>



<?php include 'templates/footer.php'; ?>
