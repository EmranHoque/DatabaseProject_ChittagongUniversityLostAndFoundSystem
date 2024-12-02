<?php
require 'includes/db.php';

// Fetch top reported locations
$sql = "SELECT location_reported, COUNT(*) AS total_reports 
        FROM post 
        GROUP BY location_reported 
        ORDER BY total_reports DESC 
        LIMIT 5";
$stmt = $pdo->query($sql);
$locations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Red Zones</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto py-12 px-4">
        <h1 class="text-3xl font-bold mb-8">Red Zones</h1>
        <table class="table-auto w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-red-600 text-white">
                    <th class="px-4 py-2">Location</th>
                    <th class="px-4 py-2">Reports</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($location['location_reported']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($location['total_reports']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
