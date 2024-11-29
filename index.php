<?php 
session_start(); 


$loggedIn = isset($_SESSION['user_id']) ? true : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CU Lost & Found</title>
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
                    <?php if ($loggedIn): ?>
                        <!-- <a href="create_post.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">Create Post</a> -->
                        <!-- <a href="posts.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">Browse Posts</a> -->
                        <!-- <a href="my_posts.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">My Posts</a> -->
                        <a href="my_profile.php" class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300">My Profile</a>
                        <a href="logout.php" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-80 transition duration-300">Log Out</a>
                    <?php else: ?>
                        <!-- <a href="login.php" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-80 transition duration-300">Login</a> -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-20">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Lost Something on Campus?</span>
                    <span class="block text-purple-700 gradient-text">We're Here to Help</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-600 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    The official lost and found system for our university community. Report lost or found items, and find what you're looking for with ease.
                </p>
                <div class="mt-8 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <?php if (!$loggedIn): ?>
                        <div class="rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="login.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 transition duration-300 md:py-4 md:text-lg md:px-10">
                                Login to Post and Browse
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="create_post.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 transition duration-300 md:py-4 md:text-lg md:px-10">
                                Create Post
                            </a>
                        </div>
                        <div class="rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="posts.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-purple-600 bg-white hover:bg-gray-50 transition duration-300 md:py-4 md:text-lg md:px-10">
                                Browse Posts
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8">How It Works</h2>
            <p class="text-lg text-gray-600 mb-4">Easily report and find lost items through our simple and secure platform. Here's how:</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-purple-600">1. Report Lost or Found Items</h3>
                    <p class="mt-2 text-gray-600">Use our platform to post details about the item you lost or found.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-purple-600">2. Browse Posts</h3>
                    <p class="mt-2 text-gray-600">Browse through posts to find what you need.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-purple-600">3. Connect and Reclaim</h3>
                    <p class="mt-2 text-gray-600">Once you find a match, you can contact the person who found your item and arrange to get it back.</p>
                </div>
            </div>
        </div>
    </section>

   <!-- Footer -->
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- About Us -->
            <div>
                <h3 class="text-xl font-bold">About Us</h3>
                <p class="mt-4 text-gray-400">
                    Chittagong University Lost and Found System is a web-based platform designed to help the university community manage lost and found items effectively. Our mission is to provide a centralized system to report lost or found items, browse through the posts and get the belongings to their rightful owners.
                </p>
            </div>

            <!-- Contact Us -->
            <div>
                <h3 class="text-xl font-bold">Contact Us</h3>
                <p class="mt-4 text-gray-400">Have questions or need help? Reach out to us:</p>
                <p class="mt-2">
                    <a href="mailto:cu_lostandfound@gmail.com" class="text-purple-400 hover:text-purple-600">
                        cu_lostandfound@gmail.com 
                    </a>
                </p>
                <p class="mt-2 text-gray-400">Phone: (+88) 01881-726226</p>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-t border-gray-700 mt-8 pt-4 text-center">
            <p class="text-sm">Developed by Md. Emranul Hoque.</p>
            <p class="text-sm">© 2024 CU Lost & Found. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
