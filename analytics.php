<?php
require 'templates/header.php'; // Include header
require 'includes/db.php'; // Include DB connection

// Fetch statistics from the database
try {
    // Total posts
    $totalPosts = $pdo->query("SELECT COUNT(*) AS total FROM post")->fetch()['total'];

    // Total lost posts
    $lostPosts = $pdo->query("SELECT COUNT(*) AS total FROM post WHERE post_type = 'Lost'")->fetch()['total'];

    // Total found posts
    $foundPosts = $pdo->query("SELECT COUNT(*) AS total FROM post WHERE post_type = 'Found'")->fetch()['total'];

    // Status distribution
    $pendingPosts = $pdo->query("SELECT COUNT(*) AS total FROM post WHERE item_status = 'Pending'")->fetch()['total'];
    $resolvedPosts = $pdo->query("SELECT COUNT(*) AS total FROM post WHERE item_status = 'Resolved'")->fetch()['total'];
} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit;
}
?>

<div class="dashboard-container" style="display: flex; gap: 2rem;">
    <!-- Left side: Cards -->
    <div class="cards-container" style="flex: 1;">
        <div class="card" style="padding: 1.5rem; border: 1px solid #ddd; margin-bottom: 1rem; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
            <h3>Total Posts</h3>
            <p><?= $totalPosts; ?></p>
        </div>
        <div class="card" style="padding: 1.5rem; border: 1px solid #ddd; margin-bottom: 1rem; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
            <h3>Lost Posts</h3>
            <p><?= $lostPosts; ?></p>
        </div>
        <div class="card" style="padding: 1.5rem; border: 1px solid #ddd; margin-bottom: 1rem; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
            <h3>Found Posts</h3>
            <p><?= $foundPosts; ?></p>
        </div>
        <div class="card" style="padding: 1.5rem; border: 1px solid #ddd; margin-bottom: 1rem; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
            <h3>Pending Posts</h3>
            <p><?= $pendingPosts; ?></p>
        </div>
    </div>

    <!-- Right side: Pie Chart -->
    <div class="chart-container" style="flex: 1;">
        <canvas id="postAnalyticsChart" width="250" height="250"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data for the chart
const ctx = document.getElementById('postAnalyticsChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Lost Posts', 'Found Posts', 'Pending Posts', 'Resolved Posts'],
        datasets: [{
            data: [<?= $lostPosts; ?>, <?= $foundPosts; ?>, <?= $pendingPosts; ?>, <?= $resolvedPosts; ?>],
            backgroundColor: ['#f39c12', '#27ae60', '#3498db', '#e74c3c'],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const total = <?= $totalPosts; ?>;
                        const value = tooltipItem.raw;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
});
</script>

<?php require 'templates/footer.php'; // Include footer ?>
