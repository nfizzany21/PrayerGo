<?php
require_once 'config.php';
require_once 'menu.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$alertType = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Semak jika input kosong (validasi mudah)
    if(empty($email) || empty($full_name) || empty($password) || empty($gender)){
        $message = "Please fill in all fields.";
        $alertType = "alert-danger";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admin (email, password, gender, full_name) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$email, $password, $gender, $full_name]);
            $message = "New admin registered successfully!";
            $alertType = "alert-success";
            
            
        } catch (PDOException $e) {
            $message = "Email already exists or database error.";
            $alertType = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Admin Panel</a>
    <div class="d-flex text-white align-items-center">
        <span class="me-3">Hi, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?></span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Add New Admin</h5>
                </div>
                <div class="card-body">

                    <?php if ($message): ?>
                        <div class="alert <?= $alertType ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input class="form-control" name="full_name" placeholder="e.g. Ahmad Ali" required 
                                   value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input class="form-control" name="email" type="email" placeholder="name@example.com" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input class="form-control" name="password" type="password" placeholder="Enter secure password" required>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="admin_list.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg"></i> Create Admin
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>