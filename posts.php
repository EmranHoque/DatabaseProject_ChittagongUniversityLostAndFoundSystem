<?php
require 'includes/db.php';

// Fetch posts with filters
$sql = "SELECT p.*, c.category_name, u.name AS user_name 
        FROM post p
        JOIN category c ON p.category_id = c.category_id
        JOIN user u ON p.user_id = u.user_id
        WHERE 1=1";

// Apply filters
if (!empty($_GET['post_type'])) {
    $sql .= " AND p.post_type = :post_type";
}
if (!empty($_GET['category'])) {
    $sql .= " AND c.category_name = :category";
}
if (!empty($_GET['search'])) {
    $sql .= " AND (p.title LIKE :search OR p.item_description LIKE :search)";
}
$sql .= " ORDER BY p.created_at DESC";

// Prepare and execute statement
$stmt = $pdo->prepare($sql);
if (!empty($_GET['post_type'])) {
    $stmt->bindValue(':post_type', $_GET['post_type']);
}
if (!empty($_GET['category'])) {
    $stmt->bindValue(':category', $_GET['category']);
}
if (!empty($_GET['search'])) {
    $searchTerm = '%' . $_GET['search'] . '%';
    $stmt->bindValue(':search', $searchTerm);
}
$stmt->execute();
$posts = $stmt->fetchAll();

// Fetch all categories for the dropdown
$categoriesStmt = $pdo->query("SELECT category_name FROM category");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - CU Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> 
</head>
<body class="bg-gray-50 hero-pattern">
    <!-- Navigation -->
    <nav class="bg-white custom-shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-purple-600 text-xl font-bold gradient-text">Chittagong University Lost & Found</span>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">Home</a>
                    <a href="create_post.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">Create Post</a>
                    <a href="my_profile.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">My Profile</a>
                    <a href="logout.php" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Filters Section -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-gray-900">Browse Posts</h2>
            
            <form method="GET" class="mt-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex space-x-4">
                    <!-- Category Dropdown -->
                    <select name="category" class="form-select bg-gray-50 border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_name']) ?>" 
                                    <?= isset($_GET['category']) && $_GET['category'] === $category['category_name'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Post Type Dropdown -->
                    <select name="post_type" class="form-select bg-gray-50 border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Post Types</option>
                        <option value="lost" <?= isset($_GET['post_type']) && $_GET['post_type'] === 'lost' ? 'selected' : '' ?>>Lost</option>
                        <option value="found" <?= isset($_GET['post_type']) && $_GET['post_type'] === 'found' ? 'selected' : '' ?>>Found</option>
                    </select>
                </div>

                <!-- Search Input -->
                <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                       placeholder="Search posts..." 
                       class="border border-gray-400 rounded-md py-2 px-4 w-full md:w-84 focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <!-- Submit Button -->
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">Filter</button>
            </form>

            <!-- Posts Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (count($posts) > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md card-hover relative">
                            <div 
                                class="absolute top-0 left-0 rounded-tr-lg rounded-bl-lg px-3 py-1 text-xs font-bold text-white 
                                       <?= $post['post_type'] == 'Lost' ? 'bg-red-500' : 'bg-green-500' ?>">
                                <?= htmlspecialchars($post['post_type']) ?>
                            </div>

                            <!-- Post Content -->
                            <div class="mt-4">
                                <p class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($post['title']) ?></p>
                                <p class="text-sm text-gray-500 mt-2">
                                    <i class="fas fa-layer-group"></i> <?= htmlspecialchars($post['category_name']) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($post['location_reported']) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-user"></i> Reported by: <?= htmlspecialchars($post['user_name']) ?>
                                </p>
                            </div>

                            <!-- View Details Button -->
                            <a href="post_details.php?post_id=<?= $post['post_id'] ?>" 
                               class="text-purple-600 hover:text-purple-800 block mt-4 text-center">
                                View Details
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">No posts found matching the criteria.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

</body>
</html>
