<?php
require 'includes/db.php';
session_start();
include 'templates/header.php';

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
    $image_path = $post['image_path']; 

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        $image_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . time() . '_' . $image_name;

        $image_type = mime_content_type($_FILES['image']['tmp_name']);
        if (in_array($image_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            } else {
                $error = "Failed to upload the image.";
            }
        } else {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        }
    }

    $sql = "UPDATE post 
            SET title = :title, location_reported = :location, date_reported = :date_reported, 
                item_description = :item_description, post_type = :post_type, 
                item_status = :item_status, category_id = :category_id, image_path = :image_path
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
            'image_path' => $image_path,
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
<body class="bg-gray-200">
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="flex justify-center mb-8">
            <h1 class="text-5xl font-extrabold text-gray-900">Edit Post</h1>
        </div>
        <form action="edit_post.php?post_id=<?= htmlspecialchars($post_id) ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md">
            <div class="mb-6">
                <label for="post_type" class="block text-sm font-medium text-gray-700">Post Type</label>
                <select id="post_type" name="post_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="Lost" <?= $post['post_type'] == 'Lost' ? 'selected' : '' ?>>Lost</option>
                    <option value="Found" <?= $post['post_type'] == 'Found' ? 'selected' : '' ?>>Found</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>

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

            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700">Location Reported</label>
                <select id="location" name="location" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="" disabled>Select a location</option>
                    <option value="1 No. Gate Area" <?= $post['location_reported'] == '1 No. Gate Area' ? 'selected' : '' ?>>1 No. Gate Area</option>
                    <option value="2 No. Gate Area" <?= $post['location_reported'] == '2 No. Gate Area' ? 'selected' : '' ?>>2 No. Gate Area</option>
                    <option value="Zero Point" <?= $post['location_reported'] == 'Zero Point' ? 'selected' : '' ?>>Zero Point</option>
                    <option value="Shaheed Minar" <?= $post['location_reported'] == 'Shaheed Minar' ? 'selected' : '' ?>>Shaheed Minar</option>
                    <option value="Central Library" <?= $post['location_reported'] == 'Central Library' ? 'selected' : '' ?>>Central Library</option>
                    <option value="Gymnasium" <?= $post['location_reported'] == 'Gymnasium' ? 'selected' : '' ?>>Gymnasium</option>
                    <option value="Botanical Garden" <?= $post['location_reported'] == 'Botanical Garden' ? 'selected' : '' ?>>Botanical Garden</option>
                    <option value="Chittagong University Medical Center" <?= $post['location_reported'] == 'Chittagong University Medical Center' ? 'selected' : '' ?>>Chittagong University Medical Center</option>
                    <option value="Jamal Nazrul Islam Research Centre" <?= $post['location_reported'] == 'Jamal Nazrul Islam Research Centre' ? 'selected' : '' ?>>Jamal Nazrul Islam Research Centre</option>
                    <option value="Institute of Forestry and Environmental Sciences" <?= $post['location_reported'] == 'Institute of Forestry and Environmental Sciences' ? 'selected' : '' ?>>Institute of Forestry and Environmental Sciences</option>
                    <option value="Faculty of Engineering" <?= $post['location_reported'] == 'Faculty of Engineering' ? 'selected' : '' ?>>Faculty of Engineering</option>
                    <option value="Faculty of Science" <?= $post['location_reported'] == 'Faculty of Science' ? 'selected' : '' ?>>Faculty of Science</option>
                    <option value="Faculty of Biological Science" <?= $post['location_reported'] == 'Faculty of Biological Science' ? 'selected' : '' ?>>Faculty of Biological Science</option>
                    <option value="Institute of Marine Science" <?= $post['location_reported'] == 'Institute of Marine Science' ? 'selected' : '' ?>>Institute of Marine Science</option>
                    <option value="Faculty of Social Sciences" <?= $post['location_reported'] == 'Faculty of Social Sciences' ? 'selected' : '' ?>>Faculty of Social Sciences</option>
                    <option value="Faculty of Business Administration" <?= $post['location_reported'] == 'Faculty of Business Administration' ? 'selected' : '' ?>>Faculty of Business Administration</option>
                    <option value="Faculty of Law" <?= $post['location_reported'] == 'Faculty of Law' ? 'selected' : '' ?>>Faculty of Law</option>
                    <option value="Alaol Hall" <?= $post['location_reported'] == 'Alaol Hall' ? 'selected' : '' ?>>Alaol Hall</option>
                    <option value="A. F. Rahman Hall" <?= $post['location_reported'] == 'A. F. Rahman Hall' ? 'selected' : '' ?>>A. F. Rahman Hall</option>
                    <option value="Shahjalal Hall" <?= $post['location_reported'] == 'Shahjalal Hall' ? 'selected' : '' ?>>Shahjalal Hall</option>
                    <option value="Suhrawardy Hall" <?= $post['location_reported'] == 'Suhrawardy Hall' ? 'selected' : '' ?>>Suhrawardy Hall</option>
                    <option value="Shah Amanat Hall" <?= $post['location_reported'] == 'Shah Amanat Hall' ? 'selected' : '' ?>>Shah Amanat Hall</option>
                    <option value="Shamsun Nahar Hall" <?= $post['location_reported'] == 'Shamsun Nahar Hall' ? 'selected' : '' ?>>Shamsun Nahar Hall</option>
                    <option value="Shaheed Abdur Rab Hall" <?= $post['location_reported'] == 'Shaheed Abdur Rab Hall' ? 'selected' : '' ?>>Shaheed Abdur Rab Hall</option>
                    <option value="Pritilata Hall" <?= $post['location_reported'] == 'Pritilata Hall' ? 'selected' : '' ?>>Pritilata Hall</option>
                    <option value="Deshnetri Begum Khaleda Zia Hall" <?= $post['location_reported'] == 'Deshnetri Begum Khaleda Zia Hall' ? 'selected' : '' ?>>Deshnetri Begum Khaleda Zia Hall</option>
                    <option value="Masterda Suriya Sen Hall" <?= $post['location_reported'] == 'Masterda Suriya Sen Hall' ? 'selected' : '' ?>>Masterda Suriya Sen Hall</option>
                    <option value="Bangabandhu Sheikh Mujibur Rahman Hall" <?= $post['location_reported'] == 'Bangabandhu Sheikh Mujibur Rahman Hall' ? 'selected' : '' ?>>Bangabandhu Sheikh Mujibur Rahman Hall</option>
                    <option value="Janonetri Sheikh Hasina Hall" <?= $post['location_reported'] == 'Janonetri Sheikh Hasina Hall' ? 'selected' : '' ?>>Janonetri Sheikh Hasina Hall</option>
                    <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall" <?= $post['location_reported'] == 'Bangamata Sheikh Fazilatunnesa Mujib Hall' ? 'selected' : '' ?>>Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                    <option value="Artist Rashid Chowdhury Hostel" <?= $post['location_reported'] == 'Artist Rashid Chowdhury Hostel' ? 'selected' : '' ?>>Artist Rashid Chowdhury Hostel</option>
                    <option value="Atish Dipangkar Srigyan Hall" <?= $post['location_reported'] == 'Atish Dipangkar Srigyan Hall' ? 'selected' : '' ?>>Atish Dipangkar Srigyan Hall</option>
                    <option value="Chittagong University School & College" <?= $post['location_reported'] == 'Chittagong University School & College' ? 'selected' : '' ?>>Chittagong University School & College</option>
                </select>
            </div>
 
            <div class="mb-6">
                <label for="item_status" class="block text-sm font-medium text-gray-700">Item Status</label>
                <select id="item_status" name="item_status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="Pending" <?= $post['item_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Resolved" <?= $post['item_status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="item_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="item_description" name="item_description" rows="4" required placeholder="Provide details about the item" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"><?= htmlspecialchars($post['item_description']) ?></textarea>
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                <?php if (!empty($post['image_path'])): ?>
                    <div class="mb-4">
                        <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Current Image" class="w-32 h-32 object-cover">
                        <p class="text-sm text-gray-500">Current Image</p>
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-900">
                <p class="text-sm text-gray-500">Leave empty to keep the current image.</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:opacity-90">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php include 'templates/footer.php'; ?>


















          

            

            

            

            <!-- Date of Incident -->
            <div class="mb-6">
                <label for="date_reported" class="block text-sm font-medium text-gray-700">Date of Incident</label>
                <input type="date" id="date_reported" name="date_reported" value="<?= htmlspecialchars($post['date_reported']) ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
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