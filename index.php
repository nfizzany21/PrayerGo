<?php
require_once 'config.php';
require_once 'menu.php'; 

// 1. Fetch Types and States for Dropdowns
$types_stmt = $pdo->query("SELECT DISTINCT type FROM location_details ORDER BY type ASC");
$types = $types_stmt->fetchAll(PDO::FETCH_COLUMN);

$states_stmt = $pdo->query("SELECT DISTINCT state_name FROM location_details WHERE state_name IS NOT NULL AND state_name != '' ORDER BY state_name ASC");
$states = $states_stmt->fetchAll(PDO::FETCH_COLUMN);

// 2. Build Query
$query = "SELECT * FROM location_details WHERE 1=1";
$params = [];

// Search (General)
if (!empty($_GET['search'])) {
    $query .= " AND (location_name LIKE :search OR city_name LIKE :search OR reporter_name LIKE :search)";
    $params[':search'] = "%" . $_GET['search'] . "%";
}

// Filter by Type
if (!empty($_GET['type'])) {
    $query .= " AND type = :type";
    $params[':type'] = $_GET['type'];
}

// Filter by State
if (!empty($_GET['state'])) {
    $query .= " AND state_name = :state";
    $params[':state'] = $_GET['state'];
}

// 3. Sorting Logic (Updated)
$sort_order = $_GET['sort'] ?? 'id_desc'; // Default to Descending ID

switch ($sort_order) {
    case 'id_asc':
        $query .= " ORDER BY id ASC"; 
        break;
    case 'id_desc':
    default:
        $query .= " ORDER BY id DESC"; 
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$all_locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$api_query = http_build_query($_GET); 
$api_url = "api.php" . (!empty($api_query) ? "?" . $api_query : "");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .table td { vertical-align: middle; }
        .user-agent-col {
            min-width: 200px;
            max-width: 300px;
            font-size: 0.75rem;
            word-wrap: break-word;
            white-space: normal !important;
            color: #555;
        }
    </style>
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

<div class="container-fluid px-4">
    
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search Name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>" <?= (isset($_GET['type']) && $_GET['type'] == $t) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="state" class="form-select">
                            <option value="">All States</option>
                            <?php foreach ($states as $s): ?>
                                <option value="<?= htmlspecialchars($s) ?>" <?= (isset($_GET['state']) && $_GET['state'] == $s) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="sort" class="form-select bg-light border-secondary">
                            <option value="id_desc" <?= ($sort_order == 'id_desc') ? 'selected' : '' ?>>Oldest</option>
                            <option value="id_asc" <?= ($sort_order == 'id_asc') ? 'selected' : '' ?>>Newest</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">Reset Filters</a>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Apply Filters & Sort</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Current Locations</h5>
            <div>
                <a href="<?= $api_url ?>" target="_blank" class="btn btn-sm btn-outline-dark me-2">
                    <i class="bi bi-filetype-json"></i> JSON API
                </a>
                <a href="create.php" class="btn btn-sm btn-success">+ Add New</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th> <th>Name</th> <th>Type</th> <th>City / State</th>
                            <th>Lat/Long</th> <th>Reporter</th> <th>Date/Time</th> <th>User Agent</th> 
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($all_locations) > 0): ?>
                            <?php foreach ($all_locations as $row): ?>
                                <tr>
                                    <td><small class="fw-bold"><?= htmlspecialchars($row['id']) ?></small></td>
                                    <td class="fw-bold"><?= htmlspecialchars($row['location_name']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['type']) ?></span></td>
                                    <td><?= htmlspecialchars($row['city_name']) ?><br><small class="text-muted"><?= htmlspecialchars($row['state_name']) ?></small></td>
                                    <td><small class="font-monospace"><?= htmlspecialchars($row['latitude']) ?>, <br><?= htmlspecialchars($row['longitude']) ?></small></td>
                                    <td><?= htmlspecialchars($row['reporter_name']) ?></td>
                                    <td><small><?= htmlspecialchars($row['report_date']) ?></small></td>
                                    <td class="user-agent-col"><?= htmlspecialchars($row['user_agent']) ?></td>
                                    <td class="text-end" style="white-space: nowrap;">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil-square"></i></a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center py-4 text-muted">No locations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>