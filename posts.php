<?php
require 'includes/db.php';
session_start(); 
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$sql = "SELECT p.*, c.category_name, u.name AS user_name 
        FROM post p
        JOIN category c ON p.category_id = c.category_id
        JOIN user u ON p.user_id = u.user_id
        WHERE 1=1";

if (!empty($_GET['post_type'])) {
    $sql .= " AND p.post_type = :post_type";
}
if (!empty($_GET['category'])) {
    $sql .= " AND c.category_name = :category";
}
if (!empty($_GET['location'])) {
    $sql .= " AND p.location_reported = :location";
}
if (!empty($_GET['search'])) {
    $sql .= " AND (p.title LIKE :search OR p.item_description LIKE :search)";
}
$sql .= " ORDER BY p.created_at DESC";


$stmt = $pdo->prepare($sql);
if (!empty($_GET['post_type'])) {
    $stmt->bindValue(':post_type', $_GET['post_type']);
}
if (!empty($_GET['category'])) {
    $stmt->bindValue(':category', $_GET['category']);
}
if (!empty($_GET['location'])) {
    $stmt->bindValue(':location', $_GET['location']);
}
if (!empty($_GET['search'])) {
    $searchTerm = '%' . $_GET['search'] . '%';
    $stmt->bindValue(':search', $searchTerm);
}
$stmt->execute();
$posts = $stmt->fetchAll();

$categoriesStmt = $pdo->query("SELECT category_name FROM category");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> 
</head>
<body class="bg-gray-200 hero-pattern">
    <!-- Filters Section -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-center mb-8">
                <h1 class="text-5xl font-extrabold text-gray-900">Browse Posts</h1>
            </div>
            <form method="GET" class="mt-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex space-x-4">
                    <!-- Category Dropdown -->
                    <select name="category" class="form-select bg-gray-50 border border-gray-400 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_name']) ?>" 
                                    <?= isset($_GET['category']) && $_GET['category'] === $category['category_name'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Post Type -->
                    <select name="post_type" class="form-select bg-gray-50 border border-gray-400 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Post Types</option>
                        <option value="lost" <?= isset($_GET['post_type']) && $_GET['post_type'] === 'lost' ? 'selected' : '' ?>>Lost</option>
                        <option value="found" <?= isset($_GET['post_type']) && $_GET['post_type'] === 'found' ? 'selected' : '' ?>>Found</option>
                    </select>

                    <!-- Location Dropdown -->
                    <select name="location" class="form-select bg-gray-50 border border-gray-400 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Locations</option>
                        <option value="1 No. Gate Area" <?= isset($_GET['location']) && $_GET['location'] === '1 No. Gate Area' ? 'selected' : '' ?>>1 No. Gate Area</option>
                        <option value="2 No. Gate Area" <?= isset($_GET['location']) && $_GET['location'] === '2 No. Gate Area' ? 'selected' : '' ?>>2 No. Gate Area</option>
                        <option value="Zero Point" <?= isset($_GET['location']) && $_GET['location'] === 'Zero Point' ? 'selected' : '' ?>>Zero Point</option>
                        <option value="Shaheed Minar" <?= isset($_GET['location']) && $_GET['location'] === 'Shaheed Minar' ? 'selected' : '' ?>>Shaheed Minar</option>
                        <option value="Central Library" <?= isset($_GET['location']) && $_GET['location'] === 'Central Library' ? 'selected' : '' ?>>Central Library</option>
                        <option value="Gymnasium" <?= isset($_GET['location']) && $_GET['location'] === 'Gymnasium' ? 'selected' : '' ?>>Gymnasium</option>
                        <option value="Botanical Garden" <?= isset($_GET['location']) && $_GET['location'] === 'Botanical Garden' ? 'selected' : '' ?>>Botanical Garden</option>
                        <option value="Chittagong University Medical Center" <?= isset($_GET['location']) && $_GET['location'] === 'Chittagong University Medical Center' ? 'selected' : '' ?>>Chittagong University Medical Center</option>
                        <option value="Jamal Nazrul Islam Research Centre" <?= isset($_GET['location']) && $_GET['location'] === 'Jamal Nazrul Islam Research Centre' ? 'selected' : '' ?>>Jamal Nazrul Islam Research Centre</option>
                        <option value="Institute of Forestry and Environmental Sciences" <?= isset($_GET['location']) && $_GET['location'] === 'Institute of Forestry and Environmental Sciences' ? 'selected' : '' ?>>Institute of Forestry and Environmental Sciences</option>
                        <option value="Faculty of Engineering" <?= isset($_GET['location']) && $_GET['location'] === 'Faculty of Engineering' ? 'selected' : '' ?>>Faculty of Engineering</option>
                        <option value="Faculty of Science" <?= isset($_GET['location']) && $_GET['location'] === 'Faculty of Science' ? 'selected' : '' ?>>Faculty of Science</option>
                        <option value="Faculty of Biological Science" <?= isset($_GET['location']) && $_GET['location'] === 'Faculty of Biological Science' ? 'selected' : '' ?>>Faculty of Biological Science</option>
                        <option value="Institute of Marine Science" <?= isset($_GET['location']) && $_GET['location'] === 'Institute of Marine Science' ? 'selected' : '' ?>>Institute of Marine Science</option>
                        <option value="Faculty of Social Sciences" <?= isset($_GET['location']) && $_GET['location'] === 'Faculty of Social Sciences' ? 'selected' : '' ?>>Faculty of Social Sciences</option>
                        <option value="Faculty of Business Administration" <?= isset($_GET['location']) && $_GET['location'] === 'Faculty of Business Administration' ? 'selected' : '' ?>>Faculty of Business Administration</option>
                        <option value="Faculty of Law" <?= isset($_GET['location']) && $_GET['location'] === 'Faculty of Law' ? 'selected' : '' ?>>Faculty of Law</option>
                        <option value="Alaol Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Alaol Hall' ? 'selected' : '' ?>>Alaol Hall</option>
                        <option value="A. F. Rahman Hall" <?= isset($_GET['location']) && $_GET['location'] === 'A. F. Rahman Hall' ? 'selected' : '' ?>>A. F. Rahman Hall</option>
                        <option value="Shahjalal Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Shahjalal Hall' ? 'selected' : '' ?>>Shahjalal Hall</option>
                        <option value="Suhrawardy Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Suhrawardy Hall' ? 'selected' : '' ?>>Suhrawardy Hall</option>
                        <option value="Shah Amanat Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Shah Amanat Hall' ? 'selected' : '' ?>>Shah Amanat Hall</option>
                        <option value="Shamsun Nahar Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Shamsun Nahar Hall' ? 'selected' : '' ?>>Shamsun Nahar Hall</option>
                        <option value="Shaheed Abdur Rab Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Shaheed Abdur Rab Hall' ? 'selected' : '' ?>>Shaheed Abdur Rab Hall</option>
                        <option value="Pritilata Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Pritilata Hall' ? 'selected' : '' ?>>Pritilata Hall</option>
                        <option value="Deshnetri Begum Khaleda Zia Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Deshnetri Begum Khaleda Zia Hall' ? 'selected' : '' ?>>Deshnetri Begum Khaleda Zia Hall</option>
                        <option value="Masterda Suriya Sen Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Masterda Suriya Sen Hall' ? 'selected' : '' ?>>Masterda Suriya Sen Hall</option>
                        <option value="Bangabandhu Sheikh Mujibur Rahman Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Bangabandhu Sheikh Mujibur Rahman Hall' ? 'selected' : '' ?>>Bangabandhu Sheikh Mujibur Rahman Hall</option>
                        <option value="Janonetri Sheikh Hasina Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Janonetri Sheikh Hasina Hall' ? 'selected' : '' ?>>Janonetri Sheikh Hasina Hall</option>
                        <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Bangamata Sheikh Fazilatunnesa Mujib Hall' ? 'selected' : '' ?>>Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                        <option value="Artist Rashid Chowdhury Hostel" <?= isset($_GET['location']) && $_GET['location'] === 'Artist Rashid Chowdhury Hostel' ? 'selected' : '' ?>>Artist Rashid Chowdhury Hostel</option>
                        <option value="Atish Dipangkar Srigyan Hall" <?= isset($_GET['location']) && $_GET['location'] === 'Atish Dipangkar Srigyan Hall' ? 'selected' : '' ?>>Atish Dipangkar Srigyan Hall</option>
                        <option value="Chittagong University School & College" <?= isset($_GET['location']) && $_GET['location'] === 'Chittagong University School & College' ? 'selected' : '' ?>>Chittagong University School & College</option>
                    </select>
                </div>

                <!-- Search Input -->
                <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                    placeholder="Search posts..." 
                    class="border border-gray-400 rounded-md py-2 px-4 w-full md:w-84 focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <!-- Button Container -->
                <div class="flex space-x-2">
                    <!-- Filter Button -->
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">Filter</button>
                    
                    <!-- Reset Button -->
                    <button type="button" onclick="window.location.href='<?= $_SERVER['PHP_SELF'] ?>'" 
                            class="bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500 transition">
                        Reset
                    </button>
                </div>
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

<?php include 'templates/footer.php'; ?>



