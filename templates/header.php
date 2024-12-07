<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <title>Chittagong University Lost & Found</title>
</head>
<body>
    <nav class="bg-white custom-shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-purple-600 text-xl font-bold gradient-text">Chittagong University Lost & Found</span>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" 
                       class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300 <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'text-purple-600 font-bold' : ''; ?>">
                        Home
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="create_post.php" 
                           class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300 <?php echo ($_SERVER['PHP_SELF'] == '/create_post.php') ? 'text-purple-600 font-bold' : ''; ?>">
                            Create Post
                        </a>
                        <a href="posts.php" 
                           class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300 <?php echo ($_SERVER['PHP_SELF'] == '/posts.php') ? 'text-purple-600 font-bold' : ''; ?>">
                            Browse Posts
                        </a>
                        <a href="analytics.php" 
                           class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300 <?php echo ($_SERVER['PHP_SELF'] == '/analytics.php') ? 'text-purple-600 font-bold' : ''; ?>">
                            Analytics
                        </a>
                        <a href="my_profile.php" 
                           class="text-gray-700 hover:text-purple-600 px-3 py-2 transition duration-300 <?php echo ($_SERVER['PHP_SELF'] == '/my_profile.php') ? 'text-purple-600 font-bold' : ''; ?>">
                            My Profile
                        </a>
                        <a href="logout.php" 
                           class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-80 transition duration-300">
                            Log Out
                        </a>
                    <?php else: ?>
                        <a href="login.php" 
                           class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-80 transition duration-300">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
