<?php
session_start(); 
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'templates/header.php';

$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : 'all';
$dateFilterCondition = '';
switch($dateRange) {
    case '7days':
        $dateFilterCondition = "AND date_reported >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)";
        break;
    case '30days':
        $dateFilterCondition = "AND date_reported >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)";
        break;
    case '90days':
        $dateFilterCondition = "AND date_reported >= DATE_SUB(CURRENT_DATE, INTERVAL 90 DAY)";
        break;
    default:
        $dateFilterCondition = ""; // all time
}

$statsQuery = $pdo->query("
    SELECT 
        SUM(CASE WHEN post_type = 'Lost' THEN 1 ELSE 0 END) AS total_lost,
        SUM(CASE WHEN post_type = 'Found' THEN 1 ELSE 0 END) AS total_found,
        SUM(CASE WHEN item_status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
        SUM(CASE WHEN item_status = 'Pending' THEN 1 ELSE 0 END) AS unresolved
    FROM post WHERE 1=1 $dateFilterCondition

");
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

$locationQuery = $pdo->query("
    SELECT location_reported, COUNT(*) as post_count
    FROM post
    WHERE 1=1 $dateFilterCondition
    GROUP BY location_reported
    ORDER BY post_count DESC
");
$locations = $locationQuery->fetchAll(PDO::FETCH_ASSOC);

$trendsQuery = $pdo->query("
    SELECT DATE(date_reported) as report_date, COUNT(*) as post_count
    FROM post
    WHERE 1=1 $dateFilterCondition
    GROUP BY DATE(date_reported)
    ORDER BY report_date ASC
");
$trends = $trendsQuery->fetchAll(PDO::FETCH_ASSOC);

$categoryQuery = $pdo->query("
    SELECT c.category_name, 
           COUNT(*) as category_count,
           SUM(CASE WHEN p.post_type = 'Lost' THEN 1 ELSE 0 END) AS lost_count,
           SUM(CASE WHEN p.post_type = 'Found' THEN 1 ELSE 0 END) AS found_count
    FROM post p
    JOIN category c ON p.category_id = c.category_id
    WHERE 1=1 $dateFilterCondition
    GROUP BY c.category_name
    ORDER BY category_count DESC
");
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

$userActivityQuery = $pdo->query("
    SELECT u.name, 
           COUNT(*) as total_posts,
           SUM(CASE WHEN p.post_type = 'Lost' THEN 1 ELSE 0 END) AS lost_posts,
           SUM(CASE WHEN p.post_type = 'Found' THEN 1 ELSE 0 END) AS found_posts
    FROM post p
    JOIN user u ON p.user_id = u.user_id
    WHERE 1=1 $dateFilterCondition
    GROUP BY u.user_id, u.name
    ORDER BY total_posts DESC
    LIMIT 10
");
$userActivity = $userActivityQuery->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="bg-gray-200">
    <div class="container mx-auto px-6 py-12 max-w-screen-xl">
        <header class="text-center mb-16 bg-white py-4">
            <h1 class="text-6xl font-black text-gray-900 uppercase tracking-tighter mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700">
                Lost & Found Analytics
            </h1>
            <p class="text-2xl font-medium text-gray-700 max-w-2xl mx-auto leading-relaxed">
            Detailed analytics dashboard for lost and found management
            </p>
            <div class="mb-4">
                <select id="dateRangeSelector" class="mx-auto bg-gray-200 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-purple-800 focus:border-purple-800 ">
                    <option value="all" <?= $dateRange == 'all' ? 'selected' : '' ?>>All Time</option>
                    <option value="7days" <?= $dateRange == '7days' ? 'selected' : '' ?>>Last 7 Days</option>
                    <option value="30days" <?= $dateRange == '30days' ? 'selected' : '' ?>>Last 30 Days</option>
                    <option value="90days" <?= $dateRange == '90days' ? 'selected' : '' ?>>Last 90 Days</option>
                </select>
            </div>
            <script>
                document.getElementById('dateRangeSelector').addEventListener('change', function() {
                    window.location.href = window.location.pathname + '?dateRange=' + this.value;
                });
            </script>

            <!-- <a href="generate_pdf.php" class="bg-blue-500 text-white px-6 py-2 rounded">
                Download PDF
            </a> -->
        </header>

        <!-- Resolved vs. Pending Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 py-12 bg-gray-100 p-6 rounded-lg shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-yellow-300 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold">Total Lost</h3>
                    <p class="text-3xl font-bold"><?= htmlspecialchars($stats['total_lost']) ?></p>
                </div>
                <div class="bg-blue-300 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold">Total Found</h3>
                    <p class="text-3xl font-bold"><?= htmlspecialchars($stats['total_found']) ?></p>
                </div>
                <div class="bg-green-300 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold">Resolved</h3>
                    <p class="text-3xl font-bold"><?= htmlspecialchars($stats['resolved']) ?></p>
                </div>
                <div class="bg-red-300 p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-xl font-semibold">Pending</h3>
                    <p class="text-3xl font-bold"><?= htmlspecialchars($stats['unresolved']) ?></p>
                </div>
            </div>

            <div>
                <canvas id="pieChart" width="300" height="300" class="mx-auto"></canvas>
            </div>

            <script>
                const pieCtx = document.getElementById('pieChart').getContext('2d');
                new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Resolved', 'Pending'],
                        datasets: [{
                            data: [<?= $stats['resolved'] ?>, <?= $stats['unresolved'] ?>],
                            backgroundColor: ['#10b981', '#ef4444']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Resolution Status Breakdown'
                            }
                        }
                    }
                });
            </script>
        </div>

        <div style="border-top: 1px #e5e7eb; margin-top: 2rem; margin-bottom: 2rem;"></div>

        <!-- Post Trends Section -->
        <div class="mb-12 bg-gray-100 p-6 rounded-lg shadow-md">
            <div style="padding: 1.5rem; border-bottom: 1px solid #f3f4f6">
                <h2 class="section-title text-center font-semibold py-6 text-2xl">Post Trends Over Time</h2>
            </div>
            <div class="p-8">
                <canvas id="lineChart" height="300"></canvas>
            </div>
            <script>
                const lineCtx = document.getElementById('lineChart').getContext('2d');
                    new Chart(lineCtx, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode(array_column($trends, 'report_date')) ?>,
                            datasets: [{
                                label: 'Posts per Day',
                                data: <?= json_encode(array_column($trends, 'post_count')) ?>,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            aspectRatio: 2.5,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Posts'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Daily Post Volume'
                                }
                            }
                        }
                    });
            </script>
        </div>

        <div style="border-top: 1px #e5e7eb; margin-top: 2rem; margin-bottom: 2rem;"></div>

        <!-- Location Analysis Section -->
        <div style="bg-gray-100 p-6 rounded-lg shadow-md">
            <div style="bg-gray-100 p-6 rounded-lg shadow-md">
                <h2 class="section-title text-center font-semibold bg-gray-100 p-6 rounded-lg shadow-md text-2xl">Location Post Distribution</h2>
            </div>
            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <canvas id="barChart" height="400"></canvas>
            </div>
            <script>
                const barCtx = document.getElementById('barChart').getContext('2d');
                    new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode(array_column($locations, 'location_reported')) ?>,
                            datasets: [{
                                label: 'Number of Posts',
                                data: <?= json_encode(array_column($locations, 'post_count')) ?>,
                                backgroundColor: '#ef4444',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: true,
                            aspectRatio: 1.5,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Posts by Location'
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Posts'
                                    }
                                }
                            }
                        }
                    });
            </script>
        </div>

        <div style="border-top: 1px #e5e7eb; margin-top: 2rem; margin-bottom: 2rem;"></div>

        <!-- Detailed Location List Section -->
        <div style="bg-gray-100 p-6 rounded-lg shadow-md">
            <div style="bg-gray-100 p-6 rounded-lg shadow-md">
                <h2 class="section-title text-center font-semibold bg-gray-100 p-6 rounded-lg shadow-md text-2xl">Detailed Location Analysis</h2>
            </div>
            <div class="p-8 bg-gray-100 rounded-lg shadow-md">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="pb-3 text-gray-600">Location</th>
                            <th class="pb-3 text-gray-600 text-right">Posts</th>
                            <th class="pb-3 text-gray-600 text-right">% of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_posts = array_sum(array_column($locations, 'post_count'));
                        foreach ($locations as $location): 
                            $percentage = round(($location['post_count'] / $total_posts) * 100, 1);
                        ?>
                            <tr class="border-b hover:bg-purple-100 transition-colors">
                                <td class="py-3"><?= htmlspecialchars($location['location_reported']) ?></td>
                                <td class="py-3 text-right"><?= htmlspecialchars($location['post_count']) ?></td>
                                <td class="py-3 text-right"><?= $percentage ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="border-top: 1px #e5e7eb; margin-top: 2rem; margin-bottom: 2rem;"></div>

        <!-- Category Analysis Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-12 bg-gray-100 p-6 rounded-lg shadow-md">
            <div>
                <div style="">
                    <h2 class="section-title text-center font-semibold text-2xl">Category Distribution</h2>
                </div>
                <div class="p-6 flex justify-center items-center text-2xl">
                    <canvas id="categoryPieChart" width="400" height="300"></canvas>
                </div>
            </div>

            <div style="bg-gray-100 p-6 rounded-lg shadow-md">
                <div style="p-6">
                    <h2 class="section-title text-center font-semibold text-2xl">Category Breakdown</h2>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead class="border-b">
                            <tr>
                                <th class="pb-3 text-gray-600">Category</th>
                                <th class="pb-3 text-gray-600 text-right">Total Posts</th>
                                <th class="pb-3 text-gray-600 text-right">Lost</th>
                                <th class="pb-3 text-gray-600 text-right">Found</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="py-3"><?= htmlspecialchars($category['category_name']) ?></td>
                                    <td class="py-3 text-right"><?= $category['category_count'] ?></td>
                                    <td class="py-3 text-right"><?= $category['lost_count'] ?></td>
                                    <td class="py-3 text-right"><?= $category['found_count'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <script>
                const categoryPieCtx = document.getElementById('categoryPieChart').getContext('2d');
                    new Chart(categoryPieCtx, {
                        type: 'pie',
                        data: {
                            labels: <?= json_encode(array_column($categories, 'category_name')) ?>,
                            datasets: [{
                                data: <?= json_encode(array_column($categories, 'category_count')) ?>,
                                backgroundColor: [
                                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                    '#9966FF', '#FF9F40', '#E7E9ED', '#FF6384'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                title: { display: true, text: 'Posts by Category' }
                            }
                        }
                    });
            </script>
        </div>

        <div style="border-top: 1px #e5e7eb; margin-top: 2rem; margin-bottom: 2rem;"></div>

        <!-- User Activity Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-12 bg-gray-100 p-6 rounded-lg shadow-md">
            <div style="p-6">
                <div style="p-6">
                    <h2 class="section-title text-center font-semibold text-2xl">Top User Activity</h2>
                </div>
                <div class="p-6">
                    <canvas id="userActivityChart" height="400"></canvas>
                </div>
            </div>

            <div style="p-6">
                <div>
                    <h2 class="section-title text-center font-semibold text-2xl">User Post Details</h2>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead class="border-b">
                            <tr>
                                <th class="pb-3 text-gray-600">User</th>
                                <th class="pb-3 text-gray-600 text-right">Total Posts</th>
                                <th class="pb-3 text-gray-600 text-right">Lost Posts</th>
                                <th class="pb-3 text-gray-600 text-right">Found Posts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userActivity as $user): ?>
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="py-3"><?= htmlspecialchars($user['name']) ?></td>
                                    <td class="py-3 text-right"><?= $user['total_posts'] ?></td>
                                    <td class="py-3 text-right"><?= $user['lost_posts'] ?></td>
                                    <td class="py-3 text-right"><?= $user['found_posts'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
                new Chart(userActivityCtx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_column($userActivity, 'name')) ?>,
                        datasets: [
                            {
                                label: 'Lost Posts',
                                data: <?= json_encode(array_column($userActivity, 'lost_posts')) ?>,
                                backgroundColor: '#FF6384'
                            },
                            {
                                label: 'Found Posts',
                                data: <?= json_encode(array_column($userActivity, 'found_posts')) ?>,
                                backgroundColor: '#36A2EB'
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            title: { display: true, text: 'Top 10 Users by Post Activity' },
                            legend: { position: 'bottom' }
                        },
                        scales: {
                            x: { stacked: true, title: { display: true, text: 'Number of Posts' } },
                            y: { stacked: true }
                        }
                    }
                });
            </script>
        </div>
    </div>
</body>
</html>
