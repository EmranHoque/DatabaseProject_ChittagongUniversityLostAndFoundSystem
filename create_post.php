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
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // Directory to store uploaded images
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = uniqid('img_', true) . '.' . $file_ext; // Unique file name
            $destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $destination)) {
                $image_path = $destination;
            } else {
                $error = "Error uploading the image.";
            }
        } else {
            $error = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.";
        }
    }

    
    $sql = "INSERT INTO post (title, location_reported, date_reported, item_description, post_type, item_status, category_id, user_id, image_path)
            VALUES (:title, :location, :date_reported, :item_description, :post_type, :item_status, :category_id, :user_id, :image_path)";
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
            'user_id' => $user_id,
            'image_path' => $image_path
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
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="flex justify-center mb-8">
            <h1 class="text-5xl font-extrabold text-gray-900">Create a Post</h1>
        </div>
        <form action="create_post.php" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md">
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter a brief title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="post_type" class="block text-sm font-medium text-gray-700">Post Type</label>
                <select id="post_type" name="post_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="Lost">Lost</option>
                    <option value="Found">Found</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="date_reported" class="block text-sm font-medium text-gray-700">Date of Incident</label>
                <input type="date" id="date_reported" name="date_reported" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>
            
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700">Location Reported</label>
                <select id="location" name="location" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="" disabled selected>Select a location</option>
                    <option value="1 No. Gate Area">1 No. Gate Area</option>
                    <option value="2 No. Gate Area">2 No. Gate Area</option>
                    <option value="Zero Point">Zero Point</option>
                    <option value="Shaheed Minar">Shaheed Minar</option>
                    <option value="Central Library">Central Library</option>
                    <option value="Gymnasium">Gymnasium</option>
                    <option value="Botanical Garden">Botanical Garden</option>
                    <option value="Chittagong University Medical Center">Chittagong University Medical Center</option>
                    <option value="Jamal Nazrul Islam Research Centre">Jamal Nazrul Islam Research Centre</option>
                    <option value="Institute of Forestry and Environmental Sciences">Institute of Forestry and Environmental Sciences</option>
                    <option value="Faculty of Engineering">Faculty of Engineering</option>
                    <option value="Faculty of Science">Faculty of Science</option>
                    <option value="Faculty of Biological Science">Faculty of Biological Science</option>
                    <option value="Institute of Marine Science">Institute of Marine Science</option>
                    <option value="Faculty of Social Sciences">Faculty of Social Sciences</option>
                    <option value="Faculty of Business Administration">Faculty of Business Administration</option>
                    <option value="Faculty of Law">Faculty of Law</option>
                    <option value="Alaol Hall">Alaol Hall</option>
                    <option value="A. F. Rahman Hall">A. F. Rahman Hall</option>
                    <option value="Shahjalal Hall">Shahjalal Hall</option>
                    <option value="Suhrawardy Hall">Suhrawardy Hall</option>
                    <option value="Shah Amanat Hall">Shah Amanat Hall</option>
                    <option value="Shamsun Nahar Hall">Shamsun Nahar Hall</option>
                    <option value="Shaheed Abdur Rab Hall">Shaheed Abdur Rab Hall</option>
                    <option value="Pritilata Hall">Pritilata Hall</option>
                    <option value="Deshnetri Begum Khaleda Zia Hall">Deshnetri Begum Khaleda Zia Hall</option>
                    <option value="Masterda Suriya Sen Hall">Masterda Suriya Sen Hall</option>
                    <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall</option>
                    <option value="Janonetri Sheikh Hasina Hall">Janonetri Sheikh Hasina Hall</option>
                    <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall">Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                    <option value="Artist Rashid Chowdhury Hostel">Artist Rashid Chowdhury Hostel</option>
                    <option value="Atish Dipangkar Srigyan Hall">Atish Dipangkar Srigyan Hall</option>
                    <option value="Chittagong University School & College">Chittagong University School & College</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="item_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="item_description" name="item_description" rows="4" required placeholder="Provide details about the item" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-gray-600 sm:text-sm">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php include 'templates/footer.php'; ?>





                    
