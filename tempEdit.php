<?php
session_start(); // Start session

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'includes/db.php';
include 'templates/header.php';

// Default timeline filter
$timeRange = isset($_GET['range']) ? $_GET['range'] : 'all';

$dateCondition = '';
if ($timeRange === '7days') {
    $dateCondition = "WHERE date_reported >= NOW() - INTERVAL 7 DAY";
} elseif ($timeRange === '30days') {
    $dateCondition = "WHERE date_reported >= NOW() - INTERVAL 30 DAY";
}

// Updated queries with timeline filters
$statsQuery = $pdo->query("
    SELECT 
        SUM(CASE WHEN post_type = 'Lost' THEN 1 ELSE 0 END) AS total_lost,
        SUM(CASE WHEN post_type = 'Found' THEN 1 ELSE 0 END) AS total_found,
        SUM(CASE WHEN item_status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
        SUM(CASE WHEN item_status = 'Pending' THEN 1 ELSE 0 END) AS unresolved
    FROM post
    $dateCondition
");
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

$locationQuery = $pdo->query("
    SELECT location_reported, COUNT(*) as post_count
    FROM post
    $dateCondition
    GROUP BY location_reported
    ORDER BY post_count DESC
");
$locations = $locationQuery->fetchAll(PDO::FETCH_ASSOC);

$trendsQuery = $pdo->query("
    SELECT DATE(date_reported) as report_date, COUNT(*) as post_count
    FROM post
    $dateCondition
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="background-color: #f4f6f9; font-family: Arial, sans-serif;">

<div style="max-width: 1200px; margin: auto; padding: 20px;">
    <!-- Header -->
    <header style="text-align: center; margin-bottom: 20px;">
        <h1 style="font-size: 2em; font-weight: bold; color: #333;">System Analytics</h1>
        <p style="color: #666;">Insights into item tracking, resolution status, and reporting trends</p>
        <a href="generate_pdf.php" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Download PDF</a>
    </header>

    <!-- Timeline Filter -->
    <div style="text-align: center; margin-bottom: 20px;">
        <form method="GET" action="">
            <label for="range" style="font-weight: bold;">Show data for:</label>
            <select name="range" id="range" style="padding: 5px; margin-left: 10px;">
                <option value="all" <?= $timeRange === 'all' ? 'selected' : '' ?>>All Time</option>
                <option value="7days" <?= $timeRange === '7days' ? 'selected' : '' ?>>Past 7 Days</option>
                <option value="30days" <?= $timeRange === '30days' ? 'selected' : '' ?>>Past 30 Days</option>
            </select>
            <button type="submit" style="padding: 5px 10px; background-color: #007bff; color: white; border: none; border-radius: 5px;">Apply</button>
        </form>
    </div>
</div>
<div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">

    <!-- Resolved and Unresolved Grids -->
    <div style="flex: 1; max-width: 300px; background-color: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 1.5em; color: #28a745;">Resolved Cases</h2>
        <p style="font-size: 2em; font-weight: bold; color: #333;"><?= $stats['resolved'] ?></p>
    </div>

    <div style="flex: 1; max-width: 300px; background-color: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 1.5em; color: #dc3545;">Unresolved Cases</h2>
        <p style="font-size: 2em; font-weight: bold; color: #333;"><?= $stats['unresolved'] ?></p>
    </div>

    <!-- Pie Chart -->
    <div style="flex: 1; max-width: 600px; background-color: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 1.5em; color: #007bff;">Case Breakdown</h2>
        <canvas id="pieChart" style="max-width: 100%; height: 300px;"></canvas>
    </div>
</div>
<div style="margin-top: 40px; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 1.5em; color: #333;">Top 5 Most-Reported Locations</h2>
    <ul style="list-style: none; padding: 0; margin: 0;">
        <?php foreach ($locations as $index => $location): ?>
            <?php if ($index < 5): ?>
                <li style="padding: 10px; border-bottom: 1px solid #f0f0f0;">
                    <strong><?= htmlspecialchars($location['location_reported']) ?></strong>
                    <span style="float: right;"><?= $location['post_count'] ?> reports</span>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<script>
    // Pie chart for resolved vs unresolved cases
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Resolved', 'Unresolved'],
            datasets: [{
                data: [<?= $stats['resolved'] ?>, <?= $stats['unresolved'] ?>],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
</script>
<div style="margin-top: 40px; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 1.5em; color: #333;">Incident Trends Over Time</h2>
    <canvas id="lineChart" style="max-width: 100%; height: 300px;"></canvas>
</div>

<script>
    // Line chart for incident trends
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: [<?= implode(',', array_map(fn($t) => '"' . $t['report_date'] . '"', $trends)) ?>],
            datasets: [{
                label: 'Posts',
                data: [<?= implode(',', array_column($trends, 'post_count')) ?>],
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: '#007bff',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Number of Reports' } }
            }
        }
    });
</script>
</body>
</html>
