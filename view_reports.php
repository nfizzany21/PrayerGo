<?php
require_once 'config.php';
require_once 'menu.php'; 

// Fetch all reports from the database
$query = "SELECT * FROM location_details ORDER BY report_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">PrayerGo Admin</a>
    <div class="d-flex text-white align-items-center">
        <span class="me-3">Hi, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?></span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Reported Locations (Full Database)</h5>
            <a href="index.php" class="btn btn-sm btn-outline-secondary">Back to Dashboard</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Place Name</th>
                            <th>Reporter</th>
                            <th>Date/Time</th>
                            <th>GPS (Lat, Long)</th>
                            <th>User Agent (Device)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reports) > 0): ?>
                            <?php foreach ($reports as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td>
                                        <span class="fw-bold"><?= htmlspecialchars($row['location_name']) ?></span><br>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($row['type']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['reporter_name']) ?></td>
                                    <td><?= htmlspecialchars($row['report_date']) ?></td>
                                    <td>
                                        <small><?= htmlspecialchars($row['latitude']) ?>, <?= htmlspecialchars($row['longitude']) ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($row['user_agent']) ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>