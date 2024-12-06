<?php 
session_start(); 
include 'templates/header.php';
require 'includes/db.php'; // Include database connection

$loggedIn = isset($_SESSION['user_id']) ? true : false;

// Fetch analytics data
$sql = "SELECT 
            COUNT(CASE WHEN post_type = 'Lost' THEN 1 END) AS total_lost,
            COUNT(CASE WHEN post_type = 'Found' THEN 1 END) AS total_found,
            COUNT(CASE WHEN item_status = 'resolved' THEN 1 END) AS resolved,
            COUNT(CASE WHEN item_status != 'resolved' THEN 1 END) AS unresolved
        FROM post";
$stmt = $pdo->query($sql);
$stats = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-gray-200">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-16">
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
    <section class="bg-gray-150">
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

    <!-- Analytics Section -->
    <section class="py-12 bg-gray">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">System Analytics</h2>

            <!-- Top Section: Grids and Pie Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- 4x4 Grids for Post Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-yellow-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Total Lost</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['total_lost']) ?></p>
                    </div>
                    <div class="bg-blue-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Total Found</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['total_found']) ?></p>
                    </div>
                    <div class="bg-green-300 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Resolved</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['resolved']) ?></p>
                    </div>
                    <div class="bg-red-300 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-lg font-semibold">Pending</h3>
                        <p class="text-3xl font-bold"><?= htmlspecialchars($stats['unresolved']) ?></p>
                    </div>
                </div>

                <!-- Pie Chart for Post Status -->
                <div>
                    <canvas id="pieChart" width="300" height="300" class="mx-auto"></canvas>
                </div>
            </div>

            <!-- Bottom Section: Bar Chart Only -->
            <div class="mt-12">
                <!-- Horizontal Bar Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Location Analysis</h3>
                    <div class="overflow-hidden">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart.js for Graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart: Resolved vs Unresolved
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Resolved', 'Pending'],
                datasets: [{
                    data: [<?= htmlspecialchars($stats['resolved']) ?>, <?= htmlspecialchars($stats['unresolved']) ?>],
                    backgroundColor: ['#34D399', '#F87171'], // Resolved: Green, Unresolved: Red
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Horizontal Bar Chart: Post Distribution by Location
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barData = {
            labels: [<?php
                $locationNames = [];
                $postCounts = [];
                $locationSql = "SELECT location_reported, COUNT(*) as post_count 
                                FROM post 
                                GROUP BY location_reported 
                                ORDER BY post_count DESC";
                $locationStmt = $pdo->query($locationSql);
                while ($location = $locationStmt->fetch()) {
                    $locationNames[] = '"' . $location['location_reported'] . '"';
                    $postCounts[] = $location['post_count'];
                }
                echo implode(',', $locationNames);
            ?>],
            datasets: [{
                label: 'Number of Posts',
                data: [<?= implode(',', $postCounts) ?>],
                backgroundColor: '#F44336',
                borderWidth: 1
            }]
        };
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: barData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                indexAxis: 'y', // Horizontal bars
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Number of Posts'
                        },
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 5, // Limit the number of ticks on the x-axis for better legibility
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Locations'
                        }
                    }
                }
            }
        });
    </script>




</body>
</html>

<?php include 'templates/footer.php'; ?>























