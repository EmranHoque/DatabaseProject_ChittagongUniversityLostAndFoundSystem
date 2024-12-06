<?php
// Include database connection
include 'includes/db.php';
include 'templates/header.php';

// Fetch general stats
$statsQuery = $pdo->query("
    SELECT 
        SUM(CASE WHEN post_type = 'Lost' THEN 1 ELSE 0 END) AS total_lost,
        SUM(CASE WHEN post_type = 'Found' THEN 1 ELSE 0 END) AS total_found,
        SUM(CASE WHEN item_status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
        SUM(CASE WHEN item_status = 'Pending' THEN 1 ELSE 0 END) AS unresolved
    FROM post
");
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

// Fetch all locations and their post counts
$locationQuery = $pdo->query("
    SELECT location_reported, COUNT(*) as post_count
    FROM post
    GROUP BY location_reported
    ORDER BY post_count DESC
");
$locations = $locationQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch post trends
$trendsQuery = $pdo->query("
    SELECT DATE(date_reported) as report_date, COUNT(*) as post_count
    FROM post
    GROUP BY DATE(date_reported)
    ORDER BY report_date ASC
");
$trends = $trendsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
        }
        .elegant-card {
            @apply bg-white rounded-3xl shadow-[0_15px_40px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-[0_15px_40px_rgba(0,0,0,0.12)];
        }
        .card-header {
            @apply px-6 py-4 border-b border-gray-100 bg-gray-50;
        }
        .section-title {
            @apply text-xl font-semibold text-gray-800;
        }
        .section-divider {
            @apply border-t border-gray-200 my-12 border-dashed;
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <div class="container mx-auto px-6 py-12 max-w-screen-xl">
        <header class="text-center mb-16">
            <h1 class="text-5xl font-light text-gray-900 mb-4">Lost & Found Analytics</h1>
            <p class="text-xl text-gray-500 font-light max-w-2xl mx-auto">
                Comprehensive insights into item tracking, resolution status, and reporting trends
            </p>
        </header>

        <!-- Quick Stats and Pie Chart Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Stats Grid -->
            <div class="elegant-card">
                <div class="card-header">
                    <h2 class="section-title">Quick Overview</h2>
                </div>
                <div class="p-8 grid grid-cols-2 gap-6">
                    <div class="text-center bg-yellow-50 p-4 rounded-xl">
                        <div class="text-sm uppercase tracking-wide text-yellow-600 mb-2">Lost Items</div>
                        <div class="text-4xl font-light text-yellow-800"><?= htmlspecialchars($stats['total_lost']) ?></div>
                    </div>
                    <div class="text-center bg-blue-50 p-4 rounded-xl">
                        <div class="text-sm uppercase tracking-wide text-blue-600 mb-2">Found Items</div>
                        <div class="text-4xl font-light text-blue-800"><?= htmlspecialchars($stats['total_found']) ?></div>
                    </div>
                    <div class="text-center bg-green-50 p-4 rounded-xl">
                        <div class="text-sm uppercase tracking-wide text-green-600 mb-2">Resolved</div>
                        <div class="text-4xl font-light text-green-800"><?= htmlspecialchars($stats['resolved']) ?></div>
                    </div>
                    <div class="text-center bg-red-50 p-4 rounded-xl">
                        <div class="text-sm uppercase tracking-wide text-red-600 mb-2">Pending</div>
                        <div class="text-4xl font-light text-red-800"><?= htmlspecialchars($stats['unresolved']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="elegant-card">
                <div class="card-header">
                    <h2 class="section-title">Case Resolution Status</h2>
                </div>
                <div class="p-8 flex justify-center items-center">
                    <canvas id="pieChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="section-divider"></div>

        <!-- Post Trends Section -->
        <div class="elegant-card mb-12">
            <div class="card-header">
                <h2 class="section-title">Post Trends Over Time</h2>
            </div>
            <div class="p-8">
                <canvas id="lineChart" height="300"></canvas>
            </div>
        </div>

        <!-- Divider -->
        <div class="section-divider"></div>

        <!-- Location Analysis Section -->
        <div class="elegant-card mb-12">
            <div class="card-header">
                <h2 class="section-title">Location Post Distribution</h2>
            </div>
            <div class="p-8">
                <canvas id="barChart" height="400"></canvas>
            </div>
        </div>

        <!-- Detailed Location List -->
        <div class="elegant-card">
            <div class="card-header">
                <h2 class="section-title">Detailed Location Analysis</h2>
            </div>
            <div class="p-8">
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
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-3"><?= htmlspecialchars($location['location_reported']) ?></td>
                                <td class="py-3 text-right"><?= htmlspecialchars($location['post_count']) ?></td>
                                <td class="py-3 text-right"><?= $percentage ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Pie Chart Configuration
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
                maintainAspectRatio: true,
                aspectRatio: 1.2,
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

        // Line Chart Configuration
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

        // Horizontal Bar Chart Configuration
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($locations, 'location_reported')) ?>,
                datasets: [{
                    label: 'Number of Posts',
                    data: <?= json_encode(array_column($locations, 'post_count')) ?>,
                    backgroundColor: '#F97316',
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
</body>
</html>

