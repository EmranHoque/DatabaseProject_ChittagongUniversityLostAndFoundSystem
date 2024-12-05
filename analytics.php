<?php
// Include database connection
include 'includes/db.php';

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

// Fetch top 5 locations
$locationQuery = $pdo->query("
    SELECT location_reported, COUNT(*) as post_count
    FROM post
    GROUP BY location_reported
    ORDER BY post_count DESC
    LIMIT 5
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
    <title>Enhanced Analytics Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Inline styles for demonstration */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .stats-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .stats-card h3 {
            margin: 0;
            font-size: 1.2rem;
        }
        .stats-card p {
            font-size: 2.5rem;
            margin: 0;
            font-weight: bold;
        }
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }
        .chart-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .locations-list {
            list-style: none;
            padding: 0;
        }
        .locations-list li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">System Analytics Dashboard</h1>

        <!-- General Stats -->
        <div class="stats-grid">
            <div class="stats-card" style="background-color: #fef3c7;">
                <h3>Total Lost Items</h3>
                <p><?= htmlspecialchars($stats['total_lost']) ?></p>
            </div>
            <div class="stats-card" style="background-color: #bfdbfe;">
                <h3>Total Found Items</h3>
                <p><?= htmlspecialchars($stats['total_found']) ?></p>
            </div>
            <div class="stats-card" style="background-color: #d1fae5;">
                <h3>Resolved Cases</h3>
                <p><?= htmlspecialchars($stats['resolved']) ?></p>
            </div>
            <div class="stats-card" style="background-color: #fecaca;">
                <h3>Pending Cases</h3>
                <p><?= htmlspecialchars($stats['unresolved']) ?></p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="charts-container">
            <div class="chart-card">
                <h3>Post Trends Over Time</h3>
                <canvas id="lineChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Post Status Overview</h3>
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Location Analytics -->
        <div class="charts-container">
            <div class="chart-card">
                <h3>Top 5 Most-Reported Locations</h3>
                <canvas id="barChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Detailed Location Data</h3>
                <ul class="locations-list">
                    <?php foreach ($locations as $location): ?>
                        <li>
                            <span><?= htmlspecialchars($location['location_reported']) ?></span>
                            <span><?= htmlspecialchars($location['post_count']) ?> Posts</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Resolved', 'Pending'],
                datasets: [{
                    data: [<?= $stats['resolved'] ?>, <?= $stats['unresolved'] ?>],
                    backgroundColor: ['#10b981', '#ef4444']
                }]
            }
        });

        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($trends, 'report_date')) ?>,
                datasets: [{
                    label: 'Posts',
                    data: <?= json_encode(array_column($trends, 'post_count')) ?>,
                    borderColor: '#2563eb',
                    fill: false
                }]
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($locations, 'location_reported')) ?>,
                datasets: [{
                    label: 'Posts',
                    data: <?= json_encode(array_column($locations, 'post_count')) ?>,
                    backgroundColor: '#f97316'
                }]
            }
        });
    </script>
</body>
</html>
