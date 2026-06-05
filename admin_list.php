<?php
require_once 'config.php';
require_once 'menu.php';

$query = "SELECT id, full_name, email, gender, created_at FROM admin WHERE 1=1";
$params = [];


if (!empty($_GET['search'])) {
    $query .= " AND (full_name LIKE :search OR email LIKE :search)";
    $params[':search'] = "%" . $_GET['search'] . "%";
}

if (!empty($_GET['gender'])) {
    $query .= " AND gender = :gender";
    $params[':gender'] = $_GET['gender'];
}

$query .= " ORDER BY created_at DESC";

// Execute Query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <div class="d-flex text-white align-items-center">
        <span class="me-3">Hi, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?></span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container">

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search name or email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="gender" class="form-select">
                            <option value="">All Genders</option>
                            <option value="Male" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="admin_list.php" class="btn btn-outline-secondary">Reset</a> 
                        </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Admin List</h5>
            <div>
                 <a href="register.php" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-lg"></i> Add Admin
                 </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($admins) > 0): ?>
                            <?php foreach ($admins as $a): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($a['full_name']) ?></td>
                                    <td><?= htmlspecialchars($a['email']) ?></td>
                                    <td>
                                        <?php 
                                            
                                            $badgeColor = ($a['gender'] == 'Male') ? 'bg-primary' : (($a['gender'] == 'Female') ? 'bg-danger' : 'bg-secondary');
                                        ?>
                                        <span class="badge <?= $badgeColor ?>"><?= htmlspecialchars($a['gender']) ?></span>
                                    </td>
                                    <td><small class="text-muted"><?= $a['created_at'] ?></small></td>
                                    
                                    <td class="text-end" style="white-space: nowrap;">
                                        <a href="admin_edit.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="admin_delete.php?id=<?= $a['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Delete this admin?')"
                                           title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No admins found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>